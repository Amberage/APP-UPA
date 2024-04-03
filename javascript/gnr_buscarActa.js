const errorMsg = document.getElementById("errorMsg");

function search() {
  if (validateFormData() === true) {
    searchPet();
  }
}

function searchPet() {
  // Datos de la mascota
  var folio = document.getElementById("folio").value.trim();
  var numIne = document.getElementById("ine").value.trim();
  const url = "/php/gnr_searchPet.php";

  let formData = new FormData();
  formData.append("userFolio", folio);
  formData.append("userINE", numIne);

  //Enviarlos via POST
  fetch(url, {
    method: "POST",
    body: formData,
  })
    .then((response) => response.json())
    .then((responseData) => {
      const printFolio = responseData.folio;
      const resultQuery = responseData.resultQuery;

      if (resultQuery == true) {
        console.log("activado true")
        printPetUser(printFolio);
        document.getElementById("folio").value = "";
        document.getElementById("ine").value = "";
      }

      if (resultQuery == false) {
        console.log("activado false")
        Swal.fire({
          title: "¡ERROR!",
          html: "No se encontro ningún acta con los datos proporcionados, revisa los datos y si el problema persiste asiste a las <a href='https://maps.app.goo.gl/Kr1hdXWRYfJMzRgD7'>instalaciones de la UPA</a> para revisar tu caso.",
          icon: "error",
        });
      }
    })
    .catch((err) => console.log(err));
}

function validateFormData() {
  var folio = document.getElementById("folio").value.trim();
  var numIne = document.getElementById("ine").value.trim();

  const usernamePattern = /^[a-zA-Z0-9Ññ]+$/;

  //Control de errores
  if (folio === "") {
    errorMsg.innerHTML = "Por favor, ingrese un folio.";
    return false;
  }

  if (numIne === "") {
    errorMsg.innerHTML = "Por favor, ingrese una</br>clave de elector valida.";
    return false;
  } else if (numIne.length < 18 || !usernamePattern.test(numIne)) {
    errorMsg.innerHTML =
      'Ingrese una clave de elector valida. </br> Mínimo 18 caracteres. <p style="font-weight: normal; font-style: italic; font-size:0.75em;">(Sin espacios, acentos o caracteres especiales)</p>';
    return false;
  }

  // Si todas las validaciones pasan, limpiar el mensaje de error
  errorMsg.innerHTML = "";
  return true;
}

function upperCase(e) {
  e.value = e.value.toUpperCase();
}

function killSpace(event) {
  var codigoTecla = event.keyCode || event.which;
  // Verificar si la tecla presionada es un espacio o si se está intentando pegar
  if (
    codigoTecla === 32 ||
    (event.ctrlKey && (codigoTecla === 86 || codigoTecla === 118))
  ) {
    event.preventDefault();
    return false;
  }
}

function killStrings(event) {
  // Obtener el código de la tecla presionada
  var keyCode = event.keyCode || event.which;

  // Permitir solo números (códigos de teclas del 0 al 9) o tecla de retroceso (código 8)
  // También permitir números del pad numérico (códigos de teclas del 96 al 105)
  if (
    (keyCode < 48 || keyCode > 57) &&
    (keyCode < 96 || keyCode > 105) &&
    keyCode !== 8
  ) {
    // Bloquear la entrada
    event.preventDefault();
    return false;
  }
}

function allowInput(event) {
  // Obtener el código de la tecla presionada
  var keyCode = event.keyCode || event.which;

  // Permitir el uso de la tecla de retroceso (backspace)
  if (keyCode === 8) {
    return true;
  }

  // Bloquear la inserción de espacios
  if (keyCode === 32) {
    event.preventDefault();
    return false;
  }

  // Bloquear la inserción de caracteres especiales
  var caracteresEspeciales = /[!@#$%^&*()_+\-=\[\]{};':"\\|,.<>\/?]/;
  var caracter = String.fromCharCode(keyCode);
  if (caracteresEspeciales.test(caracter)) {
    event.preventDefault();
    return false;
  }

  return true;
}
