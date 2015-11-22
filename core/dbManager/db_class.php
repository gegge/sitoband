<?php
include_once($_SERVER['DOCUMENT_ROOT']."/config.php");
class db_class
{
    private $conn;
    
    function __construct() {
        $this->conn = new mysqli(MYSQL_HOST, MYSQL_USERNAME, MYSQL_PASSWORD, MYSQL_DATABASE);
    }
    
    public function validaLogin($username, $password)
    {
        $username = strtoupper($username);
        $password = strtoupper($password);
        $sha_pass_hash = strtoupper(sha1($username.":".$password));
        $query = $this->conn->prepare("SELECT * FROM accounts WHERE username = ? AND password = ?;");
        $query->bind_param("ss", $username, $sha_pass_hash);
        $query->execute();
        $query->store_result();
        if($query->num_rows == 1)
        {
            $query->close();
            return true;
        }
        else
        {
            $query->close();
            return false;
        }
    }
    
    public function executeNonQuery($q)
    {
        //Only Insert or Update or Delete statement (NOT PREPARED)
        $query = $this->conn->prepare($q);
        if($query->execute())
        {
            $query->close();
            return true;
        }
        else
        {
            $query->close();
            return false;
        }
    }
    
    public function cambiaPasswordAccount($username, $oldPassword, $newPassword)
    {
        $oldShaPassHash = strtoupper(sha1($username.":".$oldPassword));
        if($this->validaLogin($username, $oldPassword))
        {
            $newShaPassHash = strtoupper(sha1($username.":".$newPassword));
            $query = $this->conn->prepare("UPDATE accounts SET password = ? WHERE username = ?;");
            $query->bind_param("ss", $newShaPassHash, $username);
            if($query->execute())
            {
                $query->close();
                echo "Password aggiornata con successo";
                return true;
            }
            else
            {
                $query->close();
                echo "Non sono riuscito a aggiornare la password";
                return false;
            }
        }
    }
    
    public function getAccountLevel($username)
    {
        $cod = 0;
        $query = $this->conn->prepare("SELECT ksLivello FROM account_access JOIN accounts ON ksAccount = ID WHERE username = ?");
        $query->bind_param("s", $username);
        $query->execute();
        $query->bind_result($livello);
        while($query->fetch())
            $cod = $livello;
        return $cod;
    }
    
    public function getAccountId($username)
    {
        $id = 0;
        $query = $this->conn->prepare("SELECT ID FROM accounts WHERE username = ?;");
        $query->bind_param("s", $username);
        $query->execute();
        $query->bind_result($idacc);
        while($query->fetch())
            $id = $idacc;
        $query->close();
        return $id;
    }
    
    public function creaAccount($username, $sha_hash_pass, $email, $acclvl)
    {
        
        if($query = $this->conn->prepare("INSERT INTO accounts(username, password, email) VALUES (?, ?, ?);"))
        {
            $query->bind_param("sss", $username, $sha_hash_pass, $email);
            if($query->execute())
            {
                echo "Account creato con successo<br>";
            }
            else
                echo "Errore nella creazione dell'account. Potrebbe essere già esistente.<br>";
        }
        $query->close();
        $accid = $this->getAccountId($username);
        if($query = $this->conn->prepare("INSERT INTO account_access(ksAccount, ksLivello) VALUES(?, ?);"))
        {
            $query->bind_param("ii", $accid, $acclvl);
            if($query->execute())
                echo "Poteri dati all'account";
            else
                echo "Poteri non dati all'account";
        }
        $query->close();
    }

    public function setLastIP($username)
    {
        $ip = $_SERVER['REMOTE_ADDR'];
        $query = $this->conn->prepare("UPDATE accounts SET last_ip = ? WHERE username = ?;");
        $query->bind_param("ss", $ip, $username);
        $query->execute();
        $query->close();
    }
    
    public function setLastLogin($username)
    {
        $dd = date("d");
        $mm = date("m");
        $aaaa = date("Y");
        $query = $this->conn->prepare("UPDATE accounts SET last_connection = '".$aaaa."-".$mm."-".$dd."' WHERE username = '$username';");
        $query->execute();
        $query->close();
    }
    
}

?>