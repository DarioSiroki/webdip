angular
  .module("prijedlogModule", [])
  .controller("prijedlogController", function ($scope, apiService) {
    $scope.gradovi = [];

    apiService.getCities().then((r) => {
      $scope.gradovi = r.data;
      $scope.$apply();
    });

    $scope.send = function () {
      const gradId = document.getElementById("grad").value;
      var err = false;
      if (!$scope.naziv) {
        $scope.nazivErr = true;
        err = true;
      }
      if (!$scope.opis) {
        $scope.opisErr = true;
      }
      if (!gradId) {
        $scope.gradErr = true;
      }
      if (err) {
        return;
      }
      apiService
        .dodajPrijedlog(
          $scope.naziv,
          $scope.opis,
          gradId,
          $scope.ime,
          $scope.prezime,
          $scope.nadimak
        )
        .then(() => {
          alert("uspjeh");
          $scope.nazivErr = $scope.opisErr = $scope.gradErr = false;
        })
        .catch(() => alert("greska"));
    };
  });
