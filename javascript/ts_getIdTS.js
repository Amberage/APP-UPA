function printTS() {
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
        document.getElementById('idTS').value = sessionData.id;
    })
    .catch(error => {
        alert('Error al obtener el ID del Trabajador Social, favor de comunicarse con el departamento de sistemas.', error);
});
}