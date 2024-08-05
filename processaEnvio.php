<?php
//importação das bibliotecas do PHPMailer para utilizar os serviços de envio e recebimento de e-mail
  require_once './lib/phpmailer/Exception.php';
  require_once './lib/phpmailer/OAuth.php';
  require_once './lib/phpmailer/PHPMailer.php';
  require_once './lib/phpmailer/POP3.php';
  require_once './lib/phpmailer/SMTP.php';

  use PHPMailer\PHPMailer\PHPMailer;
  use PHPMailer\PHPMailer\SMTP;
  use PHPMailer\PHPMailer\Exception;

//Criação da classe mensagem
  class Mensagem {
    private $para = null;
    private $assunto = null;
    private $mensagem = null;
    public function __construct($para, $assunto, $mensagem) {
      $this->para = $para;
      $this->assunto = $assunto;
      $this->mensagem = $mensagem;
    }

    public function __get($attr){
      return $this->$attr;
    }

    public function __set($attr, $value) {
      $this->$attr = $value;
    }

    public function mensagemValida(){
      if(empty($this->para) || empty($this->assunto) || empty($this->mensagem)){
        return false;
      } else {
        return true;
      }
    }
  }

  $mensagem = new Mensagem($_POST['para'], $_POST['assunto'], $_POST['mensagem']);
  if(!$mensagem->mensagemValida()){
    echo 'Mensagem inválida. Falta(m) um ou mais parâmetros';
    die();
  }

  //Create an instance; passing `true` enables exceptions
$mail = new PHPMailer(true);

try {
    //Server settings
    $mail->SMTPDebug  = SMTP::DEBUG_SERVER;                     //Enable verbose debug output
    $mail->isSMTP();                                            //Send using SMTP
    $mail->Host       = 'smtp.example.com';                     //Set the SMTP server to send through
    $mail->SMTPAuth   = true;                                   //Enable SMTP authentication
    $mail->Username   = 'user@example.com';                     //SMTP username
    $mail->Password   = 'secret';                               //SMTP password
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;            //Enable implicit TLS encryption
    $mail->Port       = 465;                                    //TCP port to connect to; use 587 if you have set `SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS`

    //Recipients
    $mail->setFrom('from@example.com', 'Mailer');
    $mail->addAddress('joe@example.net', 'Joe User');     //Add a recipient
    $mail->addAddress('ellen@example.com');               //Name is optional
    $mail->addReplyTo('info@example.com', 'Information');
    $mail->addCC('cc@example.com');
    $mail->addBCC('bcc@example.com');

    //Attachments
    $mail->addAttachment('/var/tmp/file.tar.gz');         //Add attachments
    $mail->addAttachment('/tmp/image.jpg', 'new.jpg');    //Optional name

    //Content
    $mail->isHTML(true);                                  //Set email format to HTML
    $mail->Subject = 'Here is the subject';
    $mail->Body    = 'This is the HTML message body <b>in bold!</b>';
    $mail->AltBody = 'This is the body in plain text for non-HTML mail clients';

    $mail->send();
    echo 'Message has been sent';
} catch (Exception $e) {
    echo "Não foi possível enviar este email! Por favor, tente mais tarde. Erro: {$mail->ErrorInfo}";
}