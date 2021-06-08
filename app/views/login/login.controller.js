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

      const lastUserName = configService.getLastLoggedInUsername();

      if (lastUserName) {
        // slice first and last character because username will be pulled out of storage as "username"
        $scope.korime = lastUserName.slice(1, lastUserName.length - 1);
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
            $scope.korime,
            $scope.password,
            token,
            $scope.activationCode
          );
          // Save user data to cookies
          configService.setUserData({
            korisnik_id: result.data.korisnik_id,
            uloga: result.data.uloga,
          });
          // Save or clear remember me in cookies
          if ($scope.zapamtiMe) {
            configService.setLastLoggedInUsername($scope.korime);
          } else {
            configService.clearLastLoggedInUsername();
          }
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

      $scope.forgotPw = function () {
        const korime = prompt("Vaše korisničko ime:");
        apiService
          .resetPw(korime)
          .then(() => alert("uspjeh"))
          .catch((err) => alert(err.data));
      };
    }
  );
