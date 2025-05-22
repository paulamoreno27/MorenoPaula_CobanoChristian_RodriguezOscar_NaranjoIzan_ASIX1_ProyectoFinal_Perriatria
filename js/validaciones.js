/* Login */
function mostrarError(id, mensaje) 
{
    document.getElementById(id).innerText = mensaje;
    document.getElementById(id).style.color = "red";
}

function limpiarError(id) 
{
    document.getElementById(id).innerText = "";
}

/*Login Usuario*/
function valLogUsuario()
{
    let usuario = document.getElementById("usuario").value;
    if(usuario == "") 
    {
        mostrarError("nombreError", "Este campo es obligatorio");
        document.getElementById("usuario").style.backgroundColor = "rgba(227, 105, 105, 0.44)";
    }else if(usuario.length < 3) 
    {
        mostrarError("nombreError", "Tiene que tener al menos 3 caracteres");
        document.getElementById("usuario").style.backgroundColor = "rgba(227, 105, 105, 0.44)";
    }
    else
    {
        document.getElementById("usuario").style.backgroundColor = "rgba(105, 227, 134, 0.44)";
        limpiarError("nombreError");
    }
}

function valLogPassword()
{
    let passUser = document.getElementById("password").value;
    let regex = /^(?=.*[A-Z])(?=.*\d).{8,}$/;
    if(passUser == "") 
    {
        mostrarError("passwordError", "La contraseña es obligatoria");
        document.getElementById("password").style.backgroundColor = "rgba(227, 105, 105, 0.44)";
    }
    else if(!regex.test(passUser))
    {
        mostrarError("passwordError", "Mínimo 8 caracteres, 1 número y 1 mayúscula");
        document.getElementById("password").style.backgroundColor = "rgba(227, 105, 105, 0.44)";
    }
    else 
    {
        document.getElementById("password").style.backgroundColor = "rgba(105, 227, 134, 0.44)";
        limpiarError("passwordError");
    }
}

function valLogEmail()
{
    let email = document.getElementById("email").value;
    let regex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/; // Expresión regular para validar email
    if(email == "") 
    {
        mostrarError("emailError", "El email es obligatorio");
        document.getElementById("email").style.backgroundColor = "rgba(227, 105, 105, 0.44)";
    }
    else if(!regex.test(email))
    {
        mostrarError("emailError", "Introduce un email válido");
        document.getElementById("email").style.backgroundColor = "rgba(227, 105, 105, 0.44)";
    }
    else 
    {
        document.getElementById("email").style.backgroundColor = "rgba(105, 227, 134, 0.44)";
        limpiarError("emailError");
    }
}

/*--------------------------------------------------------------------------------------------------------*/
/* Registro Usuario*/
function valUsuario()
{
    let usuario = document.getElementById("usuario").value;
    if(usuario == "") 
    {
        mostrarError("error_usuario", "Este campo es obligatorio");
        document.getElementById("usuario").style.backgroundColor = "rgba(227, 105, 105, 0.44)";
    }else if(usuario.length < 3) 
    {
        mostrarError("error_usuario", "Tiene que tener al menos 3 caracteres");
        document.getElementById("usuario").style.backgroundColor = "rgba(227, 105, 105, 0.44)";
    }
    else
    {
        document.getElementById("usuario").style.backgroundColor = "rgba(105, 227, 134, 0.44)";
        limpiarError("error_usuario");
    }
}


// Función para validar el email
function valEmail()
{
    let email = document.getElementById("email").value;
    let regex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/; // Expresión regular para validar email
    if(email == "") 
    {
        mostrarError("error_email", "El email es obligatorio");
        document.getElementById("email").style.backgroundColor = "rgba(227, 105, 105, 0.44)";
    }
    else if(!regex.test(email))
    {
        mostrarError("error_email", "Introduce un email válido");
        document.getElementById("email").style.backgroundColor = "rgba(227, 105, 105, 0.44)";
    }
    else 
    {
        document.getElementById("email").style.backgroundColor = "rgba(105, 227, 134, 0.44)";
        limpiarError("error_email");
    }
}

// Función para validar la contraseña
function valPassword()
{
    let passUser = document.getElementById("password").value;
    let regex = /^(?=.*[A-Z])(?=.*\d).{8,}$/;
    if(passUser == "") 
    {
        mostrarError("error_password", "La contraseña es obligatoria");
        document.getElementById("password").style.backgroundColor = "rgba(227, 105, 105, 0.44)";
    }
    else if(!regex.test(passUser))
    {
        mostrarError("error_password", "Mínimo 8 caracteres, 1 número y 1 mayúscula");
        document.getElementById("password").style.backgroundColor = "rgba(227, 105, 105, 0.44)";
    }
    else 
    {
        document.getElementById("password").style.backgroundColor = "rgba(105, 227, 134, 0.44)";
        limpiarError("error_password");
    }
}

// Función para validar la confirmación de contraseña
function validarConfirmPassword() {
    let confirmPassword = document.getElementById("confirm_password").value;
    let errorConfirmPassword = document.getElementById("error_confirm_password");
    let password = document.getElementById("password").value;

    if(confirmPassword == "") 
    {
        mostrarError("error_confirm_password", "Este campo es obligatorio");
        document.getElementById("confirm_password").style.backgroundColor = "rgba(227, 105, 105, 0.44)";
    }

    else if (confirmPassword !== password) {
        mostrarError("error_confirm_password", "Las contraseñas no coinciden.");
        document.getElementById("confirm_password").style.backgroundColor = "rgba(227, 105, 105, 0.44)";
    }
    else 
    {
        document.getElementById("confirm_password").style.backgroundColor = "rgba(105, 227, 134, 0.44)";
        limpiarError("error_confirm_password");
    }
}

// Función para validar el teléfono
function valTelefono() {
    let telefono = document.getElementById("telefono").value;
    let regex = /^\d{9}$/;

    if (telefono.length === 0) {
        mostrarError("error_telefono", "El campo no puede estar vacío.");
        document.getElementById("telefono").style.backgroundColor = "rgba(227, 105, 105, 0.44)"; // Rojo
    }

    else if (!regex.test(telefono)) {
        mostrarError("error_telefono", "El teléfono debe tener exactamente 9 dígitos numéricos.");
        document.getElementById("telefono").style.backgroundColor = "rgba(227, 105, 105, 0.44)"; // Rojo
    } 
    else 
    {
        document.getElementById("telefono").style.backgroundColor = "rgba(105, 227, 134, 0.44)";
        limpiarError("error_telefono");
    }
}

// Función para validar la dirección
function validarDireccion() {
    let direccion = document.getElementById("direccion").value;
    let errorDireccion = document.getElementById("error_direccion");
    errorDireccion.textContent = "";

    if (direccion.length < 5) {
        mostrarError("error_direccion", "La dirección debe tener al menos 5 caracteres.");
        document.getElementById("direccion").style.backgroundColor = "rgba(227, 105, 105, 0.44)";
    }
    else 
    {
        document.getElementById("direccion").style.backgroundColor = "rgba(105, 227, 134, 0.44)";
        limpiarError("error_direccion");       
    }

}
/*---------------------------------------------------------------------------------------------------------------------------------------*/
/*Validación registro Mascotas*/

//Validación Nombre Mascota

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

//Validacion fecha de nacimiento mascota 

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

// validacion sexo mascota 

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

// Validación de la raza de la mascota

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

// Validación de la especie de la mascota

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


// Validación de la veterinario de la mascota 

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

/*-----------------------------------------------------------------------------------------------------------------*/
/* Validacion modificar veterinario*/

// Validar nombre (mínimo 3 caracteres)
function valNombreVet()
{
    let nombre = document.getElementById("nombre_veterinario").value;
    if(nombre == "") 
    {
        mostrarError("nombreError", "Este campo es obligatorio");
        document.getElementById("nombre_veterinario").style.backgroundColor = "rgba(227, 105, 105, 0.44)";
    }else if(nombre.length < 3) 
    {
        mostrarError("nombreError", "Tiene que tener al menos 3 caracteres");
        document.getElementById("nombre_veterinario").style.backgroundColor = "rgba(227, 105, 105, 0.44)";
    }
    else
    {
        document.getElementById("nombre_veterinario").style.backgroundColor = "rgba(105, 227, 134, 0.44)";
        limpiarError("nombreError");
    }
}

// Validar apellidos (mínimo 3 caracteres)
function valApellidosVet() {
    let apellidos = document.getElementById("apellidos_veterinario").value.trim();
    if (apellidos.length < 3) {
        mostrarError("apellidosError", "Los apellidos deben tener al menos 3 caracteres.");
        document.getElementById("apellidos_veterinario").style.backgroundColor = "rgba(227, 105, 105, 0.44)"; // Rojo

    }else 
    {
    document.getElementById("apellidos_veterinario").style.backgroundColor = "rgba(105, 227, 134, 0.44)";
    limpiarError("apellidosError", "apellidos_veterinario");
    }
  
}

// Validar email
function valEmailVet() {
    let email = document.getElementById("email_veterinario").value.trim();
    let regex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    if (email.length === 0) 
    {
        mostrarError("emailError", "El campo no puede estar vacío.");
        document.getElementById("email_veterinario").style.backgroundColor = "rgba(227, 105, 105, 0.44)"; // Rojo
    }
    if (!regex.test(email)) 
    {
        mostrarError("emailError", "El formato del email no es válido.");
        document.getElementById("email_veterinario").style.backgroundColor = "rgba(227, 105, 105, 0.44)"; // Rojo
        
    }
    else
    {
    document.getElementById("email_veterinario").style.backgroundColor = "rgba(105, 227, 134, 0.44)";
    limpiarError("emailError", "email_veterinario");
    }

}

// Validar teléfono (exactamente 9 dígitos)
function valTelefonoVet() {
    let telefono = document.getElementById("telefono_veterinario").value.trim();
    let regex = /^\d{9}$/;
    if (telefono.length === 0) {
        mostrarError("telefonoError", "El campo no puede estar vacío.");
        document.getElementById("telefono_veterinario").style.backgroundColor = "rgba(227, 105, 105, 0.44)"; // Rojo

    }
    else if (!regex.test(telefono)) {
        mostrarError("telefonoError", "El teléfono debe tener exactamente 9 dígitos numéricos.");
        document.getElementById("telefono_veterinario").style.backgroundColor = "rgba(227, 105, 105, 0.44)"; // Rojo

    }
    else
    {
        document.getElementById("telefono_veterinario").style.backgroundColor = "rgba(105, 227, 134, 0.44)";
        limpiarError("telefonoError", "telefono_veterinario");
    }
}

// Validar especialidad (mínimo 3 caracteres)
function valEspecialidadVet() {
    let especialidad = document.getElementById("especialidad_veterinario").value.trim();
    if (especialidad.length < 3) {
        mostrarError("especialidadError", "La especialidad debe tener al menos 3 caracteres.");
        document.getElementById("especialidad_veterinario").style.backgroundColor = "rgba(227, 105, 105, 0.44)"; // Rojo

    }
    else
    {
        document.getElementById("especialidad_veterinario").style.backgroundColor = "rgba(105, 227, 134, 0.44)";
        limpiarError("especialidadError", "especialidad_veterinario");

    }
}

// Validar salario (mayor que 0)
function valSalarioVet() {
    let salario = document.getElementById("salario_veterinario").value.trim();
    if (salario === "" || isNaN(salario) || parseFloat(salario) <= 0) 
    {
        mostrarError("salarioError", "El salario debe ser mayor que 0.");
        document.getElementById("salario_veterinario").style.backgroundColor = "rgba(227, 105, 105, 0.44)"; // Rojo
    }
    else
    {
        document.getElementById("salario_veterinario").style.backgroundColor = "rgba(105, 227, 134, 0.44)";
        limpiarError("salarioError", "salario_veterinario");
    }
}

// Validar contraseña (mínimo 8 caracteres, al menos 1 mayúscula y 1 número)
function valContraseñaVet() {
    let contraseña = document.getElementById("contraseña_veterinario").value.trim();
    let regex = /^(?=.*[A-Z])(?=.*\d).{8,}$/;

    if (contraseña === "") {
        mostrarError("contraseñaError", "La contraseña es obligatoria");
        document.getElementById("contraseña_veterinario").style.backgroundColor = "rgba(227, 105, 105, 0.44)"; // Rojo
    } 
    else if (!regex.test(contraseña)) {
        mostrarError("contraseñaError", "Mínimo 8 caracteres, 1 número y 1 mayúscula");
        document.getElementById("contraseña_veterinario").style.backgroundColor = "rgba(227, 105, 105, 0.44)"; // Rojo
    } 
    else {
        document.getElementById("contraseña_veterinario").style.backgroundColor = "rgba(105, 227, 134, 0.44)"; // Verde
        limpiarError("contraseñaError");
    }
}

/*-----------------------------------------------------------------------------------------------------------------*/
/* Raza.proc.js*/

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
