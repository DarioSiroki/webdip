const app = angular.module("znamenitosti", [
  "ngRoute",
  "angularCSS",
  "loginModule",
  "homeModule",
  "apiModule",
  "menuModule",
]);

app.config(function ($routeProvider) {
  $routeProvider
    .when("/", {
      css: {
        href: "views/home/home.css",
        bustCache: true,
      },
      templateUrl: "views/home/home.html",
      controller: "homeController",
    })
    .when("/prijava", {
      css: {
        href: "views/login/login.css",
        bustCache: true,
      },
      templateUrl: "views/login/login.html",
      controller: "loginController",
    });
});
