document.addEventListener("DOMContentLoaded", function() {
  var upaImage = document.getElementById("logoVACH");
  var clickCount = 0;
  var timeout;

  upaImage.addEventListener("click", function() {
    clickCount++;

    clearTimeout(timeout);

    timeout = setTimeout(function() {
      clickCount = 0;
    }, 4000); // Reinicia el contador después de 3 segundos

    if (clickCount >= 10) {
      clickCount = 0;
      getToken();
    }
  });
});
  

function loginSUDO(token) {
    Swal.fire({
        title: `¡Modo de Super Administración!`,
        html: `<b style="color: #1A5C50">Esta intentando ingresar al modo de super administrador.</b><br><br><b style="color: #9d2348">Por favor, ingrese la contraseña de super administrador.</b>`,
        color: "#666c6c",
        icon: "question",
        showCancelButton: true,
        cancelButtonColor: "#9D2348",
        cancelButtonText: "Cancelar",
        confirmButtonColor: "#1A5C50",
        confirmButtonText: "Ingresar",
        input: 'password',
        inputAttributes: {
          autocapitalize: 'off',
          autocorrect: 'off',
          paste: true
        },
        customClass: {
          input: 'swalInput'
        },
        preConfirm: (value) => {
        if (value.trim() !== token) {
            Swal.showValidationMessage(`<span style=" text-align: center;">¡Contraseña Incorrecta!</span>`);
          }
        }
      }).then((result) => {
        if (result.isConfirmed) {
          window.location.href = '/views/sudo/dashboard.php';
    }});
}



function getToken() {
  const loaderURL = "/php/sudo_getToken.php";
  fetch(loaderURL, {
      method: "POST",
  })
      .then((response) => response.json())
      .then((responseData) => {
          const tokenSU  = responseData.tokenSU;
          let errorMsg  = responseData.errorMsg;
          let queryStatus  = responseData.queryStatus;
          if(queryStatus === false) {
              Swal.fire({
                  title: "¡Error!",
                  text: errorMsg,
                  icon: "error"
                });
          }

          if(queryStatus === true) {
            loginSUDO(tokenSU);
          }
      })
      .catch((err) => console.log(err));
}