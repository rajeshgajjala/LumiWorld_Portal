<!DOCTYPE html>
<html>
    <head>
        <title>Search | Lumi World</title>
        <?php include('includes/head.php')?>
     
       <script>
           $(document).ready(function () {
               setupLoadingScreen();
               //getConnectedUsers();
               getConnectedUsersList();


               $('#btn-add-to-group').click(createNewGroup);
               $('#btn-new-group').click(function () {
                   //showGroupUsers(true);
                   showNewGroup();
               });


               $('#btn-search').click(searchCustomer);
               $('#txt-customer-search').keyup(function () {
                   localCustomerSearch($(this).val());
               });
               $('#btn-markall').click(function () {
                   markAllCustomers(true);
               });
               $('#btn-unmarkall').click(function () {
                   markAllCustomers(false);
               });

               $('#btn-reach-marked').click(reachMarked);

               $('#btn-new-group-confirm').click(function () {
                   createGroup($('#txt-new-group').val());
               });

               $('#btn-new-group-cancel').click(cancelCreateGroup);

               $('#btn-create-group').click(function () {
                   createGroup($('#txt-new-group-groups').val());
               });

               $('#btn-confirm-edit-group').click(confirmEditGroup);

               $('#btn-remove-from-group').click(removeMarkedFromGroup);

               /*$('.step-up').click(function () {
               connectedUsers.perpage++;
               while (connectedUsers.current * connectedUsers.perpage >= connectedUsers.results.length)
               connectedUsers.current--;
               renderConnectedUsers();

               });

               $('.step-down').click(function () {
               if (connectedUsers.perpage > 0) {
               connectedUsers.perpage--;

               renderConnectedUsers();
               }


               });*/

               $('.stepper').click(showPerPageDropdown);
               $('.perpage-dropdown li').click(function () {
                   $('.perpage-dropdown').css('display', 'none');
                   connectedUsers.perpage = parseInt($(this).text(), 10);
                   renderConnectedUsers();
               });
               var f = false;
               $('.perpage-dropdown li').each(function () {
                   $(this).addClass(f ? 'odd-row' : 'even-row');
                   f = !f;
               });

               $('#search-menu').css('display', 'block');
               $('#group-search').css('display', 'none');

               $('#search-menu li').click(function () {
                   var st = $(this).val();
                   $('#search-menu li').each(function () { $(this).css('font-weight', '300') });
                   $(this).css('font-weight', '700');
                   $('#connected-consumer-container').css('display', st == 0 ? 'block' : 'none');
                   $('#group-search').css('display', st == 1 ? 'block' : 'none');
                   renderConnectedUsers();
               });

               $('#reach-connected').click(function () {
                   window.location.replace("reach.php?g=connected");
               });

           });

           function showGroupsHome() {
               showGroupUsers(false);
           }

           function reachGroup(index) {
               sessionStorage.setItem('groups', JSON.stringify(groups));
               location.href = 'reach.php?g=' + index;
           }

           function showPerPageDropdown() {
               $('.perpage-dropdown').css('display', 'block');
           }

           function showNewGroup() {
               $('#new-group-container').css('display', 'block');
               $('#btn-create-group').css('display', 'block');

               $('.groups-container').css('display', 'none');
               $('#btn-confirm-edit-group').css('display', 'none');
               $('#edit-group').css('display', 'none');
               $('.reach-cell').css('display', 'none');
               $('.groups-cell').css('display', 'none');
               $('#btn-new-group').css('display', 'none');
           }

           function showEditGroup() {

           }

           function showGroupUsers(f) {
               $('#new-group-container').css('display', f ? 'block' : 'none');
               $('#btn-create-group').css('display', f ? 'block' : 'none');
               $('#btn-confirm-edit-group').css('display', f ? 'block' : 'none');
               $('#edit-group').css('display', f ? 'block' : 'none');

               $('.groups-container').css('display', !f ? 'block' : 'none');
               $('.reach-cell').css('display', !f ? 'block' : 'none');
               $('.groups-cell').css('display', !f ? 'block' : 'none');
               $('#btn-new-group').css('display', !f ? 'block' : 'none');

           }

           function createNewGroup() {
               console.log('creating new group');
               $('#new-group-popup').css('visibility', 'visible');
           }

           function cancelCreateGroup() {
               $('#new-group-popup').css('visibility', 'hidden');
           }

           function createGroup(name) {
               var selected = [];
               var gi = parseInt($('#combo-groups').val(), 10);

               $(connectedUsers.data).each(function (index, item) {
                   if (item.checked)
                       selected[selected.length] = item.cell;
               });
               var now = new Date();
               if (gi !== -1) {
                   addUsersToGroup(selected, groups[gi].guid, now);
               }
               else if (name.length > 5) {
                   var groupNode = {
                       label: 'Group',
                       key: 'guid',
                       properties: {
                           guid: generateUUID().toString(),
                           date: now,
                           name: name,
                           status: 1
                       }
                   };
                   var enterpriseGroup = {
                       uniqueKey: 'ID',
                       uniqueKeyValue: oEnterprise.registrationnumber + '_' + groupNode.properties.guid,
                       fromNodeLabel: 'Enterprise',
                       fromNodeKey: 'companyregistrationnumber',
                       fromNodeKeyValue: oEnterprise.registrationnumber,
                       toNodeLabel: groupNode.label,
                       toNodeKey: groupNode.key,
                       toNodeKeyValue: groupNode.properties.guid,
                       relationshipType: 'Contains',
                       properties: {
                           ID: oEnterprise.registrationnumber + '_' + groupNode.properties.guid,
                           time: now,
                           status: 1,
                           type: 'Contains'
                       }
                   }
                   $.ajax({
                       type: "POST",
                       url: "http://" + serverURL + ":11100/createuniquenode",
                       data: {
                           params: JSON.stringify(groupNode)
                       },
                       contentType: "application/x-www-form-urlencoded",
                       dataType: "json",
                       success: function (result) {
                           console.log(result);
                           $.ajax({
                               type: "POST",
                               url: "http://" + serverURL + ":11103/createRelationship", // JQuery loads serverside.php
                               data: {
                                   params: JSON.stringify(enterpriseGroup)
                               },
                               contentType: "application/x-www-form-urlencoded",
                               dataType: "json",
                               success: function (res) {
                                   addUsersToGroup(selected, groupNode.properties.guid, now);
                                   console.log('Group Successfully Created');
                                   console.log(res);
                               },
                               error: function (e) {
                                   console.log('Eish, couldnt do it, created a group node though - I guess its a dangler now :-(');
                                   console.log(e);
                               }
                           });
                       },
                       error: function (e) {
                           console.log('Eish, couldnt even create a unique node... I am doomed :\'\'\'(');
                           console.log(e);
                       }
                   });
               }
               else {
                   alert('Group name must be at least 5 characters')
               }
           }

           function addUsersToGroup(selected, guid, now) {
               var count = 0;
               console.log(selected);
               console.log(guid);
               console.log(now);
               $(selected).each(function (index, item) {
                   var consumerGroup = {
                       uniqueKey: 'ID',
                       uniqueKeyValue: item + '_' + guid,
                       fromNodeLabel: 'Consumer',
                       fromNodeKey: 'cell',
                       fromNodeKeyValue: item,
                       toNodeLabel: 'Group',
                       toNodeKey: 'guid',
                       toNodeKeyValue: guid,
                       relationshipType: 'MemberOf',
                       properties: {
                           ID: item + '_' + guid,
                           time: now,
                           status: 1,
                           type: 'MemberOf'
                       }
                   }
                   $.ajax({
                       type: "POST",
                       url: "http://" + serverURL + ".:11103/createRelationship",
                       data: {
                           params: JSON.stringify(consumerGroup)
                       },
                       contentType: "application/x-www-form-urlencoded",
                       dataType: "json",
                       success: function (cres) {
                           count++;
                           console.log(cres);
                           if (count === selected.length) {
                               getGroups();
                               showGroupsHome();
                               cancelCreateGroup();
                           }

                       },
                       error: function (error) {
                           console.log('error');
                           console.log(error);
                       }
                   });
               });
           }

           var currentGroup = null;
           var removeMembers = [];
           function editGroup(index) {
               currentGroup = groups[index];
               if (typeof (currentGroup) !== 'undefined') {
                   for (var i = 0; i < currentGroup.consumers.length; i++) {
                       currentGroup.consumers[i].checked = false;
                   }
                   currentGroup.results = currentGroup.consumers;
                   renderGroupUsers();
                   $('#txt-edit-group').val(currentGroup.name);
                   $('.groups-container').css('display', 'none');
                   $('#btn-new-group').css('display', 'none');
                   $('#btn-confirm-edit-group').css('display', 'block');
                   $('#edit-group').css('display', 'block');
               }
           }



           function reachMarked() {
               var numbers = '';

               sessionStorage.setItem('connectedUsers', JSON.stringify(connectedUsers));

               $(connectedUsers.data).each(function (index, item) {
                   if (item.checked)
                       numbers += ((numbers.length == 0 ? '' : '|') + item.cell);
               });
               if (numbers.length == 0)
                   alert('Please select people to reach');
               else
                   window.location.replace('reach.php?c=reach-marked');
           }

           function localCustomerSearch(q) {
               q = q.toLowerCase();
               connectedUsers.results = [];
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
               renderConnectedUsers();
           }

           function searchCustomer() {
               $('#individual-results-table').html('');
               $('#total-results').text('No existing user found');
               $('#individual-search-results').css('display', 'none');

               var dataConnectedIndividual = JSON.stringify({
                   'searchKey': 'cell',
                   'searchKeyValue': $('#txt-search').val(),
                   'nodeFromLabel': 'Consumer',
                   'nodeToLabel': 'Enterprise',
                   'relationship': 'Connected',
                   'searchKeyEnterprise': 'name',
                   'searchKeyValueEnterprise': oEnterprise.name
               });

               $.ajax({// ajax call starts
                   type: "POST",
                   url: "http://" + serverURL + ":11014/getConnectedNode", // JQuery loads serverside.php
                   data: {
                       params: dataConnectedIndividual
                   },
                   contentType: "application/x-www-form-urlencoded",
                   dataType: "json",
                   success: function (result) {

                       var output = JSON.stringify(result);

                       if (result[0] == 1) {
                           $('#total-results').text('1 existing user found');
                           renderResults();
                       }
                       else {

                       }

                       $('#individual-search-results').css('display', 'block');
                   }
               });
           }

           function renderResults() {
               var res = '<table style="width: 83%">';
               res += '<tr><td>Mobile No.</td><td>Status</td><td style="width: 15%"></td></tr>';
               res += '<tr class="odd-row"><td>' + $('#txt-search').val() + '</td><td>Connected</td><td style="background-color: #ffffff"><input onClick="reachCustomer()" type="button" class="login-button" value="Reach" style="width: 100%" autofocus /></td></tr>';
               res += '</table>';

               $('#btn-search').blur();
               $('#individual-results-table').html(res);

           }

           function reachCustomer(num) {
               window.location.replace('reach.php?n=' + connectedUsers.data[num].cell);
           }

           function testGrid(res) {
               //var con = new Grid()
           }

           function markAllCustomers(f) {
               $(connectedUsers.data).each(function (index) {
                   connectedUsers.data[index].checked = f;
               });
               $('#marked-individuals').text((f ? connectedUsers.data.length : 0) + ' individuals marked');
               renderConnectedUsers();
           }


           function openPage(num) {
               connectedUsers.current = num;
               renderConnectedUsers();
           }

           function renderConnectedUsers() {
               var results = connectedUsers.results;

               var tableResults = '';
               var perpage = connectedUsers.perpage;
               var count = 0;
               var item;
               var start = connectedUsers.current * perpage;
               var end = start + perpage;
               if (end > results.length)
                   end = results.length;

               for (var i = start; i < end; i++) {
                   item = results[i];

                   tableResults += '<tr class="' + ((i - start) % 2 == 0 ? 'even-row' : 'odd-row') + '"><td>';
                   tableResults += '<input ' + (item.checked ? 'checked="checked"' : '') + ' type="checkbox" class="check-customer" value="' + item.index + '" />';
                   tableResults += '</td><td>';
                   tableResults += item.displayName;
                   tableResults += '</td><td>';
                   tableResults += item.firstName;
                   tableResults += '</td><td>';
                   tableResults += item.lastName;
                   tableResults += '</td><td>';
                   tableResults += item.cell;
                   tableResults += '</td><td>';
                   tableResults += 'Connected';
                   tableResults += '</td><td class="groups-cell">';
                   tableResults += 'Connected'
                   tableResults += '</td><td class="reach-cell">';
                   tableResults += '<input onClick="reachCustomer(\'' + item.index + '\')" type="button" class="login-button" value="Reach" style="width: 100%"/>';
                   tableResults += '</td></tr>';

               }
               $('#marked-individuals').text(getMarkedIndividualsText());
               $('#gmarked-individuals').text(getMarkedIndividualsText());

               $('#connected-consumers').html(tableResults);
               $('#gconnected-consumers').html(tableResults);
               $('.check-customer').change(function () {
                   connectedUsers.data[$(this).val()].checked = $(this).attr('checked') == 'checked';
                   $('#marked-individuals').text(getMarkedIndividualsText());
                   $('#gmarked-individuals').text(getMarkedIndividualsText());
               });
               renderConnectedPager();
           }

           function getMarkedIndividualsText() {
               var count = 0;
               $(connectedUsers.data).each(function (index, item) {
                   if (item.checked)
                       count++;
               });
               return count + ' of ' + connectedUsers.data.length + ' individuals marked';
           }

           function renderConnectedPager() {
               var numPages = parseInt(connectedUsers.results.length / connectedUsers.perpage, 10) + (connectedUsers.results.length % connectedUsers.perpage == 0 ? 0 : 1);
               var htmlPages = '';
               for (var i = 0; i < numPages; i++) {
                   if (i == connectedUsers.current)
                       htmlPages += '<span class="grid-page">' + (i + 1) + '</span>';
                   else
                       htmlPages += '<a class="grid-page" href="javascript:openPage(' + i + ')">' + (i + 1) + '</a>';
               }
               $('#users-pages').html(htmlPages);
               $('#num-perpage').text(connectedUsers.perpage);
           }

           function getConnectedUsers() {
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
                   url: "http://" + serverURL + ":11014/getrelationshipcount", // JQuery loads serverside.php
                   data: {
                       params: dataRelationshipSearch
                   }, // Send value of the clicked button
                   contentType: "application/x-www-form-urlencoded",
                   dataType: "json", // Choosing a JSON datatype
                   success: function (result)// Variable data contains the data we get from serverside
                   {
                       $('.number-connected').text(result);
                   }
               });
           }
           function renderGroupUsers() {
               var results = currentGroup.results;

               var tableResults = '';
               var perpage = connectedUsers.perpage;
               var count = 0;
               var item;
               var start = connectedUsers.current * perpage;
               var end = start + perpage;
               if (end > results.length)
                   end = results.length;

               for (var i = start; i < end; i++) {
                   item = results[i];

                   tableResults += '<tr class="' + ((i - start) % 2 == 0 ? 'even-row' : 'odd-row') + '"><td>';
                   tableResults += '<input ' + (item.checked ? 'checked="checked"' : '') + ' type="checkbox" class="check-customer" value="' + i + '" />';
                   tableResults += '</td><td>';
                   tableResults += item.displayName;
                   tableResults += '</td><td>';
                   tableResults += item.firstName;
                   tableResults += '</td><td>';
                   tableResults += item.lastName;
                   tableResults += '</td><td>';
                   tableResults += item.cell;
                   tableResults += '</td><td>';
                   tableResults += 'Connected';
                   tableResults += '</td><td>';
                   tableResults += 'Connected'
                   tableResults += '</td>';
                   tableResults += '</tr>';

               }
               $('#marked-individuals').text(getMarkedIndividualsText());
               $('#gmarked-individuals').text(getMarkedIndividualsText());

               $('#edit-group-consumers').html(tableResults);
               $('.check-customer').change(function () {
                   currentGroup.consumers[$(this).val()].checked = $(this).attr('checked') == 'checked';
                   $('#edit-marked-individuals').text(getMarkedIndividualsText());
                   //$('#gmarked-individuals').text(getMarkedIndividualsText());
               });
               renderEditPager();
           }

           function renderEditPager() {
               var numPages = parseInt(currentGroup.consumers.length / connectedUsers.perpage, 10) + (currentGroup.consumers.length % connectedUsers.perpage == 0 ? 0 : 1);
               var htmlPages = '';
               for (var i = 0; i < numPages; i++) {
                   htmlPages += '<a class="grid-page" href="javascript:openPageEdit(' + i + ')">' + (i + 1) + '</a>';
               }
               $('#edit-group-users-pages').html(htmlPages);
               $('#edit-num-perpage').text(connectedUsers.perpage);
           }

           function confirmEditGroup() {
               if (currentGroup === null)
                   return;
               var groupNode = {
                   nodeLabel: 'Group',
                   nodeKey: 'guid',
                   nodeKeyValue: currentGroup.guid,
                   properties: {
                       name: $('#txt-edit-group').val()
                   }
               };
               addNodeProperties(groupNode, function (data) {
                   console.log(data);
                   if (removeMembers.length === 0) {
                       showGroupsHome();

                   }

               }, function (error) {
                   console.log(error);
               });
               console.log(removeMembers);
               var count = 0;


               $(removeMembers).each(function (index, item) {
                   var relationship = {
                       uniqueKey: 'ID',
                       uniqueKeyValue: item.cell + '_' + currentGroup.guid,
                       relationshipType: 'MemberOf',
                       properties: {
                           status: 0
                       }
                   };
                   addRelationshipProperties(relationship, function (data) {
                       console.log(data);
                       count++;
                       if (count === removeMembers.length) {
                           removeMembers = [];
                           currentGroup = null;
                           showGroupsHome();
                       }

                   },
                   function (error) {
                       console.log('error');
                       console.log(error);
                   });
               });
           }

           function removeMarkedFromGroup() {
               removeMembers = [];
               $(currentGroup.results).each(function (index) {
                   if (currentGroup.results[index - removeMembers.length].checked) {
                       removeMembers[removeMembers.length] = currentGroup.results[index - removeMembers.length];
                       currentGroup.results.splice(index, 1);
                   }
               });
               renderGroupUsers();
           }

           


		</script>
       
    </head>
    <body style="background-color: #767777">

        <?php include('includes/topmenu.php')?>
			
            <section class="content" id="searchtab">
                
			    
                <?php include('includes/leftmargin.php')?>
					
				<div class="main-content">
                    <div id="connected-consumer-container" class="inner-content">
						<div class="content-header"><span>Individuals</span></div>
                        <div style="display: table-cell; width: 80%; float: left">
                            <div style="display: inline-block; width: 100%">
                                <input id="txt-customer-search" type="text" class="customer-search" placeholder="Search Individuals..." />
                            </div>
                            <div>
                                <span id="marked-individuals"></span>
                            </div>
						    <div style="float: left; display: inline-block; width: 100%">
                                <table class="customers">
                                    <thead>
                                        <tr>
                                            <td></td><td>Display Name</td><td>First Name</td><td>Last Name</td><td>Mobile Number</td><td>Status</td><td>Groups</td><td></td><td></td>
                                        </tr>
                                    </thead>
                                    <tbody id="connected-consumers"></tbody>
                                    <tfoot>
                                        <tr>
                                            <td colspan="8">
                                                <div class="pager">
							                        <div class="perpage">
								                        <div class="perpage-view"><span id="num-perpage">8</span> Records Per Page</div>
								                        <div class="stepper">
									                        <div class="step-up">
									                        </div>
									                        <div class="step-down">
									                        </div>
								                        </div>
                                                        <div class="perpage-dropdown">
                                                            <ul>
                                                                <li>10</li>
                                                                <li>20</li>
                                                                <li>40</li>
                                                                <li>50</li>
                                                            </ul>
                                                        </div>
							                        </div>
                                                    <div id="users-pages" class="grid-pages"></div>
                                                </div>
                                            </td>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>
                        </div>
                        
                        <div class="right-tools">
                            <div><input type="button" id="btn-markall" class="right-tool" value="Mark All"/></div>
                            <div><input type="button" id="btn-unmarkall" class="right-tool" value="Unmark All"/></div>
                            <div style="display: none"><input type="button" id="btn-mark-not-registered" class="right-tool" value="Mark Not Registered"/></div>
                            <div style="display: none"><input type="button" id="btn-mark-connected" class="right-tool" value="Mark Connected"/></div>
                            <div style="display: none"><input type="button" id="btn-mark-not-connected" class="right-tool" value="Mark Not Connected"/></div>
                            <div style="display: none"><input type="button" id="btn-connect-to-marked" class="right-tool" value="Connect To Marked"/></div>
                            <div style="display: none"><input type="button" id="btn-remove-marked" class="right-tool" value="Remove Marked"/></div>
                            <div><input type="button" id="btn-reach-marked" class="right-tool" value="Reach Marked"/></div>
                            <div><input type="button" id="btn-add-to-group" class="right-tool" value="Add Marked To Group"/></div>
                        </div>
                    </div>
					<div id="individual-search" style="display: none" class="inner-content">
						<div class="content-header"><span>Individuals</span></div>
						<span class="field-top-label">Search for an individual on Lumi World</span><br>
                        <form onsubmit="searchCustomer(); return false;">
							<input id="txt-search" type="text" class="privatetextstyle" style="width: 70%; float: left" placeholder="Enter mobile number..." />
							<input id="btn-search" type="button" class="login-button" value="Search Luminet World" style="margin-left: 5px;" />
                        </form>
                        <div class="clearfix"></div>
                        <div id="individual-search-results" style="margin-top: 20px; display: none">
                            <span style="font-weight: 700">Search Result:</span>
                            <span id="total-results" class="total-results" style="margin-left: 10px;">No existing user found</span>
                            <div id="individual-results-table">
                            </div>
                        </div>
					</div>
					<div id="group-search" style="display: none" class="inner-content">
						<div class="content-header"><span>Groups</span>
                            <input id="btn-new-group" type="button" value="+ New Group" class="login-button fr" />
                            <input id="btn-create-group" type="button" value="+ Create Group" class="login-button fr" style="display: none" />
                            <input id="btn-confirm-edit-group" type="button" value="Done" class="login-button fr" style="display: none" />
                        </div>
						<div class="groups-container">
							
						</div>
                        <div id="new-group-container" style="display: none">
                            <span class="field-top-label">Group Name:</span><br>
                            <input type="text" id="txt-new-group-groups" style="width: 70%;" />
                            <br>
                            <span class="field-top-label">Select individuals to add to Group</span><br>
                            <div>
                                <span id="gmarked-individuals">0 individuals marked</span>
                            </div>
						    <div style="float: left; display: inline-block; width: 100%">
                                <table style="width: initial" class="customers">
                                    <thead>
                                        <tr>
                                            <td></td><td>Display Name</td><td>First Name</td><td>Last Name</td><td>Mobile Number</td><td>Status</td><td class="groups-cell">Groups</td><td class="reach-cell"></td><td></td>
                                        </tr>
                                    </thead>
                                    <tbody id="gconnected-consumers"></tbody>
                                    <tfoot>
                                        <tr>
                                            <td colspan="8">
                                                <div class="pager">
							                        <div class="perpage">
								                        <div class="perpage-view"><span id="num-perpage">8</span> Records Per Page</div>
								                        <div class="stepper">
									                        <div class="step-up">
									                        </div>
									                        <div class="step-down">
									                        </div>
								                        </div>
							                        </div>
                                                    <div id="gusers-pages" class="grid-pages"></div>
                                                </div>
                                            </td>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>
                        </div>
					    <div id="edit-group" style="display: none">
                            <span class="field-top-label">Group Name:</span><br>
                            <input type="text" id="txt-edit-group" style="width: 70%;" />
                            <br>
                            <span class="field-top-label">Select individuals to add to Group</span><br>
                            <div>
                                <span id="edit-marked-individuals">0 individuals marked</span>
                            </div>
                            <div style="display: table-cell; width: 80%; float: left">
						    <div style="float: left; display: inline-block; width: 100%">
                                <table class="customers">
                                    <thead>
                                        <tr>
                                            <td></td><td>Display Name</td><td>First Name</td><td>Last Name</td><td>Mobile Number</td><td>Status</td><td class="groups-cell">Groups</td><td class="reach-cell"></td><td></td>
                                        </tr>
                                    </thead>
                                    <tbody id="edit-group-consumers"></tbody>
                                    <tfoot>
                                        <tr>
                                            <td colspan="8">
                                                <div class="pager">
							                        <div class="perpage">
								                        <div class="perpage-view"><span id="edit-num-perpage">8</span> Records Per Page</div>
								                        <div class="stepper">
									                        <div class="step-up">
									                        </div>
									                        <div class="step-down">
									                        </div>
								                        </div>
							                        </div>
                                                    <div id="edit-group-users-pages" class="grid-pages"></div>
                                                </div>
                                            </td>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>
                                </div>
                            <div style="margin-top: 30px" class="right-tools">
                                <div><input type="button" id="btn-markall-edit" class="right-tool" value="Mark All"/></div>
                                <div><input type="button" id="btn-unmarkall-edit" class="right-tool" value="Unmark All"/></div>
                                <div><input type="button" id="btn-remove-from-group" class="right-tool" value="Remove Marked From Group"/></div>
                            </div>
                        </div>
                    </div>
				</div>
        </section>

        <div class="popup" id="new-group-popup">
            <div class="popup-container">
                <div class="popup-title">
                    <span>Add Individuals to Group</span>
                </div>
                <div class="popup-content">
                    <span>Add to Existing Group</span>
                    <select id="combo-groups">
                    </select>
                    <span>Create New Group</span>
                    <input id="txt-new-group" type="text"/>
                </div>
                <div class="popup-toolbar">
                    <input id="btn-new-group-confirm" type="button" value="Confirm" class="login-button fr" />
                    <input id="btn-new-group-cancel" type="button" value="Cancel" class="login-button fr" />
                </div>
            </div>
        </div>
        

        
        <script>window.jQuery || document.write('<script src="js/vendor/jquery-1.10.1.min.js"><\/script>')</script>
        
		<script src="js/jquery.json-2.js"></script>

        <div class="modal-loading"><!-- Place at bottom of page --></div>
    </body>
</html>
