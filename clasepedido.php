<?php

class clasepedido
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

    public function obtenerclientes()
    {
        return $this->conexion->query("SELECT id_cliente, nombre FROM Cliente ORDER BY nombre");
    }

    public function obtenerpedido()
    {
        return $this->conexion->query(
            "SELECT Pedido.id_pedido,
                    Pedido.fecha_pedido,
                    Pedido.modalidad_pedido,
                    Pedido.fecha_entrega,
                    Pedido.direccion,
                    Cliente.nombre
             FROM Pedido
             INNER JOIN Cliente ON Pedido.id_cliente = Cliente.id_cliente
             ORDER BY Pedido.id_pedido"
        );
    }

    public function insertar($id_cliente, $fecha_pedido, $modalidad_pedido, $fecha_entrega, $direccion)
    {
        $stmt = $this->conexion->prepare(
            "INSERT INTO Pedido (id_cliente, fecha_pedido, modalidad_pedido, fecha_entrega, direccion)
             VALUES (?, ?, ?, ?, ?)"
        );
        $stmt->bind_param("issss", $id_cliente, $fecha_pedido, $modalidad_pedido, $fecha_entrega, $direccion);
        $resultado = $stmt->execute();
        $stmt->close();
        return $resultado;
    }

    public function eliminar($id)
    {
        $stmt = $this->conexion->prepare("DELETE FROM Pedido WHERE id_pedido = ?");
        $stmt->bind_param("i", $id);
        $resultado = $stmt->execute();
        $stmt->close();
        return $resultado;
    }

    public function seleccionarpedido($id)
    {
        $stmt = $this->conexion->prepare("SELECT * FROM Pedido WHERE id_pedido = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        return $stmt->get_result();
    }

    public function modificar($id, $id_cliente, $fecha_pedido, $modalidad_pedido, $fecha_entrega, $direccion)
    {
        $stmt = $this->conexion->prepare(
            "UPDATE Pedido SET id_cliente=?, fecha_pedido=?, modalidad_pedido=?,
             fecha_entrega=?, direccion=? WHERE id_pedido=?"
        );
        $stmt->bind_param("issssi", $id_cliente, $fecha_pedido, $modalidad_pedido, $fecha_entrega, $direccion, $id);
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
