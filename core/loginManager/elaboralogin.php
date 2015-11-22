<?php

include_once($_SERVER['DOCUMENT_ROOT']."/config.php");
$data = $_REQUEST['data'];
$arr = explode("|", $data);
$user = $arr[0];
$pass = $arr[1];
if($dbMGR->validaLogin($user, $pass))
{
    echo "ok";
    $_SESSION['userid'] = $dbMGR->getAccountId(strtoupper($user));
    $_SESSION['username'] = strtoupper($user);
}
?>