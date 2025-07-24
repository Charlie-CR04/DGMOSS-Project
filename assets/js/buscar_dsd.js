// buscar_dsd.js

// 1. Espera a que la página cargue completamente
document.addEventListener("DOMContentLoaded", function () {
  // 2. Selecciona el input por su ID
  const inputBusqueda = document.getElementById("input-busqueda-dsd");

  // 3. Escucha cuando el usuario escribe (evento keyup)
  inputBusqueda.addEventListener("keyup", function () {
    const termino = inputBusqueda.value.trim();

    // 4. Si el término es muy corto, limpiamos resultados
    if (termino.length < 2) {
      document.getElementById("resultados-busqueda-dsd").innerHTML = "";
      return;
    }

    // 5. Realizamos una petición al servidor (busqueda_dsd.php)
    fetch(`/dgmoss-project/direccion_5/busqueda_dsd.php?q=${encodeURIComponent(termino)}`)
      .then(response => response.text()) // Esperamos texto (HTML)
      .then(data => {
        document.getElementById("resultados-busqueda-dsd").innerHTML = data;
      })
      .catch(error => {
        console.error("Error al buscar documentos:", error);
      });
  });
});
