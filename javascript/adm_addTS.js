var errorMessage = document.getElementById('errorMsg');
var successMessage = document.getElementById('successMsg');

function sendTS() {
    if (validateFormData() === true) {
        createTS();
    }
}

function validateFormData() {
    var tsName = document.getElementById('tsName').value;
    var tsLastname = document.getElementById('tsLastname').value;
    var username = document.getElementById('username').value;
    var password = document.getElementById('password').value;
    var confirmPassword = document.getElementById('confirmPassword').value;
    
    const namePattern = /^[A-Za-záéíóúÁÉÍÓÚñÑ\s]+$/;
    const usernamePattern = /^[a-zA-Z0-9_.-]+$/;

    //Control de errores
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
        errorMessage.innerHTML = 'Por favor, ingrese un username.';
        return false;
    } else if (!usernamePattern.test(username)) {
        errorMessage.innerHTML = 'Por favor, ingrese un username válido </br> Mínimo 4 caracteres. <p style="font-weight: normal; font-style: italic; font-size:0.75em;">(Sin espacios, acentos o caracteres especiales)</p>';
        return false;
    } else if (username.length < 4 || username.length > 12) {
        errorMessage.innerHTML = 'El nombre de usuario no es valido <p style="font-weight: normal; font-style: italic; font-size:0.75em;">(Mínimo 4 caracteres, máximo 12 caracteres.)</p>';
        return false;
    }
    
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
    // Si todas las validaciones pasan, limpiar el mensaje de error
    errorMessage.innerHTML = '';
    return true;
}

function createTS() {
    // Datos de la mascota
    var tsName = document.getElementById('tsName').value;
    var tsLastname = document.getElementById('tsLastname').value;
    var username = document.getElementById('username').value;
    var password = document.getElementById('password').value;
    var confirmPassword = document.getElementById('confirmPassword').value;
    const loaderURL = "/php/adm_insertTS.php";

    let formData = new FormData();

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
            let resultQuery = responseData.resultQuery;
            let successfulMssg  = responseData.successfulMssg;
            let errorMsg  = responseData.errorMsg;

            if(resultQuery === false) {
                Swal.fire({
                    title: "¡Error!",
                    text: errorMsg,
                    icon: "error"
                  });
            }

            if(resultQuery === true) {
                Swal.fire({
                    title: successfulMssg,
                    text: "El usuario ha sido registrado satisfactoriamente.",
                    icon: "success"
                  });
                  resetForm();
            }
            
        })
        .catch((err) => console.log(err));
}

function resetForm() {
    document.getElementById('tsName').value = "";
    document.getElementById('tsLastname').value = "";
    document.getElementById('username').value = "";
    document.getElementById('password').value = "";
    document.getElementById('confirmPassword').value = "";
}

function back() {
    window.location.href = '/views/admin/dashboard.php';
}