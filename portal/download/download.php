<!DOCTYPE html>
<!--[if lt IE 7]>      <html class="no-js lt-ie9 lt-ie8 lt-ie7"> <![endif]-->
<!--[if IE 7]>         <html class="no-js lt-ie9 lt-ie8"> <![endif]-->
<!--[if IE 8]>         <html class="no-js lt-ie9"> <![endif]-->
<!--[if gt IE 8]>      <html class="no-js"> <![endif]-->
<html lang="en">
	<head>
		<meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
		<title>Download | Lumi World</title>
		<meta name="description" content="">
		<meta name="viewport" content="width=device-width">
		<link rel="stylesheet" href="../css/main.css">
		<link rel="shortcut icon" href="favicon.ico" />
		<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.10.1/jquery.min.js"></script>
		
		<script type="text/javascript">
      $(document).ready(function () {
          $('#btn-download').click(function () {
              window.location.href = 'https://play.google.com/store/apps/details?id=com.luminet.world';
          });
      });

		</script>
        <script>
            var _gaq;
            (function (i, s, o, g, r, a, m) {
                i['GoogleAnalyticsObject'] = r; i[r] = i[r] || function () {
                    _gaq = i[r].q = i[r].q || [];
                    (i[r].q = i[r].q || []).push(arguments)
                }, i[r].l = 1 * new Date(); a = s.createElement(o),
            m = s.getElementsByTagName(o)[0]; a.async = 1; a.src = g; m.parentNode.insertBefore(a, m)
            })(window, document, 'script', '//www.google-analytics.com/analytics.js', 'ga');

            ga('create', 'UA-48173368-1', 'luminetworld.com');
            ga('send', 'pageview');

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
				<a href="http://www.luminetworld.com">Lumi World</a> &nbsp;&nbsp;&nbsp;&nbsp;
                <a href="http://www.luminetgroup.com">Lumi Group</a>
			</div>
		</div>
		
		<div class="cs-login clearfix">
			<div class="container-body center-div">
                <header>
					<h1>Lumi World For Android</h1>
				</header>
				<div style="margin-bottom: 10px; text-align: center; margin-top: 30px;">
					<input style="display: none" id="btn-download" class="login-button" type="button" value="Download Now" id="reset" style="padding-left: 20px; padding-right: 20px" onclick="_gaq.push(['_trackEvent', 'button', 'clicked', 'download',, 'true'])"/>
                    <a href="https://play.google.com/store/apps/details?id=com.luminet.world&hl=en"><img style="width: 160px" src="../img/Android-App-on-Google-Play.jpg" alt="luminet on Google Play" /></a>
				</div>
                <div style="text-align: center; font-size: 8pt">
                    <?php
                        $version_file = 'version.dat';
                        $version_array = file($version_file);
                        echo count($version_array) > 0 ? rtrim(ltrim($version_array[0])) : '';
                    ?>
                </div>
                <div style="text-align: center; font-weight: 400;">
                    <span>
                        <?php
                            $log_file = 'download.log';
                            //$log_array = file($log_file);
                            //echo 'Total Downloads: ' . count($log_array);
                        ?>
                    </span>
                </div>
                <div style="text-align: center; margin-top: 10px;">
                    Minimum Requirements:
                </div>
                <div style="text-align: center; font-size: 8pt;">
                    -&nbsp;&nbsp;Android OS 4.x or above
                </div>
			</div>
			
		</div>

		<script>
			window.jQuery || document.write('<script src="js/vendor/jquery-1.10.1.min.js"><\/script>')
		</script>
		
		<script src="../js/main.js"></script>
        <div class="modal-loading"><!-- Place at bottom of page --></div>
	</body>
</html>
