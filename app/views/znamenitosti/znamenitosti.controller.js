angular
  .module("znamenitostiModule", [])
  .controller("znamenitostiController", function ($scope, apiService) {
    $scope.lista = [];
    apiService.getListOfLandmarksAndOwners().then((r) => {
      $scope.lista = r.data;
      $scope.$apply();
    });
  });
