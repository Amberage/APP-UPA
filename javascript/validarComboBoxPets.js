function validarComboBox() {
    var petSex = document.getElementById('petSex').value;
    var ownerColony = document.getElementById('ownerColony').value;
    var errorMessage = document.getElementById('errorMsg');

    if (petSex === '') {
        errorMessage.innerHTML = 'Por favor seleccione el sexo de la mascota';
        return false; // Evita que el formulario se envíe
    }

    if (ownerColony === '') {
        errorMessage.innerHTML = 'Por favor seleccione una colonia';
        return false; // Evita que el formulario se envíe
    }

    if (petSex != '' && ownerColony != '') {
        errorMessage.innerHTML = '';
    }
    
    return true;
}