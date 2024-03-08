function validarComboBox() {
    var petSex = document.getElementById('petSex').value;
    var ownerColony = document.getElementById('ownerColony').value;

    if (petSex === '') {
        alert('Por favor seleccione el sexo de la mascota');
        return false; // Evita que el formulario se envíe
    }

    if (ownerColony === '') {
        alert('Por favor seleccione una colonia');
        return false; // Evita que el formulario se envíe
    }
    return true; // Permite que el formulario se envíe
}