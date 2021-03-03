// Define the `phonecatApp` module
var inobitecApp = angular.module('inobitecApp', ['ngSanitize','ui.mask']);

inobitecApp.directive('onFinishRender', function ($timeout) {
    return {
        restrict: 'A',
        link: function (scope, element, attr) {
            if (scope.$last === true) {
                $timeout(function () {
                    scope.$emit(attr.onFinishRender);
                });
            }
        }
    }
});    
// Define the `PhoneListController` controller on the `phonecatApp` module
inobitecApp.controller('buyPageController', ['$window', '$scope', '$http', '$rootScope', function buyPageController($window, $scope, $http, $rootScope) {
  $scope.usdCurr = false;
  $scope.showBuyBlock = true;  
  $scope.sectionID = false;
  $scope.site = "s1";
  $scope.extRedactions = [];

  $scope.liteRedactions = [];
  $scope.proRedactions = [];
  
  $scope.$on('reInitDataMain', function(event, data) {
      $scope.initDicomviewerPage();
  });
  
  $scope.redirect = redirect;
  
  $scope.initDicomviewerPage = initDicomviewerPage;
  $scope.addToBasket = addToBasket;
  $scope.addModuleToBasket = addModuleToBasket;
  $scope.deleteFromBasket = deleteFromBasket;
  $scope.addRedactionlite = addRedactionlite;
  $scope.addRedactionpro = addRedactionpro;
  $scope.needCheckEndProRender = false;
  $scope.license = {
        "viewerLic": false,
        "serverLic": false,
    };
  
  $scope.checkIfLiteRedactionsFull = checkIfLiteRedactionsFull;
  $scope.checkIfProRedactionsFull = checkIfProRedactionsFull;
  $scope.changeViewBlock = changeViewBlock;
  $scope.calculateDicomViewerPrice = calculateDicomViewerPrice;
  $scope.parsePrice = parsePrice;
  $scope.countModulesPrice = countModulesPrice;
  $scope.calculateLicProPrice_renew = calculateLicProPrice_renew;
  $scope.calculateLicLitePrice = calculateLicLitePrice;
  $scope.calculateLicLitePrice_renew = calculateLicLitePrice_renew;
  $scope.calculateLicProPrice = calculateLicProPrice;

  
  $scope.yearLicPrice = {};
  $scope.yearLicPrice['1'] = false;
  $scope.yearLicPrice['2'] = false;
  $scope.yearLicPrice['3'] = false;
  $scope.fullViewerPrice = false;
  $scope.fullPrice = false;
  
  $scope.increaseGoodAmount = increaseGoodAmount;
  $scope.reduceGoodAmount = reduceGoodAmount;
  
  $scope.timerId
  
  $scope.itemAddupdateFilter = function (item){
    return item.canUpdateLic;
  };
  
  $scope.setGoodAmount = function(good){
    if(!Number.isInteger(good.quantity))
        good.quantity = 0;
     if(good.quantity < 1){
        good.quantity = 1;
      }
      if(good.quantity > 99){
        good.quantity = 99;
      }
      
      //return false;
    clearTimeout($scope.timerId);
    $scope.timerId = setTimeout(function () {
        if(good.quantityold && good.quantityold == good.quantity)
          return false;
        $('.preloader').show();
        $http.get('/include/basket/changeGoodsCount.php?itemBasketId='+good.cartitemId+'&count='+good.quantity).then(function (response) {
          good.quantityold = good.quantity;
           $rootScope.$broadcast('reInitData');
            $('.preloader').hide();
        });
    }, 1500);
  }
    
  function increaseGoodAmount(good){
    if(!good.inbasket)
      return false;
    good.quantity++;
    if(good.modules){
      for(keyModule in good.modules){
        if(good.modules[keyModule].inbasket){
          good.modules[keyModule].quantity++;
        }
      }
    }
    $scope.setGoodAmount(good);
    
  }
  
  $scope.checkIfLicenseForUpdate = function(){
    $flag = false;
    if($scope.extRedactions){
      if($scope.extRedactions.lite){
        for(keyExtLite in $scope.extRedactions.lite){
          $flag = $scope.extRedactions.lite[keyExtLite].canUpdateLic;
          if($flag)
            return $flag;
        }
      }
      
      if($scope.extRedactions.pro){
        for(keyExtPro in $scope.extRedactions.pro){
          $flag = $scope.extRedactions.pro[keyExtPro].canUpdateLic;
          if($flag)
            return $flag;
        }
      }
    }
    return $flag;
  }
  
  $scope.changeModuleStatus = function($event, module, proItem){
    if(module.inbasket){
      deleteFromBasket($event, module);
    }else{
      addModuleToBasket($event, module, proItem);
    }
    
  }
  
  $scope.changeViewerLic = function(year, proItem){
    $('.preloader').show();
    if($scope.ifViewerLicSolid(year, proItem)){
      $scope.delViewerLic(proItem);
    }else{
      if(proItem.license.inbasket && proItem.license.cartitemId > 0){
        $http.get('/include/basket/deleteGood.php?itemBasketId='+proItem.license.cartitemId).then(function successCallback(response) {
          if(response.data.success == proItem.license.cartitemId){
            proItem.license.inbasket = false;
            proItem.license.years = false;
            proItem.license.cartitemId = false;
            $rootScope.$broadcast('reInitData');
            $scope.addViewerLic(year,proItem);
          }
        }, function errorCallback(response) {
        });
      }else{
        $scope.addViewerLic(year,proItem);
      }
      
    }
    
    
  }
  
  function reduceGoodAmount(good){
    if(!good.inbasket)
      return false;
    if(good.quantity == 1)
      return false;
    good.quantity--;
    if(good.modules){
      for(keyModule in good.modules){
        if(good.modules[keyModule].inbasket){
          good.modules[keyModule].quantity--;
        }
      }
    }
    $scope.setGoodAmount(good);
  }
  
  $scope.checkIfInBaketLicenseUpdate = function(years, extItem){
    if(!extItem.updateLic)
      return false;
    if(extItem.updateLic.years == years)
      return true;
    return false;
  }
  
  $scope.changeLicenseUpdate = function(years, extItem){
    $('.preloader').show();
    if(extItem.updateLic && extItem.updateLic.years == years){
      $scope.delViewerLicUpdate(extItem.updateLic);
    }else if(extItem.updateLic){
      if(extItem.updateLic.cartitemId > 0){
        $http.get('/include/basket/deleteGood.php?itemBasketId='+extItem.updateLic.cartitemId).then(function successCallback(response) {
          if(response.data.success == extItem.updateLic.cartitemId){
            extItem.updateLic.inbasket = false;
            extItem.updateLic.years = false;
            extItem.updateLic.cartitemId = false;
            $rootScope.$broadcast('reInitData');
            $scope.addViewerLicUpdateViewer(years,extItem);
          }
        }, function errorCallback(response) {
        });
      }else{
        $scope.addViewerLicUpdateViewer(years,extItem);
      }
    }else{
      $scope.addViewerLicUpdateViewer(years,extItem);
    }
    
  }
  
  $scope.addViewerLicUpdateViewer = function(years, extItem){
    $http.get('/include/basket/addLicenseUpdate.php?productId='+$scope.license.viewerLic.productID+"&years="+years + "&licenseId="+extItem.oldLicID).then(function successCallback(response) {
      if(response.data.itemBasketId){
        if(!extItem.updateLic)
          extItem.updateLic = [];
        extItem.updateLic.inbasket = true;
        extItem.updateLic.years = years;
        extItem.updateLic.price = response.data.price;
        extItem.updateLic.cartitemId = response.data.itemBasketId;
        $rootScope.$broadcast('reInitData');
        $('.preloader').hide();
      }else{
      }
    }, function errorCallback(response) {
      $('.preloader').hide();
    });
  }
  
  $scope.countBoughtModules = function(proItem){
    var $n = 0;
    for(keyModule in proItem.modules){
      if(proItem.modules[keyModule].inbasket){
        $n++;
      }
    }
    return $n;
  }
  
  function changeViewBlock(blockType){
    if(blockType == 'buy' && $scope.showBuyBlock){
      return false;
    }
    else if(blockType == 'lic' && !$scope.showBuyBlock){
      return false;
    }
    $scope.showBuyBlock = !$scope.showBuyBlock;
  }
  
  function redirect(url){
    $window.location.href = url;
  }
  
  function calculateLicLitePrice(price, years){
    var processedPrice = price.replace(/ /g, '');
    if(years == 1)
      return processedPrice * 0.3;
    if(years == 2)
      return processedPrice * 0.25;
    if(years == 3)
      return processedPrice * 0.2;
    return false;
  }
  
  function calculateLicLitePrice_renew(good, years){
    $price = false;
    if($scope.site == "s1")
      $price = good.price;
    else
      $price = good.price_en;
    var processedPrice = $price.replace(/ /g, '');
    if(years == 1)
      return processedPrice * 0.3;
    if(years == 2)
      return processedPrice * 0.25;
    if(years == 3)
      return processedPrice * 0.2;
    return false;
  }
  
  function calculateLicProPrice(good, years){
    $price = good.price;
    if(good.currency == 'USD')
      $price = good.price_en;
    var processedPrice = parseInt($price.replace(/ /g, ''));
    processedPrice += parseInt(countModulesPrice(good));
    if(years == 0)
      return processedPrice;
    if(years == 1)
      return processedPrice * 0.3;
    if(years == 2)
      return processedPrice * 0.25;
    if(years == 3)
      return processedPrice * 0.2;
    return false;
  }

  function calculateLicProPrice_renew(good, years){
    $price = false;
    if($scope.site == "s1")
      $price = good.price;
    else
      $price = good.price_en;
    var processedPrice = parseInt($price.replace(/ /g, ''));
    processedPrice += parseInt(countModulesPrice_renew(good));
    if(years == 0)
      return processedPrice;
    if(years == 1)
      return processedPrice * 0.3;
    if(years == 2)
      return processedPrice * 0.25;
    if(years == 3)
      return processedPrice * 0.2;
    return false;
  }
  
  function checkIfLiteRedactionsFull(){
    for(keyLite in $scope.liteRedactions){
      if(!$scope.liteRedactions[keyLite].inbasket){
        return false;
      }
    }  
    return true;
  }
  
  function checkIfProRedactionsFull(){
    for(keyPro in $scope.proRedactions){
      if(!$scope.proRedactions[keyPro].inbasket){
        return false;
      }
    }  
    return true;
  }
  
  function addRedactionlite(){
    if(!$scope.checkIfLiteRedactionsFull())
      return false;
    $http.get('/include/basket/addRedaction.php?sectionID='+$scope.sectionID+"&siteID="+$scope.site).then(function successCallback(response) {
        if(response.data.lite){
          $scope.liteRedactions.push(response.data.lite[0]);
        }
          
      }, function errorCallback(response) {
      });
  }
  
  function addRedactionpro(){
    if(!$scope.checkIfProRedactionsFull())
      return false;
    $http.get('/include/basket/addRedaction.php?sectionID='+$scope.sectionID+"&siteID="+$scope.site).then(function successCallback(response) {
        if(response.data.pro){
          $scope.proRedactions.push(response.data.pro[0]);
        }
          
      }, function errorCallback(response) {
      });
  }

  function initDicomviewerPage(){
    if($scope.sectionID)
      $http.get('/include/basket/getBasketInfo.php?sectionID='+$scope.sectionID+"&siteID="+$scope.site).then(buyPageInfo);
  }

  function countModulesPrice(good){

    //if(!good.inbasket)
     // return 0;
    $total = 0;
    
    if(good.modules){
      for(keyModule in good.modules){
        if(good.modules[keyModule].inbasket){
          $price = good.modules[keyModule].price;
          if(good.currency == 'USD')
            $price = good.modules[keyModule].price_en;
          var processedModule = $price.replace(/ /g, '');
          $total +=  parseInt(processedModule);
        }
      }
    }
    return $total;
  }
  
  function countModulesPrice_renew(good){

    //if(!good.inbasket)
     // return 0;
    $total = 0;
    
    if(good.modules){
      for(keyModule in good.modules){
        if(good.modules[keyModule].inbasket){
          $price = false;
          if($scope.site == "s1")
            $price = good.modules[keyModule].price;
          else
            $price = good.modules[keyModule].price_en;  
          var processedModule = $price.replace(/ /g, '');
          $total +=  parseInt(processedModule);
        }
      }
    }
    return $total;
  }
  
  $scope.getNumber = function(num) {
      return new Array(num);   
  }
  
  function buyPageInfo(response) {
    console.log(response)
    if(response.data.USD_CUR)
      $scope.usdCurr = response.data.USD_CUR;
    if(response.data.viewerLic)
      $scope.license.viewerLic = response.data.viewerLic;
    else
      $scope.license.viewerLic = false;
    if(response.data.serverLic)
      $scope.license.serverLic = response.data.serverLic;
    else
      $scope.license.serverLic = false
    if(response.data.listOfExtLic)  
      $scope.extRedactions = response.data.listOfExtLic;
    if(!$scope.extRedactions.lite )
      $scope.extRedactions.lite = [];
    if(!$scope.extRedactions.pro)
      $scope.extRedactions.pro = [];
    $scope.liteRedactions = response.data.lite;
    $scope.needCheckEndProRender = true;
    $scope.proRedactions = response.data.pro;
    
    $('.preloader').hide();
  }
  
  $scope.$on('proRedactionsRenderEnd', function(proRedactionsRenderEndEvent) {
    if(!$scope.needCheckEndProRender)
      return false;
    for(keyPro in $scope.proRedactions){
      if($scope.proRedactions[keyPro].inbasket){
        //openCase(keyPro);
      }
    }
    $scope.needCheckEndProRender = false;
  });
  
  function addToBasket($event, good){
    if(good.inbasket)
        return false;
    $http.get('/include/basket/addGood.php?productID='+good.productID+'&uniqueCode='+good.basketId).then(function successCallback(response) {
        if(response.data.itemBasketId){
          //gtag_report_addToBasket();
          good.inbasket = true;
          good.cartitemId = response.data.itemBasketId;
          $rootScope.$broadcast('reInitData');
        }
        
          
      }, function errorCallback(response) {
      });
  }
  
  function addModuleToBasket($event, module, lic){
    if(module.inbasket || !lic.inbasket || !lic.basketId)
        return false;

    $http.get('/include/basket/addGood.php?productID='+module.productID+'&parenId='+lic.basketId).then(function successCallback(response) {
        if(response.data.itemBasketId){
          module.inbasket = true;
          module.cartitemId = response.data.itemBasketId;
          module.quantity = lic.quantity;
          $rootScope.$broadcast('reInitData');
        }
          
      }, function errorCallback(response) {
      });
  }
  
  function deleteFromBasket($event, good){
      if(!good.inbasket)
        return false;
      $http.get('/include/basket/deleteGood.php?itemBasketId='+good.cartitemId).then(function successCallback(response) {
        if(response.data.success == good.cartitemId){
          good.inbasket = false;
          good.quantity = 1;
          if(good.modules){
            for(keyModule in good.modules){
              good.modules[keyModule].inbasket = false;
              good.modules[keyModule].quantity = 1;
            }
          }
          if(good.license && good.license.inbasket){
            good.license.inbasket = false;
            good.license.years = false;
            good.license.cartitemId = false;
          }
          $rootScope.$broadcast('reInitData');
        }
          
      }, function errorCallback(response) {
      });
  }
  
  function calculateDicomViewerPrice(){
    var price = 0;
    for(keyLite in $scope.liteRedactions){
      if($scope.liteRedactions[keyLite].inbasket){
        
        var processed = $scope.liteRedactions[keyLite].price.replace(/ /g, '');
       
        price += (parseInt(processed) * parseInt($scope.liteRedactions[keyLite].quantity));
        if($scope.liteRedactions[keyLite].license.inbasket){
          price += parseInt( parseInt(parseInt(processed)  * (0.3-(($scope.liteRedactions[keyLite].license.years-1)*0.05))* $scope.liteRedactions[keyLite].license.years) * parseInt($scope.liteRedactions[keyLite].quantity));
        }
      }
    }  
  
    for(keyPro in $scope.proRedactions){
      if($scope.proRedactions[keyPro].inbasket){
        var processed = $scope.proRedactions[keyPro].price.replace(/ /g, '');
         
        var modulesProcc = 0;
        price += (parseInt(processed) * parseInt($scope.proRedactions[keyPro].quantity));
        
        for(keyModule in $scope.proRedactions[keyPro].modules){
          if($scope.proRedactions[keyPro].modules[keyModule].inbasket){
            var processedModule = $scope.proRedactions[keyPro].modules[keyModule].price.replace(/ /g, '');
            modulesProcc += parseInt(processedModule);
            price += parseInt(parseInt(processedModule) * parseInt($scope.proRedactions[keyPro].modules[keyModule].quantity));
          }
        }
        if($scope.proRedactions[keyPro].license.inbasket){
          price += parseInt(parseInt( parseInt(modulesProcc) + parseInt(processed)) * (0.3-(($scope.proRedactions[keyPro].license.years-1)*0.05))* $scope.proRedactions[keyPro].license.years)  * parseInt($scope.proRedactions[keyPro].quantity);
        }
      }
    }
    $scope.fullViewerPrice = price;
    $scope.fullPrice = price;

    return price;
    
  }
  
  function parsePrice($price, currency = false, state = false){
    siteId = false;
    if(!currency)
      siteId = $scope.site;
    else if(currency == "RUB")
      siteId = 's1';
    else if(currency == "USD")
      siteId = 's2';
    if(siteId == 's1')
      return $price.toString().replace(/(\d)(?=(\d{3})+(?!\d))/g, '$1 ');
    else
      if(!state)
        return parseInt($price);
      else
        return $price.toFixed(1);
  }

  $scope.parsePriceCustom = function(item, currency = false, state = false){
    siteId = false;
    if(!currency)
      siteId = $scope.site;
    else if(currency == "RUB")
      siteId = 's1';
    else if(currency == "USD")
      siteId = 's2';
    $price = item.price;
    if(siteId == 's2')
      $price = item.price_en;
    if(siteId == 's1')
      return $price.toString().replace(/(\d)(?=(\d{3})+(?!\d))/g, '$1 ');
    else
      if(!state)
        return parseInt($price);
      else
        return $price.toFixed(1);
  }
  
  $scope.ifViewerLicSolid = function(years, good){
    if(!good.license)
      return false;
    if(!good.license.inbasket)
      return false;
    if(good.license.years != years)
      return false;
    return true;
  }
  
  $scope.ifViewerLicActive = function(years){
    if(calculateDicomViewerPrice() == 0)
      return false;
    if($scope.license.viewerLic.inbasket && $scope.license.viewerLic.years != years)
      return false;
    return true;
  }
  
  $scope.addViewerLic = function(years, good){
    if(!$scope.ifViewerLicActive(years)){
      return;
    }
    $http.get('/include/basket/addLicense.php?productId='+$scope.license.viewerLic.productID+"&years="+years + "&basketItemId="+good.cartitemId).then(function successCallback(response) {
      if(response.data.itemBasketId){
        good.license.inbasket = true;
        good.license.years = years;
        good.license.cartitemId = response.data.itemBasketId;
        $rootScope.$broadcast('reInitData');
        $('.preloader').hide();
      }
    }, function errorCallback(response) {
      $('.preloader').hide();
    });
  }
  
  $scope.delViewerLicUpdate = function(good){
    $http.get('/include/basket/deleteGood.php?itemBasketId='+good.cartitemId).then(function successCallback(response) {
      if(response.data.success == good.cartitemId){
        good.inbasket = false;
        good.years = false;
        good.cartitemId = false;
        $rootScope.$broadcast('reInitData');
        $('.preloader').hide();
      }

    }, function errorCallback(response) {
      $('.preloader').hide();
    });
  }
  
  $scope.delViewerLic = function(good){

    if(!good.license.inbasket || good.license.cartitemId < 0)
      return;
    $http.get('/include/basket/deleteGood.php?itemBasketId='+good.license.cartitemId).then(function successCallback(response) {
      if(response.data.success == good.license.cartitemId){
        good.license.inbasket = false;
        good.license.years = false;
        good.license.cartitemId = false;
        $rootScope.$broadcast('reInitData');
        $('.preloader').hide();
      }

    }, function errorCallback(response) {
      $('.preloader').hide();
    });
  }
  
  $scope.calculateFullDicomViewerPrice = function(){
    var price = $scope.calculateDicomViewerPrice();
    if($scope.license.viewerLic.inbasket)
      price += parseInt($scope.license.viewerLic.price.replace(/ /g, ''));
    return price;
  }

}]);

inobitecApp.controller('buyServerController', ['$window', '$scope', '$http', '$rootScope', function buyServerController($window, $scope, $http, $rootScope) {
  $scope.usdCurr = false;  
  $scope.showBuyBlock = true;
  $scope.sectionID = false;
  $scope.site = "s1";
  $scope.serverRedaction = [];
  $scope.initDicomserverPage = initDicomserverPage;
  $scope.addServerToBasket = addServerToBasket;
  $scope.delServerFromBasket = delServerFromBasket;
  $scope.extRedactions = [];
  $scope.license = {
        "viewerLic": false,
        "serverLic": false,
    };
    
  $scope.serverProductKey = false;  
    
  $scope.yearLicPrice = {};
  $scope.yearLicPrice['1'] = false;
  $scope.yearLicPrice['2'] = false;
  $scope.yearLicPrice['3'] = false;
  $scope.fullViewerPrice = false;
  $scope.fullPrice = false;  
  $scope.beforeLodaing = true;  
  $scope.changeViewBlock = changeViewBlock;
  $scope.redirect = redirect;
  $scope.calculateLicServerPrice = calculateLicServerPrice;
  $scope.calculateLicServerPrice_renew = calculateLicServerPrice_renew;
  $scope.existCompCodeArr = false;
  $scope.redactionForNewConnections = false;
  $scope.ignoreSetConnections = false;
  
  $scope.itemAddupdateFilter = function (item){
      return item.canUpdateLic;
    };
  
  
  $scope.$on('reInitDataMain', function(event, data) {
    $scope.serverProductKey = "";
    console.log("REINITDATAMAIN");
      $scope.initDicomserverPage();
  });
  
  
  $scope.checkIfLicenseForUpdate = function(){
    $flag = false;
    if($scope.extRedactions){
      if($scope.extRedactions.server){
        for(keyExtServ in $scope.extRedactions.server){
          console.log($scope.extRedactions.server[keyExtServ]);
          $flag = $scope.extRedactions.server[keyExtServ].canUpdateLic;
          if($flag)
            return $flag;
        }
      }
    }
    return $flag;
  }
  
  
  $scope.changeServerLic = function(years, good){
    $('.preloader').show();
    if($scope.ifServerLicSolid(years, good)){
      $scope.delServerLic(good);
    }else{
      if(good.license.inbasket && good.license.cartitemId > 0){
        $http.get('/include/basket/deleteGood.php?itemBasketId='+good.license.cartitemId).then(function successCallback(response) {
          if(response.data.success == good.license.cartitemId){
            good.license.inbasket = false;
            good.license.years = false;
            good.license.cartitemId = false;
            $rootScope.$broadcast('reInitData');
            $scope.addServerLic(years,good);
          }
        }, function errorCallback(response) {
          //console.log(response);
        });
      }else{
        $scope.addServerLic(years,good);
      }
      
    }
  }
  
  $scope.getNumber = function(num) {
      return new Array(num);   
  }
  
  function calculateLicServerPrice(price, years){
    if(!price)
      return false;
    if(Number.isInteger(price))
      var processedPrice  = price;
    else
      var processedPrice = price.replace(/ /g, '');
    if(years == 1)
      return processedPrice * 0.3;
    if(years == 2)
      return processedPrice * 0.25;
    if(years == 3)
      return processedPrice * 0.2;
    return false;
  }
  
  function calculateLicServerPrice_renew(extItem, years, currency = false){
    if($scope.site == "s1" || ($scope.site == "s2" && currency == 'RUB') )
      price = extItem.price;
    else
      price = extItem.price_en;
    
    price = parseInt(extItem.price) * parseInt(extItem.numConnection);
    
    if(!price)
      return false;
    if(Number.isInteger(price))
      var processedPrice  = price;
    else
      var processedPrice = price.replace(/ /g, '');
    
    if(years == 1){
      if($scope.site == "s1" || ($scope.site == "s2" && currency == 'RUB') )
        return processedPrice * 0.3;
      return processedPrice/$scope.usdCurr * 0.3;
    }
    if(years == 2){
      if($scope.site == "s1" || ($scope.site == "s2" && currency == 'RUB') )
        return processedPrice * 0.25;
      return processedPrice/$scope.usdCurr * 0.25;
    }
    if(years == 3){
      if($scope.site == "s1" || ($scope.site == "s2" && currency == 'RUB') )
        return processedPrice * 0.2;
      return processedPrice/$scope.usdCurr * 0.2;
    }
    
    
    
    return false;
  }
  
  function changeViewBlock(blockType){
    if(blockType == 'buy' && $scope.showBuyBlock){
      return false;
    }
    else if(blockType == 'lic' && !$scope.showBuyBlock){
      return false;
    }
    $scope.showBuyBlock = !$scope.showBuyBlock;
  }
  
  function initDicomserverPage(){
    console.log('/include/basket/getBasketInfo.php?sectionID='+$scope.sectionID+"&siteID="+$scope.site);
    if($scope.sectionID)
      $http.get('/include/basket/getBasketInfo.php?sectionID='+$scope.sectionID+"&siteID="+$scope.site).then(buyServerPageInfo);
  };
  
  function redirect(url){
    $window.location.href = url;
  }
  
  $scope.$watch('serverConnections',function(newValue,oldValue)
  {
    if($scope.serverRedaction.inbasket){
      if(newValue != $scope.serverRedaction.serverConnections)
        $scope.serverConnections = parseInt($scope.serverRedaction.serverConnections);
    }
  });
  
  $scope.$watch('serverProductKey',function(newValue,oldValue)
  {
    if(newValue && newValue != oldValue){
      $scope.checkIfExistCompCode(newValue);
    }
    
  });
  
  $scope.checkIfExistCompCode = function(compCode){
    if($scope.serverRedaction.inbasket || ($scope.redactionForNewConnections && $scope.redactionForNewConnections.inbasket ))
      return false;
    $http.get('/include/basket/checkIfCompCodeExist.php?compCode='+compCode).then(function successCallback(response){
      $scope.existCompCodeArr = response.data;
      if($scope.existCompCodeArr.existCode){    //если код повторяется
        if(!$scope.existCompCodeArr.currentUser){  //если код для другого пользователя
          Popup('another_user_compcode');
          $scope.redactionForNewConnections = false;
        }else{                                     //если код текущегоп ользователя
          var maxNum = 1;
          if($scope.extRedactions)
            if($scope.extRedactions.server){
              var $tempRedaction = false;
              for(keyExtServ in $scope.extRedactions.server){
                if($scope.extRedactions.server[keyExtServ].compCode == compCode){
                  $tempRedaction = $scope.extRedactions.server[keyExtServ]
                }
              }
              if($tempRedaction){
                $scope.redactionForNewConnections = [];
                $scope.redactionForNewConnections.shortName = $tempRedaction.shortName;
                $scope.redactionForNewConnections.detailText = $tempRedaction.detailText;
                $scope.redactionForNewConnections.price = $tempRedaction.price;
                $scope.redactionForNewConnections.productID = $tempRedaction.productID;
                $scope.redactionForNewConnections.inbasket = false;
                $scope.redactionForNewConnections.basketId = $tempRedaction.basketId;
                $scope.redactionForNewConnections.lic = $tempRedaction.lic;
                $scope.redactionForNewConnections.numConnection = $tempRedaction.numConnection;
                $scope.redactionForNewConnections.oldLicID = $tempRedaction.oldLicID;
                $scope.redactionForNewConnections.compCode = $tempRedaction.compCode;
                $scope.redactionForNewConnections.maxYears = $tempRedaction.maxYears;
                $scope.redactionForNewConnections.licKey = $tempRedaction.licKey;
                $scope.redactionForNewConnections.actualLic = $tempRedaction.actualLic;
                $scope.redactionForNewConnections.endData = $tempRedaction.endData;
                $scope.redactionForNewConnections.haveUpdates = $tempRedaction.haveUpdates;
                $scope.redactionForNewConnections.updateLic = [];
                if($scope.redactionForNewConnections.haveUpdates){
                  Popup('already_have_updates');
                  return false;
                }
              }else{
                $scope.redactionForNewConnections = [];
                $scope.redactionForNewConnections.haveUpdates = true;
                Popup('already_have_updates');
                return false;
              }

              //maxNum = 50 - $scope.redactionForNewConnections.numConnection;
			  maxNum = 50;
            }
          if($scope.serverConnections > maxNum){
            $scope.serverConnections = maxNum;
            $( ".slider" ).slider( "value", maxNum );
          }
          $( ".slider" )
                .slider( "option", "max", maxNum )
                .slider("pips", "refresh")
                .draggable({disabled: true});
        }
      }else{
        $scope.redactionForNewConnections = false;
          $( ".slider" )
                .slider( "option", "max", 50 )
                .slider("pips", "refresh")
                .draggable({disabled: true});
      }
      console.log(response);
    });
  }
  
  $scope.parseInt = function(num){
    return parseInt(num);
  }
  
  function buyServerPageInfo(response){
    console.log(response);
    if(response.data.USD_CUR)
      $scope.usdCurr = response.data.USD_CUR;
    if(response.data.viewerLic)
      $scope.license.viewerLic = response.data.viewerLic;
    else
      $scope.license.viewerLic = false;
  
    if(response.data.serverLic)
      $scope.license.serverLic = response.data.serverLic;
    else
      $scope.license.serverLic = false;
    
    if(response.data.listOfExtLic)  
      $scope.extRedactions = response.data.listOfExtLic;
    if(!$scope.extRedactions.server )
      $scope.extRedactions.server = [];
    
    if(response.data.newServConn){
      $scope.redactionForNewConnections = response.data.newServConn;
      
    }else
      $scope.redactionForNewConnections = false;
    if(response.data.server.serverConnections)  
      $scope.serverConnections = parseInt(response.data.server.serverConnections);
    else
      $scope.serverConnections = 1;
    
    if(response.data.server.productCode)
      $scope.serverProductKey = response.data.server.productCode;
    $scope.serverRedaction = response.data.server;
    $scope.beforeLodaing = false;
    
    if($scope.redactionForNewConnections && $scope.redactionForNewConnections.inbasket){
      $scope.serverProductKey = $scope.redactionForNewConnections.compCode;
      //maxNum = 50 - $scope.redactionForNewConnections.numConnection;
      maxNum = 50;
      $scope.serverConnections = $scope.redactionForNewConnections.updateLic.connectionsNum;
      $( ".slider" )
                .slider( "option", "max", maxNum )
                .slider("pips", "refresh")
                .draggable({disabled: true});
      //$scope.serverConnections = 
    }
    $('.preloader').hide();
	$scope.ignoreSetConnections = true;
    $( ".slider" ).slider( "value", $scope.serverConnections );
	$scope.ignoreSetConnections = false;
    if($scope.serverRedaction.inbasket || ($scope.redactionForNewConnections && $scope.redactionForNewConnections.inbasket)){
      increaseLicConn($scope.redactionForNewConnections.oldLicID, $scope.serverConnections);
      $( ".slider" ).slider( "disable" );

      $('.slider').draggable({
          disabled: true
      });
    } else {
      $( ".slider" ).slider( "enable" );
        $('.slider').draggable({
            disabled: false
        });
    }
  }
  $scope.tryAddServer = false;

  $scope.checkIfInBaketLicenseUpdate = function(years, extItem){
    if(!extItem.updateLic)
      return false;
    if(extItem.updateLic.years == years)
      return true;
    return false;
  }
  
  $scope.changeLicenseUpdate = function(years, extItem){
    $('.preloader').show();
    if(extItem.updateLic && extItem.updateLic.years == years){
      $scope.delViewerLicUpdate(extItem.updateLic);
    }else if(extItem.updateLic){
      if(extItem.updateLic.cartitemId > 0){
        $http.get('/include/basket/deleteGood.php?itemBasketId='+extItem.updateLic.cartitemId).then(function successCallback(response) {
          if(response.data.success == extItem.updateLic.cartitemId){
            extItem.updateLic.inbasket = false;
            extItem.updateLic.years = false;
            extItem.updateLic.cartitemId = false;
            $rootScope.$broadcast('reInitData');
            $scope.addViewerLicUpdateServer(years,extItem);
          }
        }, function errorCallback(response) {
          //console.log(response);
        });
      }else{
        $scope.addViewerLicUpdateServer(years,extItem);
      }
    }else{
      $scope.addViewerLicUpdateServer(years,extItem);
    }
    
  }
  
  $scope.delViewerLicUpdate = function(good){
    $http.get('/include/basket/deleteGood.php?itemBasketId='+good.cartitemId).then(function successCallback(response) {
      if(response.data.success == good.cartitemId){
        good.inbasket = false;
        good.years = false;
        good.cartitemId = false;
        $rootScope.$broadcast('reInitData');
        $('.preloader').hide();
      }

    }, function errorCallback(response) {
      $('.preloader').hide();
      //console.log(response);
    }
    );
  }
  
  $scope.addViewerLicUpdateServer = function(years, extItem){
    $http.get('/include/basket/addLicenseUpdate.php?productId='+$scope.license.serverLic.productID+"&years="+years + "&licenseId="+extItem.oldLicID).then(function successCallback(response) {
      if(response.data.itemBasketId){
        if(!extItem.updateLic)
          extItem.updateLic = [];
        extItem.updateLic.inbasket = true;
        extItem.updateLic.years = years;
        extItem.updateLic.price = response.data.price;
        extItem.updateLic.cartitemId = response.data.itemBasketId;
        $rootScope.$broadcast('reInitData');
        $('.preloader').hide();
      }else{
        console.log(response.data);
      }
    }, function errorCallback(response) {
      $('.preloader').hide();
      //console.log(response);
    });
  }
  
  function increaseLicConn(LicId, Conn){
    for(keyExtServ in $scope.extRedactions.server){
      if($scope.extRedactions.server[keyExtServ].oldLicID == LicId){
        $scope.extRedactions.server[keyExtServ].numConnection = parseInt($scope.extRedactions.server[keyExtServ].numConnection) + parseInt(Conn);
      }
    }
  }
  
  function deacreaseConn(LicId, Conn){
    for(keyExtServ in $scope.extRedactions.server){
      if($scope.extRedactions.server[keyExtServ].oldLicID == LicId){
        $scope.extRedactions.server[keyExtServ].numConnection = parseInt($scope.extRedactions.server[keyExtServ].numConnection) - parseInt(Conn);
      }
    }
  }

  function addServerToBasket(){
    $scope.tryAddServer = true;
    if(!$scope.serverProductKey){
      return false;
    }
    if($scope.existCompCodeArr){
      if($scope.existCompCodeArr.existCode){
        if(!$scope.existCompCodeArr.currentUser){
          Popup('another_user_compcode');
        }else{
          
          if($scope.redactionForNewConnections.haveUpdates){
            Popup('already_have_updates');
            return false;
          }
          if($scope.redactionForNewConnections && $scope.redactionForNewConnections.inbasket){

            return false;
          }else{
            $http.get('/include/basket/addConnectionsToServer.php?productId='+$scope.license.serverLic.productID+'&licenseId='+$scope.redactionForNewConnections.oldLicID+'&conn='+$scope.serverConnections+'&siteID='+$scope.site).then(function successCallback(response) {

              if(response.data.itemBasketId){
                $scope.redactionForNewConnections.inbasket = true;
                if(!$scope.redactionForNewConnections.updateLic)
                  $scope.redactionForNewConnections.updateLic = [];
                $scope.redactionForNewConnections.updateLic.cartitemId = response.data.itemBasketId;
                $scope.redactionForNewConnections.updateLic.inbasket = true;
                $scope.redactionForNewConnections.updateLic.price = $scope.parsePrice(response.data.price);
                $scope.redactionForNewConnections.updateLic.priceEN = $scope.parsePrice(response.data.price_en);
                increaseLicConn($scope.redactionForNewConnections.oldLicID,$scope.serverConnections);
                $rootScope.$broadcast('reInitData');
                $( ".slider" ).slider( "disable" );
                $('.slider').draggable({
                    disabled: true
                });
              }
              
            });
          }
        }
        return false;
      }
    }
    if(!$scope.serverRedaction.inbasket && $scope.serverRedaction.offers[$scope.serverConnections]){
       $http.get('/include/basket/addGood.php?productID='+$scope.serverRedaction.offers[$scope.serverConnections].productID+'&uniqueCode='+$scope.serverRedaction.basketId+'&productKey='+$scope.serverProductKey).then(function successCallback(response) {
         console.log(response);
        if(response.data.itemBasketId){
          $scope.serverRedaction.inbasket = true;
          $scope.serverRedaction.cartitemId = response.data.itemBasketId;
          $scope.serverRedaction.serverConnections = $scope.serverConnections;
          $rootScope.$broadcast('reInitData');
          $( ".slider" ).slider( "disable" );
          $('.slider').draggable({
              disabled: true
          });
        }
      }, function errorCallback(response) {
        //console.log(response);
      });
    }
  }
  
  function delServerFromBasket(good){
    if($scope.serverRedaction.inbasket){
      $http.get('/include/basket/deleteGood.php?itemBasketId='+good.cartitemId).then(function successCallback(response) {
        if(response.data.success == $scope.serverRedaction.cartitemId){
          good.inbasket = false;
          good.cartitemId = false;
          $scope.serverRedaction.license.inbasket = false;
          $scope.serverRedaction.license.years = false;
          $scope.serverRedaction.license.cartitemId = false;
          $rootScope.$broadcast('reInitData');
          $( ".slider" ).slider( "enable" );
          /*$('.slider').draggable({
              disabled: false
          });*/
          $scope.delServerLic(good);
        }
      });
    }
    if($scope.redactionForNewConnections && $scope.redactionForNewConnections.inbasket){
      $http.get('/include/basket/deleteGood.php?itemBasketId='+$scope.redactionForNewConnections.updateLic.cartitemId).then(function successCallback(response) {
        if(response.data.success == $scope.redactionForNewConnections.updateLic.cartitemId){
          $scope.redactionForNewConnections.inbasket = false;
          $scope.redactionForNewConnections.updateLic.cartitemId = false;
          $scope.redactionForNewConnections.updateLic.inbasket = false;
          console.log($scope.existCompCodeArr);
          $scope.checkIfExistCompCode($scope.serverProductKey);
          deacreaseConn($scope.redactionForNewConnections.oldLicID, $scope.serverConnections);
          $rootScope.$broadcast('reInitData');
          $( ".slider" ).slider( "enable" );
          /*$('.slider').draggable({
              disabled: false
          });*/
          console.log("TO DO - продление что делать?");
          
        }
      });
    }
      
  }
  
  
  $scope.ifServerLicSolid = function(years, good){
    if(!good.inbasket)
      return false;
    if(good.license.inbasket && good.license.years == years)
      return true;
    return false;
  }
  
  $scope.ifServerLicActive = function(years, good){
    if(!good.inbasket)
      return false;
    if(good.license.years && good.license.years != years)
      return false;
    return true;
  }
  
  $scope.addServerLic = function(years, good){
    if(!$scope.ifServerLicActive(years, good)){
      return;
    }
    $http.get('/include/basket/addLicense.php?productId='+$scope.license.serverLic.productID+"&years="+years+"&basketItemId="+good.cartitemId).then(function successCallback(response) {
      if(response.data.itemBasketId){
        good.license.inbasket = true;
        good.license.years = years;
        good.license.cartitemId = response.data.itemBasketId;
        $rootScope.$broadcast('reInitData');
        $('.preloader').hide();
      }
    }, function errorCallback(response) {
      $('.preloader').hide();
      //console.log(response);
    });
  }
  
  $scope.delServerLic = function(good){
    if($scope.license.serverLic.cartitemId < 0)
      return;
    
    $http.get('/include/basket/deleteGood.php?itemBasketId='+good.license.cartitemId).then(function successCallback(response) {
      if(response.data.success == good.license.cartitemId){
        good.license.inbasket = false;
        good.license.years = false;
        good.license.cartitemId = false;
        $rootScope.$broadcast('reInitData');
        $('.preloader').hide();
      }

    }, function errorCallback(response) {
      $('.preloader').hide();
      //console.log(response);
    });
  }
  
  $scope.calcServerFullPrices = function(){
    
    siteId = $scope.site;
    if($scope.beforeLodaing)
      return 0;
    $scope.fullPrice = 0;
    
    $scope.yearLicPrice["1"] = parseInt($scope.serverRedaction.offers[$scope.serverConnections].price.replace(/ /g, ''))*0.3;
    $scope.yearLicPrice["2"] = parseInt($scope.serverRedaction.offers[$scope.serverConnections].price.replace(/ /g, ''))*0.25;
    $scope.yearLicPrice["3"] = parseInt($scope.serverRedaction.offers[$scope.serverConnections].price.replace(/ /g, ''))*0.2;
    
    if($scope.serverRedaction.inbasket)
      $scope.fullPrice += parseInt($scope.serverRedaction.offers[$scope.serverConnections].price.replace(/ /g, ''));
    
    if($scope.serverRedaction.license.inbasket){
      $scope.fullPrice += parseInt($scope.yearLicPrice[$scope.serverRedaction.license.years])*parseInt($scope.serverRedaction.license.years);
    }
    
    if($scope.redactionForNewConnections && $scope.redactionForNewConnections.inbasket){
      if($scope.redactionForNewConnections.updateLic.cartitemId && $scope.redactionForNewConnections.updateLic.inbasket){
        console.log($scope.redactionForNewConnections);
        console.log(siteId);
        if(siteId == 's2')
          $scope.fullPrice += parseInt($scope.redactionForNewConnections.updateLic.price);
        else  
         $scope.fullPrice += parseInt($scope.redactionForNewConnections.updateLic.price.replace(/ /g, ''));
      }
    }
    
    return $scope.fullPrice;
    
  }
  
  $scope.parsePrice = function($price, currency = false, state = false){
    siteId = false;
    if(!currency)
      siteId = $scope.site;
    else if(currency == "RUB")
      siteId = 's1';
    else if(currency == "USD")
      siteId = 's2';
    if(siteId == 's1')
      return $price.toString().replace(/(\d)(?=(\d{3})+(?!\d))/g, '$1 ');
    else
      if(!state)
        return parseInt($price);
      else
        return $price.toFixed(1);
  }

  $scope.calcBuyServerPrice = function($servobj){

    if($servobj.currency == "RUB")
      $priceOneConn = $servobj.price;
    else
      $priceOneConn = $servobj.price_en;
    return parseInt($priceOneConn)*parseInt($servobj.numConnection)
  }
  
  /*$scope.parsePrice = function($price){
    if($scope.site == 's1')
      return $price.toString().replace(/(\d)(?=(\d{3})+(?!\d))/g, '$1 ');
    else
      return parseInt($price);
  }*/
  
  $scope.setServerConnections = function(serverConnections){
	if($scope.ignoreSetConnections)
      return false;
    if($scope.serverRedaction.inbasket){
      $( ".slider" ).slider( "value", $scope.serverRedaction.serverConnections );
    }else{
      $scope.serverConnections = serverConnections;

    }
	return true;
  }
  
  //console.log("Test controller");

}]);

inobitecApp.controller('cartController', ['$window','$scope', '$http', '$rootScope', '$window', function cartController($window, $scope, $http, $rootScope, $window) {
    $scope.liteRedactions = [];
    $scope.proRedactions = [];
    $scope.serverRedaction = false;
    $scope.extRedactions = [];
    $scope.newServConn = [];
    $scope.site = "s1";
    $scope.license = {
        "viewerLic": false,
        "serverLic": false,
    };
    
    $scope.beforeFirstLoading = true;
    
    $scope.calculateDicomViewerPrice = calculateDicomViewerPrice;
    $scope.calculateFullPrice = calculateFullPrice;
    $scope.parsePrice = parsePrice;
    $scope.redirect = redirect;
    $scope.initCart = initCart;
    $scope.clearBasket = clearBasket;
    
    $scope.$on('reInitDataMain', function(event, data) {
      console.log("REINITDATAMAIN");
      $scope.initCart();
    });
    
    function redirect(url){
      $window.location.href = url;
    }
    
    function clearBasket(){
      Popup('basketAlert');
      return false;
      $http.get('/include/basket/clearBasket.php').then(function successCallback(response) {
        if(response.data.success == 1){
          $rootScope.$broadcast('reInitData');
          $scope.initCart();
        }          
      }, function errorCallback(response) {
        //console.log(response);
      });
    }
    
    $scope.countBoughtModules = function(proItem){
      var $n = 0;
      for(keyModule in proItem.modules){
        if(proItem.modules[keyModule].inbasket){
          $n++;
        }
      }
      return $n;
    }
    
    $scope.defaultDelGood = function(good){
      
      if(!good.inbasket)
        return false;
      $rootScope.$broadcast('sendItemID', good.cartitemId);
      Popup('basketAlertTwo');
      return false;
      $http.get('/include/basket/deleteGood.php?itemBasketId='+good.cartitemId).then(function successCallback(response) {
        if(response.data.success == good.cartitemId){
          good.inbasket = false;
          if(good.modules){
            for(keyModule in good.modules){
              good.modules[keyModule].inbasket = false;
            }
          }
          $rootScope.$broadcast('reInitData');
          $scope.initCart();
        }
          
      }, function errorCallback(response) {
        //console.log(response);
      });
    }
    
    $scope.checkIfLiteRedactionInBAsket = function(){
      for(keyLite in $scope.liteRedactions){
        if($scope.liteRedactions[keyLite].inbasket){
          return true;
        }
      }
      return false;
    }
    
    $scope.checkIfViewerInBasket = function(){
      for(keyLite in $scope.liteRedactions){
        //console.log($scope.liteRedactions[keyLite].inbasket);
        if($scope.liteRedactions[keyLite].inbasket){
          return true;
        }
      }
      for(keyPro in $scope.proRedactions){
        //console.log($scope.proRedactions[keyPro].inbasket);
        if($scope.proRedactions[keyPro].inbasket){
          return true;
        }
      }
      return false;
    }
    
    $scope.licenseUpdateFilter = function(item){
      if(item.updateLic && item.updateLic.inbasket)
        return true;
      return false
    }
    
    $scope.licenseUpdateViewerCount = function(){
      var $count = 0;
      if($scope.extRedactions){
        for(keyLiteUpdate in $scope.extRedactions.lite){
          if($scope.extRedactions.lite[keyLiteUpdate].updateLic && $scope.extRedactions.lite[keyLiteUpdate].updateLic.inbasket && $scope.extRedactions.lite[keyLiteUpdate].updateLic.cartitemId > 0)
            $count++;
        }
        for(keyProUpdate in $scope.extRedactions.pro){
          if($scope.extRedactions.pro[keyProUpdate].updateLic && $scope.extRedactions.pro[keyProUpdate].updateLic.inbasket && $scope.extRedactions.pro[keyProUpdate].updateLic.cartitemId > 0)
            $count++;
        }
        for(keyProUpdate in $scope.extRedactions.server){
          if($scope.extRedactions.server[keyProUpdate].updateLic && $scope.extRedactions.server[keyProUpdate].updateLic.inbasket && $scope.extRedactions.server[keyProUpdate].updateLic.cartitemId > 0)
            $count++;
        }
      }
      return $count;
    }

    $scope.itemFilter = function (item){
      return item.inbasket;
    };

    $scope.delLiteFromBasket = function($event, good){
      $scope.defaultDelGood(good);
    };

    $scope.delProFromBasket = function($event, good){
      //if($scope.canDeletePro(good)){
        $scope.defaultDelGood(good);
      //}else
        //return false;
    };
    
    $scope.canDeletePro = function(pro){
      for(keyModule in pro.modules){
        if(pro.modules[keyModule].inbasket)
          return false;
      }
      return true;
    }

    $scope.delModuleFromBasket = function($event, good){
      $scope.defaultDelGood(good);
    };

    $scope.delServerFromBasket = function($event){
      $scope.defaultDelGood($scope.serverRedaction);
      return false;
      /*if($scope.serverRedaction.inbasket){
        $http.get('/include/basket/deleteGood.php?itemBasketId='+$scope.serverRedaction.cartitemId).then(function successCallback(response) {
          if(response.data.success == $scope.serverRedaction.cartitemId){
            $scope.serverRedaction = false;
            $rootScope.$broadcast('reInitData');
            if($scope.license.serverLic.inbasket){
              $scope.defaultDelGood($scope.license.serverLic);
            }
            
          }
        });
      }*/
    };
    
    
    $scope.strPriceToNumber = function(strPrice){
      if(strPrice)
        return strPrice.replace(/ /g, '');
      return false;
    }
    
    function calculateDicomViewerPrice(){
      var price = 0;
      for(keyLite in $scope.liteRedactions){
        if($scope.liteRedactions[keyLite].inbasket){
          var processed = $scope.liteRedactions[keyLite].price.replace(/ /g, '');
          price += parseInt(processed) * $scope.liteRedactions[keyLite].quantity;
          if($scope.liteRedactions[keyLite].license && $scope.liteRedactions[keyLite].license.inbasket){
            var processed = $scope.liteRedactions[keyLite].license.price.replace(/ /g, '');
            price += parseInt(processed) * $scope.liteRedactions[keyLite].quantity;
          }
        }
        
      }  

      for(keyPro in $scope.proRedactions){
        if($scope.proRedactions[keyPro].inbasket){
          var processed = $scope.proRedactions[keyPro].price.replace(/ /g, '');
          price += parseInt(processed) * $scope.proRedactions[keyPro].quantity;
          for(keyModule in $scope.proRedactions[keyPro].modules){
            if($scope.proRedactions[keyPro].modules[keyModule].inbasket){
              var processedModule = $scope.proRedactions[keyPro].modules[keyModule].price.replace(/ /g, '');
              price += parseInt(processedModule) * $scope.proRedactions[keyPro].modules[keyModule].quantity ;
            }
          }
          if($scope.proRedactions[keyPro].license.inbasket){
            var processed = $scope.proRedactions[keyPro].license.price.replace(/ /g, '');
            price += parseInt(processed) * $scope.proRedactions[keyPro].quantity;
          }
        }
      }
      
      if($scope.extRedactions && $scope.extRedactions.lite){
        for(keyUpdateLite in $scope.extRedactions.lite){
          if($scope.extRedactions.lite[keyUpdateLite].updateLic && $scope.extRedactions.lite[keyUpdateLite].updateLic.inbasket){
            var processed = $scope.extRedactions.lite[keyUpdateLite].updateLic.price.replace(/ /g, '');
            price += parseInt(processed);
          }

        }
      }
      
      if($scope.extRedactions && $scope.extRedactions.pro){
        for(keyUpdatePro in $scope.extRedactions.pro){
          if($scope.extRedactions.pro[keyUpdatePro].updateLic && $scope.extRedactions.pro[keyUpdatePro].updateLic.inbasket){
            var processed = $scope.extRedactions.pro[keyUpdatePro].updateLic.price.replace(/ /g, '');
            price += parseInt(processed);
          }

        }
      }
      
      return price;
    }
    
    function calculateFullPrice(){
      var fullPrice = $scope.calculateDicomViewerPrice();
      if($scope.serverRedaction){
        fullPrice += parseInt($scope.serverRedaction.offers[$scope.serverRedaction.serverConnections].price.replace(/ /g, ''));
        if($scope.serverRedaction.license.inbasket){
          fullPrice += parseInt($scope.serverRedaction.license.price.replace(/ /g, ''));
        }
      }
      if($scope.extRedactions.server){
        for(keyUpdateServer in $scope.extRedactions.server){
          if($scope.extRedactions.server[keyUpdateServer].updateLic && $scope.extRedactions.server[keyUpdateServer].updateLic.inbasket){
            var processed = $scope.extRedactions.server[keyUpdateServer].updateLic.price.replace(/ /g, '');
            fullPrice += parseInt(processed);
          }

        }
      }
      if($scope.newServConn && $scope.newServConn.inbasket){
        if(Number.isInteger($scope.newServConn.updateLic.price))
          fullPrice += parseInt($scope.newServConn.updateLic.price);
        else
          fullPrice += parseInt($scope.newServConn.updateLic.price.replace(/ /g, ''));
      }
      
      return parsePrice(fullPrice);
    }

    function parsePrice($price){
      if($scope.site == 's1')
        return $price.toString().replace(/(\d)(?=(\d{3})+(?!\d))/g, '$1 ');
      else
        return parseInt($price);
    }
    
    function initCart(){
      //console.log('/include/basket/getBasketInfo.php?siteID='+$scope.site);
      $http.get('/include/basket/getBasketInfo.php?siteID='+$scope.site).then(cartInfo);
    }
    
    function cartInfo(response){
      if(response.data.viewerLic)
        $scope.license.viewerLic = response.data.viewerLic;
      else
        $scope.license.viewerLic = false;
      
      if(response.data.serverLic)
        $scope.license.serverLic = response.data.serverLic;
      else
        $scope.license.serverLic = false;
      
      if(response.data.listOfExtLic)  
        $scope.extRedactions = response.data.listOfExtLic;
      else
        $scope.extRedactions = [];
      
      if(response.data.newServConn)
        $scope.newServConn = response.data.newServConn;
      else
        $scope.newServConn = [];
      
      $scope.liteRedactions = response.data.lite;
      $scope.proRedactions = response.data.pro;
      $scope.serverRedaction = response.data.server;
      $scope.beforeFirstLoading = false;
      $('.preloader').hide();
    }
    
    
    $scope.goToPage = function(href){
      $window.location.href = href;
    }
    
    $scope.declOfNum = function(number, titles) {  
        cases = [2, 0, 1, 1, 1, 2];  
        return titles[ (number%100>4 && number%100<20)? 2 : cases[(number%10<5)?number%10:5] ];  
    }

    

}]);

inobitecApp.controller('basketHeaderController', ['$window','$scope', '$http', '$rootScope', function basketHeaderController($window, $scope, $http, $rootScope){
    $scope.liteRedactions = [];
    $scope.proRedactions = [];
    $scope.extRedactions = [];
    $scope.newServConn = [];
    $scope.serverRedaction = false;
    $scope.site = "s1";
    $scope.license = {
        "viewerLic": false,
        "serverLic": false,
    };
    
    $scope.initCart = function(){
      $http.get('/include/basket/getBasketInfo.php?siteID='+$scope.site).then(cartInfo);
    }
    
    $scope.redirect = function(url){
      $window.location.href = url;
    }
    
    function cartInfo(response){
      if(response.data.viewerLic)
        $scope.license.viewerLic = response.data.viewerLic;
      else
        $scope.license.viewerLic = false;
      if(response.data.serverLic)
        $scope.license.serverLic = response.data.serverLic;
      else
        $scope.license.serverLic = false
      if(response.data.listOfExtLic)  
        $scope.extRedactions = response.data.listOfExtLic;
      else
        $scope.extRedactions = [];
      
      if(response.data.newServConn)
        $scope.newServConn = response.data.newServConn;
      else
        $scope.newServConn = [];
      
      $scope.liteRedactions = response.data.lite;
      $scope.proRedactions = response.data.pro;
      $scope.serverRedaction = response.data.server;
    }
    
    $scope.licenseUpdateFilter = function(item){
      if(item.updateLic && item.updateLic.inbasket)
        return true;
      return false
    }
    
    $scope.licenseUpdateViewerCount = function(){
      var $count = 0;
      if($scope.extRedactions && $scope.extRedactions.lite){
        for(keyLiteUpdate in $scope.extRedactions.lite){
          if($scope.extRedactions.lite[keyLiteUpdate].updateLic && $scope.extRedactions.lite[keyLiteUpdate].updateLic.inbasket && $scope.extRedactions.lite[keyLiteUpdate].updateLic.cartitemId > 0)
            $count++;
        }
      }
      if($scope.extRedactions && $scope.extRedactions.pro){
        for(keyProUpdate in $scope.extRedactions.pro){
          if($scope.extRedactions.pro[keyProUpdate].updateLic && $scope.extRedactions.pro[keyProUpdate].updateLic.inbasket && $scope.extRedactions.pro[keyProUpdate].updateLic.cartitemId > 0)
            $count++;
        }
      }
      
      if($scope.extRedactions && $scope.extRedactions.server){
        for(keySeverUpdate in $scope.extRedactions.server){
          if($scope.extRedactions.server[keySeverUpdate].updateLic && $scope.extRedactions.server[keySeverUpdate].updateLic.inbasket && $scope.extRedactions.server[keySeverUpdate].updateLic.cartitemId > 0)
            $count++;
        }
      }
      return $count;
    }
    
    $scope.itemFilter = function (item){
      return item.inbasket;
    };
    
    $scope.$on('reInitData', function(event, data) {
      $scope.initCart();
    });
    
    
    $scope.checkIfViewerInBasket = function(){
      for(keyLite in $scope.liteRedactions){
        if($scope.liteRedactions[keyLite].inbasket){
          return true;
        }
      }
      for(keyPro in $scope.proRedactions){
        if($scope.proRedactions[keyPro].inbasket){
          return true;
        }
      }
      return false;
    }
    
    $scope.calculateProductsNum = function(){
      var $numViewer = 0;
      for(keyLite in $scope.liteRedactions){
        if($scope.liteRedactions[keyLite].inbasket){
          $numViewer+= $scope.liteRedactions[keyLite].quantity;
        }
      }  
      for(keyPro in $scope.proRedactions){
        if($scope.proRedactions[keyPro].inbasket){
          $numViewer += $scope.proRedactions[keyPro].quantity;
          /*for(keyModule in $scope.proRedactions[keyPro].modules){
            if($scope.proRedactions[keyPro].modules[keyModule].inbasket){
              $numViewer++;
            }
          }*/
        }
      }
      var $num = $numViewer;
      if($scope.serverRedaction)
        $num++;
      /*if($scope.license.viewerLic.inbasket && $numViewer > 0)
        $num++;
      if($scope.license.serverLic.inbasket && $scope.serverRedaction)
        $num++;*/
      $num += $scope.licenseUpdateViewerCount();
      if($scope.newServConn && $scope.newServConn.inbasket){
         $num++;
      }
      
      $('.new-mobile-menu_icons-counter').html($num);
      return $num;
      
    }
    
    $scope.calculateDicomViewerPrice = function(){
      var price = 0;
      for(keyLite in $scope.liteRedactions){
        if($scope.liteRedactions[keyLite].inbasket){
          var processed = $scope.liteRedactions[keyLite].price.replace(/ /g, '');
          price += parseInt(processed) * $scope.liteRedactions[keyLite].quantity;
          if($scope.liteRedactions[keyLite].license && $scope.liteRedactions[keyLite].license.inbasket){
            var processed = $scope.liteRedactions[keyLite].license.price.replace(/ /g, '');
            price += parseInt(processed) * $scope.liteRedactions[keyLite].quantity;
          }
        }
        
      }  

      for(keyPro in $scope.proRedactions){
        if($scope.proRedactions[keyPro].inbasket){
          var processed = $scope.proRedactions[keyPro].price.replace(/ /g, '');
          price += parseInt(processed) * $scope.proRedactions[keyPro].quantity;
          for(keyModule in $scope.proRedactions[keyPro].modules){
            if($scope.proRedactions[keyPro].modules[keyModule].inbasket){
              var processedModule = $scope.proRedactions[keyPro].modules[keyModule].price.replace(/ /g, '');
              price += parseInt(processedModule) * $scope.proRedactions[keyPro].modules[keyModule].quantity ;
            }
          }
          if($scope.proRedactions[keyPro].license.inbasket){
            var processed = $scope.proRedactions[keyPro].license.price.replace(/ /g, '');
            price += parseInt(processed) * $scope.proRedactions[keyPro].quantity;
          }
        }
      }
      if($scope.extRedactions && $scope.extRedactions.lite){
        for(keyUpdateLite in $scope.extRedactions.lite){
          if($scope.extRedactions.lite[keyUpdateLite].updateLic && $scope.extRedactions.lite[keyUpdateLite].updateLic.inbasket){
            var processed = $scope.extRedactions.lite[keyUpdateLite].updateLic.price.replace(/ /g, '');
            price += parseInt(processed);
          }

        }
      }
      
      if($scope.extRedactions && $scope.extRedactions.pro){
        for(keyUpdatePro in $scope.extRedactions.pro){
          if($scope.extRedactions.pro[keyUpdatePro].updateLic && $scope.extRedactions.pro[keyUpdatePro].updateLic.inbasket){
            var processed = $scope.extRedactions.pro[keyUpdatePro].updateLic.price.replace(/ /g, '');
            price += parseInt(processed);
          }

        }
      }
      
      if($scope.extRedactions && $scope.extRedactions.server){
        for(keyUpdateServer in $scope.extRedactions.server){
          if($scope.extRedactions.server[keyUpdateServer].updateLic && $scope.extRedactions.server[keyUpdateServer].updateLic.inbasket){
            var processed = $scope.extRedactions.server[keyUpdateServer].updateLic.price.replace(/ /g, '');
            price += parseInt(processed);
          }

        }
      }
      
      return price;
    }
    
    $scope.calculateFullPrice = function(){
      var fullPrice = $scope.calculateDicomViewerPrice();
      if($scope.serverRedaction){
        fullPrice += parseInt($scope.serverRedaction.offers[$scope.serverRedaction.serverConnections].price.replace(/ /g, ''));
        if($scope.serverRedaction.license.inbasket){
          fullPrice += parseInt($scope.serverRedaction.license.price.replace(/ /g, ''));
        }
      }
      if($scope.newServConn && $scope.newServConn.inbasket){
        if(Number.isInteger($scope.newServConn.updateLic.price))
          fullPrice += parseInt($scope.newServConn.updateLic.price);
        else
          fullPrice += parseInt($scope.newServConn.updateLic.price.replace(/ /g, ''));
      }
      
      return $scope.parsePrice(fullPrice);
    }

    $scope.parsePrice = function($price){
      if($scope.site == 's1')
        return $price.toString().replace(/(\d)(?=(\d{3})+(?!\d))/g, '$1 ');
      else
        return parseInt($price);
    }
    
    $scope.defaultDelGood = function(good){
      if(!good.inbasket)
        return false;
      $rootScope.$broadcast('sendItemID', good.cartitemId);
      Popup('basketAlertTwo');
      return false;
      
      $http.get('/include/basket/deleteGood.php?itemBasketId='+good.cartitemId).then(function successCallback(response) {
        if(response.data.success == good.cartitemId){
          console.log("reinitData");
          $rootScope.$broadcast('reInitData');
          $rootScope.$broadcast('reInitDataMain');
        }
          
      }, function errorCallback(response) {
        //console.log(response);
      });
    }
    
    $scope.delLiteFromBasket = function($event, good){
      $scope.defaultDelGood(good);
    };

    $scope.delProFromBasket = function($event, good){
      //if($scope.canDeletePro(good)){
        $scope.defaultDelGood(good);
      //}else
        //return false;
    };
    
    $scope.canDeletePro = function(pro){
      for(keyModule in pro.modules){
        if(pro.modules[keyModule].inbasket)
          return false;
      }
      return true;
    }

    $scope.delModuleFromBasket = function($event, good){
      $scope.defaultDelGood(good);
    };

    $scope.delServerFromBasket = function($event){
      $scope.defaultDelGood($scope.serverRedaction);
      return false;
      /*if($scope.serverRedaction.inbasket){
        $http.get('/include/basket/deleteGood.php?itemBasketId='+$scope.serverRedaction.cartitemId).then(function successCallback(response) {
          if(response.data.success == $scope.serverRedaction.cartitemId){
            console.log("REINITDATA");
            //$scope.serverRedaction = false;
            $rootScope.$broadcast('reInitData');
            //$scope.initCart();
            $rootScope.$broadcast('reInitDataMain');
          }
        });
      }*/
    };
    
}]);

inobitecApp.directive('myPhonestopinput', function() {
        function link(scope, elem, attrs, ngModel) {
            ngModel.$parsers.push(function(viewValue) {
              var element = $('[ng-model="userPhone"]');
              var regex = RegExp(element.attr('pattern'));
              // if view values matches regexp, update model value
              if (regex.test(viewValue)) {
                return viewValue;
                
              }
              if(ngModel.$modelValue)
                var transformedValue = ngModel.$modelValue;
              else
                var transformedValue = viewValue.substring(0, viewValue.length - 1);
              if(!regex.test(transformedValue))
                transformedValue = "+";
              ngModel.$setViewValue(transformedValue);
              ngModel.$render();
              return transformedValue;
              // keep the model value as it is
              
            });
        }

        return {
            restrict: 'A',
            require: 'ngModel',
            link: link
        };      
    });

inobitecApp.directive('myValidatorpass', function () {
    function link(scope, elem, attrs, ngModel) {
        var regex = RegExp('^\[a-zA-Z0-9]*$');
       /** var $mobileFlag = true;
          elem.on('keypress', function(e){
            $mobileFlag = false;
            var char = String.fromCharCode(e.which||e.charCode||e.keyCode), matches = [];
            
            if(!regex.test(char))
              return false;
          });*/
          
          ngModel.$parsers.push(function(viewValue) {
            // if view values matches regexp, update model value
            if (regex.test(viewValue)) {
              return viewValue;

            }
            if(ngModel.$modelValue)
              var transformedValue = ngModel.$modelValue;
            else
              var transformedValue = viewValue.substring(0, viewValue.length - 1);
            if(regex.test(transformedValue)){

              ngModel.$setViewValue(transformedValue);
              ngModel.$render();
              return transformedValue;
            }
            return viewValue;
            
          });
    }

    return {
        restrict: 'A',
        require: 'ngModel',
        link: link
    };
  

});

inobitecApp.controller('orderController', ['$scope', '$http', '$rootScope','$compile', function orderController($scope, $http, $rootScope, $compile){
    //console.log("orderController");
    $scope.showValid = false;
    $scope.site = "s1";
    $scope.EMAILERROR = true;
    $scope.EMAILERRORCODE = false;
    $scope.needPhoneExp = false;
    $scope.refresh = function(res){
        $("#order_form_content").html($compile(res)($scope));
    }
    
    $scope.$watch('EMAIL',function(newValue,oldValue)
    {
      if(!$scope.EMAILERROR){
        if(newValue != oldValue){
         $scope.EMAILERROR = true; 
         $scope.EMAILERRORCODE = false;
       }
      }
    });
   
	$scope.checkFormValidate = function(){
      //console.log("---------------");
      //console.log($scope.ORDER_FORM);
      var $validLength = true;
      var anotherForm = false;
      angular.forEach($scope.ORDER_FORM, function (element, name) {
          if (!name.startsWith('$')) {
            
            //console.log(element.$$element.context.name);
            if($("[name='"+element.$$element.context.name+"']").length < 1){
              anotherForm = true;
            }
            
            if($("[name='"+element.$$element.context.name+"']").attr("data-call") == "POWER_OF_ATTORNEY")
              if($("[data-call='EVIDENCE']:checked").val() != "powerOfAttorney"){
                $("[name='"+element.$$element.context.name+"']").val("");
                $("[name='"+element.$$element.context.name+"']").trigger('input');
                $("[name='"+element.$$element.context.name+"']").trigger('change');
                return;
              }else if($("[data-call='EVIDENCE']:checked").val() == "powerOfAttorney"){
                if(typeof element.$viewValue === "undefined" || element.$viewValue.length < 1){
                  console.log('test11');
                  $validLength = false;
                  
                }
                return;
              }
            
            

            if($("[name='"+element.$$element.context.name+"']").attr("data-req") == "Y"){
              if($("[name='"+element.$$element.context.name+"']").attr("data-call") == "OFERTA_AGREE"){
                if(!$("[name='"+element.$$element.context.name+"']").is(':checked')){
                  //console.log('test12');
                  $validLength = false;
                }
              }else{
                if(typeof element.$viewValue === "undefined" || element.$viewValue.length < 1){
                  //console.log('test13');
                  $validLength = false;
                }
              }
            }
          }
      });
      
      if(anotherForm)
        return;
      
      if( (!$validLength || !$scope.ORDER_FORM.$valid) ){
        
        $scope.showValid = false;
        $("#ORDER_CONFIRM_BUTTON").addClass('btn-blue-disable');
        return false;
      }
      
      if($scope.PASS){
        if($scope.PASS !=  $scope.PASS_CONF){
          $scope.showValid = false;
          $("#ORDER_CONFIRM_BUTTON").addClass('btn-blue-disable');
          return false;
        }
        
      }
      $("#ORDER_CONFIRM_BUTTON").removeClass('btn-blue-disable');
      $scope.showValid = true;
      return true;
    }
   
    $scope.sumbitOrder = function(){
      var $validLength = true;

      angular.forEach($scope.ORDER_FORM, function (element, name) {
          if (!name.startsWith('$')) {

            element.$dirty = true;
            

            if($("[name='"+element.$$element.context.name+"']").attr("data-call") == "POWER_OF_ATTORNEY")
              if($("[data-call='EVIDENCE']:checked").val() != "powerOfAttorney"){
                $("[name='"+element.$$element.context.name+"']").val("");
                $("[name='"+element.$$element.context.name+"']").trigger('input');
                $("[name='"+element.$$element.context.name+"']").trigger('change');
                return;
              }else if($("[data-call='EVIDENCE']:checked").val() == "powerOfAttorney"){
                if(typeof element.$viewValue === "undefined" || element.$viewValue.length < 1){
                  $validLength = false;
                }
                return;
              }
            
            

            if($("[name='"+element.$$element.context.name+"']").attr("data-req") == "Y"){
              if($("[name='"+element.$$element.context.name+"']").attr("data-call") == "OFERTA_AGREE"){
                if(!$("[name='"+element.$$element.context.name+"']").is(':checked')){
                  $validLength = false;
                }
              }else{
                if(typeof element.$viewValue === "undefined" || element.$viewValue.length < 1){
                  $validLength = false;
                }
              }
            }
          }
      });
      $scope.ORDER_FORM.$dirty = true;
      $scope.ValidateAttorney();
      if(!$validLength || !$scope.ORDER_FORM.$valid){
        return false;
      }
      if($scope.PASS){
        if($scope.PASS !=  $scope.PASS_CONF){
          return false;
        }
        
      }
      $url = '/include/checkEmail.php?mail='+encodeURIComponent($("[data-call='EMAIL']").val());
      $http.get($url).then(function successCallback(response) {
        if(response.data.rez == "YES"){
          submitForm('Y');
        }else{
          $scope.EMAILERROR = false;
          $scope.EMAILERRORCODE = response.data.rez;
        }
      });
      
    }
    
    $scope.ValidateAttorneyFlag = false;
    
    $scope.showAttorney = function(){
      var evidenceVal = $("[data-call='EVIDENCE']:checked").val();
      if(evidenceVal == "powerOfAttorney")
        return true;
      return false;
    }
    
    
    $scope.ValidateAttorney = function(){
      //console.log("checkValidaty");
      var evidenceVal = $("[data-call='EVIDENCE']:checked").val();
      var attorneyVal = $("[data-call='POWER_OF_ATTORNEY']").val();
      
      if(!evidenceVal){
        $scope.ValidateAttorneyFlag = false;
        return;
      }
      
      if(attorneyVal.length < 1 && evidenceVal == "powerOfAttorney"){
        $scope.ValidateAttorneyFlag = false;
      }else{
        $scope.ValidateAttorneyFlag = true;
      }
    }
    
    $scope.ValidateAttorney();
    
}]);

inobitecApp.controller('popupController', ['$scope', '$http', '$rootScope','$compile', function popupController($scope, $http, $rootScope, $compile){
    
    $scope.CurrenItemId = false;
    $scope.$on('sendItemID', function(event, data) {
      $scope.CurrenItemId = data;
    });
    
    $scope.defaultDelGoodPopup = function(){
      if(!$scope.CurrenItemId)
        return false;
      $http.get('/include/basket/deleteGood.php?itemBasketId='+$scope.CurrenItemId).then(function successCallback(response) {
        if(response.data.success == $scope.CurrenItemId){
          $scope.CurrenItemId = false;
          $rootScope.$broadcast('reInitData');
          $rootScope.$broadcast('reInitDataMain');
          $scope.closePopup('basketAlertTwo');
        }
          
      }, function errorCallback(response) {
        //console.log(response);
      });
    }
    
    $scope.clearBasketPopup = function clearBasketPopup(){
      console.log('clearBasketPopup');
      $http.get('/include/basket/clearBasket.php').then(function successCallback(response) {
        if(response.data.success == 1){
          $rootScope.$broadcast('reInitData');
          $rootScope.$broadcast('reInitDataMain');
          $scope.closePopup('basketAlert');
        }          
      }, function errorCallback(response) {
        //console.log(response);
      });
    }
    
    $scope.closePopup = function(blockID){
      var currentPopup = document.querySelector('#'+blockID);
      closePopup(currentPopup);
    }
}])

inobitecApp.controller('redirectController', ['$window', '$scope', '$http', '$rootScope','$compile', function popupController($window, $scope, $http, $rootScope, $compile){
    
    
    
    $scope.redirect = function(url){
      $window.location.href = url;
    }
}])
