document.getElementById("taxiForm").addEventListener("submit", function(event) {
    event.preventDefault(); // Evita que el formulario se envíe

    const pickupLocation = document.getElementById("pickup").value;
    const destination = document.getElementById("destination").value;

    if (pickupLocation && destination) {
        // Mostrar el popup de confirmación
        document.getElementById("popup").classList.remove("hidden");
    } else {
        alert("Por favor completa todos los campos.");
    }
});

// Cierra el popup al hacer clic en el botón "Cerrar"
document.getElementById("closePopup").addEventListener("click", function() {
    document.getElementById("popup").classList.add("hidden");
});