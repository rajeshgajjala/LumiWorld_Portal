
<!DOCTYPE html>
<!--[if lt IE 7]>      <html class="no-js lt-ie9 lt-ie8 lt-ie7"> <![endif]-->
<!--[if IE 7]>         <html class="no-js lt-ie9 lt-ie8"> <![endif]-->
<!--[if IE 8]>         <html class="no-js lt-ie9"> <![endif]-->
<!--[if gt IE 8]>      <html class="no-js"><![endif]-->
<html lang="en">
	<head>
		<meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
		<title>Login | Lumi World</title>
		<meta name="description" content="">
		<meta name="viewport" content="width=device-width">
        <link rel="stylesheet" href="css/bootstrap.min.css"/>
		<link rel="stylesheet" href="css/main.css">
		<link rel="shortcut icon" href="favicon.ico" />
		<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.10.1/jquery.min.js"></script>
        <script src="js/vendor/bootbox.min.js"></script>
		<script src="js/vendor/bootstrap.min.js"></script>
		<script src="js/vendor/modernizr-2.6.2.min.js"></script>
		<script src="js/sessionstorage.1.4.js"></script>
        <script src="js/main.js?v=0.2"></script>
        <script src="js/config.js"></script>
        <script type="text/javascript">
            $(document).ready(function () {
                sessionStorage.clear();
                setupLoadingScreen();
                //console.log(getServerDate(new Date(2014, 9, 10)));
                //console.log(getServerDate(new Date(2014, 10, 9)));

                $('#login-form').submit(function (e) {
                    e.preventDefault();
                    login();
                });
            });

            function LogUser() {

                var loginTime = new Date();
                loginTime = loginTime.getFullYear() + "/" + (loginTime.getMonth() + 1) + "/" + loginTime.getDate() + " " + loginTime.getHours() + ":" + loginTime.getMinutes();


                var userdata = new Object();
                userdata.username = $('#txt-username').val();
                userdata.time = loginTime;
                userdata.channel = "portal";

                var dataEnterpriseLog = JSON.stringify({
                    'username': userdata.username,
                    'time': userdata.time,
                    'channel': userdata.channel,
                    'status': 1,
                    'key': 'insertenterpriselog'
                });


                $.ajax({
                    type: "POST",
                    url: "http://" + serverURL + ":12010/enterpriselog",
                    data: {
                        params: dataEnterpriseLog
                    },
                    contentType: "application/x-www-form-urlencoded",
                    dataType: "json",
                    success: function (result) {
                        window.location.replace("search.php");
                    }
                });
            }



            function login() {
                var data = new Object();

                data = new Object();
                data.name = $('#txt-username').val();
                data.password = $('#txt-password').val();

                var dataEnterpriseAuth = JSON.stringify({
                    'username': data.name,
                    'password': data.password,
                    'key': 'getenterpriselogins'
                });



                $.ajax({
                    type: "POST",
                    url: "http://" + serverURL + ":12020/enterpriseprofile",
                    data: {
                        params: dataEnterpriseAuth
                    },
                    contentType: "application/x-www-form-urlencoded",
                    dataType: "json",
                    success: function (result) {
                        if (result == "0") {
                            bootbox.alert({
                                message: "Username or Password is incorrect. Please try again.",
                                buttons: {
                                    ok: {
                                        className: 'btn-danger'
                                    }
                                }
                            });

                        } else {
                            var enterprise = JSON.stringify({
                                'id': result[0].id,
                                'name': result[0].name,
                                'registrationnumber': result[0].companyRegistrationNumber,
                                'companyLogo': result[0].enterpriseLogo,
                                'coverpage': result[0].enterpriseCoverPage,
                                'description': result[0].shortDescription,
                                'email': result[0].emailAddress,
                                'contactnumber': result[0].contactNumber,
                                'username': data.name,
                                'physicalAddress': result[0].physicalAddress != null && typeof (result[0].physicalAddress) != 'undefined' ? JSON.parse(result[0].physicalAddress.value)[0] : {},
                                'sectorId': result[0].sectorID
                            });

                            sessionStorage.setItem('enterprise', enterprise);
                            console.log(enterprise);

                            LogUser();
                        }
                    },
                    error: function (e) {
                        console.log('Error: ' + e);
                        console.log(e);
                    }
                });
            }


            function forgot() {
                var url = "resetpassword.html?u=" + $('#txt-username').val();
                window.location.replace(url);
            }
		</script>
	</head>

	<body>
		<div class="top-bar">
			<div class="top-logo fl">
				<a href="http://www.lumiworld.biz" class="fl"><img alt="logo" src="img/lumiworld_login.png"></a>
			</div>
			<div class="centre-title fl">
				<span>Lumi World</span>
			</div>
			<!--<div class="top-menu fr">
				<a href="http://www.luminetgroup.com">Luminet Group</a>
			</div>-->
		</div>
		
		<div>
			<div class="center-div cs-login ">
				<header>
					<h1>Enterprise portal</h1>
				</header>
				<form id="login-form">
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
			
			<footer style="bottom: 25%;position: fixed;left: 0;right: 0;">
				<div class="login-footer clearfix">
					By logging in I acknowledge that I have read the <a href="termsandconditions.html">Terms and Conditions</a> of using Lumi World.
				</div>
			</footer>
		</div>	

		<script>
			window.jQuery || document.write('<script src="js/vendor/jquery-1.10.1.min.js"><\/script>')
		</script>
		

		<script src="js/plugins.js"></script>
        <div class="login-loading"><!-- Place at bottom of page --></div>
	</body>
</html>
