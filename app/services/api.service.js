angular.module("apiModule", []).service("apiService", function ($http) {
  const baseURL = "/api";

  this.login = async function (email, password) {
    try {
      const response = await $http.post(baseURL + "/login", {
        email,
        password,
      });
      console.log(response);
    } catch (e) {
      console.log(e);
    }
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
});
