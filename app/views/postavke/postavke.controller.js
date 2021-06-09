angular
  .module("postavkeModule", ["apiModule"])
  .controller("postavkeController", function ($scope, apiService) {
    $scope.backups = [];
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
