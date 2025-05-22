function mostrarError(id, mensaje) 
{
    document.getElementById(id).innerText = mensaje;
    document.getElementById(id).style.color = "red";
}

function limpiarError(id) 
{
    document.getElementById(id).innerText = "";
}

function valNombreMascota()
{
    let nombre = document.getElementById("nombre").value;
    let regex = /^[a-zA-Z\s]+$/;

    if (nombre.length < 3 || nombre.length > 20) 
    {
        mostrarError("nombreError", "El nombre debe tener entre 3 y 20 caracteres.");
        document.getElementById("nombre").style.backgroundColor = "rgba(227, 105, 105, 0.44)";
    } 
    else if (!regex.test(nombre)) 
    {
        mostrarError("nombreError", "El nombre solo puede contener letras y espacios.");
        document.getElementById("nombre").style.backgroundColor = "rgba(227, 105, 105, 0.44)";
    } 
    else 
    {
        document.getElementById("nombre").style.backgroundColor = "rgba(105, 227, 134, 0.44)";
        limpiarError("nombreError");
    }
}

function valNacimientoMascota()
{
    let nacimiento = document.getElementById("fecha_nacimiento").value;
    let fechaActual = new Date();
    let fechaNacimiento = new Date(nacimiento);
    let edad = fechaActual.getFullYear() - fechaNacimiento.getFullYear();
    let mes = fechaActual.getMonth() - fechaNacimiento.getMonth();
    if (mes < 0 || (mes === 0 && fechaActual.getDate() < fechaNacimiento.getDate())) 
    {
        edad--;
    }
    if (edad < 0) 
    {
        mostrarError("fechaNacimientoError", "La fecha de nacimiento no puede ser futura.");
        document.getElementById("fecha_nacimiento").style.backgroundColor = "rgba(227, 105, 105, 0.44)";
    }
    else if (nacimiento === "") 
    {
        mostrarError("fechaNacimientoError", "La fecha de nacimiento es obligatoria.");
        document.getElementById("fecha_nacimiento").style.backgroundColor = "rgba(227, 105, 105, 0.44)";
    }
    else
    {
        document.getElementById("fecha_nacimiento").style.backgroundColor = "rgba(105, 227, 134, 0.44)";
        limpiarError("fechaNacimientoError");
    }
}

function valSexoMascota()
{
    let sexo = document.getElementById("sexo").value;
    if (sexo === "") 
    {
        mostrarError("sexoError", "El sexo es obligatorio.");
        document.getElementById("sexo").style.backgroundColor = "rgba(227, 105, 105, 0.44)";
    } 
    else 
    {
        document.getElementById("sexo").style.backgroundColor = "rgba(105, 227, 134, 0.44)";
        limpiarError("sexoError");
    }
}

function valRazaMascota()
{
    let raza = document.getElementById("id_raza_mascota").value;
    if (raza === "") 
    {
        mostrarError("razaError", "La raza es obligatoria.");
        document.getElementById("id_raza_mascota").style.backgroundColor = "rgba(227, 105, 105, 0.44)";
    } 
    else 
    {
        document.getElementById("id_raza_mascota").style.backgroundColor = "rgba(105, 227, 134, 0.44)";
        limpiarError("razaError");
    }
}

function valEspecieMascota()
{
    let especie = document.getElementById("id_especie_mascota").value;
    if (especie === "") {
        mostrarError("especie_mascota_error", "La especie es obligatoria.");
        document.getElementById("id_especie_mascota").style.backgroundColor = "rgba(227, 105, 105, 0.44)";
    } 
    else {
        document.getElementById("id_especie_mascota").style.backgroundColor = "rgba(105, 227, 134, 0.44)";
        limpiarError("especie_mascota_error");
    }
}

function valVeterinario() {
    let veterinario = document.getElementById("veterinario").value;

    if (veterinario === "") {
        mostrarError("veterinarioError", "Debe seleccionar un veterinario obligatoriamente.");
        document.getElementById("veterinario").style.backgroundColor = "rgba(227, 105, 105, 0.44)";
    } else {
        document.getElementById("veterinario").style.backgroundColor = "rgba(105, 227, 134, 0.44)";
        limpiarError("veterinarioError");
    }
}
