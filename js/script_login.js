/*function valUsuario() {
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
*/

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
        mostrarError("nombreError", "El nombre de usuario es obligatorio");
        document.getElementById("usuario").style.backgroundColor = "rgba(227, 105, 105, 0.44)";
    }
    else
    {
        document.getElementById("usuario").style.backgroundColor = "rgba(105, 227, 134, 0.44)";
        limpiarError("nombreError");
    }
}

function valPassword()
{
    let passUser = document.getElementById("password").value;
    if(passUser == "") 
    {
        mostrarError("passwordError", "La contraseña es obligatoria");
        document.getElementById("password").style.backgroundColor = "rgba(227, 105, 105, 0.44)";
    }
    else 
    {
        document.getElementById("password").style.backgroundColor = "rgba(105, 227, 134, 0.44)";
        limpiarError("passwordError");
    }
}

function valEmail()
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

