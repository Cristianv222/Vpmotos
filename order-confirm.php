<?php
session_start();
require_once __DIR__ . '/config/config.php';
require_once __DIR__ . '/includes/api.php';

$carrito = $_SESSION['carrito'] ?? [];
$datos   = $_SESSION['checkout_datos'] ?? [];

$error_pedido  = null;
$pedido_creado = null;

// ── Caso 1: Ya hay resultado guardado → mostrar y limpiar ─────────
if (!empty($_SESSION['pedido_resultado'])) {
    $pedido_creado = $_SESSION['pedido_resultado'];
    // Recuperar carrito original antes de que haya sido limpiado
    $carrito_original = $_SESSION['carrito_snapshot'] ?? [];
    unset($_SESSION['pedido_resultado'], $_SESSION['carrito_snapshot']);

// ── Caso 2: Hay datos para procesar → crear pedido ────────────────
} elseif (!empty($carrito) && !empty($datos)) {

    $items = array_map(fn($item) => [
        'producto_id'     => $item['id'],
        'cantidad'        => $item['cantidad'],
        'precio_unitario' => $item['precio'],
    ], array_values($carrito));

    $payload = [
        'nombres'       => $datos['nombres'],
        'apellidos'     => $datos['apellidos'],
        'cedula'        => $datos['cedula'],
        'telefono'      => $datos['telefono'],
        'email'         => $datos['email'] ?? '',
        'tipo_entrega'  => $datos['tipo_entrega'],
        'metodo_pago'   => $datos['metodo_pago'],
        'observaciones' => $datos['observaciones'] ?? '',
        'items'         => $items,
    ];

    if ($datos['tipo_entrega'] === 'SERVIENTREGA') {
        $payload['direccion_envio']  = $datos['direccion']  ?? '';
        $payload['ciudad_envio']     = $datos['ciudad']     ?? '';
        $payload['provincia_envio']  = $datos['provincia']  ?? '';
        $payload['referencia_envio'] = $datos['referencia'] ?? '';
    }

    if ($datos['metodo_pago'] === 'TRANSFERENCIA') {
        $payload['numero_comprobante'] = $datos['numero_comprobante'] ?? '';
        $payload['banco_origen']       = $datos['banco_origen']       ?? '';
        $payload['comprobante_base64'] = $datos['comprobante_base64'] ?? '';
    }

    $resultado = crear_pedido($payload);

    if (!empty($resultado['success']) && !empty($resultado['numero_orden'])) {
        // Guardar snapshot del carrito ANTES de limpiarlo
        $_SESSION['carrito_snapshot']  = $carrito;
        $_SESSION['pedido_resultado']  = $resultado;
        $_SESSION['carrito']           = [];
        unset($_SESSION['checkout_datos']);
        // PRG: redirigir para evitar reenvío
        header('Location: order-confirm.php');
        exit;
    } else {
        $error_pedido = $resultado['error'] ?? $resultado['errors'] ?? 'No se pudo crear el pedido. Intenta nuevamente.';
        if (is_array($error_pedido)) $error_pedido = implode(', ', array_values($error_pedido));
    }

// ── Caso 3: Sin datos ni resultado → redirigir al carrito ─────────
} else {
    header('Location: cart.php');
    exit;
}

$metodos       = ['PAYPHONE' => 'Payphone', 'TRANSFERENCIA' => 'Transferencia bancaria', 'CONTRA_ENTREGA' => 'Contra entrega'];
$entregas      = ['RETIRO' => 'Retiro en tienda', 'SERVIENTREGA' => 'Envío Servientrega'];
$metodo_label  = $metodos[$datos['metodo_pago'] ?? ''] ?? ($datos['metodo_pago'] ?? '');
$entrega_label = $entregas[$datos['tipo_entrega'] ?? ''] ?? ($datos['tipo_entrega'] ?? '');

// ── Construir URL de WhatsApp con mensaje completo ──────────────────
$_wa_nombre   = trim(($pedido_creado['nombres_comprador'] ?? $datos['nombres'] ?? '') . ' ' . ($datos['apellidos'] ?? ''));
$_wa_orden    = $pedido_creado['numero_orden'] ?? '';
$_wa_cedula   = $datos['cedula']   ?? '';
$_wa_telefono = $datos['telefono'] ?? '';
$_wa_entrega  = $entrega_label;
$_wa_pago     = $metodo_label;

// ── Construir líneas de productos y subtotal ───────────────────────
$_wa_productos = '';
$_wa_subtotal  = 0;

// Fuente 1: carrito_original (snapshot guardado antes del PRG)
$fuente_items = $carrito_original ?? [];

// Fuente 2: carrito en sesión actual (si no hubo PRG, ej: error)
if (empty($fuente_items) && !empty($carrito)) {
    $fuente_items = $carrito;
}

// Fuente 3: items devueltos por la API en el resultado
if (empty($fuente_items) && !empty($pedido_creado['items'])) {
    foreach ($pedido_creado['items'] as $item) {
        $qty    = $item['cantidad']        ?? 1;
        $nombre = $item['nombre']          ?? $item['producto'] ?? $item['name'] ?? 'Producto';
        $precio = $item['precio_unitario'] ?? $item['precio']   ?? 0;
        $linea  = $qty * $precio;
        $_wa_subtotal += $linea;
        $_wa_productos .= "  \u{2022} {$qty}x {$nombre} \u{2014} $" . number_format($precio, 2) . "\n";
    }
    $fuente_items = []; // ya procesados arriba
}

// Procesar fuente 1 o 2 (formato carrito de sesión)
if (!empty($fuente_items)) {
    foreach (array_values($fuente_items) as $item) {
        $qty    = $item['cantidad'] ?? 1;
        $nombre = $item['nombre']   ?? $item['name']    ?? 'Producto';
        $precio = $item['precio']   ?? $item['price']   ?? 0;
        $linea  = $qty * $precio;
        $_wa_subtotal += $linea;
        $_wa_productos .= "  \u{2022} {$qty}x " . strtolower($nombre) . " \u{2014} $" . number_format($precio, 2) . "\n";
    }
}

// Si no se pudieron obtener productos de ninguna fuente
if (empty($_wa_productos)) {
    $_wa_productos = "  \u{2022} Ver detalle en tienda\n";
}

$_wa_envio = number_format(($pedido_creado['costo_envio'] ?? $pedido_creado['envio'] ?? 0), 2);
$_wa_total = number_format($pedido_creado['total'] ?? 0, 2);

// Fecha del pedido
$_wa_fecha_raw = $pedido_creado['fecha'] ?? $pedido_creado['created_at'] ?? $pedido_creado['fecha_creacion'] ?? null;
if ($_wa_fecha_raw) {
    $_wa_fecha = date('d/m/Y H:i', strtotime($_wa_fecha_raw));
} else {
    $_wa_fecha = date('d/m/Y H:i');
}

// ── Armar mensaje ──────────────────────────────────────────────────
$_wa_msg  = " NUEVO PEDIDO #{$_wa_orden}\n";
$_wa_msg .= " Cliente: {$_wa_nombre}\n";
if ($_wa_cedula)   $_wa_msg .= " C\xC3\xA9dula: {$_wa_cedula}\n";
if ($_wa_telefono) $_wa_msg .= " Tel\xC3\xA9fono: {$_wa_telefono}\n";
$_wa_msg .= " Productos:\n";
$_wa_msg .= $_wa_productos;
$_wa_msg .= "Subtotal: $" . number_format($_wa_subtotal, 2) . "\n";
$_wa_msg .= " Env\xC3\xADo: $" . $_wa_envio . "\n";
$_wa_msg .= " TOTAL: $" . $_wa_total . "\n";
$_wa_msg .= " Entrega: {$_wa_entrega}\n";
$_wa_msg .= " Pago: {$_wa_pago}\n";

if (!empty($datos['tipo_entrega']) && $datos['tipo_entrega'] === 'SERVIENTREGA') {
    if (!empty($datos['ciudad']))    $_wa_msg .= "\xF0\x9F\x93\x8D Destino: " . $datos['ciudad'] . ", " . ($datos['provincia'] ?? '') . "\n";
    if (!empty($datos['direccion'])) $_wa_msg .= "\xF0\x9F\x8F\xA0 Direcci\xC3\xB3n: " . $datos['direccion'] . "\n";
    if (!empty($datos['referencia'])) $_wa_msg .= "\xF0\x9F\x93\x8C Referencia: " . $datos['referencia'] . "\n";
}

if (!empty($datos['metodo_pago']) && $datos['metodo_pago'] === 'TRANSFERENCIA') {
    if (!empty($datos['banco_origen']))       $_wa_msg .= "\xF0\x9F\x8F\xA6 Banco: " . $datos['banco_origen'] . "\n";
    if (!empty($datos['numero_comprobante'])) $_wa_msg .= "\xF0\x9F\xA7\xBE Comprobante: " . $datos['numero_comprobante'] . "\n";
}

if (!empty($datos['observaciones'])) {
    $_wa_msg .= "\xF0\x9F\x93\x9D Observaciones: " . $datos['observaciones'] . "\n";
}

$_wa_msg .= "Pedido realizado el {$_wa_fecha}";

$pedido_creado['whatsapp_url'] = 'https://wa.me/593996628440?text=' . rawurlencode($_wa_msg);
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Pedido confirmado — Vpmotos</title>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Audiowide&family=Orbitron:wght@400..900&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
  <link rel="stylesheet" href="./includes/menu.css">
  <style>
    *, *::before, *::after { margin:0; padding:0; box-sizing:border-box; }
    :root {
      --bg:#0a0a0a; --surface:#111111; --surface2:#1a1a1a;
      --border:rgba(255,255,255,0.07); --accent:#d2ed05; --accent2:#a8bc04;
      --text:#e8e8e8; --muted:#666; --danger:#ff4444;
      --whatsapp:#25d366; --whatsapp2:#1da851;
      --radius:10px;
    }
    body { font-family:'Audiowide',sans-serif; background:var(--bg); color:var(--text); overflow-x:hidden; }

    .shop-hero {
      position:relative; width:100%; height:200px;
      overflow:hidden; display:flex; align-items:flex-end; padding:32px 60px;
    }
    .shop-hero::before {
      content:''; position:absolute; inset:0; z-index:1;
      background:linear-gradient(135deg,rgba(210,237,5,0.08) 0%,transparent 50%),
        linear-gradient(180deg,rgba(0,0,0,0.1) 0%,rgba(0,0,0,0.8) 100%);
    }
    .shop-hero-bg {
      position:absolute; inset:0; z-index:0;
      background:radial-gradient(ellipse 80% 60% at 50% 0%,rgba(210,237,5,0.06) 0%,transparent 70%), #0a0a0a;
    }
    .shop-hero-bg::after {
      content:''; position:absolute; inset:0;
      background-image:linear-gradient(rgba(210,237,5,0.04) 1px,transparent 1px),
        linear-gradient(90deg,rgba(210,237,5,0.04) 1px,transparent 1px);
      background-size:60px 60px;
    }
    .shop-hero-content { position:relative; z-index:2; }
    .hero-line { width:60px; height:2px; background:var(--accent); margin-bottom:14px; }
    .shop-hero-content h1 {
      font-family:'Orbitron',sans-serif; font-size:clamp(1.6rem,3.5vw,2.8rem);
      font-weight:400; letter-spacing:0.2em; color:#fff;
    }
    .shop-hero-content h1 span { color:var(--accent); }

    .steps-bar {
      background:var(--surface); border-bottom:1px solid var(--border);
      padding:16px 60px; display:flex; align-items:center;
    }
    .step { display:flex; align-items:center; gap:10px; font-size:0.6rem; letter-spacing:0.15em; color:var(--muted); }
    .step.active { color:var(--accent); }
    .step.done   { color:var(--muted); }
    .step-num {
      width:26px; height:26px; border-radius:50%; border:1px solid currentColor;
      display:flex; align-items:center; justify-content:center;
      font-family:'Orbitron',sans-serif; font-size:0.6rem; flex-shrink:0;
    }
    .step.active .step-num { background:var(--accent); color:#000; border-color:var(--accent); font-weight:700; }
    .step.done .step-num { background:rgba(210,237,5,0.15); color:var(--accent); border-color:rgba(210,237,5,0.3); }
    .step-sep { width:48px; height:1px; background:var(--border); margin:0 10px; flex-shrink:0; }

    .confirm-wrap {
      max-width:860px; margin:0 auto; padding:36px 40px 80px;
      display:flex; flex-direction:column; gap:20px;
    }

    .success-banner {
      background:rgba(210,237,5,0.06); border:1px solid rgba(210,237,5,0.25);
      border-radius:var(--radius); padding:32px 36px;
      display:flex; align-items:center; gap:24px;
      position:relative; overflow:hidden;
    }
    .success-banner::before {
      content:''; position:absolute; top:0; left:0; right:0; height:2px;
      background:linear-gradient(90deg, transparent, var(--accent), transparent);
    }
    .success-icon {
      width:64px; height:64px; border-radius:50%; flex-shrink:0;
      background:rgba(210,237,5,0.15); border:2px solid rgba(210,237,5,0.4);
      display:flex; align-items:center; justify-content:center;
      font-size:1.8rem; color:var(--accent);
    }
    .success-text h2 {
      font-family:'Orbitron',sans-serif; font-size:1.1rem; font-weight:400;
      color:#fff; letter-spacing:0.1em; margin-bottom:6px;
    }
    .success-text p { font-size:0.7rem; color:var(--muted); line-height:1.7; }
    .order-number-badge {
      display:inline-flex; align-items:center; gap:8px; margin-top:10px;
      background:rgba(210,237,5,0.1); border:1px solid rgba(210,237,5,0.3);
      border-radius:6px; padding:6px 14px;
    }
    .order-number-badge span { font-size:0.6rem; color:var(--muted); letter-spacing:.1em; }
    .order-number-badge strong {
      font-family:'Orbitron',sans-serif; font-size:0.9rem; color:var(--accent); letter-spacing:.08em;
    }

    .error-banner {
      background:rgba(255,68,68,0.08); border:1px solid rgba(255,68,68,0.3);
      border-radius:var(--radius); padding:24px 28px;
      display:flex; align-items:flex-start; gap:16px;
    }
    .error-banner .error-icon { font-size:1.5rem; flex-shrink:0; margin-top:2px; color:var(--danger); }
    .error-banner h3 { font-family:'Orbitron',sans-serif; font-size:0.8rem; color:var(--danger); margin-bottom:6px; }
    .error-banner p { font-size:0.68rem; color:#ff9999; line-height:1.6; }
    .error-actions { display:flex; gap:10px; flex-wrap:wrap; margin-top:14px; }
    .btn-retry {
      display:inline-flex; align-items:center; gap:8px; padding:10px 20px;
      background:rgba(255,68,68,0.15); border:1px solid rgba(255,68,68,0.4);
      border-radius:8px; color:var(--danger); text-decoration:none;
      font-size:0.65rem; letter-spacing:.1em; transition:all .2s;
    }
    .btn-retry:hover { background:rgba(255,68,68,0.25); }

    .details-grid { display:grid; grid-template-columns:1fr 1fr; gap:16px; }
    .detail-card {
      background:var(--surface); border:1px solid var(--border);
      border-radius:var(--radius); overflow:hidden;
    }
    .detail-header {
      padding:14px 20px; border-bottom:1px solid var(--border);
      display:flex; align-items:center; gap:10px;
    }
    .detail-header .dh-icon { font-size:1rem; color:var(--accent); }
    .detail-header h3 { font-family:'Orbitron',sans-serif; font-size:0.65rem; letter-spacing:.2em; color:#fff; }
    .detail-body { padding:16px 20px; display:flex; flex-direction:column; gap:10px; }
    .detail-row {
      display:flex; justify-content:space-between; align-items:flex-start;
      gap:12px; font-size:0.65rem;
    }
    .detail-row .dr-key { color:var(--muted); flex-shrink:0; letter-spacing:.05em; }
    .detail-row .dr-val { color:var(--text); text-align:right; }
    .detail-row .dr-val.accent { color:var(--accent); font-family:'Orbitron',sans-serif; }

    .next-steps-card {
      background:var(--surface); border:1px solid var(--border);
      border-radius:var(--radius); overflow:hidden;
    }
    .ns-header { padding:16px 24px; border-bottom:1px solid var(--border); }
    .ns-header h3 { font-family:'Orbitron',sans-serif; font-size:0.7rem; letter-spacing:.2em; color:#fff; }
    .ns-steps { padding:20px 24px; display:flex; flex-direction:column; gap:14px; }
    .ns-step { display:flex; align-items:flex-start; gap:16px; }
    .ns-step-num {
      width:30px; height:30px; border-radius:50%; background:rgba(210,237,5,0.1);
      border:1px solid rgba(210,237,5,0.3); display:flex; align-items:center; justify-content:center;
      font-family:'Orbitron',sans-serif; font-size:0.65rem; color:var(--accent); flex-shrink:0;
    }
    .ns-step-text { flex:1; }
    .ns-step-text h4 { font-family:'Orbitron',sans-serif; font-size:0.65rem; color:#fff; margin-bottom:3px; letter-spacing:.05em; }
    .ns-step-text p { font-size:0.62rem; color:var(--muted); line-height:1.6; }
    .ns-sep { width:1px; height:14px; background:var(--border); margin-left:14px; }

    .whatsapp-section {
      background:rgba(37,211,102,0.06); border:1px solid rgba(37,211,102,0.2);
      border-radius:var(--radius); padding:24px 28px;
      display:flex; align-items:center; justify-content:space-between; gap:20px; flex-wrap:wrap;
    }
    .wa-text h3 { font-family:'Orbitron',sans-serif; font-size:0.85rem; color:#fff; letter-spacing:.08em; margin-bottom:6px; display:flex; align-items:center; gap:10px; }
    .wa-text h3 i { color:var(--whatsapp); font-size:1.1rem; }
    .wa-text p { font-size:0.68rem; color:var(--muted); line-height:1.6; max-width:460px; }
    .btn-whatsapp {
      display:inline-flex; align-items:center; gap:12px; padding:14px 28px;
      background:var(--whatsapp); color:#fff; text-decoration:none;
      font-family:'Orbitron',sans-serif; font-size:0.75rem; font-weight:700;
      letter-spacing:.1em; border-radius:var(--radius);
      transition:all .25s; white-space:nowrap; flex-shrink:0;
    }
    .btn-whatsapp:hover { background:var(--whatsapp2); transform:translateY(-3px); box-shadow:0 12px 36px rgba(37,211,102,.35); }
    .btn-whatsapp i { font-size:1.3rem; }

    .final-actions { display:flex; gap:12px; flex-wrap:wrap; }
    .btn-final {
      display:inline-flex; align-items:center; gap:8px; padding:12px 24px; text-decoration:none;
      font-family:'Orbitron',sans-serif; font-size:0.65rem; letter-spacing:.1em;
      border-radius:var(--radius); transition:all .2s;
    }
    .btn-tienda { background:var(--accent); color:#000; font-weight:700; }
    .btn-tienda:hover { background:var(--accent2); transform:translateY(-2px); box-shadow:0 8px 24px rgba(210,237,5,.3); }
    .btn-inicio { background:transparent; color:var(--muted); border:1px solid var(--border); }
    .btn-inicio:hover { border-color:rgba(210,237,5,.3); color:var(--accent); }

    @media(max-width:800px) {
      .confirm-wrap { padding:20px 16px 60px; gap:16px; }
      .success-banner { flex-direction:column; align-items:flex-start; gap:16px; padding:24px; }
      .details-grid { grid-template-columns:1fr; }
      .whatsapp-section { flex-direction:column; align-items:flex-start; }
      .btn-whatsapp { width:100%; justify-content:center; }
      .steps-bar { padding:12px 16px; }
      .step-sep { width:24px; }
      .step span { display:none; }
      .shop-hero { padding:26px 16px; height:170px; }
    }
    @media(max-width:480px) {
      .success-icon { width:50px; height:50px; font-size:1.4rem; }
      .final-actions { flex-direction:column; }
      .btn-final { justify-content:center; }
    }

    @keyframes fadeUp {
      from { opacity:0; transform:translateY(20px); }
      to   { opacity:1; transform:translateY(0); }
    }
    .success-banner   { animation:fadeUp .5s ease both; }
    .details-grid     { animation:fadeUp .5s .1s ease both; }
    .whatsapp-section { animation:fadeUp .5s .2s ease both; }
    .next-steps-card  { animation:fadeUp .5s .15s ease both; }
  </style>
</head>
<body>
<?php include './includes/menu.php'; ?>

<section class="shop-hero">
  <div class="shop-hero-bg"></div>
  <div class="shop-hero-content">
    <div class="hero-line"></div>
    <?php if ($pedido_creado): ?>
      <h1>¡Pedido <span>Registrado!</span></h1>
    <?php else: ?>
      <h1>Confirmación de <span>Pedido</span></h1>
    <?php endif; ?>
  </div>
</section>

<div class="steps-bar">
  <div class="step done"><div class="step-num"><i class="bi bi-check-lg"></i></div><span>CARRITO</span></div>
  <div class="step-sep"></div>
  <div class="step done"><div class="step-num"><i class="bi bi-check-lg"></i></div><span>DATOS</span></div>
  <div class="step-sep"></div>
  <div class="step active"><div class="step-num">3</div><span>CONFIRMACIÓN</span></div>
</div>

<div class="confirm-wrap">

  <?php if ($error_pedido): ?>
  <div class="error-banner">
    <i class="bi bi-exclamation-triangle-fill error-icon"></i>
    <div>
      <h3>No se pudo procesar el pedido</h3>
      <p><?= htmlspecialchars(is_string($error_pedido) ? $error_pedido : 'Error al conectar con el servidor.') ?></p>
      <div class="error-actions">
        <a href="checkout.php" class="btn-retry"><i class="bi bi-arrow-left"></i> Volver al checkout</a>
        <a href="cart.php" class="btn-retry"><i class="bi bi-cart3"></i> Ver carrito</a>
      </div>
    </div>
  </div>

  <?php else: ?>
  <div class="success-banner">
    <div class="success-icon"><i class="bi bi-check2-circle"></i></div>
    <div class="success-text">
      <h2>¡Tu pedido fue registrado exitosamente!</h2>
      <p>
        Hola <strong><?= htmlspecialchars($pedido_creado['nombres_comprador'] ?? $datos['nombres'] ?? '') ?></strong>, tu pedido ha sido recibido.<br>
        Ahora envíanos el pedido por WhatsApp para coordinar el pago y la entrega.
      </p>
      <div class="order-number-badge">
        <span>NÚMERO DE ORDEN</span>
        <strong><?= htmlspecialchars($pedido_creado['numero_orden']) ?></strong>
      </div>
    </div>
  </div>

  <div class="details-grid">
    <div class="detail-card">
      <div class="detail-header">
        <i class="bi bi-receipt dh-icon"></i>
        <h3>RESUMEN DEL PEDIDO</h3>
      </div>
      <div class="detail-body">
        <div class="detail-row">
          <span class="dr-key">N° Orden</span>
          <span class="dr-val accent"><?= htmlspecialchars($pedido_creado['numero_orden']) ?></span>
        </div>
        <div class="detail-row">
          <span class="dr-key">Total</span>
          <span class="dr-val accent">$<?= number_format($pedido_creado['total'] ?? 0, 2) ?></span>
        </div>
        <div class="detail-row">
          <span class="dr-key">Entrega</span>
          <span class="dr-val"><?= $entrega_label ?></span>
        </div>
        <div class="detail-row">
          <span class="dr-key">Pago</span>
          <span class="dr-val"><?= $metodo_label ?></span>
        </div>
        <?php if (!empty($datos['tipo_entrega']) && $datos['tipo_entrega'] === 'SERVIENTREGA' && !empty($datos['ciudad'])): ?>
        <div class="detail-row">
          <span class="dr-key">Destino</span>
          <span class="dr-val"><?= htmlspecialchars($datos['ciudad'] . ', ' . $datos['provincia']) ?></span>
        </div>
        <?php endif; ?>
        <?php if (!empty($datos['metodo_pago']) && $datos['metodo_pago'] === 'TRANSFERENCIA' && !empty($datos['numero_comprobante'])): ?>
        <div class="detail-row">
          <span class="dr-key">Comprobante</span>
          <span class="dr-val" style="font-family:monospace;color:var(--accent)"><?= htmlspecialchars($datos['numero_comprobante']) ?></span>
        </div>
        <?php endif; ?>
      </div>
    </div>

    <div class="detail-card">
      <div class="detail-header">
        <i class="bi bi-person-fill dh-icon"></i>
        <h3>DATOS DEL COMPRADOR</h3>
      </div>
      <div class="detail-body">
        <?php if (!empty($datos['nombres'])): ?>
        <div class="detail-row">
          <span class="dr-key">Nombre</span>
          <span class="dr-val"><?= htmlspecialchars($datos['nombres'] . ' ' . ($datos['apellidos'] ?? '')) ?></span>
        </div>
        <?php endif; ?>
        <?php if (!empty($datos['cedula'])): ?>
        <div class="detail-row">
          <span class="dr-key">Cédula</span>
          <span class="dr-val"><?= htmlspecialchars($datos['cedula']) ?></span>
        </div>
        <?php endif; ?>
        <?php if (!empty($datos['telefono'])): ?>
        <div class="detail-row">
          <span class="dr-key">Teléfono</span>
          <span class="dr-val"><?= htmlspecialchars($datos['telefono']) ?></span>
        </div>
        <?php endif; ?>
        <?php if (!empty($datos['email'])): ?>
        <div class="detail-row">
          <span class="dr-key">Email</span>
          <span class="dr-val"><?= htmlspecialchars($datos['email']) ?></span>
        </div>
        <?php endif; ?>
      </div>
    </div>
  </div>

  <div class="next-steps-card">
    <div class="ns-header"><h3>¿QUÉ SIGUE AHORA?</h3></div>
    <div class="ns-steps">
      <div class="ns-step">
        <div class="ns-step-num">1</div>
        <div class="ns-step-text">
          <h4>Envía tu pedido por WhatsApp</h4>
          <p>Haz clic en el botón de abajo. El mensaje con el detalle de tu pedido ya está preparado, solo confírmalo.</p>
        </div>
      </div>
      <div class="ns-sep"></div>
      <div class="ns-step">
        <div class="ns-step-num">2</div>
        <div class="ns-step-text">
          <?php if (!empty($datos['metodo_pago']) && $datos['metodo_pago'] === 'TRANSFERENCIA'): ?>
          <h4>Confirma tu transferencia</h4>
          <p>Ya registramos tu número de comprobante. Te contactaremos para confirmar la recepción del pago.</p>
          <?php elseif (!empty($datos['metodo_pago']) && $datos['metodo_pago'] === 'CONTRA_ENTREGA'): ?>
          <h4>Pago al momento de entrega</h4>
          <p>Prepara el efectivo por el monto de tu pedido. Lo pagarás al retirar en tienda o al recibirlo.</p>
          <?php else: ?>
          <h4>Confirma el método de pago</h4>
          <p>Te indicaremos los pasos para completar el pago por WhatsApp.</p>
          <?php endif; ?>
        </div>
      </div>
      <div class="ns-sep"></div>
      <div class="ns-step">
        <div class="ns-step-num">3</div>
        <div class="ns-step-text">
          <?php if (!empty($datos['tipo_entrega']) && $datos['tipo_entrega'] === 'SERVIENTREGA'): ?>
          <h4>Coordinamos el envío</h4>
          <p>Una vez confirmado el pago, preparamos tu pedido y calculamos el costo de envío según tu provincia.</p>
          <?php else: ?>
          <h4>Recoge tu pedido</h4>
          <p>Te avisamos cuando tu pedido esté listo. Solo preséntate en tienda con tu número de orden.</p>
          <?php endif; ?>
        </div>
      </div>
    </div>
  </div>

  <div class="whatsapp-section">
    <div class="wa-text">
      <h3><i class="bi bi-whatsapp"></i> Envía tu pedido ahora</h3>
      <p>
        Tu pedido <strong style="color:#fff"><?= htmlspecialchars($pedido_creado['numero_orden']) ?></strong>
        está registrado. El siguiente paso es enviarlo por WhatsApp para que lo procesemos.
        El mensaje ya está preparado con todos los detalles.
      </p>
    </div>
    <?php if (!empty($pedido_creado['whatsapp_url'])): ?>
    <a href="<?= htmlspecialchars($pedido_creado['whatsapp_url']) ?>"
       target="_blank" rel="noopener noreferrer" class="btn-whatsapp">
      <i class="bi bi-whatsapp"></i>
      ENVIAR POR WHATSAPP
    </a>
    <?php else: ?>
    <a href="https://wa.me/593996628440?text=<?= urlencode('Hola, acabo de hacer un pedido en Vpmotos. N° de orden: ' . ($pedido_creado['numero_orden'] ?? '')) ?>"
       target="_blank" rel="noopener noreferrer" class="btn-whatsapp">
      <i class="bi bi-whatsapp"></i>
      ENVIAR POR WHATSAPP
    </a>
    <?php endif; ?>
  </div>

  <div class="final-actions">
    <a href="tienda.php" class="btn-final btn-tienda"><i class="bi bi-bag-check-fill"></i> Seguir comprando</a>
    <a href="index.php" class="btn-final btn-inicio"><i class="bi bi-house-fill"></i> Ir al inicio</a>
  </div>

  <?php endif; ?>

</div>

<?php include './includes/footer.php'; ?>
</body>
</html>