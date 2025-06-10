<?php
session_start();
$esAdmin = isset($_SESSION['tipousuario']) && $_SESSION['tipousuario'] === 'admin';
$logueado = isset($_SESSION['nombre']) && isset($_SESSION['tipousuario']);
$nombre = $logueado ? htmlspecialchars($_SESSION['nombre']) : '';
$tipousuario = $logueado ? $_SESSION['tipousuario'] : '';

$conexion = new mysqli("localhost", "root", "", "trabajo");

if ($conexion->connect_error) {
    die("Conexión fallida: " . $conexion->connect_error);
}

$resultado = $conexion->query("SELECT id, sabor, tamano, mensaje, fecha_entrega, detalles FROM tortapersonalizada ORDER BY fecha_entrega ASC");

$pedidos = [];
if ($resultado && $resultado->num_rows > 0) {
    while ($fila = $resultado->fetch_assoc()) {
        $pedidos[] = $fila;
    }
}

$conexion->close();
?>


<!DOCTYPE html>
<html lang="es">
<head>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Nunito:ital,wght@0,200..1000;1,200..1000&display=swap" rel="stylesheet">

    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
    <link rel="stylesheet" href="Style/Pedidos.css">
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
            <?php if ($logueado): ?>
                <li><a href="Web.php">Inicio</a></li>

                <?php if ($esAdmin): ?>
                  <li><a href="Pedidos.php">Pedidos</a></li>
                <?php endif; ?>

                <!-- Ícono de usuario/admin -->
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
      <h1 class="TituloPedidos">Pedidos Personalizados</h1>

      <div class="contenedor-pedidos">
        <?php if (!empty($pedidos)): ?>
          <?php foreach ($pedidos as $pedido): ?>
            <div class="carta-pedido">
              <h3>Torta de <?= htmlspecialchars($pedido['sabor']) ?></h3>
              <p><strong>Tamaño:</strong> <?= htmlspecialchars($pedido['tamano']) ?></p>
              <p><strong>Mensaje:</strong> <?= htmlspecialchars($pedido['mensaje']) ?: '—' ?></p>
              <p  class="entrega" ><strong>Entrega:</strong> <?= htmlspecialchars($pedido['fecha_entrega']) ?></p>
              <p><strong>Detalles:</strong> <?= htmlspecialchars($pedido['detalles']) ?: '—' ?></p>
              <p><strong>id:</strong> <?=htmlspecialchars($pedido['id']) ?></p>


            </div>
          <?php endforeach; ?>
        <?php else: ?>
          <p>No hay pedidos registrados aún.</p>
        <?php endif; ?>
      </div>



    </main>



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





<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="java/script.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
</body>
</html>