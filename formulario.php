<form method="POST">
  Nombre: <input name="nombre"><br>
  Correo: <input name="correo"><br>
  <input type="submit" value="Enviar">
</form>

<?php
    try {
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $conn = new PDO("sqlsrv:server = tcp:rootits.database.windows.net,1433; Database = bd_PaaS", "rootits", "@Lfonso279114");
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        $stmt = $conn->prepare("INSERT INTO usuarios (nombre, correo) VALUES (?, ?)");
        $stmt->execute([$_POST['nombre'], $_POST['correo']]);
        echo "Datos enviados.";
        }
    }
    catch (PDOException $e) {
        print("Error connecting to SQL Server.");
        die(print_r($e));
    }
?>
