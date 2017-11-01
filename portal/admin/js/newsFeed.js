// JavaScript source code
//var serverURL = "197.96.19.118"; //dev
//var serverURL = "197.96.18.60"; //prod


function newsFeedController($scope, $http, $window, $sce) {

    $scope.loading = true;
    $scope.first = 0;
    $scope.showFeeds = false;

    //get danglers
    $http.get('http://'+ serverURL +':11331/danglers').success(function (response) {
        $scope.danglers = response;
        $scope.danglersNo = response[0].no;
        console.log($scope.danglers);
    });

    //get newsfeeds stats
    $http.get('http://' + serverURL + ':11332/stats').success(function (response) {

        $scope.feedStats = response[0];
        console.log(response);
        if ($scope.loading) {
            $scope.loading = false;
        }
    });

    //get the enterprises
    //http://' + serverURL + ':11309/getEnterprises
    $http.get('http://' + serverURL + ':11309/getEnterprises').success(function (data) {
        $scope.enterpriseNames = data;
        console.log(data);
        if ($scope.loading) {
            $scope.loading = false;
        }
        
    });

    $scope.validation = function () {
        //alert('I am in validation .... ')

        $scope.Error1 = false;
        $scope.startDate = document.getElementById("cal").value;
        $scope.endDate = document.getElementById("cal2").value;
        //console.log($scope.startDate);

        //alert('Name : ' + $scope.name + ' & StartDate : ' + $scope.startDate + ' & EndDate : ' + $scope.endDate);

        if ($scope.startDate > $scope.endDate) {
            $scope.Error1 = true;
            $scope.errorMessage = "End date can not be greater than start date";
        } else if ($scope.startDate == "" || $scope.endDate == "") {
            $scope.Error1 = true;
            $scope.errorMessage = "Date is missing";
        } else if ($scope.startDate > new Date() || $scope > new Date) {
            $scope.Error1 = true;
            $scope.errorMessage = "Date can not be greater than today";
        }else if($scope.name == null || $scope.name == ""){
            $scope.Error1 = true;
            $scope.errorMessage = "Please select an enterprise name";
        } else {
            //alert('I am here ....')
            $scope.submitData();
        }           
    }
        
    $scope.submitData = function () {
        $scope.loading = true;

        $http.defaults.headers.post["Content-Type"] = "application/x-www-form-urlencoded";

        $http({
            url: 'http://' + serverURL + ':11330/getFeeds',
            method: "POST",
            data: { date: $scope.startDate, endDate: $scope.endDate, name:$scope.name }
        }).then(function (response) {
            $scope.feeds = response.data;
            $scope.showFeeds = true;
            $scope.loading = false;
        });
    };

    $scope.escapeHTML = function (text) {
        return text ? String(text).replace(/<[^>]+>/gm, '') : '';
    };
}