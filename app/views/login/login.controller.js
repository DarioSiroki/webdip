angular
  .module("loginModule", ["apiModule"])
  .controller(
    "loginController",
    function ($scope, $location, apiService, configService) {
      if (configService.isLoggedIn()) {
        $location.path("/");
      }

      $scope.errorMsg = "";

      $scope.login = async function () {
        document.body.style.cursor = "wait";
        try {
          const result = await apiService.login($scope.email, $scope.password);
          configService.setUserData(result.data);
          $location.path("/");
        } catch (e) {
          $scope.errorMsg = "Ne postoji korisnik sa ovim podacima.";
        } finally {
          $scope.$apply();
        }
        document.body.style.cursor = "default";
      };
    }
  );
