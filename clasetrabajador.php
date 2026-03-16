<?php

class clasetrabajador
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

    public function obtenertrabajador()
    {
        return $this->conexion->query("SELECT * FROM Trabajador ORDER BY id_trabajador");
    }

    public function insertar($nombre, $puesto, $horario)
    {
        $stmt = $this->conexion->prepare(
            "INSERT INTO Trabajador (nombre, puesto, horario) VALUES (?, ?, ?)"
        );
        $stmt->bind_param("sss", $nombre, $puesto, $horario);
        $resultado = $stmt->execute();
        $stmt->close();
        return $resultado;
    }

    public function eliminar($id)
    {
        $stmt = $this->conexion->prepare("DELETE FROM Trabajador WHERE id_trabajador = ?");
        $stmt->bind_param("i", $id);
        $resultado = $stmt->execute();
        $stmt->close();
        return $resultado;
    }

    public function seleccionartrabajador($id)
    {
        $stmt = $this->conexion->prepare("SELECT * FROM Trabajador WHERE id_trabajador = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        return $stmt->get_result();
    }

    public function modificar($id, $nombre, $puesto, $horario)
    {
        $stmt = $this->conexion->prepare(
            "UPDATE Trabajador SET nombre=?, puesto=?, horario=? WHERE id_trabajador=?"
        );
        $stmt->bind_param("sssi", $nombre, $puesto, $horario, $id);
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
