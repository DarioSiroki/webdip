angular
  .module("validationModule", [])
  .service("validationService", function ($http) {
    this.validateEmail = function (string) {
      // matches anything@anything.anything
      const regex = /^.+@.+\..+$/;
      let isValid = false;
      let message = "Neispravan email.";

      if (regex.test(string)) {
        isValid = true;
        message = "";
      }

      return { isValid, message };
    };

    this.validatePassword = function (string) {
      // Matches anything with less than 8 characters, no numbers, no uppercase,
      // no lowercase or no special characters.
      // Regex doesn't have AND operator but does have OR operator
      // so the goal here is to test if regex is invalid.
      // If it's invalid, password is valid.
      const regex = /^(.{0,7}|[^0-9]*|[^A-Z]*|[^a-z]*|[a-zA-Z0-9]*)$/;
      let isValid = false;
      let message =
        "Lozinka mora sadržavati barem 8 znakova, jedan broj, jedno malo, jedno veliko slovo i jedan broj.";

      if (!regex.test(string)) {
        isValid = true;
        message = "";
      }

      return { isValid, message };
    };
  });
