angular
  .module("registrationModule", [])
  .controller("registrationController", function ($scope, apiService) {
    $scope.errorMsg = "";

    $scope.validatePasswordRepeat = function () {
      if ($scope.password !== $scope.passwordRepeat) {
        $scope.errorMsg = "Lozinke se ne podudaraju.";
      } else {
        $scope.errorMsg = "";
      }
    };

    $scope.register = async function () {
      if ($scope.password !== $scope.passwordRepeat) {
        return ($scope.errorMsg = "Lozinke se ne podudaraju");
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
          console.log(result);
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
  });
