<?php
include_once($_SERVER['DOCUMENT_ROOT']."/config.php");
if($dbMGR->getAccountLevel($_SESSION['username']) < 2)
{
    header("Location: /");
}


include(HEADER);
?>
<div class="contenitore">
    <div  class="contenuto">
        <!-- contenuto -->
        <div class="modpasscontainer">
            <table id="tabellamodpass">
                <tr>
                    <td><label for="usertxt">Username:</label></td><td><input type="text" id="usertxt" title="Inserisci username" /></td>
                </tr>
                <tr>
                    <td><label for="oldpasstxt">Vecchia Password:</label></td><td><input type="password" id="oldpasstxt" title="Inserisci vecchia password" /></td>
                </tr>
                <tr>
                    <td><label for="newpasstxt">Nuova Password:</label></td><td><input type="password" id="newpasstxt" title="Inserisci nuova password" /></td>
                </tr>
                <tr>
                    <td colspan="2"><input type="button" value="Modifica" id="inviaModuloModPsw" /></td>
                </tr>
            </table>
            <div id="risultato"></div>
            <script type="text/javascript">
                $("#inviaModuloModPsw").click(function()
                    {
                        var username = $("#usertxt").val();
                        var oldpassword = $("#oldpasstxt").val();
                        var newpassword = $("#newpasstxt").val();
                        if (username == "" || oldpassword == "" || newpassword == "")
                        {
                            alert("Non hai compilato tutti i campi");
                        }
                        else
                        {
                            $.post("/elaboramodificapasswordmeteora/"+username+"|"+oldpassword+"|"+newpassword, "", function(data, status)
                            {
                                $("#risultato").html(data);
                            });
                        }
                    });
            </script>
        </div>
    </div>
</div>

<?php            
include(FOOTER);
?>