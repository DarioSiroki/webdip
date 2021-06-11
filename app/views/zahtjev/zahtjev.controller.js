angular
  .module("zahtjevModule", [])
  .controller(
    "zahtjevController",
    function ($scope, apiService, configService) {
      const pageTourConfig = [
        {
          targetId: "novi-zahtjev",
          description: `Registrirani korisnici mogu pregledavati/kreirati zahtjeve za dodavanje nove znamenitosti. Zahtjev se sastoji od
          odabira grada i unosa naziva, opisa i godine.`,
        },
        {
          targetId: "popis-prijedloga",
          description: `Registrirani korisnik vidi prijedloge za nove znamenitosti od neregistriranog korisnika i ima mogućnost kreirati
          novi zahtjev temeljem istog`,
        },
      ];

      $scope.pageTour = new PageTour(pageTourConfig);

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
          if (configService.isAdmin()) {
            $scope.listaZahtjeva = data;
          } else {
            $scope.listaZahtjeva = data.filter((zahtjev) =>
              gradoviModeriranjaId.includes(zahtjev.grad_id)
            );
          }
          if ($scope.listaZahtjeva.length > 0) {
            $scope.pageTour.config.push({
              targetId: "popis-zahtjeva",
              description: `Moderator vidi popis zahtjeva te potvrđuje/odbija zahtjeve korisnika za znamenitost. Posebno su
            označeni zahtjevi za dodavanje znamenitosti koji nisu obrađeni.`,
            });
          }
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
