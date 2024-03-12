document.addEventListener("DOMContentLoaded", function() {
    var upaImage = document.getElementById("upa");
    var debugMenu = document.getElementById("debugMenu");
    var clickCount = 0;
    var timeout;
  
    upaImage.addEventListener("click", function() {
      clickCount++;
  
      clearTimeout(timeout);
  
      timeout = setTimeout(function() {
        clickCount = 0;
      }, 3000); // Reinicia el contador después de 3 segundos
  
      if (clickCount >= 10) {
        debugMenu.style.display = "block"; // Muestra el elemento li
        clickCount = 0; // Reinicia el contador
        upaImage.style.animation = "rotate 2s";
      }
    });
  });
  