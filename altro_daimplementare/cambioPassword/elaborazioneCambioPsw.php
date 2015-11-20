 <?php
include_once($_SERVER['DOCUMENT_ROOT']."/config.php");
include_once(DB_CLASS);


if(isset($_REQUEST['user']) && $_REQUEST['user'] != "")
    $username = strtoupper($_REQUEST['user']);
else
    echo("Non hai inserito username");
    
    
if(isset($_REQUEST['oldpsw']) && $_REQUEST['oldpsw'] != "")
    $oldPass = strtoupper($_REQUEST['oldpsw']);
else
    die("Non hai inserito la vecchia password");
    
    
if(isset($_REQUEST['newpass']) && $_REQUEST['newpass'] != "")
    $newPass = strtoupper($_REQUEST['newpass']);
else
    die("Non hai inserito la nuova password");
    
if(isset($_REQUEST['renewpass']) && $_REQUEST['renewpass'] != "")
    $renewPass = strtoupper($_REQUEST['renewpass']);
else
    die("Non hai re-inserito la nuova password");
    
$db = new db_class();
if($db->validaLogin($username, $oldPass))
{
    if($newPass == $renewPass)
    {
        $db->cambiaPasswordAccount($username, $oldPass, $newPass);
    }
    else
    {
        echo "Password ripetute non uguali";
    }
}

?>