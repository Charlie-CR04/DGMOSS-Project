document.addEventListener('DOMContentLoaded', function(){
    function enableDisableSearchButton(){
        const select = document.querySelector('#direccion-select');
        const searchButton = document.querySelector('#search-button');

        if(select.value === ""){
            searchButton.disabled = true;
        } else {
            searchButton.disabled = false;
        }
    }

    //Agregar el evento de cambio para habilitar/deshabilitar el botón de búsqueda
    document.querySelector('#direccion-select').addEventListener('change', enableDisableSearchButton);

    //Ejecutar la función al cargar la página para verificar el estado actual
    enableDisableSearchButton();
});
