// buscador_dpts.js
document.addEventListener('DOMContentLoaded', function () {
    const input = document.getElementById('input-busqueda-dpts');
    const contenedor = document.getElementById('resultados-busqueda-dpts');

    input.addEventListener('keyup', function () {
        const query = input.value.trim();

        if (query.length < 2) {
            contenedor.innerHTML = '';
            return;
        }

        fetch(`/dgmoss-project/direccion_2/buscador_dpts.php?q=${encodeURIComponent(query)}`)
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
