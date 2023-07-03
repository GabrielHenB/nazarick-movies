<?php
/***
 * ARQUIVO DE CONSTANTES GLOBAIS DO PROJETO
 */

/***
 * DATABASE (APENAS TESTES NAO DEPLOY)
 */
 define("CONF_DB_HOST","localhost");
 define("CONF_DB_USER","root");
 define("CONF_DB_PASS","mysqlpassword"); //placeholder
 define("CONF_DB_NAME","nazamovieudb"); //placeholder
 
 
 /***
  * URLS
  */
 
 //Sem barra no final para que todas outras adicionem a barra antes
 define("CONF_URL_BASE","https://www.localhost/diwsegundatempphp");
 define("CONF_URL_ADMIN", CONF_URL_BASE . "/admin");
 define("CONF_URL_ERROR", CONF_URL_BASE . "/error");
 
 /***
  * DATAS
  */
 define("CONF_DATE_BR", "d/m/Y H:i:s");
 define("CONF_DATE_APP", "Y-m-d H:i:s");
 
 /**
  * SENHAS
  */
 define("CONF_PASSWD_MIN", 8);
 define("CONF_PASSWD_MAX", 40);
 define("CONF_PASSWD_ALGO", PASSWORD_DEFAULT);
 define("CONF_PASSWD_OPTIONS", ["cost" => 10]);

 /***
  * SESSION
  */
 define("CONF_SES_PATH",__DIR__."/../../storage/sessions/");
 
 /**
  * MESSAGE
  */
 define("CONF_MESSAGE_CLASS","mensagem");
 define("CONF_MESSAGE_INFO","info");
 define("CONF_MESSAGE_SUCCESS","sucesso");
 define("CONF_MESSAGE_WARNING","aviso");
 define("CONF_MESSAGE_ERROR","error");
 
 /**
  * EMAIL SERVICE (NAO UTILIZADO)
  */
 define("CONF_MAIL_HOST","smtp.sendgrid.net");
 define("CONF_MAIL_PORT","587"); //TLS
 define("CONF_MAIL_USER","apikey");
 define("CONF_MAIL_PASS",""); //aqui ficaria a chave da API
 define("CONF_MAIL_SENDER",["name" => "Replicael", "address" => "bntcodtest@gmail.com"]);
 
 define("CONF_MAIL_LANGUAGE","br"); //Linguagem para os erros e mensagens
 define("CONF_MAIL_ISHTML",true); //O email sera enviado como HTML
 define("CONF_MAIL_SMTP_AUTH",true);
 define("CONF_MAIL_SMTP_SECURE","tls");
 define("CONF_MAIL_CHARSET","utf-8");
 