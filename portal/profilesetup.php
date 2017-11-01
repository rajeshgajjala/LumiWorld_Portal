<!DOCTYPE html>
<!--[if lt IE 7]>      <html class="no-js lt-ie9 lt-ie8 lt-ie7"> <![endif]-->
<!--[if IE 7]>         <html class="no-js lt-ie9 lt-ie8"> <![endif]-->
<!--[if IE 8]>         <html class="no-js lt-ie9"> <![endif]-->
<!--[if gt IE 8]>      <html class="no-js"> <![endif]-->
<html lang="en">
    <head>
        <title>Profile Setup | Lumi World</title>
        <?php include('includes/head.php') ?>
        <script>

            $(document).ready(function () {
                setupLoadingScreen();
                getEnterpriseInfo();
                getCustomLinks();
                countCharacters('txt-description', 'chars-left', 140);
                $('#txt-description').keyup(function () {
                    countCharacters('txt-description', 'chars-left', 140);
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
                        var oEnterprise = JSON.parse(sessionStorage.getItem('enterprise'));
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
                            checkbox.checked = sectorId == oEnterprise.sectorId;
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
                    window.location.replace('search.php');
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
                        'id': '6',
                        'number': $('#txt-tel').val(),
                        'email': $('#txt-email').val(),
                        'registrationnumber': $('#txt-reg').val(),
                        'status': '1',
                        'url': '" + serverURL + "/images/' + obj.id + '.png',
                        'key': 'insertenterprise',
                        'physicalAddress': [{
                                'street': $('#txt-street').val(),
                                'suburb': $('#txt-suburb').val(),
                                'city': $('#txt-city').val(),
                                'postalCode': $('#txt-postal-code').val(),
                                'province': $('#txt-province').val(),
                                'country': $('#txt-country').val()
                            }]
                    });
                    var dataEnterpriseUpdate = JSON.stringify({
                        'name': $('#txt-name').val(),
                        'description': $('#txt-description').val(),
                        'id': getSelectedSector(),
                        'number': $('#txt-tel').val(),
                        'email': $('#txt-email').val(),
                        'registrationnumber': $('#txt-reg').val(),
                        'status': 4,
                        'url': serverURL + '/images/' + obj.id + '.png',
                        //'package':'Basic',
                        'physicalAddress': [{
                                'Street': $('#txt-street').val(),
                                'Suburb': $('#txt-suburb').val(),
                                'City': $('#txt-city').val(),
                                'PostalCode': $('#txt-postal-code').val(),
                                'Province': $('#txt-province').val(),
                                'Country': $('#txt-country').val()
                            }],
                        'key': 'updateenterprise',
                        'change': $('#txt-name').val()
                    });
                    bootbox.dialog({
                        message: "Do you want to continue your profile setup?",
                        title: "Profile Setup",
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

                                        if (obj.name == $('#txt-name').val()) {
                                            updateEnterpriseProfile(dataEnterpriseUpdate, imageDataURL, obj, coverImageDataURL, setEnterpriseInfo);
                                      
                                        } else if (obj.registrationnumber == null) {
                                            createEnterpriseProfile(x, dataEnterprise, imageDataURL, obj, coverImageDataURL);
                                        }

                                        init();
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

                function updateEnterpriseProfile(dataEnterpriseUpdate, imageDataURL, obj, coverImageDataURL, updateDataFunction) {
                    $.ajax({
                        type: "POST",
                        url: "http://" + serverURL + ":11020/enterpriseprofile?" + "logo=" + imageDataURL,
                        data: {
                            params: dataEnterpriseUpdate
                        },
                        contentType: "application/x-www-form-urlencoded",
                        dataType: "json",

                        success: function (data) {
                            console.log(dataEnterpriseUpdate);
                            console.log("DATA = " + data);
                            dataEnterpriseUpdate = JSON.parse(dataEnterpriseUpdate);
                            var addDataEnterprise = JSON.stringify({
                                'nodeLabel': 'Enterprise',
                                'nodeKey': "companyregistrationnumber",
                                'nodeKeyValue': obj.registrationnumber,
                                'properties': {
                                    'name': dataEnterpriseUpdate.name,
                                    'cell': dataEnterpriseUpdate.number,
                                    'email': dataEnterpriseUpdate.email
                                }

                            });
                            $.ajax({// ajax call starts
                                type: "POST",
                                url: "http://"  + serverURL + ":12101/addnodeproperties", // JQuery loads serverside.php
                                data: {
                                    params: addDataEnterprise
                                }, // Send value of the clicked button
                                contentType: "application/x-www-form-urlencoded",
                                dataType: "json", // Choosing a JSON datatype
                                success: function (data)// Variable data contains the data we get from serverside
                                {
                                    oEnterprise.companyLogo = imageDataURL;
                                    console.log(data);

                                    sessionStorage.setItem('enterprise', JSON.stringify(updateDataFunction(dataEnterpriseUpdate)));
                                    var count = 0;
                                    var doneUpdate = function(){
                                        count++;
                                        console.log('Count = ' + count);
                                        if(count === 2)
                                            window.location.href = 'search.php';
                                    };
                                    updateCustomLinks(doneUpdate);
                                    removeCustomLinks(doneUpdate);

                                }
                            });

                        }
                    });
                }

                function removeCustomLinks(doneCall) {
                    var count = 0;
                    if(removeLinks.length === 0)
                        doneCall();
                    $(removeLinks).each(function (index, link) {
                        if (link.hasOwnProperty('id')) {
                            var customLink = {
                                key: 'deleteservice',
                                serviceID: link.id,
                                status: 0
                            }
                            $.ajax({
                                type: "POST",
                                url: 'http://' + serverURL + ':12004/service',
                                data: {
                                    params: JSON.stringify(customLink)
                                },
                                contentType: 'application/x-www-form-urlencoded',
                                dataType: 'json',
                                success: function (result) {
                                    console.log('removed');
                                    console.log(result);
                                    count++;
                                    if (count === removeLinks.length)
                                        doneCall();
                                },
                                error: function (e) {
                                    console.log('couldn\'t reach server');
                                    console.log(e);
                                }
                            });
                        }
                        else {
                            count++;
                            if (count === removeLinks.length)
                                doneCall();
                        }
                    });
                }

                function updateCustomLinks(doneCall) {
                    var count = 0;
                    if(customLinks.length === 0)
                        doneCall();
                    $(customLinks).each(function (index, item) {
                        if (!item.hasOwnProperty('id')) {
                            var customLink = {
                                key: 'insertservice',
                                serviceName: item.linkName,
                                url: item.linkURL,
                                status: 1,
                                companyRegistrationNumber: oEnterprise.registrationnumber
                            }
                            $.ajax({
                                type: "POST",
                                url: 'http://' + serverURL + ':12004/service',
                                data: {
                                    params: JSON.stringify(customLink)
                                },
                                contentType: 'application/x-www-form-urlencoded',
                                dataType: 'json',
                                success: function (result) {
                                    console.log('updated');
                                    console.log(result);
                                    count++;
                                    if(count === customLinks.length)
                                        doneCall();
                                },
                                error: function (e) {
                                    console.log('couldn\'t reach server');
                                    console.log(e);
                                }
                            });
                        }
                        else{
                            count++;
                            if(count === customLinks.length)
                                doneCall();
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
                                    var max = 160;
                                    var img = new Image();
                                    img.onload = function () {

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
                return $("input:checked").val();
            }

            function setEnterpriseInfo() {
                oEnterprise = JSON.parse(sessionStorage.getItem('enterprise'));
                oEnterprise.description = $('#txt-description').val();
                oEnterprise.contactnumber = $('#txt-tel').val();
                oEnterprise.email = $('#txt-email').val();
                oEnterprise.sectorId = getSelectedSector();
                oEnterprise.companyLogo = document.getElementById("logocanvas").toDataURL();
                oEnterprise.physicalAddress = {};
                oEnterprise.physicalAddress.Street = $('#txt-street').val();
                oEnterprise.physicalAddress.City = $('#txt-city').val();
                oEnterprise.physicalAddress.Country = $('#txt-country').val();
                oEnterprise.physicalAddress.PostalCode = $('#txt-postal-code').val();
                oEnterprise.physicalAddress.Suburb = $('#txt-suburb').val();
                oEnterprise.physicalAddress.Province = $('#txt-province').val();

                return oEnterprise;

            }

            function getEnterpriseInfo() {

                $('#txt-tel').forceNumeric();
                $('#txt-reg').forceNumeric();
                $('#txt-name').focus();

                var oEnterprise = JSON.parse(sessionStorage.getItem('enterprise'));



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
                console.log(oEnterprise);
                if (oEnterprise != null) {
                    $('#txt-reg').prop('readonly', oEnterprise.registrationNumber != null);
                    $('#txt-name').val(oEnterprise.name);
                    $('#txt-reg').val(oEnterprise.registrationnumber);
                    $('#txt-description').val(oEnterprise.description);
                    $('#txt-tel').val(oEnterprise.contactnumber);
                    $('#txt-email').val(oEnterprise.email);

                    if (typeof (oEnterprise.physicalAddress) != 'undefined') {
                        $('#txt-street').val(oEnterprise.physicalAddress.Street);
                        $('#txt-city').val(oEnterprise.physicalAddress.City);
                        $('#txt-country').val(oEnterprise.physicalAddress.Country);
                        $('#txt-postal-code').val(oEnterprise.physicalAddress.PostalCode);
                        $('#txt-suburb').val(oEnterprise.physicalAddress.Suburb);
                        $('#txt-province').val(oEnterprise.physicalAddress.Province);
                    }
                }

            }
            var customLinks = [],
            removeLinks = [];
            function getCustomLinks() {
                var getLinks = {
                    key: 'getServiceByEnterpriseID',
                    enterpriseID: oEnterprise.id
                }
                $.ajax({
                    type: "POST",
                    url: 'http://' + serverURL + ':12004/service',
                    data: {
                        params: JSON.stringify(getLinks)
                    },
                    contentType: 'application/x-www-form-urlencoded',
                    dataType: 'json',
                    success: function (result) {
                        customLinks = [];
                        for (var i = 0; result.hasOwnProperty('length') && i < result.length; i++) {
                            if(result[i] !== null && result[i].hasOwnProperty('id'))
                                customLinks[customLinks.length] = { id: result[i].id, linkName: result[i].name, linkURL: result[i].url };
                        }
                        renderCustomLinks();
                        console.log(result);
                    },
                    error: function (e) {
                        console.log('couldn\'t reach server');
                        console.log(e);
                    }
                });

            }

            function insertCustomLink() {
                var linkName = $('#txt-custom-link-name').val();
                var linkURL = $('#txt-custom-link-url').val();
                linkURL = (linkURL.toString().indexOf('://') < 0 ? 'http://' : '') + linkURL;
                //var hhh = "";
                //hhh
                //if(linkURL.toString().indexOf('http://'))
                if (linkName !== '' && linkURL !== '') {
                    customLinks[customLinks.length] = {
                        linkName: linkName,
                        linkURL: linkURL
                    };
                    renderCustomLinks();
                    $('#txt-custom-link-name').val('');
                    $('#txt-custom-link-url').val('');
                }
            }
            function renderCustomLinks() {
                var linksHTML = '';
                $(customLinks).each(function (index, item) {
                    var linkRow = '';
                    linkRow += '<input type="text" disabled="disabled" value="' + item.linkName + '" />';
                    linkRow += '<span>URL</span>';
                    linkRow += '<input type="text" disabled="disabled" value="' + item.linkURL + '" />';
                    linkRow += '<input type="button" value="remove" class="login-button" onclick="removeCustomLink(' + index + ')" /><br />';
                    linksHTML += linkRow;
                });
                $('#custom-links').html(linksHTML);
            }
            function removeCustomLink(index) {
                removeLinks[removeLinks.length] = customLinks[index];
                customLinks.splice(index, 1);
                renderCustomLinks();
            }

        </script>
    </head>

    <body style="background-color: #767777;">
        <?php
        include('includes/topmenu.php');
        ?>
        <section class="content">
            <?php include('includes/leftmargin.php') ?>
            <!-- Main content -->
            <div class="main-content">
                <div class="inner-content">
                    <div class="content-header"><span>Enterprise Profile</span><input id="save-button" type="button" value="save" class="login-button fr" /><input id="cancel-button" type="button" value="cancel" class="login-button fr" /></div>
                    <h4>Enterprise Contact Information</h4>
                    <table style="width:  fill-available">
                        <tr><td class="form-label">Enterprise Name:</td><td class="privateidtext"><input class="privatetextstyle" type="text" placeholder="e.g. ACME Products, Inc." id="txt-name" disabled="disabled" /></td><td></td></tr>
                        <tr><td class="form-label">Short Description</td><td><textarea class="privateidtext" style="height: 100px" placeholder="Short Description" id="txt-description"></textarea><div id="chars-left" style="text-align: right; padding-right: 10px">90 characters left</div></td><td></td></tr>
                        <tr><td class="form-label">Company Registration No.</td><td><input class="privatetextstyle" type="text" placeholder="e.g. 40872957211" id="txt-reg" disabled="disabled" /></td><td></td></tr>
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
                    <br />
                    <div>
                        <h4>Custom Links on your Enterprise Homepage</h4>
                        <span>You may insert one or more custom links onto your organisation’s homepage within Lumi World. Custom links provide a way to add value to your customer’s user experience by quickly and efficiently directing them to useful online services you may already offer. It is recommended to only insert custom links to existing mobile-friendly mobi sites.</span><br /><br />
                        <span>Insert a New Custom Link</span><br />
                        <input type="text" placeholder="Custom link name..." id="txt-custom-link-name" />
                        <span>URL</span>
                        <input type="text" placeholder="Enter URL" id="txt-custom-link-url" />
                        <input type="button" value="Insert" class="login-button" onclick="insertCustomLink()" />
                        <br />
                        <span>Manage Existing Custom Links</span>
                        <div id="custom-links">
                        </div>
                    </div>
                </div>
            </div>
        </section>


        <script>
            window.jQuery || document.write('<script src="js/vendor/jquery-1.10.1.min.js"><\/script>');
        </script>

        <script src="js/plugins.js"></script>
        <script src="js/main.js"></script>
        <script src="js/bootbox.js"></script>
        <script src="js/bootstrap.js"></script>
        <div class="modal-loading"><!-- Place at bottom of page --></div>
    </body>
</html>
