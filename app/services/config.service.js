angular
  .module("configModule", [])
  .service("configService", function ($cookies) {
    // Constants

    this.CAPTCHA_KEY = "6LfMpNcaAAAAAA75RmarKUWkGoZCXuCf13s1cylS";

    // Authenthication

    this.routeChangeStart = function (event, next, current) {
      const access = next.$$route.allowed;

      // Allow if access is not set up
      if (access === undefined) return;

      // Allow if it's a public view
      if (typeof access === "string" && access === "all") return;
    };

    this.isLoggedIn = function () {
      return !!$cookies.get("userData");
    };

    this.isAdmin = function () {
      return $cookies.get("userData").role === "administrator";
    };

    this.isRegisteredUser = function () {
      return $cookies.get("userData").role === "registrirani_korisnik";
    };

    this.setUserData = function (userData) {
      $cookies.putObject("userData", userData);
    };

    this.getUserData = function () {
      return $cookies.getObject("userData");
    };

    this.clearUserData = function () {
      $cookies.remove("userData");
    };

    this.setLastLoggedInUsername = function (userName) {
      $cookies.putObject("lastUsername", userName);
    };

    this.getLastLoggedInUsername = function () {
      return $cookies.get("lastUsername");
    };

    this.clearLastLoggedInUsername = function () {
      $cookies.remove("lastUsername");
    };
  });
