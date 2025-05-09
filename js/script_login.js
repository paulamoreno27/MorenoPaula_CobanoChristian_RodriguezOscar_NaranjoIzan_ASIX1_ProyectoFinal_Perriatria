function validateUsuario() {
    const usuario = document.getElementById('usuario');
    const usuarioError = document.getElementById('usuario-error');
    if (usuario.value.trim() === '') {
        usuarioError.textContent = 'El campo Usuario no puede estar vacío.';
        usuarioError.style.color = 'red';
    } else {
        usuarioError.textContent = '';
    }
}

function validateEmail() {
    const email = document.getElementById('email');
    const emailError = document.getElementById('email-error');
    if (email.value.trim() === '') {
        emailError.textContent = 'El campo Email no puede estar vacío.';
        emailError.style.color = 'red';
    } else {
        emailError.textContent = '';
    }
}

function validatePassword() {
    const password = document.getElementById('password');
    const passwordError = document.getElementById('password-error');
    if (password.value.trim() === '') {
        passwordError.textContent = 'El campo Contraseña no puede estar vacío.';
        passwordError.style.color = 'red';
    } else {
        passwordError.textContent = '';
    }
}