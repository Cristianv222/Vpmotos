<?php
session_start();
require_once __DIR__ . '/config/config.php';

$action = $_POST['action'] ?? $_GET['action'] ?? '';

// ── Acciones ──────────────────────────────────────────────────────
if ($action === 'agregar' && isset($_POST['producto_id'])) {
    $id       = (int)$_POST['producto_id'];
    $nombre   = htmlspecialchars(strip_tags($_POST['nombre'] ?? ''));
    $precio   = (float)$_POST['precio'];
    $cantidad = max(1, (int)($_POST['cantidad'] ?? 1));
    $imagen   = $_POST['imagen'] ?? '';
    $codigo   = $_POST['codigo'] ?? '';

    if (!isset($_SESSION['carrito'])) $_SESSION['carrito'] = [];

    if (isset($_SESSION['carrito'][$id])) {
        $_SESSION['carrito'][$id]['cantidad'] += $cantidad;
    } else {
        $_SESSION['carrito'][$id] = compact('id','nombre','precio','cantidad','imagen','codigo');
    }

    if (!empty($_POST['ajax'])) {
        header('Content-Type: application/json');
        echo json_encode([
            'success'     => true,
            'total_items' => array_sum(array_column($_SESSION['carrito'], 'cantidad'))
        ]);
        exit;
    }
    header('Location: cart.php'); exit;
}

if ($action === 'actualizar' && isset($_POST['producto_id'])) {
    $id  = (int)$_POST['producto_id'];
    $qty = (int)$_POST['cantidad'];
    if ($qty <= 0) unset($_SESSION['carrito'][$id]);
    else           $_SESSION['carrito'][$id]['cantidad'] = $qty;
    header('Location: cart.php'); exit;
}

if ($action === 'eliminar' && isset($_GET['id'])) {
    unset($_SESSION['carrito'][(int)$_GET['id']]);
    header('Location: cart.php'); exit;
}

if ($action === 'vaciar') {
    $_SESSION['carrito'] = [];
    header('Location: cart.php'); exit;
}

// ── Totales ───────────────────────────────────────────────────────
$carrito     = $_SESSION['carrito'] ?? [];
$subtotal    = array_sum(array_map(fn($i) => $i['precio'] * $i['cantidad'], $carrito));
$total_items = array_sum(array_column($carrito, 'cantidad'));
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Carrito — Vpmotos</title>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Audiowide&family=Orbitron:wght@400..900&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="./includes/menu.css">
  <style>
    *, *::before, *::after { margin: 0; padding: 0; box-sizing: border-box; }
    :root {
      --bg:#0a0a0a; --surface:#111111; --surface2:#1a1a1a;
      --border:rgba(255,255,255,0.07); --accent:#d2ed05; --accent2:#a8bc04;
      --text:#e8e8e8; --muted:#666; --danger:#ff4444; --radius:10px;
    }
    body { font-family:'Audiowide',sans-serif; background:var(--bg); color:var(--text); overflow-x:hidden; }

    /* ── HERO ── */
    .shop-hero {
      position:relative; width:100%; height:220px;
      overflow:hidden; display:flex; align-items:flex-end; padding:36px 60px;
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
    .hero-line { width:60px; height:2px; background:var(--accent); margin-bottom:16px; }
    .shop-hero-content h1 {
      font-family:'Orbitron',sans-serif;
      font-size:clamp(1.8rem,4vw,3rem);
      font-weight:400; letter-spacing:0.2em; color:#fff; line-height:1;
    }
    .shop-hero-content h1 span { color:var(--accent); }
    .shop-hero-content p { margin-top:10px; color:var(--muted); font-size:0.7rem; letter-spacing:0.15em; }
    .breadcrumb { margin-top:8px; font-size:0.62rem; color:var(--muted); display:flex; gap:8px; align-items:center; }
    .breadcrumb a { color:var(--muted); text-decoration:none; transition:color .2s; }
    .breadcrumb a:hover { color:var(--accent); }
    .breadcrumb .sep { color:var(--border); }
    .breadcrumb .current { color:var(--accent); }

    /* ── STEPS ── */
    .steps-bar {
      background:var(--surface); border-bottom:1px solid var(--border);
      padding:16px 60px; display:flex; align-items:center; gap:0;
    }
    .step { display:flex; align-items:center; gap:10px; font-size:0.6rem; letter-spacing:0.15em; color:var(--muted); }
    .step.active { color:var(--accent); }
    .step-num {
      width:26px; height:26px; border-radius:50%; border:1px solid currentColor;
      display:flex; align-items:center; justify-content:center;
      font-family:'Orbitron',sans-serif; font-size:0.6rem; flex-shrink:0;
    }
    .step.active .step-num { background:var(--accent); color:#000; border-color:var(--accent); font-weight:700; }
    .step.done .step-num { background:rgba(210,237,5,0.15); color:var(--accent); border-color:rgba(210,237,5,0.3); }
    .step-sep { width:48px; height:1px; background:var(--border); margin:0 10px; flex-shrink:0; }

    /* ── LAYOUT ── */
    .cart-layout {
      display:grid; grid-template-columns:1fr 360px; gap:24px;
      max-width:1200px; margin:0 auto; padding:32px 40px 80px;
    }

    /* ── PANEL IZQUIERDO ── */
    .cart-panel {
      background:var(--surface); border:1px solid var(--border); border-radius:var(--radius); overflow:hidden;
    }
    .cart-panel-header {
      padding:20px 28px; border-bottom:1px solid var(--border);
      display:flex; align-items:center; justify-content:space-between;
    }
    .cart-panel-header h2 { font-family:'Orbitron',sans-serif; font-size:0.75rem; letter-spacing:0.2em; color:#fff; }
    .btn-vaciar {
      background:transparent; border:1px solid rgba(255,68,68,0.3); color:var(--danger);
      font-family:'Audiowide',sans-serif; font-size:0.6rem; padding:6px 14px;
      border-radius:6px; cursor:pointer; transition:all .2s;
    }
    .btn-vaciar:hover { background:rgba(255,68,68,0.1); }

    /* ── ITEM ── */
    .cart-item {
      display:grid; grid-template-columns:88px 1fr auto 110px auto;
      gap:20px; align-items:center; padding:22px 28px;
      border-bottom:1px solid var(--border); transition:background .2s;
    }
    .cart-item:last-child { border-bottom:none; }
    .cart-item:hover { background:rgba(255,255,255,0.015); }

    .item-thumb {
      width:88px; height:88px; border-radius:8px; overflow:hidden;
      background:var(--surface2); flex-shrink:0;
      border:1px solid var(--border);
    }
    .item-thumb img { width:100%; height:100%; object-fit:cover; }
    .item-thumb-ph {
      width:100%; height:100%; display:flex; align-items:center;
      justify-content:center; color:var(--muted); opacity:.4;
    }
    .item-info { min-width:0; }
    .item-codigo {
      font-size:0.55rem; letter-spacing:0.2em; color:var(--accent);
      opacity:.8; margin-bottom:5px; font-family:'Orbitron',sans-serif;
    }
    .item-nombre {
      font-family:'Orbitron',sans-serif; font-size:0.78rem; color:#fff;
      letter-spacing:0.04em; line-height:1.4; margin-bottom:6px;
      display:-webkit-box; -webkit-line-clamp:2; -webkit-box-orient:vertical; overflow:hidden;
    }
    .item-precio-u { font-size:0.65rem; color:var(--muted); }

    /* Cantidad */
    .item-qty {
      display:flex; align-items:center;
      background:var(--surface2); border:1px solid var(--border); border-radius:8px; overflow:hidden;
    }
    .qty-btn {
      width:34px; height:34px; border:none; background:transparent; color:var(--text);
      font-size:1.1rem; cursor:pointer; transition:all .2s;
      display:flex; align-items:center; justify-content:center;
    }
    .qty-btn:hover { background:rgba(210,237,5,0.1); color:var(--accent); }
    .qty-inp {
      width:42px; height:34px; border:none; background:transparent; color:#fff;
      font-family:'Orbitron',sans-serif; font-size:0.78rem; text-align:center; outline:none;
    }
    .qty-inp::-webkit-outer-spin-button,
    .qty-inp::-webkit-inner-spin-button { -webkit-appearance:none; }

    .item-total {
      font-family:'Orbitron',sans-serif; font-size:1rem; color:var(--accent);
      white-space:nowrap; text-align:right;
    }
    .btn-del {
      background:transparent; border:none; color:var(--muted); cursor:pointer;
      padding:6px; transition:color .2s; font-size:1rem; line-height:1;
      opacity:.6;
    }
    .btn-del:hover { color:var(--danger); opacity:1; }

    /* ── EMPTY ── */
    .cart-empty {
      padding:80px 40px; text-align:center; display:flex;
      flex-direction:column; align-items:center; gap:18px;
    }
    .cart-empty svg { opacity:.12; }
    .cart-empty h3 { font-family:'Orbitron',sans-serif; font-size:1rem; letter-spacing:0.2em; color:var(--muted); }
    .cart-empty p { font-size:0.7rem; color:var(--muted); opacity:.6; }
    .btn-ir-tienda {
      display:inline-block; padding:10px 28px;
      border:1px solid rgba(210,237,5,0.4); border-radius:6px;
      color:var(--accent); text-decoration:none; font-size:0.65rem;
      letter-spacing:0.15em; transition:background .2s;
    }
    .btn-ir-tienda:hover { background:rgba(210,237,5,0.08); }

    /* ── RESUMEN ── */
    .summary-sticky { position:sticky; top:20px; }
    .summary-card {
      background:var(--surface); border:1px solid var(--border); border-radius:var(--radius);
      overflow:hidden; margin-bottom:14px;
    }
    .summary-head {
      padding:18px 24px; border-bottom:1px solid var(--border);
      display:flex; align-items:center; gap:10px;
    }
    .summary-head h2 { font-family:'Orbitron',sans-serif; font-size:0.75rem; letter-spacing:0.2em; color:#fff; }
    .summary-body { padding:20px 24px; }
    .sum-row {
      display:flex; justify-content:space-between; align-items:center;
      margin-bottom:12px; font-size:0.68rem;
    }
    .sum-row .lbl { color:var(--muted); }
    .sum-row .val { font-family:'Orbitron',sans-serif; font-size:0.72rem; color:var(--text); }
    .sum-divider { height:1px; background:var(--border); margin:14px 0; }
    .sum-total { display:flex; justify-content:space-between; align-items:center; }
    .sum-total .lbl { font-size:0.75rem; color:#fff; letter-spacing:0.1em; }
    .sum-total .val { font-family:'Orbitron',sans-serif; font-size:1.3rem; color:var(--accent); }

    .sum-envio-note {
      margin-top:16px;
      background:rgba(210,237,5,0.05); border:1px solid rgba(210,237,5,0.15);
      border-radius:8px; padding:12px 14px; font-size:0.6rem; color:var(--muted); line-height:1.7;
    }
    .sum-envio-note strong { color:var(--accent); display:block; margin-bottom:3px; font-size:0.62rem; letter-spacing:.05em; }

    .btn-checkout {
      display:block; width:100%; padding:16px; text-align:center; text-decoration:none;
      background:var(--accent); color:#000; font-family:'Orbitron',sans-serif;
      font-size:0.75rem; letter-spacing:0.15em; font-weight:700;
      border-radius:var(--radius); border:none; cursor:pointer; transition:all .25s;
    }
    .btn-checkout:hover { background:var(--accent2); transform:translateY(-3px); box-shadow:0 12px 36px rgba(210,237,5,0.3); }
    .btn-checkout:disabled { opacity:.35; cursor:not-allowed; transform:none; box-shadow:none; }
    .btn-seguir {
      display:block; width:100%; padding:12px; margin-top:10px; text-align:center; text-decoration:none;
      background:transparent; color:var(--muted); font-family:'Audiowide',sans-serif; font-size:0.65rem;
      letter-spacing:0.08em; border-radius:var(--radius); border:1px solid var(--border); transition:all .2s;
    }
    .btn-seguir:hover { border-color:rgba(210,237,5,0.3); color:var(--accent); }

    /* ── RESPONSIVE ── */
    @media(max-width:1024px) {
      .cart-layout { grid-template-columns:1fr 320px; gap:18px; padding:24px 24px 60px; }
      .steps-bar { padding:14px 24px; }
      .shop-hero { padding:28px 24px; }
    }
    @media(max-width:800px) {
      .cart-layout { grid-template-columns:1fr; padding:16px 16px 60px; }
      .summary-sticky { position:static; }
      .cart-item { grid-template-columns:70px 1fr auto; grid-template-rows:auto auto; gap:12px; padding:16px; }
      .item-qty  { grid-column:2; grid-row:2; }
      .item-total{ grid-column:3; grid-row:1; align-self:start; }
      .btn-del   { grid-column:3; grid-row:2; align-self:center; }
      .item-thumb { width:70px; height:70px; }
      .steps-bar { padding:12px 16px; gap:0; }
      .step-sep  { width:24px; }
      .step span { display:none; }
    }
    @media(max-width:420px) {
      .shop-hero { height:180px; padding:24px 16px; }
      .cart-item { padding:14px; gap:10px; }
      .item-nombre { font-size:0.7rem; }
    }
  </style>
</head>
<body>
<?php include './includes/menu.php'; ?>

<!-- HERO -->
<section class="shop-hero">
  <div class="shop-hero-bg"></div>
  <div class="shop-hero-content">
    <div class="hero-line"></div>
    <h1>Mi <span>Carrito</span></h1>
    <div class="breadcrumb">
      <a href="index.php">Inicio</a><span class="sep">/</span>
      <a href="tienda.php">Tienda</a><span class="sep">/</span>
      <span class="current">Carrito</span>
    </div>
  </div>
</section>

<!-- STEPS -->
<div class="steps-bar">
  <div class="step active">
    <div class="step-num">1</div><span>CARRITO</span>
  </div>
  <div class="step-sep"></div>
  <div class="step">
    <div class="step-num">2</div><span>DATOS</span>
  </div>
  <div class="step-sep"></div>
  <div class="step">
    <div class="step-num">3</div><span>CONFIRMACIÓN</span>
  </div>
</div>

<!-- CONTENIDO -->
<div class="cart-layout">

  <!-- ITEMS -->
  <div>
    <div class="cart-panel">
      <div class="cart-panel-header">
        <h2>PRODUCTOS <span style="color:var(--muted);font-size:0.65rem">(<?= $total_items ?>)</span></h2>
        <?php if ($carrito): ?>
        <form method="POST" style="margin:0">
          <input type="hidden" name="action" value="vaciar">
          <button type="submit" class="btn-vaciar"
            onclick="return confirm('¿Vaciar carrito? Esta acción no se puede deshacer.')">
            Vaciar todo
          </button>
        </form>
        <?php endif; ?>
      </div>

      <?php if (empty($carrito)): ?>
        <div class="cart-empty">
          <svg width="72" height="72" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="0.8">
            <path d="M6 2 3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4z"/>
            <line x1="3" y1="6" x2="21" y2="6"/>
            <path d="M16 10a4 4 0 0 1-8 0"/>
          </svg>
          <h3>Carrito vacío</h3>
          <p>Aún no has agregado ningún producto</p>
          <a href="tienda.php" class="btn-ir-tienda">← Explorar tienda</a>
        </div>
      <?php else: ?>
        <?php foreach ($carrito as $id => $item): ?>
        <div class="cart-item">
          <!-- Imagen -->
          <div class="item-thumb">
            <?php if ($item['imagen']): ?>
              <img src="<?= htmlspecialchars($item['imagen']) ?>" alt="<?= htmlspecialchars($item['nombre']) ?>">
            <?php else: ?>
              <div class="item-thumb-ph">
                <svg width="30" height="30" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1">
                  <rect x="3" y="3" width="18" height="18" rx="2"/>
                  <circle cx="8.5" cy="8.5" r="1.5"/>
                  <polyline points="21 15 16 10 5 21"/>
                </svg>
              </div>
            <?php endif; ?>
          </div>

          <!-- Info -->
          <div class="item-info">
            <div class="item-codigo"><?= htmlspecialchars($item['codigo']) ?></div>
            <div class="item-nombre"><?= htmlspecialchars($item['nombre']) ?></div>
            <div class="item-precio-u">$<?= number_format($item['precio'], 2) ?> c/u</div>
          </div>

          <!-- Cantidad -->
          <form method="POST" style="margin:0">
            <input type="hidden" name="action" value="actualizar">
            <input type="hidden" name="producto_id" value="<?= $id ?>">
            <div class="item-qty">
              <button type="button" class="qty-btn" onclick="adjQty(this,-1)">−</button>
              <input class="qty-inp" type="number" name="cantidad"
                value="<?= $item['cantidad'] ?>" min="1" max="99"
                onchange="this.form.submit()">
              <button type="button" class="qty-btn" onclick="adjQty(this,1)">+</button>
            </div>
          </form>

          <!-- Total -->
          <div class="item-total">$<?= number_format($item['precio'] * $item['cantidad'], 2) ?></div>

          <!-- Eliminar -->
          <a href="cart.php?action=eliminar&id=<?= $id ?>" class="btn-del" title="Eliminar">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
              <polyline points="3 6 5 6 21 6"/><path d="M19 6l-1 14a2 2 0 0 1-2 2H8a2 2 0 0 1-2-2L5 6"/>
              <path d="M10 11v6"/><path d="M14 11v6"/><path d="M9 6V4h6v2"/>
            </svg>
          </a>
        </div>
        <?php endforeach; ?>
      <?php endif; ?>
    </div>
  </div>

  <!-- RESUMEN -->
  <div class="summary-sticky">
    <div class="summary-card">
      <div class="summary-head">
        <h2>RESUMEN</h2>
      </div>
      <div class="summary-body">
        <div class="sum-row">
          <span class="lbl">Subtotal (<?= $total_items ?> <?= $total_items === 1 ? 'producto' : 'productos' ?>)</span>
          <span class="val">$<?= number_format($subtotal, 2) ?></span>
        </div>
        <div class="sum-row">
          <span class="lbl">Envío</span>
          <span class="val" style="font-size:0.6rem;color:var(--muted)">Por coordinar</span>
        </div>
        <div class="sum-divider"></div>
        <div class="sum-total">
          <span class="lbl">TOTAL</span>
          <span class="val">$<?= number_format($subtotal, 2) ?></span>
        </div>
        <div class="sum-envio-note">
          <strong>📦 Envío Servientrega</strong>
          El costo varía por peso y provincia. Lo coordinamos contigo por WhatsApp al confirmar el pedido.
        </div>
      </div>
    </div>

    <?php if ($carrito): ?>
      <a href="checkout.php" class="btn-checkout">PROCEDER AL PAGO →</a>
    <?php else: ?>
      <button class="btn-checkout" disabled>PROCEDER AL PAGO →</button>
    <?php endif; ?>
    <a href="tienda.php" class="btn-seguir">← Seguir comprando</a>
  </div>

</div>

<?php include './includes/footer.php'; ?>
<script>
function adjQty(btn, delta) {
  const inp = btn.closest('.item-qty').querySelector('.qty-inp');
  inp.value = Math.max(1, Math.min(99, parseInt(inp.value || 1) + delta));
  inp.closest('form').submit();
}

// Agregar al carrito desde tienda (llamado vía fetch)
function agregarAlCarrito(id, nombre, precio, imagen, codigo) {
  return fetch('cart.php', {
    method: 'POST',
    headers: {'Content-Type':'application/x-www-form-urlencoded'},
    body: new URLSearchParams({action:'agregar', producto_id:id, nombre, precio, imagen, codigo, cantidad:1, ajax:1})
  })
  .then(r => r.json())
  .then(data => {
    if (data.success) actualizarBadgeCarrito(data.total_items);
    return data;
  });
}

function actualizarBadgeCarrito(n) {
  const badge = document.getElementById('cart-badge');
  if (badge) { badge.textContent = n; badge.style.display = n > 0 ? 'flex' : 'none'; }
}
</script>
</body>
</html>