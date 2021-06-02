angular
  .module("zahtjevModule", [])
  .controller("zahtjevController", function ($scope, apiService) {
    $scope.gradovi = [];
    $scope.prijedlozi = [];

    apiService.getCities().then((r) => {
      $scope.gradovi = r.data;
      $scope.$apply();
    });

    apiService.dohvatiPrijedloge().then((res) => {
      $scope.prijedlozi = res.data;
      $scope.$apply();
    });

    $scope.predlozak = function (id) {
      const prijedlog = $scope.prijedlozi.find(
        (prijedlog) => prijedlog.neregistrirani_zahtjev_id == id
      );
      $scope.naziv = prijedlog.naziv;
      $scope.opis = prijedlog.opis;
      document.getElementById("grad").value = prijedlog.grad_id;
    };

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
      console.log($scope.godina);
      if (!$scope.godina) {
        $scope.godinaErr = true;
      }
      if (err) {
        return;
      }
      apiService
        .dodajZahtjev($scope.naziv, $scope.opis, gradId, $scope.godina)
        .then(() => {
          alert("uspjeh");
          $scope.nazivErr =
            $scope.opisErr =
            $scope.gradErr =
            $scope.godinaErr =
              false;
        })
        .catch(() => alert("greska"));
    };
  });
