<?php
namespace Source\Core;

//Isso e um componente externo que deve vir pelo Composer
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception as MailerException;

/**
 * Esta classe visa manter o desacoplamento entre a aplicacao e o componente PHPMailer
 * @author frest
 * @package Source\Core
 */
class Email
{
    /** @var array */
    private $dados;
    
    /** @var PHPMailer */
    private $mailer;
    
    /** @var Message */
    private $message;
    
    public function __construct()
    {
        //O true como parametro permite o retorno de excecoes e seus textos
        $this->mailer = new PHPMailer(true);
        $this->message = new Message();
        
        $this->mailer->isSMTP();
        $this->mailer->setLanguage(CONF_MAIL_LANGUAGE);
        $this->mailer->isHTML(CONF_MAIL_ISHTML);
        $this->mailer->SMTPAuth = CONF_MAIL_SMTP_AUTH;
        $this->mailer->SMTPSecure = CONF_MAIL_SMTP_SECURE;
        $this->mailer->CharSet = CONF_MAIL_CHARSET;
        
        $this->mailer->Host = CONF_MAIL_HOST;
        $this->mailer->Port = CONF_MAIL_PORT;
        $this->mailer->Username = CONF_MAIL_USER;
        $this->mailer->Password = CONF_MAIL_PASS;
        
    }
    
    /**
     * Faz o inicio dos dados do objeto Email o retornando para mante-lo como Registro Ativo
     * @param string $assunto
     * @param string $message
     * @param string $toEmail
     * @param string $toName
     * @return Email
     */
    public function bootstrap(string $assunto, string $message, string $toEmail, string $toName): Email
    {
        $this->data = new \StdClass();
        
        $this->data->assunto = $assunto;
        $this->data->message = $message;
        $this->data->toEmail = $toEmail;
        $this->data->toName = $toName;
        
        return $this;
    }
    
    /**
     * ANEXOS DO EMAIL SAO ADICIONADOS EM ARRAY NO ATRIBUTO DATA
     * @param string $filePath
     * @param string $fileName
     * @return Email
     */
    public function attach(string $filePath, string $fileName): Email
    {
        $this->data->attached[$filePath] = $fileName;
        return $this; //Garante registro ativo do Email
    }
    
    /**
     * Metodo responsavel por validar os dados e chamar os metodos do objeto PHPMailer
     * que atribuem e enviam o email ao servidor SMTP
     * @param string $deEmail
     * @param string $deNome
     * @return bool
     */
    public function send($deEmail = CONF_MAIL_SENDER['address'], $deNome = CONF_MAIL_SENDER['name']): bool
    {
        //VALIDACOES
        
        if(empty($this->data)){
            //Dados vazios indicam que bootstrap() nao foi executado
            $this->message->error("Erro ao enviar: dados vazios no objeto email");
            return false;
        }
        if(!is_email($this->data->toEmail)){
            $this->message->warning("Erro ao enviar: email inválido destino no objeto email");
            return false;
        }
        if(!is_email($deEmail)){
            $this->message->warning("Erro ao enviar: email inválido remetente no objeto email");
            return false;
        }
        
        try{
            $this->mailer->Subject = $this->data->assunto;
            $this->mailer->msgHTML($this->data->message);
            $this->mailer->addAddress($this->data->toEmail, $this->data->toNome);
            $this->mailer->setFrom($deEmail,$deNome);
            
            //Negocio de arquivos em anexo existir como array em data
            //Mas nao verifica tudo do arquivo antes de enviar
            if(!empty($this->data->attached)){
                foreach($this->data->attached as $caminho => $nomeArquivo){
                    $this->mailer->addAttachment($caminho, $nomeArquivo);
                }
            }
            
            $this->mailer->send();
            return true;
            
        }catch (MailerExcetion $excp){
            $this->message->error($excp->getMessage());
            return false;
        }
        
    }
    
    /**
     * Literalmente retorna o objeto PHPMail contido nesse objeto Email
     * @return PHPMail
     */
    public function mail(): PHPMail
    {
        return $this->mailer;
    }
    
    /**
     * Literalmente retorna o objeto Message contido nesse objeto Email
     * @return Message
     */
    public function message(): Message
    {
        return $this->message;
    }
    
}

