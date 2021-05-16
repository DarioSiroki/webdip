angular.module("menuModule", []).directive("znamenitostiMenu", function () {
  return {
    restrict: "E",
    scope: {},
    templateUrl: "partials/menu.html",
    controller: function ($scope, $location) {
      $scope.isActive = function (route) {
        return route == $location.$$path ? "active" : null;
      };
    },
  };
});
