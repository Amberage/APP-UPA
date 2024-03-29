const loaderURL = "/php/st_getSessionData.php";
let id, nombre, apellido, userType;

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
    id = sessionData.id;
    nombre = sessionData.nombre;
    apellido = sessionData.apellido;
    userType = sessionData.userType;

    // Luego de asignar los valores, puedes realizar las validaciones aquí mismo
    if (userType === 'null' || userType === undefined) {
        window.location.href = '/index.php';
    } else if (userType === 'ts') {
        window.location.href = '/views/ts/dashboard.php';
    } else if (userType === 'adm') {
        //pass
    } else {
        alert("Error en la sesión, favor de contactar al departamento de sistemas.");
        console.log(`ID: ${id} - NOMBRE: ${nombre} - APELLIDO: ${apellido} - CUENTA: ${userType}`);
    }
})
.catch(error => {
    alert('Error de sesión, favor de comunicarse con el departamento de sistemas.', error);
});
