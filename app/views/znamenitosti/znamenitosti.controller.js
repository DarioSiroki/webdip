angular
  .module("znamenitostiModule", [])
  .controller(
    "znamenitostiController",
    function ($scope, apiService, validationService, $location) {
      $scope.lista = [];
      apiService.getListOfLandmarksAndOwners().then((r) => {
        $scope.lista = r.data;
        console.log(r.data);
        $scope.$apply();
      });
    }
  );
