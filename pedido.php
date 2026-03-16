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


<?php
require_once("clasepedido.php");
$objetoConexion = new clasepedido();

// ── GUARDAR ──────────────────────────────────────────────────────────────────
if (isset($_POST['guardar'])) {
    $objetoConexion->insertar(
        (int)$_POST['id_cliente'],
        $_POST['fecha_pedido'],
        trim($_POST['modalidad_pedido']),
        $_POST['fecha_entrega'],
        trim($_POST['direccion'])
    );
    header("Location: pedido.php");
    exit;
}

// ── ELIMINAR ─────────────────────────────────────────────────────────────────
if (isset($_GET['eliminar'])) {
    $objetoConexion->eliminar((int)$_GET['eliminar']);
    header("Location: pedido.php");
    exit;
}

// ── CARGAR PARA EDITAR ───────────────────────────────────────────────────────
$pedidoEditar = null;
if (isset($_GET['editar'])) {
    $res = $objetoConexion->seleccionarpedido((int)$_GET['editar']);
    $pedidoEditar = $res->fetch_assoc();
}

// ── MODIFICAR ────────────────────────────────────────────────────────────────
if (isset($_POST['modificar'])) {
    $objetoConexion->modificar(
        (int)$_POST['id_pedido'],
        (int)$_POST['id_cliente'],
        $_POST['fecha_pedido'],
        trim($_POST['modalidad_pedido']),
        $_POST['fecha_entrega'],
        trim($_POST['direccion'])
    );
    header("Location: pedido.php");
    exit;
}

$recibirpedido = $objetoConexion->obtenerpedido();
$clientes      = $objetoConexion->obtenerclientes();

// Guardar clientes en array para reusar en el SELECT
$clientesArr = [];
while ($c = $clientes->fetch_assoc()) {
    $clientesArr[] = $c;
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Gestión de Pedidos – Ferretería</title>
<?php include '_estilos.php'; ?>
</head>
<body>

<h2>📦 Gestión de Pedidos</h2>

<nav>
<a href="cliente.php">Clientes</a>
    <a href="material.php">Materiales</a>
    <a href="trabajador.php">Trabajadores</a>
    <a href="transporte.php">Transporte</a>
    <a href="pedido.php">Pedidos</a>
    <a href="detalle.php">Detalles de Pedido</a>
    <a href="venta.php">Ventas</a>
</nav>

<form method="POST" action="pedido.php">

    <input type="hidden" name="id_pedido" value="<?= (int)($pedidoEditar['id_pedido'] ?? 0) ?>">

    <!-- SELECT CLIENTE -->
    <select name="id_cliente" required>
        <option value="">-- Seleccione Cliente --</option>
        <?php foreach ($clientesArr as $c):
            $sel = (isset($pedidoEditar['id_cliente']) && $pedidoEditar['id_cliente'] == $c['id_cliente']) ? 'selected' : '';
        ?>
        <option value="<?= (int)$c['id_cliente'] ?>" <?= $sel ?>>
            <?= htmlspecialchars($c['nombre']) ?>
        </option>
        <?php endforeach; ?>
    </select>

    <div class="field-wrap">
        <label>📅 Fecha del Pedido</label>
        <input type="date" name="fecha_pedido" required
            value="<?= htmlspecialchars($pedidoEditar['fecha_pedido'] ?? '') ?>">
    </div>

    <!-- SELECT modalidad -->
    <select name="modalidad_pedido" required>
        <option value="">-- Modalidad --</option>
        <?php
        $modalidades = ['Presencial', 'En línea', 'Por teléfono', 'A domicilio'];
        foreach ($modalidades as $m):
            $sel = (isset($pedidoEditar['modalidad_pedido']) && $pedidoEditar['modalidad_pedido'] === $m) ? 'selected' : '';
        ?>
        <option value="<?= $m ?>" <?= $sel ?>><?= $m ?></option>
        <?php endforeach; ?>
    </select>

    <div class="field-wrap">
        <label>📅 Fecha de Entrega</label>
        <input type="date" name="fecha_entrega" required
            value="<?= htmlspecialchars($pedidoEditar['fecha_entrega'] ?? '') ?>">
    </div>

    <input type="text" name="direccion" placeholder="Dirección de entrega" required
        value="<?= htmlspecialchars($pedidoEditar['direccion'] ?? '') ?>">

    <?php if ($pedidoEditar): ?>
        <button type="submit" name="modificar">✏️ Modificar</button>
        <a class="btn-cancel" href="pedido.php">✖ Cancelar</a>
    <?php else: ?>
        <button type="submit" name="guardar">💾 Guardar</button>
    <?php endif; ?>

</form>

<table>
    <thead>
        <tr>
            <th>#Pedido</th>
            <th>Cliente</th>
            <th>Fecha Pedido</th>
            <th>Modalidad</th>
            <th>Fecha Entrega</th>
            <th>Dirección</th>
            <th>Acciones</th>
        </tr>
    </thead>
    <tbody>
    <?php
    $i = 0;
    while ($row = $recibirpedido->fetch_assoc()):
        $i++;
    ?>
        <tr>
            <td><strong>#<?= (int)$row['id_pedido'] ?></strong></td>
            <td><?= htmlspecialchars($row['nombre']) ?></td>
            <td><?= htmlspecialchars($row['fecha_pedido']) ?></td>
            <td><?= htmlspecialchars($row['modalidad_pedido']) ?></td>
            <td><?= htmlspecialchars($row['fecha_entrega']) ?></td>
            <td><?= htmlspecialchars($row['direccion']) ?></td>
            <td>
                <a href="?editar=<?= (int)$row['id_pedido'] ?>">✏️ Editar</a>
                <a href="?eliminar=<?= (int)$row['id_pedido'] ?>"
                   onclick="return confirm('¿Eliminar este pedido?')">🗑️ Eliminar</a>
            </td>
        </tr>
    <?php endwhile; ?>
    <?php if ($i === 0): ?>
        <tr><td colspan="7">No hay pedidos registrados.</td></tr>
    <?php endif; ?>
    </tbody>
</table>

</body>
</html>
