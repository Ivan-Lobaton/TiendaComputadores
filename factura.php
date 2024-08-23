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

$factura_html = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['id_comprador']) && isset($_POST['id_computador'])) {
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
    } elseif (isset($_POST['id_factura_consultar'])) {
        $id_factura_consultar = $_POST['id_factura_consultar'];

        // Consultar la factura seleccionada
        $sql_factura_consulta = "
            SELECT Factura.id_factura, Factura.fecha_compra, Comprador.nombre, Comprador.apellido, Computador.marca, Computador.modelo, Detalle_Factura.precio_unitario 
            FROM Factura 
            JOIN Comprador ON Factura.id_comprador = Comprador.id_comprador 
            JOIN Detalle_Factura ON Factura.id_factura = Detalle_Factura.id_factura 
            JOIN Computador ON Detalle_Factura.id_computador = Computador.id_computador 
            WHERE Factura.id_factura = $id_factura_consultar";
        $result_factura = $conn->query($sql_factura_consulta);
        
        if ($result_factura->num_rows > 0) {
            $factura = $result_factura->fetch_assoc();
            $factura_html = "
                <h1>Factura consultada</h1>
                <p>Factura N°: " . $factura['id_factura'] . "</p>
                <p>Fecha de compra: " . $factura['fecha_compra'] . "</p>
                <p>Comprador: " . $factura['nombre'] . " " . $factura['apellido'] . "</p>
                <p>Producto: " . $factura['marca'] . " " . $factura['modelo'] . "</p>
                <p>Precio: $" . $factura['precio_unitario'] . "</p>
                <p>Total: $" . $factura['precio_unitario'] . "</p>
            ";
        } else {
            $factura_html = "<p>No se encontró la factura seleccionada.</p>";
        }
    } else {
        $factura_html = "<p>Error en la solicitud.</p>";
    }
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

    <div class="center">
        <h1>Consultar facturas</h1>
        <form method="post" action="">
            <div class="txt_field">
                <label for="id_factura_consultar">Seleccione una factura:</label>
                <select name="id_factura_consultar" id="id_factura_consultar" required>
                    <option value="">Seleccione una factura</option>
                    <?php
                    // Obtener las facturas disponibles
                    $conn = new mysqli($servername, $username, $password, $dbname);
                    $sql_facturas = "SELECT id_factura, fecha_compra, (SELECT CONCAT(nombre, ' ', apellido) FROM Comprador WHERE id_comprador = Factura.id_comprador) AS nombre_comprador FROM Factura";
                    $result_facturas = $conn->query($sql_facturas);
                    if ($result_facturas->num_rows > 0) {
                        while($row = $result_facturas->fetch_assoc()) {
                            echo "<option value='" . $row['id_factura'] . "'>Factura N°" . $row['id_factura'] . " - " . $row['nombre_comprador'] . " (" . $row['fecha_compra'] . ")</option>";
                        }
                    }
                    $conn->close();
                    ?>
                </select>
            </div>
            <div class="boton">
                <input type="submit" value="Consultar Factura">
            </div>
        </form>
    </div>
</body>
</html>
