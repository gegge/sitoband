<?php
include_once($_SERVER['DOCUMENT_ROOT']."/config.php");
//header
include(HEADER);
?>

<div class="contenitore">
    <div  class="contenuto">
        <!-- contenuto -->
        <div class="logincontainer">
            <table id="tabellalogin">
                <tr>
                    <td><label for="usertxt">Username:</label></td><td><input type="text" id="usertxt" title="Inserisci username" /></td>
                </tr>
                <tr>
                    <td><label for="passtxt">Password:</label></td><td><input type="password" id="passtxt" title="Inserisci password" /></td>
                </tr>
                <tr>
                    <td colspan="2"><input type="button" value="Login" id="loginbtn" /></td>
                </tr>
            </table>
            <script type="text/javascript">
                $("document").ready(function()
                {
                    $("#loginbtn").click(function()
                    {
                        var username = $("#usertxt").val();
                        var psw = $("#passtxt").val();
                        if (psw != "" && username != "")
                        {
                            $.post("/elaboraloginmeteora/"+username+"|"+psw, "", function(data,status)
                            {
                                if (data == "ok")
                                {
                                    window.location.reload("/");
                                }
                                else
                                    alert("Credenziali non corrette");
                            });
                        }
                        else
                            alert("Completa tutti i campi");
                    });
                });
            </script>
        </div>
    </div>
</div>
<?php
//footer
include(FOOTER);
?>