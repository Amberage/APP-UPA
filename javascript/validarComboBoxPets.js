function validarComboBox() {
    var petSex = document.getElementById('petSex').value;
    var ownerColony = document.getElementById('ownerColony').value;
    var ownerCURP = document.getElementById('ownerCURP').value;
    var ownerINE = document.getElementById('ownerINE').value;
    var errorMessage = document.getElementById('errorMsg');

    if (petSex === '') {
        errorMessage.innerHTML = 'Por favor seleccione el sexo de la mascota';
        return false; // Evita que el formulario se envíe
    }

    if (ownerINE.length < 18) {
        errorMessage.innerHTML = 'El INE debe tener al menos 18 caracteres';
        return false; // Evita que el formulario se envíe
    }

    if (ownerCURP.length < 18) {
        errorMessage.innerHTML = 'El CURP debe tener al menos 18 caracteres';
        return false; // Evita que el formulario se envíe
    }
    
    if (ownerColony === '') {
        errorMessage.innerHTML = 'Por favor seleccione una colonia';
        return false; // Evita que el formulario se envíe
    }

    // Si todas las validaciones pasan, limpiar el mensaje de error
    errorMessage.innerHTML = '';
    
    return true;
}
