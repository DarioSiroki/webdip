angular
  .module("apiModule", [])
  .service("apiService", function ($http, $location) {
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
  });
