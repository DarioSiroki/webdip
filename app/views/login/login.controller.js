angular
  .module("loginModule", ["apiModule"])
  .controller(
    "loginController",
    function ($scope, $location, apiService, configService) {
      if (configService.isLoggedIn()) {
        $location.path("/");
      }
      if ($location.$$url.indexOf("s=register") > -1) {
        $scope.requireActivationCode = true;
      }

      $scope.errorMsg = "";
      $scope.activationCode = "";

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
            token,
            $scope.activationCode
          );
          // Save user data to cookies
          configService.setUserData(result.data);
          $location.path("/");
        } catch (response) {
          if (response.status === 409) {
            $scope.errorMsg =
              "Detektirana je sumnjiva aktivnost s vaše lokacije. Pokušajte ponovno kasnije.";
          } else {
            if (response.data == "Korisnik nije aktiviran") {
              alert("Unesite kod za aktivaciju koji Vam je poslan na mail.");
              $scope.requireActivationCode = true;
            } else {
              $scope.errorMsg = response.data;
            }
          }
        } finally {
          $scope.$apply();
        }
        document.body.style.cursor = "default";
      };
    }
  );
