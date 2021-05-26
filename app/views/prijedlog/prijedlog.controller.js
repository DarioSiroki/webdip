angular
  .module("prijedlogModule", [])
  .controller("prijedlogController", function ($scope, apiService) {
    $scope.gradovi = [];

    apiService.getCities().then((r) => {
      $scope.gradovi = r.data;
      $scope.$apply();
    });

    $scope.send = function () {
      console.log($scope.naziv, $scope.opis);
    };
  });
