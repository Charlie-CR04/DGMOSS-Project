
//1. Espera que la página cargue completamente
document.addEventListener("DOMContentLoaded", function () {
    //2. Seleccionar el id por su input
    const inputBusqueda_2 = document.getElementById("input-busqueda.dpts");
    //3. Escucha cuando el usuario escribe (evento keyup)
    inputBusqueda_2.addEventListener("keyup", function() {
        const termino_2 = inputBusqueda_2.trim();
    //4. Si el termino es muy corto limpiamos resultados
    if(termino_2.length < 2) {
        document.getElementById("resultados-busqueda-dpts").innerHTML = "";
        return;
    }

    //5. Realizamos una petición al servidor (busqueda_dpts.php)
    fetch(`/dgmoss-project/direccion_2/buscador.php?q=${encodeURIComponent(termino_2)}`)
    .then(response => response.text()) // Esperamos texto (HTML)
    .then(data => {
        document.getElementById("resultados_busqueda-dpts").innerHTML = data;
    })
    .catch(error => {
        console.error("Error al buscar documentos:", error);
    });
    });
});