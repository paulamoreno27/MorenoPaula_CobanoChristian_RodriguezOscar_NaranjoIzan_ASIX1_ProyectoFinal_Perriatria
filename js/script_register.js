function validarUsuario() {


    let usuario = document.getElementById("usuario").value.trim();
    let errorUsuario = document.getElementById("error_usuario");


    if (usuario.length < 3) {


        errorUsuario.textContent = "El usuario debe tener al menos 3 caracteres.";
        return false;


    }


    errorUsuario.textContent = "";
   
    return true;


}
function validarEmail() {
   
    let email = document.getElementById("email").value.trim();
    let errorMensaje = document.getElementById("error_email");




    // Expresión regular para validar email
    let emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;


    errorMensaje.textContent = "";


    if (email.length === 0) {
        errorMensaje.textContent = "El campo no puede estar vacío.";
        return false;
    }
    if (!emailRegex.test(email)) {
        errorMensaje.textContent = "El formato del email no es válido.";
        return false;
    }


    errorMensaje.textContent = "";
   
    return true;
}
function validarPassword() {


    let password = document.getElementById("password").value.trim();
    let errorPassword = document.getElementById("error_password");


    if (password.length < 8) {
       
        errorPassword.textContent = "La contraseña debe tener al menos 8 caracteres.";
        return false;


    }
    if (password === password.toLowerCase()) {


        alert("La contraseña debe contener al menos una letra mayúscula.");
        return false;
    }


    errorPassword.textContent = "";
   
    return true;


}
function validarConfirmPassword() {


    let confirmPassword = document.getElementById("confirm_password").value.trim();
    let errorConfirmPassword = document.getElementById("error_confirm_password");
    let password = document.getElementById("password").value.trim();


   
    if (confirmPassword !== password) {


        errorConfirmPassword.textContent = "Las contraseñas no coinciden.";
        return false;
    }


    errorConfirmPassword.textContent = "";
   
    return true;


}
