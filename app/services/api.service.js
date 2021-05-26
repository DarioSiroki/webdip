angular.module("apiModule", []).service("apiService", function ($http) {
  const baseURL = "/api";

  this.login = async function (email, password, token, activationCode) {
    return $http.post(baseURL + "/login", {
      email,
      password,
      token,
      activationCode,
    });
  };

  this.register = async function (
    first_name,
    second_name,
    user_name,
    email,
    password
  ) {
    return $http.post(baseURL + "/register", {
      first_name,
      second_name,
      user_name,
      email,
      password,
    });
  };

  this.logout = async function () {
    return $http.post(baseURL + "/logout");
  };

  this.getStats = async function () {
    return $http.get(baseURL + "/znamenitost/statistika");
  };

  this.getListOfLandmarksAndOwners = async function () {
    return $http.get(baseURL + "/znamenitost/popis_znamenitosti_i_autora");
  };
});
