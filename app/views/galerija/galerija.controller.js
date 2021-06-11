angular
  .module("galerijaModule", [])
  .controller("galerijaController", function ($scope, apiService) {
    $scope.baseURL = window.location.pathname.replace("app", "api");

    const pageTourConfig = [
      {
        targetId: "filteri",
        description: "Ovdje možete filtrirati rezultate",
      },
      {
        targetId: "opcenito",
        description: `Na ovoj stranici možete vidjeti popis svih znamenitosti i materijala vezanih uz njih koji se nalaze u bazi podataka. `,
      },
      {
        targetId: "upload",
        description:
          "Ovdje možete dodati neki materijal koji je vezan uz znamenitost. Sliku, video ili audio zapis.",
      },
    ];

    $scope.pageTour = new PageTour(pageTourConfig);

    $scope.paginator = new Paginator([]);
    $scope.page = 0;
    $scope.materijali = [];

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

    apiService.dohvatiMaterijale().then((res) => {
      $scope.materijali = res.data;
      $scope.$apply();
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
      const fileType = document.getElementById(
        "fileType" + znamenitostId
      ).value;
      const formData = new FormData();
      formData.append("znamenitost_id", znamenitostId);
      formData.append("file", file);
      formData.append("fileType", fileType);
      await apiService.dodajMaterijal(formData);
      alert("Uspjeh");
    };
  });
