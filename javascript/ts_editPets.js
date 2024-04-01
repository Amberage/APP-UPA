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

    const namePattern = /^[A-Za-záéíóúÁÉÍÓÚñÑ\s]+$/;

    //Errores de la mascota
    if (petName === '') {
        errorMessage.innerHTML = 'Por favor, ingrese el nombre de la mascota';
        return false;
    } else if (!namePattern.test(petName) || petName.length < 3) {
        errorMessage.innerHTML = 'Por favor, ingrese un nombre válido para la mascota. </br> Solo letras, mínimo 3. <p style="font-weight: normal; font-style: italic; font-size:0.75em;">(Se permiten espacios)</p>';
        return false;
    }

    if (petBreed === '') {
        errorMessage.innerHTML = 'Por favor, ingrese la raza de la mascota';
        return false;
    } else if (!namePattern.test(petBreed)|| petBreed.length < 3) {
        errorMessage.innerHTML = 'Por favor, ingrese una raza válida para la mascota. </br> Solo letras, mínimo 3. <p style="font-weight: normal; font-style: italic; font-size:0.75em;">(Se permiten espacios)</p>';
        return false;
    }

    if (petColor === '') {
        errorMessage.innerHTML = 'Por favor, ingrese el color de la mascota';
        return false;
    } else if (!namePattern.test(petColor) || petColor.length < 3) {
        errorMessage.innerHTML = 'Por favor, ingrese un color válido para la mascota. </br> Solo letras, mínimo 3. <p style="font-weight: normal; font-style: italic; font-size:0.75em;">(Se permiten espacios)</p>';
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
    } else if (!namePattern.test(ownerName) || ownerName.length < 4) {
        errorMessage.innerHTML = 'Por favor, ingrese un nombre válido para el propietario. </br> Solo letras, mínimo 4. <p style="font-weight: normal; font-style: italic; font-size:0.75em;">(Se permiten espacios)</p>';
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

    if (ownerAddress === '' || ownerAddress.length < 5) {
        errorMessage.innerHTML = 'Por favor, ingrese una dirección válida</br> Mínimo 5 caracteres.';
        return false;
    }

    // Si todas las validaciones pasan, limpiar el mensaje de error
    errorMessage.innerHTML = '';
    return true;
}

function updatePet() {
    disableButton();
    // Datos de la mascota
    var petName = document.getElementById('petName').value.trim();
    var petBreed = document.getElementById('petBreed').value.trim();
    var petColor = document.getElementById('petColor').value.trim();
    var petSex = document.getElementById('petSex').value
    var petPicture = document.getElementById('petPicture').files[0];
    // Datos del dueño
    var ownerName = document.getElementById('ownerName').value.trim();
    var ownerINE = document.getElementById('ownerINE').value.trim();
    var ownerCURP = document.getElementById('ownerCURP').value.trim();
    var ownerColony = document.getElementById('ownerColony').value;
    var ownerAddress = document.getElementById('ownerAddress').value.trim();
    // Datos antiguos
    var folioActa = document.getElementById('folioActa').value.trim();
    var old_petPicture = document.getElementById('old_petPicture').value.trim();

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
    document.getElementById('petPicture').value = null;
    
    //Enviarlos via POST
    fetch(loaderURL, {
        method: "POST",
        body: formData,
    })
        .then((response) => response.json())
        .then((responseData) => {
            errorMessage.innerHTML = responseData.errorMsg;
            let resultQuery = responseData.resultQuery;
            let validateQuery = responseData.validateQuery;
            let backupState = responseData.backupState;
            let backupRequired = responseData.backupRequired;
            let errorState = true;
            let backupMsg = 'default';

            if(backupState === true && backupRequired === 'no') {
                backupMsg = 'NO se solicituo un backup pero si se genero uno';
                errorState = true;
            }

            if(backupState === true && backupRequired === 'si') {
                backupMsg = 'SI se solicito un backup y se genero adecuadamente';
                errorState = false;
            }

            if(backupState === false && backupRequired === 'si') {
                backupMsg = 'SI se solicito un backup, pero este no se generó.';
                errorState = true;
            }

            if(backupState === false && backupRequired === 'no') {
                backupMsg = 'No se solicito ningun backup';
                errorState = false;
            }

            if(resultQuery === true && validateQuery === true && errorState === false) {
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
                console.log(backupMsg);
                Swal.fire({
                    title: "¡Error al actualizar el acta!",
                    text: `Favor de informar al departamento de sistemas, lamentamos los inconvenientes. (Problema en el procesamiento de datos, result: ${resultQuery} validate: ${validateQuery} backup:${backupMsg})`,
                    icon: "error"
                  });
            }
            enableButton();
        })
        .catch((err) => console.log(err));
}

function cancelPet() {
    window.location.href = '/views/ts/viewPets.php';
}


function upperCase(e){
    e.value = e.value.toUpperCase();
}

function killSpace(event) {
    var codigoTecla = event.keyCode || event.which;
    // Verificar si la tecla presionada es un espacio o si se está intentando pegar
    if (codigoTecla === 32 || (event.ctrlKey && (codigoTecla === 86 || codigoTecla === 118))) {
        event.preventDefault();
        return false;
    }
}

function showWarningPic(){
    toastr.options = {
      "closeButton": false,
      "debug": false,
      "newestOnTop": false,
      "progressBar": true,
      "positionClass": "toast-bottom-right",
      "preventDuplicates": true,
      "onclick": null,
      "showDuration": "500",
      "hideDuration": "1000",
      "timeOut": "7000",
      "extendedTimeOut": "3000",
      "showEasing": "swing",
      "hideEasing": "linear",
      "showMethod": "fadeIn",
      "hideMethod": "fadeOut"
    }

    toastr["success"]("Se esta generando el acta, este proceso puede tardar debido al peso de la imagen.<br><span style='font-size: 0.75em; color: #d3bf9a; font-style: italic;'>Si el proceso tarda demasiado se recomienda usar imagenes más ligeras (menos de 5MB).</span>", "¡ACTA GENERANDOSE!")
    // Aplicar estilo al mensaje de Toastr para aumentar el ancho
    $(".toast").css("width", "420px"); // Cambia el valor 500px al ancho deseado
}

function disableButton() {
    var button = document.getElementById("sendPet");
    // Verificar si se ha cargado una imagen para mostrar la alerta de espera.
    let inputFile = document.getElementById('petPicture');
    if (inputFile.files && inputFile.files[0]) {
        showWarningPic();
    }
    button.classList.add("loginDisabled");
    button.classList.remove("login");
}

function enableButton() {
    var button = document.getElementById("sendPet");
    button.classList.remove("loginDisabled");
    button.classList.add("login");
}


// Evitar mandar datos innecesarios a la BBDD
var petNameInput = document.getElementById('petName');
var petBreedInput = document.getElementById('petBreed');
var petColorInput = document.getElementById('petColor');
var petSexInput = document.getElementById('petSex');
var petPictureInput = document.getElementById('petPicture');
// Datos del dueño
var ownerNameInput = document.getElementById('ownerName');
var ownerINEInput = document.getElementById('ownerINE');
var ownerCURPInput = document.getElementById('ownerCURP');
var ownerColonyInput = document.getElementById('ownerColony');
var ownerAddressInput = document.getElementById('ownerAddress');
const editButton = document.getElementById('sendPet');

//Modificar el codigo de abajo usando las variables de arriba

// Obtener los valores iniciales
let old_petName = petNameInput.value.trim();
let old_petBreed = petBreedInput.value.trim();
let old_petColor = petColorInput.value.trim();
let old_petSex = petSexInput.value // Select
let old_petPicturex = petPictureInput.files[0]; //Input file
// Datos del dueño
let old_ownerName = ownerNameInput.value.trim();
let old_ownerINE = ownerINEInput.value.trim();
let old_ownerCURP = ownerCURPInput.value.trim();
let old_ownerColony = ownerColonyInput.value; //Select
let old_ownerAddress = ownerAddressInput.value.trim();

// Listener: Datos mascota
petNameInput.addEventListener('input', checkInputChange);
petBreedInput.addEventListener('input', checkInputChange);
petColorInput.addEventListener('input', checkInputChange);
petSexInput.addEventListener('change', checkInputChange);
petPictureInput.addEventListener('change', checkInputChange);
// Listener: Datos propietarios
ownerNameInput.addEventListener('input', checkInputChange);
ownerINEInput.addEventListener('input', checkInputChange);
ownerCURPInput.addEventListener('input', checkInputChange);
ownerColonyInput.addEventListener('change', checkInputChange);
ownerAddressInput.addEventListener('input', checkInputChange);

// Función para comprobar el cambio en los valores de entrada y deshabilitar el botón
function checkInputChange() {
    const new_petName = petNameInput.value.trim();
    const new_petBreed = petBreedInput.value.trim();
    const new_petColor = petColorInput.value.trim();
    const new_petSex = petSexInput.value // Select
    const new_petPicturex = petPictureInput.files[0]; //Input file
    // Datos del dueño
    const new_ownerName = ownerNameInput.value.trim();
    const new_ownerINE = ownerINEInput.value.trim();
    const new_ownerCURP = ownerCURPInput.value.trim();
    const new_ownerColony = ownerColonyInput.value; //Select
    const new_ownerAddress = ownerAddressInput.value.trim();

    // Verificar si alguno de los valores ha cambiado
    if (
        /*Comprobar valores mascota */
        new_petName !== old_petName ||
        new_petBreed !== old_petBreed ||
        new_petColor !== old_petColor ||
        new_petSex !== old_petSex ||
        new_petPicturex !== old_petPicturex ||
        /*Comprobar valores propietario */
        new_ownerName !== old_ownerName ||
        new_ownerINE !== old_ownerINE ||
        new_ownerCURP !== old_ownerCURP ||
        new_ownerColony !== old_ownerColony ||
        new_ownerAddress !== old_ownerAddress
    ) {
        // Cambiar la clase del botón
        editButton.classList.remove('loginDisabled');
        editButton.classList.add('login');
    } else {
        // Restaurar la clase original del botón
        editButton.classList.remove('login');
        editButton.classList.add('loginDisabled');
    }
}

// Obtener el elemento de input de tipo file
var pictureFile = document.getElementById('petPicture');

// Agregar un event listener para el evento 'change'
pictureFile.addEventListener('change', function() {
    // Obtener el archivo de imagen cargado
    var petFile = this.files[0];
    
    // Verificar si se ha cargado un archivo
    if (petFile) {
        // Verificar si el tamaño del archivo es mayor a 5MB (5 * 1024 * 1024 bytes)
        if (petFile.size > 20 * 1024 * 1024) {
            showErrorPreload();
            this.value = "";
        } else if (petFile.size > 5 * 1024 * 1024) {
            showWarningPreload();
        }
    }
});


function showWarningPreload(){
    toastr.options = {
      "closeButton": false,
      "debug": false,
      "newestOnTop": false,
      "progressBar": true,
      "positionClass": "toast-bottom-right",
      "preventDuplicates": true,
      "onclick": null,
      "showDuration": "500",
      "hideDuration": "1000",
      "timeOut": "7000",
      "extendedTimeOut": "3000",
      "showEasing": "swing",
      "hideEasing": "linear",
      "showMethod": "fadeIn",
      "hideMethod": "fadeOut"
    }

    toastr["warning"]("La imagen es valida, pero quizá el acta tarde en poco más en actualizarse, te recomendamos usar imagenes de menos de 5MB.", "IMAGEN PESADA")
    // Aplicar estilo al mensaje de Toastr para aumentar el ancho
    $(".toast").css("width", "420px");
    $(".toast-title").css("color", "#611230");
    $(".toast-message").css("color", "#fff");
}

function showErrorPreload(){
    toastr.options = {
      "closeButton": false,
      "debug": false,
      "newestOnTop": false,
      "progressBar": true,
      "positionClass": "toast-bottom-right",
      "preventDuplicates": true,
      "onclick": null,
      "showDuration": "500",
      "hideDuration": "1000",
      "timeOut": "7000",
      "extendedTimeOut": "3000",
      "showEasing": "swing",
      "hideEasing": "linear",
      "showMethod": "fadeIn",
      "hideMethod": "fadeOut"
    }

    toastr["error"]("La imagen es muy pesada, el límite es de 15MB.", "¡IMAGEN INVALIDA!")
    // Aplicar estilo al mensaje de Toastr para aumentar el ancho
    $(".toast").css("width", "420px");
    $(".toast-title").css("color", "#d7a255");
    $(".toast-message").css("color", "#fff");
}