<?php

class clasematerial
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

    //Insertar registros con imagen
    public function insertar($nom_producto, $precio, $descripcion, $cantidad, $imagen = '')
    {
        $nom_producto  = $this->conexion->real_escape_string($nom_producto);
        $precio        = $this->conexion->real_escape_string($precio);
        $descripcion   = $this->conexion->real_escape_string($descripcion);
        $cantidad      = $this->conexion->real_escape_string($cantidad);
        $imagen        = $this->conexion->real_escape_string($imagen);

        $sentencia = "INSERT INTO Material (nom_producto, precio, descripcion, cantidad, imagen)
        VALUES ('$nom_producto','$precio','$descripcion','$cantidad','$imagen')";

        return $this->conexion->query($sentencia);
    }

    //Eliminar registro
    public function eliminar($id)
    {
        $id = $this->conexion->real_escape_string($id);
        return $this->conexion->query("DELETE FROM Material WHERE id_material='$id'");
    }

    //Seleccionar material para editar
    public function seleccionarmaterial($id)
    {
        $id = $this->conexion->real_escape_string($id);
        return $this->conexion->query("SELECT * FROM Material WHERE id_material='$id'");
    }

    //Modificar registro con imagen
    public function modificar($id, $nom_producto, $precio, $descripcion, $cantidad, $imagen = null)
    {
        $id           = $this->conexion->real_escape_string($id);
        $nom_producto = $this->conexion->real_escape_string($nom_producto);
        $precio       = $this->conexion->real_escape_string($precio);
        $descripcion  = $this->conexion->real_escape_string($descripcion);
        $cantidad     = $this->conexion->real_escape_string($cantidad);

        // Solo actualizar imagen si se subió una nueva
        if ($imagen !== null && $imagen !== '') {
            $imagen = $this->conexion->real_escape_string($imagen);
            $sentencia = "UPDATE Material 
            SET nom_producto='$nom_producto',
            precio='$precio',
            descripcion='$descripcion',
            cantidad='$cantidad',
            imagen='$imagen'
            WHERE id_material='$id'";
        } 
        
        
        else {
            $sentencia = "UPDATE Material 
            SET nom_producto='$nom_producto',
            precio='$precio',
            descripcion='$descripcion',
            cantidad='$cantidad'
            WHERE id_material='$id'";
        }

        return $this->conexion->query($sentencia);
    }

    public function cerrarConexion()
    {
        $this->conexion->close();
    }
}
?>