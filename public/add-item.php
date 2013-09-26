<?php
/**
 * Created by JetBrains PhpStorm.
 * User: eugene
 * Date: 18.09.13
 * Time: 12:55
 * To change this template use File | Settings | File Templates.
 */
session_start();

$maxImageWidth = 280;
$maxImageHeight = 320;


function resizeImage($fileName, $mimeType, $maxW, $maxH) {
    if (strpos(strtolower($mimeType), 'jpeg') !== false) {
        $image = imagecreatefromjpeg($fileName);
    }
    if (strpos(strtolower($mimeType), 'png') !== false) {
        $image = imagecreatefrompng($fileName);

    }
    if (!isset($image)) {
        throw new Exception("Unknown image type");
    }

    $currentW = imagesx($image);
    $currentH = imagesy($image);

    $aspectX = $maxW / $currentW;
    $aspectY = $maxH / $currentH;

    $scale = $aspectY;

    if ($aspectX < $aspectY) {
        $scale = $aspectX;
    }

    if ($scale < 1) {
        $newImage = imagecreatetruecolor($scale * $currentW, $scale * $currentH);
        $white = imagecolorallocatealpha($newImage, 255, 255, 255, 0);
        imagecolortransparent ( $newImage, $white);
        imagefill($newImage, 1, 1, $white);
        imagefilledrectangle($newImage, 0, 0, $scale * $currentW, $scale * $currentH, $white);
        imagecopyresized($newImage, $image, 0, 0, 0, 0, $scale * $currentW, $scale * $currentH, $currentW, $currentH );
    } else {
        $newImage = $image;
    }

    $tmpName = tempnam('/tmp', 'img');

    if (strpos(strtolower($mimeType), 'jpeg') !== false) {
        imagejpeg($newImage, $tmpName);
    }
    if (strpos(strtolower($mimeType), 'png') !== false) {
        imagesavealpha($newImage, true);
        imagepng($newImage, $tmpName);
    }


    $content = file_get_contents($tmpName);
    unlink($tmpName);

    return array(
        'content' => $content,
        'type' => $mimeType,
        'width' => imagesx($newImage),
        'height' => imagesy($newImage),
    );

}


function getHtmlCode()
{
    $html = <<<HTML
<table width="1407" cellpading=0 cellspacing=0 background="#d1bda4">
        <tr>
            <td colspan="2" style="background-image: url('images/background-top.png');
             height: 283px; color: #082755; font-weight: bold; padding-left: 170px;
             font-size: 20px; vertical-align: bottom; padding-bottom: 50px;"
            >
                Адрес редакции:<br/>
                Адрес: 111123, Москва, Электродный проезд, д. 6, оф. 14<br/>
                Тел./факс: +7 (495) 645-12-21<br/>
                Отдел подписки: +7 (495) 64-555-82<br/>
                Отдел подписки в Екатеринбурге: +7 (343) 383-27-88<br/>
                <big>Email: mail@eepr.ru</big>
            </td>
        </tr>

HTML;

    foreach ($_SESSION['items'] as $itemData) {
        if ($itemData['type'] != 'banner')
        $date = $itemData['date'];
        $date = explode('.', $date);
        $html .= <<<HTML
    <tr>
            <td width="220" style="background-image: url('images/background-left.png');
                font-weight: bold; color: #FFFFFF; vertical-align: top;
            ">
                <div style="background: url('images/background-date.png') no-repeat; margin: 5px 0 0px 100px; padding: 5px 25px 40px 40px">
                    {$date[0]}.{$date[1]}.<br/>
                    {$date[2]}
                </div>
            </td>
            <td style="background-image: url('images/background-right.png'); background-repeat:repeat-y; background-position: right;
                padding-left: 10px; padding-right: 140px;
                vertical-align: top;
            " >
                <span style="font-weight: bold; font-size: 20px; color: #F06129;">
                    {$itemData['title']}
                </span>
                <p style="font-weight: bold; color: #525B6C; font-size: 13px;">{$itemData['text']}</p>
                <div style="background: url('images/background-read.png') no-repeat right; text-align: right;
                    padding: 10px 15px 20px 0;
                    margin-right: -35px;
                ">
                    <a style="font-weight: bold; color: #FFFFFF; text-decoration: none;" href="{$itemData['link']}">Читать далее</a>
                </div>
            </td>
        </tr>
HTML;

    }

    $html .= '</table>';
    return $html;
}


if (!array_key_exists("items", $_SESSION)) {
    $_SESSION['items'] = array();
    $_SESSION['images'] = array();
    $_SESSION['blockId'] = 0;
}

header("Content-Type: application/json; charset=utf-8");

$result = array(
    'success' => true,
    'items' => $_SESSION['items'],
);

if (isset($_FILES['image'])) {
    error_log($_FILES['image']['error']);
    if ($_FILES['image']['error'] == 0 || $_FILES['image']['error'] == 4) {
        try {
            $_SESSION['blockId']++;
            $imageData = null;
            if ($_FILES['image']['error'] == 0) {
                if ($_REQUEST['type'] == 'banner') {
                    $maxImageWidth = 800;
                    $maxImageHeight = 1000;
                }
                $imageData = array(
                    'size' => $_FILES['image']['size'],
                    'type' => $_FILES['image']['type'],
                    'image' => resizeImage($_FILES['image']['tmp_name'], $_FILES['image']['type'], $maxImageWidth, $maxImageHeight),
                );
                $_SESSION['images'][$_SESSION['blockId']] = $imageData;
            }
            $itemData = array(
                'title' => $_REQUEST['header'],
                'link' => $_REQUEST['link'],
                'text' => $_REQUEST['text'],
                'date' => $_REQUEST['date'],
                'type' => $_REQUEST['type'],
            );
            if ($imageData) {

                $itemData['imageIndex'] = $_SESSION['blockId'];
                $itemData['imageWidth'] = $imageData['image']['width'];
                $itemData['imageHeight'] = $imageData['image']['height'];
            }
            $_SESSION['items'][$_SESSION['blockId']] = $itemData;
            $result = array(
                'success' => true,
                'code' => getHtmlCode(),
            );
        } catch (Exception $e) {
            $result['success'] = false;
            $result['message'] = 'FILE_PROCESS_ERROR';
        }
    } else {
        $result['success'] = false;
        $result['message'] = 'FILE_UPLOAD_ERROR';
    }
} else {
    $result['success'] = false;
    $result['message'] = 'NO_IMAGE';
}

echo json_encode($result);


