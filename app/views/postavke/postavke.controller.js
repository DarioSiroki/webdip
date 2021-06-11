angular
  .module("postavkeModule", ["apiModule"])
  .controller("postavkeController", function ($scope, apiService) {
    $scope.backups = [];

    const pageTourConfig = [
      {
        targetId: "vrati-backup",
        description: `Možete vratiti podatke iz sigurnosne kopije pri čemu se brišu trenutni podaci u bazi. Za svaku
      znamenitost automatski se provjerava da li postoje fizičke datoteke materijala koje su
      postavljene od strane korisnika i ako ne šalje se zahtjev na e-mail korisniku da se ponovo
      postave.`,
      },
      {
        targetId: "napravi-backup",
        description: `Možete napraviti sigurnosnu kopiju (eng. backup) svih znamenitosti i materijala iz baze u obliku
      SQL skripte. Ne radi se sigurnosna kopija datoteka`,
      },
    ];
    $scope.pageTour = new PageTour(pageTourConfig);

    $scope.napraviBackup = function () {
      apiService
        .napraviBackup()
        .then((res) => {
          alert("uspjeh");
          $scope.backups.push(res.data);
          $scope.$apply();
        })
        .catch(() => alert("greska"));
    };

    apiService.dohvatiBackupove().then((res) => {
      $scope.backups = res.data;
      $scope.$apply();
    });

    $scope.vratiBackup = function (naziv) {
      apiService
        .vratiBackup(naziv)
        .then(() => {
          alert("uspjeh");
        })
        .catch(() => {
          alert("greška");
        });
    };
  });
