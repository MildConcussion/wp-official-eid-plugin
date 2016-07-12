<?php
defined('ABSPATH') or die('No script kiddies please!');
if (get_transient( "site_temp_key" ) == null) {
    ?>
    <div class = "container">
        <div id = "loginBlock" class = "col-md-offset-3 col-md-6">
            <h1>Please authenticate yourself before activating the ID-API service</h1>
            <div id = "idlogin"></div>
            <script src = "https://api.smartid.dev/js/idbutton.js"></script>
            <script>
                new Button({clientId: "<?php echo ((IdCardLogin::getAuthKey() == null) ? 'new_api' : IdCardLogin::getAuthKey()); ?>"}, function (auth_token) {
                    document.getElementById("auth_key").value = auth_token;
                    document.getElementById("admin_login_form").submit();
                });
            </script>
            <form id="admin_login_form" action="" method="post">
                <input name="status" value="admin_login_done" type="hidden">
                <input id="auth_key" name="auth_key" type="hidden">
            </form>
        </div>
    </div>
<?php } else {
    ?>
    <div class = "container">
        <div id="register_form" class="container">     
            <h3>By registering you accept the below terms and conditions</h3>
            <form id="admin_login_form" action="" method="post">
                <input name="status" value="register_api" type="hidden">
                <button id="registerbutton" class="page-title-action" style="height:70px;font-size:200%">Activate registration</button>
            </form>
            <br>
            <div>
                <?php include("terms.html"); ?>
            </div>
        </div>
    </div>

<?php } ?>








