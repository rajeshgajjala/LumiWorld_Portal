<?php

?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8" />
		<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
		<title>Lumi World Admin | View Enterprises</title>
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
        <script>
            
            $(document).ready(function () {
                init();
                first = false;
                getAllEnterprises();
            });
            function getAllEnterprises() {
                var dataEnterprise = JSON.stringify({
                    'key': 'getallenterprises'
                });

                $.ajax({// ajax call starts
                    type: "POST",
                    url: "http://" + serverURL + ":11020/enterpriseprofile", // + "?" + "limit=" + "12" + "&" + "page=" + "0", // JQuery loads serverside.php
                    data: {
                        params: dataEnterprise
                    }, // Send value of the clicked button
                    contentType: "application/x-www-form-urlencoded",
                    dataType: "json", // Choosing a JSON datatype
                    success: function (result)// Variable data contains the data we get from serverside
                    {
                        console.log(result);
                        renderEnteprises(result);
                        //var output = JSON.stringify(result);
                    }
                });
            }
            function renderEnteprises(enterpriseList) {
                var htmlRows = '';
                $(enterpriseList).each(function (index, item) {
                    htmlRows += ('<tr><td>' + item.displayName + '</td><td>' + item.companyRegistrationNumber + '</td><td>' + item.emailAddress + '</td></tr>');
                });
                $('#tbody-enterprises').html(htmlRows);
            }
        </script>
    </head>
    <body>
        <div class="top-bar">
			<div class="top-logo fl">
				<a href="index.html" class="fl"><img alt="logo" src="../img/lumiworld_login.png"></a>
			</div>
			<div class="centre-title fl">
				<span>Lumi World</span>
			</div>
			<div class="top-menu fr">
				<a href="http://www.lumiworld.biz">Zazo</a>
			</div>
		</div>
		
			
		<div class="clearfix" style="margin-top: 40pt;">
            <a href="editenterprise.php">New Enterprise</a>
			<header>
				<h1>All Enterprises</h1>
			</header>
            <div class="enterprises">
                <table>
                    <thead>
                        <tr><th>Name</th><th>Registration Number</th><th>Email</th></tr>
                    </thead>
                    <tbody id="tbody-enterprises">
                    </tbody>
                </table>
            </div>
		</div>

		<script>
			window.jQuery || document.write('<script src="js/vendor/jquery-1.10.1.min.js"><\/script>')
		</script>
		

		<script src="../js/plugins.js"></script>
        <div class="login-loading"><!-- Place at bottom of page --></div>
    </body>
</html>
