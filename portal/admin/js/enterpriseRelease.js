// JavaScript source code


function releaseEnterpriseController($http, $scope) {
    //var serverURL = "197.96.19.118"; //dev
    //var serverURL = "197.96.18.60"; //prod

    $scope.name = " ";
    $scope.loading = true;
    var show1 = false;
    var show2 = false;
    var show3 = false;
    var show4 = false;
    $scope.showCurrent = false;
    $scope.showChange = false;
    $scope.showSuccess = false;
    $scope.showFail = false;
    $scope.loadingStatus = false;
     $scope.enterprises = [];

     $http.get('http://' + serverURL + ':11346/enterpriseStatus').success(function (response) {

        console.log(response);
        $scope.predicate = '+status';
        for (var x = 0 ; x < response.length; x++) {

            if (response[x].status == 1) {
                show1 = true;
            } else if (response[x].status == 2) {
                show2 = true;
            } else if (response[x].status == 3) {
                show3 = true;
            } else if (response[x].status == 4) {
                show4 = true;
            }

            $scope.enterprises[x] = { name: response[x].name, status: response[x].status, show1: show1, show2: show2, show3: show3, show4: show4 };

            show1 = false;
            show2 = false;
            show3 = false;
            show4 = false;
        }

        console.log($scope.enterprises);
        $scope.loading = false;
    });

    $scope.changeStatus = function () {

        if ($scope.showCurrent == false) {
            $scope.showChange = true;
        } else {
            $scope.showChange = false;
        }
        
    };

    $scope.change1 = function (name) {

        $scope.showSuccess2 = false;
        $scope.showFail2 = false;
        $scope.showCurrent = false;
        $scope.showChange = false;
        $scope.showSuccess = false;
        $scope.showFail = false;
        if (confirm("Are you sure you want to change status to 1?")) {
            $scope.loadingStatus = true;
            $http.defaults.headers.post["Content-Type"] = "application/x-www-form-urlencoded";
            $http({
                url: 'http://' + serverURL + ':11336/changeStatus',
                method: "POST",
                data: { status: 1, name: name }
            }).then(function (response) {
                console.log(response.data);
                $scope.loadingStatus = false;
                if (response.data == 1) {
                    $scope.showSuccess = true;
                } else {
                    $scope.showFail = true;
                }

                location.reload(true);
            });
        }
    };

    $scope.change2 = function (name) {

        $scope.showSuccess2 = false;
        $scope.showFail2 = false;
        $scope.showCurrent = false;
        $scope.showChange = false;
        $scope.showSuccess = false;
        $scope.showFail = false;

        if (confirm("Are you sure you want to change status to 2?")) 
            $scope.loadingStatus = true;
            $http.defaults.headers.post["Content-Type"] = "application/x-www-form-urlencoded";
            $http({
                url: 'http://' + serverURL + ':11336/changeStatus',
                method: "POST",
                data: { status: 2, name: name }
            }).then(function (response) {
                $scope.loadingStatus = false;
                console.log(response.data);
                if (response.data == 1) {
                    $scope.showSuccess = true;
                } 
                else {
                    $scope.showFail = true;
                }

                location.reload(true);
            });
    };

    $scope.change3 = function (name) {

        $scope.showSuccess2 = false;
        $scope.showFail2 = false;
        $scope.showCurrent = false;
        $scope.showChange = false;
        $scope.showSuccess = false;
        $scope.showFail = false;

        if (confirm("Are you sure you want to change status to 3?")) {
            $scope.loadingStatus = true;
            $http.defaults.headers.post["Content-Type"] = "application/x-www-form-urlencoded";
            $http({
                url: 'http://' + serverURL + ':11336/changeStatus',
                method: "POST",
                data: { status: 3, name: name }
            }).then(function (response) {
                $scope.loadingStatus = false;
                console.log(response.data);
                if (response.data == 1) {
                    $scope.showSuccess = true;
                } else {
                    $scope.showFail = true;
                }

                location.reload(true);
            });
        }
    };

    $scope.change4 = function (name) {

        $scope.showSuccess2 = false;
        $scope.showFail2 = false;
        $scope.showCurrent = false;
        $scope.showChange = false;
        $scope.showSuccess = false;
        $scope.showFail = false;

        if (confirm("Are you sure you want to change status to 4?")) {
            $scope.loadingStatus = true;
            $http.defaults.headers.post["Content-Type"] = "application/x-www-form-urlencoded";
            $http({
                url: 'http://' + serverURL + ':11336/changeStatus',
                method: "POST",
                data: { status: 4, name: name }
            }).then(function (response) {
                $scope.loadingStatus = false;
                console.log('response.data.status : ' + response.data.status);
                if (response.data.status == 1) {
                    $scope.showSuccess = true;
                    $scope.showSuccess2 = true;

                    $scope.emailData = response.data.emailInfo;
                    //console.log('$scope.emailData : ' + $scope.emailData);

                    var reg = $scope.emailData.companyRegistrationNumber;
                    var pack = $scope.emailData.package;
                    
                    var dataActivationEmail = JSON.stringify({
                        'companyRegNumber': reg,
                        'package': pack
                    });

                    //console.log('Reg : ' + reg + ' & Pack : ' + pack + ' & dataActivationEmail : ' + dataActivationEmail);

                    $.ajax({// ajax call starts
                        type: "POST",
                        url: 'http://' + serverURL + ':12015/activationEmail',
                        data: {
                            params: dataActivationEmail

                        }, // Send value of the clicked button
                        contentType: 'application/x-www-form-urlencoded',
                        dataType: 'json',
                        success: function (result) {
                            console.log(result);
                        },
                        error: function (result) {
                            alert('api unavailable');
                        }

                    });

                    $http.get('http://' + serverURL + ':11346/enterpriseStatus').success(function (response) {
                        console.log(response);
                        $scope.predicate = '+status';
                        for (var x = 0; x < response.length; x++) {

                            if (response[x].status == 1) {
                                show1 = true;
                            } else if (response[x].status == 2) {
                                show2 = true;
                            } else if (response[x].status == 3) {
                                show3 = true;
                            } else if (response[x].status == 4) {
                                show4 = true;
                            }

                            $scope.enterprises[x] = { name: response[x].name, status: response[x].status, show1: show1, show2: show2, show3: show3, show4: show4 };

                            show1 = false;
                            show2 = false;
                            show3 = false;
                            show4 = false;
                        }

                        console.log($scope.enterprises);
                        $scope.loading = false;
                    });

                } else if (response.data.status == 2) {
                    $scope.showFail2 = true;
                }
                else {
                    $scope.showFail = true;
                }
            });
        }
    };
}
