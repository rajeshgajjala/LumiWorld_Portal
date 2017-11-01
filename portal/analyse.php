<!DOCTYPE html>
<!--[if lt IE 7]>      <html class="no-js lt-ie9 lt-ie8 lt-ie7"> <![endif]-->
<!--[if IE 7]>         <html class="no-js lt-ie9 lt-ie8"> <![endif]-->
<!--[if IE 8]>         <html class="no-js lt-ie9"> <![endif]-->
<!--[if gt IE 8]>      <html class="no-js"> <![endif]-->
<html>
    <head>
        <title>Analyse | Lumi World</title>
        <?php include('includes/head.php')?>
        <script type="text/javascript" src="https://www.google.com/jsapi"></script>
        <script type="text/javascript">
            var messagesHistory = new Array();
            var sentMonth, sentYear,
                dispMonth, dispYear,
                sentPerDay,
                dispPerDay,
                totalSent,
                totalDisp,
                maxVal;



            google.load('visualization', '1', { 'packages': ['table', 'corechart'] });

            $(document).ready(function () {
                setupLoadingScreen();
                first = false;
                maxVal = 0;


                resetMonthLabels();
                updateMonthLabels();

                resetTotalDispPerDay();
                resetTotalSentPerDay();

                loadData();
            });

            function resetMonthLabels() {
                sentMonth = dispMonth = (new Date()).getMonth();
                sentYear = dispYear = (new Date()).getFullYear();
            }



            function drawSentChart() {
                var data = google.visualization.arrayToDataTable(getChartData(sentPerDay, 0));

                var tableData = google.visualization.arrayToDataTable(historyData);
                var options = {
                    chartArea: { top: '20', left: '30', width: '90%', height: '85%' },
                    legend: 'none',
                    allowHtml: false,
                    hAxis: {
                        textStyle: {
                            fontSize: 10
                        },
                        maxAlternation: 1,
                        slantedText: false,
                        showTextEvery: 1,
                        minTextSpacing: 6
                    },
                    vAxis: {
                        gridlines: {
                            color: 'transparent'
                        },
                        maxValue: maxVal
                    }
                };
                var chart = new google.visualization.ColumnChart(document.getElementById('chart-sent'));
                //var table = new google.visualization.Table(document.getElementById('messages_table'));
                chart.draw(data, options);
            }

            function drawDispChart() {
                var data = google.visualization.arrayToDataTable(getChartData(dispPerDay, 1));


                var options = {
                    chartArea: { top: '20', left: '30', width: '90%', height: '85%' },
                    legend: 'none',
                    allowHtml: false,
                    hAxis: {
                        textStyle: {
                            fontSize: 10
                        },
                        maxAlternation: 1,
                        slantedText: false,
                        showTextEvery: 1,
                        minTextSpacing: 6
                    },
                    vAxis: {
                        gridlines: {
                            color: 'transparent'
                        },
                        maxValue: maxVal
                    }
                };
                var chart = new google.visualization.ColumnChart(document.getElementById('chart-disp'));
                chart.draw(data, options);
            }

            function sortByDate(a, b) {
                return b.publishedTime - a.publishedTime;
            }

            function resetTotalSentPerDay() {
                var daysSent = daysInMonth(sentMonth + 1, sentYear);
                sentPerDay = new Array();
                for (var i = 0; i < daysSent; i++) {
                    sentPerDay[i] = 0;
                }
            }

            function resetTotalDispPerDay() {
                var daysDisp = daysInMonth(dispMonth + 1, dispYear);
                dispPerDay = new Array();
                for (var i = 0; i < daysDisp; i++) {
                    dispPerDay[i] = 0;
                }
            }

            function getTooltip(label, value) {
                return label + '\n' + value;
            }

            function getChartData(arr, ci) {
                var month = ci == 0 ? sentMonth : dispMonth;
                var year = ci == 0 ? sentYear : dispYear;

                var allData = [['Day', 'Total Messages', { role: 'style' }, { role: 'tooltip'}]];
                /*if (arr.length == 0)
                return allData;*/

                for (var i = 1; i <= daysInMonth(month + 1, year); i++) {
                    allData[allData.length] = ['' + i, arr[i - 1], 'silver', getTooltip(getShortDate(new Date(year, month, i)), arr[i - 1])];
                }
                //console.log(allData);
                return allData;
            }

            function nextMonth(t) {
                if (t == 0) {
                    sentMonth++;
                    if (sentMonth > 11) {
                        sentMonth = 0;
                        sentYear++;
                    }
                    getSentMessages();
                }
                else if (t == 1) {
                    dispMonth++;
                    if (dispMonth > 11) {
                        dispMonth = 0;
                        dispYear++;
                    }
                    getDisplayedMessages();
                }
                updateMonthLabels();
            }

            function prevMonth(t) {
                if (t == 0) {
                    sentMonth--;
                    if (sentMonth < 0) {
                        sentMonth = 11;
                        sentYear--;
                    }
                    getSentMessages();
                }
                else if (t == 1) {
                    dispMonth--;
                    if (dispMonth < 0) {
                        dispMonth = 11;
                        dispYear--;
                    }
                    getDisplayedMessages();
                }
                updateMonthLabels();
            }

            function updateMonthLabels() {
                $('#sent-month').text(month_names[sentMonth] + ' ' + sentYear);
                $('#disp-month').text(month_names[dispMonth] + ' ' + dispYear);
                //loadData();
            }


            var historyData = new Array();

            function getSentMessages() {
                var getNewsfeedHistory = JSON.stringify({
                    'searchKey': 'companyregistrationnumber',
                    'searchKeyValue': oEnterprise.registrationnumber,
                    'nodeFromLabel': 'Enterprise',
                    'nodeToLabel': 'Newsfeed',
                    'startDate': sentYear + '-' + getTwoDigitInt(sentMonth + 1) + '-01',
                    'endDate': sentYear + '-' + getTwoDigitInt(sentMonth + 1) + '-' + daysInMonth(sentYear, sentMonth + 1)
                });

                $.ajax({
                    type: "POST",
                    url: "http://" + serverURL + ":12014/getTotalPublishedNewsfeedPerMonth", //+ "?" + "limit=" + "1" + "&" + "page=" + "1", // JQuery loads serverside.php
                    data: {
                        params: getNewsfeedHistory
                    },
                    contentType: "application/x-www-form-urlencoded",
                    dataType: "json",
                    success: function (results) {
                        resetTotalSentPerDay();
                        totalSent = 0;
                        var maxSent = 0;
                        $(results).each(function (index, item) {
                            var tmp = sentPerDay[new Date(item.createdTime).getDate() - 1] += parseInt(item.reachedConsumers, 10);

                            if (tmp > maxSent)
                                maxSent = tmp;

                            totalSent += parseInt(item.reachedConsumers, 10);
                            allMessages[item.guid] = {
                                sent: item.reachedConsumers,
                                reached: [],
                                type: 'Message',
                                dateSent: item.createdTime,
                                messageBody: item.NewsFeedBody
                            };
                        });

                        $('#total-sent').text(totalSent);
                        if (sentMonth == dispMonth && sentYear == dispYear)
                            updateOverallRate();
                        //console.log(allMessages);
                        if (maxSent > maxVal) {
                            maxVal = maxSent;
                            drawDispChart();
                        }
                        drawSentChart();

                        if (getObjectLength(allMessages) > 0) {
                            var dataTotalViewedNewsfeedPerDay = JSON.stringify({
                                'searchKey': 'companyregistrationnumber',
                                'searchKeyValue': oEnterprise.registrationnumber,
                                'nodeFromLabel': 'Enterprise',
                                'nodeToLabel': 'Newsfeed',
                                'startDate': sentYear + '-' + getTwoDigitInt(sentMonth + 1) + '-01',
                                'endDate': sentYear + '-' + getTwoDigitInt(sentMonth + 1) + '-' + daysInMonth(sentYear, sentMonth + 1)
                            });


                            $.ajax({// ajax call starts
                                type: "POST",
                                url: "http://" + serverURL + ":12014/getTotalViewedNewsfeedPerDay", // + "?" + "limit=" + "15" + "&" + "page=" + "0", // JQuery loads serverside.php
                                data: {
                                    params: dataTotalViewedNewsfeedPerDay
                                },
                                contentType: "application/x-www-form-urlencoded",
                                dataType: "json",
                                success: function (result) {
                                    $(result).each(function (index, item) {
                                        var ida = item.ID.toString().split('_');
                                        if (typeof (allMessages[ida[1]]) !== 'undefined')
                                            allMessages[ida[1]].reached[ida[0]] = getDateFromString(item.date);
                                    });
                                    renderHistory();
                                }
                            });
                        }
                        else {
                            console.log('No No No');
                        }

                    }
                });
            }


            function renderHistory() {
                var historyHTML = '';
                var key;
                var count = 0;
                for (key in allMessages) {
                    var item = allMessages[key];
                    var reached = getObjectLength(item.reached);
                    var mess = stripHTMLTags(item.messageBody);
                    mess = mess.length > 60 ? mess.substr(0, 60) + '...' : mess;
                    var reachRate = (parseFloat(reached, 10) / parseFloat(item.sent == 0 ? 1 : item.sent, 10)) * 100;
                    historyHTML += '<tr class="' + (count % 2 === 0 ? 'even-row' : 'odd-row') + '"><td>' + item.type + '</td><td>' + mess + '</td><td>' + getHistoryDate(new Date(item.dateSent));
                    historyHTML += '</td><td>' + item.sent + '</td><td>' + reached + '</td><td valign="middle">' + getReachRateBar(Math.round(reachRate)) + '</td></tr>';
                    count++;
                }
                $('#history-body').html(historyHTML);
            }

            function renderPager(){
                
            }

            function getReachRateBar(rate) {
                var realRate = rate;
                if (rate > 100)
                    rate = 100;
                var barHTML = '<div style="text-align: center; width: 90%; height: 80%; border: 1px solid red; margin: 2px;">';
                barHTML += realRate + '%';
                barHTML += ('<div style="width: ' + rate + '%; background-color: #dd3333; opacity: 0.6; position: relative; margin-top: -21px;">&nbsp;</div>');
                barHTML += '</div>';
                return barHTML;
            }

            function getDisplayedMessages() {
                var dataTotalViewedNewsfeedPerDay = JSON.stringify({
                    'searchKey': 'companyregistrationnumber',
                    'searchKeyValue': oEnterprise.registrationnumber,
                    'nodeFromLabel': 'Enterprise',
                    'nodeToLabel': 'Newsfeed',
                    'startDate': dispYear + '-' + getTwoDigitInt(dispMonth + 1) + '-01',
                    'endDate': dispYear + '-' + getTwoDigitInt(dispMonth + 1) + '-' + daysInMonth(sentYear, sentMonth + 1)
                });


                $.ajax({// ajax call starts
                    type: "POST",
                    url: "http://" + serverURL + ":12014/getTotalViewedNewsfeedPerDay", // + "?" + "limit=" + "15" + "&" + "page=" + "0", // JQuery loads serverside.php
                    data: {
                        params: dataTotalViewedNewsfeedPerDay
                    },
                    contentType: "application/x-www-form-urlencoded",
                    dataType: "json",
                    success: function (result) {
                        //console.log(result);
                        resetTotalDispPerDay();
                        totalDisp = 0;
                        var maxDisp = 0;
                        $(result).each(function (index, item) {
                            var tmp = dispPerDay[getDateFromString(item.date).getDate() - 1]++;
                            if (tmp > maxDisp)
                                maxDisp = tmp;
                            totalDisp++;
                        });
                        if (maxVal < maxDisp) {
                            maxVal = maxDisp;
                            drawSentChart();
                        }


                        drawDispChart();
                        $('#total-disp').text(totalDisp);
                        updateOverallRate();

                    }
                });
            }
            var allMessages = new Array();
            var reached = new Array();
            function loadData() {
                getSentMessages();
                getDisplayedMessages();
            }

            function updateOverallRate() {

                if (sentYear === dispYear && sentMonth === dispMonth) {
                    if (totalSent > 0) {
                        $('#overall-rate').text(Math.round((totalDisp / totalSent) * 100) + ' %');
                    }
                    else {
                        $('#overall-rate').text('0.0 %');
                    }
                }
                else {
                    if (totalDisp > 0) {
                        var dataTotalNewsfeedsPublishedPerMonth = JSON.stringify({
                            'searchKey': 'companyregistrationnumber',
                            'searchKeyValue': oEnterprise.registrationnumber,
                            'nodeFromLabel': 'Enterprise',
                            'nodeToLabel': 'Newsfeed',
                            'startDate': dispYear + '-' + getTwoDigitInt(dispMonth + 1) + '-01',
                            'endDate': dispYear + '-' + getTwoDigitInt(dispMonth + 1) + '-' + daysInMonth(sentYear, sentMonth + 1)
                        });

                        $.ajax({// ajax call starts
                            type: "POST",
                            url: "http://" + serverURL + ":12014/getTotalPublishedNewsfeedPerMonth", // + "?" + "limit=" + "15" + "&" + "page=" + "0", // JQuery loads serverside.php
                            data: {
                                params: dataTotalNewsfeedsPublishedPerMonth
                            }, // Send value of the clicked button
                            contentType: "application/x-www-form-urlencoded",
                            dataType: "json", // Choosing a JSON datatype
                            success: function (result)// Variable data contains the data we get from serverside
                            {
                                totalSent = 0;
                                $(result).each(function (index, item) {
                                    totalSent += parseInt(item.reachedConsumers, 10);
                                });
                                if (totalSent > 0) {
                                    $('#overall-rate').text(Math.round((totalDisp / totalSent) * 100) + ' %');
                                }
                                else {
                                    $('#overall-rate').text('0.0 %');
                                }
                            }
                        });
                    }
                    else {
                        $('#overall-rate').text('0.0 %');
                    }
                }
            }

    </script>
        <style>
            #total-sent, #total-disp{
                font-size: 60pt;
            }
            .date-selector{
                font-weight: 400;
                font-size: 14pt;
            }
        </style>
    </head>
    <body>
        <?php include('includes/topmenu.php')?>
			
        <section class="content" id="searchtab">
                
            <?php include('includes/leftmargin.php')?>
					
			<div class="main-content">
			    <div class="content-header">
				    <span>Messages Summary</span>
                    <input id="save-button" type="button" onclick="loadData()" value="Reset View" class="login-button fr" />
                </div>
                <div style="display: block; margin-bottom: 20pt; font-weight: 500">
                    <span class="fr" id="overall-rate"></span>
                    <span class="fr">Overall Monthly Display Rate: &nbsp;</span>
                </div>
                <div class="clearfix" style="height: 20px;"></div>
                <div class="clearfix">
                    <div class="chart-container fl" style="width: 49.5%;">
                        <span class="chart-title">Messages Sent</span>
                        <div class="date-selector">
                            <a class="prev-small-button" href="javascript:prevMonth(0)"><span>p</span></a>
                            <span class="date-selector-month" id="sent-month">February</span>
                            <a class="next-small-button" href="javascript:nextMonth(0)"><span>n</span></a>
                        </div>
                        <div style="border: 1px solid #999; text-align: center; width: 100%">
                            <span id="total-sent"></span>
                            <div id="chart-sent" style="width: 99.5%; height: 300px;"></div>
                        </div>
                    </div>

                    <div class="chart-container fr" style="width: 49.5%;" >
                        <span class="chart-title">Messages Displayed</span>
                        <div class="date-selector">
                            <a class="prev-small-button" href="javascript:prevMonth(1)"><span>p</span></a>
                            <span class="date-selector-month" id="disp-month">February</span>
                            <a class="next-small-button" href="javascript:nextMonth(1)"><span>n</span></a>
                        </div>
                        <div style="border: 1px solid #999; text-align: center; width: 100%">
                            <span id="total-disp"></span>
                            <div id="chart-disp" style="width: 99.5%; height: 300px;"></div>
                        </div>
                    </div>
                </div>
                <div class="clearfix" style="margin-top: 20px;">
                    <span style="font-weight: 700">Messages History: All</span>
                </div>
                <div id="message-history" class="clearfix" style="margin-top: 20px; display: block; font-weight: 300;">
                    <table class="history-table">
                        <thead style="font-weight: 300">
                            <tr><th>Type</th><th>Message Body</th><th>Date Sent</th><th>Sent</th><th>Displayed</th><th>Display Rate</th></tr>
                        </thead>
                        <tbody id="history-body">
                                
                        </tbody>
                        <tfoot>
                            <tr>
                                <td>
                                    
                                </td>
                                <td colspan="5" id="cell-pager">
                                </td>
                            </tr>
                        </tfoot>
                    </table>
                    <div id="history-pager" style="height: 30px; margin-bottom: 10px; display: none">
                        <div style="background-color: #808080; height: 30px; width: 150px; padding-right: 10px;">
                            <div class="fl">
                                <span id="perpager" class="fl">1</span>
                                &nbsp;Records per page
                            </div>
                            
                            <div style="float: right; height: 30px; border: 1px solid red">
                                <div class="up-small-button" style="width: 10px; height: 15px; overflow: hidden; border: 1px solid green">
                                    <a href="">up</a>
                                </div>
                                <div style="width: 10px; height: 15px; overflow: hidden; border: 1px solid yellow">
                                    <a class="down-small-button" href="">down</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div id="messages_table"></div>
            </div>
        </section>
        <br>
        <div class="modal-loading"><!-- Place at bottom of page --></div>
    </body>
</html>
