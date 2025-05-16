function mostrarError(id, mensaje){
    
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
    let raza = document.getElementById("raza").value;
    if (raza === "") 
    {
        mostrarError("razaError", "La raza es obligatoria.");
        document.getElementById("raza").style.backgroundColor = "rgba(227, 105, 105, 0.44)";
    } 
    else 
    {
        document.getElementById("raza").style.backgroundColor = "rgba(105, 227, 134, 0.44)";
        limpiarError("razaError");
    }
}
function valEspecieMascota()
{
    let especie = document.getElementById("especie").value;
    if (especie === "") 
    {
        mostrarError("especieError", "La especie es obligatoria.");
        document.getElementById("especie").style.backgroundColor = "rgba(227, 105, 105, 0.44)";
    } 
    else 
    {
        document.getElementById("especie").style.backgroundColor = "rgba(105, 227, 134, 0.44)";
        limpiarError("especieError");
    }
}