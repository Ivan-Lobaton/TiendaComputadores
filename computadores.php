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
                    echo "<form method='post' action='factura.php'>";
                    echo "<input type='hidden' name='id_computador' value='" . $row["id_computador"] . "'>";
                    echo "<input type='hidden' name='id_comprador' value='{$_POST['id_comprador']}'>";
                    echo "<input type='submit' value='Comprar'>";
                    echo "</form>";
                    echo "</div>";
                }
            } else {
                echo "<p>No hay productos disponibles.</p>";
            }

            $conn->close();
            ?>
        </div>
    </div>
</body>
</html>
