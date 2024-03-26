var errorMessage = document.getElementById('errorMsg');
var successMessage = document.getElementById('successMsg');
const folioActa = document.getElementById('folioActa').value;
const old_petPicture = document.getElementById('old_petPicture').value;

function editPet() {
    if (validateFormData() === true) {
        updatePet();
    }
}

function validateFormData() {
    var petName = document.getElementById('petName').value;
    var petBreed = document.getElementById('petBreed').value;
    var petColor = document.getElementById('petColor').value;
    var petSex = document.getElementById('petSex').value;

    var ownerName = document.getElementById('ownerName').value;
    var ownerINE = document.getElementById('ownerINE').value;
    var ownerCURP = document.getElementById('ownerCURP').value;
    var ownerColony = document.getElementById('ownerColony').value;
    var ownerAddress = document.getElementById('ownerAddress').value;

    //Errores de la mascota
    if (petName === '') {
        errorMessage.innerHTML = 'Por favor, ingrese el nombre de la mascota';
        return false;
    }

    if (petBreed === '') {
        errorMessage.innerHTML = 'Por favor, ingrese la raza de la mascota';
        return false;
    }

    if (petColor === '') {
        errorMessage.innerHTML = 'Por favor, ingrese el color de la mascota';
        return false;
    }
    
    if (petSex === '') {
        errorMessage.innerHTML = 'Por favor, seleccione el sexo de la mascota';
        return false;
    }

    //Errores del propietario
    if (ownerName === '') {
        errorMessage.innerHTML = 'Por favor, ingrese el nombre del propietario';
        return false;
    }

    if (ownerINE.length < 18) {
        errorMessage.innerHTML = 'El INE debe tener al menos 18 caracteres';
        return false;
    }

    if (ownerCURP.length < 18) {
        errorMessage.innerHTML = 'El CURP debe tener al menos 18 caracteres';
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

function updatePet() {
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
    // Datos antiguos
    var folioActa = document.getElementById('folioActa').value;
    var old_petPicture = document.getElementById('old_petPicture').value;

    const loaderURL = "/php/ts_updatePet.php";
    let formData = new FormData();
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
    // Datos antiguos
    formData.append("folioActa", folioActa);
    formData.append("old_petPicture", old_petPicture);
    
    //Enviarlos via POST
    fetch(loaderURL, {
        method: "POST",
        body: formData,
    })
        .then((response) => response.json())
        .then((responseData) => {
            errorMessage.innerHTML = responseData.errorMsg;
            let result = responseData.resultQuery;
            if(result === 1) {
                Swal.fire({
                    title: responseData.successfulMssg,
                    text: "¿Desea descargar el acta actualizada?",
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
                    title: "¡Error al actualizar el acta!",
                    text: "Favor de informar al departamento de sistemas, lamentamos los inconvenientes. \n(PROBLEMA EN LA BBDD)",
                    icon: "error"
                  });
            }

        })
        .catch((err) => console.log(err));
}

function upperCase(e){
    e.value = e.value.toUpperCase();
}

function cancelPet() {
    window.location.href = '/views/ts/viewPets.php';
}
