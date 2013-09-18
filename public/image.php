<?php
/**
 * Created by JetBrains PhpStorm.
 * User: eugene
 * Date: 18.09.13
 * Time: 12:55
 * To change this template use File | Settings | File Templates.
 */
session_start();

if (!array_key_exists("images", $_SESSION)) {
    $_SESSION['images'] = array();
}



if (isset($_REQUEST['image'])) {
    $id = $_REQUEST['image'];
    $data = $_SESSION['images'][$id];
    header("Content-Type: " . $data['type']);
    echo $data['image']['content'];
}



