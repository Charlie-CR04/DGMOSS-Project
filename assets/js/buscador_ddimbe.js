document.addEventListener("DOMContentLoaded",function(){
    const input = document.getElementById('input-busqueda-ddimbe');
    const contenedor = document.getElementById('resultados-busqueda-ddimbe');
    input.addEventListener('keyup', function () {
        const query = input.value.trim();

        if(query.length < 2){
            contenedor.innerHTML = '';
            return;
        }

        fetch(`/dgmoss-project/direccion_3/buscador_ddimbe.php?q=${encodeURIComponent(query)}`)
            .then(response => response.text())
            .then(html => {
                contenedor.innerHTML = html;
            })
            .catch(error => {
                contenedor.innerHTML = '<p>Error al buscar documentos.</p>';
                console.error(error);
            });
    });
});