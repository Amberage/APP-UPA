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
  let loaderURL = "/php/loaderTable.php";

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


/*
* En esta function se manda la solicitud para borrar un acta.
! No se implementó ningun validador, ya que no se considera que este sistema sea objetivo de algun ciberataque, pero de requererirse solo hay
! que llamar a la cookie de inicio de sesión y validar que las cuentas sean de admin o ts, EN EL ESTADO ACTUAL ESTA FUNCIONALIDAD ES EXPLOTABLE!
*/

function deletePet(folio) {
  let deleteURL = "/php/eliminarActa.php";
  Swal.fire({
    title: "¿Estas seguro que deseas eliminar el acta número " + folio + "?",
    text: "¡Esta acción es irreversible!",
    color: "#666c6c",
    icon: "warning",
    showCancelButton: true,
    cancelButtonColor: "#1A5C50",
    cancelButtonText: "Cancelar",
    confirmButtonColor: "#9D2348",
    confirmButtonText: "Eliminar Acta"
  }).then((result) => {
    if (result.isConfirmed) {

      //Datos que se van a enviar
      let delPet = new FormData();
      delPet.append("delFolio", folio);
      //Enviarlos via post, despues la respuesta del PHP se imprime en el HTML

      fetch(deleteURL, {
        method: "POST",
        body: delPet,
      })
        .then((response) => response.json())
        .then((delResponse) => {
          Swal.fire({
            color: "#666c6c",
            title: "Acta Eliminada",
            text: delResponse,
            icon: "success",
            confirmButtonColor: "#1A5C50",
          });
          getData(actualPage);
        })
        .catch((err) => console.log(err));

    }
  });
}