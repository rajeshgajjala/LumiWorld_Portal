// JavaScript source code
function systemCheckController($scope, $http) {

    //var serverURL = "197.96.19.118"; //dev
    //var serverURL = "197.96.18.60"; //prod

    $scope.choice = " ";
    $scope.loading = false;
    $scope.loading2 = false;
    $scope.tableShow = false;

   
    //watch for change of choice
    $scope.$watch('choice', function () {

        $scope.start = 0; //start pagnation 
        $scope.count = 0;
        $scope.countFeed = 0;
        $http.defaults.headers.post["Content-Type"] = "application/x-www-form-urlencoded";
        $scope.tableShow = false;
        $scope.newsShow = false;
        $scope.duplicateShow = false;
        $scope.relationTable = false;

        if ($scope.choice != " ") {
            $scope.loading = true;
            $scope.loading2 = false;
            if ($scope.choice == "Enterprise Data") {

                //count the enterprises
                $http.get('http://' + serverURL + ':11350/checkCounts').success(function (response) {
                    console.log(response);
                    if (response.relCount < response.graphCount || response.relCount < response.graphCount) {
                        alert("Relational database and graph database enterprise count does not match, as a result the table may have some blank rows. To solve this please delete the inconsistant data");
                    }
                    $scope.entCounts = response;
                });


                //get enterprise comparism 
                $http({
                    url: 'http://' + serverURL + ':11317/checkEnterprise',
                    method: "POST",
                    data: { start: $scope.start }
                }).
                    then(function (response) {
                        //success 
                        $scope.enterpriseData = response.data;
                        console.log($scope.enterpriseData);
                        $scope.tableShow = true;
                        $scope.newsShow = false;
                        $scope.duplicateShow = false;
                        $scope.relationTable = false;
                        $scope.loading = false;
                        $scope.count = response.data.length;
                    });
            }
            else if ($scope.choice == "News Feed Data") {
                //get newsfeeds comparisim
                $http({
                    url: 'http://' + serverURL + ':11318/checkNewsfeed',
                    method: "POST",
                    data: { start: $scope.start }
                }).
            then(function (response) {
                //success 
                $scope.newsData = response.data;
                console.log($scope.newsData);
                $scope.tableShow = false;
                $scope.duplicateShow = false;
                $scope.loading = false;
                $scope.relationTable = false;
                $scope.newsShow = true;
                $scope.countFeed = response.data.length;
            });

            }
            else if ($scope.choice == "Duplicate Nodes") {
                //get duplicate nodes
                $http.get('http://' + serverURL + ':11323/duplicateNodes').success(function (response) {
                    $scope.tableShow = false;
                    $scope.newsShow = false;
                    $scope.duplicateShow = true;
                    $scope.relationTable = false;
                    $scope.duplicates = response;
                    $scope.loading = false;
                    console.log($scope.duplicates);

                });
            }
            else if ($scope.choice == "Duplicate Relations") {
                //get duplicate relationships
                $http.get('http://' + serverURL + ':11324/duplicateRelations').success(function (response) {

                    $scope.duplicateRelations = response;
                    console.log($scope.duplicateRelations);
                    $scope.loading = false;
                    $scope.relationTable = true;
                });
            }
        }
    });

    $scope.proceedNextEnt = true;
    $scope.proceedPrevEnt = true;

    //get next enterprise page data
    $scope.getNextEntPage = function () {
        if ($scope.proceedNextEnt) {
            $scope.start += 1;
            $scope.loading = true;
            $scope.loading2 = true;

            $http({
                url: 'http://' + serverURL + ':11317/checkEnterprise',
                method: "POST",
                data: { start: $scope.start }
            }).then(function (response) {
                //success 
                $scope.enterpriseData = response.data;
                $scope.count += response.data.length;
                $scope.loading = false;
                $scope.loading2 = false;

                console.log($scope.enterpriseData);

                if ($scope.count < $scope.enterpriseData[0].resultSize) {
                    $scope.proceedNextEnt = true;
                } else if ($scope.count > $scope.enterpriseData[0].resultSize) {
                    var remainder = $scope.enterpriseData[0].resultSize % 10;
                    $scope.count = $scope.count - response.data.length + remainder;
                    $scope.proceedNextEnt = false;
                } else {
                    $scope.proceedNextEnt = false;
                }

                if ($scope.count <= 10) {
                    $scope.proceedPrevEnt = false;
                } else {
                    $scope.proceedPrevEnt = true;
                }
            });
        }
    }

    //get previous entertainment page data
    $scope.getPrevEntPage = function () {

        if ($scope.proceedPrevEnt) {
            $scope.loading = true;
            $scope.loading2 = true;

            if ($scope.start > 0) {
                $scope.start -= 1;
            } else {
                $scope.start = 0;
            }

            var remainder;

            $http({
                url: 'http://' + serverURL + ':11317/checkEnterprise',
                method: "POST",
                data: { start: $scope.start }
            }).
            then(function (response) {
                //success 
                $scope.enterpriseData = response.data;
                $scope.loading = false;
                $scope.loading2 = false;

                if ($scope.count < $scope.enterpriseData[0].resultSize && $scope.count > 0) {
                    $scope.count -= response.data.length;
                } else if ($scope.count == $scope.enterpriseData[0].resultSize) {
                    remainder = $scope.enterpriseData[0].resultSize % 10;

                    if (remainder == 0) {
                        $scope.count -= response.data.length;
                    } else {
                        $scope.count -= remainder;
                    }
                }

                $scope.proceedNextEnt = true;

                if ($scope.count <= 10) {
                    $scope.proceedPrevEnt = false;
                }
                                        
                console.log($scope.enterpriseData);
            });
        }
    }

    $scope.proceedNextFeed = true;
    $scope.proceedPrevFeed = true;

    //get next feed page data
    $scope.getNxtFeedPage = function () {
        if ($scope.proceedNextFeed) {

            $scope.start += 1;
            $scope.loading = true;
            $scope.loading2 = true;

            $http({
                url: 'http://' + serverURL + ':11318/checkNewsfeed',
                method: "POST",
                data: { start: $scope.start }
            }).then(function (response) {
                //success 
                $scope.newsData = response.data;
                $scope.loading = false;
                $scope.loading2 = false;
                $scope.countFeed += response.data.length;

                if ($scope.countFeed < $scope.newsData[0].resultSize) {
                    $scope.proceedNextFeed = true;
                } else if ($scope.countFeed > $scope.newsData[0].resultSize) {
                    var remainder = $scope.newsData[0].resultSize % 10;
                    $scope.countFeed = $scope.countFeed - response.data.length + remainder;
                    $scope.proceedNextFeed = false;
                } else {
                    $scope.proceedNextFeed = false;
                }

                if ($scope.countFeed <= 10) {
                    $scope.proceedPrevFeed = false;
                } else {
                    $scope.proceedPrevFeed = true;
                }
            });
        }
    }

    //get previous feed page data
    $scope.getPrevFeedPage = function () {

        if ($scope.proceedPrevFeed) {
            $scope.loading = true;
            $scope.loading2 = true;

            if ($scope.start > 0) {
                $scope.start -= 1;
            } else {
                $scope.start = 0;
            }

            var remainder;

            $http({
                url: 'http://' + serverURL + ':11318/checkNewsfeed',
                method: "POST",
                data: { start: $scope.start }
            }).then(function (response) {
                //success 
                $scope.newsData = response.data;
                $scope.loading = false;
                $scope.loading2 = false;

                if ($scope.countFeed < $scope.newsData[0].resultSize && $scope.countFeed > 0) {
                    $scope.countFeed -= response.data.length;
                } else if ($scope.countFeed == $scope.newsData[0].resultSize) {
                    remainder = $scope.newsData[0].resultSize % 10;

                    if (remainder == 0) {
                        $scope.countFeed -= response.data.length;
                    } else {
                        $scope.countFeed -= remainder;
                    }
                }

                $scope.proceedNextFeed = true;

                if ($scope.countFeed <= 10) {
                    $scope.proceedPrevFeed = false;
                }

                console.log($scope.newsData);
            });
        }

    }

    //get inconsistant data
    $scope.showIndata = false;
    $scope.loadingIn = false;
    $scope.showButton = false;
    $scope.deleted = false;
    $scope.getInData = function () {
        $scope.loadingIn = true;
        $http.get('http://' + serverURL + ':11351/inconsistentData').success(function (response) {
            console.log(response);
            $scope.inData = response;
            $scope.showIndata = true;
            $scope.showButton = true;
            $scope.loadingIn = false;
        });
    };
    //delete inconsistant data
    $scope.deleteIndata = function () {
        $http({
            method: "POST",
            url: 'http://' + serverURL + ':11352/deleteIndata',
            data: { inData: $scope.inData }
        }).then(function (response) {
            console.log(response)

            if (response.data == "deleted") {
                $scope.deleted = true;
            }
        });
    };

    $scope.cancel = function () {
        $scope.showIndata = false;
        $scope.showButton = false;
    };
}