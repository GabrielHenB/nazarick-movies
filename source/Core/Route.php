<?php
namespace Source\Core;

class Route
{
    protected static $route;

    public static function get(string $route, $handler)
    {
        //Recebe um nome de rota e um handler que corresponde a um nome de classe ou uma closure
        //Diz respeito a como tratar a recpcao de cada rota
        
        //Recebe o GET, sempre concatenando / antes da URL recebida e filtrando impedir ataques url
        $get = "/" .  filter_input(INPUT_GET,"url", FILTER_SANITIZE_SPECIAL_CHARS);
        
        //Atualiza a route com o que chegar no metodo o separando em classe e metodo a ser executado
        //Assim em cada rota um metodo diferente do Controller fornecido eh chamado
        //Em cada chave o valor eh um array com o nome route, o nome do controller ou sua Closure se for
        //E um metodo que eh separado por um caractere sendo : ou @ ou qualquer outro....
        self::$route = [
            $route => [
                "route" => $route,
                "controller" => (!is_string($handler) ? $handler : strstr($handler, ":", true) ),
                "method" => (!is_string($handler) ? : str_replace(":","",strstr($handler, ":", false)))
            ]
        ];
        //var_dump(self::$route);
        //Envia como parametro o GET da URL.
        self::dispatch($get);
    }
    
    public static function dispatch($route): void
    {
        //Se existe essa rota que foi obtida como parametro no metodo
        $route = (self::$route[$route] ?? []);
        
        if($route){
            
            if($route['controller'] instanceof \Closure){
                //Executa o metodo callback contido aqui
                call_user_func($route['controller']);
                return;
            }
            //Se nao for uma closure executar isso
            
            $controller = self::namespace() . $route['controller'];
            $method = $route['method'];
            
            var_dump($controller,$method);
            
            //Instancia o Controller especifico e chama o metodo do mesmo
            if(class_exists($controller)){
                $newContro = new $controller;
                if(method_exists($controller, $method)){
                    $newContro->$method();
                }
            }
        }
        
        
    }
    
    public static function get_routes()
    {
        return self::$route;
    }
    
    /**
     * Retorna o Namespace dos controladores ou handlers de rotas
     * isso deveria receber algum parametro e possivelmente ser alteravel
     * mas esta assim como
     * testes
     * @return string
     */
    private static function namespace(): string
    {
        return "Source\App\Controllers\\";
    }
    
}

