<?php

    use PHPMailer\PHPMailer\PHPMailer;
    use PHPMailer\PHPMailer\Exception;

    class Email {
        private $from = null;
        private $subject = null;
        private $message = null;
        public $status = array ('status_code' => null, 'code_description' => '');
    

    public function __get($atr){
        return $this->$atr ;
    }

    public function __set($atr, $valor){
        return $this->$atr = $valor;
    }

    public function validMessage(){
        if(empty($this->from) || empty($this->subject) || empty($this->message)){
            return false;
        }
        return true;
    }
}

    $email = new Email();

    $email->__set('from', $_POST['from']);
    $email->__set('subject', $_POST['subject']);
    $email->__set('message', $_POST['message']);

    if(!$email->validMessage()){
        echo 'Mensagem Invalida!Por favor verifique se preencheu todos os campos requeridos.';
        header('Location: index.php');
    }

    $mail = new PHPMailer(true);

    try {
        //Server settings
        $mail->SMTPDebug = false;                      //Enable verbose debug output
        $mail->isSMTP();                                            //Send using SMTP
        $mail->Host       = 'smtp.gmail.com';                     //Set the SMTP server to send through
        $mail->SMTPAuth   = true;                                   //Enable SMTP authentication
        $mail->Username   = 'seu_email@dominio.com';                     //SMTP username
        $mail->Password   = 'sua@pass';                               //SMTP password
        $mail->SMTPSecure = 'tls';         //Enable TLS encryption; `PHPMailer::ENCRYPTION_SMTPS` encouraged
        $mail->Port       = 587;                                    //TCP port to connect to, use 465 for `PHPMailer::ENCRYPTION_SMTPS` above

        //Recipients
        $mail->setFrom('email@dominio.com', 'Test Mailer Send');
        $mail->addAddress($email->__get('from'));     //Add a recipient

        //Content
        $mail->isHTML(true);                                  //Set email format to HTML
        $mail->Subject = $email->__get('subject');
        $mail->Body    = $email->__get('message');

        $mail->send();

        $email->status['status_code'] = 1;
        $email->status['code_description'] = 'Message has been sent successfull!';

    } catch (Exception $e) {

        $email->status['status_code'] = 2;
        $email->status['code_description'] = 'Não foi possível enviar o seu e-mail! Por favor, tente novamente mais tarde:'.$mail->ErrorInfo;
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>App Send Mail</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
</head>

<body>
    <div class="container ">
        <div class="py-3 text-center">
			<img class="d-block mx-auto mb-2" src="logo.png" alt="" width="72" height="72">
			<h2>Send Mail</h2>
			<p class="lead">Seu app de envio de e-mails particular!</p>
		</div>

        <div class="row">
            <div class="col-md-12 py-3 text-center">
                <? if($email->status['status_code'] == 1) { ?> 
                    <div class="container">
                        <h1 class="display-4 text-success"> Sucesso! </h1>
                        <p><?= $email->status['code_description'] ?> </p>
                        <a href="index.php" class="btn btn-success btn-lg mt-5 text-white">Voltar a página de início</a>
                    </div>
                <? } ?>

                <? if($email->status['status_code'] == 2) { ?> 
                    <div class="container">
                        <h1 class="display-4 text-danger"> Ops! Algo correu mal. </h1>
                        <p><?= $email->status['code_description'] ?> </p>
                        <a href="index.php" class="btn btn-danger btn-lg mt-5 text-white">Voltar a página de início</a>
                    </div>
                <? } ?>

            </div>
        </div>

    </div>
</body>

</html>