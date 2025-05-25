<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Formulario de Registro</title>
</head>
<body>
    <h2>Registro de Usuario</h2>
    <form method="post" action="">
        Nombre: <input type="text" name="nombre" required><br><br>
        Correo: <input type="email" name="correo" required><br><br>
        <input type="submit" name="submit" value="Registrar">
    </form>

<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nombre = $_POST["nombre"];
    $correo = $_POST["correo"];

    // Datos de conexión
    $serverName = "tcp:rootits.database.windows.net,1433";
    $connectionInfo = array(
        "UID" => "rootits",
        "pwd" => "@Lfonso279114",
        "Database" => "bd_PaaS",
        "Encrypt" => 1,
        "TrustServerCertificate" => 0,
        "LoginTimeout" => 30
    );

    // Conectar
    $conn = sqlsrv_connect($serverName, $connectionInfo);

    if ($conn === false) {
        echo "<p>Error al conectar a la base de datos.</p>";
        die(print_r(sqlsrv_errors(), true));
    }

    // Consulta para insertar
    $sql = "INSERT INTO usuarios (nombre, correo) VALUES (?, ?)";
    $params = array($nombre, $correo);
    $stmt = sqlsrv_query($conn, $sql, $params);

    if ($stmt === false) {
        echo "<p>Error al insertar datos.</p>";
        die(print_r(sqlsrv_errors(), true));
    } else {
        echo "<p>Registro exitoso.</p>";
    }

    sqlsrv_close($conn);
}
?>
</body>
</html>
