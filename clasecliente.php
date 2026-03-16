<?php

class clasecliente
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

    public function obtenercliente()
    {
        return $this->conexion->query("SELECT * FROM Cliente ORDER BY id_cliente");
    }

    public function insertar($nombre, $direccion, $telefono)
    {
        $stmt = $this->conexion->prepare(
            "INSERT INTO Cliente (nombre, direccion, telefono) VALUES (?, ?, ?)"
        );
        $stmt->bind_param("sss", $nombre, $direccion, $telefono);
        $resultado = $stmt->execute();
        $stmt->close();
        return $resultado;
    }

    public function eliminar($id)
    {
        $stmt = $this->conexion->prepare("DELETE FROM Cliente WHERE id_cliente = ?");
        $stmt->bind_param("i", $id);
        $resultado = $stmt->execute();
        $stmt->close();
        return $resultado;
    }

    public function seleccionarcliente($id)
    {
        $stmt = $this->conexion->prepare("SELECT * FROM Cliente WHERE id_cliente = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        return $stmt->get_result();
    }

    public function modificar($id, $nombre, $direccion, $telefono)
    {
        $stmt = $this->conexion->prepare(
            "UPDATE Cliente SET nombre=?, direccion=?, telefono=? WHERE id_cliente=?"
        );
        $stmt->bind_param("sssi", $nombre, $direccion, $telefono, $id);
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
