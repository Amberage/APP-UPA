var errorMessage = document.getElementById('errorMsg');
var successMessage = document.getElementById('successMsg');
const idTS = document.getElementById('idTS').value;

function sendPet() {
    if (validateFormData() === true) {
        createPet();
    }
}

function validateFormData() {
    var petName = document.getElementById('petName').value;
    var petBreed = document.getElementById('petBreed').value;
    var petColor = document.getElementById('petColor').value;
    var petSex = document.getElementById('petSex').value;
    var petPicture = document.getElementById('petPicture').value;

    var ownerName = document.getElementById('ownerName').value;
    var ownerINE = document.getElementById('ownerINE').value;
    var ownerCURP = document.getElementById('ownerCURP').value;
    var ownerColony = document.getElementById('ownerColony').value;
    var ownerAddress = document.getElementById('ownerAddress').value;
    
    const namePattern = /^[A-Za-záéíóúÁÉÍÓÚñÑ\s]+$/;

    //Errores de la mascota
    if (petName === '') {
        errorMessage.innerHTML = 'Por favor, ingrese el nombre de la mascota';
        return false;
    } else if (!namePattern.test(petName)) {
        errorMessage.innerHTML = 'Por favor, ingrese un nombre válido para la mascota. </br> Solo letras, mínimo 3. <p style="font-weight: normal; font-style: italic; font-size:0.75em;">(Se permiten espacios)</p>';
        return false;
    }

    if (petBreed === '') {
        errorMessage.innerHTML = 'Por favor, ingrese la raza de la mascota';
        return false;
    } else if (!namePattern.test(petBreed)) {
        errorMessage.innerHTML = 'Por favor, ingrese una raza válida para la mascota. </br> Solo letras, mínimo 3. <p style="font-weight: normal; font-style: italic; font-size:0.75em;">(Se permiten espacios)</p>';
        return false;
    }

    if (petColor === '') {
        errorMessage.innerHTML = 'Por favor, ingrese el color de la mascota';
        return false;
    } else if (!namePattern.test(petColor)) {
        errorMessage.innerHTML = 'Por favor, ingrese un color válido para la mascota. </br> Solo letras, mínimo 3. <p style="font-weight: normal; font-style: italic; font-size:0.75em;">(Se permiten espacios)</p>';
        return false;
    }
    
    if (petSex === '') {
        errorMessage.innerHTML = 'Por favor, seleccione el sexo de la mascota';
        return false;
    }

    if (petPicture === '') {
        errorMessage.innerHTML = 'Por favor, suba una imagen de la mascota';
        return false;
    }

    //Errores del propietario
    if (ownerName === '') {
        errorMessage.innerHTML = 'Por favor, ingrese el nombre del propietario';
        return false;
    } else if (!namePattern.test(ownerName)) {
        errorMessage.innerHTML = 'Por favor, ingrese un nombre válido para el propietario. </br> Solo letras. <p style="font-weight: normal; font-style: italic; font-size:0.75em;">(Se permiten espacios)</p>';
        return false;
    }

    if (ownerINE.length < 18) {
        errorMessage.innerHTML = 'El INE debe tener 18 caracteres';
        return false;
    }

    if (ownerCURP.length < 18) {
        errorMessage.innerHTML = 'El CURP debe tener 18 caracteres';
        return false;
    }
    
    if (ownerColony === '') {
        errorMessage.innerHTML = 'Por favor, seleccione una colonia';
        return false;
    }

    if (ownerAddress === '') {
        errorMessage.innerHTML = 'Por favor, ingrese una dirección';
        return false;
    }

    // Si todas las validaciones pasan, limpiar el mensaje de error
    errorMessage.innerHTML = '';
    return true;
}

function createPet() {
    // Datos del TS
    var idTS = document.getElementById('idTS').value;
    // Datos de la mascota
    var petName = document.getElementById('petName').value;
    var petBreed = document.getElementById('petBreed').value;
    var petColor = document.getElementById('petColor').value;
    var petSex = document.getElementById('petSex').value;
    var petPicture = document.getElementById('petPicture').files[0];
    // Datos del dueño
    var ownerName = document.getElementById('ownerName').value;
    var ownerINE = document.getElementById('ownerINE').value;
    var ownerCURP = document.getElementById('ownerCURP').value;
    var ownerColony = document.getElementById('ownerColony').value;
    var ownerAddress = document.getElementById('ownerAddress').value;

    const loaderURL = "/php/ts_insertPet.php";

    let formData = new FormData();
    // Datos del TS
    formData.append("idTS", idTS);
    // Datos de la mascota
    formData.append("petName", petName);
    formData.append("petBreed", petBreed);
    formData.append("petColor", petColor);
    formData.append("petSex", petSex);
    formData.append("petPicture", petPicture);
    // Datos del dueño
    formData.append("ownerName", ownerName);
    formData.append("ownerINE", ownerINE);
    formData.append("ownerCURP", ownerCURP);
    formData.append("ownerColony", ownerColony);
    formData.append("ownerAddress", ownerAddress);
    
    //Enviarlos via POST
    fetch(loaderURL, {
        method: "POST",
        body: formData,
    })
        .then((response) => response.json())
        .then((responseData) => {
            //successMessage.innerHTML = responseData.successfulMssg;
            errorMessage.innerHTML = responseData.errorMsg;
            let folio = responseData.folio;
            let validateQuery = responseData.validateQuery;
            let backupState = responseData.backupState;
            if(backupState === false) {
                Swal.fire({
                    title: "¡Error al generar el respaldo!",
                    text: "El acta esta disponible, pero la imagen no ha sido respaldada adecuadamente, informa al departamento de sistemas.",
                    icon: "error"
                  });
            }

            if(validateQuery === true && backupState === true) {
                Swal.fire({
                    title: `¡${petName} ha sido regitrad@!`,
                    text: "¿Desea descargar el acta?",
                    color: "#666c6c",
                    icon: "success",
                    showCancelButton: true,
                    cancelButtonColor: "#1A5C50",
                    cancelButtonText: "No",
                    confirmButtonColor: "#1A5C50",
                    confirmButtonText: "Descargar Acta"
                  }).then((result) => {
                    if (result.isConfirmed) {
                        printPet(folioActa);
                    }
                  });
            } else {
                Swal.fire({
                    title: "¡Error al registrar a" + petName + "!",
                    text: `Favor de informar al departamento de sistemas, lamentamos los inconvenientes. \n(Problema en el procesamiento de datos, result: ${resultQuery} validate: ${validateQuery})`,
                    icon: "error"
                  });
            }
            resetForm();
        })
        .catch((err) => console.log(err));
}

function resetForm() {
    document.getElementById('petName').value = "";
    document.getElementById('petBreed').value = "";
    document.getElementById('petColor').value = "";
    document.getElementById('petSex').value = "";
    document.getElementById('petPicture').value = null;
    document.getElementById('ownerName').value = "";
    document.getElementById('ownerINE').value = "";
    document.getElementById('ownerCURP').value = "";
    document.getElementById('ownerColony').value = "";
    document.getElementById('ownerAddress').value = "";
}

function upperCase(e){
    e.value = e.value.toUpperCase();
}

function back() {
    window.location.href = '/views/ts/dashboard.php';
}