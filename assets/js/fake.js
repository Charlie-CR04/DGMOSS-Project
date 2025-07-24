function buscarDocumentos() {
    
    const input = document.getElementById("busqueda");
    const query = input.value.trim();
    const resultadosDiv = document.getElementById("resultados-busqueda");

    if(query === "") {
        resultadosDiv.innerHTML = "";
        return;
    }

    fetch("/dgmoss-project/direcciones/direccion_5/buscar.php",{
        method:"POST",
        headers: {
            "Content-Type": "application/x-www-form-urlencoded",
        },
        body: "query=" + encodeURIComponent(query),
    })
    .then(response => response.text())
    .then(data => {
        resultadosDiv.innerHTML = data;
    })
    .catch(error => {
        resultadosDiv.innerHTML = "<p>Error al buscar documentos.</p>";
        console.error("Error en la busqueda: ", error);
    });
}