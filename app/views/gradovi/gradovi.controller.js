angular
  .module("gradoviModule", [])
  .controller("gradoviController", function ($scope, apiService, $location) {
    $scope.gradovi = [];
    $scope.korisnici = [];
    $scope.displayForm = false;
    $scope.selectedGradId = null;
    $scope.allMods = [];
    $scope.akcijaDodavanja = null;

    $scope.dodajNoviGrad = function () {
      $scope.akcijaDodavanja = true;
      $scope.naziv = null;
      $scope.opis = null;
      $scope.postanski_broj = null;
      $scope.povrsina = null;
      $scope.broj_stanovnika = null;
      $scope.displayForm = true;
    };

    apiService.getCities().then((response) => {
      $scope.gradovi = response.data;
      apiService.getUsers().then((response) => {
        $scope.korisnici = response.data;
        $scope.$apply();
        apiService.getMods().then((response) => {
          $scope.allMods = response.data.map((mod) =>
            Object.assign(
              mod,
              $scope.korisnici.find((k) => k.korisnik_id == mod.korisnik_id)
            )
          );
          $scope.$apply();
        });
      });
    });

    $scope.uredi = function (gradId) {
      $scope.akcijaDodavanja = false;
      $scope.selectedGradId = gradId;
      $scope.displayForm = true;
      const grad = $scope.gradovi.find((grad) => grad.grad_id == gradId);
      $scope.naziv = grad.naziv;
      $scope.opis = grad.opis;
      $scope.postanski_broj = grad.postanski_broj;
      $scope.povrsina = grad.povrsina;
      $scope.broj_stanovnika = grad.broj_stanovnika;
    };

    $scope.spremi = function () {
      if (!$scope.akcijaDodavanja)
        apiService
          .updateGrad(
            $scope.selectedGradId,
            $scope.naziv,
            $scope.opis,
            $scope.postanski_broj,
            $scope.povrsina,
            $scope.broj_stanovnika
          )
          .then(() => {
            alert("uspjeh");
            $scope.displayForm = false;
            const grad = $scope.gradovi.find(
              (grad) => grad.grad_id == $scope.selectedGradId
            );
            grad.naziv = $scope.naziv;
            grad.opis = $scope.opis;
            grad.postanski_broj = $scope.postanski_broj;
            grad.povrsina = $scope.povrsina;
            grad.broj_stanovnika = $scope.broj_stanovnika;
            $scope.$apply();
          })
          .catch(() => alert("greska"));
      else
        apiService
          .addGrad(
            $scope.naziv,
            $scope.opis,
            $scope.postanski_broj,
            $scope.povrsina,
            $scope.broj_stanovnika
          )
          .then((response) => {
            alert("uspjeh");
            $scope.displayForm = false;
            $scope.gradovi.push({
              grad_id: response.data,
              naziv: $scope.naziv,
              opis: $scope.opis,
              postanski_broj: $scope.postanski_broj,
              povrsina: $scope.povrsina,
              broj_stanovnika: $scope.broj_stanovnika,
            });
            $scope.$apply();
          })
          .catch(() => {
            alert("greska");
          });
    };

    $scope.dodajModa = function (korisnik) {
      apiService
        .addMod($scope.selectedGradId, korisnik.korisnik_id)
        .then(() => {
          alert("uspjeh");
          $scope.allMods.push({
            korisnik_id: korisnik.korisnik_id,
            grad_id: $scope.selectedGradId,
          });
          $scope.$apply();
        })
        .catch(() => alert("greska"));
    };

    $scope.obrisiModa = function (korisnik) {
      apiService
        .deleteMod($scope.selectedGradId, korisnik.korisnik_id)
        .then(() => {
          alert("uspjeh");
          $scope.allMods = $scope.allMods.filter(
            (mod) =>
              !(
                mod.korisnik_id == korisnik.korisnik_id &&
                $scope.selectedGradId == mod.grad_id
              )
          );
          $scope.$apply();
        })
        .catch(() => alert("greska"));
    };

    $scope.isMod = function (korisnik) {
      return $scope.allMods.find(
        (m) =>
          m.korisnik_id == korisnik.korisnik_id &&
          m.grad_id == $scope.selectedGradId
      );
    };
  });
