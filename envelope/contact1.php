<?php
include './PHPMailer.php';
include './SMTP.php';
include './POP3.php';
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;


// Instantiation and passing `true` enables exceptions
$mail = new PHPMailer(true);

try {
    //Server settings
    $mail->SMTPDebug = SMTP::DEBUG_SERVER;                      // Enable verbose debug output
    $mail->isSMTP();                                            // Send using SMTP
    $mail->SMTPAuth = true;                                   // Enable SMTP authentication
    $mail->Host = 'ssl://smtp.gmail.com';
    $mail->Port = 465;
    $mail->Username = 'username';
    $mail->Password = 'pass';
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;         // Enable TLS encryption; `PHPMailer::ENCRYPTION_SMTPS` encouraged
    $mail->SMTPDebug = 0;
    $mail->CharSet = "utf-8";
    //Recipients
    $mail->setFrom('mailer@gmail.com', 'landing');
    $mail->addAddress('mail@mail.ru');     // Add a recipient
//    $mail->addReplyTo('info@example.com', 'Information');
//    $mail->addCC('cc@example.com');
//    $mail->addBCC('bcc@example.com');


    $mail->Subject = 'Запрос для связи с сайта';
    $name = $_POST['name'];
    $email = $_POST['email'];
    $tel = $_POST['tel'];
    $mail->Body = "Имя контактного лица: $name \nТелефон: $tel \nemail:$email";

    $mail->send();
    print (json_encode(['result'=>'ok']));
} catch (Exception $e) {
    print (json_encode(['result'=>'bad', 'text' => $e->getMessage()]));
}
?>