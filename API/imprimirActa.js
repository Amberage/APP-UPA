// Cargar la librería jsPDF dinámicamente
function loadJsPDF() {
    return new Promise(resolve => {
        const script = document.createElement('script');
        script.src = '/API/jspdf.min.js'; // Ubicación de jsPDF
        script.onload = () => {
            resolve(window.jspdf); // Resuelve la promesa con la referencia a jsPDF
        };
        document.head.appendChild(script);
    });
}

// Cargar imagenes en el PDF
function loadImage(url){
    return new Promise(resolve => {
        const xhr = new XMLHttpRequest();
        xhr.open('GET', url, true);
        xhr.responseType = "blob";
        xhr.onload = function(e) {
            const reader = new FileReader();
            reader.onload = function(event){
                const res = event.target.result;
                resolve(res);
            }
            const file = this.response;
            reader.readAsDataURL(file);
        }
        xhr.send();
    });
}

async function createPDF(folioActual, petName, petSex, petBreed, petColor, petPicture, ownerName, ownerCURP, ownerINE, ownerColony, ownerAddress, tsName, dia, monthInt, anio){
    const jsPDF = await loadJsPDF();
    const background = await loadImage("/API/bg.jpeg");
    const pdf = new jsPDF('p', 'pt', 'letter');
    pdf.addImage(background, 'jpge', 0, 0, 615, 792);

    const monthNumber = parseInt(monthInt);
    if (isNaN(monthNumber) || monthNumber < 1 || monthNumber > 12) {
        console.error("El mes proporcionado no es válido." + monthNumber);
        return;
    }

    const monthNames = [
        "ENERO", "FEBRERO", "MARZO",
        "ABRIL", "MAYO", "JUNIO", "JULIO",
        "AGOSTO", "SEPTIEMBRE", "OCTUBRE",
        "NOVIEMBRE", "DICIEMBRE"
    ];

    const month = monthNames[monthNumber - 1]; 

    // Renderizar texto centrado
    function renderCenterText(text, yPostion, size, bold) {
        const textWidth = pdf.getStringUnitWidth(text) * size / pdf.internal.scaleFactor;
        const pageWidth = pdf.internal.pageSize.width;
        const centerX = (pageWidth - textWidth) / 2;

        if(bold === true) {
            pdf.setFontSize(size);
            pdf.setFont(undefined, 'bold');
        } else {
            pdf.setFontSize(size);
            pdf.setFont(undefined, 'normal');
        }
        pdf.text(text, centerX, yPostion);
    }

    // Renderizar textos largos
    function renderLargeText(text, yPostion, size, bold) {
        const maxWidth = 500; 
        const initialXPosition = 45; // Posición inicial en X
        let currentXPosition = initialXPosition; // Inicializamos la posición X actual
    
        const lines = pdf.splitTextToSize(text, maxWidth);
        const spacedLines = lines.map(line => line + '     '); // Agrega espacios adicionales
    
        if (bold === true) {
            pdf.setFontSize(size);
            pdf.setFont(undefined, 'bold');
        } else {
            pdf.setFontSize(size);
            pdf.setFont(undefined, 'normal');
        }
    
        // Ajusta el espaciado vertical y agrega las líneas al PDF con un salto de línea
        spacedLines.forEach((line, index) => {
            const y = yPostion + (index * size); // Ajusta el espaciado vertical
            // Si es la primera línea, no ajustamos la posición X
            if (index !== 0) {
                // Ajusta la posición X para la próxima línea
                currentXPosition = 120; // Puedes establecer el valor que desees
            }
            pdf.text(line, currentXPosition, y); // Coordenadas donde se agregará el texto
        });
    }

    // Renderizar texto
    function renderText(text, yPostion, size, bold) {
        const posFijaX = 45;
        if(bold === true) {
            pdf.setFontSize(size);
            pdf.setFont(undefined, 'bold');
        } else {
            pdf.setFontSize(size);
            pdf.setFont(undefined, 'normal');
        }
        pdf.text(text, posFijaX, yPostion);
    }


    //! Cuerpo del PDF
    //? Datos de la mascota
    const folio = `FOLIO: ${folioActual}`;
    const nombreMascota = `NOMBRE:      ${petName}`;
    const sexoMascota = `SEXO:            ${petSex}`;
    const razaMascota = `RAZA:            ${petBreed}`;
    const colorMascota = `COLOR:         ${petColor}`;
    const fotoMascota = await loadImage(`${petPicture}`);

    //? Datos del dueño
    const nombreTitular = `NOMBRE:     ${ownerName}`;
    const curpTitular = `CURP:           ${ownerCURP}`;
    const ineTitular = `INE:               ${ownerINE}`;
    const coloniaTitular = `COLONIA:     ${ownerColony}`;
    const domicilioTitular = `DIRECCIÓN: ${ownerAddress}`;
    //? Datos del trabajador social
    const registroTS = `REGISTRADO POR ${tsName.toUpperCase()}`;
    const registroFecha = `EL DÍA ${dia} DE ${month} DEL AÑO ${anio} EN VALLE DE CHALCO, ESTADO DE MÉXICO.`;

    // Renderizado PDF
    // Folio
    pdf.setFontSize(14);
    pdf.setFont(undefined, 'bold');
    pdf.setTextColor(160 ,32 ,59);
    pdf.text(folio, 490, 115);
    pdf.setTextColor(0 ,0 ,0);
    //! Datos del Header
    renderCenterText('REGISTRO ANIMAL', 60, 18, true);
    renderCenterText('UNIDAD DE PROTECCIÓN ANIMAL DEL MUNICIPIO', 85, 12, true);
    renderCenterText('DE VALLE DE CHALCO SOLIDARIDAD', 100, 12, true);
    renderText('H. AYUNTAMIENTO DE VALLE DE CHALCO SOLIDARIDAD, POR MEDIO DEL PRESENTE SE', 145, 12, false);
    renderText('LE HACE ENTREGA DE ESTA CONSTANCIA, INFORMANDO  ASI QUE SE LE BENEFICIO  AL', 160, 12, false);
    renderText('CIUDADANO(A) , REGISTRADO CON LOS SIGUIENTES DATOS:', 175, 12, false);
    //! Datos de la Mascota
    renderCenterText('DATOS DE LA MASCOTA', 210, 14, true);
    renderText(nombreMascota, 235, 12, true);
    renderText(sexoMascota, 250, 12, true);
    renderText(razaMascota, 265, 12, true);
    renderText(colorMascota, 280, 12, true);
    pdf.addImage(fotoMascota, 'jpg', 425, 215, 150, 150);
    //! Datos del Propietario
    renderCenterText('DATOS DEL PROPIETARIO', 310, 14, true);
    renderText(nombreTitular, 335, 12, true);
    renderText(curpTitular, 350, 12, true);
    renderText(ineTitular, 365, 12, true);
    renderText(coloniaTitular, 380, 12, true);
    renderLargeText(domicilioTitular, 395, 12, true);
    //! Datos del trabajador social
    renderCenterText(registroTS, 455, 12, true);
    renderCenterText(registroFecha, 470, 10, false);

    // Guardar el documento
    var folioSinCeros = folioActual.replace(/^0+/, ''); // Elimina todos los ceros a la izquierda
    pdf.save(`${folioSinCeros} ${petName}.pdf`);
}

function printPet(folio) {
    if (isNaN(folio)) {
        Swal.fire({
            title: "¡ERROR!",
            text: "Ocurrió un error al generar el acta, favor de contactar al departamento de sistemas. El error esta en el folio: " + folio,
            icon: "error"
          });
        return;
    } else {
        const loaderURL = "/php/getPrintPetData.php";
        let formData = new FormData();
        formData.append("folio", folio);
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
                Swal.fire({
                    title: successMsg,
                    html: '<b>Este proceso puede demorar debido a la velocidad de internet o tu dispositivo, no olvides revisar tus descargas.</b><br><br><p style="font-size: 0.75em; color: #9d2348; font-style: italic;">Si el acta no se genera después de un minuto ponte en contacto con el departamento de sistemas.</p>',
                    icon: "success"
                  });
                createPDF(responseData[0].folio, responseData[0].petName, responseData[0].petSex, responseData[0].petBreed, responseData[0].petColor, responseData[0].petPicture, responseData[0].ownerName, responseData[0].ownerCURP, responseData[0].ownerINE, responseData[0].ownerColony, responseData[0].ownerAddress, responseData[0].nombreTS, responseData[0].dia, responseData[0].mes, responseData[0].anio);
            }
        })
        .catch((err) => console.log(err));
    }
}

function printPetBackup(folio) {
    if (isNaN(folio)) {
        Swal.fire({
            title: "¡ERROR!",
            text: "Ocurrió un error al generar el acta, favor de contactar al departamento de sistemas. El error esta en el folio: " + folio,
            icon: "error"
          });
        return;
    } else {
        const loaderURL = "/php/getPrintPetDataBackup.php";
        let formData = new FormData();
        formData.append("folio", folio);
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
                Swal.fire({
                    title: successMsg,
                    html: '<b>Este proceso puede demorar debido a la velocidad de internet o tu dispositivo, no olvides revisar tus descargas.</b><br><br><p style="font-size: 0.75em; color: #9d2348; font-style: italic;">Si el acta no se genera después de un minuto ponte en contacto con el departamento de sistemas.</p>',
                    icon: "success"
                  });
                createPDF(responseData[0].folio, responseData[0].petName, responseData[0].petSex, responseData[0].petBreed, responseData[0].petColor, responseData[0].petPicture, responseData[0].ownerName, responseData[0].ownerCURP, responseData[0].ownerINE, responseData[0].ownerColony, responseData[0].ownerAddress, responseData[0].nombreTS, responseData[0].dia, responseData[0].mes, responseData[0].anio);
            }
        })
        .catch((err) => console.log(err));
    }
}