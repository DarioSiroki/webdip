angular
  .module("homeModule", [])
  .controller("homeController", function ($scope, apiService) {
    $scope.stats = [];
    apiService.getStats().then((response) => {
      $scope.stats = response.data;
      $scope.$apply();
    });

    $scope.openRSS = function (gradId) {
      window.location = window.location.pathname.replace(
        "app/",
        "api/rss?grad=" + gradId
      );
    };
  });
