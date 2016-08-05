<?php

$mail = new PHPMailer;
$mail->isSMTP();
$mail->Host = $this->MailerConfig['Host'];
$mail->SMTPAuth = true;
$mail->Username = $this->MailerConfig['Username'];
$mail->Password = $this->MailerConfig['Password'] ;
$mail->SMTPSecure = $this->MailerConfig['SMTPSecure'];
$mail->Port = $this->MailerConfig['Port'];

$this->mail = $mail;
