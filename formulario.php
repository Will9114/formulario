<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Registro de Mascota</title>
  <style>
    :root {
      --primary-color: #38b000;
      --secondary-color: #70e000;
      --light-color: #f8f9fa;
      --dark-color: #212529;
      --success-color: #4cc9f0;
      --error-color: #f72585;
    }

    * {
      box-sizing: border-box;
      margin: 0;
      padding: 0;
      font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    }

    body {
      background-color: #f5f7fa;
      display: flex;
      justify-content: center;
      align-items: center;
      min-height: 100vh;
      padding: 20px;
    }

    .form-container {
      background-color: white;
      border-radius: 10px;
      box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
      padding: 40px;
      width: 100%;
      max-width: 600px;
      transition: all 0.3s ease;
    }

    .form-container:hover {
      box-shadow: 0 15px 35px rgba(0, 0, 0, 0.15);
    }

    h1 {
      color: var(--primary-color);
      margin-bottom: 30px;
      text-align: center;
      font-weight: 600;
    }

    .form-group {
      margin-bottom: 25px;
      position: relative;
    }

    label {
      display: block;
      margin-bottom: 8px;
      color: var(--dark-color);
      font-weight: 500;
      font-size: 14px;
    }

    input {
      width: 100%;
      padding: 12px 15px;
      border: 2px solid #e9ecef;
      border-radius: 6px;
      font-size: 16px;
      transition: all 0.3s ease;
    }

    input:focus {
      border-color: var(--primary-color);
      outline: none;
      box-shadow: 0 0 0 3px rgba(56, 176, 0, 0.2);
    }

    .btn-submit {
      background-color: var(--primary-color);
      color: white;
      border: none;
      padding: 14px 20px;
      font-size: 16px;
      font-weight: 600;
      border-radius: 6px;
      cursor: pointer;
      width: 100%;
      transition: all 0.3s ease;
      text-transform: uppercase;
      letter-spacing: 1px;
    }

    .btn-submit:hover {
      background-color: var(--secondary-color);
      transform: translateY(-2px);
    }

    .response {
      margin-top: 30px;
      padding: 20px;
      border-radius: 6px;
      background-color: #e8f4fd;
      border-left: 4px solid var(--success-color);
      display: none;
    }

    .error {
      border-left-color: var(--error-color) !important;
      background-color: #fef0f5 !important;
    }

    @media (max-width: 768px) {
      .form-container {
        padding: 30px 20px;
      }
    }
  </style>
</head>
<body>
  <div class="form-container">
    <h1>Registro de Mascota</h1>
    <form method="post" action="">
      <div class="form-group">
        <label for="nombre_mascota">Nombre de la Mascota</label>
        <input type="text" id="nombre_mascota" name="nombre_mascota" required>
      </div>

      <div class="form-group">
        <label for="especie">Especie</label>
        <input type="text" id="especie" name="especie" required>
      </div>

      <div class="form-group">
        <label for="raza">Raza</label>
        <input type="text" id="raza" name="raza">
      </div>

      <div class="form-group">
        <label for="edad">Edad (en años)</label>
        <input type="number" id="edad" name="edad" min="0" required>
      </div>

      <div class="form-group">
        <label for="nombre_duenio">Nombre del Dueño</label>
        <input type="text" id="nombre_duenio" name="nombre_duenio" required>
      </div>

      <button type="submit" name="enviar" class="btn-submit">Registrar</button>
    </form>

    <?php
    // Configuración de conexión a la base de datos
    $host = "10.10.0.4"; // Por ejemplo: "localhost"
    $dbname = "mascotas_db";
    $username = "william";
    $password = "alfonso27";

    try {
      $conn = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
      $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

      // Crear tabla si no existe
      $sql = "CREATE TABLE IF NOT EXISTS mascotas (
        id INT AUTO_INCREMENT PRIMARY KEY,
        nombre_mascota VARCHAR(50) NOT NULL,
        especie VARCHAR(50) NOT NULL,
        raza VARCHAR(50),
        edad INT NOT NULL,
        nombre_duenio VARCHAR(100) NOT NULL,
        fecha_registro TIMESTAMP DEFAULT CURRENT_TIMESTAMP
      )";
      $conn->exec($sql);
    } catch (PDOException $e) {
      die("Error de conexión: " . $e->getMessage());
    }

    if (isset($_POST['enviar'])) {
      try {
        $stmt = $conn->prepare("INSERT INTO mascotas (nombre_mascota, especie, raza, edad, nombre_duenio)
          VALUES (:nombre_mascota, :especie, :raza, :edad, :nombre_duenio)");

        $stmt->bindParam(':nombre_mascota', $_POST['nombre_mascota']);
        $stmt->bindParam(':especie', $_POST['especie']);
        $stmt->bindParam(':raza', $_POST['raza']);
        $stmt->bindParam(':edad', $_POST['edad']);
        $stmt->bindParam(':nombre_duenio', $_POST['nombre_duenio']);

        $stmt->execute();

        echo '<div class="response" style="display: block;">';
        echo '<h3>Datos guardados correctamente:</h3>';
        echo '<p><strong>Mascota:</strong> ' . htmlspecialchars($_POST['nombre_mascota']) . '</p>';
        echo '<p><strong>Especie:</strong> ' . htmlspecialchars($_POST['especie']) . '</p>';
        echo '<p><strong>Raza:</strong> ' . htmlspecialchars($_POST['raza']) . '</p>';
        echo '<p><strong>Edad:</strong> ' . htmlspecialchars($_POST['edad']) . '</p>';
        echo '<p><strong>Dueño:</strong> ' . htmlspecialchars($_POST['nombre_duenio']) . '</p>';
        echo '</div>';
      } catch (PDOException $e) {
        echo '<div class="response error" style="display: block;">';
        echo '<h3>Error al guardar los datos:</h3>';
        echo '<p>' . htmlspecialchars($e->getMessage()) . '</p>';
        echo '</div>';
      }
    }
    ?>
  </div>
</body>
</html>
