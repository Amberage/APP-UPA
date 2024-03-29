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
    const namePattern = /^[A-Za-záéíóúÁÉÍÓÚñÑ\s]+$/;
    const usernamePattern = /^[a-zA-Z0-9_.-]+$/;
    var editPassword = document.getElementById('editPassword').checked;

    //Validación de errores sin cambio de contraseña
    if (tsName === '') {
        errorMessage.innerHTML = 'Por favor, ingrese un nombre.';
        return false;
    } else if (!namePattern.test(tsName)) {
        errorMessage.innerHTML = 'Por favor, ingrese un nombre válido.</br> Solo letras, mínimo 3. <p style="font-weight: normal; font-style: italic; font-size:0.75em;">(Se permiten espacios)</p>';
        return false;
    }

    if (tsLastname === '') {
        errorMessage.innerHTML = 'Por favor, ingrese los apellidos.';
        return false;
    } else if (!namePattern.test(tsLastname)) {
        errorMessage.innerHTML = 'Por favor, ingrese apellidos válidos. </br> Solo letras, mínimo 3. <p style="font-weight: normal; font-style: italic; font-size:0.75em;">(Se permiten espacios)</p>';
        return false;
    }

    if (username === '') {
        errorMessage.innerHTML = 'Por favor, ingrese un nombre de usuario.';
        return false;
    } else if (!usernamePattern.test(username)) {
        errorMessage.innerHTML = 'Por favor, ingrese un nombre de usuario válido </br> Mínimo 4 caracteres. <p style="font-weight: normal; font-style: italic; font-size:0.75em;">(Sin espacios, acentos o caracteres especiales)</p>';
        return false;
    } else if (username.length < 4 || username.length > 12) {
        errorMessage.innerHTML = 'El nombre de usuario no es valido <p style="font-weight: normal; font-style: italic; font-size:0.75em;">(Mínimo 4 caracteres, máximo 12 caracteres.)</p>';
        return false;
    }

    if(editPassword === true) {
        var password = document.getElementById('password').value;
        var confirmPassword = document.getElementById('confirmPassword').value;
        if (password === '') {
            errorMessage.innerHTML = 'Por favor, ingrese una contraseña.';
            return false;
        } else if (password.length < 6 || password.length > 36) {
            errorMessage.innerHTML = 'La contraseña no es valida <p style="font-weight: normal; font-style: italic; font-size:0.75em;">(Mínimo 6 caracteres, máximo 36 caracteres.)</p>';
            return false;
        }
    
        if (confirmPassword === '') {
            errorMessage.innerHTML = 'Por favor, confirme la contraseña';
            return false;
        } else if (password.length < 6 || password.length > 36) {
            errorMessage.innerHTML = 'Las contraseñas no coinciden.'
            return false;
        } else if (password != confirmPassword) {
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
    var confirmPassword = document.getElementById('confirmPassword').value;
    const loaderURL = "/php/adm_updateTS.php";


    let formData = new FormData();

    formData.append("idTS", idTS);
    formData.append("tsName", tsName);
    formData.append("tsLastname", tsLastname);
    formData.append("username", username);
    formData.append("password", password);
    formData.append("confirmPassword", confirmPassword);

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
    window.location.href = '/views/admin/viewTS.php';
}



var tsNameInput = document.getElementById('tsName');
var tsLastnameInput = document.getElementById('tsLastname');
var usernameInput = document.getElementById('username');
var passwordInput = document.getElementById('password');
const editButton = document.getElementById('editButton');

// Obtener los valores iniciales
let old_tsName = tsNameInput.value;
let old_tsLastname = tsLastnameInput.value;
let old_username = usernameInput.value;

// Agregar event listener a cada input
tsNameInput.addEventListener('input', checkInputChange);
tsLastnameInput.addEventListener('input', checkInputChange);
usernameInput.addEventListener('input', checkInputChange);
passwordInput.addEventListener('input', checkInputChange);

// Función para comprobar el cambio en los valores de entrada y deshabilitar el botón
function checkInputChange() {
    // Obtener los nuevos valores
    const new_tsName = tsNameInput.value;
    const new_tsLastname = tsLastnameInput.value;
    const new_username = usernameInput.value;
    const new_password = passwordInput.value;

    // Verificar si alguno de los valores ha cambiado
    if (
        new_tsName !== old_tsName ||
        new_tsLastname !== old_tsLastname ||
        new_username !== old_username ||
        new_password !== ''
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
}