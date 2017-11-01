<!DOCTYPE html>
<html lang="en">
    <head>
        <title>Lumi World</title>

        <link href='http://fonts.googleapis.com/css?family=Roboto:500,400,300,700' rel='stylesheet' type='text/css'>
        <link rel="shortcut icon" href="favicon.ico" />
        <link rel="stylesheet" href="css/main.css?v=0.6">

        <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.10.1/jquery.min.js"></script>
        <script src="js/view.js?v=0.1"></script>
        <script src="js/config.js"></script>
        <script src="js/main.js?v=0.6"></script>
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
            header{
                display: inline-block;
                width: 100%;
                position: relative;
                margin-top: 30pt;
            }
            h1{
                text-align: center;
                color: #d1d1d2;
                font-weight: 400;
            }

            .big-button{
                border-radius: 10px;
                height: 40px;
                padding-right: 20px;
                padding-left: 20px;
                font-size: large;
            }

            .grey-div{
                position: relative;
                display: inline-block;
                width: 100%;
                background-color: #e1e1e2;
            }
            .grey-div div{
                margin: 5% 20% 5% 20%;
                background-color: #e1e1e2;
                font-size: 20pt;
                line-height: 24pt;
            }
            .grey-div ul{
                padding-left: 15pt;
            }

            textarea{
                width: 100%;
            }

            small{
                font-size: 10pt;
                color: #CACACA;
                display: block;
                margin: 0 10px 0 10px;
                height: 20px;
                line-height: 20px;
            }

            input[type=text], input[type=password], input[type=email]{
                height: 30px;
                font-size: large;
                margin: 12pt 1pt 0pt 1pt;
                width: 100%;
            }
            textarea{
                font-size: large;
            }


            .toolbar{
                display: inline-block;
                width: 100%;
            }

            .toolbar input{
                margin-right: 20%;
            }
            .fields input{
                width: 100%;
            }


            .grey-div input[type=checkbox]{
                width: 25px;
                height: 25px;
            }
            .subtitle{
                font-weight: 700;
            }

        </style>
        <script>
            var verificationCode = '';
            //enableBrowserCheck = false;
            function confirmVerification(complete){
                
                complete();
            }
            $(document).ready(function(){
                setupLoadingScreen();
                verificationCode = getUrlVars()['vc'];
                
                if(typeof verificationCode !== 'undefined'){
                    
                    var dataCheckEnterpriseRegNumber = JSON.stringify({
        
                        'key' : 'checkEnterpriseRegNumber',
                        'companyRegNumber' : verificationCode 
                    });

                    $.ajax({// ajax call starts
                        type : "POST",
                        url: 'http://' + serverURL + ':12020/enterpriseprofile', // JQuery loads serverside.php
                        data : {
                            params: dataCheckEnterpriseRegNumber

                        }, // Send value of the clicked button
                        contentType : 'application/x-www-form-urlencoded',
                        dataType : 'json', // Choosing a JSON datatype
                        success : function(result)// Variable data contains the data we get from serverside
                        {

                            console.log(result);

                            if(parseInt(result, 10) === 1){
                                confirmVerification(function(){
                                    showScreen(2);
                                    $('#email-sent').text('Your email address has been verified, and you can now continue with the Lumi World sign-up process.');
                                    $('#btn-validate-resend').css('display', 'none');
                                    $('#btn-validate-next').css('display', 'inline-block');
                        
                                });
                            }
                            else{
                                alert('Your account has already been verified, you will be redirected to the login screen now');
                                window.location.replace('login.php');
                            }


                        },
                        error : function(result) {
                            console.log('api unavailable');
                            console.log(result);
                        }
                    });
                }
                else{
                    showScreen(1);
                }
                $('.login-button').addClass('big-button');
                
                
                $('#btn-browse').click(function(){
                    $('#browse-file').click();
                });
                
                $('#browse-file').change(imageSelect);
                
                
                $('#btn-validate-next').click(function(){
                    showScreen(3);
                });
            
                $('#btn-validate-resend').click(function(){
                    var dataVerificationResend = JSON.stringify({
                        'username' : $('#txt-email').val()
                    });

                    $.ajax({
                        type : "POST",
                        url: 'http://' + serverURL + ':12011/verificationResend', 
                        data : {
                            params: dataVerificationResend

                        }, 
                        contentType : 'application/x-www-form-urlencoded',
                        dataType : 'json',
                        success : function(result)
                        {

                            console.log(result);

                            var output = JSON.stringify(result);

                            $('#txtOutput').val(output);

                        },
                        error : function(result) {
                            alert('api unavailable');
                        }
                    });

                });
                
                $('#btn-save-org').click(function(){
                    if(validateEnterpriseData())
                        showScreen(4);
                });
                
                var getAllSectorsURL = "http://" + serverURL + ":11000/getallsectors";


                var allsector = [];

                $.ajax({
                    type: "GET",
                    url: getAllSectorsURL,
                    success: function (data) {
                        console.log(data);
                        document.getElementById('sectors-div').innerHTML = '';
                        //var oEnterprise = JSON.parse(sessionStorage.getItem('enterprise'));
                        var first = true;
                        for (x in data) {
                            var sectorId = data[x].id;
                            var sectorName = data[x].displayName;

                            var label = document.createElement("label");
                            var description = document.createTextNode(sectorName);
                            var checkbox = document.createElement('input');
                            var cont = document.createElement('div');

                            checkbox.type = 'checkbox';
                            checkbox.multiple = 'false';
                            checkbox.name = 'sectors_select';
                            checkbox.class = 'radio';
                            //checkbox.checked = sectorId == oEnterprise.sectorId;
                            checkbox.checked = first;
                            first = false;
                            checkbox.value = sectorId;

                            label.appendChild(checkbox);
                            label.appendChild(description);
                            label.style = 'width: 100%';

                            document.getElementById('sectors-div').appendChild(label);
                            document.getElementById('sectors-div').appendChild(document.createElement('br'));

                        }

                        $("input:checkbox").click(function () {
                            if ($(this).is(":checked")) {
                                var group = "input:checkbox[name='" + $(this).attr("name") + "']";
                                $(group).prop("checked", false);
                                $(this).prop("checked", true);
                            } else {
                                $(this).prop("checked", false);
                            }
                            getSelectedSector();
                        });
                    },

                    error: function (e) {
                        console.log(e);
                    }
                });

                $('#categories').change(function () {
                    var option = $(this).find('option:selected');

                    for (x in allsector) {
                        if (allsector[x].id == parseInt(option[0].innerHTML)) {
                            $('#sectorname').html(allsector[x].displayName);
                        }
                    }
                });
                $('#btn-save-sector').click(createEnterpriseProfile);
                
            });
            function showScreen(idx){
                $('.grey-div').css('display', 'none');
                switch(idx){
                    case 0:
                        $('#div-description').css('display', 'inline-block');
                        $('#heading').text('Lumi World Standard Edition: Sign-up process');
                        break;
                    case 1:
                        $('#div-admin').css('display', 'inline-block');
                        $('#heading').text('Step 1 of 4: Create your administrator account');
                        break;
                    case 2:
                        $('#div-confirmation').css('display', 'inline-block');
                        $('#heading').text('Step 2 of 4: Create your administrator account');
                        break;
                    case 3:
                        $('#div-enterprise-data').css('display', 'inline-block');
                        $('#heading').text('Step 3 of 4: Setup your organisation\'s homepage');
                        break;
                    case 4:
                        $('#div-enterprise-sector').css('display', 'inline-block');
                        $('#heading').text('Step 4 of 4: Choose a lifestyle category for your organisation');
                        break;
                    case 5:
                        $('#div-enterprise-done').css('display', 'inline-block');
                        $('#heading').text('Congratulations');
                        break;
                        
                }
            }
            var user = {
                username: '',
                password: '',
                firstName: '',
                lastName: ''
            }
            
            function validateAdminData(){
                user.username = $('#txt-email').val();
                user.password = $('#txt-password').val();
                user.firstName = $('#txt-firstname').val();
                user.lastName = $('#txt-lastname').val();
                if (!validateEmail(user.username)) {
                    alert('Invalid email address');
                    $('#txt-email').focus();
                    return false;
                }
                if(user.password.length < 8 || user.password != $('#txt-vpassword').val() || !validatePassword(user.password)){
                    alert('please enter a valid and matching password');
                    return false;
                }
                return true;
            }
            
            function createAdminAccount(){
                if(!validateAdminData())
                    return;
                console.log(JSON.stringify(user));
                
                $.ajax({
                    type: "POST",
                    url: "http://" + serverURL + ":12011/enterpriseSignUp",
                    data: {
                        params: JSON.stringify(user)
                    },
                    contentType: "application/x-www-form-urlencoded",
                    dataType: "json",

                    success: function (result) {
                        console.log('RES:');
                        console.log(result);
                        if(parseInt(result) === 1){
                            verificationEmailSent();
                        }   
                        else{
                            alert('The email address is already in use');
                        }
                        console.log('eish');
                    },
                    error: function(err){
                        console.log('error:')
                        console.log(err);
                    }
                });
            }
            function verificationEmailSent(){
                var txt = 'For security verification, an email has been sent to ' + $('#txt-email').val();
                txt += ' Please check your email inbox and follow the instructions provided in';
                txt += ' order to complete the sign-up process.';
                $('#email-sent').text(txt);
                showScreen(2);
            }
            
            function getSelectedSector() {
                return parseInt($("input:checked").val(), 10);
            }
            function validateEnterpriseData(){
                if (!validateEmail($('#txt-org-email').val())) {
                    alert('Invalid email address');
                    $('#txt-email').focus();
                    return false;
                }
                //inputErrors[inputErrors.length] = 'Invalid email address';
                if (!validatePhone($('#txt-tel').val())) {
                    alert('Invalid phone number')
                    $('#txt-tel').focus();
                    return false;
                }
                if ($('#txt-description').val().length > 120) {
                    alert('Description is too long')
                    $('#txt-description').focus();
                    return false;
                }
                return true;
            }

            function validateTC(){
                if($('#check-tc').prop("checked") === false){
                    alert('You have to agree to terms and conditions');
                    $('#check-tc').focus();
                    return false;
                }
                return true;
            }

            function createEnterpriseProfile() {
                if(!validateTC())
                    return;
                var canvas = document.getElementById("logo-canvas");
                var imageDataURL = canvas.toDataURL();
                
                var dataEnterprise = JSON.stringify({
                    'name' : $('#txt-organisation-name').val(),
                    'description' : $('#txt-description').val(),
                    'id' : getSelectedSector(),
                    'number' : $('#txt-tel').val(),
                    'email' : $('#txt-org-email').val(),
                    'registrationnumber' : $('#txt-reg').val(),
                    'status' : 3,
                    'url': '197.96.19.118/images/1.png',
                    'package' : 'Basic',
                    'physicalAddress' : [{
                            'Street' : '',
                            'Suburb' : '',
                            'City' : '',
                            'PostalCode' : '',
                            'Province' : '',
                            'Country' : ''
                        }],
                    'key' : 'updateenterprise',
                    'change' : verificationCode
                });

                console.log(dataEnterprise);
                $.ajax({
                    type: "POST",
                    url: "http://" + serverURL + ":12020/enterpriseprofile?" + "logo=" + imageDataURL,
                    data: {
                        params: dataEnterprise
                    },
                    contentType: "application/x-www-form-urlencoded",
                    dataType: "json",

                    success: function (result) {

                        console.log('RDB ' + result);
                        if(parseInt(result) === 1){
                            showScreen(5);
                            
                            var dataCongratsEmail = JSON.stringify({
                                'companyRegNumber' : $('#txt-reg').val(),
                                'package' : 'Basic Edition'
                            });

                            $.ajax({// ajax call starts
                                type : "POST",
                                url: 'http://' + serverURL + ':12015/congratsEmail', 
                                data : {
                                    params: dataCongratsEmail

                                }, 
                                contentType : 'application/x-www-form-urlencoded',
                                dataType : 'json', 
                                success : function(result)
                                {
                                    

                                    console.log(result);

                                    var output = JSON.stringify(result);

                                    $('#txtOutput').val(output);

                                },
                                error : function(result) {
                                    alert('api unavailable');
                                }
                            });

                            
                            
                            /*var createDataEnterprise = JSON.stringify({
                                'label': 'Enterprise',
                                'key': "companyregistrationnumber",
                                'properties': {
                                    'name': $('#txt-organisation-name').val(),
                                    'cell': $('#txt-tel').val(),
                                    'email': $('#txt-org-email').val(),
                                    'companyregistrationnumber': $('#txt-reg').val()
                                }

                            });

                            $.ajax({// ajax call starts
                                type: "POST",
                                url: "http://" + serverURL + ":12100/createuniquenode", // JQuery loads serverside.php
                                data: {
                                    params: createDataEnterprise
                                }, // Send value of the clicked button
                                contentType: "application/x-www-form-urlencoded",
                                dataType: "json", // Choosing a JSON datatype
                                success: function (res)// Variable data contains the data we get from serverside
                                {
                                    console.log('see?');
                                    console.log(res);
                                    window.location.replace('login.php');
                                },
                                error: function (err){
                                    console.log('error...');
                                    console.log(err);
                                }
                            });*/
                        }
                    }
                });

            }

            //end of Enterprise insert

            //Image Browser
            function imageSelect(evt) {
                if (window.File && window.FileReader && window.FileList && window.Blob) {
                    var files = evt.target.files;

                    var file;
                    for (var i = 0; i < files.length; i++) {
                        file = files[i];
                        // if the file is not an image, continue
                        if (!file.type.match('image.*')) {
                            continue;
                        }

                        reader = new FileReader();
                        reader.onload = (function (tFile) {
                            return function (evt) {
                                var canvas = document.getElementById("logo-canvas");
                                var context = canvas.getContext('2d');

                                // Store the current transformation matrix
                                //context.save();

                                // Use the identity matrix while clearing the canvas
                                context.setTransform(1, 0, 0, 1, 0, 0);
                                context.clearRect(0, 0, 160, 160);

                                // Restore the transform
                                //context.restore();

                                var img = new Image();
                                img.onload = function () {
                                    var max = 160;
                                    var r = img.naturalWidth > img.naturalHeight ? img.naturalWidth / max : img.naturalHeight / max;
                                    var w = img.naturalWidth / r,
                                    h = img.naturalHeight / r;


                                    var x = 0, y = 0;
                                    if (w < max) {
                                        var diff = max - w;
                                        x = diff / 2;
                                    }
                                    if (h < max) {
                                        var diff = max - h;
                                        y = diff / 2;
                                    }

                                    context.drawImage(img, x, y, w, h);

                                };
                                img.src = evt.target.result;

                            };
                        } (file));
                        reader.readAsDataURL(file);
                    }
                } else {
                    alert('The File APIs are not fully supported in this browser.');
                }
            }
            
            
            
            function validatePassword(input) {
                var reg = /^[^%\s]{6,}$/;
                var reg2 = /[a-zA-Z]/;
                var reg3 = /[A-Z]/;
                var reg4 = /[0-9]/;
                return reg.test(input) && reg2.test(input) && reg3.test(input) && reg4.test(input);
            }
            
        </script>
    </head>
    <body>
        <div class="top-bar">
            <div class="top-logo fl">
                <a href="index.php" class="fl"><img alt="logo" src="img/lumiworld_login.png"></a>
            </div>
            <div style="float: right;" class="top-right">
                <div style="display: inline-block; margin-right: 200px">
                    <ul class="home-menu">
                        <li>Home</li>
                    </ul>
                </div>
                <div style="display: inline-block; margin-right: 10px">
                    <a href="login.php">Login</a>
                </div>
            </div>
        </div>

        <header>
            <hgroup>
                <h1 id="heading">Lumi World Standard Edition: Sign-up process</h1>
            </hgroup>
        </header>
        <div id="div-description" class="grey-div">
            <div>
                <span>
                    As a Standard Edition organisation on Lumi World, you have access to a host of additional benefits such as:
                </span>
                <ul>
                    <li>A much larger bundle of messages to send - up to 150,000 per month</li>
                    <li>Features such as message scheduling & campaigns to interest filters, messaging chat conversations, custom app plug-ins and richer analytics</li>
                    <li>A superior level of service managed through a dedicated, personal account manager at Zazo</li>
                    <li>Support with strategic & digital marketing techniques through Lumi World</li>
                    <li>Featured organisation real estate through the Lumi World app</li>
                </ul>
                <span>
                    Once you have completed the sign-up process, you will be personally
                    contacted by your Lumi World Account Manager in order to validate and
                    activate your account.
                </span>
            </div>
            <nav class="toolbar">
                <input type="button" value="Continue" class="login-button fr" onclick="showScreen(1)" />
            </nav>
        </div>

        <div id="div-admin" class="grey-div">
            <div>
                <span>
                    The administrator is the primary user and representative of an organisation on Lumi World.
                </span>
                <form id="form-admin">
                    <div class="fields" style="margin: 0">
                        <input type="text" style="width: 48%" placeholder="First name" id="txt-firstname" class="fl"/>
                        <input type="text" style="width: 48%; margin-right: 0" placeholder="Last name" id="txt-lastname" class="fr"/><br>
                        <input type="email" placeholder="Email address" id="txt-email" /><br>
                        <input type="password" placeholder="Password" id="txt-password"/><br>
                        <input type="password" placeholder="Verify password" id="txt-vpassword"/>
                        <small>Your password must be at least 8 characters long, contain 1 uppercase letter, 1 lowercase letter and 1 digit.</small>
                    </div>
                </form>
            </div>
            <nav class="toolbar">
                <input id="btn-create-admin" type="button" value="Continue" class="login-button fr" onclick="createAdminAccount()" />
            </nav>
        </div>

        <div id="div-confirmation" class="grey-div">
            <div>
                <span id="email-sent">

                </span>
            </div>
            <nav class="toolbar">
                <input id="btn-validate-next" type="button" value="Continue" class="login-button fr" style="display: none"/>
                <input id="btn-validate-resend" type="button" value="Resend verification email" class="login-button fr"  />
            </nav>
        </div>

        <div id="div-enterprise-data" class="grey-div">
            <div>
                <span>
                    Your organisation's homepage is your brand presence and gateway to
                    communicating with end-users within Lumi World.
                </span><br><br>
                <span class="subtitle">
                    Organisational data
                </span><br>

                <input type="text" placeholder="Name of organisation" id="txt-organisation-name" />
                <small>The name of your organisation as you would like it to be displayed on your organisation's homepage within Lumi World.</small>
                <input type="text" placeholder="Primary contact telephone number" id="txt-tel" />
                <small>The primary contact phone number that end-users may use to call your organisation directly from within Lumi World.</small>
                <input type="email" placeholder="Primary contact email address" id="txt-org-email" />
                <small>The primary contact email address that end-users may use to email your organisation directly from within Lumi World.</small>
                <input type="text" placeholder="Organisation website" id="txt-website" />
                <small>Your organisation's website appears on your organisation homepage within Lumi World</small>
                <input type="text" placeholder="Company Registration No. or Owner's ID number" id="txt-reg" />
                <small>We use your company registration no. or ID number internally to uniquely identify your organisation and prevent misrepresentation.</small>
                <br><br>
                <span class="subtitle">
                    Organisation description & logo
                </span><br>
                <textarea id="txt-description" placeholder="Organisation Description" style="height: 60px"></textarea>
                <small>A brief & concise summary description about your organisation to appear on your organisation homepage.</small>
                <div style="margin: 0; display: inline-block; width: 100%">
                    <canvas style="background-color: white; float: left" id="logo-canvas" width="160px" height="160px"></canvas>
                    <input style="display: none" type='file' id="browse-file" name="browse-file" value="Browse" />
                    <input type="button" value="Browse" id="btn-browse" class="big-button" style="background-color: #929293; margin-top: 50px"/>
                </div>
                <small>Your organisation's logo, for best results use a logo suitably formatted for display in a square orientation.</small>
            </div>
            <nav class="toolbar">
                <input id="btn-save-org" type="button" value="Continue" class="login-button fr" />
            </nav>
        </div>

        <div id="div-enterprise-sector" class="grey-div">
            <div>
                The final step - select a lifestyle category which most closely aligns to
                your organisation's core purpose.
                <br><br>
                Hint: choose a category that within which your end users will most likely
                expect to find your organisation.
                <br><br>
                <div style="margin: 0" id="sectors-div">
                </div>

            </div>
            <nav class="toolbar">
                <input id="btn-save-sector" type="button" value="Continue" class="login-button fr" />
                <br>
                <div style="margin-top: 2%;"><label style="float: right; text-align: right; display: inline-block; font-size: 14px" class="form-item"><input style="margin-right: 10px; width: 14px; height: 14px" type="checkbox" id="check-tc" />I agree to the <a target="_blank" href="termsandconditions.html">Terms & Conditions</a></label></div>

            </nav>
        </div>
        <div id="div-enterprise-done" class="grey-div">
            <div>
                You have successfully signed up your organisation on the Lumi World
                Basic Edition package.
                <br><br>

                As a security measure, your organisation will be activated within 24 hours
                after a validation check has been completed.
                <br><br>
                <!--To help you get familiar with the Luminet World Enterprise Portal in the
                meantime, have a look at the Quick Start Guide for a few handy tips,
                otherwise click on Skip to go straight to your organisation's portal.-->

            </div>
            <nav class="toolbar">
                <input id="btn-quick-guide" onclick="javascript:window.location.replace('quickguide.html')" type="button" value="View Quick Start Guide" class="login-button fr"/>
                <input id="btn-home" onclick="javascript:window.location.replace('login.php')" type="button" value="Login" class="login-button fr" style="margin-right: 10px"/>
            </nav>
        </div>
        <div class="modal-loading"><!-- Place at bottom of page --></div>
    </body>
</html>