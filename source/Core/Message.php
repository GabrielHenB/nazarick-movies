<?php
namespace Source\Core;

/**
 * Class Message
 * @author frest
 * @package Source\Core
 */
class Message
{
    //Stateful com propriedades e manipuladores
    private $text;
    private $type;
    
    public function __toString(): string
    {
        return $this->render();
    }
    
    /**
     * @return string
     */
    public function getText()
    {
        return $this->text;
    }

    /**
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }
    
    //Cada metodo alimenta as propriedades
    
    public function success(string $mensagem): Message
    {
        $this->type = CONF_MESSAGE_SUCCESS;
        $this->text = $this->filter($mensagem);
        return $this;
    }
    public function info(string $mensagem): Message
    {
        $this->type = CONF_MESSAGE_INFO;
        $this->text = $this->filter($mensagem);
        return $this;
    }
    public function warning(string $mensagem): Message
    {
        $this->type = CONF_MESSAGE_WARNING;
        $this->text = $this->filter($mensagem);
        return $this;
    }
    public function error(string $mensagem): Message
    {
        $this->type = CONF_MESSAGE_ERROR;
        $this->text = $this->filter($mensagem);
        return $this;
    }
    
    // Metodo renderiza na view a mensagem
    
    /**
     * Envia a View da mensagem como um div html
     * @return string
     */
    public function render(): string
    {
        return "<div class='" . CONF_MESSAGE_CLASS . " {$this->getType()} '>{$this->getText()}</div>";
    }
    
    /**
     * Transforma a mensagem em um JSON categoria error para todos os tipos e retorna em string
     * @return string
     */
    public function json(): string
    {
        return json_encode(["error" => $this->getText()]);
    }
    
    // Utilizado para guardar a mensagem dentro de uma sessao
    
    /**
     * Seta o campo flash da sessao atual
     */
    public function flash(): void
    {
        //Coloca na sessao atual um campo flash que guarda esse objeto
        (new Session())->set("flash", $this);
    }
    
    /**
     * Filtra por caracteres especiais e retorna a string
     * @param string $m
     * @return string
     */
    private function filter(string $m): string
    {
        return filter_var($m,FILTER_SANITIZE_SPECIAL_CHARS);
    }
    
}

