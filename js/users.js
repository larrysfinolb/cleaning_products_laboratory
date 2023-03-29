$(document).ready(function () {
  // Funciones
  function registerUser() {
    const username = $('#usernameNew').val();
    const role = $('#roleNew').val();
    const newPassword = $('#newPasswordNew').val();
    const repeatPassword = $('#repeatPasswordNew').val();
    let isValid = true;

    isValid =
      username !== '' &&
      newPassword !== '' &&
      (role === 'admin' || role === 'seller') &&
      repeatPassword !== '' &&
      newPassword === repeatPassword;

    if (isValid) {
      $.ajax({
        url: './php/users/create.php',
        type: 'POST',
        data: {
          username,
          password: newPassword,
          role,
        },
        success: function (data) {
          console.log(data);
          $('#usernameNew').val('');
          $('#newPasswordNew').val('');
          $('#repeatPasswordNew').val('');
        },
      });
    } else {
      alert('Debes de llenar todos los campos correctamente');
    }
  }

  // Implementaciones
  $('#formNewUser').submit(function (event) {
    event.preventDefault();
    registerUser();
  });
  $('#closeSesionButton').click(function () {
    location.href = './php/sesion/close.php';
  });
});
