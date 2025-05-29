
// Este script maneja la apertura del modal de registro desde el enlace en el modal de login

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



// Este script maneja el envío del formulario de registro y muestra la respuesta del servidor

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

// funcion mostrar mas para las cartas de productos

document.addEventListener("DOMContentLoaded", () => {
  const productos = document.querySelectorAll(".Carta_producto");
  const boton = document.getElementById("botonVerMas");

  const mostrarLimite = 6;
  let mostrandoTodos = false;

  function actualizarVista() {
    productos.forEach((producto, index) => {
      if (!mostrandoTodos && index >= mostrarLimite) {
        producto.style.display = "none";
      } else {
        producto.style.display = "block";
      }
    });

    if (productos.length > mostrarLimite) {
      boton.style.display = "block";
      boton.textContent = mostrandoTodos ? "Ver menos" : "Ver más";
    } else {
      boton.style.display = "none";
    }
  }

  // Evento del botón para alternar
  boton.addEventListener("click", () => {
    mostrandoTodos = !mostrandoTodos;
    actualizarVista();
  });

  // Inicializar vista
  actualizarVista();
});