// VALIDACIONES DEL LADO DEL CLIENTE

// NOTA: Este archivo valida los formularios antes de enviarlos al servidor
// Verifica campos vacíos y formatos correctos usando expresiones regulares

// Validación del formulario de LOGIN
function validarLogin() {
    // DOM 1: Obtenemos los valores de los campos usando getElementById
    var correo = document.getElementById("correo").value.trim();
    var contrasena = document.getElementById("contrasena").value.trim();
    
    // Verificamos que no estén vacíos
    if (correo === "" || contrasena === "") {
        alert("Por favor completa todos los campos");
        return false; // Detiene el envío del formulario
    }
    
    // Expresión regular para validar formato de correo
    var regexCorreo = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    if (!regexCorreo.test(correo)) {
        alert("Por favor ingresa un correo válido");
        return false;
    }
    
    // Validamos que la contraseña tenga al menos 6 caracteres
    if (contrasena.length < 6) {
        alert("La contraseña debe tener al menos 6 caracteres");
        return false;
    }
    
    return true; // Si todo está bien, permite enviar el formulario
}

// Validación del formulario de REGISTRO
function validarRegistro() {
    // DOM 2: Obtenemos múltiples elementos del formulario
    var nombre = document.getElementById("nombre").value.trim();
    var correo = document.getElementById("correo").value.trim();
    var telefono = document.getElementById("telefono").value.trim();
    var contrasena = document.getElementById("contrasena").value.trim();
    var tipo = document.getElementById("tipo").value;
    
    // Verificamos campos vacíos
    if (nombre === "" || correo === "" || telefono === "" || contrasena === "") {
        alert("Por favor completa todos los campos obligatorios");
        return false;
    }
    
    // Validamos nombre (solo letras y espacios)
    var regexNombre = /^[a-zA-ZáéíóúÁÉÍÓÚñÑ\s]+$/;
    if (!regexNombre.test(nombre)) {
        alert("El nombre solo puede contener letras");
        return false;
    }
    
    // Validamos correo
    var regexCorreo = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    if (!regexCorreo.test(correo)) {
        alert("Por favor ingresa un correo válido");
        return false;
    }
    
    // Validamos teléfono (10 dígitos)
    var regexTelefono = /^\d{10}$/;
    if (!regexTelefono.test(telefono)) {
        alert("El teléfono debe tener 10 dígitos");
        return false;
    }
    
    // Validamos contraseña (mínimo 6 caracteres, al menos 1 número)
    if (contrasena.length < 6) {
        alert("La contraseña debe tener al menos 6 caracteres");
        return false;
    }
    var regexContrasena = /\d/; // Verifica que tenga al menos un número
    if (!regexContrasena.test(contrasena)) {
        alert("La contraseña debe contener al menos un número");
        return false;
    }
    
    // Validamos campos específicos según el tipo de usuario
    if (tipo === "participante") {
        var institucion = document.getElementById("institucion").value.trim();
        if (institucion === "") {
            alert("Por favor ingresa tu institución");
            return false;
        }
    } else if (tipo === "ponente") {
        var titulo = document.getElementById("titulo").value.trim();
        var resumen = document.getElementById("resumen").value.trim();
        var area = document.getElementById("area").value.trim();
        
        if (titulo === "" || resumen === "" || area === "") {
            alert("Por favor completa todos los campos de ponente");
            return false;
        }
    }
    
    return true;
}

// DOM 3: Función para mostrar/ocultar campos según el tipo de usuario
// Manipula el DOM cambiando estilos dinámicamente
function mostrarCampos() {
    var tipo = document.getElementById("tipo").value;
    var camposParticipante = document.getElementById("campos-participante");
    var camposPonente = document.getElementById("campos-ponente");
    
    if (tipo === "participante") {
        camposParticipante.style.display = "block";
        camposPonente.style.display = "none";
    } else {
        camposParticipante.style.display = "none";
        camposPonente.style.display = "block";
    }
}
