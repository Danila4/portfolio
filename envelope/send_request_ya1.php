<?php
include './PHPMailer.php';
include './SMTP.php';
include './POP3.php';
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;
require  './yandex/lib/autoload.php';


// Instantiation and passing `true` enables exceptions
$mail = new PHPMailer(true);

$name = $_POST['name'];
$email = $_POST['email'];
$tel = $_POST['tel'];
$addr = $_POST['addr'];
$comment = $_POST['comment'];
$sum = $_POST['price'];
$payment_method = $_POST['payment'];
$delivery_method = $_POST['delivery'];
$items_list = $_POST['items'];
$ord_number = rand(1,10000000);
$error = [];
$url = false;
$urlAddr = '';
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
    $mail->CharSet = "utf-8";
    //Recipients
    $mail->setFrom('mailer@gmail.com', 'landing');
    $mail->addAddress('qweews@mail.ru');     // Add a recipient
//    $mail->addReplyTo('info@example.com', 'Information');
//    $mail->addCC('cc@example.com');
//    $mail->addBCC('bcc@example.com');
    $mail->Subject = 'Запрос для связи с сайта';
    $mail->Body = "Заказ №$ord_number\nИмя контактного лица: $name \nТелефон: $tel \nemail:$email\nАдрес доставки:$addr\nКомментарий к заказу:$comment\nСумма заказа:$sum\nСпособ оплаты:$payment_method\nСпособ доставки:$delivery_method\nКупленные товары:$items_list";

    $mail->send();
//    print (json_encode(['result'=>'ok']));
} catch (\Exception $e) {
    array_push($error, 'owner_communication');
//    print (json_encode(['result'=>'bad', 'text' => $e->getMessage()]));
}

include './yandex/lib/Client.php';
use YandexCheckout\Client;
if ($payment_method === 'Перевод с карты на карту') {
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
        $mail->CharSet = "utf-8";
        //Recipients
        $mail->setFrom('mailer@gmail.com', 'land');
        $mail->addAddress($email);     // Add a recipient
//    $mail->addReplyTo('info@example.com', 'Information');
//    $mail->addCC('cc@example.com');
//    $mail->addBCC('bcc@example.com');
        $mail->Subject = "Реквизиты для оплаты заказа №$ord_number";
        $mail->Body = "Заказ №$ord_number\nРеквизиты:\nИндивидуальный предприниматель Кравченко Алексей Владимирович\nНомер карты: 4276 3801 7054 9237\nКупленные товары:$items_list \nС наилучшими пожеланиями,\nАлексей Кравченко";

        $mail->send();
//    print (json_encode(['result'=>'ok']));
    } catch (\Exception $e) {
//        print (json_encode(['result'=>'bad', 'text' => $e->getMessage()]));
        array_push($error, 'client_communication');
    }
}
if ($payment_method === 'На р/с юридического лица') {
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
        $mail->CharSet = "utf-8";
        //Recipients
        $mail->setFrom('mailer@gmail.com', 'land');
        $mail->addAddress($email);     // Add a recipient
//    $mail->addReplyTo('info@example.com', 'Information');
//    $mail->addCC('cc@example.com');
//    $mail->addBCC('bcc@example.com');
        $mail->Subject = "Реквизиты для оплаты заказа №$ord_number";
        $mail->Body = "Заказ №$ord_number\nРеквизиты:\nИндивидуальный предприниматель Кравченко Алексей Владимирович\nИНН\n771406796798\nЮр. Адрес:\n127287, г. Москва, ул.Писцовая, 16-4, офис 122\n Банковские реквизиты в Московском банке Сбербанка России ОАО\nр/c:  40802810138050005361\n К/c: 30101810400000000225\n БИК: 044525225\nКупленные товары:$items_list \nС наилучшими пожеланиями,\nАлексей Кравченко";

        $mail->send();
//    print (json_encode(['result'=>'ok']));
    } catch (\Exception $e) {
//        print (json_encode(['result'=>'bad', 'text' => $e->getMessage()]));
        array_push($error, 'client_communication');
    }
}
if ($payment_method === 'Через Яндекс Кассу') {
    try {
        $client = new Client();
        $client->setAuth('id', 'key');
        $payment = $client->createPayment(
            array(
                'amount' => array(
                    'value' => number_format((float)$sum, 1, '.', ''),
                    'currency' => 'RUB',
                ),
                'confirmation' => array(
                    'type' => 'redirect',
                    'return_url' => 'site',
                ),
                'capture' => true,
                'description' => "Заказ №$ord_number",
            ),
            uniqid('', true)
        );
        if($payment) {
            if($payment->confirmation->confirmation_url) {
                $url = true;
                $urlAddr = $payment->confirmation->confirmation_url;
            }
        }
    } catch (\Throwable $e) {
//        print (json_encode(['result'=>'bad', 'text' => $e->getMessage()]));
        array_push($error, 'yandex');
        array_push($error, $e->getMessage());

    }
}

if (!empty($error)) {
    print (json_encode(['result'=>'bad', 'error' => $error]));
}else if ($url){
    print (json_encode(['result'=>'ok', 'url' => $urlAddr]));
}else{
    print (json_encode(['result'=>'ok']));
}

