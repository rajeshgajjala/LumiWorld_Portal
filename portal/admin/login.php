<?php

?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8" />
		<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
		<meta name="description" content="">
		<meta name="viewport" content="width=device-width">
        <link rel="stylesheet" href="../css/bootstrap.min.css"/>
		<link rel="stylesheet" href="../css/main.css">
		<link rel="shortcut icon" href="favicon.ico" />
		<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.10.1/jquery.min.js"></script>
        <script src="../js/vendor/bootbox.min.js"></script>
		<script src="../js/vendor/bootstrap.min.js"></script>
		<script src="../js/vendor/modernizr-2.6.2.min.js"></script>
		<script src="../js/sessionstorage.1.4.js"></script>
        <script src="../js/main.js"></script>
        <script src="../js/config.js"></script>
        <title>Lumi World Admin | Login</title>
        <script>
            $(document).ready(function () {
                setupLoadingScreen();
                sessionStorage.removeItem('adminUser');
                $('#login-form').submit(function (e) {
                    e.preventDefault();
                    login();
                });
            })
            function login() {
                var data = new Object();

                data = new Object();
                data.name = $('#txt-username').val();
                data.password = $('#txt-password').val();

                var adminLogin = JSON.stringify({
                    'username': data.name,
                    'password': data.password
                });
                var today = new Date();
                var adminLoginLog = JSON.stringify({
                    'username': $('#txt-username').val(),
                    'loginTime': today,
                    'key': 'getAdminLog'
                });



                $.ajax({
                    type: "POST",
                    url: "http://" + serverURL + ":11060/luminetWorldAdmin",
                    data: {
                        params: adminLogin
                    },
                    contentType: "application/x-www-form-urlencoded",
                    dataType: "json",
                    success: function (result) {
                        if (parseInt(result) === 1) {
                            sessionStorage.setItem('adminUser', JSON.stringify(adminLogin));
                            $.ajax({// ajax call starts
                                type: "POST",
                                url: "http://" + serverURL + ":11060/adminLog",
                                data: {
                                    params: adminLoginLog
                                }, 
                                contentType: "application/x-www-form-urlencoded",
                                dataType: "json", 
                                success: function (result)
                                {
                                    console.log(result);
                                    window.location.replace('Dashboard.html');
                                    //$('#txtOutput').val(output);
                                }
                            });
                            
                        }
                        else
                            sessionStorage.removeItem('adminUser');
                    },
                    error: function (e) {
                        console.log('Error: ' + e);
                        console.log(e);
                    }
                });
            }
        </script>
    </head>
    <body>
        <div class="top-bar">
			<div class="top-logo fl">
				<a href="http://www.lumiworld.biz" class="fl"><img alt="logo" src="../img/lumiworld_login.png"></a><!--Need to give Zazo's website address once the site is up-->
			</div>
			<div class="centre-title fl">
				<span>Lumi World</span>
			</div>
			<!--<div class="top-menu fr">
				<a href="http://www.luminetgroup.com">Luminet Group</a>
			</div>-->
		</div>
		<div class="cs-login clearfix">
			
			<div class="container-body center-div">
				<header>
					<h1>Admin portal</h1>
				</header>
				<form id="login-form" method="POST">
					<div class="privateidtext">
						<input class="privatetextstyle" type="email" id="txt-username" name="name" placeholder="Enter username" />
					</div>
					<div class="passwordtext">
						<input class="privatetextstyle" type="password" id="txt-password" name="password" placeholder="Enter password"/>
					</div>
					
					<div class="passwordtext" style="margin-bottom: 10px;">
						<input class="login-button fr" type="submit" value="login" id="login"/>
					</div>
					<div class="forgot-container">
						<a class="link-forgot" href="javascript:forgot();" id="link-forgot">Forgot Password</a>
					</div>
				</form>
			</div>
		</div>

		<script>
			window.jQuery || document.write('<script src="js/vendor/jquery-1.10.1.min.js"><\/script>')
		</script>
		

		<script src="../js/plugins.js"></script>
        <div class="login-loading"><!-- Place at bottom of page --></div>
    </body>
</html>
