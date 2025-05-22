function mostrarError(id, mensaje) 
{
    document.getElementById(id).innerText = mensaje;
    document.getElementById(id).style.color = "red";
}

function limpiarError(id) 
{
    document.getElementById(id).innerText = "";
}

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
