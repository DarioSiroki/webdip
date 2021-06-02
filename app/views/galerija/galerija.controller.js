angular
  .module("galerijaModule", [])
  .controller("galerijaController", function ($scope, apiService) {
    $scope.paginator = new Paginator([]);
    $scope.page = 0;

    function bindajGradoveZnamenitostima(gradovi, znamenitosti) {
      for (const z of znamenitosti) {
        const grad = gradovi.find((grad) => grad.grad_id == z.grad_id).naziv;
        z.grad = grad;
      }
      return znamenitosti;
    }

    apiService.getCities().then((res) => {
      const gradovi = res.data;
      apiService.getLandmarks().then((res) => {
        const znamenitosti = bindajGradoveZnamenitostima(gradovi, res.data);
        $scope.paginator.setList(znamenitosti);
        $scope.loaded = true;
        $scope.$apply();
      });
    });

    $scope.filtersChanged = function () {
      const { od, do: _do, amount } = $scope;
      if (amount) {
        $scope.paginator.setItemCount(amount);
      }
      const fn = (arr) =>
        arr.filter((znamenitost) => {
          let pass = true;
          if (od) {
            if (parseInt(znamenitost.godina) < od) pass = false;
          }
          if (_do) {
            if (parseInt(znamenitost.godina) > _do) pass = false;
          }
          return pass;
        });
      $scope.paginator.setFilter(fn);
    };

    $scope.upload = async function (znamenitostId) {
      const file = document.getElementById("file" + znamenitostId).files[0];
      if (file === undefined) return alert("Niste dodali datoteku");
      const formData = new FormData();
      formData.append("znamenitost_id", znamenitostId);
      formData.append("file", file);
      await apiService.dodajMaterijal(formData);
      alert("Uspjeh");
    };
  });
