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
      document.getElementById("lbl-total").innerHTML = 'Mostrando ' + data.totalFiltro + ' de ' + data.totalRegistros;
      document.getElementById("nav-paginacion").innerHTML = data.paginacion;
    })
    .catch((err) => console.log(err));
}
