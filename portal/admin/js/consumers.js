// JavaScript source code
function consumerController($scope, $http) {
    //var serverURL = "197.96.19.118"; //dev
    //var serverURL = "197.96.18.60"; //prod
    $scope.loading = true;
    $scope.fetch = true;
    $scope.fetchPrev = true;
    //total consumers
    var key = "countFol";
    var start = 0;
    $http.defaults.headers.post["Content-Type"] = "application/x-www-form-urlencoded";
    $http({
        url: 'http://' + serverURL + ':11300/enterprises',
        method: "POST",
        data: { key: key, startEnt: start }
    }).then(function (response) {
        $scope.followersCount = response.data;

    });
    //--------------------------------------------------------------------------------------------

    //get all devices
    $http.get('http://' + serverURL + ':11339/devices').success(function (response) {

        console.log(response);
        $scope.devices = response;
    });
    //----------------------------------------------------------------------------------------------

    //get consumers first page
    $scope.skip = 0;
    $scope.showNum = 0;
    var key = "all";
    $http.defaults.headers.post["Content-Type"] = "application/x-www-form-urlencoded";
    $http({
        url: 'http://' + serverURL + ':11340/getConsumers',
        method: "POST",
        data: { key: key, skip: $scope.skip }
    }).then(function (response) {
        $scope.consumers = response.data[0];
        console.log($scope.consumers);
        
        if (response.data[0].total < 10) {
            $scope.showNum = response.data[0].total;
            $scope.fetchPrev = false;
            $scope.fetch = false;
        } else {
            $scope.showNum = 10;
        }
    });
    //-----------------------------------------------------------------------------------------------------

    //get consumers next page
    $scope.nextConsumers = function () {

        if ($scope.fetch) {
            $scope.loading = true;
            $scope.fetchPrev = true;
            $scope.showNum += 10;

            if ($scope.showNum == $scope.consumers.total || $scope.showNum > $scope.consumers.total) {

                $scope.showNum = $scope.consumers.total;
                $scope.fetch = false;
            }

            $scope.skip += 10;
            $http.defaults.headers.post["Content-Type"] = "application/x-www-form-urlencoded";
            $http({
                url: 'http://' + serverURL + ':11340/getConsumers',
                method: "POST",
                data: { key: key, skip: $scope.skip, name: $scope.name }
            }).then(function (response) {
                $scope.consumers = response.data[0];
                console.log($scope.consumers);
                $scope.loading = false;
            });

        }
    };
    //-------------------------------------------------------------------------------------------------------------

    //get consumers prev page
    $scope.prevConsumers = function () {

        if ($scope.fetchPrev) {
            $scope.fetch = true;
            $scope.loading = true;

            if ($scope.showNum == $scope.consumers.total) {
                $scope.showNum = $scope.consumers.total - ($scope.consumers.total % 10);
                $scope.skip -= 10;
            } else if ($scope.showNum == 10) {
                $scope.showNum = 10;
                $scope.fetchPrev = false;
                $scope.skip = 0;
            } else {
                $scope.showNum -= 10;
                $scope.skip -= 10;
            }

            ;

            $http.defaults.headers.post["Content-Type"] = "application/x-www-form-urlencoded";
            $http({
                url: 'http://' + serverURL + ':11340/getConsumers',
                method: "POST",
                data: { key: key, skip: $scope.skip, name: $scope.name }
            }).then(function (response) {
                $scope.consumers = response.data[0];
                console.log($scope.consumers);
                $scope.loading = false;
            });

        }
    };
    //-------------------------------------------------------------------------------------------------------------

    //get most used device
    $http.defaults.headers.post["Content-Type"] = "application/x-www-form-urlencoded";
    $http.get('http://' + serverURL + ':11341/deviceCount').success(function (response) {

        console.log(response);
        $scope.mostUsed = response;
        $scope.loading = false;
    });

    //-------------------------------------------------------------------------------------------------------------

    //get Enterprises
    $http.get('http://' + serverURL + ':11309/getEnterprises').success(function (data) {
        $scope.enterpriseNames = data;
        console.log(data);
    });
    //-------------------------------------------------------------------------------------------------------------

    //get specific consumers
    $scope.name = "";
    key = "specific";
    $scope.$watch('name', function () {
        $scope.loading = true;
        if ($scope.name != "") {
            $scope.skip = 0;
            $scope.showNum = 0;
            
            $http.defaults.headers.post["Content-Type"] = "application/x-www-form-urlencoded";
            $http({
                url: 'http://' + serverURL + ':11340/getConsumers',
                method: "POST",
                data: { key: key, skip: $scope.skip, name: $scope.name }
            }).then(function (response) {
                $scope.consumers = response.data[0];
                $scope.loading = false;
                console.log($scope.consumers);
                if (response.data[0].total < 10) {
                    $scope.showNum = response.data[0].total;
                    $scope.fetchPrev = false;
                    $scope.fetch = false;
                } else {
                    $scope.showNum = 10;
                }
            });
        }
    });
    //-------------------------------------------------------------------------------------------------------------
}