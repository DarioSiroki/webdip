angular
  .module("loginModule", ["apiModule"])
  .controller("loginController", function ($scope, apiService) {
    $scope.login = function () {
      apiService.login($scope.email, $scope.password);
    };
  });
