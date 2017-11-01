var totalConnectedUsers = 0,
    totalFeeds = 10,
    perPage = 10,
    currentPage = 0;

var connectedUsers = {
    data: [],
    results: [],
    perpage: 20,
    current: 0
};

$(document).ready(function () {
    //overlay();
    setupLoadingScreen();
    //startRTE();
    setDefaults();
    gettotalConnectedUsers();
    getConnectedUsersList();
    getTotalFeeds();
    $('body').click(function () {
        $('#live-search').css('display', 'none');
        $('#txt-search').val('');
        $('#live-search-groups').css('display', 'none');
        $('#txt-search-groups').val('');
    });
    $('#search-customers').click(searchCustomer);
    $('#txt-message').keyup(function () {
        countCharacters('txt-message', 'chars-left', 500);
    });


    tinymce.init({
        object_resizing: false,
        selector: '#txt-rte',
        menubar: false,
        statusbar: false,
        plugins: ["textcolor", "image", "link", "anchor"],
        toolbar: "insertfile undo redo | styleselect | bold italic underline | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image | forecolor backcolor emoticons | anchor",
        setup: function (ed) {
            ed.on('keyup', function (ed, e) {
                var text = stripHTMLTags(tinyMCE.get('txt-rte').getContent());
                countCharactersText(text, 'chars-left', 500);
            });
        },
        width: 320,
        height: 460
    });

    $('#reach-connected').click(function () {
        addGroup();
    });

    $('#rad-groups').change(function () {
        if ($(this).is(':checked')) {
            $('#lbl-recipients').text('Recipients Groups');
            $('#groups-container').css('display', 'block');
            $('#individuals-container').css('display', 'none');
            recipients = new Array();
            $('#selected-numbers').html('');
        }
    });
    $('#rad-individual').change(function () {
        if ($(this).is(':checked')) {
            $('#lbl-recipients').text('Recipients List');
            $('#groups-container').css('display', 'none');
            $('#individuals-container').css('display', 'block');
            recipients = new Array();
            $('#selected-numbers').html('');
        }
    });
    $('#rad-rte').click(function () {
        if ($(this).is(':checked')) {
            toggleRTE(true);
        }
    });
    $('#rad-plain').click(function () {
        if ($(this).is(':checked')) {
            toggleRTE(false);
        }
    });
    
    $('#rad-no-expiry').click(function () {
        if ($(this).is(':checked')) {
            $('#cont-message-lifetime').css('display', 'none');
        }
    });

    $('#rad-limited').click(function () {
        if ($(this).is(':checked')) {
            $('#cont-message-lifetime').css('display', 'block');
        }
    });

    $('#rad-plain').click(function () {
        if ($(this).is(':checked')) {
            toggleRTE(false);
        }
    });

    $('#overlay div').click(function (evt) {
        evt.preventDefault();
        evt.stopPropagation();
    });
    $('#overlay').click(overlay);

    $('#txt-search').keyup(function () {
        localCustomerSearch($(this).val());
    });

    $('#txt-search-groups').keyup(function () {
        localGroupsSearch($(this).val());
    });
    $('#combo-groups').change(function () {
        addGroup($(this).val());
    });
    

});

function localCustomerSearch(q) {
    q = q.toLowerCase();
    connectedUsers.results = [];
    if(q.length === 0){
        $('#live-search').css('display', 'none');
        return;
    }
       
    $(connectedUsers.data).each(function (index, item) {
        //console.log(item.displayName.contains(q));
        if (item.displayName.toLowerCase().indexOf(q) != -1 ||
                                   item.firstName.toLowerCase().indexOf(q) != -1 ||
                                   item.lastName.toLowerCase().indexOf(q) != -1 ||
                                   item.cell.toLowerCase().indexOf(q) != -1) {
            connectedUsers.results[connectedUsers.results.length] = item;
        }

    });
    connectedUsers.current = 0;
    renderSearchResults();
}

function renderSearchResults(){
    $('.search-results ul').html('');
    var f = false;
    $(connectedUsers.results).each(function (index, item) {
        $('.search-results ul').append('<li value="' + item.index + '">' + (item.hasOwnProperty('firstName') && item.firstName.toString().length > 0 ? item.firstName : item.cell) + '</li>')
        f = !f;
    });
    $('.search-results').css('display', 'inline-block');
    $('#live-search').css('display', 'block');
    $('.search-results li').click(function () {
        if (!arrayHasObject(recipients, connectedUsers.data[$(this).val()])) {
            recipients[recipients.length] = connectedUsers.data[$(this).val()];
            renderRecipients();
            $('#live-search').css('display', 'none');
            $('#txt-search').val('');
        }

    });
}

function localGroupsSearch(q) {
    q = q.toLowerCase();
    if(q.length === 0){
        $('#live-search').css('display', 'none');
        return;
    }
    groupResults = [];
    $(groups).each(function (index, item) {
        //console.log(item.displayName.contains(q));
        if (item.name.toLowerCase().indexOf(q) != -1) {
            groupResults[groupResults.length] = item;
        }

    });
    renderSearchResultsGroups();
}
var groupResults = [];
function renderSearchResultsGroups(){
    $('.search-results-groups ul').html('');
    var f = false;
    $(groupResults).each(function (index, item) {
        $('.search-results-groups ul').append('<li value="' + index + '">' + item.name + ' (' + item.consumers.length + ')' + '</li>');
        f = !f;
    });
    $('.search-results-groups').css('display', 'inline-block');
    $('#live-search-groups').css('display', 'block');
    $('.search-results-groups li').click(function () {
        if (!arrayHasObject(recipients, groupResults[$(this).val()])) {
            recipients[recipients.length] = groupResults[$(this).val()];
            renderRecipients();
            $('#live-search-groups').css('display', 'none');
            $('#txt-search-groups').val('');
        }

    });
}


/*
function getConnectedUsersList() {
    connectedUsers = JSON.parse(sessionStorage.getItem('connectedUsers'));
    if(connectedUsers !== null && typeof(connectedUsers) !== 'undefined' && connectedUsers.hasOwnProperty('data') && connectedUsers.data.length > 0){
        totalConnectedUsers = connectedUsers.data.length;
        $('.number-connected').text(totalConnectedUsers);
        return;
    }

    connectedUsers = {
        data: [],
        results: [],
        perpage: 20,
        current: 0
    };
    var dataListConnected = JSON.stringify({
        'searchKey': 'companyregistrationnumber',
        'searchKeyValue': oEnterprise.registrationnumber,
        'nodeFromLabel': 'Consumer',
        'nodeToLabel': 'Enterprise',
        'fieldsToReturn': '[c.displayName,c.firstName,c.lastName,c.cell]'

    });

    $.ajax({// ajax call starts
        type: "POST",
        url: "http://" + serverURL + ":11014/getConnectedNodeByEnterprise", // + "?" + "username=" + "LFritz" + "&" + "password=" + "l3lFUtYM" + "&" + "mobilemessage=" + "Hello" + "&" + "mobilenumber=" + "27741869609", // JQuery loads serverside.php
        data: { params: dataListConnected }, // Send value of the clicked button
        contentType: "application/x-www-form-urlencoded",
        dataType: "json",
        success: function (results) {
            connectedUsers.results = [];
            $(results).each(function (index, item) {
                $(item).each(function (j) {
                    if (item[j] == null || typeof (item[j]) === 'undefined')
                        item[j] = '';
                    //item[j] = (j == 0 ? '*JD' : (j == 1 ? '*John' : '*Doe'));
                });
                connectedUsers.data[index] = connectedUsers.results[index] = {
                    displayName: item[0].toString(),
                    firstName: item[1].toString(),
                    lastName: item[2].toString(),
                    cell: item[3].toString(),
                    checked: false,
                    index: index
                };

            });
            
            totalConnectedUsers = connectedUsers.data.length;
            //renderConnectedUsers()
            //testGrid(results);
        }
    });
    
}
*/
function toggleRTE(f){
    $('#div-rte').css('display', f ? 'block' : 'none');
    $('#txt-message').css('display', !f ? 'block' : 'none');
}

function startRTE() {
    $('#txt-rte').wysihtml5('deepExtend', {
        "font-styles": false, //Font styling, e.g. h1, h2, etc. Default true
        "emphasis": true, //Italics, bold, etc. Default true - I have removed underline
        "justify": true, //Buttons for text justification
        "lists": true, //(Un)ordered lists, e.g. Bullets, Numbers. Default true
        "html": true, //Button which allows you to edit the generated HTML. Default false
        "link": true, //Button to insert a link. Default true
        "image": true, //Button to insert an image. Default true,
        "color": false, //Button to change color of font        

        "events": {
            "load": function () {
                console.log("RTE Loaded!");
            }
        }

    });
}


function getTotalFeeds() {
    var oEnterprise = JSON.parse(sessionStorage.getItem('enterprise'));
    var datatotal = JSON.stringify({
        'enterpriseID': oEnterprise.id
    });

    $.ajax({
        type: "POST",
        url: "http://" + serverURL + ":12003/getTotalNumberOfNewsfeed",
        data: {
            params: datatotal
        }, 
        contentType: "application/x-www-form-urlencoded",
        dataType: "json", 
        success: function (result)
        {
            totalFeeds = result[0].count;
        }
    });
}

function gettotalConnectedUsers() {
    var oEnterprise = JSON.parse(sessionStorage.getItem('enterprise'));
    var dataRelationshipSearch = JSON.stringify({
        'searchKey': 'companyregistrationnumber',
        'searchKeyValue': oEnterprise.registrationnumber,
        'relationshipType': 'Connected',
        'nodeToLabel': 'Enterprise',
        'nodeFromLabel': 'Consumer'
    });
    $.ajax({// ajax call starts
        type: "POST",
        url: "http://" + serverURL + ":12014/getrelationshipcount", // JQuery loads serverside.php
        data: {
            params: dataRelationshipSearch
        }, // Send value of the clicked button
        contentType: "application/x-www-form-urlencoded",
        dataType: "json", // Choosing a JSON datatype
        success: function (result)// Variable data contains the data we get from serverside
        {
            $('.number-connected').text(result);
            totalConnectedUsers = result[0];
            console.log(result[0]);
        }
    });
}

function searchCustomer() {
    $('#individual-results-table').html('');
    //$('#total-results').text('No existing user found');
    $('#individual-search-results').css('display', 'none');
    $('#search-results').css('display', 'none');

    $('.small-close-button').click(function () {
    });

    var dataConnectedIndividual = JSON.stringify({
        'searchKey': 'cell',
        'searchKeyValue': $('#txt-search').val(),
        'nodeFromLabel': 'Consumer',
        'nodeToLabel': 'Enterprise',
        'relationship': 'Connected',
        'searchKeyEnterprise': 'companyregistrationnumber',
        'searchKeyValueEnterprise': oEnterprise.registrationnumber
    });


    $.ajax({// ajax call starts
        type: "POST",
        url: "http://" + serverURL + ":12014/getConnectedNode",
        data: {
            params: dataConnectedIndividual
        },
        contentType: "application/x-www-form-urlencoded",
        dataType: "json",
        success: function (result) {

            var output = JSON.stringify(result);

            if (result[0] == 1) {
                $('#total-results-container').css('display', 'none');
                renderResults();
            }
            else {
                $('#total-results-container').css('display', 'block');
            }

            $('#individual-search-results').css('display', 'block');
        }
    });
}

function renderResults() {
    $('#search-results').css('display', 'inline-block');
    //$('#search-results').css('width', '80%');
    $('#search-customers').blur();
    var searchHTML = '<table style="width: 88%;">';
    searchHTML += '<tr><td>Mobile No.</td><td>Status</td><td></td></tr>';
    searchHTML += '<tr><td style="width: 20%; padding-left: 5px" class="odd-row" id="cell-number">' + $('#txt-search').val() + '</td><td class="odd-row">Connected</td><td style="width: 150px"><input id="btn-add-recipient" type="button" style="width: 100%" class="login-button" value="Add Recipient" onClick="addRecipient()" autofocus /></td></tr>';
    searchHTML += '</table>';
    $('#txt-search').val('');

    $('#individual-results-table').html(searchHTML);
    
    //$('#')
}

function removeRecipient(number) {
    //console.log(number);
    //var i = recipients.indexOf(number);
    //if (i >= 0)
    recipients.splice(number, 1);
    renderRecipients();
}

var recipients = new Array();
var groups = [];

function addGroup(index) {
    if (index < 0 || index >= groups.length)
        return;
    if ((!arrayHasObject(recipients, groups[index]))/* && recipients.length == 0*/) {
        recipients[recipients.length] = groups[index];
        renderRecipients();
    }
    
}

function addRecipient() {
    if (recipients.indexOf($('#cell-number').text()) < 0) {
        recipients[recipients.length] = $('#cell-number').text();
        renderRecipients();
    }

}

function renderRecipients() {
    var numbersHtml = '';
    console.log(recipients);
    $(recipients).each(function (index, item) {
        
        var name = item;
        if (item.hasOwnProperty('firstName'))
            name = item.firstName.toString().length > 0 ? (item.firstName + ' ' + item.lastName) : item.cell;
        else if (item.hasOwnProperty('name'))
            name = item.name;
        //var name = ((item.hasOwnProperty('firstName') && item.firstName.toString().length > 0) ? (item.firstName + ' ' + item.lastName) : item.cell);

        numbersHtml += '<div class="selected-number"><div style="float: left; display: block">' + name + '</div><div onClick="removeRecipient(' + index + ')" class="small-close-button"><img alt="close" src="img/73_circle cross.png" /></div></div>';
    });

    $('#selected-numbers').html(numbersHtml);
    sessionStorage.setItem('recipients', recipients);
}

function setDefaults() {

    
    var params = getUrlVars();
    
    if(params.hasOwnProperty('c')){
        var connectedUsers = JSON.parse(sessionStorage.getItem('connectedUsers'));

        recipients = [];
        $(connectedUsers.data).each(function (index, item) {
            if (item.checked)
                recipients[recipients.length] = item;
        });

        
        showScreen(1);
        renderRecipients();
    }
    else if (params.hasOwnProperty("n")) {
        //if(params["n"].indexOf(',' > 0))
        recipients = params["n"].toString().split('|');
        renderRecipients();
        showScreen(1);
    }
    else if (params.hasOwnProperty('g')) {
        $('#rad-groups').click();

        groups = JSON.parse(sessionStorage.getItem('groups'));
        addGroup(parseInt(params['g']));

        $('#groups-container').css('display', 'block');
        $('#individuals-container').css('display', 'none');
        
        showScreen(1);
    }
    else {
        showScreen(0);
    }


    $('#new-message').click(function () { showScreen(1) });
    $('#btn-next-recipients').click(function () {
        if(recipients.length > 0){
            showScreen(2);
        }
        else{
            bootbox.alert('Please add at least one recipient');
        }
        
    });

    $('#btn-next-compose').click(function () {
        if (getMessage().length > 500) {
            bootbox.alert('Your message exceeds character limit of 500');
            $('#txt-message').focus();
        }
        else if (getMessage().length == 0) {
            bootbox.alert('Can not send an empty message');
            $('#txt-message').focus();
        }
        else
            showScreen(3);
    });
    $('#btn-next-schedule').click(function () { showScreen(4) });
    $('#btn-back-send').click(function () { showScreen(3) });
    $('#btn-back-schedule').click(function () { showScreen(2) });
    $('#btn-back-compose').click(function () { showScreen(1) });

    $('#btn-send').click(sendIndivNewsFeed);


    getReachHistory();
    setMessageValidityValues()
}

function setMessageValidityValues(){
    var options = '';
    for(var i = 1; i < 32; i++){
        options += '<option>' + i +'</option>'
    }
    $('#message-life').html(options);
}

function getMessage(){
    //return $('#rad-plain').is(':checked') ? $('#txt-message').val() : $('.wysihtml5-sandbox').contents().find('body').text();
    
    return $('#rad-plain').is(':checked') ? $('#txt-message').val() : stripHTMLTags(tinyMCE.get('txt-rte').getContent());;
}

function getOriginalHTMLMessage() {
    return $('#rad-plain').is(':checked') ? $('#txt-message').val() : tinyMCE.get('txt-rte').getContent();
}

function getHTMLMessage() {
    var domObj = $('<div/>').html(getOriginalHTMLMessage());

    var aAttr = "";
    domObj.find('a').each(function (index) {
        

        aAttr = $(this).attr("href");
        $(this).removeAttr("href");
        $(this).removeAttr("rel");
        $(this).removeAttr("target");

        $(this).attr("onclick", "window.open('" + aAttr + "', '_system')");

        //console.log(index + ": " + $(this).attr("href") + " : " + $(this).attr("onclick"));
        
    });

    domObj.find('img').each(function (index) {
        //console.log(index + ": " + $(this).attr("href"));

        $(this).attr("width", "100%");

        //console.log(index + ": " + $(this).attr("href") + " : " + $(this).attr("onclick"));

    });

    return domObj.html();
}

function showScreen(idx) {
    $('#reach-history').css('display', idx == 0 ? 'block' : 'none');
    $('#reach-compose-recipients').css('display', idx == 1 ? 'block' : 'none');
    $('#reach-compose-message').css('display', idx == 2 ? 'block' : 'none');
    $('#reach-compose-schedule').css('display', idx == 3 ? 'block' : 'none');
    $('#reach-compose-send').css('display', idx == 4 ? 'block' : 'none');
    if (idx == 4)
        renderPreviewScreen();
    renderSteps(idx);
}

function sendIndivNewsFeed() {
    
    var params = new Object();
    var obj = JSON.parse(sessionStorage.getItem('enterprise'));
    params.body = getHTMLMessage();
    params.header = obj.name;
    params.status = 1;
    params.imageurl = obj.companyLogo;
    params.tag = "news feed";
    params.publishedtime = new Date();
    params.expirytime = new Date(Date.now() + 86400000 * 3);
    params.createdtime = new Date();
    params.createdtime = params.createdtime.getTime();
    params.expirytime = params.expirytime.getTime();
    params.publishedtime = params.publishedtime.getTime();
    params.id = obj.id;
    params.guid = generateUUID().toString();
    var totalReached = recipients.length;
    if(!document.getElementById('rad-individual').checked && recipients.length > 0 && recipients[0].hasOwnProperty('consumers')){
        totalReached = recipients[0].consumers.length;
    }
    
    

    var sendToAll = getObjectByProperty(recipients, 'guid', 'connected') !== null;
    var allRecipients = document.getElementById('rad-individual').checked ? recipients : [];
    if (!document.getElementById('rad-individual').checked) {
        //console.log("recipients");
        //console.log(recipients);
        $(recipients).each(function (index, item) {
            if (item.guid !== 'connected') {
                $(item.consumers).each(function (idx, itm) {
                    if (!arrayHasObject(allRecipients, itm)) {
                        allRecipients[allRecipients.length] = itm;
                        console.log(itm);
                        //console.log(allRecipients);
                    }
                });
            }
        });
        
        totalReached = allRecipients.length;
        if (sendToAll && totalConnectedUsers > totalReached)
            totalReached = totalConnectedUsers;
    }
    //console.log(allRecipients);
    //return;
    var dataNewsFeed = JSON.stringify({
        'body': params.body,
        'header': params.header,
        'status': params.status,
        'tag': params.tag,
        'publishedtime': params.publishedtime,
        'createdtime': params.createdtime,
        'expirytime': getMessageExpiryDate().getTime(),
        'id': params.id,
        'guid': params.guid,
        'reachedConsumers': totalReached

    });

    bootbox.dialog({
        message: "Are you sure you want to send this Reach Message?",
        title: "Confirmation",
        buttons: {
            success: {
                label: "Yes",
                className: "btn-danger",
                callback: function () {
                    if (params.body == "") {
                        bootbox.alert("Please insert a news feed message", function () {
                        });
                    } else {
                        console.log(dataNewsFeed);
                        $.ajax({
                            type: "POST",
                            url: "http://" + serverURL + ":12003/insertnewsfeed?" + "imageurl=" + params.imageurl,
                            data: {
                                data: dataNewsFeed
                            },
                            contentType: "application/x-www-form-urlencoded",
                            dataType: "json",

                            success: function (res) {
                                if (!res.hasOwnProperty("guid")) {
                                    alert('could not create a node');
                                    return;
                                }
                                var date = getServerDateNow();

                                var feed = {
                                    label: 'Newsfeed',
                                    key: "guid",
                                    properties: {
                                        guid: params.guid,
                                        time: date
                                    }
                                };

                                if (!document.getElementById('rad-no-expiry').checked) {
                                    feed.properties.expiryDate = getMessageExpiryDateString();
                                }
                                var createNewsfeed = JSON.stringify(feed);
                                console.log(createNewsfeed);
                                var obj = JSON.parse(sessionStorage.getItem('enterprise'));

                                var type = document.getElementById('rad-individual').checked ? 'Group' : 'Connected';   // Choose 1 - Group=Individuals, Connected=Self Explainatory



                                var createNewsFeedRelationship = JSON.stringify({
                                    'uniqueKey': 'ID',
                                    'uniqueKeyValue': obj.registrationnumber + "_" + params.guid + (sendToAll && recipients.length > 1 ? '_G' : ''),
                                    'fromNodeLabel': 'Enterprise',
                                    'fromNodeKey': 'companyregistrationnumber',
                                    'fromNodeKeyValue': obj.registrationnumber,
                                    'toNodeLabel': 'Newsfeed',
                                    'toNodeKey': 'guid',
                                    'toNodeKeyValue': params.guid,
                                    'relationshipType': 'Published',
                                    'properties': {
                                        'ID': obj.registrationnumber + "_" + params.guid + (sendToAll && recipients.length > 1 ? '_G' : ''),
                                        'time': date,
                                        'status': 1,
                                        'notificationStatus': 0,
                                        'type': sendToAll && recipients.length === 1 ? 'Connected' : 'Group'
                                    }
                                });

                                if (sendToAll && recipients.length > 1) {
                                    var createNewsFeedRelationshipConnected = JSON.stringify({
                                        'uniqueKey': 'ID',
                                        'uniqueKeyValue': obj.registrationnumber + "_" + params.guid + '_C',
                                        'fromNodeLabel': 'Enterprise',
                                        'fromNodeKey': 'companyregistrationnumber',
                                        'fromNodeKeyValue': obj.registrationnumber,
                                        'toNodeLabel': 'Newsfeed',
                                        'toNodeKey': 'guid',
                                        'toNodeKeyValue': params.guid,
                                        'relationshipType': 'Published',
                                        'properties': {
                                            'ID': obj.registrationnumber + "_" + params.guid + '_C',
                                            'time': date,
                                            'status': 1,
                                            'notificationStatus': 0,
                                            'type': 'Connected'
                                        }
                                    });
                                    createRelationship(createNewsFeedRelationshipConnected,
                                    function (data) {
                                        console.log('done');
                                    },
                                    function (error) {

                                    });
                                }




                                $.ajax({// ajax call starts
                                    type: "POST",
                                    url: "http://" + serverURL + ":12100/createuniquenode", // JQuery loads serverside.php
                                    data: {
                                        params: createNewsfeed
                                    }, // Send value of the clicked button
                                    contentType: "application/x-www-form-urlencoded",
                                    dataType: "json", // Choosing a JSON datatype
                                    success: function (data)// Variable data contains the data we get from serverside
                                    {
                                        if (!data.hasOwnProperty("guid")) {
                                            alert('could not create a node');
                                            console.log('could not create a node');
                                            console.log(data);
                                            console.log(createNewsfeed);
                                            return;
                                        }
                                        $.ajax({
                                            type: "POST",
                                            url: "http://" + serverURL + ":12103/createRelationship", // JQuery loads serverside.php
                                            data: {
                                                params: createNewsFeedRelationship
                                            },
                                            contentType: "application/x-www-form-urlencoded",
                                            dataType: "json",
                                            success: function (data) {
                                                if (!data.hasOwnProperty("id")) {
                                                    alert('could not create a relationship');
                                                    console.log('could not create a relationship');
                                                    console.log(data)
                                                    console.log(createNewsFeedRelationship)
                                                    return;
                                                }

                                                if (!sendToAll || document.getElementById('rad-individual').checked || recipients.length > 1) {
                                                    var temp = document.getElementById('rad-individual').checked ? recipients : allRecipients;

                                                    createIndividualRelationships(temp, params.guid, date);
                                                    if (!document.getElementById('rad-individual').checked) {
                                                        createGroupNewsfeedRelationship(params.guid, date)
                                                    }
                                                }
                                                else {
                                                    $.ajax({
                                                        type: "POST",
                                                        url: "http://" + serverURL + ":12600/push_connected?enterprise=" + oEnterprise.name + '&regno=' + oEnterprise.registrationnumber, // JQuery loads serverside.php
                                                        contentType: "application/x-www-form-urlencoded",
                                                        dataType: "json",
                                                        success: function (data) {
                                                            console.log(data);
                                                            window.location = "reach.php";
                                                        }
                                                    });
                                                }

                                            }
                                        });
                                    }
                                });
                            }
                        });
                    }
                }
            },
            danger: {
                label: "No",
                className: "btn-danger",
                callback: function () {
                    bootbox.alert("Sending of news feed cancelled", function () {
                    });

                }
            }

        }
    });
}

function getMessageExpiryDate(){
    
    
    var value = parseInt($('#message-life').val(), 10),
        unit  = $('#message-life-unit').val(),
        today = new Date();
        
    if (document.getElementById('rad-no-expiry').checked) {
        return today.addHours(8760);         // Leave it to the year
    }

    if (unit === 'days')
        return today.addHours(value * 24);
    else if (unit === 'hours')
        return today.addHours(value);
    else if (unit === 'weeks')
        return today.addHours(value * 168);     // 168 hours in a week
    else return today.addHours(24);
}

function getMessageExpiryDateString(){
    /*if (document.getElementById('rad-no-expiry').checked) {
        return getServerDate(today.addHours(8760));         // Leave it to the year
    }*/
    
    var value = parseInt($('#message-life').val(), 10),
        unit  = $('#message-life-unit').val(),
        today = new Date();

    if (unit === 'days')
        return getServerDate(today.addHours(value * 24));
    else if (unit === 'hours')
        return getServerDate(today.addHours(value));
    else if (unit === 'weeks')
        return getServerDate(today.addHours(value * 168));     // 168 hours in a week
    else return getServerDate(today.addHours(24));
}

function getTotalReachedGroupedUsers() {
    var allRecipients = [];
    $(recipients).each(function (index, item) {
        if (item.guid !== 'connected') {
            $(item.consumers).each(function (idx, itm) {
                if (!arrayHasObject(allRecipients, itm)) {
                    allRecipients[allRecipients.length] = itm;
                }
            });
        }
    });
    return allRecipients.length;
}

function getReachHistory() {

    $.ajax({
        type: "GET",
        url: "http://" + serverURL + ":12003/getnewsfeedbyenterpriseid?id=" + oEnterprise.id + "&limit=" + perPage + "&page=" + currentPage,
        contentType: "application/x-www-form-urlencoded",
        dataType: "json",
        success: function (result) {
            var odd = true;
            $('#history-body').html('');
            $(result).each(function (index) {
                console.log($(this)[0])
                var row = document.createElement('tr');
                $(row).attr('class', odd ? 'odd-row' : 'even-row');

                var typeCell = document.createElement('td');
                typeCell.appendChild(document.createTextNode('Message'));

                var bodyCell = document.createElement('td');
                bodyCell.appendChild(document.createTextNode($(this)[0].NewsFeedBody));
                bodyCell.innerHTML = $(this)[0].NewsFeedBody;

                var recipientsCell = document.createElement('td');
                recipientsCell.appendChild(document.createTextNode($(this)[0].reachedConsumers));

                var deliveryTimeCell = document.createElement('td');
                deliveryTimeCell.appendChild(document.createTextNode((new Date($(this)[0].createdTime).toString().substr(0, 21))));

                var expiryTimeCell = document.createElement('td');
                expiryTimeCell.appendChild(document.createTextNode((new Date($(this)[0].expiryTime).toString().substr(0, 21))));


                row.appendChild(typeCell);
                row.appendChild(bodyCell);
                row.appendChild(recipientsCell);
                row.appendChild(deliveryTimeCell);
                row.appendChild(expiryTimeCell);

                $('#history-body').append(row);

                odd = !odd;
            });

            initHistoryPagination();
        }
    });
}

function initHistoryPagination() {
    var pagerHtml = '',
        count = 0,
        i = 0;

    var totalPages = parseInt(totalFeeds / perPage, 10);

    for(i = 0; i < totalPages || i == totalPages && totalFeeds % perPage > 0 ; i++){
        //if (i > 0) pagerHtml += '|';
        pagerHtml += i == currentPage ? ('<span class="grid-page">' + (i + 1) + '</span>') : ('<a class="grid-page" href="javascript:openGridPage(' + i + ')">' + (i + 1) + '</a>');
    }
    $('#gird-pager').html(pagerHtml);

    $('#sel-page-limit').change(function () {
        perPage = $(this).val();
        //console.log(currentPage + ' ' + perPage + ' ' + totalFeeds)
        openGridPage(currentPage);
    });
}



function openGridPage(num){
    currentPage = num;
    getReachHistory();
}


function renderPreviewScreen(total){
    var indiv = document.getElementById('rad-individual').checked;
    var rec = '';
    $('#view-recipient-type').text(indiv ? 'Individuals:' : 'Groups:');
    var totalReached = recipients.length;
    if(indiv){
        var first = true;
        $(recipients).each(function (index, item) {
            if (!first)
                rec += ', ';
            rec += ((item.hasOwnProperty('firstName') && item.firstName.toString().length > 0) ? (item.firstName + ' ' + item.lastName) : item.hasOwnProperty('cell') ? item.cell : item);
            first = false;
        });
    }
    else{
        var conGrp = getObjectByProperty(recipients, 'guid', 'connected');
        totalReached = conGrp !== null ? conGrp.consumers.length : getTotalReachedGroupedUsers();
        $(recipients).each(function (index, item) {
            if (index !== 0)
                rec += ',';
            rec += item.name;
        });
    }
    
    
    var value = parseInt($('#message-life').val(), 10),
        unit = $('#message-life-unit').val();
    var lifetime = document.getElementById('rad-limited').checked ? value + ' ' + unit + ' from sending' : 'No expiry';
    
    $('#view-scheduling').text(lifetime);

    $('#view-all-recipients').text(rec);
    $('#view-message-body').text(getMessage());
    $('#recipients-count').text(totalReached);

}

function overlay() {


    document.getElementById('phone-header-logo').setAttribute('src', oEnterprise.companyLogo);
    $('.phone-header-name').text(oEnterprise.name);
    $('.phone-header-timestamp').text(getAppDate(new Date()));

    $('.phone-message').html(getHTMLMessage());

	el = document.getElementById("overlay");
	el.style.visibility = (el.style.visibility == "visible") ? "hidden" : "visible";
}

function createGroupNewsfeedRelationship(newsfeedGuid, date){
    var count = 0;
    $(recipients).each(function (index, grp) {
        if (grp.guid !== 'connected') {
            $(grp.consumers).each(function (idx, consumer) {
                var rel = {
                    'uniqueKey': 'ID',
                    'uniqueKeyValue': grp.guid + "_" + newsfeedGuid,
                    'fromNodeLabel': 'Newsfeed',
                    'fromNodeKey': 'guid',
                    'fromNodeKeyValue': newsfeedGuid,
                    'toNodeLabel': 'Consumer',
                    'toNodeKey': 'cell',
                    'toNodeKeyValue': consumer.cell,
                    'relationshipType': 'GroupNewsfeed',
                    'properties': {
                        'ID': grp.guid + "_" + newsfeedGuid,
                        'time': date,
                        'status': 1,
                        'notificationStatus': 0,
                        'type': 'Group'
                    }
                };

                createRelationship(rel,
                    function () {
                        count++;
                        console.log('done > ' + count);

                    },
                    function () {
                        console.log('could not create a group-newsfeed relationship');
                    });

            });
        }

    });
}

function createIndividualRelationships(consumers, newsfeedGuid, date) {
    var successCount = 0;
    
    $(consumers).each(function (index, item) {
        var cell = item.hasOwnProperty('firstName') ? item.cell : item;
        var createNewsFeedIndivRelationship = JSON.stringify({
            'uniqueKey': 'ID',
            'uniqueKeyValue': cell + "_" + newsfeedGuid,
            'fromNodeLabel': 'Newsfeed',
            'fromNodeKey': 'guid',
            'fromNodeKeyValue': newsfeedGuid,
            'toNodeLabel': 'Consumer',
            'toNodeKey': 'cell',
            'toNodeKeyValue': cell,
            'relationshipType': 'GroupNewsfeed',
            'properties': {
                'ID': cell + "_" + newsfeedGuid,
                'time': date,
                'status': 1,
                'notificationStatus': 0,
                'type': 'Group'
            }
        });

        $.ajax({
            type: "POST",
            url: "http://" + serverURL + ":12103/createRelationship", // JQuery loads serverside.php
            data: {
                params: createNewsFeedIndivRelationship
            },
            contentType: "application/x-www-form-urlencoded",
            dataType: "json",
            success: function (data) {
                successCount++;
                console.log(successCount + ' / ' + consumers.length);
                if (successCount == consumers.length) {

                    $.ajax({
                        type: "POST",
                        url: "http://" + serverURL + ":12600/push_group?enterprise=" + oEnterprise.name + '&regno=' + oEnterprise.registrationnumber, // JQuery loads serverside.php
                        contentType: "application/x-www-form-urlencoded",
                        dataType: "json",
                        success: function (data) {
                            if (parseInt(data, 10) === 0) {
                                bootbox.alert("Message was sent, however the push notification was not", function () {
                                    console.log(e);
                                });
                            }
                            window.location = "reach.php";
                        },
                        error: function (e) {
                            console.log(e);
                        }
                    });
                }
                else {

                }

            },
            error: function (e) {
                bootbox.alert("Could not send Reach Message to " + (item.hasOwnProperty('firstName') ? item.firstName + ' ' + item.lastName : item), function () {
                    console.log(e);
                });
            }

        });

    });
}