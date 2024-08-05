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
  public $status = array('codigo_status' => null, 'descricao_status' => null);
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
  header('Location: index.php');
}

//Create an instance; passing `true` enables exceptions
$mail = new PHPMailer(true);

try {
  //Server settings
  $mail->SMTPDebug  = false;    //SMTP::DEBUG_SERVER;                     //Enable verbose debug output
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
  $mensagem->status['codigo_status'] = 1;
  $mensagem->status['descricao_status'] = 'Mensagem Enviada!';
} catch (Exception $e) {
  $mensagem->status['codigo_status'] = 2;
  $mensagem->status['descricao_status'] = "Não foi possível enviar este email! Por favor, tente mais tarde. Erro: {$mail->ErrorInfo}";
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>App Send Mail</title>

  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
</head>
<body>
  <div class="container">  
			<div class="py-3 text-center">
				<img class="d-block mx-auto mb-2" src="logo.png" alt="" width="72" height="72">
				<h2>Send Mail</h2>
				<p class="lead">Seu app de envio de e-mails particular!</p>
			</div>

      <div class="row">
        <div class="col-md-12">
          <?php if($mensagem->status["codigo_status"] === 1) { ?>
            <div class="container">
              <h1 class="display-4 text-success">Sucesso!</h1>
              <p><?= $mensagem->status["descricao_status"]?></p>
              <a href="index.php" class="btn btn-lg btn-success">Voltar</a>
            </div>
          <?php } else if ($mensagem->status["codigo_status"] === 2) { ?>
            <div class="container">
              <h1 class="display-4 text-danger">Falha!</h1>
              <p><?= $mensagem->status["descricao_status"]?></p>
              <a href="index.php" class="btn btn-lg btn-danger">Voltar</a>
            </div>
          <?php } ?>
        </div>
      </div>
  </div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</body>
</html>
