// JavaScript source code


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

    $scope.tableHeader2 = {views: 'Views', name: 'Name', content: 'Content', hash: '#' };
    //---------------------------------------------------------------------------------------

}

function enterpriseController($scope, $http) {

    //loading bar
    $scope.loading = true;

    //get danglers
    //' + serverURL + ':11316/danglers
    //localhost:11316/danglers
    $http.get('http://' + serverURL + ':11316/danglers').success(function (response) {
        $scope.danglers = response;
        $scope.danglersNo = response[0].no;
        console.log(response);
    });

    //total enterprises
    $http.get('http://' + serverURL + ':11300/enterprises?key=countEnt').success(function (data) {

        $scope.enterpriseCount = data;
        console.log(data);
    });
    //--------------------------------------------------------------------------------------------

    //total followers
    $http.get('http://' + serverURL + ':11300/enterprises?key=countFol').success(function (data) {

        $scope.followersCount = data;
    });
    //--------------------------------------------------------------------------------------------


    //get enterprises
    //' + serverURL + '
   /* $http.get('http://localhost:11300/enterprises?key=enterprices').success(function (data) {

        $scope.enterprises = data;
        console.log(data);
        $scope.loading = false;
    });
    */

    $scope.startEnt = 0;
    $scope.count = 0;
    var key = "enterprices";
    $http.defaults.headers.post["Content-Type"] = "application/x-www-form-urlencoded";
    $http({
        url: 'http://localhost:11300/enterprises',
        method: "POST",
        data: { key: key, startEnt: $scope.startEnt }
    }).then(function (response) {
        $scope.enterprises = response.data;
        console.log(response.data);
        $scope.loading = false;
        $scope.count += 10;
    });

    $scope.getNxtEnterprises = function () {

        $scope.loading = true;
        $scope.startEnt += 1;
        $scope.proceed = true;
        var key = "enterprices";

        if ($scope.proceed) {
            $scope.loading2 = true;
            $http.defaults.headers.post["Content-Type"] = "application/x-www-form-urlencoded";
            $http({
                url: 'http://localhost:11300/enterprises',
                method: "POST",
                data: { key: key, startEnt: $scope.startEnt }
            }).then(function (response) {
                $scope.enterprises = response.data;
                $scope.count += 10;
                console.log(response.data);
                $scope.loading = false;
                $scope.loading2 = false;
                if ($scope.count > $scope.enterpriseCount) {
                    $scope.count = $scope.enterpriseCount;
                    $scope.proceed = false;
                }
            });
        }
    };

    $scope.getPrevEnterprises = function () {

        $scope.loading2 = true;
        $scope.loading = true;
        $scope.startEnt -= 1;
        var key = "enterprices";

        if ($scope.startEnt >= 0) {

            $http.defaults.headers.post["Content-Type"] = "application/x-www-form-urlencoded";
            $http({
                url: 'http://localhost:11300/enterprises',
                method: "POST",
                data: { key: key, startEnt: $scope.startEnt }
            }).then(function (response) {
                $scope.enterprises = response.data;
                if ($scope.count <= $scope.enterpriseCount) { $scope.count -= 10; }
                console.log(response.data);
                $scope.loading = false;
                $scope.loading2 = false;
            });
        }
    };

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
    $scope.startWeek = 0;
    $scope.weekLoading = true;
    $http.defaults.headers.post["Content-Type"] = "application/x-www-form-urlencoded";
    $http({
        url: 'http://' + serverURL + ':11320/pastWeek',
        method: "POST",
        data: { start: $scope.startWeek }
    }).
        then(function (response) {
            //success 
            $scope.pastWeekLogins = response.data;
            $scope.weekLoading = false;
            console.log($scope.pastWeekLogins);
            //create pagination pages
            $scope.pages = new Array();
            for (var x = 0; x < $scope.pastWeekLogins[0].ASize; x++) {
                $scope.pages[x] = x + 1;
            }
            console.log($scope.pages);
        });



    //get certain page data
    $scope.getPage = function (index) {
        $scope.startWeek = index;
        $scope.weekLoading = true;
        $http({
            url: 'http://' + serverURL + ':11320/pastWeek',
            method: "POST",
            data: { start: $scope.startWeek }
        }).
        then(function (response) {
            //success 
            $scope.pastWeekLogins = response.data;
            $scope.weekLoading = false;
            console.log($scope.pastWeekLogins);
        });
    }
    //-------------------------------------------------------------------------------

    //get logs current month
    //' + serverURL + ':11321/currentMonth'
    //localhost:11321/currentMonth'
    /*   $http.get('http://' + serverURL + ':11321/currentMonth').success(function (data) {
   
           $scope.currentMonthLogins = data;
           console.log(data);
       }); */

    $scope.startMonth = 0;
    $scope.monthLoading = true;
    $http.defaults.headers.post["Content-Type"] = "application/x-www-form-urlencoded";
    $http({
        url: 'http://' + serverURL + ':11321/currentMonth',
        method: "POST",
        data: { start: $scope.startMonth }
    }).
        then(function (response) {
            //success 
            $scope.monthLogins = response.data;
            $scope.monthLoading = false;
            console.log($scope.monthLogins);
            //create pagination pages
            $scope.monthPages = new Array();
            for (var x = 0; x < $scope.monthLogins[0].ASize; x++) {
                $scope.monthPages[x] = x + 1;
            }
            console.log($scope.monthPages);
        });



    //get certain page data
    $scope.getMonthPage = function (index) {
        $scope.startMonth = index;
        $scope.monthLoading = true;
        $http({
            url: 'http://' + serverURL + ':11321/currentMonth',
            method: "POST",
            data: { start: $scope.startMonth }
        }).
        then(function (response) {
            //success 
            $scope.monthLogins = response.data;
            $scope.monthLoading = false;
            console.log($scope.monthLogins);
        });
    }
    $scope.logsTableHeaders = { hash: '#', time: 'Login Time', username: 'Username', enterprise: 'Enterprise Name' };
    //------------------------------------------------------------------------------------------------------------------
}



function addEnterpriseController($scope, $http) {

    //get all sectors
    //' + serverURL + '
    $http.get('http://' + serverURL + ':11000/getallsectors').success(function (data) {

        $scope.sectors = data;
        console.log(data);

    });



    //success message
    $scope.showSuccess = false;
    $scope.showFail = false;

    $scope.enterpriseAdd = {};
    $scope.enterpriseAdd.sector = '';
    $scope.enterpriseAdd.status = 1;
    $scope.enterpriseAdd.address = {};
    $scope.enterpriseAdd.address.country = "South Africa";
    $scope.enterpriseAdd.sectorID = 0;
    var myID = 0;
    $scope.imageStatus = false;

    $scope.sendImage = $(function ($scope) {
        $('#file-input').change(function (e) {
            var file = e.target.files[0],
                imageType = /image.*/;

            if (!file.type.match(imageType))
                return;

            var reader = new FileReader();
            reader.onload = fileOnload;
            reader.readAsDataURL(file);
        });

        function fileOnload(e){
            var $img = $('<img>', { src: e.target.result });
            var canvas = document.getElementById('canvas');
            var context = canvas.getContext('2d');
            var dataCanvas = canvas.toDataURL();
            //http://' + serverURL + ':11319/image
            $.ajax({
                url: 'http://localhost:11319/image',
                type: "POST",
                data: {
                    params: dataCanvas
                },
                contentType: "application/x-www-form-urlencoded",
                dataType: "json",
                success: function (result){
                    console.log(result);
                    myID = result;
                    console.log(myID);
                    $scope.imageStatus = true;
                }
            });

            $img.load(function () {
                context.drawImage(this, 0, 0);
            });
        }
    });


    $scope.sendData = function () {

        $scope.imageStatus = false;

        for (var x = 0; x < $scope.sectors.length ; x++) {

            if ($scope.sectors[x].displayName == $scope.enterpriseAdd.sector) {

                $scope.enterpriseAdd.sectorID = $scope.sectors[x].id;
            }
        } 

       $http.defaults.headers.post["Content-Type"] = "application/x-www-form-urlencoded";
          //'http://' + serverURL + ':11308/addEnterprise'
          //'ttp://localhost:11308/addEnterprise'
          $http({
              url: 'http://localhost:11308/addEnterprise',
              method: "POST",
              data: { entData: $scope.enterpriseAdd, entID: myID }
          }).
              then(function (response) {
                  //success
                  console.log(response.data);
                  var result = angular.fromJson(response.data);
                  console.log(result);
  
                  if (angular.equals(result, "Success")) {
                      $scope.showSuccess = true;
                  }
                  else {
                      $scope.showFail = true;
                  }
              });  
    }

 
   


}

function editEnterpriseController($scope, $http) {

    //status bar
    $scope.loading = true;

    //get the enterprises
    $http.get('http://' + serverURL + ':11309/getEnterprises').success(function (data) {
        $scope.enterpriseNames = data;
        console.log(data);
        $scope.loading = false;
    });

    $scope.name = " ";
    

    //watch for change of name
    $scope.$watch('name', function () {

        if ($scope.name != " ") {
            $scope.loading = true;
            $scope.editAddress = {};

            $http.defaults.headers.post["Content-Type"] = "application/x-www-form-urlencoded";

            //get address
            $http({
                url: 'http://' + serverURL + ':11311/getAddress',
                method: "POST",
                data: $scope.name
            }).then(function (response) {
                if (response.data != []) {
                    $scope.editAddress = response.data;
                    $scope.loading = false;
                }
            });

            //get enterprise info
            $http({
                url: 'http://' + serverURL + ':11310/findEnterprise',
                method: "POST",
                data: $scope.name
            }).then(function (response) {
                $scope.enterpriseData = response.data;

                /** Draw the image on to the webpage (if image exists) - By Rajesh **/

                var canvasLogo = $('#canvas')[0]; ;
                var c = canvasLogo.getContext('2d');

                var logoImg = new Image();
                logoImg.onload = function () {
                    c.clearRect(0, 0, canvas.width, canvas.height);

                    var grd = c.createLinearGradient(0, 0, canvas.width, canvas.height);
                    grd.addColorStop(0, "#FFFFFF");
                    grd.addColorStop(1, "#FFFFFF");
                    c.fillStyle = grd;
                    c.fillRect(0, 0, canvas.width, canvas.height);

                    if (logoImg != null) {
                        var originalWidth = logoImg.width;
                        logoImg.width = 160;
                        logoImg.height = 160;

                        var logo = {
                            img: logoImg,
                            x: (canvas.width / 2) - (logoImg.width / 2),
                            y: (canvas.height / 2) - (logoImg.height / 2)
                        }

                        c.drawImage(logo.img, logo.x, logo.y, logo.img.width, logo.img.height);

                        c.font = "bold 16px sans-serif";
                        var mt = c.measureText(phrase);
                        var xcoord = (canvas.width / 2) - (mt.width / 2);
                        c.fillStyle = '#656565'
                        c.fillText(phrase, xcoord, 30);
                    }
                }

                if ($scope.enterpriseData.enterpriseLogo != null) {
                    logoImg.src = $scope.enterpriseData.enterpriseLogo;
                } else {
                    logoImg = new Image();
                    c.clearRect(0, 0, canvas.width, canvas.height);
                }

                /** Till here Draw the image on to the webpage (if image exists) - By Rajesh **/

                /** By Rajesh - to show the details on the screen for the selected enterprise **/

                var addressObj = null;

                if ($scope.enterpriseData.physicalAddress != undefined) {
                    addressObj = JSON.parse($scope.enterpriseData.physicalAddress.value);
                }

                if (addressObj != null) {
                    if (addressObj[0] != undefined) {
                        $scope.editAddress.street = addressObj[0].Street;
                        $scope.editAddress.suburb = addressObj[0].Suburb;
                        $scope.editAddress.city = addressObj[0].City;
                        $scope.editAddress.postalCode = addressObj[0].PostalCode;
                        $scope.editAddress.province = addressObj[0].Province;
                        $scope.editAddress.country = addressObj[0].Country;
                    } else {
                        $scope.editAddress.street = addressObj.street;
                        $scope.editAddress.suburb = addressObj.suburb;
                        $scope.editAddress.city = addressObj.city;
                        $scope.editAddress.postalCode = addressObj.postalCode;
                        $scope.editAddress.province = addressObj.province;
                        $scope.editAddress.country = addressObj.country;
                    }
                }

                /** By Rajesh - to show the details on the screen for the selected enterprise **/


                /** By Rajesh - to draw the selected image on to screen **/

                $scope.sendImage = $(function ($scope) {
                    $('#file-input').change(function (evt) {
                        if (window.File && window.FileReader && window.FileList && window.Blob) {
                            //alert('I am here ....')
                            var files = evt.target.files;

                            var result = '';
                            var file;
                            for (var i = 0; i < files.length; i++) {
                                file = files[i];
                                // if the file is not an image, continue
                                if (!file.type.match('image.*')) {
                                    continue;
                                }

                                reader = new FileReader();
                                reader.onload = (function (tFile) {
                                    return function (evt) {
                                        var canvas = document.getElementById("canvas");
                                        var context = canvas.getContext('2d');

                                        // Store the current transformation matrix
                                        //context.save();

                                        // Use the identity matrix while clearing the canvas
                                        context.setTransform(1, 0, 0, 1, 0, 0);
                                        context.clearRect(0, 0, 160, 160);

                                        // Restore the transform
                                        //context.restore();
                                        var max = 160;
                                        var img = new Image();
                                        img.onload = function () {

                                            var r = img.naturalWidth > img.naturalHeight ? img.naturalWidth / max : img.naturalHeight / max;
                                            var w = img.naturalWidth / r,
                                            h = img.naturalHeight / r;


                                            var x = 0, y = 0;
                                            if (w < max) {
                                                var diff = max - w;
                                                x = diff / 2;
                                            }
                                            if (h < max) {
                                                var diff = max - h;
                                                y = diff / 2;
                                            }
                                            context.drawImage(img, x, y, w, h);
                                        };
                                        img.src = evt.target.result;

                                    };
                                } (file));
                                reader.readAsDataURL(file);
                            }
                        } else {
                            alert('The File APIs are not fully supported in this browser.');
                        }
                    });
                });

                /** By Rajesh - to draw the selected image on to screen **/

                //get sectors
                $http.get('http://' + serverURL + ':11000/getallsectors').success(function (data) {
                    for (var x = 0; x < data.length; x++) {
                        if (data[x].id == $scope.enterpriseData.sectorID) {
                            $scope.enterpriseData.sectorName = data[x].displayName;
                        }
                    }
                });
            });
        }
    });   

    //send data
    $scope.sendData = function (e) {

        var physicalAddress = { Street: $scope.editAddress.street, Suburb: $scope.editAddress.suburb,
            City: $scope.editAddress.city, PostalCode: $scope.editAddress.postalCode,
            Province: $scope.editAddress.province, Country: $scope.editAddress.country
        };

        $scope.enterpriseData.physicalAddress.value = JSON.stringify(physicalAddress);

        $scope.showSuccess = false;
        $scope.showFail = false;

        $http.defaults.headers.post['Content-Type'] = "application/x-www-form-urlencoded";

        var canvas = document.getElementById("canvas");
        var imageDataURL = canvas.toDataURL();

        //console.log('$scope.enterpriseData.enterpriseLogo (1) : ' + $scope.enterpriseData.enterpriseLogo + ' & imageDataURL : ' + imageDataURL);

        $scope.enterpriseData.enterpriseLogo = imageDataURL;

        //console.log('$scope.enterpriseData.enterpriseLogo (2) : ' + $scope.enterpriseData.enterpriseLogo);

        //update data
        $http({
            url: 'http://' + serverURL + ':11312/update',
            method: "POST",
            data: $scope.enterpriseData
        }).then(function (response) {
            //update address
            $scope.editAddress = {};

            $scope.editAddress.street = physicalAddress.Street;
            $scope.editAddress.suburb = physicalAddress.Suburb;
            $scope.editAddress.city = physicalAddress.City;
            $scope.editAddress.postalCode = physicalAddress.PostalCode;
            $scope.editAddress.province = physicalAddress.Province;
            $scope.editAddress.country = physicalAddress.Country;

            $http({
                url: 'http://' + serverURL + ':11313/updateAddress',
                method: "POST",
                data: { address: $scope.editAddress, name: $scope.name }
            }).then(function (response) {

                if (angular.fromJson(response.data) == "Success") {
                    $scope.showSuccess = true;
                } else {
                    $scope.showFail = true;
                }

            });
        });
    };
        
        
}

function newsFeedController($scope, $http) {

    //get danglers
    $http.get('http://localhost:11315/danglers').success(function (response) {
        $scope.danglers = response;
        $scope.danglersNo = response[0].no;
        console.log($scope.danglers);
    });

    //get newsfeeds stats
    $http.get('http://localhost:11314/stats').success(function (response) {

        $scope.feedStats = response[0];
        console.log(response);
    });

    //get Unviewed newsfeeds stats
    $http.get('http://localhost:11322/unviewed').success(function (response) {

        $scope.unviewed = response;
        console.log(response);
        if ($scope.unviewed == []) {
            $scope.unviewedSize = 0;
        } else {
            $scope.unviewedSize = $scope.unviewed.length;
        }
    });
}

function systemCheckController($scope, $http) {

    $scope.choice = " ";
    $scope.loading = false;
    $scope.loading2 = false;
    $scope.tableShow = false;

    //watch for change of choice
    $scope.$watch('choice', function () {

        $scope.start = 0; //start pagnation 
        $http.defaults.headers.post["Content-Type"] = "application/x-www-form-urlencoded";
        $scope.tableShow = false;
        $scope.newsShow = false;

        if ($scope.choice != " ") {
            $scope.loading = true;
            $scope.loading2 = false;
            if ($scope.choice == "Enterprise Data") {
                //get enterprise comparism 
                $http({
                    url: 'http://localhost:11317/checkEnterprise',
                    method: "POST",
                    data: { start: $scope.start }
                }).
                    then(function (response) {
                        //success 
                        $scope.enterpriseData = response.data;
                        console.log($scope.enterpriseData);
                        $scope.tableShow = true;
                        $scope.newsShow = false;
                        $scope.loading = false;
                    });
            }
            else if ($scope.choice == "News Feed Data") {
                //get newsfeeds comparisim
                $http({
                    url: 'http://localhost:11318/checkNewsfeed',
                    method: "POST",
                    data: { start: $scope.start }
                }).
            then(function (response) {
                //success 
                $scope.newsData = response.data;
                console.log($scope.newsData);
                $scope.tableShow = false;
                $scope.loading = false;
                $scope.newsShow = true;

            });

            }
            else if ($scope.choice == "Duplicate Nodes") {
                //get duplicate nodes
                $http.get('http://localhost:11323/duplicateNodes').success(function (response) {

                    $scope.duplicates = response;
                    console.log($scope.duplicates);
                });
            }
        }
    });

    //get next enterprise page data
    $scope.getNextEntPage = function () {
        $scope.start += 1;
        $scope.loading = true;
        $scope.loading2 = true;
        if (($scope.start * 10) <= $scope.enterpriseData[0].resultSize) {
            $http({
                url: 'http://localhost:11317/checkEnterprise',
                method: "POST",
                data: { start: $scope.start }
            }).
            then(function (response) {
                //success 
                $scope.enterpriseData = response.data;
                $scope.loading = false;
                $scope.loading2 = false;
                console.log($scope.enterpriseData);
            });
        }
    }
    
    //get previous entertainment page data
    $scope.getPrevEntPage = function () {
        $scope.start -= 1;;
        $scope.loading = true;
        $scope.loading2 = true;
        if ($scope.start >= 0) {
            $http({
                url: 'http://localhost:11317/checkEnterprise',
                method: "POST",
                data: { start: $scope.start }
            }).
            then(function (response) {
                //success 
                $scope.enterpriseData = response.data;
                $scope.loading = false;
                $scope.loading2 = false;
                console.log($scope.enterpriseData);
            });
        }
    }

    //get next feed page data
    $scope.getNxtFeedPage = function () {
        $scope.start += 1;
        console.log($scope.start);
        $scope.loading = true;
        $scope.loading2 = true;

        if (($scope.start * 10) <= $scope.newsData[0].resultSize) {

        
        $http({
            url: 'http://localhost:11318/checkNewsfeed',
            method: "POST",
            data: { start: $scope.start }
        }).
        then(function (response) {
            //success 
            $scope.newsData = response.data;
            $scope.loading = false;
            $scope.loading2 = false;
            console.log($scope.newsData);
        });
        }
    }

    //get previous feed page data
    $scope.getPrevFeedPage = function () {

        $scope.start -= 1;
        $scope.loading = true;
        $scope.loading2 = true;
        if ($scope.start >= 0) {
            $http({
                url: 'http://localhost:11318/checkNewsfeed',
                method: "POST",
                data: { start: $scope.start }
            }).
            then(function (response) {
                //success 
                $scope.newsData = response.data;
                $scope.loading = false;
                $scope.loading2 = false;
                console.log($scope.newsData);
            });
        }

    }
}