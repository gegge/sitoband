 <?php
include_once($_SERVER['DOCUMENT_ROOT']."/config.php");

$data = $_REQUEST['data'];
$arr = explode("|", $data);
$username = $arr[0];
$oldPass = $arr[1];
$newPass = $arr[2];

if($dbMGR->validaLogin($username, $oldPass))
{
    $dbMGR->cambiaPasswordAccount($username, $oldPass, $newPass);
}

?>