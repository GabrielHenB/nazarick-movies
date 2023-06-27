<?php
namespace Source\Core;

/**
 * Class Session
 * Manipula a sessao como um objeto
 * @package Source\Core
 * @author frest
 *
 */
class Session
{
    /**
     * Session constructor.
     */
    public function __construct()
    {
        if(!session_id()){
            session_save_path(CONF_SES_PATH);
            session_start();
        }
    }
    
    /**
     * @param  $name
     * @return mixed|NULL
     */
    public function __get($name)
    {
        if(!empty($_SESSION[$name])){
            return $_SESSION[$name];
        }
        
        return null;
    }
    
    /**
     * @param $name
     * @return bool
     */
    public function __isset($name): bool
    {
        return $this->has($name);
    }
    
    /**
     * Retorna o _SESSION como cast object ou se nao houver null
     * @return object|NULL
     */
    public function all(): ?object
    {
        return (object)$_SESSION;
    }
    
    /**
     * Seta um valor na posicao chave, se for array sera convertido em objeto
     * @param string $key
     * @param mixed $value
     * @return \Source\Core\Session
     */
    public function set(string $key, $value): Session
    {
        $_SESSION[$key] = (is_array($value)) ? (object)$value : $value;
        return $this;
    }
    
    public function unset(string $key): Session
    {
        unset($_SESSION[$key]);
        return $this;
    }
    
    public function has(string $key): bool
    {
        return isset($_SESSION[$key]);
    }
    
    public function regenerate(): Session
    {
        session_regenerate_id(true);
        return $this;
    }
    
    public function destroy(): Session
    {
        session_destroy();
        return $this;
    }
    
    public function flash(): ?Message
    {
        if($this->has('flash')){
            $flash = $this->flash;
            $this->unset("flash");
            return $flash;
        }
        return null;
    }
    
    /**
     * CSRF Token
     */
    public function csrf(): void
    {
        //Tenta impedir ataques CSRF validando o usuario e as requisicoes do mesmo
        //Utilizada nos handlers em helpers.php
        
        $_SESSION['csrftoken'] = base64_encode(random_bytes(20));
    }
}

