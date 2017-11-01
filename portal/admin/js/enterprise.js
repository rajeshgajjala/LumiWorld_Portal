// JavaScript source code
function enterpriseController($scope, $http) {

    //var serverURL = "197.96.19.118"; //dev
    //var serverURL = "197.96.18.60"; //prod
    
    //loading bar
    $scope.loading = true;
    var start = 0;

    //get danglers
    //' + serverURL + ':11316/danglers
    //localhost:11316/danglers
    $http.get('http://' + serverURL + ':11316/danglers').success(function (response) {
        $scope.danglers = response;
        $scope.danglersNo = response[0].no;
        console.log(response);
    });
    
    //total enterprises - Modified by Rajesh to get all the enterprises
    $http.get('http://' + serverURL + ':11346/enterpriseStatus').success(function (response) {
        $scope.enterpriseCount = response.length;
        //alert('$scope.enterpriseCount : ' + $scope.enterpriseCount);
    });

    //--------------------------------------------------------------------------------------------

    //total consumers
    var key = "countFol";
    $http.defaults.headers.post["Content-Type"] = "application/x-www-form-urlencoded";
    $http({
        url: 'http://' + serverURL + ':11300/enterprises',
        method: "POST",
        data: { key: key, startEnt:start}
    }).then(function (response) {
        $scope.followersCount = response.data;
      
    }); 
    //--------------------------------------------------------------------------------------------

   //get enterprises
    $scope.startEnt = 0;
    $scope.count = 0;
    var key = "enterprices";
    $http.defaults.headers.post["Content-Type"] = "application/x-www-form-urlencoded";
    $http({
        url: 'http://' + serverURL + ':11300/enterprises',
        method: "POST",
        data: { key: key, startEnt: $scope.startEnt }
    }).then(function (response) {
        //alert(response.data.length)

        $scope.enterprises = response.data;
        console.log(response.data);
        $scope.loading = false;
        $scope.count += response.data.length;
    });

    /** Modified By Rajesh for solving pagination issues **/

    //get next page data
    $scope.proceedNext = true;
    $scope.proceedPrev = true;

    $scope.getNxtEnterprises = function () {

        if ($scope.proceedNext) {

            $scope.loading = true;
            $scope.startEnt += 1;
            var key = "enterprices";
            $scope.loading2 = true;

            $http.defaults.headers.post["Content-Type"] = "application/x-www-form-urlencoded";
            $http({
                url: 'http://' + serverURL + ':11300/enterprises',
                method: "POST",
                data: { key: key, startEnt: $scope.startEnt }
            }).then(function (response) {
                $scope.enterprises = response.data;
                $scope.count += response.data.length;

                console.log(response.data);

                $scope.loading = false;
                $scope.loading2 = false;

                //alert('$scope.count next : ' + $scope.count)

                if ($scope.count < $scope.enterpriseCount) {
                    $scope.proceedNext = true;
                } else {
                    $scope.proceedNext = false;
                }

                if ($scope.count <= 10) {
                    $scope.proceedPrev = false;
                } else {
                    $scope.proceedPrev = true;
                }

                //alert('Next $scope.proceedNext : ' + $scope.proceedNext + ' & $scope.proceedPrev : ' + $scope.proceedPrev);

            });
        }
    };

    //get previous page data
    $scope.getPrevEnterprises = function () {
        if ($scope.proceedPrev) {
            if ($scope.startEnt >= 0) {

                if ($scope.startEnt > 0) {
                    $scope.startEnt -= 1;
                } else {
                    $scope.startEnt = 0;
                }

                $scope.loading2 = true;
                $scope.loading = true;

                var key = "enterprices";
                var remainder;

                if ($scope.count == $scope.enterpriseCount) {
                    remainder = $scope.enterpriseCount % 10;
                    //alert('Remainder : ' + remainder);
                }

                $http.defaults.headers.post["Content-Type"] = "application/x-www-form-urlencoded";
                $http({
                    url: 'http://' + serverURL + ':11300/enterprises',
                    method: "POST",
                    data: { key: key, startEnt: $scope.startEnt }
                }).then(function (response) {
                    $scope.enterprises = response.data;

                    if ($scope.count < $scope.enterpriseCount && $scope.count > 0) {
                        $scope.count -= response.data.length;
                    } else if ($scope.count == $scope.enterpriseCount) {
                        if(remainder == 0) {
                            $scope.count -= response.data.length;
                        } else {
                            $scope.count -= remainder;
                        }                        
                    }

                    $scope.proceedNext = true;

                    //alert('Reverse Count : ' + $scope.count);

                    if ($scope.count <= 10) {
                        $scope.proceedPrev = false;
                    }                   

                    //alert('Prev $scope.proceedNext : ' + $scope.proceedNext + ' & $scope.proceedPrev : ' + $scope.proceedPrev);

                    console.log(response.data);
                    $scope.loading = false;
                    $scope.loading2 = false;
                });
            }
        }
    }; 

    /** Till here - Modified By Rajesh for solving pagination issues **/

    $scope.enterpriseTableHeaders = { hash: '#', secName: 'Sector Name', entName: 'EnterpriseName' };
    //---------------------------------------------------------------------------------------------------

    //get logs
    //' + serverURL + ':11307/log'
    //localhost:11307/log
    $http.get('http://' + serverURL + ':11307/log').success(function (data) {

        $scope.logins = data;
        console.log(data);
    });
    //-------------------------------------------------------------------------------

    //get logs past week
    //' + serverURL + ':11320/pastWeek'
    //localhost:11320/pastWeek'

    var key = "count";
    $scope.startWeek = 0;

    $http.defaults.headers.post["Content-Type"] = "application/x-www-form-urlencoded";
    $http({
        url: 'http://' + serverURL + ':11320/pastWeek',
        method: "POST",
        data: { key: key, start: $scope.startWeek}
    }).
        then(function (response) {
            //success 
            $scope.pastWeekCount = response.data;
            console.log($scope.pastWeekCount);
        });

    $scope.countWeek = 0; //count week logs

    $scope.getInitialWeekData = function () {
        key = "data";
        $scope.startWeek = 0;
        $scope.weekLoading = true;
        $http.defaults.headers.post["Content-Type"] = "application/x-www-form-urlencoded";
        $http({
            url: 'http://' + serverURL + ':11320/pastWeek',
            method: "POST",
            data: { start: $scope.startWeek, key:key }
        }).
            then(function (response) {
                //success 
                $scope.countWeek += response.data.length;
                $scope.pastWeekLogins = response.data;
                $scope.weekLoading = false;
                console.log($scope.pastWeekLogins);
                
            });

    };

    /** Modified By Rajesh for solving pagination issues **/

    //get next page data
    $scope.proceedNextWeek = true;
    $scope.proceedPrevWeek = true;

    $scope.getNxtWeekPage = function () {
        
        if ($scope.proceedNextWeek) {
            key = "data";
            $scope.startWeek += 1;
            $scope.weekLoading = true;

            $http({
                url: 'http://' + serverURL + ':11320/pastWeek',
                method: "POST",
                data: { start: $scope.startWeek, key: key }
            }).
            then(function (response) {
                //success 
                $scope.pastWeekLogins = response.data;
                $scope.countWeek += response.data.length;
                $scope.weekLoading = false;

                if ($scope.countWeek < $scope.pastWeekCount) {
                    $scope.proceedNextWeek = true;
                } else if ($scope.countWeek > $scope.pastWeekCount) {
                    var remainder = $scope.pastWeekCount % 10;
                    $scope.countWeek = $scope.countWeek - response.data.length + remainder;
                    $scope.proceedNextWeek = false;
                } else {
                    $scope.proceedNextWeek = false;
                }

                if ($scope.countWeek <= 10) {
                    $scope.proceedPrevWeek = false;
                } else {
                    $scope.proceedPrevWeek = true;
                }

                console.log($scope.pastWeekLogins);
            });
        }
    };

        //get previous page data
    $scope.getPrevWeekPage = function () {
        
        if ($scope.proceedPrevWeek) {
            
            if ($scope.startWeek > 0) {
                $scope.startWeek -= 1;
            } else {
                $scope.startWeek = 0;
            }

            key = "data";
            $scope.weekLoading = true;
            var remainder;

            $http({
                url: 'http://' + serverURL + ':11320/pastWeek',
                method: "POST",
                data: { start: $scope.startWeek, key: key }
            }).then(function (response) {
                //success 
                $scope.pastWeekLogins = response.data;

                if ($scope.countWeek < $scope.pastWeekCount && $scope.countWeek > 0) {
                    $scope.countWeek -= response.data.length;
                } else if ($scope.countWeek == $scope.pastWeekCount) {
                    remainder = $scope.pastWeekCount % 10;

                    if (remainder == 0) {
                        $scope.countWeek -= response.data.length;
                    } else {
                        $scope.countWeek -= remainder;
                    }
                }

                $scope.proceedNextWeek = true;

                if ($scope.countWeek <= 10) {
                    $scope.proceedPrevWeek = false;
                }
           });
        }
    };

    /** Till here -- Modified By Rajesh for solving pagination issues **/
    //-------------------------------------------------------------------------------

    //get logs current month
    //' + serverURL + ':11321/currentMonth'
    //localhost:11321/currentMonth'
    //get current month count
    var key = "count";
    $scope.startMonth = 0;
    $http.defaults.headers.post["Content-Type"] = "application/x-www-form-urlencoded";
    $http({
        url: 'http://' + serverURL + ':11321/currentMonth',
        method: "POST",
        data: { key: key, start: $scope.startMonth }
    }).
        then(function (response) {
            //get count
            $scope.monthCount = response.data;
            console.log($scope.monthCount);
        });

    $scope.countMonth = 0; //count month logs

    //get initial month log data
    $scope.getInitialMonthData = function () {

        key = "data"
        $scope.startMonth = 0;
        $scope.monthLoading = true;

        $http.defaults.headers.post["Content-Type"] = "application/x-www-form-urlencoded";
        $http({
            url: 'http://' + serverURL + ':11321/currentMonth',
            method: "POST",
            data: { start: $scope.startMonth, key:key }
        }).
            then(function (response) {
                //success 
                $scope.monthLogins = response.data;
                $scope.monthLoading = false;
                console.log($scope.monthLogins);
                $scope.countMonth += response.data.length;
            });
    };

    //get next page data
    var monthProceed = true;

    /** Modified By Rajesh for solving pagination issues **/

    $scope.proceedNextMonth = true;
    $scope.proceedPrevMonth = true;

    $scope.getNxtMonthPage = function () {
        if ($scope.proceedNextMonth) {
            $scope.startMonth += 1;
            $scope.monthLoading = true;
            key = "data";

            $http({
                url: 'http://' + serverURL + ':11321/currentMonth',
                method: "POST",
                data: { start: $scope.startMonth, key: key }
            }).then(function (response) {
                //success 
                $scope.monthLogins = response.data;
                $scope.countMonth += response.data.length;
                $scope.monthLoading = false;

                if ($scope.countMonth < $scope.monthCount) {
                    $scope.proceedNextMonth = true;
                } else if ($scope.countMonth > $scope.monthCount) {
                    var remainder = $scope.monthCount % 10;
                    $scope.countMonth = $scope.countMonth - response.data.length + remainder;
                    $scope.proceedNextMonth = false;
                } else {
                    $scope.proceedNextMonth = false;
                }

                if ($scope.countMonth <= 10) {
                    $scope.proceedPrevMonth = false;
                } else {
                    $scope.proceedPrevMonth = true;
                }
                console.log($scope.monthLogins);
            });
        }    
    };

    //get previous data
    $scope.getPrevMonthPage = function () {
        if ($scope.proceedPrevMonth) {

            if ($scope.startMonth > 0) {
                $scope.startMonth -= 1;
            } else {
                $scope.startMonth = 0;
            }
            
            $scope.monthLoading = true;
            key = "data";
            var remainder;

            $http({
                url: 'http://' + serverURL + ':11321/currentMonth',
                method: "POST",
                data: { start: $scope.startMonth, key: key }
            }).then(function (response) {
                //success 
                $scope.monthLogins = response.data;
                $scope.monthLoading = false;

                if ($scope.countMonth < $scope.monthCount && $scope.countMonth > 0) {
                    $scope.countMonth -= response.data.length;
                } else if ($scope.countMonth == $scope.monthCount) {
                    remainder = $scope.monthCount % 10;

                    if (remainder == 0) {
                        $scope.countMonth -= response.data.length;
                    } else {
                        $scope.countMonth -= remainder;
                    }
                }

                $scope.proceedNextMonth = true;

                if ($scope.countMonth <= 10) {
                    $scope.proceedPrevMonth = false;
                }

                console.log($scope.monthLogins);
            });
        }
    };

    /** Till here .. Modified By Rajesh for solving pagination issues **/

    $scope.logsTableHeaders = { hash: '#', time: 'Login Time', username: 'Username', enterprise: 'Enterprise Name' };
    //------------------------------------------------------------------------------------------------------------------
}
