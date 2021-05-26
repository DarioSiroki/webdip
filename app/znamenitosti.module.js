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
