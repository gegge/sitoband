<?php
include_once($_SERVER['DOCUMENT_ROOT']."/config.php");
//header
include(HEADER);
?>

<div class="contenitore">
    <div  class="contenuto">
        <!-- contenuto -->
        <div class="accountcreationcontainer">
            <table id="tabellacreaaccount">
                <tr>
                    <td><label for="usertxt">Username:</label></td><td><input type="text" id="usertxt" title="Inserisci username" /></td>
                </tr>
                <tr>
                    <td><label for="passtxt">Password:</label></td><td><input type="password" id="passtxt" title="Inserisci password" /></td>
                </tr>
                <tr>
                    <td><label for="emailtxt">Email:</label></td><td><input type="email" id="mailtxt" title="Inserisci email" /></td><
                </tr>
                <tr>
                    <td><label for="acclvlsel">Account Level:</label></td><td><select id="acclvlsel"><option value="null">-- Seleziona livello --</option><option value="1">normal</option><option value="2">admin</option></select></td>
                </tr>
                <tr>
                    <td colspan="2"><input type="button" value="Crea" id="creabtn" /></td>
                </tr>
                <tr>
                    <td colspan="2" id="risultato"></td>
                </tr>
            </table>
            <script type="text/javascript">
                $("document").ready(function()
                {
                    $("#creabtn").click(function()
                    {
                        var username = $("#usertxt").val();
                        var psw = $("#passtxt").val();
                        var email = $("#mailtxt").val();
                        var acclvl = $("#acclvlsel").val();
                        if (validateEmail(email))
                        {
                            if (psw != "" && username != "" && acclvl != "null")
                            {
                                $.post("/elaboraaccountmeteora/"+username+"|"+psw+"|"+email+"|"+acclvl, "", function(data,status)
                                {
                                    $("#risultato").html(data);
                                });
                            }
                            else
                                alert("Completa tutti i campi");
                            
                        }
                        else
                        {
                            alert("email non valida: " + email);
                        }
                    });
                    
                    $("#mailtxt").keydown(function()
                    {
                        var testo = $("#mailtxt").val();
                        if (validateEmail(testo))
                        {
                            $("#mailtxt").css("background-color", "green");
                        }
                        else
                        {
                            $("#mailtxt").css("background-color", "red");
                        }
                    });
                    function validateEmail(email) {
                        var re = /^([\w-]+(?:\.[\w-]+)*)@((?:[\w-]+\.)*\w[\w-]{0,66})\.([a-z]{2,6}(?:\.[a-z]{2})?)$/i;
                        return re.test(email);
                    }
                });
            </script>
        </div>
    </div>
</div>
<?php
//footer
include(FOOTER);
?>