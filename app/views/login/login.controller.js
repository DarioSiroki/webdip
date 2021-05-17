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
        const token = await grecaptcha.execute(configService.CAPTCHA_KEY, {
          action: "submit",
        });

        // Set cursor to loading so user has some kind of idea to wait
        document.body.style.cursor = "wait";
        try {
          const result = await apiService.login(
            $scope.email,
            $scope.password,
            token
          );
          // Save user data to cookies
          configService.setUserData(result.data);
          $location.path("/");
        } catch (response) {
          if (response.status === 409) {
            $scope.errorMsg =
              "Detektirana je sumnjiva aktivnost s vaše lokacije. Pokušajte ponovno kasnije.";
          } else {
            $scope.errorMsg = "Ne postoji korisnik sa ovim podacima.";
          }
        } finally {
          $scope.$apply();
        }
        document.body.style.cursor = "default";
      };
    }
  );
