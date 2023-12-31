<?php

require __DIR__ . "/Support/Config.php"; //Sempre traz o arquivo de configuracoes

require __DIR__ . "/Support/Helpers.php"; //Arquivo com funcoes uteis para os outros codigos


/**
 * AUTOLOADER SEM COMPOSER VER SE O PROJETO USA ESSE OU O DO VENDOR DO COMPOSER
 */

spl_autoload_register(function($class){
    $diretorio = __DIR__ . "/";
    $prefixo = "Source\\"; //O barra barra e um caractere de escape
    
    $tamanho = strlen($prefixo);
    
    //Compara o namespace da classe de entrada com o prefixo atribuido em ate n = tamanho caracteres
    
    if(strncmp($prefixo,$class,$tamanho) !== 0){
        return; //Se for de um source diferente ele nao faz nada
    }
    
    //Retirando o "Source\" do nome ja que tamanho eh so o prefixo
    $relativa = substr($class,$tamanho);
    
    $file = $diretorio . str_replace("\\","/",$relativa) . ".php";
    //var_dump($file);
    
    if(file_exists($file)){
        require $file;
    }
});
?>