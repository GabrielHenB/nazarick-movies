<?php

 // ARQUIVO COM FUNCOES UTILITARIAS PARA APLICACAO

 // ------------- VALIDATION FUNCTIONS -----------------------


function is_email(string $s): bool
{
    return filter_var($s,FILTER_VALIDATE_EMAIL);
}

function is_passwd(string $senha): bool
{
    if(password_get_info($senha)['algo']){
        //se tiver algoritmo sera diferente de zero e passa o if
        //aqui deve retornar pois um hash tem tamanho maior que CONF_PASSWD_MAX e falharia o teste abaixo
        //mas se a senha ja foi transformada em hash deve entrar no BD
        //entao o teste tem de ser true
        return true;
    }
    return (mb_strlen($senha) >= CONF_PASSWD_MIN && mb_strlen($senha) <= CONF_PASSWD_MAX) ? true : false;
}

// --------------------- PASSWORD FUNCTIONS --------------------------

/**
 * Gera um hash para a string fornecida
 * @param string $senha
 * @return string
 */
function gerar_password(string $senha): string
{
    return password_hash($senha, CONF_PASSWD_ALGO, CONF_PASSWD_OPTIONS);
}

/**
 * Realiza verificacoes em uma senha e hash retornando true se sim
 * @param string $senha
 * @param string $hashSenha
 * @return bool
 */
function verificar_password(string $senha, string $hashSenha): bool
{
    //Outras possiveis verificaoes aqui
    return password_verify($senha,$hashSenha);
}

/**
 * Compara com um hash se deve ser rehash
 * @param string $hashSenha
 * @return bool
 */
function passwd_rehash(string $hashSenha): bool
{
    return password_needs_rehash($hashSenha, CONF_PASSWD_ALGO, CONF_PASSWD_OPTIONS);
}

// -------------- CSRF HANDLERS  ------------------------

/**
 * Gera uma tag input hidden com valor igual ao token csrf da session se existir
 * @return string
 */
function csrf_input(): string
{
    //Gera nova codificacao para o token
    session()->csrf();
    //Input cujo valor eh o token ou se nao existir eh "" pelo operador null coalescence ??
    return "<input type='hidden' name='csrf' value='".(session()->csrftoken ?? "")."' />";
}

function csrf_verify($request): bool
{
    if(empty(session()->csrftoken) || empty($request['csrf']) || $request['csrf'] != session()->csrftoken){
        return false; //Nao eh valido o token
    }else{
        return true; //EH valido
    }
}

// ------------- STRING FUNCTIONS -----------------------

/**
 * 
 * @param string $s
 * @return string
 */
function str_slug(string $s): string
{
    //Sera transformado em url entao precisa filtrar
    //O mb_str para minusculas e levando em conta sinais
    $s = filter_var(mb_strtolower($s), FILTER_SANITIZE_STRIPPED);
    $formats = 'ÀÁÂÃÄÅÆÇÈÉÊËÌÍÎÏÐÑÒÓÔÕÖØÙÚÛÜüÝÞßàáâãäåæçèéêëìíîïðñòóôõöøùúûýýþÿRr"!@#$%&*()_-+={[}]/?;:.,\\\'<>°ºª';
    $replace = 'aaaaaaaceeeeiiiidnoooooouuuuuybsaaaaaaaceeeeiiiidnoooooouuuyybyRr                                 ';
    
    $slug = str_replace(['-----','----','---','--'],'-',
        str_replace(' ','-',
        trim(strtr(utf8_decode($s),utf8_decode($formats), $replace))));
    
    //$pattern = '/[^a-zA-Z0-9\-._~:\/?#\[\]@!$&\'()*+,;=%]/';
    //$s = preg_replace($pattern,'-', utf8_decode($s));
    
    
    return $slug;
}

function str_studly_case(string $s): string
{
    $s = str_slug($s);
    //troca - por espaco e transforma em studly case
    $studlyCase = str_replace(' ','',mb_convert_case(str_replace('-',' ',$s),MB_CASE_TITLE));
    return $studlyCase;
}

function str_camel_case(string $s): string
{
    //usa a funcao de studly com minusculo no 1
    return lcfirst(str_studly_case($s));
    
}

function str_title(string $s): string
{
    //$s = htmlspecialchars($s);
    return mb_convert_case(filter_var($s,FILTER_SANITIZE_SPECIAL_CHARS),MB_CASE_TITLE);
}

function str_limit_words(string $s, int $limit, string $pointer = "..."): string
{
    $s = trim(filter_var($s,FILTER_SANITIZE_SPECIAL_CHARS));
    $palavras = explode(" ", $s);
    $numeroPalavras = count($palavras);
    
    if($numeroPalavras < $limit){
        return $s;
    }
    //Cria novo string de um offset 0 ate limite no array palavras
    $palavras = implode(" ", array_slice($palavras,0,$limit));
    return "{$palavras}{$pointer}";
    
}

function str_limit_chars(string $s, int $limit, string $pointer = "..."): string
{
    $s = trim(filter_var($s,FILTER_SANITIZE_SPECIAL_CHARS));
    if(mb_strlen($s) <= $limit){
        //Se numero de caracteres menor ou igual ao limite
        return $s;
    }
    //Para evitar que corte uma palavra ele descobre o espaco anterior ao ultimo
    $caracteres = mb_substr($s,0,mb_strrpos(mb_substr($s, 0, $limit), " "));
    return "{$caracteres}{$pointer}";
}

// ------------- NAVIGATION FUNCTIONS -----------------------

/**
 * @param string $url
 * @return string
 */
function url(string $caminho): string
{
    return CONF_URL_BASE . "/" . ($caminho[0]=="/" ? mb_substr($caminho,1) : $caminho);
}

/**
 * Redireciona e da um exit
 * @param string $url
 */
function redirect(string $url): void
{
    header("HTTP/1.1 302 Redirect");
    if(filter_var($url,FILTER_VALIDATE_URL)){
        //Eh uma URL valida e contem o http
        header("Location: {$url}");
        exit;
    }
    $location = url($url);
    header("Location: {$location}");
    exit;
}

// -------------        URL        -----------------------

/**
 * Retorna DateTime formatado d/m/Y Hhi
 * @param string $data
 * @param string $formato
 * @return string
 */
function formatar_data(string $data = "now", string $formato = "d/m/Y H\hi"): string
{
    return (new DateTime($data))->format($formato);
}


/**
 * Retorna DateTime formatado de acordo com constante em config.php
 * @param string $data
 * @return string
 */
function formatar_data_brazuca(string $data = "now"): string
{
    return (new DateTime($data))->format(CONF_DATE_BR);
}


/**
 * Retorna DateTime formatado de acordo com constante em config.php
 * @param string $data
 * @return string
 */
function formatar_data_app(string $data = "now"): string
{
    return (new DateTime($data))->format(CONF_DATE_APP);
}

// ------------- TRIGGER FUNCTIONS -----------------------

/**
 * Simplesmente retorna um getInstance de Connection
 * @return PDO
 */
function db(): PDO
{
    return \Source\Core\Connection::getInstance();
}

/**
 * Simplesmente retorna um Message construido padrao
 * @return \Source\Core\Message
 */
function message(): \Source\Core\Message
{
    return new \Source\Core\Message();
}

/**
 * Simplesmente retorna um Session construido padrao
 * @return \Source\Core\Session
 */
function session(): \Source\Core\Session
{
    return new \Source\Core\Session();
}

/**
 * Simplemente retorna um Model User construido padrao
 * @return \Source\Models\User
 */
function user(): \Source\Models\User
{
    return new \Source\Models\User();
}