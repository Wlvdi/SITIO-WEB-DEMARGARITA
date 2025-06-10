// Verificación de carga
console.log("Script carrito.js cargado correctamente");

// Hacer funciones globales explícitamente
if (typeof window !== 'undefined') {
    window.agregarAlCarrito = agregarAlCarrito;
    window.eliminarDelCarrito = eliminarDelCarrito;
    window.vaciarCarrito = vaciarCarrito;
}
// ========== VARIABLES GLOBALES ==========
let carrito = JSON.parse(localStorage.getItem('carrito')) || [];

// ========== FUNCIONES PRINCIPALES ==========

function agregarAlCarrito(nombre, descripcion, imagen, precio) {

    console.log('Producto:', nombre, 'Precio:', precio);
    
    // Validar parámetros
    if (!nombre || !descripcion || !imagen || precio === undefined) {
        console.error("Faltan parámetros en agregarAlCarrito");
        return;
    }

    const productoExistente = carrito.find(item => item.nombre === nombre);
    
    if (productoExistente) {
        productoExistente.cantidad++;
    } else {
        carrito.push({
            nombre,
            descripcion,
            precio: Number(precio),
            imagen,
            cantidad: 1
        });
    }
    
    guardarCarrito();
    actualizarInterfaz();
    mostrarToastAgregado();
}

function eliminarDelCarrito(nombre) {
    carrito = carrito.filter(item => item.nombre !== nombre);
    guardarCarrito();
    actualizarInterfaz();
}

function vaciarCarrito() {
    if (carrito.length === 0) return;
    
    if (confirm('¿Estás seguro de que quieres vaciar el carrito?')) {
        carrito = [];
        guardarCarrito();
        actualizarInterfaz();
    }
}

function calcularTotal() {
    return carrito.reduce((total, item) => total + (item.precio * item.cantidad), 0);
}

// ========== FUNCIONES DE APOYO ==========

function guardarCarrito() {
    localStorage.setItem('carrito', JSON.stringify(carrito));
    actualizarContador();
}

function actualizarContador() {
    const contador = document.getElementById('contador-carrito');
    if (contador) {
        const totalItems = carrito.reduce((total, item) => total + item.cantidad, 0);
        contador.textContent = totalItems;
        contador.style.display = totalItems > 0 ? 'inline-block' : 'none';
    }
}

function mostrarToastAgregado() {
    const toastEl = document.getElementById('toastAgregado');
    if (toastEl) {
        const toast = new bootstrap.Toast(toastEl);
        toast.show();
    }
}

function actualizarInterfaz() {
    const modalItems = document.getElementById('carrito-items');
    const modalTotal = document.getElementById('carrito-total');
    const totalCheckout = document.getElementById('total-checkout');
    
    if (!modalItems || !modalTotal) return;
    
    if (carrito.length === 0) {
        modalItems.innerHTML = '<p class="text-center py-3 text-muted">El carrito está vacío</p>';
        modalTotal.textContent = '0.00';
        if (totalCheckout) totalCheckout.textContent = '0.00';
        return;
    }
    
    modalItems.innerHTML = carrito.map(item => `
        <div class="d-flex justify-content-between align-items-center mb-3 border-bottom pb-2">
            <div class="d-flex align-items-center">
                <img src="${item.imagen}" alt="${item.nombre}" width="60" class="me-3 rounded" style="object-fit: cover; height: 60px;">
                <div>
                    <h6 class="mb-0">${item.nombre}</h6>
                    <small class="text-muted">${item.descripcion}</small>
                </div>
            </div>
            <div class="text-end">
                <p class="mb-0">$${item.precio.toFixed(2)} x ${item.cantidad}</p>
                <p class="mb-0 fw-bold">$${(item.precio * item.cantidad).toFixed(2)}</p>
                <button class="btn btn-sm btn-outline-danger mt-1" onclick="eliminarDelCarrito('${item.nombre.replace(/'/g, "\\'")}')">
                    Eliminar
                </button>
            </div>
        </div>
    `).join('');
    
    const total = calcularTotal();
    modalTotal.textContent = total.toFixed(2);
    if (totalCheckout) totalCheckout.textContent = total.toFixed(2);
}

// ========== INICIALIZACIÓN Y EVENTOS ==========

document.addEventListener('DOMContentLoaded', function() {
    // Inicializar
    actualizarContador();
    actualizarInterfaz();
    
    // Finalizar compra
    document.getElementById('finalizar-compra')?.addEventListener('click', function() {
        if (carrito.length === 0) {
            alert('El carrito está vacío');
            return;
        }
        
        if (confirm(`¿Confirmar compra por $${calcularTotal().toFixed(2)}?`)) {
            alert('¡Compra realizada con éxito! Gracias por tu pedido.');
            vaciarCarrito();
        }
    });
    
    // Verificar botones al cargar
    document.querySelectorAll('.btn[onclick^="agregarAlCarrito"]').forEach(btn => {
        const onclick = btn.getAttribute('onclick');
        if (!onclick.includes(', 12000)')) { // Ajusta este precio según necesites
            console.warn('Botón con parámetros incorrectos:', btn);
        }
    });
});

// Hacer funciones accesibles globalmente
window.agregarAlCarrito = agregarAlCarrito;
window.eliminarDelCarrito = eliminarDelCarrito;
window.vaciarCarrito = vaciarCarrito;