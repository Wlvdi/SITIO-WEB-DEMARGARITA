<?php
session_start();
$esAdmin = isset($_SESSION['tipousuario']) && $_SESSION['tipousuario'] === 'admin';
$logueado = isset($_SESSION['nombre']) && isset($_SESSION['tipousuario']);
$nombre = $logueado ? htmlspecialchars($_SESSION['nombre']) : '';
$tipousuario = $logueado ? $_SESSION['tipousuario'] : '';

// Habilitar reporte de errores para depuración
ini_set('display_errors', 1);
error_reporting(E_ALL);

// FUNCIÓN CARGAR PRODUCTOS - DEFINIR PRIMERO
function cargarProductos($categoria) {
    $productos = [];
    
    try {
        // Intentar conexión
        $conexion = new mysqli("localhost", "root", "", "trabajo");
        
        // Verificar conexión
        if ($conexion->connect_error) {
            error_log("❌ Error de conexión: " . $conexion->connect_error);
            return $productos;
        }
        
        // Configurar charset
        $conexion->set_charset("utf8");
        
        // Verificar que la tabla existe
        $checkTable = $conexion->query("SHOW TABLES LIKE 'productos'");
        if ($checkTable->num_rows == 0) {
            error_log("❌ La tabla 'productos' no existe");
            $conexion->close();
            return $productos;
        }
        
        // Preparar la consulta
        $sql = "SELECT * FROM productos WHERE categoria = ? ORDER BY id_producto DESC";
        $stmt = $conexion->prepare($sql);
        
        // Verificar que prepare() funcionó
        if ($stmt === false) {
            error_log("❌ Error al preparar la consulta: " . $conexion->error);
            $conexion->close();
            return $productos;
        }
        
        // Bind parameters
        $stmt->bind_param("s", $categoria);
        
        // Ejecutar consulta
        if (!$stmt->execute()) {
            error_log("❌ Error al ejecutar la consulta: " . $stmt->error);
            $stmt->close();
            $conexion->close();
            return $productos;
        }
        
        // Obtener resultados
        $resultado = $stmt->get_result();
        
        // Procesar resultados
        while ($producto = $resultado->fetch_assoc()) {
            $productos[] = $producto;
        }
        
        $stmt->close();
        $conexion->close();
        
    } catch (Exception $e) {
        error_log("❌ Excepción cargando productos: " . $e->getMessage());
    }
    
    return $productos;
}

// CARGAR PRODUCTOS DESPUÉS DE DEFINIR LA FUNCIÓN
$productosTortas = cargarProductos('torta');
$productosCoctel = cargarProductos('coctel');

// Debug para verificar
echo "<!-- Debug: Productos tortas cargados: " . count($productosTortas) . " -->";
echo "<!-- Debug: Productos coctel cargados: " . count($productosCoctel) . " -->";

if (!empty($productosTortas)) {
    echo "<!-- Debug: Primer producto torta: " . htmlspecialchars($productosTortas[0]['nombre']) . " -->";
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Nunito:ital,wght@0,200..1000;1,200..1000&display=swap" rel="stylesheet">

    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
    <link rel="stylesheet" href="Style/style.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Página Principal</title>
</head>


<body id="Inicio">
    <header>
      <div  class="hcontenedor">
        <!-- Logo -->
        <img src="Imagenes\DeMargarita.png" alt="Logo" class="logo">
        <!-- Menú de navegación -->
        <nav>
          <ul>

                <li><a href="#Inicio">Inicio</a></li>
                <li><a href="#Productos">Productos</a></li>
                <li><a href="#">Contacto</a></li>
                <li><a href="#SobreNosotros">Sobre nosotros</a></li>
                <li><a href="#" data-bs-toggle="modal" data-bs-target="#carritoModal"> Carrito (<span id="contador-carrito">0</span>)</a></li>
                <?php if ($esAdmin): ?>
                  <li><a href="Pedidos.php">Pedidos</a></li>
                <?php endif; ?>
                
                <!-- Ícono de usuario/admin -->
                <?php if ($logueado): ?>
                  <li><a href="#"><?= $esAdmin ? '👑' : '👤' ?> </a></li>
                <!-- Cerrar sesión -->
                <li><a href="logout.php">Cerrar sesión</a></li>
                <?php else: ?>
                <li><a data-bs-toggle="modal" data-bs-target="#loginModal" href="#">Iniciar sesión</a></li>
                <?php endif; ?>
            

          </ul>
        </nav>
          <div class="hamburger" id="hamburger">
            ☰
          </div>
      </div>
    </header>

    <main> 

      <section  class="Carrusel">

          <div id="carouselExampleIndicators" class="carousel slide" data-bs-ride="carousel">
            <div class="carousel-indicators">
              <button type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide-to="0" class="active" aria-current="true" aria-label="Slide 1"></button>
              <button type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide-to="1" aria-label="Slide 2"></button>
              <button type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide-to="2" aria-label="Slide 3"></button>
            </div>
            <div class="carousel-inner">
              <div class="carousel-item active">
                <img src="https://upload.wikimedia.org/wikipedia/commons/thumb/6/62/Solid_red.svg/2048px-Solid_red.svg.png" class="d-block w-100" alt="...">
              </div>
              <div class="carousel-item">
                <img src="https://fancywalls.eu/wp-content/uploads/solid-color-burgundy-pattern-repeat-removable-wallpaper-design-683x1024.jpg" class="d-block w-100" alt="...">
              </div>
              <div class="carousel-item">
                <img src="https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcRvmvSBmyvV0GBqkgbOln79NVEafDcoCvwL-w&s" class="d-block w-100" alt="...">
              </div>
            </div>
            <button class="carousel-control-prev" type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide="prev">
              <span class="carousel-control-prev-icon" aria-hidden="true"></span>
              <span class="visually-hidden">Previous</span>
            </button>
            <button class="carousel-control-next" type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide="next">
              <span class="carousel-control-next-icon" aria-hidden="true"></span>
              <span class="visually-hidden">Next</span>
            </button>
          </div>

      </section>

      <section  class="Productos">
        <h1 id="Productos">PRODUCTOS</h1>

        <div class="Tortas_seccion">
          <div class="Cartas_productos_TORTAS" id="CartasTortas">
            <div class="Carta_tipo_producto">
              <h2 class="Tipo_Producto">TORTAS</h2>
            </div>

            <?php 
              // NUEVA LÓGICA: Mostrar productos dinámicos desde BD
              if (!empty($productosTortas)): 
                  foreach ($productosTortas as $producto): ?>
                      <div class="Carta_producto">
                          <img src="<?= htmlspecialchars($producto['imagen']) ?>" alt="<?= htmlspecialchars($producto['nombre']) ?>">
                          <h2><?= htmlspecialchars($producto['nombre']) ?></h2>
                          <p class="descripcion"><?= htmlspecialchars($producto['descripcion']) ?></p>
                          <p class="precio">$<?= number_format($producto['precio'], 0, ',', '.') ?></p>
                          <?php if ($esAdmin): ?> 
                            <div class="Adminbtn">
                              <button class='Adminbtna btn-editar'  data-categoria="torta" data-id='<?= $producto['id_producto'] ?>'> Editar</button>
                              <button class='Adminbtna btn-eliminar' data-categoria="torta" data-id='<?= $producto['id_producto'] ?>'> Eliminar</button>
                            </div>
                          <?php endif; ?>
                          <button class="btn" onclick="agregarAlCarrito('<?= htmlspecialchars($producto['nombre'], ENT_QUOTES) ?>', '<?= htmlspecialchars($producto['descripcion'], ENT_QUOTES) ?>', '<?= htmlspecialchars($producto['imagen'], ENT_QUOTES) ?>', <?= $producto['precio'] ?>)"> Añadir al carrito</button>

                      </div>
                  <?php endforeach;
              else: 
                  // Productos por defecto si no hay en BD
              ?>
                  <div class="Carta_producto">
                    <img src="https://fancywalls.eu/wp-content/uploads/solid-color-burgundy-pattern-repeat-removable-wallpaper-design-683x1024.jpg" alt="Producto 1">
                    <h2>Torta Ejemplo</h2>
                    <p>Descripción de ejemplo.</p>
                    <button class="btn">Añadir al carrito</button>
                  </div>
            <?php endif; ?>

        </div>
          <div class="BotonesEnd">
            <button id="botonVerMasTorta" class="BotonVerMasProducto" onclick="alternarProductos()">Ver más</button>
            <?php if ($esAdmin): ?>     
              <button class="BotonVerMasProducto" data-bs-toggle="modal" data-bs-target="#adminModal" data-categoria="torta">Añadir producto</button>
            <?php endif; ?>
          </div>

        </div>

        <div class="Coctel_seccion">
          <div class="Cartas_productos_COCTEL" id="CartasCoctel">
            <div class="Carta_tipo_producto">
              <h2 class="Tipo_Producto">COCTEL</h2>
            </div>
            <?php 
              // NUEVA LÓGICA: Mostrar productos dinámicos desde BD
              if (!empty($productosCoctel)): 
                  foreach ($productosCoctel as $producto): ?>
                      <div class="Carta_producto">
                          <img src="<?= htmlspecialchars($producto['imagen']) ?>" alt="<?= htmlspecialchars($producto['nombre']) ?>">
                          <h2><?= htmlspecialchars($producto['nombre']) ?></h2>
                          <p class="descripcion"><?= htmlspecialchars($producto['descripcion']) ?></p>
                          <p class="precio">$<?= number_format($producto['precio'], 0, ',', '.') ?></p>
                          <?php if ($esAdmin): ?> 
                            <div class="Adminbtn">
                              <button class='Adminbtna btn-editar'  data-categoria="coctel" data-id='<?= $producto['id_producto'] ?>'> Editar</button>
                              <button class='Adminbtna btn-eliminar' data-categoria="coctel" data-id='<?= $producto['id_producto'] ?>'> Eliminar</button>
                            </div>
                          <?php endif; ?>
                          <button class="btn" onclick="agregarAlCarrito('<?= htmlspecialchars($producto['nombre'], ENT_QUOTES) ?>', '<?= htmlspecialchars($producto['descripcion'], ENT_QUOTES) ?>', '<?= htmlspecialchars($producto['imagen'], ENT_QUOTES) ?>', <?= $producto['precio'] ?>)"> Añadir al carrito</button>
                      </div>
                  <?php endforeach;
              else: 
                  // Productos por defecto si no hay en BD
              ?>
                  <div class="Carta_producto">
                    <img src="https://fancywalls.eu/wp-content/uploads/solid-color-burgundy-pattern-repeat-removable-wallpaper-design-683x1024.jpg" alt="Producto 1">
                    <h2>Torta Ejemplo</h2>
                    <p>Descripción de ejemplo.</p>
                    <button class="btn">Añadir al carrito</button>
                  </div>
            <?php endif; ?>
        </div>
          <div class="BotonesEnd">
            <button id="botonVerMasCoctel" class="BotonVerMasProducto" onclick="alternarProductos()">Ver más</button>
            <?php if ($esAdmin): ?> 
            <button class="BotonVerMasProducto" data-bs-toggle="modal" data-bs-target="#adminModal" data-categoria="coctel">Añadir producto</button>
            <?php endif; ?>
          </div>
        </div>

        <div class="TortaPersonalizada_seccion">
          <h1>TORTAS PERSONALIZADAS</h1>
            <div class="Imagen_TortasPersonalizadas">
              <img src="https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcRvmvSBmyvV0GBqkgbOln79NVEafDcoCvwL-w&s" alt="Torta Personalizada">
              <img src="Imagenes\torta2.png" alt="Torta Personalizada">
              <img src="Imagenes\torta2.png" alt="Torta Personalizada">
              <img src="Imagenes\torta2.png" alt="Torta Personalizada">
              <img src="Imagenes\torta2.png" alt="Torta Personalizada">
              <img src="https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcRvmvSBmyvV0GBqkgbOln79NVEafDcoCvwL-w&s" alt="Torta Personalizada">
              <img src="https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcRvmvSBmyvV0GBqkgbOln79NVEafDcoCvwL-w&s" alt="Torta Personalizada">
              <img src="https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcRvmvSBmyvV0GBqkgbOln79NVEafDcoCvwL-w&s" alt="Torta Personalizada">
            </div>
            <div class="Texto_TortaPersonalizada">
              <div class="Columna_texto">
              <h2>¡Crea tu propia torta!</h2>
              <p>Elige los sabores, el diseño y los ingredientes para hacer una torta única y deliciosa.</p> 
              </div>
              <button data-bs-toggle="modal" data-bs-target="#TortaPersonalizadaModal" href="#" class="BotonComenzarTorta">Comenzar</button>
            </div>
        </div>
      </section>

      <section class="SobreNosotros"> 
        <h1 id="SobreNosotros">Sobre nosotros</h1>
        <div class="SobreNosotrosContent">
          <div class="SobreNosotrosTexto">
            <p>Somos una empresa dedicada a la creación de tortas y cocteles únicos y personalizados. Nuestro objetivo es hacer que cada celebración sea especial con productos de alta calidad y un servicio excepcional.</p>
            <p></p>
          </div>
          <div class="SobreNosotrosImagen">
            <img src="Imagenes/DeMargarita.png" alt="Sobre nosotros">
          </div>
        </div>
      </section>

    </main>

<footer class="footer">
  <div class="footer-container">
    <div class="footer-logo">
      <img src="Imagenes/DeMargarita.png" alt="Logo De Margarita">
    </div>
    <div class="footer-links">
      <a href="#Inicio">Inicio</a>
      <a href="#Productos">Productos</a>
      <a href="#SobreNosotros">Sobre Nosotros</a>
      <a href="#Contacto">Contacto</a>
    </div>
    <div class="footer-social">
      <a href="https://www.instagram.com/demargarita.cl/"><i class="fab fa-instagram"></i></a>
      <a href=""><i class="fa-brands fa-facebook"></i></a>
      <a href=""><i class="fa-brands fa-whatsapp"></i></a>
    </div>
  </div> 
  <div class="footer-copy">
    © <?= date('Y') ?> De Margarita. Todos los derechos reservados.
  </div>
</footer>


<!-- Modal inicio de sesion -->
<div class="modal fade" id="loginModal" tabindex="-1" aria-labelledby="loginModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-xl modal-dialog-centered">
    <div class="modal-content shadow" style="background-color: #F9E8F6; border: none; border-radius: 1rem; padding: 0;">
      
      <!-- Aquí solo va una sombra y el borde del modal se elimina -->
      <div class="row g-0">

        <!-- Imagen lateral -->
        <div class="col-md-6 col-lg-5 d-none d-md-flex justify-content-center align-items-center p-3"
        style="border-radius: 1rem 0 0 1rem;">
        <img src="Imagenes/DeMargarita.png"
          alt="login form"
          class="img-fluid"
          style="max-height: 300px; object-fit: contain;" />
        </div>

       
        <div class="col-md-6 col-lg-7 d-flex align-items-center bg-white rounded-end" style="border-radius: 0 1rem 1rem 0;">
          <div class="p-4 p-lg-5 text-black w-100">

            <div class="d-flex justify-content-between align-items-center mb-3">
              <h5 class="modal-title" id="loginModalLabel">Iniciar sesion</h5>
              <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

 <!-- Formulario -->
            <form action="login.php" method="POST">
                <div class="form-outline mb-4">
                  <label class="form-label" for="email">Direccion Email</label>
                  <input name="email" type="email" id="email" class="form-control form-control-lg" placeholder="Escribe tu Email" />
                </div>

                <div class="form-outline mb-4">
                  <label class="form-label" for="password">Contraseña</label>
                  <input  name="contrasena" type="password" id="contrasena" class="form-control form-control-lg" placeholder="Escribe tu contraseña" />
                </div>

                <div class="pt-1 mb-4">
                  <button class="ButtonIniciar">Iniciar sesion</button>
                </div>
                
                <div class="mt-10">   
                  <a class="small text-muted" href="#!">Olvide la contraña</a>           
                  <p   style="color: #393f81;">¿No tienes una cuenta?
                  <a href="#!" id="registroLink" style="color: #393f81;">Registrate aqui</a>
                </p></div>

            </form>

          </div>
        </div>
      </div> <!-- row -->
    </div>
  </div>
</div>

<!-- Modal de registro al estilo del login -->
<div class="modal fade" id="registroModal" tabindex="-1" aria-labelledby="registroModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-xl modal-dialog-centered">
    <div class="modal-content shadow" style="background-color: #F9E8F6; border: none; border-radius: 1rem; padding: 0;">
      <div class="row g-0">

        <!-- Imagen lateral -->
        <div class="col-md-6 col-lg-5 d-none d-md-flex justify-content-center align-items-center p-3" style="border-radius: 1rem 0 0 1rem;">
          <img src="Imagenes/DeMargarita.png" alt="registro form" class="img-fluid" style="max-height: 300px; object-fit: contain;" />
        </div>

        <!-- Formulario de registro -->
        <div class="col-md-6 col-lg-7 d-flex align-items-center bg-white rounded-end" style="border-radius: 0 1rem 1rem 0;">
          <div class="p-4 p-lg-5 text-black w-100">

            <div class="d-flex justify-content-between align-items-center mb-3">
              <h5 class="modal-title" id="registroModalLabel">Registro</h5>
              <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <form id="registroForm" action="registro.php" method="POST">
              <div class="form-outline mb-3">
                <label class="form-label">Nombre</label>
                <input type="text" name="nombre" class="form-control form-control-lg" required />
              </div>

              <div class="form-outline mb-3">
                <label class="form-label">Email</label>
                <input type="email" name="email" class="form-control form-control-lg" required />
              </div>

              <div class="form-outline mb-3">
                <label class="form-label">Contraseña</label>
                <input type="password" name="contrasena" class="form-control form-control-lg" required />
              </div>

              <div class="form-outline mb-3">
                <label class="form-label">Comuna</label>
                <input type="text" name="comuna" class="form-control form-control-lg" />
              </div>

              <div class="form-outline mb-3">
                <label class="form-label">Dirección</label>
                <input type="text" name="direccion" class="form-control form-control-lg" />
              </div>

              <div class="form-outline mb-4">
                <label class="form-label">Teléfono</label>
                <input type="text" name="telefono" class="form-control form-control-lg" />
              </div>

              <div class="Contenedor_boton">
                <input class="boton-registro" type="submit" name="registro" value="Registrar">
              </div>
            </form>

          </div>
        </div>
      </div> <!-- row -->
    </div>
  </div>
</div>

<!-- Modal de Torta personalizada -->
<div class="modal fade" id="TortaPersonalizadaModal" tabindex="-1" aria-labelledby="customCakeModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <form class="modal-content" id="TortaPersonalizadaContent" action="TortaPersonalizada.php" method="POST">
      <div class="modal-header">
        <h5 class="modal-title" id="TortaPersonalizadalLabel">Personaliza tu torta</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
      </div>
      <div class="modal-body">

        <div class="mb-3">
          <label for="flavor" class="form-label">Sabor</label>
          <select class="form-select" id="flavor" name="flavor" required>
            <option value="">Selecciona un sabor</option>
            <option>Chocolate</option>
            <option>Vainilla</option>
            <option>Fresa</option>
            <option>Red Velvet</option>
          </select>
        </div>

        <div class="mb-3">
          <label for="size" class="form-label">Tamaño</label>
          <select class="form-select" id="size" name="size"  required>
            <option value="">Selecciona un tamaño</option>
            <option>Pequeña (6 porciones)</option>
            <option>Mediana (10 porciones)</option>
            <option>Grande (20 porciones)</option>
          </select>
        </div>

        <div class="mb-3">
          <label for="message" class="form-label">Mensaje en la torta</label>
          <input type="text" class="form-control" id="message" name="message" maxlength="50" placeholder="Feliz cumpleaños, Ana">
        </div>

        <div class="mb-3">
          <label for="deliveryDate" class="form-label">Fecha de entrega</label>
          <input type="date" class="form-control" id="deliveryDate" name="deliveryDate" required>
        </div>

        <div class="mb-3">
          <label for="details" class="form-label">Detalles adicionales</label>
          <textarea class="form-control" id="details" name="details" rows="3" placeholder="Colores, temática, alergias, etc."></textarea>
        </div>

      </div>
      <div class="Modal_Footer">
        <button type="button" class="btnModalTortaCancelar" data-bs-dismiss="modal">Cancelar</button>
        <button type="submit" class="btnModalTortaEnviar">Enviar solicitud</button>
      </div>
    </form>
  </div>
</div>

<?php if ($esAdmin): ?>  
<!-- Modal agregar producto -->
<div class="modal fade" id="adminModal" tabindex="-1" aria-labelledby="adminModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content" style="border-radius: 1rem;">
      <div class="modal-header">
        <h5 class="modal-title" id="adminModalLabel">Añadir nuevo producto</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
      </div>
      <div class="modal-body">
        <form id="formNuevoProducto" enctype="multipart/form-data" method="POST">
          <div class="mb-3">
            <label for="nombreProducto" class="form-label">Nombre del producto</label>
            <input type="text" class="form-control" name="nombre" id="nombreProducto" required>
          </div>
          <div class="mb-3">
            <label for="descripcionProducto" class="form-label">Descripción</label>
            <input type="text" class="form-control" name="descripcion" id="descripcionProducto" required>
          </div>
          <div class="mb-3">
            <label for="imagen" class="form-label">Imagen del producto</label>
            <input type="file" class="form-control" name="imagen" id="imagen" accept="image/*" required>
          </div>
          <div class="mb-3">
            <label for="precioProducto" class="form-label">Precio</label>
            <input type="number" class="form-control" name="precio" id="precioProducto" min="1000" required>
          </div>
          <div class="mb-3">
            <input type="hidden" name="categoria" id="categoriaProducto" value="">
          </div>
          <button type="submit" class="btn btn-success">Subir producto</button>
        </form>
      </div>
    </div>
  </div>
</div>

<!-- Modal para editar producto -->
<div class="modal fade" id="modalEditarProducto" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <form id="formEditarProducto" enctype="multipart/form-data">
        <div class="modal-header">
          <h5 class="modal-title">Editar producto</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
        </div>
        <div class="modal-body">
          <input type="hidden" name="id" id="edit_id">
          <div class="mb-3">
            <label>Nombre</label>
            <input type="text" name="nombre" id="edit_nombre" class="form-control" required>
          </div>
          <div class="mb-3">
            <label>Descripción</label>
            <input type="text" name="descripcion" id="edit_descripcion" class="form-control">
          </div>
          <div class="mb-3">
            <label>Precio</label>
            <input type="number" name="precio" id="edit_precio" class="form-control" required>
          </div>
          <div class="mb-3">
            <label>Imagen (opcional)</label>
            <input type="file" name="imagen" class="form-control">
            <small id="edit_imagen_actual"></small>
          </div>
        </div>
        <div class="modal-footer">
          <button type="submit" class="btn btn-primary">Guardar cambios</button>
        </div>
      </form>
    </div>
  </div>
</div>
<?php endif; ?>

<div class="modal fade" id="carritoModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content" id="CarritoModal">
            <div class="modal-header">
                <h5 class="modal-title">🛒 Tu Carrito</h5>
                <button type="button" class="btn-close btn-close-black" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div id="carrito-items" class="carrito-items">
                    <!-- Productos dinámicos aparecerán aquí via JS -->
                    <p class="text-center py-3 text-muted">El carrito está vacío</p>
                </div>
                <div class="carrito-total text-end mt-4">
                    <h4>Total: $<span id="carrito-total"></span></h4>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" onclick="vaciarCarrito()" class="btn btn-danger">
                    Vaciar Carrito
                </button>
                <button type="button" id="finalizar-compra" class="btn btn-success">
                    Finalizar Compra ($<span id="total-checkout">0.00</span>)
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Toast de notificación-->
<div class="toast align-items-center text-white bg-success border-0" id="toastAgregado" role="alert" aria-live="assertive" aria-atomic="true">
    <div class="d-flex">
        <div class="toast-body">
            Producto añadido al carrito!
        </div>
        <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
    </div>
</div>


<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="java/script.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
<script src="https://kit.fontawesome.com/0d4c023edf.js" crossorigin="anonymous"></script>
<script src="java/carrito.js"></script>

</body>
</html>