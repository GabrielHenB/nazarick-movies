<?php
namespace Source\Core;


//Layer supertype
//Define um modelo para as regras de negocio
//Se comunica com o BD
//Outras subclasses usam seus servicos

abstract class Model
{
    /** @var object|null */
    protected $data;
    
    /** @var \PDOException|null */
    protected $fail;
    
    /** @var Message|null */
    protected $message;
    
    /**
     * Model Constructor.
     */
    public function __construct(){
        //Agora existe uma classe dedicada as mensagens e ela aqui
        $this->message = new Message();
    }
    
    //Atributos obtidos do BD precisam ser protegidos
    //Mas ainda e possivel alterar e se save() deve persistir no BD tambem
    public function __set($name,$value)
    {
        if(empty($this->data)){
            $this->data = new \stdClass();
        }
        $this->data->$name = $value;
        //Guarda em data quaisquer campos que tente acessar e nao existam como propriedades da classe
    }
    
    public function __isset($name){
        return isset($this->data->$name);
    }
    
    public function __get($name){
        return ($this->data->$name ?? null);
    }
    
    /**
     * @return null|object
     */
    public function data(): ?object
    {
        return $this->data;
    }

    /**
     * @return \PDOException|NULL
     */
    public function fail(): ?\PDOException
    {
        return $this->fail;
    }

    /**
     * @return Message|NULL
     */
    public function message(): ?Message
    {
        return $this->message;
    }

    /**
     * create: insere em uma tabela um array de chave-valor
     * @param string $tabela
     * @param array $data
     * @return int|NULL
     */
    protected function create(string $tabela, array $data): ?int
    {
        try{
            //Usa as chaves do array em uma string separada de acordo com a sintaxe sql
            $colunas = implode(", ", array_keys($data));
            
            $valores = ':' . implode(", :", array_keys($data));
            $stmt = Connection::getInstance()->prepare("INSERT INTO {$tabela} ({$colunas}) VALUES ({$valores})");
            
            //Nesse caso ele aplica o param_str ao binding nas strings
            $stmt->execute($this->filter($data));
            
            return Connection::getInstance()->lastInsertId();
        }catch(\PDOException $exception){
            //Exporta os erros para a variavel fail
            $this->fail = $exception;
            return null;
        }
    }
    
    /**
     * read: Le um query e seus parametros retornando um PDOStatement ou nulo
     * @param string $select
     * @param string $params
     * @return \PDOStatement|NULL
     */
    protected function read(string $select, string $params = null): ?\PDOStatement
    {
        try{
            $stmt = Connection::getInstance()->prepare($select);
            //Queremos filtrar com os params do pdo
            if($params){
                parse_str($params,$params); //transforma em array assoc de parametros chave-valor
                
                foreach($params as $key => $value){
                    //O is_numeric ou is_int falharia para um float gerando inconsistencia no bd
                    //$tipoFil = (is_numeric($value) ? \PDO::PARAM_INT : \PDO::PARAM_STR);
                    
                    //Usa palavras reservadas
                    if($key == 'limit' || $key == 'offset'){
                        //Palavras que em uma consulta tem valor inteiro
                        $stmt->bindValue(":{$key}", $value, \PDO::PARAM_INT); 
                        //a chave o valor do parametro e a filtragem
                    }else{
                        $stmt->bindValue(":{$key}", $value, \PDO::PARAM_STR);
                    }
                    
                }
            }
            $stmt->execute();
            return $stmt;
        }catch(\PDOException $exception){
            //Exporta os erros para a variavel fail
            $this->fail = $exception;
            return null;
        }
    }
    
    
    protected function update(string $tabela, array $data, string $terms, string $params): ?int
    {
        try{
            $dataSet = [];
            foreach($data as $bind => $value){
                $dataSet[] = "{$bind} = :{$bind}";
            }
            $dataSet = implode(", ", $dataSet);
            parse_str($params,$params);
            
            $stmt = Connection::getInstance()->prepare("UPDATE {$tabela} SET {$dataSet} WHERE {$terms}");
            $stmt->execute($this->filter(array_merge($data,$params)));
            //Linhas afetadas ou um
            return ($stmt->rowCount() ?? 1);
        }catch(\PDOException $exception){
            //Exporta os erros para a variavel fail
            $this->fail = $exception;
            return null;
        }
    }
    
    /**
     * delete: deleta de uma tabela dependendo de string termos e string parametros
     * @param string $tabela
     * @param string $terms
     * @param string $params
     * @return int|NULL
     */
    protected function delete(string $tabela, string $terms, string $params): ?int
    {
        //Deleta a partir de termos e parametros, retorna quantas linhas afetadas ou nulo
        try{
            $stmt = Connection::getInstance()->prepare("DELETE FROM {$tabela} WHERE {$terms}");
            parse_str($params, $params); //transforma array em string de parametros
            $stmt->execute($params); //aplica a todos o params_str do pdo
            //Linhas afetadas ou um
            return ($stmt->rowCount() ?? 1);
        }catch(\PDOException $exception){
            //Exporta os erros para a variavel fail
            $this->fail = $exception;
            return null;
        }
    }
    
    protected function safe(): ?array
    {
        //Remove do array dados que nao possam ser atualizado pelas regras de negocio
        $safe = (array) $this->data; //Obtem propriedades armazenadas em data e transforma de objeto para array
        //O static pega algo estatico que nao esta no self
        foreach(static::$safe as $tirarIsso){
            unset($safe[$tirarIsso]);
        }
        return $safe;
    }
    
    protected function filter(array $data): ?array
    {
        $filtrado = [];
        foreach($data as $key => $value){
            $filtrado[$key] = (is_null($value) ? null : filter_var($value, FILTER_SANITIZE_SPECIAL_CHARS));
        }
        return $filtrado;
    }
    
    /**
     * Metodo que reflete as regras de negocio do bd verificando campos obrigatorios
     * @return bool
     */
    protected function required(): bool
    {
        $dados = (array)$this->data();
        //Verificar se algum dos campos de required() das subclasses existem em data
        foreach(static::$required as $campo){
            if(empty($dados[$campo])){
                //echo $campo;
                return false; //Nao contem algum campo requerido
            }
        }
        //Apos iterar todos os campos requeridos
        return true;
    }
    
}

