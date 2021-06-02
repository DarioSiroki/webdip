angular.module("menuModule", []).directive("znamenitostiMenu", function () {
  return {
    restrict: "E",
    scope: {},
    templateUrl: "partials/menu.html",
    controller: function (
      $scope,
      $location,
      $route,
      configService,
      apiService
    ) {
      if (configService.isLoggedIn()) {
        $location.path("/");
      }

      $scope.isLoggedIn = configService.isLoggedIn;
      $scope.isRegisteredUser = configService.isRegisteredUser;
      $scope.isAdmin = configService.isAdmin;

      $scope.isActive = function (route) {
        return route == $location.$$path ? "active" : null;
      };

      $scope.logOut = async function () {
        try {
          await apiService.logout();
          configService.clearUserData();
          $route.reload();
        } catch (e) {
          alert(
            "Ispričavamo se, došlo je do pogreške... Pokušajte ponovno kasnije"
          );
        }
      };
    },
  };
});
