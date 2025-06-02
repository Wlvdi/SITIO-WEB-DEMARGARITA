
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


document.querySelectorAll('a[href="#Productos"]').forEach(link => {
  link.addEventListener('click', function(e) {
    e.preventDefault();
    const header = document.querySelector('header');
    const target = document.getElementById('Productos');
    const headerHeight = header.offsetHeight;
    const targetPosition = target.getBoundingClientRect().top + window.pageYOffset - headerHeight;
    window.scrollTo({ top: targetPosition, behavior: 'smooth' });
  });
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


// funcion mostrar mas para las cartas de productos TORTAS
document.addEventListener("DOMContentLoaded", () => {
  const productos = document.querySelectorAll(".Cartas_productos_TORTAS .Carta_producto");
  const boton = document.getElementById("botonVerMasTorta");

  const mostrarLimite = 5;
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

// funcion mostrar mas para las cartas de productos COCTEL

document.addEventListener("DOMContentLoaded", () => {
  const productos = document.querySelectorAll(".Cartas_productos_COCTEL .Carta_producto");
  const boton = document.getElementById("botonVerMasCoctel");

  const mostrarLimite = 5;
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

//Modal para eleguir una torta personalizada

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


// calendario 3 dias despues
  document.addEventListener("DOMContentLoaded", function () {
    const deliveryDateInput = document.getElementById("deliveryDate");

    // Calcular fecha mínima (3 días después de hoy)
    const today = new Date();
    today.setDate(today.getDate() + 4); // sumar 3 días

    const year = today.getFullYear();
    const month = String(today.getMonth() + 1).padStart(2, '0');
    const day = String(today.getDate()).padStart(2, '0');
    const minDate = `${year}-${month}-${day}`;

    deliveryDateInput.setAttribute("min", minDate);
<<<<<<< HEAD
  });


  document.getElementById("imagen").addEventListener("change", function () {
    const file = this.files[0];
    if (file) {
      const reader = new FileReader();
      reader.onload = function (e) {
        const preview = document.getElementById("preview");
        preview.src = e.target.result;
        preview.style.display = "block";
      };
      reader.readAsDataURL(file);
    }
  });


// funcion para mandar a la base de datos el nuevo producto

// Mostrar productos en secciones con botón Ver más

document.addEventListener("DOMContentLoaded", function () {
  const botonTorta = document.getElementById("botonVerMasTorta");
  const productosTorta = document.querySelectorAll(".Cartas_productos_TORTAS .Carta_producto");
  let mostrarTortas = false;

  function actualizarTortas() {
    productosTorta.forEach((producto, index) => {
      producto.style.display = (!mostrarTortas && index >= 5) ? "none" : "block";
    });
    botonTorta.textContent = mostrarTortas ? "Ver menos" : "Ver más";
  }

  botonTorta.addEventListener("click", function () {
    mostrarTortas = !mostrarTortas;
    actualizarTortas();
  });

  actualizarTortas();
});

document.addEventListener("DOMContentLoaded", function () {
  const botonCoctel = document.getElementById("botonVerMasCoctel");
  const productosCoctel = document.querySelectorAll(".Cartas_productos_COCTEL .Carta_producto");
  let mostrarCocteles = false;

  function actualizarCocteles() {
    productosCoctel.forEach((producto, index) => {
      producto.style.display = (!mostrarCocteles && index >= 5) ? "none" : "block";
    });
    botonCoctel.textContent = mostrarCocteles ? "Ver menos" : "Ver más";
  }

  botonCoctel.addEventListener("click", function () {
    mostrarCocteles = !mostrarCocteles;
    actualizarCocteles();
  });

  actualizarCocteles();
});

// 🧩 Rellenar input oculto "categoria" según botón que abre el modal
const modal = document.getElementById("adminModal");
modal.addEventListener("show.bs.modal", function (event) {
  const boton = event.relatedTarget;
  const categoria = boton && boton.getAttribute("data-categoria") || "";
  document.getElementById("categoriaProducto").value = categoria;
});

// ✅ Enviar nuevo producto al backend y renderizar en sección correspondiente
document.addEventListener("DOMContentLoaded", function () {
  const form = document.getElementById("formNuevoProducto");

  form.addEventListener("submit", function (e) {
    e.preventDefault();
    const formData = new FormData(form);

    fetch("subir_producto.php", {
      method: "POST",
      body: formData
    })
      .then(res => res.json())
      .then(data => {
        console.log("📥 Respuesta del servidor:", data);

        if (data.exito) {
          const carta = document.createElement("div");
          carta.classList.add("Carta_producto");
          carta.innerHTML = `
            <img src="${data.imagen}" alt="${data.nombre}">
            <h2>${data.nombre}</h2>
            <p>${data.descripcion}</p>
            <p>Precio: $${parseInt(data.precio).toLocaleString()}</p>
            <button class="btn">Añadir al carrito</button>
          `;
          carta.style.display = "block";

          if (data.categoria === "torta") {
            document.querySelector(".Cartas_productos_TORTAS").appendChild(carta);
          } else if (data.categoria === "coctel") {
            document.querySelector(".Cartas_productos_COCTEL").appendChild(carta);
          } else {
            console.warn("⚠️ Categoría no reconocida");
          }

          const modalInstance = bootstrap.Modal.getInstance(document.getElementById("adminModal"));
          if (modalInstance) modalInstance.hide();
          form.reset();
        } else {
          alert("❌ Error del servidor: " + data.mensaje);
        }
      })
      .catch(err => {
        console.error("❌ Error en la conexión:", err);
        alert("Error al enviar el formulario.");
      });
  });
});
=======
  });
>>>>>>> 689339ebf5a3104d85e4b1fa510148d139829610
