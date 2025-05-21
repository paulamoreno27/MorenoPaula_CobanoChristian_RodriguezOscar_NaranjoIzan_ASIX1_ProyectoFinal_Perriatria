// Función para mostrar errores en rojo
function mostrarError(id, mensaje) {
    let elemento = document.getElementById(id);
    elemento.innerText = mensaje;
    elemento.style.color = "red";
}

// Función para limpiar errores y marcar el campo como válido
function limpiarError(id, inputId) {
    document.getElementById(id).innerText = "";
    document.getElementById(inputId).style.backgroundColor = "rgba(105, 227, 134, 0.44)"; // Verde
}

// Validar nombre (mínimo 3 caracteres)
function valNombreVet() {
    let nombre = document.getElementById("nombre_veterinario").value.trim();
    if (nombre.length < 3) {
        mostrarError("nombreError", "El nombre debe tener al menos 3 caracteres.");
        document.getElementById("nombre_veterinario").style.backgroundColor = "rgba(227, 105, 105, 0.44)"; // Rojo
        return false;
    }
    limpiarError("nombreError", "nombre_veterinario");
    return true;
}

// Validar apellidos (mínimo 3 caracteres)
function valApellidosVet() {
    let apellidos = document.getElementById("apellidos_veterinario").value.trim();
    if (apellidos.length < 3) {
        mostrarError("apellidosError", "Los apellidos deben tener al menos 3 caracteres.");
        document.getElementById("apellidos_veterinario").style.backgroundColor = "rgba(227, 105, 105, 0.44)"; // Rojo
        return false;
    }
    limpiarError("apellidosError", "apellidos_veterinario");
    return true;
}

// Validar email
function valEmailVet() {
    let email = document.getElementById("email_veterinario").value.trim();
    let regex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    if (email.length === 0) {
        mostrarError("emailError", "El campo no puede estar vacío.");
        document.getElementById("email_veterinario").style.backgroundColor = "rgba(227, 105, 105, 0.44)"; // Rojo
        return false;
    }
    if (!regex.test(email)) {
        mostrarError("emailError", "El formato del email no es válido.");
        document.getElementById("email_veterinario").style.backgroundColor = "rgba(227, 105, 105, 0.44)"; // Rojo
        return false;
    }
    limpiarError("emailError", "email_veterinario");
    return true;
}

// Validar teléfono (exactamente 9 dígitos)
function valTelefonoVet() {
    let telefono = document.getElementById("telefono_veterinario").value.trim();
    let regex = /^\d{9}$/;
    if (telefono.length === 0) {
        mostrarError("telefonoError", "El campo no puede estar vacío.");
        document.getElementById("telefono_veterinario").style.backgroundColor = "rgba(227, 105, 105, 0.44)"; // Rojo
        return false;
    }
    if (!regex.test(telefono)) {
        mostrarError("telefonoError", "El teléfono debe tener exactamente 9 dígitos numéricos.");
        document.getElementById("telefono_veterinario").style.backgroundColor = "rgba(227, 105, 105, 0.44)"; // Rojo
        return false;
    }
    limpiarError("telefonoError", "telefono_veterinario");
    return true;
}

// Validar especialidad (mínimo 3 caracteres)
function valEspecialidadVet() {
    let especialidad = document.getElementById("especialidad_veterinario").value.trim();
    if (especialidad.length < 3) {
        mostrarError("especialidadError", "La especialidad debe tener al menos 3 caracteres.");
        document.getElementById("especialidad_veterinario").style.backgroundColor = "rgba(227, 105, 105, 0.44)"; // Rojo
        return false;
    }
    limpiarError("especialidadError", "especialidad_veterinario");
    return true;
}

// Validar salario (mayor que 0)
function valSalarioVet() {
    let salario = document.getElementById("salario_veterinario").value.trim();
    if (salario === "" || isNaN(salario) || parseFloat(salario) <= 0) {
        mostrarError("salarioError", "El salario debe ser mayor que 0.");
        document.getElementById("salario_veterinario").style.backgroundColor = "rgba(227, 105, 105, 0.44)"; // Rojo
        return false;
    }
    limpiarError("salarioError", "salario_veterinario");
    return true;
}

// Validar contraseña (mínimo 8 caracteres, al menos 1 mayúscula y 1 número)
function valContraseñaVet() {
    let contraseña = document.getElementById("contraseña_veterinario").value.trim();
    let error = document.getElementById("contraseñaError");

    // Verificar longitud mínima de 8 caracteres
    if (contraseña.length < 8) {
        mostrarError("contraseñaError", "La contraseña debe tener al menos 8 caracteres.");
        document.getElementById("contraseña_veterinario").style.backgroundColor = "rgba(227, 105, 105, 0.44)"; // Rojo
        return false;
    }

    // Verificar que contenga al menos una letra mayúscula
    let tieneMayuscula = false;
    for (let i = 0; i < contraseña.length; i++) {
        if (contraseña[i] === contraseña[i].toUpperCase() && isNaN(contraseña[i])) {
            tieneMayuscula = true;
            break;
        }
    }
    if (!tieneMayuscula) {
        mostrarError("contraseñaError", "La contraseña debe incluir al menos una letra mayúscula.");
        document.getElementById("contraseña_veterinario").style.backgroundColor = "rgba(227, 105, 105, 0.44)"; // Rojo
        return false;
    }

    // Verificar que contenga al menos un número
    let tieneNumero = false;
    for (let i = 0; i < contraseña.length; i++) {
        if (!isNaN(contraseña[i])) {
            tieneNumero = true;
            break;
        }
    }
    if (!tieneNumero) {
        mostrarError("contraseñaError", "La contraseña debe incluir al menos un número.");
        document.getElementById("contraseña_veterinario").style.backgroundColor = "rgba(227, 105, 105, 0.44)"; // Rojo
        return false;
    }

    // Si pasa todas las validaciones
    limpiarError("contraseñaError", "contraseña_veterinario");
    return true;
}
