<?php
include_once($_SERVER['DOCUMENT_ROOT']."/config.php");
class news_class
{
    private $conn;
    
    function __construct() {
        $this->conn = new mysqli(MYSQL_HOST, MYSQL_USERNAME, MYSQL_PASSWORD, MYSQL_DATABASE);
    }
    
    public function stampaNews()
    {
        $query = $this->conn->prepare("SELECT idNews, titolo, contenuto FROM news;");
        $query->execute();
        $query->bind_result($id, $titolo, $contenuto);
        while($query->fetch())
        {
            echo "<h2 class=\"titolonews\" id=\"news_$id\">$titolo</h2>";
            echo "<div class=\"newsbody\">$contenuto</div>";
            echo "<hr>";
        }
        $query->close();
    }
    
}

?>