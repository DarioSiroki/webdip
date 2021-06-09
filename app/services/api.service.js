angular.module("apiModule", []).service("apiService", function ($http) {
  const baseURL = window.location.pathname.replace("app", "api");

  this.login = async function (username, password, token, activationCode) {
    return $http.post(baseURL + "/login", {
      username,
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

  this.resetPw = async function (user_name) {
    return $http.post(baseURL + "/reset-password", {
      user_name,
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

  this.getCities = async function () {
    return $http.get(baseURL + "/grad");
  };

  this.dodajPrijedlog = async function (
    naziv,
    opis,
    gradId,
    ime,
    prezime,
    nadimak
  ) {
    return $http.post(baseURL + "/neregistrirani_prijedlog", {
      naziv,
      opis,
      gradId,
      ime,
      prezime,
      nadimak,
    });
  };

  this.getLandmarks = async function () {
    return $http.get(baseURL + "/znamenitost/popis");
  };

  this.dodajMaterijal = async function (formData) {
    return $http.post(baseURL + "/privitak", formData, {
      transformRequest: angular.identity,
      headers: { "Content-Type": undefined, "Process-Data": false },
    });
  };

  this.dohvatiMaterijale = async function () {
    return $http.get(baseURL + "/privitak");
  };

  this.dohvatiPrijedloge = async function () {
    return $http.get(baseURL + "/neregistrirani_prijedlog");
  };

  this.dodajZahtjev = async function (naziv, opis, gradId, godina) {
    return $http.post(baseURL + "/registrirani_zahtjev", {
      naziv,
      opis,
      gradId,
      godina,
    });
  };

  this.dohvatiZahtjeve = async function () {
    return $http.get(baseURL + "/registrirani_zahtjev");
  };

  this.changeStatus = async function (registrirani_zahtjev_id, status) {
    return $http.patch(baseURL + "/registrirani_zahtjev", {
      registrirani_zahtjev_id,
      status,
    });
  };

  this.getUsers = async function () {
    return $http.get(baseURL + "/users");
  };

  this.updateGrad = async function (
    grad_id,
    naziv,
    opis,
    postanski_broj,
    povrsina,
    broj_stanovnika
  ) {
    return $http.patch(baseURL + "/grad", {
      grad_id,
      naziv,
      opis,
      postanski_broj,
      povrsina,
      broj_stanovnika,
    });
  };

  this.addGrad = async function (
    naziv,
    opis,
    postanski_broj,
    povrsina,
    broj_stanovnika
  ) {
    return $http.post(baseURL + "/grad", {
      naziv,
      opis,
      postanski_broj,
      povrsina,
      broj_stanovnika,
    });
  };

  this.getMods = async function () {
    return $http.get(baseURL + "/moderator");
  };

  this.addMod = async function (grad_id, korisnik_id) {
    return $http.post(baseURL + "/moderator", {
      grad_id,
      korisnik_id,
    });
  };

  this.deleteMod = async function (grad_id, korisnik_id) {
    return $http.delete(baseURL + "/moderator", {
      data: {
        grad_id,
        korisnik_id,
      },
    });
  };

  this.napraviBackup = async function () {
    return $http.post(baseURL + "/backup");
  };

  this.dohvatiBackupove = async function () {
    return $http.get(baseURL + "/backup");
  };

  this.vratiBackup = async function (naziv) {
    return $http.post(baseURL + "/backup/vrati", { naziv });
  };
});
