<?php

class clasedetalle
{
    private $host ="localhost";
    private $usuario ="root";
    private $password ="123456789";
    private $bd ="Ferreteria_db";
    private $puerto ="3306";
    private $conexion;

    public function __construct ()
    {
        $this->conexion = new mysqli(
            $this->host,
            $this->usuario,
            $this->password,
            $this->bd,
            $this->puerto
        );

        if($this->conexion->connect_error)
        {
            die("Error de conexión:" .$this->conexion->connect_error);
        }
    }

    //Consultar registros
    public function obtenermaterial()
    {
        return $this->conexion->query("SELECT * FROM Material ORDER BY id_material");
    }

    public function obtenercliente()
    {
        return $this->conexion->query("SELECT * FROM Cliente ORDER BY id_cliente");
    }

    public function obtenerpedidos()
    {
        return $this->conexion->query(
            "SELECT Pedido.id_pedido, Cliente.nombre
             FROM Pedido
             INNER JOIN Cliente ON Pedido.id_cliente = Cliente.id_cliente
             ORDER BY Pedido.id_pedido"
        );
    }

    //Consultar registros
    public function obtenerdetalles()
    {
        return $this->conexion->query(
            "SELECT dp.id_detalle_pedido,
                    dp.precio_unitario,
                    dp.cantidad,
                    dp.id_pedido,
                    dp.id_material,
                    m.nom_producto,
                    c.nombre AS nombre_cliente,
                    p.id_pedido AS num_pedido
             FROM Detalle_pedido dp
             INNER JOIN Material m ON dp.id_material = m.id_material
             INNER JOIN Pedido p ON dp.id_pedido = p.id_pedido
             INNER JOIN Cliente c ON p.id_cliente = c.id_cliente
             ORDER BY dp.id_detalle_pedido"
        );
    }

    //Insertar registros
    public function insertar($preciou, $cantidad, $id_pedido, $id_material)
    {
        $preciou    = $this->conexion->real_escape_string($preciou);
        $cantidad   = $this->conexion->real_escape_string($cantidad);
        $id_pedido  = $this->conexion->real_escape_string($id_pedido);
        $id_material= $this->conexion->real_escape_string($id_material);

        $sentencia = "INSERT INTO Detalle_pedido (precio_unitario, cantidad, id_pedido, id_material)
        VALUES ('$preciou', '$cantidad', '$id_pedido', '$id_material')";

        return $this->conexion->query($sentencia);
    }

    //Eliminar registro
    public function eliminar($id)
    {
        return $this->conexion->query("DELETE FROM Detalle_pedido WHERE id_detalle_pedido='$id'");
    }

    //Seleccionar detalle para editar
    public function seleccionardetalle($id)
    {
        $id = $this->conexion->real_escape_string($id);
        return $this->conexion->query("SELECT * FROM Detalle_pedido WHERE id_detalle_pedido='$id'");
    }

    //Modificar registro
    public function modificar($id, $preciou, $cantidad, $id_pedido, $id_material)
    {
        $id         = $this->conexion->real_escape_string($id);
        $preciou    = $this->conexion->real_escape_string($preciou);
        $cantidad   = $this->conexion->real_escape_string($cantidad);
        $id_pedido  = $this->conexion->real_escape_string($id_pedido);
        $id_material= $this->conexion->real_escape_string($id_material);

        $sentencia = "UPDATE Detalle_pedido 
        SET precio_unitario='$preciou',
            cantidad='$cantidad',
            id_pedido='$id_pedido',
            id_material='$id_material'
        WHERE id_detalle_pedido='$id'";

        return $this->conexion->query($sentencia);
    }

    public function cerrarConexion()
    {
        $this->conexion->close();
    }
}
?>