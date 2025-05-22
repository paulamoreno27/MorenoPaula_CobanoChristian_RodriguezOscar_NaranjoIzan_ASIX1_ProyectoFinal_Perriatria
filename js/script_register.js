// Función para cambiar el color del mensaje de error a rojo
function mostrarError(elemento, mensaje) {
    elemento.textContent = mensaje;
    elemento.style.color = "red";  // Cambia el color a rojo
}

function limpiarError(id, inputId) {
    document.getElementById(id).innerText = "";
    document.getElementById(inputId).style.backgroundColor = "rgba(105, 227, 134, 0.44)"; // Verde
}
// Función para validar el usuario
function validarUsuario() {
    let usuario = document.getElementById("usuario").value.trim();
    let errorUsuario = document.getElementById("error_usuario");

    if (usuario.length < 3) {
        mostrarError(errorUsuario, "El usuario debe tener al menos 3 caracteres.");
        return false;
    }

    errorUsuario.textContent = "";
    return true;
}

// Función para validar el email
function validarEmail() {
    let email = document.getElementById("email").value.trim();
    let errorMensaje = document.getElementById("error_email");

    // Expresión regular para validar email
    let emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;

    errorMensaje.textContent = "";

    if (email.length === 0) {
        mostrarError(errorMensaje, "El campo no puede estar vacío.");
        return false;
    }
    if (!emailRegex.test(email)) {
        mostrarError(errorMensaje, "El formato del email no es válido.");
        return false;
    }

    errorMensaje.textContent = "";
    return true;
}

// Función para validar la contraseña
function validarPassword() {
    let password = document.getElementById("password").value.trim();
    let errorPassword = document.getElementById("error_password");

    if (password.length < 8) {
        mostrarError(errorPassword, "La contraseña debe tener al menos 8 caracteres.");
        return false;
    }
    if (password === password.toLowerCase()) {
        alert("La contraseña debe contener al menos una letra mayúscula.");
        return false;
    }
    errorPassword.textContent = "";
    return true;
}

// Función para validar la confirmación de contraseña
function validarConfirmPassword() {
    let confirmPassword = document.getElementById("confirm_password").value.trim();
    let errorConfirmPassword = document.getElementById("error_confirm_password");
    let password = document.getElementById("password").value.trim();

    if (confirmPassword !== password) {
        mostrarError(errorConfirmPassword, "Las contraseñas no coinciden.");
        return false;
    }
    errorConfirmPassword.textContent = "";
    return true;
}

// Función para validar el teléfono
function validarTelefono() {
    let telefono = document.getElementById("telefono").value.trim();
    let errorTelefono = document.getElementById("error_telefono");

    // Teléfono: exactamente 9 dígitos
    let telefonoRegex = /^\d{9}$/;

    errorTelefono.textContent = "";

    if (telefono.length === 0) {
        mostrarError(errorTelefono, "El campo no puede estar vacío.");
        return false;
    }
    if (!telefonoRegex.test(telefono)) {
        mostrarError(errorTelefono, "El teléfono debe tener exactamente 9 dígitos numéricos.");
        return false;
    }

    return true;
}

// Función para validar la dirección
function validarDireccion() {
    let direccion = document.getElementById("direccion").value.trim();
    let errorDireccion = document.getElementById("error_direccion");

    errorDireccion.textContent = "";

    if (direccion.length < 5) {
        mostrarError(errorDireccion, "La dirección debe tener al menos 5 caracteres.");
        document.getElementById("direccion").style.backgroundColor = "rgba(227, 105, 105, 0.44)";
        return false;
    }

    return true;
}
