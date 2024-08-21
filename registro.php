<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CompuMarket | Registro</title>
    <link rel="stylesheet" href="./Styles/registro.css">
</head>
<body>
    <?php
    // Configuración de la base de datos
    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "TiendaComputadores";

    // Crear conexión
    $conn = new mysqli($servername, $username, $password, $dbname);

    // Verificar conexión
    if ($conn->connect_error) {
        die("Conexión fallida: " . $conn->connect_error);
    }

    // Verificar si se han enviado los datos del formulario para registro
    if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['nombre'])) {
        // Obtener los datos del formulario
        $id_comprador = $conn->real_escape_string($_POST['id_comprador']);
        $nombre = $conn->real_escape_string($_POST['nombre']);
        $apellido = $conn->real_escape_string($_POST['apellido']);
        $email = $conn->real_escape_string($_POST['email']);
        $telefono = $conn->real_escape_string($_POST['telefono']);

        // SQL para insertar los datos en la tabla Comprador
        $sql = "INSERT INTO Comprador (id_comprador, nombre, apellido, email, telefono) 
                VALUES ('$id_comprador', '$nombre', '$apellido', '$email', '$telefono')";

        if ($conn->query($sql) === TRUE) {
            echo "Registro exitoso. <a href='computadores.php'>Ver productos disponibles</a>";
        } else {
            echo "Error: " . $sql . "<br>" . $conn->error;
        }
    }
    ?>

    <div class="container">
        <div class="center">
            <h1>Registro</h1>
            <form method="post" action="">
                <div class="txt_field">
                    <input type="text" name="id_comprador" required>
                    <span></span>
                    <label>Número de cédula</label>
                </div>
                <div class="txt_field">
                    <input type="text" name="nombre" required>
                    <span></span>
                    <label>Nombre</label>
                </div>
                <div class="txt_field">
                    <input type="text" name="apellido" required>
                    <span></span>
                    <label>Apellido</label>
                </div>
                <div class="txt_field">
                    <input type="email" name="email" required>
                    <span></span>
                    <label>Email</label>
                </div>
                <div class="txt_field">
                    <input type="text" name="telefono" required>
                    <span></span>
                    <label>Teléfono</label>
                </div>
                <div class="boton">
                    <input type="submit" value="Registrarse">
                </div>
            </form>
        </div>
        <div class="right-panel">
            <h1>Seleccionar Usuario</h1>
            <table>
                <tr>
                    <th>Nombre</th>
                    <th>Email</th>
                </tr>
                <?php
                // Obtener los compradores
                $sql = "SELECT id_comprador, nombre, email FROM Comprador";
                $result = $conn->query($sql);
                if ($result->num_rows > 0) {
                    while($row = $result->fetch_assoc()) {
                        echo "<tr onclick='selectUser(" . $row['id_comprador'] . ")'>";
                        echo "<td>" . $row['nombre'] . "</td>";
                        echo "<td>" . $row['email'] . "</td>";
                        echo "</tr>";
                    }
                }
                ?>
            </table>
        </div>
    </div>

    <form id="selectUserForm" method="post" action="computadores.php" style="display:none;">
        <input type="hidden" name="id_comprador" id="selectedUserId">
    </form>

    <script>
        function selectUser(id) {
            document.getElementById('selectedUserId').value = id;
            document.getElementById('selectUserForm').submit();
        }
    </script>

    <?php
    // Cerrar conexión
    $conn->close();
    ?>
</body>
</html>
