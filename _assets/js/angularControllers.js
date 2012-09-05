'use strict';

/* Controllers */

function OptionalProductsCtrl($scope, $rootScope) {

  $scope.optionalProducts = [
    
  ];

  /*
  {
      "menge": 2,
      "bezeichnung": "Fast just got faster with Nexus S.",
      "herkunft": "xxx"
    },
    {
      "menge": 1,
      "bezeichnung": "Fast just  with Galaxy S.",
      "herkunft": "xxx"
    }
  */

  $scope.add = function() {
    if( $scope.menge == undefined || $scope.bezeichnung == undefined || $scope.herkunft == undefined){
      alert("Bitte Menge, Bezeichung und Herkunft angeben.");
    }else{
      var newOptionalProduct = {
        menge: $scope.menge,
        bezeichnung: $scope.bezeichnung,
        herkunft: $scope.herkunft
      }
      
      $scope.optionalProducts.push(newOptionalProduct);
      $scope.menge = undefined;
      $scope.bezeichnung = undefined;
      $scope.herkunft = undefined;
    }
  }

  $scope.delete = function(obj) {
    $scope.optionalProducts.splice(this.$index, 1);
  }

}