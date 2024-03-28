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

    if (petPicture === '') {
        errorMessage.innerHTML = 'Por favor, suba una imagen de la mascota';
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

function createPet() {
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
    formData.append("idTS", getID());
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
            successMessage.innerHTML = responseData.successfulMssg;
            errorMessage.innerHTML = responseData.errorMsg;
            let folio = responseData.folio;
            if (folio != undefined) {
                printPet(folio);
                resetForm();
            } else {
                errorMessage.innerHTML = 'Error al generar el acta, consulte con el departamento de sistemas.';
            }
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

function getID() {
    const loaderURL = "/php/st_getSessionData.php";
    fetch(loaderURL, {
        method: "POST"
    })
    .then(response => {
        if (!response.ok) {
            throw new Error('Error en la solicitud a la sesión');
        }
        return response.json();
    })
    .then(sessionData => {
        let tsID;
        tsID = sessionData.id;
        return tsID;
    })
    .catch(error => {
        alert('Error al obtener el ID del Trabajador Social, favor de comunicarse con el departamento de sistemas.', error);
});
}