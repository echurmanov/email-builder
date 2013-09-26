<?php
/**
 * Created by JetBrains PhpStorm.
 * User: eugene
 * Date: 26.09.13
 * Time: 11:44
 * To change this template use File | Settings | File Templates.
 */

session_start();

session_destroy();

echo json_encode(true);