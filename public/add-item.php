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
                'items' => $_SESSION['items'],
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


