// JavaScript source code
//var serverURL = "197.96.19.118"; //dev
//var serverURL = "197.96.18.60"; //prod

function followersController($scope, $http) {

    //progress bar
    $scope.loading = true;

    //enterprise with most connections
    //'http://' + serverURL + ':11304/most'
    //localhost
    $http.get('http://' + serverURL + ':11304/most').success(function (data) {
        $scope.mostEntNames = data;
        console.log(data);
    });

    $scope.tableHeader = { name: 'Enterprise Name', followers: 'Followers', hash: '#' };
    //----------------------------------------------------------------------------------

    //    enterprise with least connections
    //' + serverURL + ':11303/least'
    //localhost:11303/least
    $http.get('http://' + serverURL + ':11303/least').success(function (data) {
        $scope.leastEntNames = data;
        console.log(data);
    });
    //--------------------------------------------------------------------------------------

    //user with most connections
    //localhost:11306/mostUser
    //' + serverURL + ':11306/mostUser'
    $http.get('http://' + serverURL + ':11305/mostUser').success(function (data) {
        $scope.mostUsers = data;
        console.log(data);
    });

    $scope.tableHeader1 = { consumer: 'Consumer No.', follows: 'Follows', hash: '#', name: 'Name' };
    //---------------------------------------------------------------------------------------

    //most viewed news feed
    $http.get('http://' + serverURL + ':11306/mostFeed').success(function (data) {
        $scope.newsFeeds = data;
        console.log(data);
        $scope.loading = false;
    });

    $scope.tableHeader2 = { views: 'Views', name: 'Name', content: 'Content', hash: '#' };
    //---------------------------------------------------------------------------------------

}