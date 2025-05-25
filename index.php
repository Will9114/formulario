<?php
// Configuración de conexión a Azure SQL con sqlsrv_connect
$serverName = "tcp:rootits.database.windows.net,1433";
$connectionInfo = array(
    "UID" => "rootits",
    "pwd" => "@Lfonso279114",
    "Database" => "bd_PaaS",
    "LoginTimeout" => 30,
    "Encrypt" => 1,
    "TrustServerCertificate" => 0
);

$conn = sqlsrv_connect($serverName, $connectionInfo);

if (!$conn) {
    die("<div class='response error' style='display: block;'><h3>Error de conexión:</h3><p>" . htmlspecialchars(sqlsrv_errors()[0]['message']) . "</p></div>");
}

// Crear tabla si no existe
$tsql_create = "
IF NOT EXISTS (SELECT * FROM INFORMATION_SCHEMA.TABLES WHERE TABLE_NAME = 'mascotas')
BEGIN
    CREATE TABLE mascotas (
        id INT IDENTITY(1,1) PRIMARY KEY,
        nombre_mascota NVARCHAR(50) NOT NULL,
        especie NVARCHAR(50) NOT NULL,
        raza NVARCHAR(50),
        edad INT NOT NULL,
        nombre_duenio NVARCHAR(100) NOT NULL,
        fecha_registro DATETIME DEFAULT GETDATE()
    )
END";
sqlsrv_query($conn, $tsql_create);

if (isset($_POST['enviar'])) {
    $nombre_mascota = $_POST['nombre_mascota'];
    $especie = $_POST['especie'];
    $raza = $_POST['raza'];
    $edad = $_POST['edad'];
    $nombre_duenio = $_POST['nombre_duenio'];

    $tsql_insert = "INSERT INTO mascotas (nombre_mascota, especie, raza, edad, nombre_duenio) VALUES (?, ?, ?, ?, ?)";
    $params = array($nombre_mascota, $especie, $raza, $edad, $nombre_duenio);

    $stmt = sqlsrv_prepare($conn, $tsql_insert, $params);

    if ($stmt && sqlsrv_execute($stmt)) {
        echo '<div class="response" style="display: block;">';
        echo '<h3>Datos guardados correctamente:</h3>';
        echo '<p><strong>Mascota:</strong> ' . htmlspecialchars($nombre_mascota) . '</p>';
        echo '<p><strong>Especie:</strong> ' . htmlspecialchars($especie) . '</p>';
        echo '<p><strong>Raza:</strong> ' . htmlspecialchars($raza) . '</p>';
        echo '<p><strong>Edad:</strong> ' . htmlspecialchars($edad) . '</p>';
        echo '<p><strong>Dueño:</strong> ' . htmlspecialchars($nombre_duenio) . '</p>';
        echo '</div>';
    } else {
        echo '<div class="response error" style="display: block;">';
        echo '<h3>Error al guardar los datos:</h3>';
        echo '<p>' . htmlspecialchars(sqlsrv_errors()[0]['message']) . '</p>';
        echo '</div>';
    }
}
?>
