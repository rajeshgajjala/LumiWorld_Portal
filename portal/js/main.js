var pageName = '';
var month_names = new Array ( );
month_names[month_names.length] = "January";
month_names[month_names.length] = "February";
month_names[month_names.length] = "March";
month_names[month_names.length] = "April";
month_names[month_names.length] = "May";
month_names[month_names.length] = "June";
month_names[month_names.length] = "July";
month_names[month_names.length] = "August";
month_names[month_names.length] = "September";
month_names[month_names.length] = "October";
month_names[month_names.length] = "November";
month_names[month_names.length] = "December";

var day_names = new Array ( );
day_names[day_names.length] = "Sunday";
day_names[day_names.length] = "Monday";
day_names[day_names.length] = "Tuesday";
day_names[day_names.length] = "Wednesday";
day_names[day_names.length] = "Thursday";
day_names[day_names.length] = "Friday";
day_names[day_names.length] = "Saturday";
$(document).ready(function () {
    var locationArr = window.location.pathname.split('/');
    pageName = locationArr != null && typeof (locationArr) !== 'undefined' && locationArr.hasOwnProperty("length") && locationArr.length > 0 ? locationArr[locationArr.length - 1] : '';
    pageName = pageName.toLowerCase();

    if (pageName != 'login.php') {
        window.setTimeout(function () {
            bootbox.alert("Session time out please login", function () {
                window.location.replace('login.php');
            });

        }, 2000000);
    }
    if (pageName != 'login.php')
        init();
    
    
    //alert(window.navigator.userAgent.indexOf('CriOS'));
    if ((typeof(enableBrowserCheck) !== 'undefined' && enableBrowserCheck)  && !(window.navigator.vendor === "Google Inc." || window.navigator.userAgent.indexOf('CriOS') !== -1)) {
        alert("This browser is not supported, please download Google Chrome");
        window.location.replace('https://www.google.com/intl/en/chrome/browser/');
    }
});


//Contact Number check
jQuery.fn.forceNumeric = function() {

	return this.each(function() {
		$(this).keydown(function(e) {
			var key = e.which || e.keyCode;

			if (!e.shiftKey && !e.altKey && !e.ctrlKey &&
				// numbers
				key >= 48 && key <= 57 ||
				// Numeric keypad
				key >= 96 && key <= 105 ||
				// comma, period and minus, . on keypad
				key == 190 || key == 188 || key == 109 || key == 110 ||
				// Backspace and Tab and Enter
				key == 8 || key == 9 || key == 13 ||
				// Home and End
				key == 35 || key == 36 ||
				// left and right arrows
				key == 37 || key == 39 ||
				// Del and Ins
				key == 46 || key == 45)
				return true;

			return false;
		});
	});
}

function generateUUID() {

	var d = new Date().getTime();

	var uuid = 'xxxxxxxx-xxxx-4xxx-yxxx-xxxxxxxxxxxx'.replace(/[xy]/g, function(c) {

		var r = (d + Math.random() * 16) % 16 | 0;

		d = Math.floor(d / 16);

		return (c == 'x' ? r : (r & 0x7 | 0x8)).toString(16);

	});

	return uuid;

};


// GLOBAL FUNCTIONS
function getUrlVars()
{
    var vars = [], hash;
    var hashes = window.location.href.slice(window.location.href.indexOf('?') + 1).split('&');
    for(var i = 0; i < hashes.length; i++)
    {
        hash = hashes[i].split('=');
        vars.push(hash[0]);
        vars[hash[0]] = hash[1];
    }
    return vars;
}
var oEnterprise = null,
    oUser = null,
    isAdmin = false,
    connectedUsers = {
        data: [],
        results: [],
        perpage: 20,
        current: 0
    },
    groups = null;
function init() {
    if (sessionStorage.getItem('enterprise') != null){
        oEnterprise = JSON.parse(sessionStorage.getItem('enterprise'));


        connectedUsers = JSON.parse(sessionStorage.getItem('connectedUsers'));
        if (connectedUsers === null)
            connectedUsers = {
                data: [],
                results: [],
                perpage: 20,
                current: 0
            };
        groups = JSON.parse(sessionStorage.getItem('groups'));
    }
        

    if (sessionStorage.getItem('adminUser') != null)
        oUser = JSON.parse(sessionStorage.getItem('adminUser'));
	
    isAdmin = pageName == 'editenterprise.php' || pageName == 'viewenterprises.php' || pageName == 'dashboard.html' || pageName == 'enterprises.html' || pageName == 'addenterprise.html' || pageName == 'editenterprise.html' || pageName == 'systemscheck.html' || pageName == 'newsfeeds.html' || pageName == 'releaseenterprise.html' || pageName == 'consumers.html';
	

    if ((oUser == null && isAdmin || oEnterprise == null && !isAdmin) && pageName !== 'register.php' && pageName !== 'quickguide.html') {
        window.location.replace("login.php");
    }

    

    if (oEnterprise != null) {
        $('#company-name').text(oEnterprise.name);
        $('#company-user').text(oEnterprise.username);
        $('#small-logo-image').attr('src', oEnterprise.companyLogo);
        $('#company-user').click(toggleUserMenu);
        if (pageName === 'search.php' || pageName === 'reach.php') {
            if (connectedUsers === null) {
                getConnectedUsers();
            }
            else if (groups === null || groups.length === 0)
                getGroups();
        }
    }
    

}
var first = true;
function setupLoadingScreen(){
	$body = $("body");

	$(document).on({
	    ajaxStart: function () {
	        if (!first || pageName == 'auth.php' || pageName == 'reach.php' || pageName == 'login.php' || pageName == 'viewenterprises.php')
	            $body.addClass("loading");
	    },
	    ajaxStop: function () {
	        $body.removeClass("loading");
            first = false;
	    }
	});
}

function toggleUserMenu(){
	var disp = $('.user-menu').css('display');
	$('.user-menu').css('display', disp == 'none' ? 'block' : 'none');
}
function getRandomInt(min, max) {
	return Math.floor(Math.random() * (max - min + 1) + min);
}

function getHistoryDate(date){
    
    var hr = date.getHours();
    var ampm = hr > 11 ? 'PM' : 'AM';

    if(hr > 12){
        hr -= 12;
    }

    var strDate = getTwoDigitInt(date.getDate())  + ' ' + month_names[date.getMonth()].substr(0, 3) + ' ' + date.getFullYear() + ' ' + getTwoDigitInt(hr);
    strDate += ':' + getTwoDigitInt(date.getMinutes()) + ':' + getTwoDigitInt(date.getSeconds()) + ' ' + ampm;

    return strDate;
}

function getShortDate(longDate) {
	var iDay = longDate.getDay();
	return day_names[iDay].substr(0, 3) + ', ' + longDate.getDate() + ' ' + month_names[longDate.getMonth()].substr(0, 3) + ' ' + longDate.getFullYear();
}

function getTwoDigitInt(num){
    if(!isNaN(num)){
        return ((num < 10 ? '0' : '') + num);
    }
    return '00';
}

function getServerDateNow(){
    return getServerDate(new Date());
}

Date.prototype.addHours= function(h){
    this.setHours(this.getHours()+h);
    return this;
}

function getServerDate(date){
    var yy = date.getFullYear(), 
        mm = getTwoDigitInt(date.getMonth() + 1),
        dd = getTwoDigitInt(date.getDate()),
        hr = getTwoDigitInt(date.getHours()),
        mi = getTwoDigitInt(date.getMinutes());

    return '' + yy + '-' + mm + '-' + dd + ' ' + hr + ':' + mi;
}

function getAppDate(date){
    var yy = date.getFullYear(),
        mm = date.getMonth() + 1,
        dd = date.getDate(),
        hr = getTwoDigitInt(date.getHours()),
        mi = getTwoDigitInt(date.getMinutes()),
        ss = date.getSeconds();

    return '' + dd + '/' + mm + '/' + yy + ' ' + hr + ':' + mi + ':' + ss;
}

function getDateFromString(strDate){
    var yy, mm, dd, hr, mi;
    var timeDateArray = strDate.toString().split(' ');

    var dateArr = timeDateArray[0].toString().split('-');
    var timeArr = timeDateArray[1].toString().split(':');

    return new Date(dateArr[0], dateArr[1], dateArr[2], timeArr[0], timeArr[1]);
}
/*
*   Takes month, january = 0
*/
function daysInMonth(month, year) {
    return new Date(year, month, 0).getDate();
}

function countCharacters(txtId, counterId, max){
    var left = max - $('#'+ txtId).val().split('').length;
    
    $('#' + counterId).text(left + ' characters left');
    if (left < 0)
        $('#' + counterId).css('color', '#ff6655');
    else
        $('#' + counterId).css('color', '#333');
}

function countCharactersText(txt, counterId, max){
    var left = max - txt.split('').length;
    
    $('#' + counterId).text(left + ' characters left');
    if (left < 0)
        $('#' + counterId).css('color', '#ff6655');
    else
        $('#' + counterId).css('color', '#333');
}

function validateEmail(email) { 
    var re = /^(([^<>()[\]\\.,;:\s@\"]+(\.[^<>()[\]\\.,;:\s@\"]+)*)|(\".+\"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
    return re.test(email);
}
function validatePhone(phone) {
    var targ = phone.replace(/[^\d]/g, ''); // remove all non-digits
    return targ && targ.length === 10;
}
/*
** Function for finding the length of an associative array(Number of object's properties)
*/
function getObjectLength(obj) {
    var size = 0, key;
    for (key in obj) {
        if (obj.hasOwnProperty(key)) size++;
    }
    return size;
}

function stripHTMLTags(htmlStr) {
    var div = document.createElement("div");
    div.innerHTML = htmlStr;
    return div.textContent || div.innerText || "";
}

function arrayHasObject(arr, obj){
    var found = false;
    $(arr).each(function (index, item) {
        if (objectsEquals(item, obj)) {
            found = true;
            return;
        }
    });
    return found;
}



function objectsEquals(obj1, obj2){
    if(getObjectLength(obj1) == getObjectLength(obj2)){
        for (key in obj1) {
            if (!(obj2.hasOwnProperty(key) && obj2[key] == obj1[key]))
                return false;
        }
        return true;
    }
    return false;
}

function getConnectedUsersList() {
    var dataListConnected = JSON.stringify({
        'searchKey': 'companyregistrationnumber',
        'searchKeyValue': oEnterprise.registrationnumber,
        'nodeFromLabel': 'Consumer',
        'nodeToLabel': 'Enterprise',
        'fieldsToReturn': '[c.displayName,c.firstName,c.lastName,c.cell]'

    });

    $.ajax({// ajax call starts
        type: "POST",
        url: "http://" + serverURL + ":12014/getConnectedNodeByEnterprise", // + "?" + "username=" + "LFritz" + "&" + "password=" + "l3lFUtYM" + "&" + "mobilemessage=" + "Hello" + "&" + "mobilenumber=" + "27741869609", // JQuery loads serverside.php
        data: { params: dataListConnected }, // Send value of the clicked button
        contentType: "application/x-www-form-urlencoded",
        dataType: "json",
        success: function (results) {
            connectedUsers.results = [];
            connectedUsers.data = [];
            console.log('all users');
            console.log(results);
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
            if (typeof renderConnectedUsers === 'function')
                renderConnectedUsers();
            console.log('users recieved, now getting groups');
            getGroups();
        },
        error: function (e) {
            console.log('api unavailable - getConnectedNodeByEnterprise');
            console.log(e);
        }
    });
}

function getGroups() {
    
    var companyInfo = {
        flowKey: 'getGroups',
        companyRegistrationNumber: oEnterprise.registrationnumber
    };

    $.ajax({
        type: "POST",
        url: "http://" + serverURL + ":12400/groups",
        data: {
            params: JSON.stringify(companyInfo)
        },
        contentType: "application/x-www-form-urlencoded",
        dataType: "json",
        success: function (result) {

            groups = [{
                consumers: connectedUsers.data,
                date: '',
                guid: 'connected',
                name: 'Connected'
            }];
            console.log(groups[0].consumers)
            $(result).each(function () {
                var grp = $(this)[0];
                if (typeof (grp.name) === 'undefined')
                    grp.name = 'No Name';
                grp.consumers = [];
                groups[groups.length] = grp;
                getGroupMembers(groups.length - 1);
            });
            console.log(groups);
            renderGroups();

            if (typeof (bindGroupsCombo) === 'function')
                bindGroupsCombo();
        },
        error: function (result) {
            console.log('api unavailable');
            console.log(result);
        }
    });
}

function bindGroupsCombo() {
    $('#combo-groups').html('');
    $('#combo-groups').append('<option value="-1">Select one--</option>');
    $(groups).each(function (index, item) {
        $('#combo-groups').append('<option value="' + index + '">' + item.name + ' (' + item.consumers.length + ')</option>');
    });
}

function getGroupMembers(groupIndex) {
    var groupInfo = {
        flowKey: 'getMembersOf',
        guid: groups[groupIndex].guid
    };
    $.ajax({
        type: "POST",
        url: "http://" + serverURL + ":12400/groups",
        data: {
            params: JSON.stringify(groupInfo)
        },
        contentType: "application/x-www-form-urlencoded",
        dataType: "json",
        success: function (result) {
            $(result).each(function (index, item) {
                var cons = getUser(item[0]);
                if (cons != null) {
                    cons.checked = false;
                    groups[groupIndex].consumers[groups[groupIndex].consumers.length] = cons;
                }
            });
            renderGroups();
        },
        error: function (result) {
            console.log('api unavailable');
            console.log(result);
        }
    });
}

function getUser(cell) {
    var result = $.grep(connectedUsers.data, function (e) { return e.cell === cell; });
    if (result.length == 0)
        return null;
    else
        return result[0];
}

function getObjectByProperty(array, key, value){
    var result = $.grep(array, function (e) { return e[key] === value; });
    if (result.length == 0)
        return null;
    else
        return result[0];
}

function renderGroups() {
    if (pageName === 'reach.php')
        return;
    var groupsHTML = '';
    console.log(groups);

    //$(groups).each(function (index, item) {
    for(var index = 0; index < groups.length; index++){
        var item = groups[index];
        var action;
        if (pageName === 'reach.php') {
            action = '<input onclick="javascript: addGroup(' + index + ')" type="button" value="Add" class="login-button fr" />';
        }
        else {
            action = '<input id="reach-connected" onclick="javascript: reachGroup(' + index + ')" type="button" value="Reach" class="login-button fr" />';
        }
        groupsHTML += '<div class="group">';
        groupsHTML += '<div class="group-name">' + item.name + '</div>';
        groupsHTML += '<div class="number-connected">' + item.consumers.length + '</div>';
        console.log(item.consumers.length);
        console.log(item.consumers[0]);
        console.log(item.consumers);
        console.log(item);
        groupsHTML += '<div>';

        if (item.guid !== 'connected') {
            groupsHTML += '<a href="javascript:deleteGroup(' + index + ')"><img style="width: 20px" src="img/54_delete tile button.png" alt="delete" /></a>';
            groupsHTML += '<a href="javascript:editGroup(' + index + ')"><img style="width: 20px" src="img/55_edit tile button.png" alt="edit" /></a>';
        }

        groupsHTML += action;
        groupsHTML += '</div>';
        groupsHTML += '</div>';
    };

    $('.groups-container').html(groupsHTML);
}

function deleteGroup(index) {
    var group = groups[index];
    var addDataEnterprise = JSON.stringify({
        'nodeLabel': 'Group',
        'nodeKey': "guid",
        'nodeKeyValue': group.guid,
        'properties': {
            'status': 0
        }

    });

    addNodeProperties(addDataEnterprise, function (data) {
        console.log('just deleted a group node');
        console.log(data);
        getGroups();
    }, function (error) {
        console.log('there was an error');
        console.log(error);
    });
}
