<?php
    

?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8" />
		<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
		<title>Lumi World Admin | Add Enterprise</title>
		<meta name="description" content="">
		<meta name="viewport" content="width=device-width">
        <link rel="stylesheet" href="../css/bootstrap.min.css"/>
		<link rel="stylesheet" href="../css/main.css">
		<link rel="shortcut icon" href="favicon.ico" />
		 <link type="text/css" rel="stylesheet" href="css/bootstrap.css" />
    <link href="http://netdna.bootstrapcdn.com/font-awesome/4.0.3/css/font-awesome.css" rel="stylesheet">
    <link type="text/css" rel="stylesheet" href="css/font-style.css" />
    <script src="js/angular.js"></script>
    <script src="js/jquery-1.10.1.min.js"></script>
    <script src="js/followers.js"></script>
    <script src="js/bootstrap.js"></script>
		<!--<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.10.1/jquery.min.js"></script>-->
        <script src="../js/vendor/bootbox.min.js"></script>
		<!--<script src="../js/vendor/bootstrap.min.js"></script>-->
		<script src="../js/vendor/modernizr-2.6.2.min.js"></script>
		<script src="../js/sessionstorage.1.4.js"></script>
        <script src="../js/config.js"></script>
        <script src="../js/main.js"></script>
        
        <style>
            .dialog-class {
                margin-left: 250px;
                width: 750px;
                height: 270px;
	            margin-top: 175px;
            }
        </style>
        <script>
            $(document).ready(function () {
                init();
                setupLoadingScreen();
                countCharacters('txt-description', 'chars-left', 120);
                $('#txt-description').keyup(function () {
                    countCharacters('txt-description', 'chars-left', 120);
                });

                $('#txt-tel').change(function () {
                    $('#val-tel').css('display', validatePhone($(this).val()) ? 'none' : 'block');
                });
                $('#txt-email').change(function () {
                    $('#val-email').css('display', validateEmail($(this).val()) ? 'none' : 'block');
                });



                var getAllSectorsURL = "http://" + serverURL + ":11000/getallsectors";


                var allsector = [];

                $.ajax({
                    type: "GET",
                    url: getAllSectorsURL,
                    success: function (data) {
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

                //end of Category insert
                var inputErrors = new Array();
                function validateInput() {
                    if (!validateEmail($('#txt-email').val())) {
                        bootbox.alert('Invalid email address', function () {
                            $('#txt-email').focus();
                        });
                        return false;
                    }
                    //inputErrors[inputErrors.length] = 'Invalid email address';
                    if (!validatePhone($('#txt-tel').val())) {
                        bootbox.alert('Invalid phone number', function () {
                            $('#txt-tel').focus();
                        });
                        return false;
                    }
                    if ($('#txt-description').val().length > 120) {
                        bootbox.alert('Description is too long', function () {
                            $('#txt-description').focus();
                        });
                        return false;
                    }
                    //inputErrors[inputErrors.length] = 'Invalid phone number';
                    return true;
                }

                $('#cancel-button').click(function (e) {
                    window.location.replace('viewenterprises.php');
                });

                //Setup Enterprise Profile
                //Enterprise insert
                $('#save-button').click(function (e) {

                    if (!validateInput())
                        return false;
                    var canvas = document.getElementById("logocanvas");
                    var cover = document.getElementById("coverpage");

                    var imageDataURL = canvas.toDataURL();
                    var coverImageDataURL = cover.toDataURL();


                    var obj = JSON.parse(sessionStorage.getItem('enterprise'));

                    var dataEnterprise = JSON.stringify({
                        'name': $('#txt-name').val(),
                        'description': $('#txt-description').val(),
                        'sectorID': getSelectedSector(),
                        'enterpriseContactNumber': $('#txt-tel').val(),
                        'emailAddress': $('#txt-email').val(),
                        'companyRegistrationNumber': $('#txt-reg').val(),
                        'status': 1,
                        'logoURL': '" + serverURL + "/images/1.png',
                        'physicalAddress': [{
                            'Street': $('#txt-street').val(),
                            'Suburb': $('#txt-suburb').val(),
                            'City': $('#txt-city').val(),
                            'PostalCode': $('#txt-postal-code').val(),
                            'Province': $('#txt-province').val(),
                            'Country': $('#txt-country').val()
                        }],
                        'username': $('#txt-username').val(),
                        'password': $('#txt-password').val(),
                        'id': 4,
                        'key': 'insertenterprise'
                    });

                    bootbox.dialog({
                        message: "Do you want to continue your profile setup?",
                        title: "Profile Setup",
                        className: "dialog-class",
                        buttons: {
                            success: {
                                label: "Yes",
                                className: "btn-danger",
                                callback: function () {
                                    if ($('#txt-name').val() == "" || $('#txt-description').val() == "" || $('#txt-tel').val() == "" || $('#txt-email').val() == "" || $('#txt-reg').val() == "") {
                                        bootbox.alert("Please complete profile setup page", function () {
                                        });
                                    } else {
                                        var obj = JSON.parse(sessionStorage.getItem('enterprise'));

                                        createEnterpriseProfile(x, dataEnterprise, imageDataURL, obj, coverImageDataURL);
                                    }

                                }
                            },
                            danger: {
                                label: "No",
                                className: "btn-danger",
                                callback: function () {
                                    bootbox.alert("Profile Setup cancelled", function () {
                                    });

                                }
                            }

                        }
                    });

                });

                function createEnterpriseProfile(x, dataEnterprise, imageDataURL, obj, coverImageDataURL) {
                    console.log('dataEnterprise : ' + dataEnterprise + ' & imageDataURL : ' + imageDataURL + ' & coverImageDataURL : ' + coverImageDataURL);
                    $.ajax({
                        type: "POST",
                        url: "http://" + serverURL + ":11020/enterpriseprofile?logo=" + imageDataURL,
                        data: {
                            params: dataEnterprise
                        },

                        contentType: "application/x-www-form-urlencoded",
                        dataType: "json",

                        success: function (result) {

                            console.log(result);

                            if (result.toString() === "Enterprise inserted") {
                                var createDataEnterprise = JSON.stringify({
                                    'label': 'Enterprise',
                                    'key': "companyregistrationnumber",
                                    'properties': {
                                        'name': $('#txt-name').val(),
                                        'cell': $('#txt-tel').val(),
                                        'email': $('#txt-email').val(),
                                        'companyregistrationnumber': $('#txt-reg').val()
                                    }

                                });

                                $.ajax({// ajax call starts
                                    type: "POST",
                                    url: "http://" + serverURL + ":11100/createuniquenode", // JQuery loads serverside.php
                                    data: {
                                        params: createDataEnterprise
                                    }, // Send value of the clicked button
                                    contentType: "application/x-www-form-urlencoded",
                                    dataType: "json", // Choosing a JSON datatype
                                    success: function (res)// Variable data contains the data we get from serverside
                                    {
                                        window.location.replace('viewenterprises.php');
                                    }
                                });
                            }
                            else {
                                alert(result);
                            }


                        }
                    });

                }

                //end of Enterprise insert

                //Image Browser
                $('#browseFile').change(function imageSelect(evt) {
                    if (window.File && window.FileReader && window.FileList && window.Blob) {
                        var files = evt.target.files;

                        var result = '';
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
                                    var canvas = document.getElementById("logocanvas");
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
                });

                $('#browsecover').change(function (evt) {
                    if (window.File && window.FileReader && window.FileList && window.Blob) {
                        var files = evt.target.files;

                        var result = '';
                        var file;
                        for (var i = 0; i < files.length; i++) {
                            // if the file is not an image, continue
                            if (!file[i].type.match('image.*')) {
                                continue;
                            }

                            reader = new FileReader();
                            reader.onload = (function (tFile) {
                                return function (evt) {
                                    var canvas = document.getElementById("coverpage");
                                    var context = canvas.getContext('2d');

                                    var img = new Image();
                                    img.onload = function () {
                                        context.drawImage(img, 0, 0, 500, 300);

                                    };
                                    img.src = evt.target.result;

                                };
                            } (file));
                            reader.readAsDataURL(file);
                        }
                    } else {
                        alert('The File APIs are not fully supported in this browser.');
                    }
                });

            });

            function loadValues() {

            }

            function getSelectedSector() {
                return parseInt($("input:checked").val(), 10);
            }

            function setEnterpriseInfo() {

                $('#txt-tel').forceNumeric();
                $('#txt-reg').forceNumeric();
                $('#txt-name').focus();


                var canvasCover = document.getElementById("coverpage");
                var contextCover = canvasCover.getContext('2d');

                var imgCover = new Image();
                imgCover.src = oEnterprise.coverpage;

                contextCover.drawImage(imgCover, 0, 0, 500, 300);

                var canvasLogo = document.getElementById("logocanvas");
                var contextLogo = canvasLogo.getContext('2d');

                var imgLogo = new Image();
                imgLogo.src = oEnterprise.companyLogo;
                contextLogo.drawImage(imgLogo, 0, 0, 160, 160);

                if (oEnterprise != null) {

                    //console.log(oEnterprise);
                    $('#txt-reg').prop('readonly', oEnterprise.registrationNumber != null);
                    $('#txt-name').val(oEnterprise.name);
                    $('#txt-reg').val(oEnterprise.registrationnumber);
                    $('#txt-description').val(oEnterprise.description);
                    $('#txt-tel').val(oEnterprise.contactnumber);
                    $('#txt-email').val(oEnterprise.email);
                    if (typeof (oEnterprise.physicalAddress) != 'undefined') {
                        $('#txt-street').val(oEnterprise.physicalAddress.street);
                        $('#txt-city').val(oEnterprise.physicalAddress.city);
                        $('#txt-country').val(oEnterprise.physicalAddress.country);
                        $('#txt-postal-code').val(oEnterprise.physicalAddress.postalCode);
                        $('#txt-suburb').val(oEnterprise.physicalAddress.suburb);
                        $('#txt-province').val(oEnterprise.physicalAddress.province);
                    }
                }

            }
        </script>
    </head>
    <body>
        <div class="navbar navbar-default navbar-fixed-top">
            <div class="container">
                <div class="navbar-header">
                    <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                    </button>
                    <a href="#" class="navbar-brand">Lumi World MIS</a>
                </div>

                <div class="navbar-collapse collapse">
                    <ul class="nav navbar-nav">
                        <li><a href="Dashboard.html"><i class="fa fa-tachometer"></i> Dashboard</a></li>
                        <li><a href="newsFeeds.html"><i class="fa fa-envelope"></i> News Feeds</a></li>

                        <li class="dropdown">
                            <a class="dropdown-toggle" href="3" data-toggle="dropdown"><i class="fa fa-building-o"></i> Enterprise<b class="caret"></b></a>
                            <ul class="dropdown-menu">
                                <li><a href="editenterprise.php">Add Enterprise</a></li>
                                <li><a href="Enterprises.html">View Enterprises</a></li>
                                <li><a href="editEnterprise.html">Edit Enterprise</a></li>
                                <li><a href="releaseEnterprise.html">Release Enterprise</a></li>
                            </ul>
                        </li>

                        <li><a href="Consumers.html"><i class="fa fa-tablet"></i> Consumer</a></li>
                        <li><a href="#"><i class="fa fa-users"></i> Users</a></li>
                        <li><a href="systemsCheck.html"><i class="fa fa-gears"></i>System Check</a></li>
                    </ul>
                </div>
            </div>
        </div>

        <div class="" style="padding-left: 17pt; margin-top: 55px;">
				<div class="inner-content">
					<div class="content-header"><span>Enterprise Profile</span><input id="save-button" type="button" value="save" class="login-button fr" /><input id="cancel-button" type="button" value="cancel" class="login-button fr" /></div>
                    <h4>Enterprise Contact Information</h4>
                    <table style="width:  fill-available">
                        <tr><td class="form-label">Username:</td><td class="privateidtext"><input class="privatetextstyle" type="email" placeholder="eg. user@enterprise.com" id="txt-username" /></td><td></td></tr>
                        <tr><td class="form-label">Password:</td><td class="privateidtext"><input class="privatetextstyle" type="password" placeholder="password" id="txt-password" /></td><td></td></tr>
                        <tr><td class="form-label">Enterprise Name:</td><td class="privateidtext"><input class="privatetextstyle" type="text" placeholder="e.g. ACME Products, Inc." id="txt-name" /></td><td></td></tr>
                        <tr><td class="form-label">Short Description</td><td><textarea class="privateidtext" style="height: 100px" placeholder="Short Description" id="txt-description"></textarea><div id="chars-left" style="text-align: right; padding-right: 10px">90 characters left</div></td><td></td></tr>
                        <tr><td class="form-label">Company Registration No.</td><td><input class="privatetextstyle" type="text" placeholder="e.g. 40872957211" id="txt-reg" /></td><td></td></tr>
                        <tr><td class="form-label">Switchboard number:</td><td><input class="privatetextstyle" type="text" placeholder="e.g. +27 (12) 123 4567" id="txt-tel" /></td><td><span style="display: none" class="input-error" id="val-tel">Invalid telephone number</span></td></tr>
                        <tr><td class="form-label">Contact email address:</td><td><input class="privateidtext" type="text" placeholder="e.g. info@acme.com" id="txt-email" /></td><td><span style="display: none" class="input-error" id="val-email">Invalid email address</span></td></tr>
                        <tr><td class="form-label">Physical Address:</td><td><input class="privateidtext" type="text" placeholder="Street" id="txt-street" /></td><td></td></tr>
                        <tr><td class="form-label"></td><td><input type="text" class="privateidtext" placeholder="Suburb" id="txt-suburb" /></td><td></td></tr>
                        <tr><td class="form-label"></td><td><input type="text" class="privateidtext" placeholder="City" id="txt-city" /></td><td></td></tr>
                        <tr><td class="form-label"></td><td><input type="text" placeholder="Postal Code" class="privateidtext" id="txt-postal-code" /></td><td></td></tr>
                        <tr><td class="form-label"></td><td><input type="text" placeholder="Province" class="privateidtext" id="txt-province" /></td><td></td></tr>
                        <tr><td class="form-label"></td><td><input type="text" placeholder="Country" class="privateidtext" id="txt-country" /></td><td></td></tr>
                    </table>

                    <h4>Enterprise Homepage</h4>
                    <span >Enterprise Logo:</span>
                    <div class="profilemarginstop" id="canvasdiv">
					    <canvas id="logocanvas" width="160px" height="160px"></canvas>
				    </div>
				    <div class="profilemarginstop" style="position: relative" class="clearfix">
					    <input type='file' id="browseFile" name="browseFile" />
				    </div>
                    <div style="display: none"><br><br><br>
                        <span>Enterprise Cover</span>
				        <div class="profilemarginstop" id="coverdiv">
					        <canvas id="coverpage" width="500" height="300"></canvas>
				        </div>
				        <div class="profilemarginstop" class="clearfix">
					        <input type='file' id="browsecover" name="browsecover" />
				        </div>
                    </div>
                    <h4>Lumi World Category Selection</h4>
                    <span>Choose a Lumi World category that most closely align to your organisation’s proposition to your consumers and/or citizens:</span>
                    <br><br>
                    <div id="sectors-div">
                    </div>
				</div>
            </div>
        <div class="login-loading"><!-- Place at bottom of page --></div>
    </body>
</html>
