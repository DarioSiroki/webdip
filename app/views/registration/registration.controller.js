angular
  .module("registrationModule", [])
  .controller(
    "registrationController",
    function ($scope, apiService, validationService, $location) {
      $scope.errorMsg = "";

      $scope.validatePasswordRepeat = function () {
        if ($scope.password !== $scope.passwordRepeat) {
          $scope.errorMsg = "Lozinke se ne podudaraju.";
        } else {
          $scope.errorMsg = "";
        }
      };

      $scope.validateEmail = function () {
        let { isValid, message } = validationService.validateEmail(
          $scope.email
        );
        $scope.emailError = isValid ? "" : message;
      };

      $scope.validatePassword = function () {
        let { isValid, message } = validationService.validatePassword(
          $scope.password
        );
        $scope.passwordError = isValid ? "" : message;
      };

      $scope.validateFirstName = function () {
        if ($scope.firstName?.length >= 3) {
          $scope.firstNameError = "";
        } else {
          $scope.firstNameError =
            "Korisničko ime mora sadržavati barem 3 znaka.";
        }
      };

      $scope.validateSecondName = function () {
        if ($scope.secondName?.length >= 3) {
          $scope.secondNameError = "";
        } else {
          $scope.secondNameError = "Prezime mora sadržavati barem 3 znaka.";
        }
      };

      $scope.validateUsername = function () {
        if ($scope.userName?.length >= 3) {
          $scope.userNameError = "";
        } else {
          $scope.userNameError = "Ime mora sadržavati barem 3 znaka.";
        }
      };

      function formIsValid() {
        $scope.validatePasswordRepeat();
        $scope.validateEmail();
        $scope.validatePassword();
        $scope.validateFirstName();
        $scope.validateSecondName();
        $scope.validateUsername();
        if (
          $scope.emailError ||
          $scope.passwordError ||
          $scope.firstNameError ||
          $scope.secondNameError ||
          $scope.userNameError
        ) {
          return false;
        }
        return true;
      }

      $scope.register = async function () {
        if (!formIsValid()) {
          return;
        }

        try {
          const result = await apiService.register(
            $scope.firstName,
            $scope.secondName,
            $scope.userName,
            $scope.email,
            $scope.password
          );

          if (result.status === 200) {
            $location.path("/prijava?s=register");
          }
          $scope.errorMsg = "";
        } catch (result) {
          if (result.status === 409) {
            $scope.errorMsg = "Korisnik s ovim korisničkim imenom već postoji.";
          } else {
            alert(
              "Ispričavamo se, došlo je do pogreške... Pokušajte ponovno kasnije"
            );
          }
        } finally {
          $scope.$apply();
        }
      };
    }
  );
