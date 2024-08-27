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
    if (isset($_POST['id_comprador']) && isset($_POST['productos']) && isset($_POST['cantidad'])) {
        $id_comprador = $_POST['id_comprador'];
        $productos = $_POST['productos'];
        $cantidades = $_POST['cantidad'];

        // Iniciar la transacción
        $conn->begin_transaction();

        try {
            // Registrar la factura
            $fecha_compra = date('Y-m-d');
            $sql_factura = "INSERT INTO Factura (id_comprador, fecha_compra, total) VALUES ($id_comprador, '$fecha_compra', 0)";
            $conn->query($sql_factura);
            $id_factura = $conn->insert_id;

            $total = 0;

            foreach ($productos as $id_computador) {
                $cantidad = $cantidades[$id_computador];

                // Obtener detalles del computador
                $sql = "SELECT * FROM Computador WHERE id_computador = $id_computador";
                $result = $conn->query($sql);

                if ($result) {
                    $computador = $result->fetch_assoc();

                    if ($computador['stock'] >= $cantidad) {
                        // Reducir el stock
                        $nuevo_stock = $computador['stock'] - $cantidad;
                        $sql_update_stock = "UPDATE Computador SET stock = $nuevo_stock WHERE id_computador = $id_computador";
                        $conn->query($sql_update_stock);

                        // Calcular precios
                        $precio_unitario = $computador['precio'];
                        $subtotal = $cantidad * $precio_unitario;
                        $total += $subtotal;

                        // Registrar el detalle de la factura
                        $sql_detalle = "INSERT INTO Detalle_Factura (id_factura, id_computador, cantidad, precio_unitario, subtotal) VALUES ($id_factura, $id_computador, $cantidad, $precio_unitario, $subtotal)";
                        $conn->query($sql_detalle);
                    } else {
                        throw new Exception("Lo siento, el producto " . $computador['marca'] . " " . $computador['modelo'] . " no tiene suficiente stock.");
                    }
                } else {
                    throw new Exception("Error al obtener los detalles del producto.");
                }
            }

            // Actualizar el total de la factura
            $sql_update_factura = "UPDATE Factura SET total = $total WHERE id_factura = $id_factura";
            $conn->query($sql_update_factura);

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
                    <table>
                        <tr>
                            <th>Producto</th>
                            <th>Cantidad</th>
                            <th>Precio unitario</th>
                            <th>Subtotal</th>
                        </tr>
                ";

                foreach ($productos as $id_computador) {
                    $cantidad = $cantidades[$id_computador];

                    $sql = "SELECT marca, modelo, precio FROM Computador WHERE id_computador = $id_computador";
                    $result = $conn->query($sql);
                    $computador = $result->fetch_assoc();

                    $subtotal = $cantidad * $computador['precio'];

                    $factura_html .= "
                        <tr>
                            <td>" . $computador['marca'] . " " . $computador['modelo'] . "</td>
                            <td>$cantidad</td>
                            <td>$" . $computador['precio'] . "</td>
                            <td>$" . $subtotal . "</td>
                        </tr>
                    ";
                }

                $factura_html .= "
                        <tr>
                            <td colspan='3'><strong>Total</strong></td>
                            <td><strong>$" . $total . "</strong></td>
                        </tr>
                    </table>
                ";
            } else {
                throw new Exception("Error al obtener los datos del comprador.");
            }

            // Confirmar la transacción
            $conn->commit();

        } catch (Exception $e) {
            // Revertir la transacción en caso de error
            $conn->rollback();
            $factura_html = "<p>" . $e->getMessage() . "</p>";
        }

    } elseif (isset($_POST['id_factura_consultar'])) {
        $id_factura_consultar = $_POST['id_factura_consultar'];

        // Consultar la factura seleccionada
        $sql_factura_consulta = "
            SELECT Factura.id_factura, Factura.fecha_compra, Comprador.nombre, Comprador.apellido, Computador.marca, Computador.modelo, Detalle_Factura.cantidad, Detalle_Factura.precio_unitario, Detalle_Factura.subtotal 
            FROM Factura 
            JOIN Comprador ON Factura.id_comprador = Comprador.id_comprador 
            JOIN Detalle_Factura ON Factura.id_factura = Detalle_Factura.id_factura 
            JOIN Computador ON Detalle_Factura.id_computador = Computador.id_computador 
            WHERE Factura.id_factura = $id_factura_consultar";
        $result_factura = $conn->query($sql_factura_consulta);
        
        if ($result_factura->num_rows > 0) {
            $factura_html = "
                <h1>Factura consultada</h1>
                <table>
                    <tr>
                        <th>Factura N°</th>
                        <th>Fecha de compra</th>
                        <th>Comprador</th>
                        <th>Producto</th>
                        <th>Cantidad</th>
                        <th>Precio unitario</th>
                        <th>Subtotal</th>
                    </tr>
            ";

            while ($factura = $result_factura->fetch_assoc()) {
                $factura_html .= "
                    <tr>
                        <td>" . $factura['id_factura'] . "</td>
                        <td>" . $factura['fecha_compra'] . "</td>
                        <td>" . $factura['nombre'] . " " . $factura['apellido'] . "</td>
                        <td>" . $factura['marca'] . " " . $factura['modelo'] . "</td>
                        <td>" . $factura['cantidad'] . "</td>
                        <td>$" . $factura['precio_unitario'] . "</td>
                        <td>$" . $factura['subtotal'] . "</td>
                    </tr>
                ";
            }

            $factura_html .= "</table>";
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
                        while ($factura = $result_facturas->fetch_assoc()) {
                            echo '<option value="' . $factura['id_factura'] . '">Factura N° ' . $factura['id_factura'] . ' - ' . $factura['nombre_comprador'] . ' - ' . $factura['fecha_compra'] . '</option>';
                        }
                    }
                    $conn->close();
                    ?>
                </select>
            </div>
            <input type="submit" value="Consultar Factura">
        </form>
    </div>
</body>
</html>
