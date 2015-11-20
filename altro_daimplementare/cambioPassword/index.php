<?php
include_once($_SERVER['DOCUMENT_ROOT']."/config.php");
include_once(DB_CLASS);
include_once(SIDEBAR_CLASS);
include_once(CONTENT_CLASS);

$db = new db_class();
if($db->getAccountLevel($_SESSION['username_customer']) < 2)
{
    header("Location: /");
}

if(isset($_POST['msg']))
{
    $msg = $_POST['msg'];
}


include(HEADER_FILE);
        if(!isset($_SESSION['username_customer']))
        {
            //header("Location: ./login");
            ?>
            <div class="middle">
                <div class="container">
                    <?php include(SIDEBAR_FILE); ?>
                    <main class="content">
                        <center><h1><u><?php echo $localizzazione['select_option'][$_SESSION['lang']] ?></u></h1></center>
                    </main><!-- .content -->
                </div><!-- .container-->        
        
            </div><!-- .middle-->
            <?php
        }
        else
        {//SIDEBAR
            ?>
            <div class="middle">
                <div class="container">
                    <?php include(SIDEBAR_FILE); ?>
                    <main class="content">
                        <div id="modulo" class="modulo">
                            <table>
                                <tr>
                                    <td>Username:</td><td><input type="text" id="user" /></td>
                                </tr>
                                <tr>
                                    <td>Vecchia Password:</td><td><input type="password" id="oldPass" /></td>
                                </tr>
                                <tr>
                                    <td>Nuova Password:</td><td><input type="password" id="newPass" /></td>
                                </tr>
                                <tr>
                                    <td>Ripeti Nuova Password:</td><td><input type="password" id="renewPass" /></td>
                                </tr>
                                <tr>
                                    <td></td><td><input type="button" id="inviaModuloModPsw" value="MODIFICA PASSWORD" /></td>
                                </tr>
                            </table>                            
                        </div>       
                
                        <div id="risultato"></div>
                        
                        <script type="text/javascript">
                            $("#inviaModuloModPsw").click(function()
                                {
                                    var username = $("#user").val();
                                    var oldpassword = $("#oldPass").val();
                                    var newpassword = $("#newPass").val();
                                    var renewpassword = $("#renewPass").val();
                                    if (username == "" || oldpassword == "" || newpassword == "" || renewpassword == "")
                                    {
                                        alert("Non hai compilato tutti i campi");
                                    }
                                    else
                                    {
                                        $.post("./features/cambioPassword/elaborazioneCambioPsw.php", "user="+username+"&oldpsw="+oldpassword+"&newpass="+newpassword+"&renewpass="+renewpassword, function(data, status)
                                        {
                                            $("#risultato").html(data);
                                        });
                                    }
                                });
                        </script>
                    </main><!-- .content -->
                </div><!-- .container-->        
        
            </div><!-- .middle-->
            <?php            
        }
        include(FOOTER_FILE);
        ?>