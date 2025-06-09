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

//navbar responsive
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

//navbar hambuerguesa
const hamburger = document.getElementById('hamburger');
const navLinks = document.querySelector('nav');

if (hamburger && navLinks) {
  hamburger.addEventListener('click', () => {
    navLinks.classList.toggle('active');
  });
}

// Este script maneja el envío del formulario de registro y muestra la respuesta del servidor
document.addEventListener("DOMContentLoaded", function() {
  const registroForm = document.getElementById("registroForm");
  if (registroForm) {
    registroForm.addEventListener("submit", function(e) {
      e.preventDefault();
      const formData = new FormData(this);

      fetch("registro.php", {
        method: "POST",
        body: formData
      })
        .then(response => response.text())
        .then(data => {
          alert("Respuesta del servidor: " + data);
        })
        .catch(err => {
          console.error("Error de conexión:", err);
          alert("Error de conexión con el servidor.");
        });
    });
  }
});

// funcion mostrar mas para las cartas de productos TORTAS
document.addEventListener("DOMContentLoaded", () => {
  const productos = document.querySelectorAll(".Cartas_productos_TORTAS .Carta_producto");
  const boton = document.getElementById("botonVerMasTorta");

  if (boton && productos.length > 0) {
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
  }
});

// funcion mostrar mas para las cartas de productos COCTEL
document.addEventListener("DOMContentLoaded", () => {
  const productos = document.querySelectorAll(".Cartas_productos_COCTEL .Carta_producto");
  const boton = document.getElementById("botonVerMasCoctel");

  if (boton && productos.length > 0) {
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
  }
});

// calendario 3 dias despues
document.addEventListener("DOMContentLoaded", function () {
  const deliveryDateInput = document.getElementById("deliveryDate");

  if (deliveryDateInput) {
    // Calcular fecha mínima (3 días después de hoy)
    const today = new Date();
    today.setDate(today.getDate() + 4); // sumar 3 días

    const year = today.getFullYear();
    const month = String(today.getMonth() + 1).padStart(2, '0');
    const day = String(today.getDate()).padStart(2, '0');
    const minDate = `${year}-${month}-${day}`;

    deliveryDateInput.setAttribute("min", minDate);
  }
});

// *** PREVISUALIZACIÓN DE IMAGEN - VERSIÓN CORREGIDA ***
document.addEventListener("DOMContentLoaded", function() {
  const imagenInput = document.getElementById("imagen");
  
  if (imagenInput) {
    console.log("✅ Input de imagen encontrado");
    
    imagenInput.addEventListener("change", function(event) {
      console.log("📸 Cambio detectado en input imagen");
      
      const file = event.target.files[0];
      
      // Limpiar preview anterior si existe
      const oldPreview = document.getElementById("preview");
      if (oldPreview) {
        oldPreview.remove();
        console.log("🗑️ Preview anterior eliminado");
      }
      
      if (file) {
        console.log("📁 Archivo seleccionado:", file.name);
        
        // Validar que sea una imagen
        if (!file.type.startsWith('image/')) {
          alert("Por favor selecciona un archivo de imagen válido");
          return;
        }
        
        const reader = new FileReader();
        
        reader.onload = function(e) {
          console.log("📖 Archivo leído exitosamente");
          
          try {
            // Crear elemento de preview
            const preview = document.createElement("img");
            preview.id = "preview";
            preview.src = e.target.result;
            preview.style.cssText = `
              max-width: 200px;
              max-height: 200px;
              margin-top: 10px;
              border-radius: 8px;
              border: 2px solid #ddd;
              display: block;
            `;
            
            // Insertar después del input
            imagenInput.parentNode.insertBefore(preview, imagenInput.nextSibling);
            
            console.log("✅ Preview creado y mostrado");
            
          } catch (error) {
            console.error("❌ Error al crear preview:", error);
          }
        };
        
        reader.onerror = function(error) {
          console.error("❌ Error al leer archivo:", error);
          alert("Error al cargar la imagen");
        };
        
        reader.readAsDataURL(file);
      }
    });
  }
});

// 🧩 Rellenar input oculto "categoria" según botón que abre el modal
document.addEventListener("DOMContentLoaded", function () {
  console.log("🚀 DOM cargado - Configurando modal admin");
  
  const modal = document.getElementById("adminModal");
  
  if (modal) {
    console.log("✅ Modal admin encontrado");
    
    modal.addEventListener("show.bs.modal", function (event) {
      const boton = event.relatedTarget;
      const categoria = boton ? boton.getAttribute("data-categoria") : "";
      
      console.log("🔍 Categoría detectada:", categoria);
      
      const categoriaInput = document.getElementById("categoriaProducto");
      if (categoriaInput && categoria) {
        categoriaInput.value = categoria;
        console.log("✅ Categoría asignada:", categoria);
      } else {
        console.warn("⚠️ No se pudo asignar categoría");
      }
    });
    
    // Limpiar al cerrar modal
    modal.addEventListener("hidden.bs.modal", function() {
      const form = document.getElementById("formNuevoProducto");
      if (form) {
        form.reset();
      }
      
      const preview = document.getElementById("preview");
      if (preview) {
        preview.remove();
      }
    });
  }



  // ✅ Enviar nuevo producto al backend
  const form = document.getElementById("formNuevoProducto");
  
  if (form) {
    console.log("✅ Formulario de nuevo producto encontrado");
    
    form.addEventListener("submit", function (e) {
      e.preventDefault();
      console.log("📤 Formulario enviado");
      
      // Validar campos
      const nombre = document.getElementById("nombreProducto").value.trim();
      const descripcion = document.getElementById("descripcionProducto").value.trim();
      const precio = document.getElementById("precioProducto").value;
      const imagen = document.getElementById("imagen").files[0];
      const categoria = document.getElementById("categoriaProducto").value;
      
      console.log("📋 Datos del formulario:", { nombre, descripcion, precio, imagen: imagen?.name, categoria });
      
      if (!nombre || !descripcion || !precio || !imagen || !categoria) {
        alert("❌ Por favor completa todos los campos");
        return;
      }
      
      if (parseInt(precio) < 1000) {
        alert("❌ El precio debe ser al menos $1.000");
        return;
      }
      
      const formData = new FormData(form);
      
      // Mostrar loading
      const submitBtn = form.querySelector('button[type="submit"]');
      const originalText = submitBtn.textContent;
      submitBtn.textContent = "Subiendo...";
      submitBtn.disabled = true;
      
      console.log("🌐 Enviando petición a subir_producto.php");

      fetch("subir_producto.php", {
        method: "POST",
        body: formData
      })
        .then(response => {
          console.log("📡 Respuesta recibida - Status:", response.status);
          
          if (!response.ok) {
            return response.text().then(text => {
              console.error("❌ Respuesta del servidor:", text);
              throw new Error(`Error del servidor (${response.status}): ${text.substring(0, 100)}...`);
            });
          }
          
          return response.text();
        })
        .then(text => {
          console.log("📥 Respuesta raw del servidor:", text);
          
          // Limpiar JSON
          let cleanText = text.trim();
          const jsonStart = cleanText.indexOf('{');
          const jsonEnd = cleanText.lastIndexOf('}');
          
          if (jsonStart === -1 || jsonEnd === -1) {
            console.error("❌ No se encontró JSON válido en la respuesta");
            throw new Error("El servidor no devolvió un JSON válido");
          }
          
          cleanText = cleanText.substring(jsonStart, jsonEnd + 1);
          
          try {
            const data = JSON.parse(cleanText);
            console.log("📥 Respuesta parseada:", data);

            if (data.exito) {
              console.log("✅ Producto guardado exitosamente");
              
              // Crear nueva carta de producto
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

              // Agregar a la sección correspondiente
              let contenedor;
              if (data.categoria === "torta") {
                contenedor = document.querySelector(".Cartas_productos_TORTAS");
              } else if (data.categoria === "coctel") {
                contenedor = document.querySelector(".Cartas_productos_COCTEL");
              }
              
              if (contenedor) {
                const botonVerMas = contenedor.parentNode.querySelector('.BotonVerMasProducto');
                if (botonVerMas) {
                  contenedor.parentNode.insertBefore(carta, botonVerMas);
                } else {
                  contenedor.appendChild(carta);
                }
                console.log("✅ Producto agregado a la sección:", data.categoria);
              }

              // Cerrar modal y resetear
              const modalInstance = bootstrap.Modal.getInstance(document.getElementById("adminModal"));
              if (modalInstance) {
                modalInstance.hide();
              }
              
              alert("✅ Producto agregado exitosamente");
              
            } else {
              console.error("❌ Error del servidor:", data.mensaje);
              alert("❌ Error: " + (data.mensaje || "Error desconocido"));
            }
            
          } catch (parseError) {
            console.error("❌ Error al parsear JSON:", parseError);
            alert("❌ Error en la respuesta del servidor");
          }
        })
        .catch(error => {
          console.error("❌ Error completo:", error);
          alert("❌ Erroraaaaaaaa: " + error.message);
        })
        .finally(() => {
          // Restaurar botón
          submitBtn.textContent = originalText;
          submitBtn.disabled = false;
        });
    });
  }
});



document.addEventListener("click", function (e) {
  if (e.target.classList.contains("btn-editar")) {
    const card = e.target.closest(".Carta_producto");
    const id = e.target.dataset.id;
    const nombre = card.querySelector("h2").textContent;
    const descripcion = card.querySelector(".descripcion")?.textContent || "";
    const precio = card.querySelector(".precio")?.textContent.replace(/\D/g, "") || "";

    // Rellenar formulario
    document.getElementById("edit_id").value = id;
    document.getElementById("edit_nombre").value = nombre;
    document.getElementById("edit_descripcion").value = descripcion;
    document.getElementById("edit_precio").value = precio;

    // Abrir modal
    const modalEditar = new bootstrap.Modal(document.getElementById("modalEditarProducto"));
    modalEditar.show();
  }
});

document.getElementById("formEditarProducto").addEventListener("submit", function (e) {
  e.preventDefault();
  const form = e.target;
  const formData = new FormData(form);

  fetch("editar_producto.php", {
    method: "POST",
    body: formData
  })
    .then(res => res.text())
    .then(msg => {
      alert(msg);
      location.reload(); // o actualizar solo esa tarjeta si prefieres
    });
});

document.addEventListener("click", function (e) {
  if (e.target.classList.contains("btn-eliminar")) {
    const id = e.target.dataset.id;
    if (confirm("¿Estás seguro de eliminar este producto?")) {
      fetch("eliminar_producto.php", {
        method: "POST",
        body: new URLSearchParams({ id })
      })
        .then(res => res.text())
        .then(msg => {
          alert(msg);
          e.target.closest(".Carta_producto").remove();
        });
    }
  }});