// Crear el mapa
    var map = L.map('map').setView([19.3992942, -99.1740027], 16);

    // Usar OpenStreetMap como capa base
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
    }).addTo(map);

    // Crear el marcador
    var marker = L.marker([19.3992942, -99.1740027]).addTo(map);

    // Añadir un popup con el nombre del lugar
    marker.bindPopup("<b>Dirección General de Modernización del Sector Salud</b><br>C. Agrarismo 227, Escandón II Secc,<br>Miguel Hidalgo, 11800 Ciudad de México, CDMX").openPopup();
