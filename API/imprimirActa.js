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
        var xhr = new XMLHttpRequest();
        // Configurar una solicitud POST al archivo PHP
        xhr.open("POST", "/php/getPrintPetData.php", true);
    
        // Establecer una función que se ejecutará cuando la solicitud se complete
        xhr.onreadystatechange = function () {
        if (xhr.readyState === 4 && xhr.status === 200) {
            //Recibir JSON
            var petData = JSON.parse(xhr.responseText);
            //Generar PDF
            createPDF(petData[0].folio, petData[0].petName, petData[0].petSex, petData[0].petBreed, petData[0].petColor, petData[0].petPicture, petData[0].ownerName, petData[0].ownerCURP, petData[0].ownerINE, petData[0].ownerColony, petData[0].ownerAddress, petData[0].nombreTS, petData[0].dia, petData[0].mes, petData[0].anio);
        }
        }

        // Establecer el encabezado de la solicitud para enviar datos como un formulario
        xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
        // Enviar la variable al servidor
        xhr.send("folio=" + folio);
        Swal.fire({
            title: "¡El acta ha sido generada!",
            text: "Revisa tus descargas, este proceso puede demorar por tu velocidad de internet o dispositivo.",
            icon: "success"
          });
    }

  }