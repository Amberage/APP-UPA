let actualPage = 1;

getData(actualPage);

document.getElementById("searchData").addEventListener("keyup", function() { getData(1) });
document.getElementById("numRegisters").addEventListener("change", function() { getData(actualPage) }, false);

function getData(pagina) {
  if (pagina != null) {
    actualPage = pagina;
  }

  let SearchData = document.getElementById("searchData").value;
  let numRegisters = document.getElementById("numRegisters").value;

  let contentTable = document.getElementById("contentTable");
  let loaderURL = "/php/adm_viewTS.php";
  //Datos que se van a enviar
  let formData = new FormData();
  //formData.append("POST en el PHP", variable);
  formData.append("searchData", SearchData);
  formData.append("numRegisters", numRegisters);
  formData.append("actualPage", actualPage);
  //Enviarlos via post, despues la respuesta del PHP se imprime en el HTML
  fetch(loaderURL, {
    method: "POST",
    body: formData,
  })
    .then((response) => response.json())
    .then((data) => {
      contentTable.innerHTML = data.table;
      document.getElementById("lbl-total").innerHTML = data.totalFiltro;
      document.getElementById("nav-paginacion").innerHTML = data.paginacion;
    })
    .catch((err) => console.log(err));
}

function editTS(idTS) {
  window.location.href = '/views/admin/editTS.php?idTS=' + idTS;
}

function deleteTS(id) {
  let deleteURL = "/php/adm_deleteTS.php";
  let nameTS = "name"+id.toString();
  let deleteName = document.getElementById(nameTS).textContent;

  Swal.fire({
    title: `¿Estás seguro que deseas eliminar a ${deleteName}?`,
    html: `Si ${deleteName} tiene actas registradas, estas pasarán a nombre de <b style="color: #1A5C50">"UPA de Valle de Chalco Solidaridad"</b> y solo serán visibles desde los perfiles de administración.<br><br><b style="color: #9d2348">¡ESTA ACCIÓN ES IRREVERSIBLE!</b>`,
    color: "#666c6c",
    icon: "warning",
    showCancelButton: true,
    cancelButtonColor: "#1A5C50",
    cancelButtonText: "Cancelar",
    confirmButtonColor: "#9D2348",
    confirmButtonText: "Eliminar Acta",
    input: 'text',
    inputLabel: `Escribe "Eliminar a ${deleteName}" para confirmar.`,
    inputPlaceholder: `Escribe "Eliminar a ${deleteName}" sin comillas.`,
    inputAttributes: {
      autocapitalize: 'off',
      autocorrect: 'off'
    },
    customClass: {
      input: 'swalInput'
    },
    preConfirm: (value) => {
    if (value.trim().toLowerCase() !== `eliminar a ${deleteName}`.toLowerCase()) {
        Swal.showValidationMessage(`<span style=" text-align: center;">Debes escribir "Eliminar a ${deleteName}" para confirmar. <br><b style="color: #9d2348">Recuerda no usar comillas.</b></span>`);
      }
    }
  }).then((result) => {
    if (result.isConfirmed) {
      //Datos que se van a enviar
      let delTS = new FormData();
      delTS.append("idTS", id);
      //Enviarlos via post, despues la respuesta del PHP se imprime en el HTML
      fetch(deleteURL, {
        method: "POST",
        body: delTS,
      })
        .then((response) => response.json())
        .then((delResponse) => {
            let message = delResponse.message;
            let queryResult = delResponse.queryResult;

            if(queryResult === true) {
                Swal.fire({
                    color: "#666c6c",
                    title: "¡Usuario Eliminado!",
                    text: message,
                    icon: "success",
                    confirmButtonColor: "#1A5C50",
                  });
                  getData(actualPage);
            } else {
                Swal.fire({
                    color: "#666c6c",
                    title: "ERROR",
                    text: message,
                    icon: "error",
                    confirmButtonColor: "#1A5C50",
                  });
            }
        })
        .catch((err) => console.log(err));
    }
  });
}