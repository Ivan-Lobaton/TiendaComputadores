<?php
// Conexión a la base de datos
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

// Obtener el ID del comprador seleccionado previamente
$id_comprador = isset($_POST['id_comprador']) ? $_POST['id_comprador'] : null;
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CompuMarket | Productos</title>
    <link rel="stylesheet" href="./Styles/computadores.css">
</head>
<body>
    <header>
        <h1>CompuMarket</h1>
        <p>Encuentra los mejores computadores</p>
    </header>

    <div class="center">
        <h2>Productos disponibles</h2>
        <form method="post" action="factura.php">
            <input type="hidden" name="id_comprador" value="<?php echo $id_comprador; ?>">
            <div class="product-list">
                <?php
                $sql = "SELECT id_computador, marca, modelo, precio, stock FROM Computador WHERE stock > 0";
                $result = $conn->query($sql);

                if ($result->num_rows > 0) {
                    while($row = $result->fetch_assoc()) {
                        echo "<div class='product-item'>";
                        echo "<h3>" . $row["marca"] . " " . $row["modelo"] . "</h3>";
                        echo "<p>Precio: <span>$" . number_format($row["precio"], 2) . "</span></p>";
                        echo "<p>Stock disponible: <span>" . $row["stock"] . "</span></p>";
                        echo "<div class='checkbox-container'>";
                        echo "<input type='checkbox' id='producto_" . $row["id_computador"] . "' name='productos[]' value='" . $row["id_computador"] . "'>";
                        echo "<label for='producto_" . $row["id_computador"] . "'>Seleccionar</label>";
                        echo "</div>";
                        echo "<label for='cantidad_" . $row["id_computador"] . "'>Cantidad:</label>";
                        echo "<input type='number' id='cantidad_" . $row["id_computador"] . "' name='cantidad[" . $row["id_computador"] . "]' value='1' min='1' max='" . $row["stock"] . "'>";
                        echo "</div>";
                    }
                } else {
                    echo "<p>No hay productos disponibles en este momento.</p>";
                }
                ?>
            </div>
            <div class="boton">
                <input type="submit" value="Comprar">
            </div>
        </form>
    </div>

    <footer>
        <p>&copy; 2024 CompuMarket. Todos los derechos reservados.</p>
    </footer>

    <?php $conn->close(); ?>
</body>
</html>
