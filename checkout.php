<?php
session_start();
require_once __DIR__ . '/config/config.php';
require_once __DIR__ . '/includes/api.php';

$carrito = $_SESSION['carrito'] ?? [];
if (empty($carrito)) { header('Location: cart.php'); exit; }

$errores = [];
$datos   = $_SESSION['checkout_datos'] ?? [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombres       = trim($_POST['nombres'] ?? '');
    $apellidos     = trim($_POST['apellidos'] ?? '');
    $cedula        = trim($_POST['cedula'] ?? '');
    $telefono      = trim($_POST['telefono'] ?? '');
    $email         = trim($_POST['email'] ?? '');
    $tipo_entrega  = $_POST['tipo_entrega'] ?? 'RETIRO';
    $direccion     = trim($_POST['direccion'] ?? '');
    $ciudad        = trim($_POST['ciudad'] ?? '');
    $provincia     = trim($_POST['provincia'] ?? '');
    $referencia    = trim($_POST['referencia'] ?? '');
    $metodo_pago   = $_POST['metodo_pago'] ?? 'TRANSFERENCIA';
    $observaciones = trim($_POST['observaciones'] ?? '');
    $numero_comprobante = trim($_POST['numero_comprobante'] ?? '');
    $banco_origen       = trim($_POST['banco_origen'] ?? '');

    // ── Validaciones ──────────────────────────────────────────────
    if (strlen($nombres) < 2)    $errores['nombres']   = 'Ingresa tu nombre';
    if (strlen($apellidos) < 2)  $errores['apellidos'] = 'Ingresa tus apellidos';
    if (!preg_match('/^\d{10,13}$/', $cedula))
        $errores['cedula'] = 'Cédula/RUC inválido (10-13 dígitos)';
    if (!preg_match('/^\d{7,15}$/', preg_replace('/[\s\-]/', '', $telefono)))
        $errores['telefono'] = 'Número inválido';
    if ($email && !filter_var($email, FILTER_VALIDATE_EMAIL))
        $errores['email'] = 'Email inválido';
    if ($tipo_entrega === 'SERVIENTREGA') {
        if (strlen($direccion) < 5) $errores['direccion'] = 'Ingresa tu dirección completa';
        if (strlen($ciudad) < 2)    $errores['ciudad']    = 'Ingresa tu ciudad';
        if (!$provincia)             $errores['provincia'] = 'Selecciona una provincia';
    }

    // ── Validación y conversión base64 del comprobante ────────────
    $comprobante_base64 = '';

    if ($metodo_pago === 'TRANSFERENCIA') {
        if (empty($numero_comprobante))
            $errores['numero_comprobante'] = 'Ingresa el número de comprobante';

        if (isset($_FILES['comprobante']) && $_FILES['comprobante']['error'] === UPLOAD_ERR_OK) {
            $file     = $_FILES['comprobante'];
            $ext      = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
            $allowed  = ['jpg' => 'image/jpeg', 'jpeg' => 'image/jpeg', 'png' => 'image/png',
                         'webp' => 'image/webp', 'gif' => 'image/gif'];

            if (!array_key_exists($ext, $allowed)) {
                $errores['comprobante'] = 'Formato no permitido (jpg, png, webp, gif)';
            } elseif ($file['size'] > 5 * 1024 * 1024) {
                $errores['comprobante'] = 'La imagen no puede superar 5MB';
            } else {
                // Convertir a base64 con data URL completo
                $contenido  = file_get_contents($file['tmp_name']);
                $mime       = $allowed[$ext];
                $b64        = base64_encode($contenido);
                $comprobante_base64 = "data:{$mime};base64,{$b64}";
            }
        }
        // Si no subió archivo, comprobante_base64 queda vacío (es opcional)
    }

    // ── Armar datos para sesión ───────────────────────────────────
    $datos = compact('nombres', 'apellidos', 'cedula', 'telefono', 'email',
        'tipo_entrega', 'direccion', 'ciudad', 'provincia', 'referencia',
        'metodo_pago', 'observaciones');

    if ($metodo_pago === 'TRANSFERENCIA') {
        $datos['numero_comprobante'] = $numero_comprobante;
        $datos['banco_origen']       = $banco_origen;
        $datos['comprobante_base64'] = $comprobante_base64;
    }

    if (empty($errores)) {
        $_SESSION['checkout_datos'] = $datos;
        header('Location: order-confirm.php'); exit;
    }
}

$subtotal    = array_sum(array_map(fn($i) => $i['precio'] * $i['cantidad'], $carrito));
$total_items = array_sum(array_column($carrito, 'cantidad'));

$provincias = ['Azuay','Bolívar','Cañar','Carchi','Chimborazo','Cotopaxi','El Oro','Esmeraldas',
  'Galápagos','Guayas','Imbabura','Loja','Los Ríos','Manabí','Morona Santiago','Napo',
  'Orellana','Pastaza','Pichincha','Santa Elena','Santo Domingo de los Tsáchilas',
  'Sucumbíos','Tungurahua','Zamora Chinchipe'];

$cuentas_bancarias = [
    [
        'banco'   => 'Banco Pichincha',
        'tipo'    => 'Cuenta de Ahorros',
        'numero'  => '2100123456',
        'titular' => 'VP Motos S.A.',
        'cedula'  => '0400123456001',
        'icon'    => '🏦',
    ],
    [
        'banco'   => 'Banco Guayaquil',
        'tipo'    => 'Cuenta Corriente',
        'numero'  => '0987654321',
        'titular' => 'VP Motos S.A.',
        'cedula'  => '0400123456001',
        'icon'    => '🏛️',
    ],
];
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Checkout — Vpmotos</title>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Audiowide&family=Orbitron:wght@400..900&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="./includes/menu.css">
  <style>
    *, *::before, *::after { margin:0; padding:0; box-sizing:border-box; }
    :root {
      --bg:#0a0a0a; --surface:#111111; --surface2:#1a1a1a;
      --border:rgba(255,255,255,0.07); --accent:#d2ed05; --accent2:#a8bc04;
      --text:#e8e8e8; --muted:#666; --danger:#ff4444; --radius:10px;
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
      font-weight:400; letter-spacing:0.2em; color:#fff; line-height:1;
    }
    .shop-hero-content h1 span { color:var(--accent); }
    .breadcrumb { margin-top:8px; font-size:0.62rem; color:var(--muted); display:flex; gap:8px; align-items:center; }
    .breadcrumb a { color:var(--muted); text-decoration:none; }
    .breadcrumb a:hover { color:var(--accent); }
    .breadcrumb .sep { color:var(--border); }
    .breadcrumb .current { color:var(--accent); }

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

    .checkout-layout {
      display:grid; grid-template-columns:1fr 340px; gap:24px;
      max-width:1100px; margin:0 auto; padding:28px 40px 80px;
    }

    .form-section {
      background:var(--surface); border:1px solid var(--border);
      border-radius:var(--radius); overflow:hidden; margin-bottom:16px;
    }
    .section-header {
      padding:18px 24px; border-bottom:1px solid var(--border);
      display:flex; align-items:center; gap:14px;
    }
    .section-icon {
      width:34px; height:34px; background:rgba(210,237,5,0.08);
      border:1px solid rgba(210,237,5,0.2); border-radius:8px;
      display:flex; align-items:center; justify-content:center; font-size:1rem; flex-shrink:0;
    }
    .section-header h3 { font-family:'Orbitron',sans-serif; font-size:0.72rem; letter-spacing:0.2em; color:#fff; }
    .section-body { padding:24px; }

    .fields-grid { display:grid; grid-template-columns:1fr 1fr; gap:16px; }
    .field-full  { grid-column:1/-1; }

    .field { display:flex; flex-direction:column; gap:7px; }
    .field label {
      font-size:0.58rem; letter-spacing:0.18em; color:var(--accent);
      text-transform:uppercase;
    }
    .field input, .field select, .field textarea {
      background:var(--surface2); border:1px solid var(--border);
      border-radius:8px; padding:11px 14px; color:var(--text);
      font-family:'Audiowide',sans-serif; font-size:0.72rem;
      outline:none; transition:border-color .2s; width:100%;
    }
    .field input:focus, .field select:focus, .field textarea:focus {
      border-color:rgba(210,237,5,0.4);
    }
    .field input::placeholder, .field textarea::placeholder { color:var(--muted); }
    .field select option { background:#1a1a1a; }
    .field textarea { resize:vertical; min-height:80px; }
    .field .err { font-size:0.6rem; color:var(--danger); margin-top:2px; }
    .field input.has-error, .field select.has-error { border-color:rgba(255,68,68,0.5); }

    .radio-grid { display:grid; grid-template-columns:1fr 1fr; gap:10px; }
    .radio-option { position:relative; }
    .radio-option input[type=radio] { position:absolute; opacity:0; width:0; height:0; }
    .radio-card {
      display:block; padding:14px 16px; border:1px solid var(--border);
      border-radius:8px; cursor:pointer; transition:all .2s; background:var(--surface2);
    }
    .radio-option input:checked ~ .radio-card {
      border-color:var(--accent); background:rgba(210,237,5,0.06);
    }
    .radio-card:hover { border-color:rgba(210,237,5,0.3); }
    .radio-card-icon { font-size:1.4rem; margin-bottom:8px; }
    .radio-card-title { font-family:'Orbitron',sans-serif; font-size:0.68rem; color:#fff; margin-bottom:5px; letter-spacing:.05em; }
    .radio-card-desc { font-size:0.6rem; color:var(--muted); line-height:1.6; }
    .radio-card-badge {
      display:inline-block; margin-top:8px; padding:3px 10px;
      border-radius:20px; font-size:0.55rem; letter-spacing:0.1em;
    }
    .badge-ok   { background:rgba(210,237,5,0.1); color:var(--accent); border:1px solid rgba(210,237,5,0.25); }
    .badge-soon { background:rgba(255,255,255,0.04); color:var(--muted); border:1px solid var(--border); }
    .radio-option.disabled .radio-card { opacity:.45; cursor:not-allowed; }
    .radio-option.disabled input { pointer-events:none; }
    .radio-option.blocked .radio-card {
      opacity:.3; cursor:not-allowed; pointer-events:none; filter:grayscale(1);
    }
    .radio-option.blocked input { pointer-events:none; }

    #envio-fields {
      display:none; margin-top:18px;
      border-top:1px solid var(--border); padding-top:18px;
    }

    #transferencia-section {
      display:none; margin-top:18px;
      border-top:1px solid var(--border); padding-top:18px;
      animation: fadeIn .3s ease;
    }
    @keyframes fadeIn { from { opacity:0; transform:translateY(-8px); } to { opacity:1; transform:translateY(0); } }

    .transfer-notice {
      background:rgba(210,237,5,0.05); border:1px solid rgba(210,237,5,0.2);
      border-radius:8px; padding:14px 16px; margin-bottom:16px;
      font-size:0.65rem; color:var(--muted); line-height:1.7;
      display:flex; gap:10px; align-items:flex-start;
    }
    .transfer-notice span { font-size:1rem; flex-shrink:0; }

    .bank-cards { display:flex; flex-direction:column; gap:10px; margin-bottom:18px; }
    .bank-card {
      background:var(--surface2); border:1px solid var(--border);
      border-radius:8px; padding:14px 18px;
      display:grid; grid-template-columns:auto 1fr auto; gap:12px; align-items:center;
    }
    .bank-icon { font-size:1.6rem; }
    .bank-info { display:flex; flex-direction:column; gap:3px; }
    .bank-name { font-family:'Orbitron',sans-serif; font-size:0.65rem; color:#fff; letter-spacing:.05em; }
    .bank-detail { font-size:0.6rem; color:var(--muted); }
    .bank-detail strong { color:var(--text); }
    .bank-number {
      font-family:'Orbitron',sans-serif; font-size:0.85rem; color:var(--accent);
      letter-spacing:.1em; text-align:right;
    }
    .bank-copy-btn {
      background:rgba(210,237,5,0.1); border:1px solid rgba(210,237,5,0.25);
      border-radius:6px; padding:5px 10px; cursor:pointer; font-size:0.55rem;
      color:var(--accent); letter-spacing:.08em; font-family:'Audiowide',sans-serif;
      transition:all .2s; margin-top:4px; display:block; width:fit-content;
    }
    .bank-copy-btn:hover { background:rgba(210,237,5,0.2); }
    .bank-copy-btn.copied { color:#0a0a0a; background:var(--accent); border-color:var(--accent); }

    .upload-area {
      border:2px dashed rgba(210,237,5,0.25); border-radius:10px;
      padding:28px 20px; text-align:center; cursor:pointer;
      transition:all .2s; background:rgba(210,237,5,0.02);
      position:relative;
    }
    .upload-area:hover, .upload-area.dragover {
      border-color:rgba(210,237,5,0.5); background:rgba(210,237,5,0.05);
    }
    .upload-area input[type=file] {
      position:absolute; inset:0; opacity:0; cursor:pointer; width:100%; height:100%;
    }
    .upload-icon { font-size:2rem; margin-bottom:8px; }
    .upload-title { font-family:'Orbitron',sans-serif; font-size:0.7rem; color:#fff; margin-bottom:4px; }
    .upload-desc { font-size:0.6rem; color:var(--muted); }
    .upload-preview {
      display:none; margin-top:12px; padding:10px 14px;
      background:rgba(210,237,5,0.06); border:1px solid rgba(210,237,5,0.2);
      border-radius:8px; font-size:0.65rem; color:var(--accent);
      align-items:center; gap:8px;
    }
    .upload-preview.visible { display:flex; }

    /* Thumbnail preview */
    #img-preview-thumb {
      display:none; margin-top:10px; border-radius:8px;
      max-height:140px; max-width:100%; object-fit:contain;
      border:1px solid rgba(210,237,5,0.2);
    }
    #img-preview-thumb.visible { display:block; }

    #servientrega-pago-notice {
      display:none; margin-top:14px;
      background:rgba(255,200,0,0.06); border:1px solid rgba(255,200,0,0.2);
      border-radius:8px; padding:12px 16px;
      font-size:0.63rem; color:#ffcc44; line-height:1.7;
      gap:10px; align-items:flex-start;
    }
    #servientrega-pago-notice.visible { display:flex; }

    .summary-sticky { position:sticky; top:20px; }
    .summary-card {
      background:var(--surface); border:1px solid var(--border);
      border-radius:var(--radius); overflow:hidden; margin-bottom:14px;
    }
    .sum-head { padding:18px 24px; border-bottom:1px solid var(--border); }
    .sum-head h2 { font-family:'Orbitron',sans-serif; font-size:0.75rem; letter-spacing:0.2em; color:#fff; }
    .sum-body { padding:18px 24px; }
    .sum-items { display:flex; flex-direction:column; gap:10px; margin-bottom:16px; max-height:220px; overflow-y:auto; }
    .sum-items::-webkit-scrollbar { width:3px; }
    .sum-items::-webkit-scrollbar-thumb { background:var(--border); border-radius:2px; }
    .sum-item { display:flex; gap:10px; align-items:center; }
    .si-thumb {
      width:46px; height:46px; border-radius:6px; overflow:hidden;
      background:var(--surface2); flex-shrink:0; border:1px solid var(--border);
    }
    .si-thumb img { width:100%; height:100%; object-fit:cover; }
    .si-info { flex:1; min-width:0; }
    .si-nombre { font-size:0.65rem; color:#fff; white-space:nowrap; overflow:hidden; text-overflow:ellipsis; }
    .si-qty { font-size:0.58rem; color:var(--muted); margin-top:2px; }
    .si-precio { font-family:'Orbitron',sans-serif; font-size:0.72rem; color:var(--accent); white-space:nowrap; }
    .sum-divider { height:1px; background:var(--border); margin:12px 0; }
    .sum-row { display:flex; justify-content:space-between; font-size:0.67rem; margin-bottom:10px; }
    .sum-row .lbl { color:var(--muted); }
    .sum-row .val { font-family:'Orbitron',sans-serif; color:var(--text); }
    .sum-total { display:flex; justify-content:space-between; padding-top:12px; border-top:1px solid var(--border); }
    .sum-total .lbl { color:#fff; font-size:0.75rem; letter-spacing:.08em; }
    .sum-total .val { font-family:'Orbitron',sans-serif; font-size:1.15rem; color:var(--accent); }

    .btn-submit {
      display:block; width:100%; padding:15px; margin-top:14px; text-align:center;
      background:var(--accent); color:#000; font-family:'Orbitron',sans-serif;
      font-size:0.75rem; letter-spacing:0.15em; font-weight:700;
      border-radius:var(--radius); border:none; cursor:pointer; transition:all .25s;
    }
    .btn-submit:hover { background:var(--accent2); transform:translateY(-3px); box-shadow:0 12px 36px rgba(210,237,5,0.3); }
    .btn-back {
      display:block; width:100%; padding:12px; margin-top:10px; text-align:center; text-decoration:none;
      background:transparent; color:var(--muted); font-family:'Audiowide',sans-serif; font-size:0.65rem;
      border-radius:var(--radius); border:1px solid var(--border); transition:all .2s;
    }
    .btn-back:hover { border-color:rgba(210,237,5,0.3); color:var(--accent); }

    @media(max-width:1024px) {
      .checkout-layout { grid-template-columns:1fr 300px; gap:18px; padding:24px 24px 60px; }
      .steps-bar { padding:14px 24px; }
      .shop-hero { padding:28px 24px; }
    }
    @media(max-width:800px) {
      .checkout-layout { grid-template-columns:1fr; padding:16px 16px 60px; }
      .summary-sticky { position:static; }
      .steps-bar { padding:12px 16px; }
      .step-sep { width:24px; }
      .step span { display:none; }
      .fields-grid { grid-template-columns:1fr; }
      .field-full { grid-column:1; }
      .radio-grid  { grid-template-columns:1fr; }
    }
    @media(max-width:480px) {
      .shop-hero { height:170px; padding:20px 16px; }
      .section-body { padding:16px; }
    }
  </style>
</head>
<body>
<?php include './includes/menu.php'; ?>

<section class="shop-hero">
  <div class="shop-hero-bg"></div>
  <div class="shop-hero-content">
    <div class="hero-line"></div>
    <h1>Datos de <span>Entrega</span></h1>
    <div class="breadcrumb">
      <a href="index.php">Inicio</a><span class="sep">/</span>
      <a href="tienda.php">Tienda</a><span class="sep">/</span>
      <a href="cart.php">Carrito</a><span class="sep">/</span>
      <span class="current">Checkout</span>
    </div>
  </div>
</section>

<div class="steps-bar">
  <div class="step done"><div class="step-num">✓</div><span>CARRITO</span></div>
  <div class="step-sep"></div>
  <div class="step active"><div class="step-num">2</div><span>DATOS</span></div>
  <div class="step-sep"></div>
  <div class="step"><div class="step-num">3</div><span>CONFIRMACIÓN</span></div>
</div>

<form method="POST" id="checkout-form" enctype="multipart/form-data">
<div class="checkout-layout">

  <div>

    <!-- Datos personales -->
    <div class="form-section">
      <div class="section-header">
        <div class="section-icon">👤</div>
        <h3>DATOS PERSONALES</h3>
      </div>
      <div class="section-body">
        <div class="fields-grid">
          <div class="field">
            <label>Nombres *</label>
            <input type="text" name="nombres" value="<?= htmlspecialchars($datos['nombres'] ?? '') ?>"
              placeholder="Juan Carlos" class="<?= isset($errores['nombres']) ? 'has-error' : '' ?>">
            <?php if (isset($errores['nombres'])): ?><span class="err"><?= $errores['nombres'] ?></span><?php endif; ?>
          </div>
          <div class="field">
            <label>Apellidos *</label>
            <input type="text" name="apellidos" value="<?= htmlspecialchars($datos['apellidos'] ?? '') ?>"
              placeholder="Pérez Gómez" class="<?= isset($errores['apellidos']) ? 'has-error' : '' ?>">
            <?php if (isset($errores['apellidos'])): ?><span class="err"><?= $errores['apellidos'] ?></span><?php endif; ?>
          </div>
          <div class="field">
            <label>Cédula / RUC *</label>
            <input type="text" name="cedula" value="<?= htmlspecialchars($datos['cedula'] ?? '') ?>"
              placeholder="1234567890" maxlength="13" inputmode="numeric"
              class="<?= isset($errores['cedula']) ? 'has-error' : '' ?>">
            <?php if (isset($errores['cedula'])): ?><span class="err"><?= $errores['cedula'] ?></span><?php endif; ?>
          </div>
          <div class="field">
            <label>Teléfono / WhatsApp *</label>
            <input type="tel" name="telefono" value="<?= htmlspecialchars($datos['telefono'] ?? '') ?>"
              placeholder="0999999999" inputmode="tel"
              class="<?= isset($errores['telefono']) ? 'has-error' : '' ?>">
            <?php if (isset($errores['telefono'])): ?><span class="err"><?= $errores['telefono'] ?></span><?php endif; ?>
          </div>
          <div class="field field-full">
            <label>Email (opcional)</label>
            <input type="email" name="email" value="<?= htmlspecialchars($datos['email'] ?? '') ?>"
              placeholder="correo@ejemplo.com" inputmode="email"
              class="<?= isset($errores['email']) ? 'has-error' : '' ?>">
            <?php if (isset($errores['email'])): ?><span class="err"><?= $errores['email'] ?></span><?php endif; ?>
          </div>
        </div>
      </div>
    </div>

    <!-- Entrega -->
    <div class="form-section">
      <div class="section-header">
        <div class="section-icon">🚚</div>
        <h3>TIPO DE ENTREGA</h3>
      </div>
      <div class="section-body">
        <div class="radio-grid">
          <label class="radio-option">
            <input type="radio" name="tipo_entrega" value="RETIRO"
              <?= ($datos['tipo_entrega'] ?? 'RETIRO') === 'RETIRO' ? 'checked' : '' ?>
              onchange="toggleEntrega(this)">
            <div class="radio-card">
              <div class="radio-card-icon">🏪</div>
              <div class="radio-card-title">Retiro en tienda</div>
              <div class="radio-card-desc">Recoge en nuestro local. Te avisamos cuando esté listo.</div>
              <span class="radio-card-badge badge-ok">SIN COSTO</span>
            </div>
          </label>
          <label class="radio-option">
            <input type="radio" name="tipo_entrega" value="SERVIENTREGA"
              <?= ($datos['tipo_entrega'] ?? '') === 'SERVIENTREGA' ? 'checked' : '' ?>
              onchange="toggleEntrega(this)">
            <div class="radio-card">
              <div class="radio-card-icon">📦</div>
              <div class="radio-card-title">Envío Servientrega</div>
              <div class="radio-card-desc">A todo Ecuador. Coordinamos el costo por WhatsApp.</div>
              <span class="radio-card-badge badge-ok">A DOMICILIO</span>
            </div>
          </label>
        </div>

        <div id="envio-fields">
          <div class="fields-grid">
            <div class="field field-full">
              <label>Dirección completa *</label>
              <input type="text" name="direccion" value="<?= htmlspecialchars($datos['direccion'] ?? '') ?>"
                placeholder="Calle principal, número, sector..."
                class="<?= isset($errores['direccion']) ? 'has-error' : '' ?>">
              <?php if (isset($errores['direccion'])): ?><span class="err"><?= $errores['direccion'] ?></span><?php endif; ?>
            </div>
            <div class="field">
              <label>Ciudad *</label>
              <input type="text" name="ciudad" value="<?= htmlspecialchars($datos['ciudad'] ?? '') ?>"
                placeholder="Tulcán" class="<?= isset($errores['ciudad']) ? 'has-error' : '' ?>">
              <?php if (isset($errores['ciudad'])): ?><span class="err"><?= $errores['ciudad'] ?></span><?php endif; ?>
            </div>
            <div class="field">
              <label>Provincia *</label>
              <select name="provincia" class="<?= isset($errores['provincia']) ? 'has-error' : '' ?>">
                <option value="">— Selecciona —</option>
                <?php foreach ($provincias as $p): ?>
                  <option value="<?= $p ?>" <?= ($datos['provincia'] ?? '') === $p ? 'selected' : '' ?>><?= $p ?></option>
                <?php endforeach; ?>
              </select>
              <?php if (isset($errores['provincia'])): ?><span class="err"><?= $errores['provincia'] ?></span><?php endif; ?>
            </div>
            <div class="field field-full">
              <label>Referencia (opcional)</label>
              <input type="text" name="referencia" value="<?= htmlspecialchars($datos['referencia'] ?? '') ?>"
                placeholder="Cerca de, frente a, color de casa...">
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Pago -->
    <div class="form-section">
      <div class="section-header">
        <div class="section-icon">💳</div>
        <h3>MÉTODO DE PAGO</h3>
      </div>
      <div class="section-body">
        <div class="radio-grid">
          <label class="radio-option" id="opt-transferencia">
            <input type="radio" name="metodo_pago" value="TRANSFERENCIA" id="radio-transferencia"
              <?= ($datos['metodo_pago'] ?? 'TRANSFERENCIA') === 'TRANSFERENCIA' ? 'checked' : '' ?>
              onchange="togglePago(this)">
            <div class="radio-card">
              <div class="radio-card-icon">🏦</div>
              <div class="radio-card-title">Transferencia</div>
              <div class="radio-card-desc">Transfiere a nuestras cuentas bancarias y envía el comprobante.</div>
            </div>
          </label>
          <label class="radio-option">
            <input type="radio" name="metodo_pago" value="CONTRA_ENTREGA"
              <?= ($datos['metodo_pago'] ?? '') === 'CONTRA_ENTREGA' ? 'checked' : '' ?>
              onchange="togglePago(this)">
            <div class="radio-card">
              <div class="radio-card-icon">💵</div>
              <div class="radio-card-title">Contra entrega</div>
              <div class="radio-card-desc">Paga en efectivo al retirar en tienda o al recibir tu pedido.</div>
            </div>
          </label>
          <label class="radio-option disabled">
            <input type="radio" name="metodo_pago" value="PAYPHONE" disabled>
            <div class="radio-card">
              <div class="radio-card-icon">💳</div>
              <div class="radio-card-title">Payphone</div>
              <div class="radio-card-desc">Pago con tarjeta de crédito o débito en línea.</div>
              <span class="radio-card-badge badge-soon">PRÓXIMAMENTE</span>
            </div>
          </label>
        </div>

        <!-- Aviso Servientrega -->
        <div id="servientrega-pago-notice" class="<?= ($datos['tipo_entrega'] ?? '') === 'SERVIENTREGA' ? 'visible' : '' ?>">
          <span>ℹ️</span>
          <span>Para envíos por Servientrega el pago se coordina directamente por WhatsApp. Te contactaremos para confirmar el costo de envío y los datos de pago.</span>
        </div>

        <!-- Sección transferencia -->
        <div id="transferencia-section">
          <div class="transfer-notice">
            <span>💡</span>
            <span>Realiza la transferencia a cualquiera de las siguientes cuentas y luego sube el comprobante con el número de transacción.</span>
          </div>

          <!-- Cuentas bancarias -->
          <div class="bank-cards">
            <?php foreach ($cuentas_bancarias as $cuenta): ?>
            <div class="bank-card">
              <div class="bank-icon"><?= $cuenta['icon'] ?></div>
              <div class="bank-info">
                <div class="bank-name"><?= htmlspecialchars($cuenta['banco']) ?></div>
                <div class="bank-detail"><?= htmlspecialchars($cuenta['tipo']) ?> · <strong><?= htmlspecialchars($cuenta['titular']) ?></strong></div>
                <div class="bank-detail">RUC/Cédula: <?= htmlspecialchars($cuenta['cedula']) ?></div>
                <button type="button" class="bank-copy-btn" onclick="copiarCuenta(this, '<?= $cuenta['numero'] ?>')">COPIAR NÚMERO</button>
              </div>
              <div style="text-align:right">
                <div class="bank-number"><?= htmlspecialchars($cuenta['numero']) ?></div>
              </div>
            </div>
            <?php endforeach; ?>
          </div>

          <!-- Número de comprobante y banco -->
          <div class="fields-grid" style="margin-bottom:16px">
            <div class="field">
              <label>Número de comprobante / transacción *</label>
              <input type="text" name="numero_comprobante"
                value="<?= htmlspecialchars($datos['numero_comprobante'] ?? '') ?>"
                placeholder="Ej: 00123456789"
                class="<?= isset($errores['numero_comprobante']) ? 'has-error' : '' ?>">
              <?php if (isset($errores['numero_comprobante'])): ?><span class="err"><?= $errores['numero_comprobante'] ?></span><?php endif; ?>
            </div>
            <div class="field">
              <label>Banco de origen (opcional)</label>
              <input type="text" name="banco_origen"
                value="<?= htmlspecialchars($datos['banco_origen'] ?? '') ?>"
                placeholder="Ej: Banco Pichincha">
            </div>
          </div>

          <!-- Upload comprobante -->
          <div class="field">
            <label>Foto / imagen del comprobante (opcional)</label>
            <div class="upload-area" id="upload-area">
              <input type="file" name="comprobante" id="comprobante-input"
                accept=".jpg,.jpeg,.png,.webp,.gif"
                onchange="previewFile(this)">
              <div class="upload-icon">📎</div>
              <div class="upload-title">Arrastra o haz clic para subir</div>
              <div class="upload-desc">JPG, PNG, WEBP o GIF · Máx. 5MB</div>
            </div>
            <div class="upload-preview" id="upload-preview">
              <span>✅</span>
              <span id="upload-filename">archivo.jpg</span>
            </div>
            <img id="img-preview-thumb" src="" alt="Vista previa">
            <?php if (isset($errores['comprobante'])): ?><span class="err"><?= $errores['comprobante'] ?></span><?php endif; ?>
          </div>
        </div>

      </div>
    </div>

    <!-- Observaciones -->
    <div class="form-section">
      <div class="section-header">
        <div class="section-icon">📝</div>
        <h3>OBSERVACIONES</h3>
      </div>
      <div class="section-body">
        <div class="field">
          <label>Notas adicionales (opcional)</label>
          <textarea name="observaciones"
            placeholder="Instrucciones especiales, preguntas sobre el producto, etc."><?= htmlspecialchars($datos['observaciones'] ?? '') ?></textarea>
        </div>
      </div>
    </div>

  </div>

  <!-- RESUMEN -->
  <div class="summary-sticky">
    <div class="summary-card">
      <div class="sum-head"><h2>TU PEDIDO</h2></div>
      <div class="sum-body">
        <div class="sum-items">
          <?php foreach ($carrito as $item): ?>
          <div class="sum-item">
            <div class="si-thumb">
              <?php if ($item['imagen']): ?>
                <img src="<?= htmlspecialchars($item['imagen']) ?>" alt="">
              <?php endif; ?>
            </div>
            <div class="si-info">
              <div class="si-nombre"><?= htmlspecialchars($item['nombre']) ?></div>
              <div class="si-qty">× <?= $item['cantidad'] ?></div>
            </div>
            <div class="si-precio">$<?= number_format($item['precio'] * $item['cantidad'], 2) ?></div>
          </div>
          <?php endforeach; ?>
        </div>
        <div class="sum-divider"></div>
        <div class="sum-row">
          <span class="lbl">Subtotal</span>
          <span class="val">$<?= number_format($subtotal, 2) ?></span>
        </div>
        <div class="sum-row">
          <span class="lbl">Envío</span>
          <span class="val" style="font-size:.6rem;color:var(--muted)">Por coordinar</span>
        </div>
        <div class="sum-total">
          <span class="lbl">TOTAL</span>
          <span class="val">$<?= number_format($subtotal, 2) ?></span>
        </div>
      </div>
    </div>
    <button type="submit" class="btn-submit">CONFIRMAR PEDIDO →</button>
    <a href="cart.php" class="btn-back">← Volver al carrito</a>
  </div>

</div>
</form>

<?php include './includes/footer.php'; ?>
<script>
function toggleEntrega(radio) {
  const esServientrega = radio.value === 'SERVIENTREGA';
  document.getElementById('envio-fields').style.display = esServientrega ? 'block' : 'none';

  const optTransferencia  = document.getElementById('opt-transferencia');
  const radioTransferencia = document.getElementById('radio-transferencia');
  const notice      = document.getElementById('servientrega-pago-notice');
  const transSection = document.getElementById('transferencia-section');

  if (esServientrega) {
    optTransferencia.classList.add('blocked');
    if (radioTransferencia.checked) {
      radioTransferencia.checked = false;
      const radioContra = document.querySelector('input[value="CONTRA_ENTREGA"]');
      if (radioContra) radioContra.checked = true;
    }
    transSection.style.display = 'none';
    notice.classList.add('visible');
  } else {
    optTransferencia.classList.remove('blocked');
    notice.classList.remove('visible');
    const metodoPago = document.querySelector('input[name="metodo_pago"]:checked');
    if (metodoPago && metodoPago.value === 'TRANSFERENCIA') {
      transSection.style.display = 'block';
    }
  }
}

function togglePago(radio) {
  const transSection  = document.getElementById('transferencia-section');
  const esServientrega = document.querySelector('input[name="tipo_entrega"]:checked')?.value === 'SERVIENTREGA';
  transSection.style.display = (radio.value === 'TRANSFERENCIA' && !esServientrega) ? 'block' : 'none';
}

function previewFile(input) {
  const preview  = document.getElementById('upload-preview');
  const filename = document.getElementById('upload-filename');
  const thumb    = document.getElementById('img-preview-thumb');

  if (input.files && input.files[0]) {
    const file = input.files[0];
    filename.textContent = file.name;
    preview.classList.add('visible');

    // Mostrar thumbnail de la imagen
    const reader = new FileReader();
    reader.onload = e => {
      thumb.src = e.target.result;
      thumb.classList.add('visible');
    };
    reader.readAsDataURL(file);
  }
}

function copiarCuenta(btn, numero) {
  navigator.clipboard.writeText(numero).then(() => {
    btn.textContent = '✓ COPIADO';
    btn.classList.add('copied');
    setTimeout(() => {
      btn.textContent = 'COPIAR NÚMERO';
      btn.classList.remove('copied');
    }, 2000);
  });
}

// Drag & drop
const uploadArea = document.getElementById('upload-area');
uploadArea.addEventListener('dragover', e => { e.preventDefault(); uploadArea.classList.add('dragover'); });
uploadArea.addEventListener('dragleave', () => uploadArea.classList.remove('dragover'));
uploadArea.addEventListener('drop', e => {
  e.preventDefault();
  uploadArea.classList.remove('dragover');
  const input = document.getElementById('comprobante-input');
  input.files = e.dataTransfer.files;
  previewFile(input);
});

// Init
document.addEventListener('DOMContentLoaded', () => {
  const entregaChecked = document.querySelector('input[name="tipo_entrega"]:checked');
  if (entregaChecked) toggleEntrega(entregaChecked);
  const pagoChecked = document.querySelector('input[name="metodo_pago"]:checked');
  if (pagoChecked) togglePago(pagoChecked);
});
</script>
</body>
</html>