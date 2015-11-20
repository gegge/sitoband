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
    
    public function getAccountCode($username)
    {
        $cod = "";
        $query = $this->conn->prepare("SELECT cod_cliente FROM accounts WHERE username = ?");
        $query->bind_param("s", $username);
        $query->execute();
        $query->bind_result($cod_cliente);
        while($query->fetch())
            $cod = $cod_cliente;
        return $cod;
    }
    
    public function getAccountLevel($username)
    {
        $cod = 0;
        $query = $this->conn->prepare("SELECT ksLivello FROM account_access JOIN accounts ON ksAccount = idAccount WHERE username = ?");
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
        $query = $this->conn->prepare("SELECT idAccount FROM accounts WHERE username = ?;");
        $query->bind_param("s", $username);
        $query->execute();
        $query->bind_result($idacc);
        while($query->fetch())
            $id = $idacc;
        $query->close();
        return $id;
    }
    
    public function getAccountAvailableAreas($username)
    {
        $va = "";
        $query = $this->conn->prepare("SELECT aree_visibili FROM accounts WHERE username = ?;");
        $query->bind_param("s", $username);
        $query->execute();
        $query->bind_result($areas);
        while($query->fetch())
            $va = $areas;
        $query->close();
        return $va;
    }
    
    public function getAccountAvailableAreaFigli($username)
    {
        $va = "";
        $query = $this->conn->prepare("SELECT figli_visibili FROM accounts WHERE username = ?;");
        $query->bind_param("s", $username);
        $query->execute();
        $query->bind_result($areas);
        while($query->fetch())
            $va = $areas;
        $query->close();
        return $va;
    }
    
    public function getAccountRagioneSociale($codcli)
    {
        $va = "";
        $query = $this->conn->prepare("SELECT RASCL FROM customer_as400_fileclienti WHERE CDCLI = ?;");
        $query->bind_param("s", $codcli);
        $query->execute();
        $query->bind_result($RASCL);
        while($query->fetch())
            $va = $RASCL;
        $query->close();
        return $va;
    }
    
    public function creaAccount($username, $sha_hash_pass, $email, $codcli, $acclvl, $figlivisibili)
    {
        
        if($query = $this->conn->prepare("INSERT INTO accounts(username, password, email, cod_cliente, figli_visibili) VALUES (?, ?, ?, ?, ?);"))
        {
            $query->bind_param("sssss", $username, $sha_hash_pass, $email, $codcli, $figlivisibili);
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
    
    public function getCodiciClientiByCodice($cod_cli)
    {
        $cod_cli = "{$cod_cli}%";
        $query = $this->conn->prepare("SELECT distinct cod_cliente, RASCL FROM accounts JOIN customer_as400_fileclienti on cod_cliente = CDCLI WHERE cod_cliente LIKE ?;");
        $query->bind_param("s", $cod_cli);
        $query->execute();
        $query->bind_result($codice, $rag_soc);
        echo "<table>";
        echo "<th>Codice Cliente</th><th>Impersonifica?</th>";
        while($query->fetch())
        {
            echo "<tr>";
                echo "<td>$codice</td><td><a href='#' id='$codice"."-"."$rag_soc' class='impersonificaLINK' title='Visualizza il sito nei panni del cliente'>Assumi identit&agrave;</a></td>";
            echo "</tr>";
        }
        echo "<table>";
        $query->close();
    }
    
    private function getAreaName($idArea)
    {
        $n = "";
        $query = $this->conn->prepare("SELECT nomeArea_".$_SESSION['lang']." FROM customer_aree WHERE idArea = ?;");
        $query->bind_param("i", $idArea);
        $query->execute();
        $query->bind_result($nome);
        while($query->fetch())
            $n = $nome;
        return $n;
    }
    
    private function getIMieiDocumentiFigliID()
    {
        $n = array();
        $query = $this->conn->prepare("SELECT idArea FROM customer_aree WHERE idAreaPadre = 1 order by ordine;");
        $query->execute();
        $query->bind_result($id);
        $c = 0;
        while($query->fetch())
        {
            $n[$c] = $id;
            $c++;
        }
        return implode(",", $n);
    }
    
    private function getIMieiDocumentiFigli($user)
    {
        $string = "";
        $idareefigli = $this->getIMieiDocumentiFigliID();
        $arrayidfigli = explode(",", $idareefigli);
        $idareefiglivisibili = $this->getAccountAvailableAreaFigli($user);
        $arrayidfiglivisibili = explode(",", $idareefiglivisibili);
        for($i = 0; $i < count($arrayidfigli); $i++)
        {
            if(in_array($arrayidfigli[$i], $arrayidfiglivisibili))
                $string .= "<label for='$i"."_figlio_'>".$this->getAreaName($arrayidfigli[$i])." <input type='checkbox' id='$i"."_figlio_' value='".$arrayidfigli[$i]."' checked /></label>";
            else
                $string .= "<label for='$i"."_figlio_'>".$this->getAreaName($arrayidfigli[$i])." <input type='checkbox' id='$i"."_figlio_' value='".$arrayidfigli[$i]."' /></label>";
        }
        
        return $string;
    }
    
    public function getAccountAdmin($username)
    {
        if($username == "")
            $query = $this->conn->prepare("SELECT idAccount, username FROM accounts JOIN account_access on idAccount = ksAccount WHERE ksLivello != 2;");
        else
        {
            $username = "{$username}%";
            $query = $this->conn->prepare("SELECT idAccount, username FROM accounts JOIN account_access on idAccount = ksAccount WHERE username LIKE ? AND ksLivello != 2 AND cod_cliente = username;");
            $query->bind_param("s", $username);
        }
        $query->execute();
        $query->store_result();
        $query->bind_result($idaccount, $username);
        echo "<table>";
        echo "<th>Username</th><th>Figli Visibili</th><th>Applica?</th><th>Cancella?</th>";
        while($query->fetch())
        {
            $figli = $this->getIMieiDocumentiFigli($username);
            $figli = str_replace("figlio_", "figlio_$username", $figli);
            echo "<tr>";
                echo "<td>$username</td>";
                echo "<td id='$username'>$figli</td>";
                echo "<td><a href='#' data-username='$username' class='applicamodificheLINK' title='Applica modifiche'>Applica</a></td>";
                echo "<td><a href='#' data-usernamedel='$username' class='cancellaLINK' title='Cancella Account'>Cancella</a></td>";
            echo "</tr>";
        }
        echo "</table>";
        $query->close();
    }
    
    public function cancellaAccountAdmin($username)
    {
        $arrayid = array();
        $query = $this->conn->prepare("SELECT idAccount FROM accounts WHERE cod_cliente = ?;");
        $query->bind_param("s", $username);
        $query->execute();
        $query->bind_result($id);
        $c = 0;
        while($query->fetch())
        {
            $arrayid[$c] = $id;
            $c++;
        }
        $query->close();
        if(count($arrayid) > 1)
            $ids = implode(",", $arrayid);
        else
            $ids = $arrayid[0];
        $query = $this->conn->prepare("DELETE FROM account_access WHERE ksAccount IN($ids);");
        $query->execute();
        $query->close();
        $query = $this->conn->prepare("DELETE FROM accounts WHERE cod_cliente = ?;");
        $query->bind_param("s", $username);
        $query->execute();
        $query->close();
    }
    
    public function cancellaAccount($username)
    {
        $username = $_SESSION['cod_cliente']."-".$username;
        $arrayid = array();
        $query = $this->conn->prepare("SELECT idAccount FROM accounts WHERE username = ?;");
        $query->bind_param("s", $username);
        $query->execute();
        $query->bind_result($id);
        $c = 0;
        while($query->fetch())
        {
            $arrayid[$c] = $id;
            $c++;
        }
        $query->close();
        if(count($arrayid) > 1)
            $ids = implode(",", $arrayid);
        else
            $ids = $arrayid[0];
        $query = $this->conn->prepare("DELETE FROM account_access WHERE ksAccount IN($ids);");
        $query->execute();
        $query->close();
        $query = $this->conn->prepare("DELETE FROM accounts WHERE username LIKE ?;");
        $query->bind_param("s", $username);
        $query->execute();
        $query->close();
    }
    
    public function getAccount($username)
    {
        if($username == "")
            $query = $this->conn->prepare("SELECT idAccount, username FROM accounts JOIN account_access on idAccount = ksAccount WHERE username LIKE '".$_SESSION['cod_cliente']."-%';");
        else
        {
            $username = "{$username}%";
            $query = $this->conn->prepare("SELECT idAccount, username FROM accounts JOIN account_access on idAccount = ksAccount WHERE username LIKE ?;");
            $query->bind_param("s", $username);
        }
        $query->execute();
        $query->store_result();
        $query->bind_result($idaccount, $username);
        echo "<table>";
        echo "<th>Username</th><th>Figli Visibili</th><th>Applica?</th><th>Cancella?</th>";
        while($query->fetch())
        {
            $figli = $this->getIMieiDocumentiFigli($username);
            $figli = str_replace("figlio_", "figlio_$username", $figli);
            $a = explode("-", $username);
            $username = $a[1];
            echo "<tr>";
                echo "<td>$username</td>";
                echo "<td id='$username'>$figli</td>";
                echo "<td><a href='#' data-username='$username' class='applicamodificheLINK' title='Applica modifiche'>Applica</a></td>";
                echo "<td><a href='#' data-usernamedel='$username' class='cancellaLINK' title='Cancella Account'>Cancella</a></td>";
            echo "</tr>";
        }
        echo "</table>";
        $query->close();
    }
    
    public function updateFigliVisibiliAdmin($username, $figli)
    {
        $query = $this->conn->prepare("UPDATE accounts SET figli_visibili = ? WHERE cod_cliente = ?;");
        $query->bind_param("ss", $figli, $username);
        $query->execute();
        $query->close();
    }
    
    public function updateFigliVisibili($username, $figli)
    {
        $username = $_SESSION['cod_cliente']."-".$username;
        $query = $this->conn->prepare("UPDATE accounts SET figli_visibili = ? WHERE username = ?;");
        $query->bind_param("ss", $figli, $username);
        $query->execute();
        $query->close();
    }
    
    private function GetBetween($var1="",$var2="",$pool){
        $temp1 = strpos($pool,$var1)+strlen($var1);
        $result = substr($pool,$temp1,strlen($pool));
        $dd=strpos($result,$var2);
        if($dd == 0){
        $dd = strlen($result);
        }
        
        return substr($result,0,$dd);
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
    
    public function checkIfWebsiteDisabled()
    {
        $v = 0;
        $query = $this->conn->prepare("SELECT valore FROM costanti WHERE nome = 'website_disabled_customer';");
        $query->execute();
        $query->bind_result($valore);
        while($query->fetch())
            $v = $valore;
        $query->close();
        return $v;
    }
    
    public function getTabellaAcquistiByCodice($codice, $foremail = false)
    {
        include(LOCALIZATION_STRINGS_FILE);
        $query = $this->conn->prepare("SELECT codice_tabella FROM customer_codici_carrelli WHERE codice = ?;");
        $query->bind_param("s", $codice);
        $query->execute();
        $query->bind_result($cod);
        $query->store_result();
        if($query->num_rows > 0)
        {
            while($query->fetch())
            {
                $cod = str_replace("text\">", "text\" readonly >", $cod);
                $cod = str_replace("<input value=\"", "", $cod);
                $cod = str_replace("\" type=\"text\" readonly >", "", $cod);
                if($foremail)
                {
                    $cod = str_replace("Â", "", $cod);
                }
                echo $cod;
            }
            $query->close();
        }
        else
        {
            echo $localizzazione["error_tabella_not_found"][$_SESSION['lang']];
            $query->close();
        }
    }
    
    public function inserisciOrdine($codice, $nome, $cognome, $email, $azienda, $stato, $conferma, $id)
    {
        $query = $this->conn->prepare("INSERT INTO customer_ordini_configuratore(codice, nome, cognome, email, azienda, stato, confermato, identificatoreOrdine) VALUES(?, ?, ?, ?, ?, ?, ?, ?);");
        $query->bind_param("ssssssis", $codice, $nome, $cognome, $email, $azienda, $stato, $conferma, $id);
        $query->execute();
        $query->close();
    }
    
    public function orderConfirmByCliente($codiceordine)
    {
        include(LOCALIZATION_STRINGS_FILE);
        $query = $this->conn->prepare("UPDATE customer_ordini_configuratore SET confermato = 1 WHERE identificatoreOrdine = ?;");
        $query->bind_param("s", $codiceordine);
        $query->execute();
        $query->store_result();
        if($query->affected_rows >= 1)
        {
            echo "<h1>".$localizzazione["order_confirm_by_cliente"][$_SESSION['lang']]."</h1>";
        }
    }
    
    public function makeLogMail($email, $messaggio, $stato)
    {
        $query = $this->conn->prepare("INSERT INTO log_email(ricevente, testo, stato) VALUES (?, ?, ?);");
        $query->bind_param("sss", $email, $messaggio, $stato);
        if($query->execute())
            echo $stato;
        $query->close();
    }
    
}

?>