// JS LIBRE: Reloj en tiempo real

// NOTA: Este es el "JS libre" del proyecto.
// Muestra la hora actual actualizándose cada segundo usando setInterval.
// Se inserta en el footer de las páginas que incluyan este script.

function iniciarReloj() {
    // Buscamos el elemento donde mostraremos la hora
    var contenedor = document.getElementById("reloj");
    if (!contenedor) return; // Si no existe el elemento, no hacemos nada
    
    // Función que actualiza la hora
    function actualizarHora() {
        var ahora = new Date();
        
        // Formateamos horas, minutos y segundos con dos dígitos
        var horas   = String(ahora.getHours()).padStart(2, "0");
        var minutos = String(ahora.getMinutes()).padStart(2, "0");
        var segundos = String(ahora.getSeconds()).padStart(2, "0");
        
        // DOM: Modificamos el texto del elemento con innerHTML
        contenedor.innerHTML = "&#128336; " + horas + ":" + minutos + ":" + segundos;
    }
    
    actualizarHora(); // Llamamos una vez al cargar para que no tarde 1 segundo en aparecer
    setInterval(actualizarHora, 1000); // Actualizamos cada segundo
}

// Ejecutamos cuando el DOM esté listo
document.addEventListener("DOMContentLoaded", iniciarReloj);
