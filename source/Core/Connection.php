<?php
namespace Source\Core;
 
use \PDO;
use \PDOException;

/**
 * Class Connection
 * @package Source\Core
 * @author frest
 */
class Connection
{
    //As propriedades unicas ao objeto tentam garantir que nao haja mais de uma conexao por usuario
    //Singleton
    
    //O case garante que os dados venham com mesmo nome de coluna em cases iguais
    private const OPTIONS = [
        PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8",
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_OBJ,
        PDO::ATTR_CASE => PDO::CASE_NATURAL
    ];
    
    //Guarda o objeto PDO usado
    private static $instance;
    
    /**
     * @return PDO
     */
    public static function getInstance(): PDO
    {
        if(empty(self::$instance)){
            try{
                self::$instance = new PDO(
                    "mysql:host=" . CONF_DB_HOST . ";dbname=" . CONF_DB_NAME,
                    CONF_DB_USER,
                    CONF_DB_PASS,
                    self::OPTIONS
                    );
            }catch(PDOException $exception){
                die("ERRO DO BANCO DE DADOS");
            }
        }
        return self::$instance;
    }

    //Queremos so uma instancia desse objeto entao o final private impede que sejam executadas
    
    /***
     * Connection constructor.
     */
    final private function __construct(){
        
    }
    /***
     * Connection clone.
     */
    final private function __clone(){
        
    }
    
    
}

