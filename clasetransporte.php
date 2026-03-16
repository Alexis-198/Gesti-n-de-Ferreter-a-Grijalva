<?php

class clasetransporte {

private $conexion;

public function __construct(){

$this->conexion = new mysqli(
"localhost",
"root",
"123456789",
"Ferreteria_db",
"3306"
);

if($this->conexion->connect_error){
die("Error de conexión: " . $this->conexion->connect_error);
}

$this->conexion->set_charset("utf8mb4");

}

public function obtenertransporte(){

$sql = "SELECT * FROM Transporte ORDER BY id_transporte";

return $this->conexion->query($sql);

}

public function insertar($tipo_transporte,$estado){

$stmt = $this->conexion->prepare(
"INSERT INTO Transporte(tipo_transporte,estado)
VALUES(?,?)"
);

$stmt->bind_param("ss",$tipo_transporte,$estado);

$resultado = $stmt->execute();

$stmt->close();

return $resultado;

}

public function eliminar($id){

$stmt = $this->conexion->prepare(
"DELETE FROM Transporte WHERE id_transporte=?"
);

$stmt->bind_param("i",$id);

$resultado = $stmt->execute();

$stmt->close();

return $resultado;

}

public function seleccionartransporte($id){

$stmt = $this->conexion->prepare(
"SELECT * FROM Transporte WHERE id_transporte=?"
);

$stmt->bind_param("i",$id);

$stmt->execute();

$resultado = $stmt->get_result();

$stmt->close();

return $resultado;

}

public function modificar($id,$tipo_transporte,$estado){

$stmt = $this->conexion->prepare(
"UPDATE Transporte
SET tipo_transporte=?,estado=?
WHERE id_transporte=?"
);

$stmt->bind_param("ssi",$tipo_transporte,$estado,$id);

$resultado = $stmt->execute();

$stmt->close();

return $resultado;

}

public function cerrarConexion(){

$this->conexion->close();

}

}