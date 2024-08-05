<?php
//importação das bibliotecas do PHPMailer para utilizar os serviços de envio e recebimento de e-mail
require_once './lib/phpmailer/Exception.php';
require_once './lib/phpmailer/PHPMailer.php';
require_once './lib/phpmailer/OAuthTokenProvider.php';
require_once './lib/phpmailer/POP3.php';
require_once './lib/phpmailer/SMTP.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

//Criação da classe mensagem
class Mensagem
{
  private $para = null;
  private $assunto = null;
  private $mensagem = null;
  public function __construct($para, $assunto, $mensagem)
  {
    $this->para = $para;
    $this->assunto = $assunto;
    $this->mensagem = $mensagem;
  }

  public function __get($attr)
  {
    return $this->$attr;
  }

  public function __set($attr, $value)
  {
    $this->$attr = $value;
  }

  public function mensagemValida()
  {
    if (empty($this->para) || empty($this->assunto) || empty($this->mensagem)) {
      return false;
    } else {
      return true;
    }
  }
}

$mensagem = new Mensagem($_POST['para'], $_POST['assunto'], $_POST['mensagem']);
if (!$mensagem->mensagemValida()) {
  echo 'Mensagem inválida. Falta(m) um ou mais parâmetros';
  die();
}

//Create an instance; passing `true` enables exceptions
$mail = new PHPMailer(true);

try {
  //Server settings
  $mail->SMTPDebug  = SMTP::DEBUG_SERVER;                     //Enable verbose debug output
  $mail->isSMTP();                                            //Send using SMTP
  $mail->Host       = 'smtp.gmail.com';                       //Set the SMTP server to send through
  $mail->SMTPAuth   = true;                                   //Enable SMTP authentication
  $mail->Username   = 'curso.web.completo.2024@gmail.com';    //SMTP username
  $mail->Password   = 'pwjp hmae cifw wtho';                  //SMTP password
  $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;            //Enable implicit TLS encryption
  $mail->Port       = 465;                                    //TCP port to connect to; use 587 if you have set `SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS`

  //Recipients
  $mail->setFrom('curso.web.completo.2024@gmail.com', 'Web Completo Remetente');          //Remetente
  $mail->addAddress($mensagem->__get('para'), 'Web Completo Destinatário');               //Destinatário
  //$mail->addAddress('ellen@example.com');                                               //Acrescentar mais um e-mail de destinatário (OPCIONAL)
  //$mail->addReplyTo('info@example.com', 'Information');                                 //E-mail de resposta padrão, quando existe uma outra pessoa no processo (OPCIONAL)
  //$mail->addCC('cc@example.com');                                                       //Acrescentar e-mail em cópia (OPCIONAL)
  //$mail->addBCC('bcc@example.com');                                                     //Acrescentar e-mail em cópia oculta (OPCIONAL)

  //Attachments
  //$mail->addAttachment('/var/tmp/file.tar.gz');         //Add attachments
  //$mail->addAttachment('/tmp/image.jpg', 'new.jpg');    //Optional name

  //Content

  $mail->isHTML(true);                                  //Set email format to HTML
  $mail->Subject = $mensagem->__get('assunto');         //Assunto do E-mail
  $mail->Body    = $mensagem->__get('mensagem');        //Corpo do e-mail no padrão HTML
  $mail->AltBody = $mensagem->__get('mensagem');        //Corpo do e-mail no padrão Não-HTML

  $mail->send();
  echo 'Mensagem Enviada!';
} catch (Exception $e) {
  echo "Não foi possível enviar este email! Por favor, tente mais tarde. Erro: {$mail->ErrorInfo}";
}
