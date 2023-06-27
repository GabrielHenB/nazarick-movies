<?php
namespace Source\Models;
//Implementa a regra de negocio do modelo

use Source\Core\Model;

/**
 * Classe User responsavel por aplicar as regras de negocio de Model e enviar para o mesmo os dados
 * @author frest
 * @package Source\Models
 */
class User extends Model
{
    //Safe define os que nao podem ser manipulados no banco de dados
    //Como eh static ele eh da classe e nao da instancia
    
    /** @var array $safe no update no create */
    protected static $safe = ["id","created_at","updated_at"];
    
    //Entidade que o modelo ira usar, se refere a tabela
    
    /** @var string $entity database table */
    protected static $entity = "users";
    
    //Os requeridos refletem a regra de negocio do bd
    
    /** @var array $required table fields*/
    protected static $required = ["first_name","last_name","email","password"];
    
    /**
     * @param string $firstName
     * @param string $lastName
     * @param string $email
     * @param string $cpf
     * @return \Source\Models\User
     */
    public function bootstrap(string $firstName, string $lastName, string $email, string $password, string $cpf = null)
    {
        //Funcoes para inicio do usuario
        //permite que metodos com mesma assinatura sejam chamados pelo Model se existirem
        //logo as propriedades sao tratadas pelo protected data()
        //e todo trabalho e realizado no data tambem
        $this->first_name = $firstName; 
        $this->last_name = $lastName;
        $this->email = $email;
        $this->password = $password;
        $this->cpf = $cpf;
        return $this; //garante que o registro continue ativo
    }
    
    /**
     * Busca columns de entidade onde termos = parametros especificados
     * @param string $termos
     * @param string $parametros
     * @param string $columns
     * @return User|NULL
     */
    public function find(string $termos, string $parametros, string $columns = "*"): ?User
    {
        //Busca $columns de entidade onde $termos sejam = $parametros especificados
        $load = $this->read("SELECT {$columns} FROM " . self::$entity . " WHERE {$termos}", $parametros);
        //LOAD contem chamada de metodo do supertype Model que cuida da comunicacao com o bd
        if($this->fail() || !$load->rowCount()){
            //entao falhou ou nada
            return null;
        }
        //Sempre em active record
        return $load->fetchObject(__CLASS__);
    }
    
    /**
     * Faz uso do metodo find setando termo como id e parametro id.
     * @param int $id
     * @param string $columns
     * @return User|NULL
     */
    public function findById(int $id, string $columns = "*"): ?User
    {
        //Busca pelo id e um numero de colunas cujo padrao sao todas
        return $this->find("id = :id", "id={$id}", $columns);
    }
    
    /**
     * Faz uso do metodo find setando termo como email e parametro email.
     * @param string $email
     * @param string $columns
     * @return User|NULL
     */
    public function findByEmail(string $email, string $columns = "*"): ?User
    {
        //Busca pelo email e um numero de colunas cujo padrao sao todas
        return $this->find("email = :email", "email={$email}", $columns);
    }
    
    /**
     * Busca todos e retorna null ou array de objetos
     * @param number $limit
     * @param number $offset
     * @param string $columns
     * @return array|NULL
     */
    public function all($limit = 20, $offset = 0, string $columns = "*"): ?array
    {
        //Busca todos e retorna null ou array de objetos
        //limit e offset sao reservadas no Model e devem estar escritas em minusculo igual especificado
        $all = $this->read("SELECT {$columns} FROM " . self::$entity . " LIMIT :limit OFFSET :offset", "limit={$limit}&offset={$offset}");
        //LOAD contem chamada de metodo do supertype Model que cuida da comunicacao com o bd
        
        if($this->fail() || !$all->rowCount()){
            //entao falhou ou nada
            //$this->message = "Nao houve retorno algum";
            return null;
        }
        return $all->fetchAll(\PDO::FETCH_CLASS, __CLASS__);
    }
    
    /**
     * Metodo que cria User se vazio this->id ou atualiza um User aplicando o safe() e required() do Model.
     * @return User|NULL
     */
    public function save(): ?User
    {
        //Responsavel pelo cadastro
        //Agora chama o required() da superclasse que verifica as regras
        if(!$this->required()){
            //Embora o controle dos outros seja passado para outra camada
            //aqui precisamos saber o que aconteceu em cada processamento
            $this->message->warning("NOME, SOBRENOME e EMAIL são campos obrigatórios!");
            return null;
        }
        //Aqui valida se o formato estiver certo atraves da funcao em Helpers.php
        if(!is_email($this->email)){
            $this->message->warning("E-mail de formato inválido");
            return null;
        }
        
        if(!is_passwd($this->password)){
            $minpwdlen = CONF_PASSWD_MIN;
            $maxpwdlen = CONF_PASSWD_MAX;
            $this->message->warning("A senha deve ter entre {$minpwdlen} e {$maxpwdlen} caracteres");
            return null;
        }else{
            //Aplica o Hash e devolve a senha
            $this->password = gerar_password($this->password);
        }
        
        /** User Update */
        if(!empty($this->id)){
            //ID setado entao o cadastro sera atualizado
            $ultimo_id = $this->id;
            //Busca se o email ja esta cadastrado em outro id
            if($this->find("email = :email AND id != :id","email={$this->email}&id={$ultimo_id}")){
                $this->message->warning("Email informado ja cadastrado");
                return null;
            }
            //Chama metodo update de Model para um id que seja igual ao id atual
            $this->update(self::$entity, $this->safe(), "id = :id", "id={$ultimo_id}");
            
            if($this->fail()){
                $this->message->error("Erro na atualizacao, verifique os dados");
                return null;
            }
            //$this->message="Dados atualizados";
            //Se deu certo o retorno nao foi null e nao eh necessario mensagem para saber
        }
        
        /** User Create */
        if(empty($this->id)){
            //ID nao setado entao o cadastro sera realizado
            
            //Testar se o email ja existe
            if($this->findByEmail($this->email)){
                $this->message->warning("O email ja existe");
                return null;
            }
            //Envia para o create um array com a entrada apos o safe() do Model ser aplicado
            $ultimo_id = $this->create(self::$entity, $this->safe());
            
            if($this->fail()){
                $this->message->error("Erros foram cometidos e o cadastro nao foi concluido!");
                return null;
            }
            //$this->message = "Cadastro bem-sucedido!!";
        }
        //Mantem o registro ativo persistindo com o que estiver no bd apos o create atualizando o data
        $this->data = ($this->findById($ultimo_id))->data();
        if(!$this->data) echo "Obs: O ID recem-inserido nao foi encontrado.\n";
        return $this; //Mantem o registro ativado tambem
    }
    
    /**
     * A verdadeira destruicao se existir propriedade id definida na instancia
     * @return User|NULL
     */
    public function destroy(): ?User
    {
        //Deletar usuario
        if(!empty($this->id)){
            $this->delete(self::$entity, "id = :id", "id={$this->id}");
        }
        if($this->fail()){
            $this->message->error("Nao foi possivel deletar");
            return null;
        }
        
        $this->data = null;
        //$this->message = "Removido sucesso";
        return $this;
        
    }
    
    /* Nao mais necessaria pois agora eh parte da superclasse Model 
    private function required(): bool
    {
        //Quais campos sao requeridos
        if(empty($this->first_name)||empty($this->last_name)||empty($this->email)){
            $this->message = "Sao necessarios o nome, sobrenome e email para cadastrar!!";
            return false;
        }
        
        if(!filter_var($this->email, FILTER_VALIDATE_EMAIL)){
            $this->message = "Email informado e invalido!";
            return false;
        }
        
        return true;
    }
    */
    
}

