angular
  .module("indexModule", ["apiModule"])
  .controller("indexController", function ($scope, configService) {
    $scope.accessibility = configService.accessibility;

    $scope.accessibilityToggle = function () {
      $scope.accessibilityActive = !$scope.accessibilityActive;
      const accessibilityModal = document.getElementById("accessibility-modal");
      if ($scope.accessibilityActive) {
        const accessibilityImage =
          document.getElementById("accessibility-icon");
        const { offsetTop, offsetLeft } = accessibilityImage;
        accessibilityModal.style.display = "block";
        const { offsetHeight } = accessibilityModal;
        accessibilityModal.style.left = offsetLeft + "px";
        accessibilityModal.style.top = offsetTop - offsetHeight + "px";
      } else {
        accessibilityModal.style.display = "none";
      }
    };
  });
