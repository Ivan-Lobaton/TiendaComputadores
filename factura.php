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

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['id_comprador']) && isset($_POST['id_computador'])) {
    $id_computador = $_POST['id_computador'];
    $id_comprador = $_POST['id_comprador'];

    // Obtener detalles del computador
    $sql = "SELECT * FROM Computador WHERE id_computador = $id_computador";
    $result = $conn->query($sql);
    if ($result) {
        $computador = $result->fetch_assoc();

        if ($computador['stock'] > 0) {
            // Reducir el stock en 1
            $nuevo_stock = $computador['stock'] - 1;
            $sql_update_stock = "UPDATE Computador SET stock = $nuevo_stock WHERE id_computador = $id_computador";
            $conn->query($sql_update_stock);

            // Registrar la factura
            $fecha_compra = date('Y-m-d');
            $total = $computador['precio'];
            $sql_factura = "INSERT INTO Factura (id_comprador, fecha_compra, total) VALUES ($id_comprador, '$fecha_compra', $total)";
            $conn->query($sql_factura);
            $id_factura = $conn->insert_id;

            // Registrar el detalle de la factura
            $cantidad = 1; // Se está comprando 1 unidad
            $precio_unitario = $computador['precio'];
            $subtotal = $cantidad * $precio_unitario;
            $sql_detalle = "INSERT INTO Detalle_Factura (id_factura, id_computador, cantidad, precio_unitario, subtotal) VALUES ($id_factura, $id_computador, $cantidad, $precio_unitario, $subtotal)";
            $conn->query($sql_detalle);

            // Obtener los datos del comprador
            $sql_comprador = "SELECT nombre, apellido FROM Comprador WHERE id_comprador = $id_comprador";
            $result_comprador = $conn->query($sql_comprador);
            if ($result_comprador) {
                $comprador = $result_comprador->fetch_assoc();
                // Mostrar la factura
                $factura_html = "
                    <h1>Factura</h1>
                    <p>Factura N°: $id_factura</p>
                    <p>Fecha de compra: $fecha_compra</p>
                    <p>Comprador: " . $comprador['nombre'] . " " . $comprador['apellido'] . "</p>
                    <p>Producto: " . $computador['marca'] . " " . $computador['modelo'] . "</p>
                    <p>Precio: $" . $computador['precio'] . "</p>
                    <p>Total: $" . $total . "</p>
                ";
            } else {
                $factura_html = "<p>Error al obtener los datos del comprador.</p>";
            }
        } else {
            $factura_html = "<p>Lo siento, el producto está agotado.</p>";
        }
    } else {
        $factura_html = "<p>Error al obtener los detalles del producto.</p>";
    }
} else {
    $factura_html = "<p>Error en la compra.</p>";
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CompuMarket | Factura</title>
    <link rel="stylesheet" href="./Styles/factura.css">
</head>
<body>
    <div class="center">
        <?php echo $factura_html; ?>
    </div>
</body>
</html>
