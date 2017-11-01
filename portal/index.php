<!DOCTYPE html>
<?php
header('Location: http://luminetworld.wix.com/welcome');
?>
<html lang="en">
	<head>
        <title>Lumi World</title>

        <link href='http://fonts.googleapis.com/css?family=Roboto:500,400,300,700' rel='stylesheet' type='text/css'>
        <link rel="shortcut icon" href="favicon.ico" />
        <link rel="stylesheet" href="css/main.css?v=0.6">
        
        <script src="js/view.js?v=0.1"></script>
        <!--<script>
            location.replace("http://luminetworld.wix.com/welcome");
        </script>-->
        <style>
            body{
                margin: 0;
            }
            .home-menu li{
                float: left;
                list-style: none;
                margin-left: 10px;
                margin-right: 10px;
            }
            .home-menu{
                padding-right: 10px;
                
            }
            .top-right{
                font-size: 12pt;
                line-height: 12pt;
            }
            .first-box{
                padding: 0pt 40pt 0pt 40pt;
                background-color: #000;
                display: inline-block;
                width: 100%;
                position: relative;
                margin-top: 30pt;
                margin-left: 0;
                margin-right: 0;
                color: #fff;
                display: inline-table;
            }
            .first-title{
                text-align: right;
                font-size: 70pt;
                height: 52pt;
                line-height: 55pt;
                font-weight: 700;
                margin-bottom: 0;
                padding-top: 0;
                margin-top: 0;
            }
            .third-title{
                font-size: 40pt;
                height: 36pt;
                line-height: 38pt;
                margin-bottom: 0;
                margin-top: 0;
            }
            .center{
                text-align: center;
            }
            
            #pane-summary{
                background-color: #e1e1e2;
                display: inline-block;
                padding-bottom: 10pt;
            }
            #pane-summary ul{
                margin: 0;
            }
            
            #pane-summary li{
                float: left;
                width: 20%;
                text-align: center;
                display: inline-block;
                margin-left: 3.2%;
                font-size: 14pt;
                line-height: 14pt;
                vertical-align: middle;
            }
            
            #pane-summary li div{
                min-height: 80pt;
            }
            .feature-summary{
                background-color: #fff;
                display: inline-block;
                height: 360px;
                margin: 20px;
            }
            .feature-summary-text{
                width: 48%;
                text-align: center;
                font-size: 20pt;
            }
            .feature-summary-image{
                width: 52%;
                height: 100%;
                overflow-y: hidden
            }
            .fl{
                float:left;
            }
            .fr{
                float: right;
            }
        </style>
	</head>
    <body>
        <div class="top-bar">
	        <div class="top-logo fl">
		        <a href="index.php" class="fl"><img alt="logo" src="img/lumiworld_login.png"></a>
	        </div>
            <div style="float: right;" class="top-right">
                <div style="display: inline-block">
                    <ul class="home-menu">
                        <li>Home</li>
                        <li>Consumers</li>
                        <li>Pricing</li>
                        <li>Contact</li>
                    </ul>
                </div>
                <div style="display: inline-block; margin-right: 10px">
                    <a href="signup.php">Sign Up</a>
                    <a href="login.php">Login</a>
                </div>
            </div>
        </div>

        <div class="first-box">
            <div style="float: left; width: 50%">
                <div class="third-title">Welcome to</div>
                <div class="first-title center">Lumi</div>
                <div class="first-title">World</div>
            </div>
            <div style="float: right; width: 50%; border: 1px solid dotted">
            </div>
        </div>
        <div id="pane-summary">
                <ul>
                    <li>
                        <h3>Search</h3>
                        <div>
                            Find your customers, employees, citizens, and stakeholders easily.
                        </div>
                        <a href="">Learn More</a>
                    </li>
                    <li>
                        <h3>Reach</h3>
                        <div>
                            Make sure your messages reach the people that want to hear from you.
                        </div>
                        <a href="">Learn More</a>
                    </li>
                    <li>
                        <h3>Engage</h3>
                        <div>
                            Listen, interact and engage with the people most important to your organisation.
                        </div>
                        <a href="">Learn More</a>
                    </li>
                    <li>
                        <h3>Analyse</h3>
                        <div>
                            Gain insights from your relationships with people to increase the value of these relationships.
                        </div>
                        <a href="">Learn More</a>
                    </li>
                </ul>
            </div>
        <div class="feature-summary">
            <div class="feature-summary-text fl">
                <h3>Search and find</h3>
                Find people important to your organisation on Lumi World,
                such as customers, employees, citizens and stakeholders.
                Invite them to connect with your organisation, segment them
                into audiences and organise them into logical groups. Lumi
                World is a live database of people you already know.
            </div>
            <div class="feature-summary-image fr">
                <img style="width: 100%;" src="img/search screen.png" />
            </div>
        </div>
        <div class="feature-summary">
            <div class="feature-summary-text fr">
                <h3>Search and find</h3>
                Find people important to your organisation on Lumi World,
                such as customers, employees, citizens and stakeholders.
                Invite them to connect with your organisation, segment them
                into audiences and organise them into logical groups. Lumi
                World is a live database of people you already know.
            </div>
            <div class="feature-summary-image fl">
                <img style="width: 100%;" src="img/Reach screen.png" />
            </div>
        </div>
        <div class="feature-summary">
            <div class="feature-summary-text fl">
                <h3>Search and find</h3>
                Find people important to your organisation on Lumi World,
                such as customers, employees, citizens and stakeholders.
                Invite them to connect with your organisation, segment them
                into audiences and organise them into logical groups. Lumi
                World is a live database of people you already know.
            </div>
            <div class="feature-summary-image fr">
                <img style="width: 100%;" src="img/engage screen.png" />
            </div>
        </div>
        <div class="feature-summary">
            <div class="feature-summary-text fr">
                <h3>Search and find</h3>
                Find people important to your organisation on Lumi World,
                such as customers, employees, citizens and stakeholders.
                Invite them to connect with your organisation, segment them
                into audiences and organise them into logical groups. Lumi
                World is a live database of people you already know.
            </div>
            <div class="feature-summary-image fl">
                <img style="width: 100%;" src="img/analyse screen.png" />
            </div>
        </div>
    </body>
</html>