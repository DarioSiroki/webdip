angular
  .module("zahtjevModule", [])
  .controller(
    "zahtjevController",
    function ($scope, apiService, configService) {
      $scope.gradovi = [];
      $scope.prijedlozi = [];
      $scope.listaZahtjeva = [];

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

      apiService.getMods().then(({ data }) => {
        const korisnik = configService.getUserData();
        const gradoviModeriranjaId = data
          .filter((mod) => mod.korisnik_id == korisnik.korisnik_id)
          .map((g) => g.grad_id);

        apiService.dohvatiZahtjeve().then(({ data }) => {
          $scope.listaZahtjeva = data.filter((zahtjev) =>
            gradoviModeriranjaId.includes(zahtjev.grad_id)
          );
          $scope.$apply();
        });
      });

      $scope.promijeniStatus = function (registrirani_zahtjev_id, status) {
        apiService
          .changeStatus(registrirani_zahtjev_id, status)
          .then(() => {
            alert("uspjeh");
            const zahtjev = $scope.listaZahtjeva.find(
              (zahtjev) =>
                zahtjev.registrirani_zahtjev_id == registrirani_zahtjev_id
            );
            zahtjev.status = status;
            $scope.$apply();
          })
          .catch(() => alert("greska"));
      };
    }
  );
