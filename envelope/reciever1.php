<?php

include './PHPMailer.php';
include './SMTP.php';
include './POP3.php';
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;
$source = file_get_contents('php://input');
$requestBody = json_decode($source, true);
$status = '';
if ($requestBody['event'] === 'payment.waiting_for_capture') {
    $status = 'Ожидает оплаты';
} else if ($requestBody['event'] === 'payment.succeeded') {
    $status = 'Прошел удачно';
}else if ($requestBody['event'] === 'payment.canceled') {
    $status = 'Отменен';
}else if ($requestBody['event'] === 'refund.succeeded') {
    $status = 'Возврат произведен';
} else {
    $status = 'Неизвестен';
}
$sum = $requestBody['object']['amount']['value'];
$desc = $requestBody['object']['description'];
$mail = new PHPMailer(true);

try {
    //Server settings
    $mail->SMTPDebug = SMTP::DEBUG_SERVER;                      // Enable verbose debug output
    $mail->isSMTP();                                            // Send using SMTP
    $mail->SMTPAuth = true;                                   // Enable SMTP authentication
    $mail->Host = 'ssl://smtp.gmail.com';
    $mail->Port = 465;
    $mail->Username = 'mailer@gmail.com';
    $mail->Password = 'pass';
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;         // Enable TLS encryption; `PHPMailer::ENCRYPTION_SMTPS` encouraged
    $mail->SMTPDebug = 0;
    $mail->setLanguage('ru');
    $mail->CharSet = "utf-8";
    //Recipients
    $mail->setFrom('mailer@gmail.com', 'landing');
    $mail->addAddress('mail@mail.ru');     // Add a recipient

    $mail->Subject = 'Запрос для связи с сайта';
    $mail->Body = "$desc\nСумма заказа:$sum\nСтатус кассы:$status\n";

    $mail->send();
} catch (Exception $e) {
    print (json_encode(['result'=>'bad', 'text' => $e->getMessage()]));
}