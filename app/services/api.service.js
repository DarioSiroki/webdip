angular.module("apiModule", []).service("apiService", function ($http) {
  const baseURL = "/api";

  this.login = async function (email, password, token) {
    return $http.post(baseURL + "/login", {
      email,
      password,
      token,
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
});