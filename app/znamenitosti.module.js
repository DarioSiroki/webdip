const app = angular.module("znamenitosti", [
  "ngRoute",
  "angularCSS",
  "ngCookies",
  "loginModule",
  "homeModule",
  "registrationModule",
  "apiModule",
  "menuModule",
  "configModule",
  "validationModule",
  "znamenitostiModule",
  "prijedlogModule",
  "galerijaModule",
  "zahtjevModule",
  "gradoviModule",
  "postavkeModule",
  "indexModule",
]);

app.config(function ($routeProvider) {
  $routeProvider
    .when("/", {
      css: {
        href: "views/home/home.css",
        bustCache: true,
      },
      allowed: "all",
      templateUrl: "views/home/home.html",
      controller: "homeController",
    })
    .when("/prijava", {
      css: {
        href: "views/login/login.css",
        bustCache: true,
      },
      allowed: "all",
      templateUrl: "views/login/login.html",
      controller: "loginController",
    })
    .when("/registracija", {
      css: {
        href: "views/registration/registration.css",
        bustCache: true,
      },
      allowed: "all",
      templateUrl: "views/registration/registration.html",
      controller: "registrationController",
    })
    .when("/znamenitosti", {
      css: {
        href: "views/znamenitosti/znamenitosti.css",
        bustCache: true,
      },
      allowed: "all",
      templateUrl: "views/znamenitosti/znamenitosti.html",
      controller: "znamenitostiController",
    })
    .when("/prijedlog", {
      css: {
        href: "views/prijedlog/prijedlog.css",
        bustCache: true,
      },
      allowed: "all",
      templateUrl: "views/prijedlog/prijedlog.html",
      controller: "prijedlogController",
    })
    .when("/galerija", {
      css: {
        href: "views/galerija/galerija.css",
        bustCache: true,
      },
      allowed: "registered",
      templateUrl: "views/galerija/galerija.html",
      controller: "galerijaController",
    })
    .when("/zahtjev", {
      css: {
        href: "views/zahtjev/zahtjev.css",
        bustCache: true,
      },
      allowed: "registered",
      templateUrl: "views/zahtjev/zahtjev.html",
      controller: "zahtjevController",
    })
    .when("/gradovi", {
      css: {
        href: "views/gradovi/gradovi.css",
        bustCache: true,
      },
      allowed: "admin",
      templateUrl: "views/gradovi/gradovi.html",
      controller: "gradoviController",
    })
    .when("/postavke", {
      css: {
        href: "views/postavke/postavke.css",
        bustCache: true,
      },
      allowed: "admin",
      templateUrl: "views/postavke/postavke.html",
      controller: "postavkeController",
    });
});

app.run([
  "$rootScope",
  "configService",
  function ($rootScope, configService) {
    // Handle authenthication on route change, check if user is allowed to go there
    $rootScope.$on("$routeChangeStart", configService.routeChangeStart);
  },
]);
