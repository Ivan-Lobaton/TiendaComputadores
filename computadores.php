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
    <div class="center">
        <h1>Productos disponibles</h1>
        <form method="post" action="factura.php">
            <input type="hidden" name="id_comprador" value="<?php echo $id_comprador; ?>">
            <div class="product-list">
                <?php
                $sql = "SELECT id_computador, marca, modelo, precio, stock FROM Computador WHERE stock > 0";
                $result = $conn->query($sql);

                if ($result->num_rows > 0) {
                    while($row = $result->fetch_assoc()) {
                        echo "<div class='product-item'>";
                        echo "<h2>" . $row["marca"] . " " . $row["modelo"] . "</h2>";
                        echo "<p>Precio: $" . $row["precio"] . "</p>";
                        echo "<p>Stock disponible: " . $row["stock"] . "</p>";
                        echo "<input type='checkbox' name='productos[]' value='" . $row["id_computador"] . "'>";
                        echo "<label for='cantidad'>Cantidad:</label>";
                        echo "<input type='number' name='cantidad[" . $row["id_computador"] . "]' value='1' min='1' max='" . $row["stock"] . "'>";
                        echo "</div>";
                    }
                } else {
                    echo "<p>No hay productos disponibles.</p>";
                }
                ?>
            </div>
            <div class="boton">
                <input type="submit" value="Comprar">
            </div>
        </form>
    </div>
    <?php $conn->close(); ?>
</body>
</html>
