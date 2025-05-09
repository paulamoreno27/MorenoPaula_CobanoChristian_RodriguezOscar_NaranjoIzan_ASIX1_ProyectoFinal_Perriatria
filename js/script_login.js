function valUsuario() {
    let valor = document.getElementById("nombre").value;
    let error_nombre = document.getElementById("nombreError");
    if (valor == null || valor.length == 0) {
        error_nombre.textContent = "El campo no puede estar vacío.";
        return false;
    } else if (!isNaN(valor)) {
        error_nombre.textContent = "El nombre no puede contener números.";
        return false;
    } else if (valor.length < 3) {
        error_nombre.textContent = "El nombre debe tener al menos 3 caracteres.";
        return false;
    } else {
        error_nombre.textContent = "";
        return true;
    }
}


function valEmail() {
        let valor = document.getElementById("email").value;
        let error_email = document.getElementById("emailError");
        let regex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/; // Expresión regular para validar email
        if (valor == null || valor.length == 0) {
            error_email.textContent = "El campo no puede estar vacío.";
            return false;
        } else if (!regex.test(valor)) {
            error_email.textContent = "Introduce un email válido.";
            return false;
        } else {
            error_email.textContent = "";
            return true;
        }
    }


function valPassword() {
        let valor = document.getElementById("password").value;
        let error_password = document.getElementById("passwordError");
        if (valor == null || valor.length == 0) {
            error_password.textContent = "El campo no puede estar vacío.";
            return false;
        } else if (valor.length < 6) {
            error_password.textContent = "La contraseña debe tener al menos 6 caracteres.";
            return false;
        } else {
            error_password.textContent = "";
            return true;
        }
    }


    // Validar todo el formulario antes de enviarlo
function validarFormulario() {
    let validNombre = valNombre();
    let validEmail = valEmail();
    let validPassword = valPassword();
    return validNombre && validEmail && validPassword;
}
