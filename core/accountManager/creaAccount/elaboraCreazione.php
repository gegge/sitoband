<?php
include_once($_SERVER['DOCUMENT_ROOT']."/config.php");
$data = $_REQUEST['data'];
$arr = explode("|", $data);
$user = $arr[0];
$pass = $arr[1];
$email = $arr[2];
$acclvl = $arr[3];
$dbMGR->creaAccount(strtoupper($user), strtoupper(sha1(strtoupper($user).":".strtoupper($pass))), strtoupper($email), $acclvl);
?>