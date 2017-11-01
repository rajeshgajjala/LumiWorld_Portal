<?php
    
if(! empty($_SERVER['HTTP_USER_AGENT'])){
    $useragent = $_SERVER['HTTP_USER_AGENT'];
    if( !preg_match('@(iPad|iPod|iPhone|Android|BlackBerry|SymbianOS|SCH-M\d+|Opera Mini|Windows CE|Nokia|SonyEricsson|webOS|PalmOS)@', $useragent) ){
        header('Location: download.php');
    }
}
?>
<!DOCTYPE html>

<html lang="en">
    <head>
        <meta charset="utf-8" />
        <meta name="viewport" content="width=device-width">
		<link href='http://fonts.googleapis.com/css?family=Roboto:400,300' rel='stylesheet' type='text/css'>
        <title>Download Luminet World</title>
        <style>
            htm, body, div, input{
                font-family: "Roboto";
                font-weight: 300;
            }
             footer{
                bottom: 0;
                position: relative;
                width: 100%;
            }
            header{
                text-transform: none;
            }
            .header-image{
                background: url('../img/downloads-header.png');
                background-size: 100% 190%;
                background-repeat: no-repeat;
                height: 150px;
                margin: 0;
            }
            h1{
                font-size: 18pt;
                width: 100%;
                text-align: center;
            }
            .download-button{
                background-color: #000;
                border: 0;
                color: #fff;
                text-transform: uppercase;
                font-size: 14pt;
                width: 200pt;
                padding: 10pt;
                margin: 5pt;
            }
            .centered-content{
                text-align: center;
            }
            .tc{
                margin-bottom: 10pt;
            }
           .downloads-footer{
                padding: 5pt;
                background-image: url('../img/downloads-footer.png');
                color: #fff;
                background-size: 100% 100%;
                background-repeat: no-repeat;
                margin: 0;
            }
        </style>
        <script src="../js/vendor/jquery-1.10.1.min.js"></script>
        <script>
            $(document).ready(function () {
                $('#btn-download').click(function () {
                    window.location.href = 'https://play.google.com/store/apps/details?id=com.luminet.world';
                });

            });
        </script>
        <script type="text/javascript">

          var _gaq = _gaq || [];
          _gaq.push(['_setAccount', 'UA-48173368-1']);
          _gaq.push(['_trackPageview']);

          (function() {
            var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
            ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
            var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
          })();

        </script>
    </head>
    <body>
        <div class="wrapper">
            <header>
                <div class="header-image"></div>
                <hgroup>
                    <h1>Welcome to Luminet World Beta</h1>
                </hgroup>
            </header>
            <div class="main-content">
                <div class="centered-content" style="display: none"><input id="btn-how-to-install" type="button" value="How to install" class="download-button" /></div>
                <div class="centered-content">
                    <input style="display: none" id="btn-download" type="button" value="Download for android" class="download-button" onclick="_gaq.push(['_trackEvent', 'button', 'clicked', 'download',, 'true'])" />
                    <a href="https://play.google.com/store/apps/details?id=com.luminet.world&hl=en"><img style="width: 160px" src="../img/Android-App-on-Google-Play.jpg" alt="luminet on Google Play" /></a>
                </div>
                <div class="centered-content">
                    <span class="version-number">
                        <?php
                            $version_file = 'version.dat';
                            $version_array = file($version_file);
                            echo count($version_array) > 0 ? $version_array[0] : '';
                        ?>
                    </span>
                </div>
            </div>
            <div style="text-align: center; font-weight: 400;">
                <span>
                    <?php
                        $log_file = 'download.log';
                        $log_array = file($log_file);
                        //echo 'Total Downloads: ' . count($log_array);
                    ?>
                </span>
            </div>
            <div style="text-align: center; font-weight: 400; display: none">
                <p>
                    Once downloaded, ensure that your phone is
                    set to allow installation of apps from sources
                    other than the Google Play Store in order to
                    install Luminet World for Android.
                </p>
                <p>
                    Visit Settings -> Security -> Unknown Sources
                    and tap on the checkbox to complete the
                    installation.
                </p>
            </div>
            <footer>
                <div class="centered-content tc"><a href="../termsandconditions.html">Terms &amp; Conditions of Use</a></div>
                <div class="centered-content downloads-footer">
                    <div>www.luminetgroup.com</div>
                    <div>Copyright &copy; 2014 Luminet Group (Pty) Ltd</div>
                </div>
            </footer>
        </div>
    </body>
</html>
