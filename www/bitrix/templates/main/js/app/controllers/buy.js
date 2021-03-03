// Define the `phonecatApp` module
var buyApp = angular.module('buyApp', []);

// Define the `PhoneListController` controller on the `phonecatApp` module
(function(){
    "use strict";
    buyApp.controller('buyController', function buyController($scope) {
    $scope.test = "CheckAngular";

    $scope.phones = [
      {
        name: 'Nexus S',
        snippet: 'Fast just got faster with Nexus S.'
      }, {
        name: 'Motorola XOOM™ with Wi-Fi',
        snippet: 'The Next, Next Generation tablet.'
      }, {
        name: 'MOTOROLA XOOM™',
        snippet: 'The Next, Next Generation tablet.'
      }
    ];
   });
});

