
document.addEventListener("DOMContentLoaded", function () {
  const registroLink = document.getElementById('registroLink');
  
  if (registroLink) {
    registroLink.addEventListener('click', function (event) {
      event.preventDefault(); // Previene comportamiento por defecto

      const loginModalElement = document.getElementById('loginModal');
      const registroModalElement = document.getElementById('registroModal');

      if (loginModalElement && registroModalElement) {
        const loginModal = bootstrap.Modal.getInstance(loginModalElement);
        const registroModal = new bootstrap.Modal(registroModalElement);

        // Cierra login y abre registro después de un corto retraso
        if (loginModal) {
          loginModal.hide();
          setTimeout(() => registroModal.show(), 500);
        } else {
          // Si login no estaba abierto, abre directamente
          registroModal.show();
        }
      }
    });
  }
});

    
document.getElementById("registroForm").addEventListener("submit", function(e) {
  e.preventDefault();
  const formData = new FormData(this);

  fetch("registro.php", {  // Reemplaza por la ruta correcta
    method: "POST",
    body: formData
  })
    .then(response => response.text())  // <- esto convierte el body en texto legible
    .then(data => {
      alert("Respuesta del servidor: " + data);  // <- ahora verás el error o éxito real
    })
    .catch(err => {
      console.error("Error de conexión:", err);
      alert("Error de conexión con el servidor.");
    });
  });