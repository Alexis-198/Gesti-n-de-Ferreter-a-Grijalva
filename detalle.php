    <?php
    require_once("clasedetalle.php");
    $objetoConexion = new clasedetalle();

    // ── GUARDAR ──────────────────────────────────────────────────────────────────
    if (isset($_POST['guardar'])) {
        $objetoConexion->insertar(
            (float)$_POST['precio_unitario'],
            (float)$_POST['cantidad'],
            (int)$_POST['id_pedido'],
            (int)$_POST['id_material']
        );
        header("Location: detalle.php");
        exit;
    }

    // ── ELIMINAR ─────────────────────────────────────────────────────────────────
    if (isset($_GET['eliminar'])) {
        $objetoConexion->eliminar((int)$_GET['eliminar']);
        header("Location: detalle.php");
        exit;
    }

    // ── CARGAR PARA EDITAR ───────────────────────────────────────────────────────
    $detalleEditar = null;
    if (isset($_GET['editar'])) {
        $res = $objetoConexion->seleccionardetalle((int)$_GET['editar']);
        $detalleEditar = $res->fetch_assoc();
    }

    // ── MODIFICAR ────────────────────────────────────────────────────────────────
    if (isset($_POST['modificar'])) {
        $objetoConexion->modificar(
            (int)$_POST['id_detalle_pedido'],
            (float)$_POST['precio_unitario'],
            (float)$_POST['cantidad'],
            (int)$_POST['id_pedido'],
            (int)$_POST['id_material']
        );
        header("Location: detalle.php");
        exit;
    }

    $recibirdetalle    = $objetoConexion->obtenerdetalles();
    $recibirmateriales = $objetoConexion->obtenermaterial();
    $recibirpedidos    = $objetoConexion->obtenerpedidos();

    // Guardar en arrays para reusar en los SELECTs
    $materialesArr = [];
    while ($m = $recibirmateriales->fetch_assoc()) { $materialesArr[] = $m; }

    $pedidosArr = [];
    while ($p = $recibirpedidos->fetch_assoc()) { $pedidosArr[] = $p; }
    ?>
    <!DOCTYPE html>
    <html lang="es">
    <head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detalles de Pedido </title>
    
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

    <h2> Detalles de Pedido</h2>


    <nav>
        <a href="cliente.php">Clientes</a>
        <a href="material.php">Materiales</a>
        <a href="trabajador.php">Trabajadores</a>
        <a href="transporte.php">Transporte</a>
        <a href="pedido.php">Pedidos</a>
        <a href="detalle.php">Detalles de Pedido</a>
        <a href="venta.php">Ventas</a>
    </nav>

    <form method="POST" action="detalle.php">

        <input type="hidden" name="id_detalle_pedido" value="<?= (int)($detalleEditar['id_detalle_pedido'] ?? 0) ?>">

        <input type="number" name="precio_unitario" step="0.01" min="0" placeholder="Precio Unitario ($)" required
            value="<?= htmlspecialchars($detalleEditar['precio_unitario'] ?? '') ?>">

        <input type="number" name="cantidad" step="1" min="1" placeholder="Cantidad" required
            value="<?= htmlspecialchars($detalleEditar['cantidad'] ?? '') ?>">

        <!-- SELECT PEDIDO -->
        <select name="id_pedido" required>
            <option value="">-- Seleccione Pedido --</option>
            <?php foreach ($pedidosArr as $p):
                $sel = (isset($detalleEditar['id_pedido']) && $detalleEditar['id_pedido'] == $p['id_pedido']) ? 'selected' : '';
            ?>
            <option value="<?= (int)$p['id_pedido'] ?>" <?= $sel ?>>
                #<?= (int)$p['id_pedido'] ?> — <?= htmlspecialchars($p['nombre']) ?>
            </option>
            <?php endforeach; ?>
        </select>

        <!-- SELECT MATERIAL -->
        <select name="id_material" required>
            <option value="">-- Seleccione Material --</option>
            <?php foreach ($materialesArr as $m):
                $sel = (isset($detalleEditar['id_material']) && $detalleEditar['id_material'] == $m['id_material']) ? 'selected' : '';
            ?>
            <option value="<?= (int)$m['id_material'] ?>" <?= $sel ?>>
                <?= htmlspecialchars($m['nom_producto']) ?> — $<?= number_format((float)$m['precio'], 2) ?>
            </option>
            <?php endforeach; ?>
        </select>

        <?php if ($detalleEditar): ?>
            <button type="submit" name="modificar">✏️ Modificar</button>
            <a class="btn-cancel" href="detalle.php">✖ Cancelar</a>
        <?php else: ?>
            <button type="submit" name="guardar">💾 Guardar</button>
        <?php endif; ?>

    </form>

    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Material</th>
                <th>Cantidad</th>
                <th>Precio Unit.</th>
                <th>Subtotal</th>
                <th>Pedido / Cliente</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
        <?php
        $i = 0;
        while ($row = $recibirdetalle->fetch_assoc()):
            $i++;
            $subtotal = (float)$row['precio_unitario'] * (float)$row['cantidad'];
        ?>
            <tr>
                <td><?= (int)$row['id_detalle_pedido'] ?></td>
                <td><?= htmlspecialchars($row['nom_producto']) ?></td>
                <td><?= (int)$row['cantidad'] ?></td>
                <td>$<?= number_format((float)$row['precio_unitario'], 2) ?></td>
                <td><strong>$<?= number_format($subtotal, 2) ?></strong></td>
                <td>#<?= (int)$row['num_pedido'] ?> — <?= htmlspecialchars($row['nombre_cliente']) ?></td>
                <td>
                    <a href="?editar=<?= (int)$row['id_detalle_pedido'] ?>">✏️ Editar</a>
                    <a href="?eliminar=<?= (int)$row['id_detalle_pedido'] ?>"
                    onclick="return confirm('¿Eliminar este detalle?')">🗑️ Eliminar</a>
                </td>
            </tr>
        <?php endwhile; ?>
        <?php if ($i === 0): ?>
            <tr><td colspan="7">No hay detalles registrados.</td></tr>
        <?php endif; ?>
        </tbody>
    </table>

    </body>
    </html>
