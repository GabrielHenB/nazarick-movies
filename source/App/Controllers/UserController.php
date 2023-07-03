<?php
namespace Source\App\Controllers;

use Source\Core\Controller;
use Source\Core\View;

class UserController extends Controller
{
    //Layer controlador usuario, emprega o Model para fornecer os dados a View que gera o resultado
    //Isso herda os metodos da superclasse Controller
    
    
    private $template;
    
    public function __construct()
    {
        //Inicializa a View em folder fornecido
        $this->template = new View();
        $this->template->path("views","views");
    }
    
    public function home()
    {
        // CONTROLLER OBTEM INFO DA PAGINA ATUAL
        $atual = filter_input(INPUT_GET,"page", FILTER_VALIDATE_INT);
        $registros = \Source\Core\Connection::getInstance()->query("SELECT count(id) AS total FROM users")->fetch()->total;
        
        // CONTROLLER ACIONA O SISTEMA DE PAGINAS
        //$paginador = new \Source\Support\Paginador("?page=");
        //$paginador->pager($registros, 4, $atual, 2);
        
        // CONTROLLER PASSA INFO PARA VIEW RENDER
        /*echo $this->template->render("views::home", [
            "title" => "Nazarick Movies: Home",
            "list" => (new \Source\Models\User())->all($paginador->limit(),$paginador->offset()),
            "pager" => $paginador->render()
        ]);*/
	  echo $this->template->render("views:home",[]);
    }
    /*
    public function edit()
    {
        // CONTROLLER OBTEM INFO DA PAGINA ATUAL
        $atual = filter_input(INPUT_GET,"id", FILTER_VALIDATE_INT);
        $alvo = ($atual ? (new \Source\Models\User())->findById($atual) : null );
        
        //Como o retorno de find pode ser null isso deve ser checado
        if(!$alvo){
            //Seta na sessao que ocorreu um erro
            (new \Source\Core\Message())->error("Usuario nao encontrado")->flash();
            //Retorna
            header("Location: ./");
            exit;
        }
        //Aqui significa que o usuario foi encontrado
        echo $this->template->render("views::user", [
            "usuario" => $alvo
        ]);
    }*/
}

