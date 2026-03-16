<?php

class claseventa
{
    private $host     = "localhost";
    private $usuario  = "root";
    private $password = "123456789";
    private $bd       = "Ferreteria_db";
    private $puerto   = "3306";
    private $conexion;

    public function __construct()
    {
        $this->conexion = new mysqli(
            $this->host, $this->usuario,
            $this->password, $this->bd, $this->puerto
        );
        if ($this->conexion->connect_error) {
            die("Error de conexión: " . $this->conexion->connect_error);
        }
        $this->conexion->set_charset("utf8mb4");
    }

    /** Devuelve todos los pedidos para el SELECT del formulario */
    public function obtenerpedidos()
    {
        return $this->conexion->query(
            "SELECT p.id_pedido, c.nombre AS nombre_cliente
             FROM Pedido p
             INNER JOIN Cliente c ON p.id_cliente = c.id_cliente
             ORDER BY p.id_pedido"
        );
    }

    public function obtenerventas()
    {
        return $this->conexion->query(
            "SELECT v.id_venta_factura, v.fecha, v.total,
                    v.id_pedido, c.nombre AS nombre_cliente
             FROM Venta_Factura v
             INNER JOIN Pedido p ON v.id_pedido = p.id_pedido
             INNER JOIN Cliente c ON p.id_cliente = c.id_cliente
             ORDER BY v.id_venta_factura"
        );
    }

    public function insertar($fecha, $total, $id_pedido)
    {
        $stmt = $this->conexion->prepare(
            "INSERT INTO Venta_Factura (fecha, total, id_pedido) VALUES (?, ?, ?)"
        );
        $stmt->bind_param("sdi", $fecha, $total, $id_pedido);
        $resultado = $stmt->execute();
        $stmt->close();
        return $resultado;
    }

    public function eliminar($id)
    {
        $stmt = $this->conexion->prepare("DELETE FROM Venta_Factura WHERE id_venta_factura = ?");
        $stmt->bind_param("i", $id);
        $resultado = $stmt->execute();
        $stmt->close();
        return $resultado;
    }

    public function seleccionarventa($id)
    {
        $stmt = $this->conexion->prepare("SELECT * FROM Venta_Factura WHERE id_venta_factura = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        return $stmt->get_result();
    }

    public function modificar($id, $fecha, $total, $id_pedido)
    {
        $stmt = $this->conexion->prepare(
            "UPDATE Venta_Factura SET fecha=?, total=?, id_pedido=? WHERE id_venta_factura=?"
        );
        $stmt->bind_param("sdii", $fecha, $total, $id_pedido, $id);
        $resultado = $stmt->execute();
        $stmt->close();
        return $resultado;
    }

    public function cerrarConexion()
    {
        $this->conexion->close();
    }
}
?>
