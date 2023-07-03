<?php
namespace Source\Core;

use League\Plates\Engine;

/**
 * Classe que abstrai o componente Plates da League e
 * constitui uma View para o projeto usando os metodos da componente
 * mas deixando em baixo acoplamento com ela
 * @author frest
 * @package Source\Core
 */
class View
{
    private $engine;
    
    public function __construct(string $path = CONF_VIEW_PATH, string $extension = CONF_VIEW_EXTENSION){
        $this->engine = Engine::create($path,$extension);
    }
    
    /**
     * Adiciona folder ao league plates
     * @param string $folderName
     * @param string $folderPath
     * @return View
     */
    public function path(string $folderName, string $folderPath): View
    {
        //Adiciona folder ao league plates
        $this->engine->addFolder($folderName, $folderPath);
        return $this;
    }
    
    /**
     * Retorna uma string do metodo render componente
     * @param string $templateName
     * @param array $dados
     * @return string
     */
    public function render(string $templateName, array $dados): string
    {
        return $this->engine->render($templateName,$dados);
    }
    
    /**
     * Retorna o objeto Engine da componente atraves do metodo engine() da mesma
     * @return Engine
     */
    public function engine(): Engine
    {
        return $this->engine();
    }
}

