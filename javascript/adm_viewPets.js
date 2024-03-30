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
  let loaderURL = "/php/adm_viewPet.php";

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
      document.getElementById("lbl-total").innerHTML = data.totalFiltro + ' actas encontradas';
      document.getElementById("nav-paginacion").innerHTML = data.paginacion;
    })
    .catch((err) => console.log(err));
}

function editPet(folio) {
  window.location.href = '/views/admin/editPets.php?petID=' + folio;
}

function restorePet(folioRestore) {
  let deleteURL = "/php/adm_restorePet.php";
  //Datos que se van a enviar
  let restoreID = new FormData();
  restoreID.append("folioRestore", folioRestore);
  fetch(deleteURL, {
    method: "POST",
    body: restoreID,
  })
    .then((response) => response.json())
    .then((responseData) => {
        let message = responseData.message;
        let queryResult = responseData.queryResult;

        if(queryResult === true) {
            Swal.fire({
                color: "#666c6c",
                title: "¡Acta Restaurada!",
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