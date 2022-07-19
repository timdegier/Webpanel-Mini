<?php

function mailer($to,$subject,$body){
  $transport = (new Swift_SmtpTransport(SMTP_SERVER, SMTP_PORT, SMTP_SECURITY))->setUsername(SMTP_USER)->setPassword(SMTP_PASS);
  $mailer = new Swift_Mailer($transport);
  $message = (new Swift_Message($subject))
    ->setFrom([SMTP_USER => SMTP_NAME])
    ->setTo([$to])
    ->setBody($body, 'text/html')
    ;

  return $mailer->send($message);
}

function mailTemplate($template, $data = null){

  $file = 'templates/' . $template . '.php';

  ob_start();

  include $file;

  return ob_get_clean();

}

?>
