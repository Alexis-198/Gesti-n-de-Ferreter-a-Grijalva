<?php
require_once("clasematerial.php");
$objetoConexion = new clasematerial();

define('UPLOAD_DIR', 'uploads/materiales/');

if (!is_dir(UPLOAD_DIR)) {
    mkdir(UPLOAD_DIR, 0755, true);
}

function subirImagen($archivo) {
    if (!isset($archivo) || $archivo['error'] !== UPLOAD_ERR_OK) return '';
    $ext = strtolower(pathinfo($archivo['name'], PATHINFO_EXTENSION));
    if (!in_array($ext, ['jpg','jpeg','png','gif','webp'])) return '';
    $nombre = uniqid('mat_', true) . '.' . $ext;
    $ruta = UPLOAD_DIR . $nombre;
    return move_uploaded_file($archivo['tmp_name'], $ruta) ? $ruta : '';
}

// ── GUARDAR ──────────────────────────────────────────────────────────────────
if (isset($_POST['guardar'])) {
    $rutaImagen = subirImagen($_FILES['imagen'] ?? null);
    $objetoConexion->insertar(
        trim($_POST['nom_producto']),
        (float)$_POST['precio'],
        trim($_POST['descripcion']),
        (int)$_POST['cantidad'],
        $rutaImagen
    );
    header("Location: material.php");
    exit;
}

// ── ELIMINAR ─────────────────────────────────────────────────────────────────
if (isset($_GET['eliminar'])) {
    $objetoConexion->eliminar((int)$_GET['eliminar']);
    header("Location: material.php");
    exit;
}

// ── CARGAR PARA EDITAR ───────────────────────────────────────────────────────
$materialEditar = null;
if (isset($_GET['editar'])) {
    $res = $objetoConexion->seleccionarmaterial((int)$_GET['editar']);
    $materialEditar = $res->fetch_assoc();
}

// ── MODIFICAR ────────────────────────────────────────────────────────────────
if (isset($_POST['modificar'])) {
    $rutaImagen = subirImagen($_FILES['imagen'] ?? null);
    $objetoConexion->modificar(
        (int)$_POST['id_material'],
        trim($_POST['nom_producto']),
        (float)$_POST['precio'],
        trim($_POST['descripcion']),
        (int)$_POST['cantidad'],
        $rutaImagen ?: null
    );
    header("Location: material.php");
    exit;
}

$recibirmaterial = $objetoConexion->obtenermaterial();
?>
<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Gestión de Materiales – Ferretería</title>
<?php include '_estilos.php'; ?>

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

</head>
<body>

<h2> Gestión de Materiales</h2>

<nav>
<a href="cliente.php">Clientes</a>
    <a href="material.php">Materiales</a>
    <a href="trabajador.php">Trabajadores</a>
    <a href="transporte.php">Transporte</a>
    <a href="pedido.php">Pedidos</a>
    <a href="detalle.php">Detalles de Pedido</a>
    <a href="venta.php">Ventas</a>
</nav>

<?php include '_nav.php'; ?>

<form method="POST" action="material.php" enctype="multipart/form-data">

    <input type="hidden" name="id_material" value="<?= (int)($materialEditar['id_material'] ?? 0) ?>">

    <input type="text" name="nom_producto" placeholder="Nombre del producto" required
        value="<?= htmlspecialchars($materialEditar['nom_producto'] ?? '') ?>">

    <input type="number" name="precio" step="0.01" min="0" placeholder="Precio ($)" required
        value="<?= htmlspecialchars($materialEditar['precio'] ?? '') ?>">

    <input type="text" name="descripcion" placeholder="Descripción" required
        value="<?= htmlspecialchars($materialEditar['descripcion'] ?? '') ?>">

    <input type="number" name="cantidad" min="0" placeholder="Stock" required
        value="<?= htmlspecialchars($materialEditar['cantidad'] ?? '') ?>">

    <div class="field-wrap">
        <label>📷 Imagen del producto</label>
        <?php if (!empty($materialEditar['imagen']) && file_exists($materialEditar['imagen'])): ?>
            <img src="<?= htmlspecialchars($materialEditar['imagen']) ?>" class="img-actual">
            <span class="hint">Sube una nueva para reemplazarla</span>
        <?php endif; ?>
        <input type="file" name="imagen" accept="image/*" onchange="previewImg(this)">
        <img id="preview-img" src="" alt="Vista previa">
    </div>

    <?php if ($materialEditar): ?>
        <button type="submit" name="modificar">✏️ Modificar</button>
        <a class="btn-cancel" href="material.php">✖ Cancelar</a>
    <?php else: ?>
        <button type="submit" name="guardar">💾 Guardar</button>
    <?php endif; ?>

</form>

<table>
    <thead>
        <tr>
            <th>Imagen</th>
            <th>Nombre</th>
            <th>Precio</th>
            <th>Descripción</th>
            <th>Stock</th>
            <th>Acciones</th>
        </tr>
    </thead>
    <tbody>
    <?php
    $i = 0;
    while ($row = $recibirmaterial->fetch_assoc()):
        $i++;
    ?>
        <tr>
            <td style="text-align:center;">
                <?php if (!empty($row['imagen']) && file_exists($row['imagen'])): ?>
                    <img src="<?= htmlspecialchars($row['imagen']) ?>"
                         alt="<?= htmlspecialchars($row['nom_producto']) ?>"
                         class="product-img">
                <?php else: ?>
                    <div class="no-img" title="Sin imagen">🔩</div>
                <?php endif; ?>
            </td>
            <td><?= htmlspecialchars($row['nom_producto']) ?></td>
            <td><strong>$<?= number_format((float)$row['precio'], 2) ?></strong></td>
            <td><?= htmlspecialchars($row['descripcion']) ?></td>
            <td>
                <?php
                $stock = (int)$row['cantidad'];
                $color = $stock > 10 ? '#27ae60' : ($stock > 0 ? '#f39c12' : '#e74c3c');
                ?>
                <span style="color:<?= $color ?>; font-weight:600;"><?= $stock ?></span>
            </td>
            <td>
                <a href="?editar=<?= (int)$row['id_material'] ?>">✏️ Editar</a>
                <a href="?eliminar=<?= (int)$row['id_material'] ?>"
                   onclick="return confirm('¿Eliminar este material?')">🗑️ Eliminar</a>
            </td>
        </tr>
    <?php endwhile; ?>
    <?php if ($i === 0): ?>
        <tr><td colspan="6">No hay materiales registrados.</td></tr>
    <?php endif; ?>
    </tbody>
</table>

<script>
function previewImg(input) {
    const img = document.getElementById('preview-img');
    if (input.files && input.files[0]) {
        const reader = new FileReader();
        reader.onload = e => { img.src = e.target.result; img.classList.add('visible'); };
        reader.readAsDataURL(input.files[0]);
    } else {
        img.classList.remove('visible');
    }
}
</script>

</body>
</html>
