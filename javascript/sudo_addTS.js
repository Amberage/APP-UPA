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
    var userType = document.getElementById('userType').value;

    //Control de errores
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
    
    if (password === '') {
        errorMessage.innerHTML = 'Por favor, ingrese una contraseña.';
        return false;
    } 

    if (confirmPassword === '') {
        errorMessage.innerHTML = 'Por favor, confirme la contraseña';
        return false;
    }
    
    if (userType === '') {
        errorMessage.innerHTML = 'Por favor, seleccione un tipo de cuenta';
        return false;
    }

    if (password != confirmPassword) {
        errorMessage.innerHTML = 'Las contraseñas no coinciden.'
        return false;
    }
    // Si todas las validaciones pasan, limpiar el mensaje de error
    errorMessage.innerHTML = '';
    return true;
}

function createTS() {
    // Datos del trabajador
    var tsName = document.getElementById('tsName').value.trim();
    var tsLastname = document.getElementById('tsLastname').value.trim();
    var username = document.getElementById('username').value.trim();
    var password = document.getElementById('password').value;
    var confirmPassword = document.getElementById('confirmPassword').value;
    var userType = document.getElementById('userType').value;

    const loaderURL = "/php/sudo_insertTS.php";

    let formData = new FormData();

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
    document.getElementById('userType').value = "";
}

function back() {
    window.location.href = '/views/sudo/dashboard.php';
}