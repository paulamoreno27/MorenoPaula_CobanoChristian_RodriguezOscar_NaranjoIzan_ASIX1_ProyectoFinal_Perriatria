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

