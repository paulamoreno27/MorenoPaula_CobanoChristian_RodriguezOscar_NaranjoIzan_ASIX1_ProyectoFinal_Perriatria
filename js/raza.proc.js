
//Función para cargar las razas según la especie seleccionada
function cargarRazas() {
    //Seleccionamos los ID de cada campo 
    const especieId = document.getElementById('id_especie_mascota').value;
    const razaSelect = document.getElementById('id_raza_mascota');

    // Limpiar las opciones anteriores
    razaSelect.innerHTML = '<option value="">Seleccione una raza</option>';

    if (razasPorEspecie[especieId]) 
    {
        razasPorEspecie[especieId].forEach(function(raza) {
            const option = document.createElement('option'); // Creamos una opción
            option.value = raza.id;
            option.text = raza.nombre;
            razaSelect.appendChild(option); //appendChild añade la opción al select
        });
    }
}

