function resetDB() {
  Swal.fire({
    title: `¿Estás seguro que deseas eliminar reiniciar la base de datos?`,
    html: `Este proceso eliminará todas las actas, usuarios y respaldos del sistema. <br><br><b style="color: #9d2348">¡ESTA ACCIÓN ES IRREVERSIBLE!</b>`,
    color: "#666c6c",
    icon: "warning",
    showCancelButton: true,
    cancelButtonColor: "#1A5C50",
    cancelButtonText: "Cancelar",
    confirmButtonColor: "#9D2348",
    confirmButtonText: "Reestablecer Base de Datos",
    input: "text",
    inputLabel: `Escribe "Deseo restablecer el sistema de la UPA :)" para confirmar.`,
    inputPlaceholder: `No uses comillas, respeta minúsculas y mayúsculas.`,
    inputAttributes: {
      paste: false,
      autocapitalize: "off",
      autocorrect: "off",
    },
    customClass: {
      input: "swalInput",
    },
    preConfirm: (value) => {
      if (value.trim() !== `Deseo restablecer el sistema de la UPA :)`) {
        Swal.showValidationMessage(
          `<span style=" text-align: center;">Debes escribir "Deseo restablecer el sistema de la UPA :)" para iniciar el proceso. <br><b style="color: #9d2348">Recuerda no usar comillas y respetar minúsculas y mayúsculas.</b></span>`
        );
      }
    },
  }).then((result) => {
    if (result.isConfirmed) {
      const loaderURL = "/php/sudo_truncate.php";
      const confirmToken = `kmpgySaEjlidYi4G7t612zZY1QqZXIpWw2A5hboa1zd91ehlGQWVU94zd34UeH3f`;

      let truncateTable = new FormData();
      truncateTable.append("confirmToken", confirmToken);

      fetch(loaderURL, {
        method: "POST",
        body: truncateTable,
      })
        .then((response) => response.json())
        .then((responseData) => {
          let queryStatus = responseData.queryStatus;
          let errorMsg = responseData.errorMsg;
          let mP = responseData.mP;
          let b_mP = responseData.b_mP;
          let users = responseData.users;
          let resetUsers = responseData.resetUsers;
          let warning = responseData.warning;

          if (queryStatus === false) {
            Swal.fire({
              title: "¡Error al realizar el proceso!",
              text: errorMsg,
              icon: "error",
            });
          }

          if (queryStatus === true) {
            const downloadLink = document.createElement("a");
            const currentDate = new Date();
            const formattedDate = currentDate
              .toLocaleString("es-ES", {
                day: "2-digit",
                month: "2-digit",
                year: "numeric",
                hour: "2-digit",
                minute: "2-digit",
              })
              .replace(/\//g, "-")
              .replace(",", ""); // Formateamos la fecha según el formato deseado
            downloadLink.href = "/views/sudo/log";
            downloadLink.download = `Log_Restablecimiento_UPA_${formattedDate}.txt`; // Nombre del archivo con la fecha actual

            const listHTML = `<ul>
                      <li>${mP}</li>
                      <li>${b_mP}</li>
                      <li>${users}</li>
                      <li>${resetUsers}</li>
                    </ul>`;

            Swal.fire({
              title: "¡La base de datos fue reiniciada!",
              html: listHTML,
              icon: "success",
              willClose: () => {
                downloadLink.click();
                deleteLog();
                if (warning === true) {
                  Swal.fire({
                    title: "¡ADVERTENCIA!",
                    html: `Se ha reseteado la base de datos, sin embargo no se han podido reestablecer las contraseñas de los administradores de default (sistemavach, admin y UPAVACH). <br><br> <b style="color: #ba1934">"Reestablece INMEDIATAMENTE la base de datos manualmente, usa el script en /assets/database/upa.sql"</b>`,
                    icon: "warning",
                  });
                }
              },
            });
          }
        })
        .catch((err) => console.log(err));
    }
  });
}

function deleteLog() {
  setTimeout(() => {
    const loaderURL = "/php/sudo_delLog.php";
    fetch(loaderURL, {
      method: "POST",
    })
      .then((response) => response.json())
      .then((deleteResult) => {
        let logDel = deleteResult;
        //console.log(logDel);
      });
  }, 1500);
}
