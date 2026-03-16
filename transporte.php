<?php
require_once("clasetransporte.php");

$objetoConexion = new clasetransporte();
$transporteEditar = null;

// ───────────── GUARDAR ─────────────
if (isset($_POST['guardar'])) {

    $tipo = trim($_POST['tipo_transporte']);
    $estado = trim($_POST['estado']);

    if ($tipo !== "" && $estado !== "") {
        $objetoConexion->insertar($tipo, $estado);
    }

    header("Location: transporte.php");
    exit;
}

// ───────────── ELIMINAR ─────────────
if (isset($_GET['eliminar'])) {

    $id = (int) $_GET['eliminar'];
    $objetoConexion->eliminar($id);

    header("Location: transporte.php");
    exit;
}

// ───────────── EDITAR ─────────────
if (isset($_GET['editar'])) {

    $id = (int) $_GET['editar'];
    $resultado = $objetoConexion->seleccionartransporte($id);

    if ($resultado && $resultado->num_rows > 0) {
        $transporteEditar = $resultado->fetch_assoc();
    }
}

// ───────────── MODIFICAR ─────────────
if (isset($_POST['modificar'])) {

    $id = (int) $_POST['id_transporte'];
    $tipo = trim($_POST['tipo_transporte']);
    $estado = trim($_POST['estado']);

    if ($tipo !== "" && $estado !== "") {
        $objetoConexion->modificar($id, $tipo, $estado);
    }

    header("Location: transporte.php");
    exit;
}

// Obtener datos
$recibirtransporte = $objetoConexion->obtenertransporte();
?>

<!DOCTYPE html>
<html lang="es">
<head>

<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Gestión de Transporte</title>



</head>
 <style>

    *{
    margin:0;
    padding:0;
    box-sizing:border-box;
    font-family:'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    }

    /* Fondo */
    body{
    background:linear-gradient(135deg,#5f9cff,#7a5cff);
    min-height:100vh;
    padding:30px;
    }

    /* Contenedor general */
    .container{
    max-width:1200px;
    margin:auto;
    }

    /* Título */
    h2{
    color:#ffffff;
    margin-bottom:25px;
    font-size:32px;
    font-weight:600;
    letter-spacing:1px;
    }

    /* Formulario */

    form{
    background:white;
    padding:30px;
    border-radius:12px;
    box-shadow:0 8px 25px rgba(0,0,0,0.2);
    margin-bottom:40px;

    display:grid;
    grid-template-columns:repeat(auto-fit,minmax(220px,1fr));
    gap:20px;
    }

    /* Grupo de campos */

    .form-group{
    display:flex;
    flex-direction:column;
    }

    label{
    font-weight:600;
    margin-bottom:6px;
    color:#555;
    font-size:14px;
    }

    /* Inputs */

    input,
    textarea{
    padding:10px 12px;
    border-radius:8px;
    border:2px solid #e0e0e0;
    font-size:14px;
    transition:0.3s;
    background:#f9f9f9;
    }

    textarea{
    resize:vertical;
    min-height:80px;
    }

    input:focus,
    textarea:focus{
    outline:none;
    border-color:#5f9cff;
    background:white;
    box-shadow:0 0 6px rgba(95,156,255,0.3);
    }

    /* Imagen preview */

    #preview-img{
    width:100px;
    height:100px;
    object-fit:cover;
    border-radius:10px;
    border:2px dashed #5f9cff;
    display:none;
    }

    #preview-img.visible{
    display:block;
    }

    /* Botones */

    button{
    padding:12px;
    border:none;
    border-radius:8px;
    font-weight:bold;
    cursor:pointer;
    transition:0.3s;
    }

    button[name="guardar"]{
    background:#4CAF50;
    color:white;
    }

    button[name="modificar"]{
    background:#ff7043;
    color:white;
    }

    button:hover{
    transform:translateY(-2px);
    box-shadow:0 5px 12px rgba(0,0,0,0.2);
    }

    /* TABLA */

    table{
    width:100%;
    background:white;
    border-radius:12px;
    overflow:hidden;
    box-shadow:0 8px 25px rgba(0,0,0,0.2);
    border-collapse:collapse;
    }

    /* Encabezado */

    th{
    background:#5f9cff;
    color:white;
    padding:15px;
    font-size:14px;
    letter-spacing:1px;
    }

    /* Celdas */

    td{
    padding:12px;
    border-bottom:1px solid #eee;
    text-align:center;
    }

    /* Hover */

    tr:hover{
    background:#f6f8ff;
    }

    /* Imagen producto */

    .product-img{
    width:90px;
    height:90px;
    object-fit:cover;
    border-radius:10px;
    transition:0.3s;
    }

    .product-img:hover{
    transform:scale(1.15);
    }

    /* Botones acciones */

    td a{
    padding:6px 12px;
    border-radius:6px;
    text-decoration:none;
    color:white;
    font-size:13px;
    margin:3px;
    display:inline-block;
    }

    td a:first-child{
    background:#2196F3;
    }

    td a:last-child{
    background:#f44336;
    }

    td a:hover{
    opacity:0.8;
    }

    /* Navegación */

    nav{
    margin-bottom:20px;
    }

    nav a{
    background:rgba(255,255,255,0.2);
    padding:10px 16px;
    border-radius:8px;
    text-decoration:none;
    color:white;
    margin-right:8px;
    font-weight:600;
    transition:0.3s;
    }

    nav a:hover{
    background:white;
    color:#5f9cff;
    }

    /* Animación */

    @keyframes fade{
    from{opacity:0;transform:translateY(10px);}
    to{opacity:1;transform:translateY(0);}
    }

    form,table{
    animation:fade 0.5s ease;
    }

    /* Responsive */

    @media(max-width:768px){

    form{
    grid-template-columns:1fr;
    }

    table{
    display:block;
    overflow-x:auto;
    }

    }

    </style>
<body>

<h2> Gestión de Transporte</h2>

  <nav>
        <a href="cliente.php">Clientes</a>
        <a href="material.php">Materiales</a>
        <a href="trabajador.php">Trabajadores</a>
        <a href="transporte.php">Transporte</a>
        <a href="pedido.php">Pedidos</a>
        <a href="detalle.php">Detalles de Pedido</a>
        <a href="venta.php">Ventas</a>
    </nav>


<form method="POST">

<input type="hidden" name="id_transporte"
value="<?= $transporteEditar['id_transporte'] ?? '' ?>">

<input type="text"
name="tipo_transporte"
placeholder="Tipo de Transporte"
required
value="<?= htmlspecialchars($transporteEditar['tipo_transporte'] ?? '') ?>">

<select name="estado" required>

<option value="">-- Seleccione Estado --</option>

<?php
$estados = ['Disponible','En ruta','En mantenimiento','No disponible'];

foreach ($estados as $e):

$selected = ($transporteEditar['estado'] ?? '') == $e ? 'selected' : '';

?>

<option value="<?= $e ?>" <?= $selected ?>><?= $e ?></option>

<?php endforeach; ?>

</select>

<?php if ($transporteEditar): ?>

<button type="submit" name="modificar">✏️ Modificar</button>
<a class="btn-cancel" href="transporte.php">Cancelar</a>

<?php else: ?>

<button type="submit" name="guardar">💾 Guardar</button>

<?php endif; ?>

</form>

<table>

<thead>

<tr>
<th>#</th>
<th>Tipo Transporte</th>
<th>Estado</th>
<th>Acciones</th>
</tr>

</thead>

<tbody>

<?php

$i = 0;

while ($row = $recibirtransporte->fetch_assoc()):

$i++;

      
switch ($row['estado']) {
    case 'Disponible': $badgeClass = 'badge-green'; break;
    case 'En ruta': $badgeClass = 'badge-blue'; break;
    case 'En mantenimiento': $badgeClass = 'badge-yellow'; break;
    default: $badgeClass = 'badge-red'; break;
}

?>

<tr>

<td><?= $i ?></td>

<td><?= htmlspecialchars($row['tipo_transporte']) ?></td>

<td>
<span class="badge <?= $badgeClass ?>">
<?= htmlspecialchars($row['estado']) ?>
</span>
</td>

<td>

<a href="?editar=<?= $row['id_transporte'] ?>">✏️ Editar</a>

<a href="?eliminar=<?= $row['id_transporte'] ?>"
onclick="return confirm('¿Eliminar este transporte?')">
🗑️ Eliminar
</a>

</td>

</tr>

<?php endwhile; ?>

</tbody>

</table>

</body>
</html>