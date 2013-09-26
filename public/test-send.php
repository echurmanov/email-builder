<?php

require_once '../vendor/autoload.php';
require_once '_functions.php';
session_start();
$mailer = new PHPMailer();

$address = $_REQUEST['to'];

$mailer->From = 'test@webtricks.pro';
$mailer->FromName = 'Email Builder';
$mailer->addAddress($address, 'Получатель');  // Add a recipient
$mailer->addBCC('bcc@webtricks.pro');

$mailer->isHTML(true);
$mailer->CharSet = 'utf-8';

$mailer->addEmbeddedImage('images/blank.png', '100000');
$mailer->addEmbeddedImage('images/background-date.png', '100001');
$mailer->addEmbeddedImage('images/background-left.png', '100002');
$mailer->addEmbeddedImage('images/background-read.png', '100003');
$mailer->addEmbeddedImage('images/background-right.png', '100004');
$mailer->addEmbeddedImage('images/background-top.png', '100005');

$tempNames = array();
$cidS = 200000;
foreach ($_SESSION['items'] as $index => $itemData) {
    if (isset($itemData['imageIndex'])) {
        $tmp = tempnam('/tmp', 'img');
        file_put_contents($tmp, $_SESSION['images'][$itemData['imageIndex']]['image']['content']);
        $tempNames[] = $tmp;
        $cid = $cidS + $index;
        $mailer->addEmbeddedImage($tmp, $cid);
    }
}

$mailer->Body = getHtmlSendCode($cidS);
$mailer->Subject = 'Тестовое письмо ' . date('Y-m-d H:i:s');




if(!$mailer->send()) {
    echo 'Message could not be sent.';
    echo 'Mailer Error: ' . $mailer->ErrorInfo;
    exit;
}

foreach ($tempNames as $name) {
    unlink($name);
}