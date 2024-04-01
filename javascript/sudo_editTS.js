var errorMessage = document.getElementById('errorMsg');
var successMessage = document.getElementById('successMsg');
const idTS = document.getElementById('idTS').value;

function editTS() {
    if (validateFormData() === true) {
        updateTS(idTS);
    }
}

function changePassword() {
    let editPassword = document.getElementById('editPassword').checked;
    let inputPassword = document.getElementById('inputPassword');
    let inputConfirmPassword = document.getElementById('inputConfirmPassword');
    let password = document.getElementById('password');
    let confirmPassword = document.getElementById('confirmPassword');
    document.getElementById('password').value = "";
    document.getElementById('confirmPassword').value = "";

    if (editPassword === true) {
        inputPassword.style.display = 'block';
        inputConfirmPassword.style.display = 'block';
        password.disabled = false;
        confirmPassword.disabled = false;
    } else {
        inputPassword.style.display = 'none';
        inputConfirmPassword.style.display = 'none';
        password.disabled = true;
        confirmPassword.disabled = true;
    }
}

function validateFormData() {
    var tsName = document.getElementById('tsName').value;
    var tsLastname = document.getElementById('tsLastname').value;
    var username = document.getElementById('username').value;
    var userType = document.getElementById('userType').value;
    var editPassword = document.getElementById('editPassword').checked;

    //Validación de errores sin cambio de contraseña
    if (tsName === '') {
        errorMessage.innerHTML = 'Por favor, ingrese un nombre.';
        return false;
    }

    if (tsLastname === '') {
        errorMessage.innerHTML = 'Por favor, ingrese los apellidos.';
        return false;
    }

    if (username === '') {
        errorMessage.innerHTML = 'Por favor, ingrese un nombre de usuario.';
        return false;
    }
    
    if (userType === '') {
        errorMessage.innerHTML = 'Por favor, seleccione un tipo de cuenta.';
        return false;
    }

    if(editPassword === true) {
        var password = document.getElementById('password').value;
        var confirmPassword = document.getElementById('confirmPassword').value;
        if (password === '') {
            errorMessage.innerHTML = 'Por favor, ingrese una contraseña.';
            return false;
        }
    
        if (confirmPassword === '') {
            errorMessage.innerHTML = 'Por favor, confirme la contraseña';
            return false;
        }

        if (password != confirmPassword) {
            errorMessage.innerHTML = 'Las contraseñas no coinciden.'
            return false;
        }
    }
    // Si todas las validaciones pasan, limpiar el mensaje de error
    errorMessage.innerHTML = '';
    return true;
}

function updateTS(idTS) {
    // Datos del trabajador
    var tsName = document.getElementById('tsName').value.trim();
    var tsLastname = document.getElementById('tsLastname').value.trim();
    var username = document.getElementById('username').value.trim();
    var password = document.getElementById('password').value;
    var userType = document.getElementById('userType').value;
    var confirmPassword = document.getElementById('confirmPassword').value;
    const loaderURL = "/php/sudo_updateTS.php";


    let formData = new FormData();

    formData.append("idTS", idTS);
    formData.append("tsName", tsName);
    formData.append("tsLastname", tsLastname);
    formData.append("username", username);
    formData.append("password", password);
    formData.append("confirmPassword", confirmPassword);
    formData.append("userType", userType);

    //Enviarlos via POST
    fetch(loaderURL, {
        method: "POST",
        body: formData,
    })
        .then((response) => response.json())
        .then((responseData) => {
            let successMsg  = responseData.successMsg;
            let errorMsg  = responseData.errorMsg;
            let resultQuery = responseData.resultQuery;

            if(resultQuery === false) {
                Swal.fire({
                    title: "¡Error!",
                    text: errorMsg,
                    icon: "error"
                  });
            }

            if(resultQuery === true) {
                disabledButton();
                Swal.fire({
                    title: `¡Datos de ${tsName} actualizados!`,
                    text: successMsg,
                    icon: "success"
                  });
            }
            
        })
        .catch((err) => console.log(err));
}

function cancelTS() {
    window.location.href = '/views/sudo/viewTS.php';
}



var tsNameInput = document.getElementById('tsName');
var tsLastnameInput = document.getElementById('tsLastname');
var usernameInput = document.getElementById('username');
var passwordInput = document.getElementById('password');
var userTypeInput = document.getElementById('userType');
const editButton = document.getElementById('editButton');

// Obtener los valores iniciales
let old_tsName = tsNameInput.value;
let old_tsLastname = tsLastnameInput.value;
let old_username = usernameInput.value;
let old_userType = userTypeInput.value;

// Agregar event listener a cada input
tsNameInput.addEventListener('input', checkInputChange);
tsLastnameInput.addEventListener('input', checkInputChange);
usernameInput.addEventListener('input', checkInputChange);
passwordInput.addEventListener('input', checkInputChange);
userTypeInput.addEventListener('change', checkInputChange);

// Función para comprobar el cambio en los valores de entrada y deshabilitar el botón
function checkInputChange() {
    // Obtener los nuevos valores
    const new_tsName = tsNameInput.value;
    const new_tsLastname = tsLastnameInput.value;
    const new_username = usernameInput.value;
    const new_password = passwordInput.value;
    const new_userType = userTypeInput.value;

    // Verificar si alguno de los valores ha cambiado
    if (
        new_tsName !== old_tsName ||
        new_tsLastname !== old_tsLastname ||
        new_username !== old_username ||
        new_password !== '' ||
        new_userType !== old_userType
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

function disabledButton() {
    // Restaurar la clase original del botón
    editButton.classList.remove('login');
    editButton.classList.add('loginDisabled');

    // Reasignar los valores iniciales a las variables
    old_tsName = tsNameInput.value;
    old_tsLastname = tsLastnameInput.value;
    old_username = usernameInput.value;
    old_userType = userTypeInput.value;
}

function killSpace(event) {
    var codigoTecla = event.keyCode || event.which;
    // Verificar si la tecla presionada es un espacio o si se está intentando pegar
    if (codigoTecla === 32 || (event.ctrlKey && (codigoTecla === 86 || codigoTecla === 118))) {
        event.preventDefault();
        return false;
    }
}