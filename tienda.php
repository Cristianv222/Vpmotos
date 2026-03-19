<?php
session_start();
require_once __DIR__ . '/config/config.php';
require_once __DIR__ . '/includes/api.php';

$productos = get_products();
$error = null;

if (isset($productos['error'])) {
    $error = $productos['error'];
    $productos = [];
}

$busqueda  = trim($_GET['q'] ?? '');
$categoria = trim($_GET['categoria'] ?? '');
$orden     = trim($_GET['orden'] ?? '');

if ($busqueda) {
    $productos = array_filter($productos, fn($p) =>
        stripos($p['nombre'], $busqueda) !== false ||
        stripos($p['codigo'], $busqueda) !== false ||
        stripos($p['descripcion'] ?? '', $busqueda) !== false
    );
}
if ($categoria) {
    $productos = array_filter($productos, fn($p) => $p['categoria'] === $categoria);
}

$productos = array_values($productos);

usort($productos, function($a, $b) use ($orden) {
    return match($orden) {
        'precio_asc'  => $a['precio'] <=> $b['precio'],
        'precio_desc' => $b['precio'] <=> $a['precio'],
        'nombre_asc'  => strcmp($a['nombre'], $b['nombre']),
        default       => 0,
    };
});

$all_raw    = get_products();
$categorias = [];
if (!isset($all_raw['error'])) {
    $categorias = array_unique(array_filter(array_column($all_raw, 'categoria')));
    sort($categorias);
}

$carrito_count = array_sum(array_column($_SESSION['carrito'] ?? [], 'cantidad'));

// Paginación
$PAGE_SIZE = 12;
$total_productos = count($productos);
$total_paginas   = ceil($total_productos / $PAGE_SIZE);

// Para AJAX: devolver JSON con la siguiente página
if (isset($_GET['ajax_page'])) {
    $page   = max(1, (int)$_GET['ajax_page']);
    $offset = ($page - 1) * $PAGE_SIZE;
    $slice  = array_slice($productos, $offset, $PAGE_SIZE);
    header('Content-Type: application/json');
    echo json_encode([
        'productos'   => $slice,
        'page'        => $page,
        'total'       => $total_productos,
        'total_pages' => $total_paginas,
        'has_more'    => $page < $total_paginas,
    ]);
    exit;
}

// Solo primera página para el render inicial
$productos_inicial = array_slice($productos, 0, $PAGE_SIZE);

// SEO
$seo_cat_map = [
    'Repuestos'  => ['Repuestos Originales para Moto en Quito', 'Repuestos originales para motocicletas en Quito Ecuador. Honda, Yamaha, Bajaj, KTM, Kawasaki. Stock disponible.'],
    'Accesorios' => ['Accesorios para Moto en Ecuador', 'Accesorios y equipamiento para motocicletas en Ecuador. Cascos, protecciones, luces y más en VP Motos Quito.'],
    'Llantas'    => ['Llantas para Moto en Quito Ecuador', 'Llantas para todo tipo de moto en Quito. Mayor variedad de marcas y medidas. Envío a todo Ecuador.'],
    'Aceites'    => ['Aceites y Lubricantes para Moto Ecuador', 'Aceites Motul, Castrol, Amsoil y más para motos en Ecuador. Cambio de aceite y lubricantes en VP Motos Quito.'],
];

if ($categoria && isset($seo_cat_map[$categoria])) {
    [$page_title, $page_desc] = $seo_cat_map[$categoria];
    $page_title = "VP Motos | " . $page_title;
} elseif ($busqueda) {
    $page_title = "Buscar \"" . htmlspecialchars($busqueda) . "\" — VP Motos Quito";
    $page_desc  = "Resultados de búsqueda para \"" . htmlspecialchars($busqueda) . "\" en VP Motos. Repuestos, accesorios y más para motos en Quito, Ecuador.";
} else {
    $page_title = "Tienda VP Motos Quito | Repuestos, Accesorios, Llantas y Aceites para Moto Ecuador";
    $page_desc  = "Compra online repuestos originales, accesorios, llantas y aceites para tu moto en Quito, Ecuador. Honda, Yamaha, Bajaj, KTM, Kawasaki, Suzuki. Envíos a todo Ecuador.";
}
?>
<!DOCTYPE html>
<html lang="es-EC">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0, viewport-fit=cover">

  <title><?= $page_title ?></title>
  <meta name="description" content="<?= $page_desc ?>">
  <meta name="keywords" content="repuestos motos Quito, accesorios motos Ecuador, llantas moto Ecuador, aceite moto Quito, comprar repuestos moto online Ecuador, tienda motos Quito">
  <meta name="author" content="VP Motos">
  <meta name="robots" content="index, follow, max-snippet:-1, max-image-preview:large">
  <link rel="canonical" href="https://www.vpmotos.ec/tienda.php<?= $categoria ? '?categoria=' . urlencode($categoria) : '' ?>">
  <meta name="theme-color" content="#D2ED05">

  <meta property="og:type" content="website">
  <meta property="og:url" content="https://www.vpmotos.ec/tienda.php">
  <meta property="og:title" content="<?= $page_title ?>">
  <meta property="og:description" content="<?= $page_desc ?>">
  <meta property="og:image" content="https://www.vpmotos.ec/images/logo_vp.png">

  <link rel="icon" type="image/png" href="./images/logo_vp.png">
  <link rel="apple-touch-icon" href="./images/logo_vp.png">

  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Audiowide&family=Orbitron:wght@400..900&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="./includes/menu.css">

  <style>
    *, *::before, *::after { margin:0; padding:0; box-sizing:border-box; }
    :root {
      --bg:#0a0a0a; --surface:#111; --surface2:#1a1a1a;
      --border:rgba(255,255,255,0.07); --accent:#d2ed05; --accent2:#a8bc04;
      --text:#e8e8e8; --muted:#666; --danger:#ff4444; --radius:10px;
      --safe-bottom: env(safe-area-inset-bottom, 0px);
    }
    html { scroll-behavior: smooth; }
    body { font-family:'Audiowide',sans-serif; background:var(--bg); color:var(--text); overflow-x:hidden; -webkit-tap-highlight-color:transparent; }

    /* ══ HERO ══ */
    .shop-hero {
      position:relative; width:100%; min-height:220px; overflow:hidden;
      display:flex; align-items:flex-end; justify-content:flex-start;
      padding:32px 20px 28px;
    }
    @media(min-width:768px){ .shop-hero { min-height:320px; padding:50px 60px; } }
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
    .hero-breadcrumb {
      font-size:0.55rem; letter-spacing:0.2em; color:rgba(255,255,255,0.35);
      margin-bottom:12px; display:flex; align-items:center; gap:8px; flex-wrap:wrap;
    }
    .hero-breadcrumb a { color:var(--accent); text-decoration:none; }
    .shop-hero-content h1 {
      font-family:'Orbitron',sans-serif;
      font-size:clamp(1.5rem,6vw,3.8rem);
      font-weight:400; letter-spacing:0.15em; text-transform:uppercase; color:#fff; line-height:1;
    }
    .shop-hero-content h1 span { color:var(--accent); }
    .shop-hero-content p { margin-top:10px; color:var(--muted); font-size:0.65rem; letter-spacing:0.1em; line-height:1.7; max-width:500px; }
    .hero-line { width:40px; height:2px; background:var(--accent); margin-bottom:14px; }

    /* ══ MOBILE FILTER BAR (sticky top) ══ */
    .mobile-filter-bar {
      display:flex; align-items:center; gap:8px;
      padding:10px 16px; background:var(--surface);
      border-bottom:1px solid var(--border);
      overflow-x:auto; -webkit-overflow-scrolling:touch;
      scrollbar-width:none; position:sticky; top:0; z-index:200;
    }
    .mobile-filter-bar::-webkit-scrollbar { display:none; }
    @media(min-width:900px){ .mobile-filter-bar { display:none; } }

    .mfb-chip {
      flex-shrink:0; display:inline-flex; align-items:center; gap:6px;
      padding:7px 14px; border-radius:20px; font-size:0.6rem; letter-spacing:0.08em;
      border:1px solid var(--border); background:var(--surface2); color:var(--muted);
      text-decoration:none; white-space:nowrap; transition:all .2s; cursor:pointer;
      font-family:'Audiowide',sans-serif;
    }
    .mfb-chip.active, .mfb-chip:hover {
      border-color:rgba(210,237,5,0.5); color:var(--accent); background:rgba(210,237,5,0.08);
    }
    .mfb-chip.filter-btn { background:var(--accent); color:#000; border-color:var(--accent); font-weight:700; }
    .mfb-chip.filter-btn:hover { background:var(--accent2); }

    /* ══ DRAWER FILTROS MOBILE ══ */
    .drawer-overlay {
      position:fixed; inset:0; background:rgba(0,0,0,0.7); z-index:500;
      opacity:0; pointer-events:none; transition:opacity .3s; backdrop-filter:blur(4px);
    }
    .drawer-overlay.open { opacity:1; pointer-events:all; }
    .drawer {
      position:fixed; bottom:0; left:0; right:0; z-index:501;
      background:var(--surface); border-radius:20px 20px 0 0;
      padding:20px 20px calc(20px + var(--safe-bottom));
      transform:translateY(100%); transition:transform .35s cubic-bezier(0.16,1,0.3,1);
      max-height:80vh; overflow-y:auto;
    }
    .drawer.open { transform:translateY(0); }
    .drawer-handle { width:36px; height:4px; border-radius:2px; background:var(--border); margin:0 auto 20px; }
    .drawer-title { font-family:'Orbitron',sans-serif; font-size:0.75rem; letter-spacing:0.2em; color:var(--accent); margin-bottom:20px; }

    /* ══ LAYOUT ══ */
    .shop-layout {
      display:grid;
      grid-template-columns:1fr;
    }
    @media(min-width:900px){
      .shop-layout { grid-template-columns:240px 1fr; }
    }

    /* ══ SIDEBAR (desktop) ══ */
    .sidebar {
      display:none;
    }
    @media(min-width:900px){
      .sidebar {
        display:block;
        background:var(--surface); border-right:1px solid var(--border);
        padding:28px 20px; position:sticky; top:0; height:100vh; overflow-y:auto;
      }
      .sidebar::-webkit-scrollbar { width:3px; }
      .sidebar::-webkit-scrollbar-thumb { background:var(--border); }
    }
    .sidebar-section { margin-bottom:32px; }
    .sidebar-label {
      font-family:'Orbitron',sans-serif; font-size:0.55rem; letter-spacing:0.25em;
      text-transform:uppercase; color:var(--accent); margin-bottom:14px;
      display:flex; align-items:center; gap:8px;
    }
    .sidebar-label::after { content:''; flex:1; height:1px; background:var(--border); }

    .search-wrap { position:relative; }
    .search-wrap input {
      width:100%; background:var(--surface2); border:1px solid var(--border);
      border-radius:var(--radius); padding:10px 14px 10px 36px;
      color:var(--text); font-family:'Audiowide',sans-serif; font-size:0.65rem;
      outline:none; transition:border-color .2s;
    }
    .search-wrap input:focus { border-color:rgba(210,237,5,0.4); }
    .search-wrap input::placeholder { color:var(--muted); }
    .search-icon { position:absolute; left:11px; top:50%; transform:translateY(-50%); color:var(--muted); font-size:0.8rem; pointer-events:none; }

    .cat-list { list-style:none; display:flex; flex-direction:column; gap:3px; }
    .cat-list li a {
      display:flex; align-items:center; justify-content:space-between;
      padding:8px 10px; border-radius:6px; text-decoration:none;
      color:var(--muted); font-size:0.65rem; letter-spacing:0.05em;
      transition:all .2s; border:1px solid transparent;
    }
    .cat-list li a:hover, .cat-list li a.active {
      background:rgba(210,237,5,0.08); border-color:rgba(210,237,5,0.2); color:var(--accent);
    }
    .cat-dot { width:6px; height:6px; border-radius:50%; background:var(--muted); transition:background .2s; }
    .cat-list li a:hover .cat-dot, .cat-list li a.active .cat-dot { background:var(--accent); }

    .order-select {
      width:100%; background:var(--surface2); border:1px solid var(--border);
      border-radius:var(--radius); padding:10px 14px; color:var(--text);
      font-family:'Audiowide',sans-serif; font-size:0.65rem; outline:none;
      cursor:pointer; appearance:none;
      background-image:url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' viewBox='0 0 24 24' fill='none' stroke='%23666' stroke-width='2'%3E%3Cpolyline points='6 9 12 15 18 9'%3E%3C/polyline%3E%3C/svg%3E");
      background-repeat:no-repeat; background-position:right 12px center;
    }
    .order-select:focus { border-color:rgba(210,237,5,0.4); }

    .stat-box { background:var(--surface2); border:1px solid var(--border); border-radius:var(--radius); padding:14px; text-align:center; }
    .stat-box .num { font-family:'Orbitron',sans-serif; font-size:1.8rem; color:var(--accent); line-height:1; }
    .stat-box .lbl { font-size:0.55rem; color:var(--muted); letter-spacing:0.15em; margin-top:4px; }

    /* ══ MAIN ══ */
    .shop-main { padding:16px; }
    @media(min-width:768px){ .shop-main { padding:28px 32px; } }

    .shop-topbar {
      display:flex; align-items:center; justify-content:space-between;
      margin-bottom:20px; padding-bottom:16px; border-bottom:1px solid var(--border);
      flex-wrap:wrap; gap:10px;
    }
    .results-info { font-size:0.65rem; color:var(--muted); letter-spacing:0.08em; }
    .results-info strong { color:var(--accent); font-size:0.85rem; }
    .active-filters { display:flex; gap:6px; flex-wrap:wrap; }
    .filter-tag {
      display:inline-flex; align-items:center; gap:5px;
      background:rgba(210,237,5,0.1); border:1px solid rgba(210,237,5,0.3);
      border-radius:20px; padding:4px 10px; font-size:0.6rem; color:var(--accent);
      letter-spacing:0.06em; text-decoration:none;
    }

    /* ══ PRODUCT GRID — mobile first ══ */
    .products-grid {
      display:grid;
      grid-template-columns:repeat(2, 1fr);
      gap:10px;
    }
    @media(min-width:480px){ .products-grid { grid-template-columns:repeat(2,1fr); gap:12px; } }
    @media(min-width:768px){ .products-grid { grid-template-columns:repeat(3,1fr); gap:16px; } }
    @media(min-width:1100px){ .products-grid { grid-template-columns:repeat(3,1fr); gap:20px; } }
    @media(min-width:1400px){ .products-grid { grid-template-columns:repeat(4,1fr); } }

    /* ══ PRODUCT CARD ══ */
    .product-card {
      background:var(--surface); border:1px solid var(--border);
      border-radius:var(--radius); overflow:hidden;
      transition:transform .3s cubic-bezier(0.16,1,0.3,1), border-color .3s, box-shadow .3s;
      cursor:pointer; position:relative;
      /* Animación entrada */
      opacity:0; transform:translateY(16px);
      animation:cardIn .4s forwards;
    }
    @keyframes cardIn {
      to { opacity:1; transform:translateY(0); }
    }
    .product-card:active { transform:scale(0.97); }
    @media(hover:hover){
      .product-card:hover {
        transform:translateY(-4px); border-color:rgba(210,237,5,0.3);
        box-shadow:0 16px 48px rgba(0,0,0,0.6);
      }
    }

    .stock-badge {
      position:absolute; top:8px; right:8px; z-index:2;
      font-size:0.48rem; letter-spacing:0.1em; padding:3px 7px; border-radius:20px;
      font-family:'Orbitron',sans-serif;
    }
    .stock-badge.in  { background:rgba(210,237,5,0.15); color:var(--accent); border:1px solid rgba(210,237,5,0.3); }
    .stock-badge.out { background:rgba(255,68,68,0.15); color:var(--danger); border:1px solid rgba(255,68,68,0.3); }

    .card-img { position:relative; width:100%; aspect-ratio:1; overflow:hidden; background:var(--surface2); }
    .card-img img { width:100%; height:100%; object-fit:cover; transition:transform .5s; }
    @media(hover:hover){ .product-card:hover .card-img img { transform:scale(1.06); } }
    .no-img { width:100%; height:100%; display:flex; flex-direction:column; align-items:center; justify-content:center; gap:8px; color:var(--muted); }
    .no-img svg { opacity:.3; }
    .no-img span { font-size:0.5rem; letter-spacing:0.15em; opacity:.5; }

    .card-body { padding:10px; }
    @media(min-width:768px){ .card-body { padding:14px; } }
    .card-categoria { font-size:0.5rem; letter-spacing:0.18em; text-transform:uppercase; color:var(--accent); margin-bottom:5px; opacity:.8; }
    .card-nombre {
      font-family:'Orbitron',sans-serif; font-size:0.65rem; font-weight:500; color:#fff;
      line-height:1.35; margin-bottom:4px; letter-spacing:0.04em;
      display:-webkit-box; -webkit-line-clamp:2; -webkit-box-orient:vertical; overflow:hidden;
    }
    @media(min-width:768px){ .card-nombre { font-size:0.78rem; } }
    .card-marca { font-size:0.55rem; color:var(--muted); letter-spacing:0.08em; margin-bottom:10px; }
    .card-footer {
      display:flex; align-items:center; justify-content:space-between;
      padding-top:10px; border-top:1px solid var(--border);
    }
    .card-precio { font-family:'Orbitron',sans-serif; font-size:0.95rem; color:var(--accent); font-weight:700; }
    @media(min-width:768px){ .card-precio { font-size:1.1rem; } }
    .card-precio small { font-size:0.48rem; color:var(--muted); display:block; letter-spacing:0.08em; font-weight:400; }
    .card-stock { font-size:0.55rem; color:var(--muted); text-align:right; }
    .card-stock strong { color:var(--text); }

    /* Botón agregar mobile: siempre visible en mobile, hover en desktop */
    .btn-add-card {
      width:100%; background:rgba(210,237,5,0.12); color:var(--accent);
      font-family:'Orbitron',sans-serif; font-size:0.55rem; font-weight:700; letter-spacing:0.1em;
      border:1px solid rgba(210,237,5,0.25); border-top:none;
      padding:10px; cursor:pointer;
      display:flex; align-items:center; justify-content:center; gap:6px;
      transition:all .2s; -webkit-tap-highlight-color:transparent;
    }
    .btn-add-card:active { background:rgba(210,237,5,0.3); }
    @media(hover:hover){
      .btn-add-card {
        position:absolute; bottom:0; left:0; right:0;
        background:rgba(210,237,5,0.9); color:#000;
        border:none;
        transform:translateY(100%); transition:transform .25s cubic-bezier(0.16,1,0.3,1);
      }
      .product-card:hover .btn-add-card { transform:translateY(0); }
      .btn-add-card:active { background:var(--accent2); }
    }
    .btn-add-card.agotado { background:rgba(255,68,68,0.1); color:var(--danger); border-color:rgba(255,68,68,0.2); cursor:not-allowed; }

    /* ══ INFINITE SCROLL LOADER ══ */
    #scroll-sentinel { height:1px; }
    .load-more-btn {
      display:flex; align-items:center; justify-content:center; gap:10px;
      width:100%; padding:14px; margin-top:20px;
      background:var(--surface); border:1px solid var(--border); border-radius:var(--radius);
      color:var(--muted); font-family:'Audiowide',sans-serif; font-size:0.65rem;
      letter-spacing:0.1em; cursor:pointer; transition:all .2s;
    }
    .load-more-btn:hover { border-color:rgba(210,237,5,0.3); color:var(--accent); }
    .load-more-btn.loading { pointer-events:none; }
    .load-more-btn.loading .btn-txt { display:none; }
    .load-more-btn .spinner {
      display:none; width:16px; height:16px; border:2px solid var(--border);
      border-top-color:var(--accent); border-radius:50%; animation:spin .7s linear infinite;
    }
    .load-more-btn.loading .spinner { display:block; }
    @keyframes spin { to { transform:rotate(360deg); } }
    .end-msg { text-align:center; padding:24px; font-size:0.6rem; color:var(--muted); letter-spacing:0.15em; display:none; }
    .end-msg.show { display:block; }

    /* ══ CART FLOAT BUTTON — mobile friendly ══ */
    .cart-float {
      position:fixed; bottom:calc(20px + var(--safe-bottom)); right:16px; z-index:400;
      display:none; align-items:center; gap:8px;
      background:var(--accent); color:#000;
      font-family:'Orbitron',sans-serif; font-size:0.6rem; font-weight:700; letter-spacing:0.08em;
      padding:12px 16px; border-radius:50px; text-decoration:none;
      box-shadow:0 8px 32px rgba(210,237,5,0.4);
      transition:all .25s cubic-bezier(0.16,1,0.3,1);
      -webkit-tap-highlight-color:transparent;
    }
    .cart-float.visible { display:flex; }
    .cart-float:active { transform:scale(0.95); }
    @media(min-width:768px){
      .cart-float { right:28px; bottom:28px; padding:13px 22px; }
    }
    .cart-float-badge {
      background:#000; color:var(--accent); font-size:0.58rem;
      width:20px; height:20px; border-radius:50%; display:flex; align-items:center; justify-content:center;
      font-weight:700; min-width:20px;
    }
    /* En mobile solo ícono + badge */
    .cart-float-text { display:none; }
    @media(min-width:480px){ .cart-float-text { display:inline; } }

    /* ══ TOAST — bottom en mobile ══ */
    .toast {
      position:fixed; bottom:calc(80px + var(--safe-bottom)); right:12px; left:12px; z-index:600;
      background:var(--surface); border:1px solid rgba(210,237,5,0.3);
      border-radius:10px; padding:12px 16px;
      display:flex; align-items:center; gap:10px;
      font-size:0.62rem; color:var(--text);
      box-shadow:0 8px 32px rgba(0,0,0,0.5);
      transform:translateY(20px); opacity:0;
      transition:transform .35s cubic-bezier(0.16,1,0.3,1), opacity .35s;
      pointer-events:none;
    }
    .toast.show { transform:translateY(0); opacity:1; }
    @media(min-width:480px){
      .toast { left:auto; right:20px; min-width:240px; max-width:300px; bottom:calc(90px + var(--safe-bottom)); }
    }
    .toast-icon { font-size:1.1rem; flex-shrink:0; }
    .toast-txt strong { display:block; color:#fff; margin-bottom:2px; }
    .toast-txt span { color:var(--muted); font-size:0.58rem; }

    /* ══ EMPTY / ERROR ══ */
    .empty-state {
      grid-column:1/-1; display:flex; flex-direction:column; align-items:center;
      justify-content:center; padding:60px 20px; text-align:center; gap:14px;
    }
    .empty-state svg { opacity:.2; }
    .empty-state h3 { font-family:'Orbitron',sans-serif; font-size:0.9rem; letter-spacing:0.2em; color:var(--muted); }
    .empty-state p { font-size:0.65rem; color:var(--muted); opacity:.7; }
    .empty-state a {
      margin-top:8px; display:inline-block; padding:10px 22px;
      border:1px solid rgba(210,237,5,0.4); border-radius:6px; color:var(--accent);
      text-decoration:none; font-size:0.62rem; letter-spacing:0.15em;
    }
    .api-error {
      grid-column:1/-1; background:rgba(255,68,68,0.08); border:1px solid rgba(255,68,68,0.2);
      border-radius:var(--radius); padding:20px; display:flex; align-items:flex-start; gap:14px;
      color:#ff8888; font-size:0.7rem;
    }

    /* ══ MODAL — bottom sheet en mobile ══ */
    .modal-overlay {
      position:fixed; inset:0; background:rgba(0,0,0,0.85); z-index:1000;
      display:flex; align-items:flex-end; justify-content:center;
      opacity:0; pointer-events:none; transition:opacity .3s; backdrop-filter:blur(8px);
    }
    @media(min-width:600px){
      .modal-overlay { align-items:center; padding:20px; }
    }
    .modal-overlay.open { opacity:1; pointer-events:all; }
    .modal {
      background:var(--surface);
      border-radius:20px 20px 0 0;
      width:100%; max-height:92vh; overflow-y:auto;
      transform:translateY(100%); transition:transform .35s cubic-bezier(0.16,1,0.3,1);
    }
    @media(min-width:600px){
      .modal {
        border:1px solid rgba(210,237,5,0.2);
        border-radius:16px; max-width:720px; max-height:90vh;
        transform:translateY(30px) scale(0.97);
      }
    }
    .modal-overlay.open .modal { transform:translateY(0) scale(1); }
    .modal::-webkit-scrollbar { width:3px; }
    .modal::-webkit-scrollbar-thumb { background:var(--border); }
    .modal-drag-handle { width:36px; height:4px; border-radius:2px; background:var(--border); margin:12px auto 0; }
    @media(min-width:600px){ .modal-drag-handle { display:none; } }

    .modal-header {
      display:flex; align-items:center; justify-content:space-between;
      padding:18px 20px; border-bottom:1px solid var(--border); position:sticky; top:0;
      background:var(--surface); z-index:1;
    }
    .modal-title { font-family:'Orbitron',sans-serif; font-size:0.75rem; letter-spacing:0.1em; color:#fff; flex:1; padding-right:12px; }
    .modal-close {
      background:var(--surface2); border:1px solid var(--border); color:var(--muted);
      width:34px; height:34px; border-radius:50%; cursor:pointer; flex-shrink:0;
      display:flex; align-items:center; justify-content:center; font-size:1rem; transition:all .2s;
    }
    .modal-close:hover { border-color:rgba(210,237,5,0.4); color:var(--accent); }

    .modal-body { padding:20px; display:flex; flex-direction:column; gap:16px; }
    @media(min-width:600px){
      .modal-body { display:grid; grid-template-columns:1fr 1fr; gap:0; padding:0; }
    }
    .modal-gallery { padding:20px; }
    @media(min-width:600px){ .modal-gallery { border-right:1px solid var(--border); } }

    .modal-img-main {
      width:100%; aspect-ratio:1; object-fit:cover; border-radius:10px; background:var(--surface2);
    }
    .modal-img-main.no-img-modal {
      display:flex; align-items:center; justify-content:center;
      background:#1a1a1a; border-radius:10px; aspect-ratio:1;
      font-size:0.65rem; letter-spacing:0.15em; color:#444;
    }
    .modal-thumbs { display:flex; gap:8px; margin-top:10px; }
    .modal-thumb { width:52px; height:52px; object-fit:cover; border-radius:6px; border:2px solid transparent; cursor:pointer; transition:border-color .2s; }
    .modal-thumb.active { border-color:var(--accent); }

    .modal-info { padding:20px; display:flex; flex-direction:column; }
    .modal-categoria { font-size:0.58rem; letter-spacing:0.18em; color:var(--accent); margin-bottom:8px; }
    .modal-nombre { font-family:'Orbitron',sans-serif; font-size:0.95rem; color:#fff; line-height:1.4; margin-bottom:4px; }
    .modal-marca { font-size:0.62rem; color:var(--muted); margin-bottom:14px; }
    .modal-precio { font-family:'Orbitron',sans-serif; font-size:1.7rem; color:var(--accent); margin-bottom:14px; }
    .modal-precio small { font-size:0.65rem; color:var(--muted); font-weight:400; }

    .modal-meta { display:flex; flex-direction:column; gap:6px; margin-bottom:14px; }
    .meta-row { display:flex; align-items:center; justify-content:space-between; padding:8px 12px; background:var(--surface2); border-radius:8px; font-size:0.62rem; }
    .meta-row .meta-key { color:var(--muted); letter-spacing:0.1em; }
    .meta-row .meta-val { color:#fff; font-weight:500; }
    .meta-row .meta-val.accent { color:var(--accent); }

    .modal-desc { font-size:0.65rem; color:var(--muted); line-height:1.8; padding:12px; background:var(--surface2); border-radius:8px; border-left:3px solid var(--accent); margin-bottom:16px; }

    .modal-cart-section { margin-top:auto; padding-top:14px; border-top:1px solid var(--border); }
    .modal-qty-row { display:flex; align-items:center; gap:10px; margin-bottom:12px; }
    .modal-qty-label { font-size:0.58rem; color:var(--muted); letter-spacing:.1em; white-space:nowrap; }
    .qty-control { display:flex; align-items:center; background:var(--surface2); border:1px solid var(--border); border-radius:8px; overflow:hidden; }
    .qty-btn-m { width:40px; height:40px; border:none; background:transparent; color:var(--text); font-size:1.1rem; cursor:pointer; display:flex; align-items:center; justify-content:center; }
    .qty-btn-m:active { background:rgba(210,237,5,0.1); }
    .qty-val { width:44px; height:40px; background:transparent; border:none; color:#fff; font-family:'Orbitron',sans-serif; font-size:0.85rem; text-align:center; outline:none; }
    .qty-val::-webkit-outer-spin-button, .qty-val::-webkit-inner-spin-button { -webkit-appearance:none; }

    .btn-add-modal {
      display:flex; align-items:center; justify-content:center; gap:8px;
      width:100%; padding:14px; background:var(--accent); color:#000;
      font-family:'Orbitron',sans-serif; font-size:0.7rem; font-weight:700; letter-spacing:0.1em;
      border:none; border-radius:var(--radius); cursor:pointer; transition:all .2s;
      -webkit-tap-highlight-color:transparent;
    }
    .btn-add-modal:active { background:var(--accent2); transform:scale(0.98); }
    .btn-add-modal:disabled { opacity:.4; cursor:not-allowed; }
    .btn-ver-carrito {
      display:flex; align-items:center; justify-content:center; gap:8px;
      width:100%; padding:10px; margin-top:8px;
      background:transparent; color:var(--accent);
      font-family:'Audiowide',sans-serif; font-size:0.62rem; letter-spacing:0.08em;
      border:1px solid rgba(210,237,5,0.3); border-radius:var(--radius);
      cursor:pointer; text-decoration:none; transition:background .2s;
    }
    .btn-ver-carrito:active { background:rgba(210,237,5,0.08); }

    /* Drawer filtros internos */
    .drawer-cat-list { list-style:none; display:flex; flex-direction:column; gap:4px; margin-bottom:20px; }
    .drawer-cat-list li a {
      display:flex; align-items:center; gap:10px; padding:12px 14px;
      border-radius:8px; text-decoration:none; color:var(--muted);
      font-size:0.7rem; letter-spacing:0.05em; border:1px solid transparent; transition:all .2s;
    }
    .drawer-cat-list li a.active, .drawer-cat-list li a:hover {
      background:rgba(210,237,5,0.08); border-color:rgba(210,237,5,0.2); color:var(--accent);
    }
    .drawer-section-title { font-size:0.6rem; letter-spacing:0.2em; color:var(--muted); text-transform:uppercase; margin-bottom:10px; margin-top:16px; }
    .drawer-order-list { display:flex; flex-direction:column; gap:4px; }
    .drawer-order-list a {
      display:block; padding:12px 14px; border-radius:8px; text-decoration:none;
      color:var(--muted); font-size:0.7rem; border:1px solid transparent; transition:all .2s;
    }
    .drawer-order-list a.active, .drawer-order-list a:hover {
      background:rgba(210,237,5,0.08); border-color:rgba(210,237,5,0.2); color:var(--accent);
    }
    .drawer-search-wrap { position:relative; margin-bottom:4px; }
    .drawer-search-wrap input {
      width:100%; background:var(--surface2); border:1px solid var(--border);
      border-radius:var(--radius); padding:12px 14px 12px 38px;
      color:var(--text); font-family:'Audiowide',sans-serif; font-size:0.7rem; outline:none;
    }
    .drawer-search-wrap input:focus { border-color:rgba(210,237,5,0.4); }
    .drawer-search-icon { position:absolute; left:12px; top:50%; transform:translateY(-50%); color:var(--muted); font-size:0.85rem; }
  </style>
</head>
<body>

<?php include './includes/menu.php'; ?>

<!-- ══ HERO ══ -->
<header class="shop-hero" role="banner">
  <div class="shop-hero-bg"></div>
  <div class="shop-hero-content">
    <nav class="hero-breadcrumb" aria-label="Ruta de navegación">
      <a href="index.php">Inicio</a>
      <span>›</span>
      <span>Tienda</span>
      <?php if ($categoria): ?>
        <span>›</span><span><?= htmlspecialchars($categoria) ?></span>
      <?php endif; ?>
    </nav>
    <div class="hero-line"></div>
    <h1>
      <?php if ($categoria): ?>
        <span><?= htmlspecialchars($categoria) ?></span> para Moto
      <?php else: ?>
        Tienda <span>Online</span>
      <?php endif; ?>
    </h1>
    <p>
      <?php if ($categoria === 'Repuestos'): ?>
        Repuestos originales Honda, Yamaha, Bajaj, KTM, Kawasaki en Quito, Ecuador.
      <?php elseif ($categoria === 'Llantas'): ?>
        Llantas para moto en Quito. Todas las medidas con envío a todo Ecuador.
      <?php elseif ($categoria === 'Aceites'): ?>
        Aceites y lubricantes para moto. Motul, Castrol, Amsoil y más.
      <?php elseif ($categoria === 'Accesorios'): ?>
        Accesorios y equipamiento para motocicletas en Quito. Calidad garantizada.
      <?php else: ?>
        Repuestos · Accesorios · Llantas · Aceites — Envíos a todo Ecuador
      <?php endif; ?>
    </p>
  </div>
</header>

<!-- ══ MOBILE FILTER BAR (sticky) ══ -->
<div class="mobile-filter-bar" role="navigation" aria-label="Filtros rápidos">
  <button class="mfb-chip filter-btn" onclick="abrirDrawer()" aria-label="Abrir filtros">
    ⚙ Filtros
    <?php if ($categoria || $busqueda || $orden): ?>
      <span style="background:#000;color:var(--accent);border-radius:50%;width:16px;height:16px;font-size:0.5rem;display:inline-flex;align-items:center;justify-content:center;">
        <?= (($categoria?1:0) + ($busqueda?1:0) + ($orden?1:0)) ?>
      </span>
    <?php endif; ?>
  </button>

  <a href="tienda.php" class="mfb-chip <?= !$categoria && !$busqueda ? 'active' : '' ?>">Todos</a>
  <?php foreach ($categorias as $cat): ?>
  <a href="?<?= http_build_query(array_merge($_GET,['categoria'=>$cat,'q'=>''])) ?>"
     class="mfb-chip <?= $categoria===$cat ? 'active' : '' ?>">
    <?= htmlspecialchars($cat) ?>
  </a>
  <?php endforeach; ?>
</div>

<!-- ══ DRAWER FILTROS MOBILE ══ -->
<div class="drawer-overlay" id="drawer-overlay" onclick="cerrarDrawer()"></div>
<div class="drawer" id="drawer" role="dialog" aria-modal="true" aria-label="Filtros">
  <div class="drawer-handle"></div>
  <div class="drawer-title">FILTROS</div>

  <form method="GET" action="" id="drawer-form">
    <div class="drawer-search-wrap">
      <span class="drawer-search-icon">⌕</span>
      <input type="text" name="q" placeholder="Buscar producto o código..."
        value="<?= htmlspecialchars($busqueda) ?>" autocomplete="off">
    </div>

    <div class="drawer-section-title">CATEGORÍA</div>
    <ul class="drawer-cat-list">
      <li>
        <a href="tienda.php" class="<?= !$categoria ? 'active' : '' ?>" onclick="cerrarDrawer()">
          <span style="width:8px;height:8px;border-radius:50%;background:<?= !$categoria ? 'var(--accent)' : 'var(--muted)' ?>;flex-shrink:0;"></span>
          Todos los productos
        </a>
      </li>
      <?php foreach ($categorias as $cat): ?>
      <li>
        <a href="?<?= http_build_query(array_merge($_GET,['categoria'=>$cat])) ?>"
           class="<?= $categoria===$cat ? 'active' : '' ?>" onclick="cerrarDrawer()">
          <span style="width:8px;height:8px;border-radius:50%;background:<?= $categoria===$cat ? 'var(--accent)' : 'var(--muted)' ?>;flex-shrink:0;"></span>
          <?= htmlspecialchars($cat) ?>
        </a>
      </li>
      <?php endforeach; ?>
    </ul>

    <div class="drawer-section-title">ORDENAR POR</div>
    <div class="drawer-order-list">
      <?php
      $orders = [''=>'Relevancia','precio_asc'=>'Precio: menor a mayor','precio_desc'=>'Precio: mayor a menor','nombre_asc'=>'Nombre A–Z'];
      foreach ($orders as $val => $label):
      ?>
      <a href="?<?= http_build_query(array_merge($_GET,['orden'=>$val])) ?>"
         class="<?= $orden===$val ? 'active' : '' ?>" onclick="cerrarDrawer()">
        <?= $label ?>
      </a>
      <?php endforeach; ?>
    </div>

    <button type="submit" style="margin-top:16px;width:100%;padding:14px;background:var(--accent);color:#000;font-family:'Orbitron',sans-serif;font-size:0.7rem;font-weight:700;letter-spacing:0.1em;border:none;border-radius:var(--radius);cursor:pointer;">
      APLICAR BÚSQUEDA
    </button>
  </form>
</div>

<!-- BOTÓN FLOTANTE CARRITO -->
<a href="cart.php" class="cart-float <?= $carrito_count > 0 ? 'visible' : '' ?>" id="cart-float" aria-label="Ver carrito">
  <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" aria-hidden="true">
    <path d="M6 2 3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4z"/>
    <line x1="3" y1="6" x2="21" y2="6"/>
    <path d="M16 10a4 4 0 0 1-8 0"/>
  </svg>
  <span class="cart-float-text">Carrito</span>
  <div class="cart-float-badge" id="cart-badge"><?= $carrito_count ?></div>
</a>

<!-- TOAST -->
<div class="toast" id="toast" role="status" aria-live="polite">
  <div class="toast-icon">✓</div>
  <div class="toast-txt">
    <strong id="toast-title">Producto agregado</strong>
    <span id="toast-sub">Agregado al carrito</span>
  </div>
</div>

<!-- LAYOUT -->
<div class="shop-layout">

  <!-- SIDEBAR DESKTOP -->
  <aside class="sidebar" aria-label="Filtros">
    <form method="GET" action="" id="filter-form">
      <div class="sidebar-section">
        <div class="sidebar-label">Buscar</div>
        <div class="search-wrap">
          <span class="search-icon">⌕</span>
          <input type="text" name="q" placeholder="Nombre o código..."
            value="<?= htmlspecialchars($busqueda) ?>"
            aria-label="Buscar productos"
            oninput="this.form.submit()">
        </div>
      </div>
      <div class="sidebar-section">
        <div class="sidebar-label">Categorías</div>
        <ul class="cat-list">
          <li>
            <a href="?<?= http_build_query(array_merge($_GET,['categoria'=>''])) ?>" class="<?= !$categoria ? 'active' : '' ?>">
              <span class="cat-dot"></span>Todos los productos
            </a>
          </li>
          <?php foreach ($categorias as $cat): ?>
          <li>
            <a href="?<?= http_build_query(array_merge($_GET,['categoria'=>$cat])) ?>" class="<?= $categoria===$cat ? 'active' : '' ?>">
              <span class="cat-dot"></span><?= htmlspecialchars($cat) ?>
            </a>
          </li>
          <?php endforeach; ?>
        </ul>
      </div>
      <div class="sidebar-section">
        <div class="sidebar-label">Ordenar</div>
        <select class="order-select" name="orden" onchange="this.form.submit()">
          <option value="" <?= !$orden?'selected':'' ?>>Relevancia</option>
          <option value="precio_asc"  <?= $orden==='precio_asc' ?'selected':'' ?>>Precio ↑</option>
          <option value="precio_desc" <?= $orden==='precio_desc'?'selected':'' ?>>Precio ↓</option>
          <option value="nombre_asc"  <?= $orden==='nombre_asc' ?'selected':'' ?>>Nombre A–Z</option>
        </select>
      </div>
    </form>
    <div class="sidebar-section">
      <div class="sidebar-label">Total</div>
      <div class="stat-box">
        <div class="num" id="total-count"><?= $total_productos ?></div>
        <div class="lbl">Productos<?= $categoria ? ' en ' . htmlspecialchars($categoria) : '' ?></div>
      </div>
    </div>
  </aside>

  <!-- MAIN -->
  <main class="shop-main" role="main">
    <div class="shop-topbar">
      <div class="results-info">
        <strong id="shown-count"><?= count($productos_inicial) ?></strong>
        <span id="total-label"> de <?= $total_productos ?> producto<?= $total_productos!==1?'s':'' ?></span>
        <?php if ($busqueda): ?> — "<em><?= htmlspecialchars($busqueda) ?></em>"<?php endif; ?>
        <?php if ($categoria): ?> en <em><?= htmlspecialchars($categoria) ?></em><?php endif; ?>
      </div>
      <div class="active-filters">
        <?php if ($busqueda): ?>
          <a href="?<?= http_build_query(array_merge($_GET,['q'=>''])) ?>" class="filter-tag">🔍 <?= htmlspecialchars($busqueda) ?> ×</a>
        <?php endif; ?>
        <?php if ($categoria): ?>
          <a href="?<?= http_build_query(array_merge($_GET,['categoria'=>''])) ?>" class="filter-tag">📦 <?= htmlspecialchars($categoria) ?> ×</a>
        <?php endif; ?>
      </div>
    </div>

    <!-- GRID -->
    <div class="products-grid" id="products-grid" role="list">
      <?php if ($error): ?>
        <div class="api-error" role="alert">
          <span style="font-size:1.4rem;flex-shrink:0">⚠️</span>
          <div>
            <strong>Error al conectar con el inventario:</strong><br>
            <?= htmlspecialchars($error) ?><br>
            <small style="opacity:.6">Verifica que el servidor esté activo y el token sea correcto.</small>
          </div>
        </div>
      <?php elseif (empty($productos_inicial)): ?>
        <div class="empty-state">
          <svg width="56" height="56" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1">
            <circle cx="11" cy="11" r="8"/><path d="m21 21-4.35-4.35"/>
          </svg>
          <h3>Sin resultados</h3>
          <p>No encontramos productos con esos criterios</p>
          <a href="tienda.php">Ver todos los productos</a>
        </div>
      <?php else: ?>
        <?php foreach ($productos_inicial as $i => $p):
          $en_stock = (int)$p['stock'] > 0;
        ?>
        <?= renderCard($p, $en_stock, $i) ?>
        <?php endforeach; ?>
      <?php endif; ?>
    </div>

    <!-- INFINITE SCROLL SENTINEL -->
    <div id="scroll-sentinel"></div>

    <!-- BOTÓN MANUAL (fallback) -->
    <?php if ($total_paginas > 1): ?>
    <button class="load-more-btn" id="load-more-btn" onclick="cargarMas()">
      <span class="btn-txt">Cargar más productos</span>
      <div class="spinner"></div>
    </button>
    <?php endif; ?>

    <div class="end-msg" id="end-msg">— Todos los productos cargados —</div>
  </main>
</div>

<!-- MODAL DETALLE -->
<div class="modal-overlay" id="modal" role="dialog" aria-modal="true" aria-labelledby="modal-titulo" onclick="cerrarModalClick(event)">
  <div class="modal">
    <div class="modal-drag-handle"></div>
    <div class="modal-header">
      <span class="modal-title" id="modal-titulo">Detalle del producto</span>
      <button class="modal-close" onclick="cerrarModal()" aria-label="Cerrar">&times;</button>
    </div>
    <div class="modal-body">
      <div class="modal-gallery">
        <div id="modal-img-wrap"></div>
        <div class="modal-thumbs" id="modal-thumbs"></div>
      </div>
      <div class="modal-info" id="modal-info"></div>
    </div>
  </div>
</div>

<?php include './includes/footer.php'; ?>

<?php
// Función helper para renderizar una card (reutilizable en JS también)
function renderCard($p, $en_stock, $i = 0) {
  $delay = ($i % 12) * 40;
  ob_start();
?>
<article class="product-card" role="listitem"
     style="animation-delay:<?= $delay ?>ms"
     onclick="abrirModal(<?= htmlspecialchars(json_encode($p), ENT_QUOTES) ?>)"
     aria-label="<?= htmlspecialchars($p['nombre']) ?> — $<?= number_format($p['precio'],2) ?> USD">

  <span class="stock-badge <?= $en_stock?'in':'out' ?>">
    <?= $en_stock?'STOCK':'AGOTADO' ?>
  </span>

  <div class="card-img">
    <?php if ($p['imagen_url'] ?? null): ?>
      <img src="<?= htmlspecialchars($p['imagen_url']) ?>"
           alt="<?= htmlspecialchars($p['nombre']) ?> VP Motos Ecuador"
           loading="lazy" width="300" height="300">
    <?php else: ?>
      <div class="no-img" aria-hidden="true">
        <svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1">
          <rect x="3" y="3" width="18" height="18" rx="2"/>
          <circle cx="8.5" cy="8.5" r="1.5"/>
          <polyline points="21 15 16 10 5 21"/>
        </svg>
        <span>SIN IMAGEN</span>
      </div>
    <?php endif; ?>
  </div>

  <div class="card-body">
    <?php if ($p['categoria']): ?>
      <div class="card-categoria"><?= htmlspecialchars($p['categoria']) ?></div>
    <?php endif; ?>
    <h2 class="card-nombre"><?= htmlspecialchars($p['nombre']) ?></h2>
    <?php if ($p['marca']): ?>
      <div class="card-marca"><?= htmlspecialchars($p['marca']) ?></div>
    <?php endif; ?>
    <div class="card-footer">
      <div class="card-precio">
        $<?= number_format($p['precio'],2) ?>
        <small>IVA inc.</small>
      </div>
      <div class="card-stock">
        Stock<br><strong><?= (int)$p['stock'] ?></strong>
      </div>
    </div>
  </div>

  <?php if ($en_stock): ?>
  <button class="btn-add-card"
    aria-label="Agregar al carrito"
    onclick="event.stopPropagation(); addToCart(
      <?= $p['id'] ?>,
      <?= htmlspecialchars(json_encode($p['nombre']), ENT_QUOTES) ?>,
      <?= $p['precio'] ?>,
      <?= htmlspecialchars(json_encode($p['imagen_url'] ?? ''), ENT_QUOTES) ?>,
      <?= htmlspecialchars(json_encode($p['codigo']), ENT_QUOTES) ?>,
      1
    )">
    <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
      <path d="M6 2 3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4z"/>
      <line x1="3" y1="6" x2="21" y2="6"/>
      <path d="M16 10a4 4 0 0 1-8 0"/>
    </svg>
    AGREGAR
  </button>
  <?php else: ?>
  <button class="btn-add-card agotado" disabled onclick="event.stopPropagation()">AGOTADO</button>
  <?php endif; ?>

</article>
<?php
  return ob_get_clean();
}
?>

<script>
// ══ ESTADO ══
let _currentPage = 1;
let _totalPages  = <?= $total_paginas ?>;
let _loading     = false;
let _allLoaded   = <?= ($total_paginas <= 1) ? 'true' : 'false' ?>;
let _modalProducto = null;

// Parámetros actuales para AJAX
const _params = new URLSearchParams(window.location.search);
_params.set('ajax_page', '');

// ══ INFINITE SCROLL ══
const sentinel = document.getElementById('scroll-sentinel');
const grid     = document.getElementById('products-grid');
const loadBtn  = document.getElementById('load-more-btn');
const endMsg   = document.getElementById('end-msg');

const observer = new IntersectionObserver((entries) => {
  if (entries[0].isIntersecting && !_loading && !_allLoaded) {
    cargarMas();
  }
}, { rootMargin: '200px' });

if (sentinel) observer.observe(sentinel);

async function cargarMas() {
  if (_loading || _allLoaded) return;
  _loading = true;
  if (loadBtn) loadBtn.classList.add('loading');

  const nextPage = _currentPage + 1;
  const params = new URLSearchParams(window.location.search);
  params.set('ajax_page', nextPage);

  try {
    const res  = await fetch('tienda.php?' + params.toString());
    const data = await res.json();

    if (data.productos && data.productos.length > 0) {
      data.productos.forEach((p, i) => {
        const html = buildCard(p, i);
        const tmp  = document.createElement('div');
        tmp.innerHTML = html;
        const card = tmp.firstElementChild;
        grid.appendChild(card);
        // Trigger animation
        void card.offsetWidth;
      });

      _currentPage = data.page;

      // Actualizar contador
      const shown = grid.querySelectorAll('.product-card').length;
      const shownEl = document.getElementById('shown-count');
      if (shownEl) shownEl.textContent = shown;
    }

    if (!data.has_more) {
      _allLoaded = true;
      if (loadBtn) loadBtn.style.display = 'none';
      if (endMsg)  endMsg.classList.add('show');
    }
  } catch(e) {
    console.error('Error cargando productos:', e);
  }

  _loading = false;
  if (loadBtn) loadBtn.classList.remove('loading');
}

// Construir card HTML desde JS (para productos cargados via AJAX)
function buildCard(p, i) {
  const enStock = parseInt(p.stock) > 0;
  const imgHtml = p.imagen_url
    ? `<img src="${escHTML(p.imagen_url)}" alt="${escHTML(p.nombre)}" loading="lazy" width="300" height="300">`
    : `<div class="no-img"><svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1"><rect x="3" y="3" width="18" height="18" rx="2"/><circle cx="8.5" cy="8.5" r="1.5"/><polyline points="21 15 16 10 5 21"/></svg><span>SIN IMAGEN</span></div>`;

  const pData = JSON.stringify(p).replace(/'/g, "\\'");
  const delay = (i % 12) * 40;

  return `
  <article class="product-card" role="listitem" style="animation-delay:${delay}ms"
    onclick='abrirModal(${JSON.stringify(p)})'
    aria-label="${escHTML(p.nombre)} — $${parseFloat(p.precio).toFixed(2)} USD">
    <span class="stock-badge ${enStock?'in':'out'}">${enStock?'STOCK':'AGOTADO'}</span>
    <div class="card-img">${imgHtml}</div>
    <div class="card-body">
      ${p.categoria ? `<div class="card-categoria">${escHTML(p.categoria)}</div>` : ''}
      <h2 class="card-nombre">${escHTML(p.nombre)}</h2>
      ${p.marca ? `<div class="card-marca">${escHTML(p.marca)}</div>` : ''}
      <div class="card-footer">
        <div class="card-precio">$${parseFloat(p.precio).toFixed(2)}<small>IVA inc.</small></div>
        <div class="card-stock">Stock<br><strong>${parseInt(p.stock)}</strong></div>
      </div>
    </div>
    ${enStock
      ? `<button class="btn-add-card" onclick="event.stopPropagation();addToCart(${p.id},${JSON.stringify(p.nombre)},${p.precio},${JSON.stringify(p.imagen_url||'')},${JSON.stringify(p.codigo)},1)">
           <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M6 2 3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4z"/><line x1="3" y1="6" x2="21" y2="6"/><path d="M16 10a4 4 0 0 1-8 0"/></svg>
           AGREGAR
         </button>`
      : `<button class="btn-add-card agotado" disabled onclick="event.stopPropagation()">AGOTADO</button>`
    }
  </article>`;
}

// ══ DRAWER MOBILE ══
function abrirDrawer() {
  document.getElementById('drawer').classList.add('open');
  document.getElementById('drawer-overlay').classList.add('open');
  document.body.style.overflow = 'hidden';
}
function cerrarDrawer() {
  document.getElementById('drawer').classList.remove('open');
  document.getElementById('drawer-overlay').classList.remove('open');
  document.body.style.overflow = '';
}

// ══ MODAL ══
function abrirModal(p) {
  _modalProducto = p;
  const overlay = document.getElementById('modal');
  document.getElementById('modal-titulo').textContent = p.nombre;

  const imgs = [p.imagen_url, p.imagen_2_url, p.imagen_3_url].filter(Boolean);
  const imgWrap = document.getElementById('modal-img-wrap');
  const thumbsWrap = document.getElementById('modal-thumbs');

  if (imgs.length > 0) {
    imgWrap.innerHTML = `<img class="modal-img-main" id="modal-main-img" src="${imgs[0]}" alt="${escHTML(p.nombre)}">`;
    thumbsWrap.innerHTML = imgs.map((src,i) =>
      `<img class="modal-thumb ${i===0?'active':''}" src="${src}" alt="${escHTML(p.nombre)}" onclick="cambiarImg(this,'${src}')">`
    ).join('');
  } else {
    imgWrap.innerHTML = `<div class="modal-img-main no-img-modal">SIN IMAGEN</div>`;
    thumbsWrap.innerHTML = '';
  }

  const enStock = parseInt(p.stock) > 0;
  const stockColor = enStock ? 'var(--accent)' : 'var(--danger)';

  document.getElementById('modal-info').innerHTML = `
    ${p.categoria ? `<div class="modal-categoria">${escHTML(p.categoria)}</div>` : ''}
    <div class="modal-nombre">${escHTML(p.nombre)}</div>
    ${p.marca ? `<div class="modal-marca">Marca: ${escHTML(p.marca)}</div>` : ''}
    <div class="modal-precio">$${parseFloat(p.precio).toFixed(2)} <small>USD · IVA inc.</small></div>
    <div class="modal-meta">
      <div class="meta-row"><span class="meta-key">CÓDIGO</span><span class="meta-val accent">${escHTML(p.codigo)}</span></div>
      <div class="meta-row"><span class="meta-key">STOCK</span><span class="meta-val" style="color:${stockColor}">${enStock ? p.stock + ' unidades' : 'Agotado'}</span></div>
      ${p.marca ? `<div class="meta-row"><span class="meta-key">MARCA</span><span class="meta-val">${escHTML(p.marca)}</span></div>` : ''}
    </div>
    ${p.descripcion ? `<div class="modal-desc">${escHTML(p.descripcion)}</div>` : ''}
    <div class="modal-cart-section">
      ${enStock ? `
      <div class="modal-qty-row">
        <span class="modal-qty-label">CANTIDAD</span>
        <div class="qty-control">
          <button class="qty-btn-m" type="button" onclick="adjModalQty(-1)">−</button>
          <input class="qty-val" id="modal-qty" type="number" value="1" min="1" max="${p.stock}">
          <button class="qty-btn-m" type="button" onclick="adjModalQty(1)">+</button>
        </div>
        <span style="font-size:0.58rem;color:var(--muted)">Máx ${p.stock}</span>
      </div>
      <button class="btn-add-modal" onclick="addFromModal()">
        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M6 2 3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4z"/><line x1="3" y1="6" x2="21" y2="6"/><path d="M16 10a4 4 0 0 1-8 0"/></svg>
        AGREGAR AL CARRITO
      </button>
      ` : `<button class="btn-add-modal" disabled>PRODUCTO AGOTADO</button>`}
      <a href="cart.php" class="btn-ver-carrito">
        <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M6 2 3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4z"/><line x1="3" y1="6" x2="21" y2="6"/><path d="M16 10a4 4 0 0 1-8 0"/></svg>
        Ver carrito (<span id="modal-cart-count">${document.getElementById('cart-badge').textContent}</span>)
      </a>
    </div>
  `;

  overlay.classList.add('open');
  document.body.style.overflow = 'hidden';
}

function cerrarModal() {
  document.getElementById('modal').classList.remove('open');
  document.body.style.overflow = '';
}
function cerrarModalClick(e) {
  if (e.target === document.getElementById('modal')) cerrarModal();
}
function cambiarImg(thumb, src) {
  const main = document.getElementById('modal-main-img');
  if (main) main.src = src;
  document.querySelectorAll('.modal-thumb').forEach(t => t.classList.remove('active'));
  thumb.classList.add('active');
}
function adjModalQty(d) {
  const inp = document.getElementById('modal-qty');
  if (!inp) return;
  inp.value = Math.max(1, Math.min(parseInt(inp.max)||99, parseInt(inp.value||1)+d));
}
function addFromModal() {
  if (!_modalProducto) return;
  const qty = parseInt(document.getElementById('modal-qty')?.value||1);
  addToCart(_modalProducto.id, _modalProducto.nombre, _modalProducto.precio,
    _modalProducto.imagen_url||'', _modalProducto.codigo, qty);
}

// ══ CARRITO ══
function addToCart(id, nombre, precio, imagen, codigo, cantidad) {
  const body = new URLSearchParams({action:'agregar',producto_id:id,nombre,precio,imagen,codigo,cantidad,ajax:1});
  fetch('cart.php', {method:'POST',headers:{'Content-Type':'application/x-www-form-urlencoded'},body})
    .then(r => r.json())
    .then(data => {
      if (data.success) {
        updateCartUI(data.total_items);
        showToast(nombre, cantidad);
        const mc = document.getElementById('modal-cart-count');
        if (mc) mc.textContent = data.total_items;
        if (typeof actualizarBadgeNav === 'function') actualizarBadgeNav(data.total_items);
      }
    })
    .catch(() => { window.location = 'cart.php'; });
}
function updateCartUI(n) {
  const badge = document.getElementById('cart-badge');
  const float = document.getElementById('cart-float');
  if (badge) badge.textContent = n;
  if (float) float.classList.toggle('visible', n > 0);
}
let toastTimer;
function showToast(nombre, cantidad) {
  const t = document.getElementById('toast');
  document.getElementById('toast-title').textContent = `${cantidad}× ${nombre.substring(0,28)}${nombre.length>28?'…':''}`;
  document.getElementById('toast-sub').textContent = 'Agregado al carrito ✓';
  t.classList.add('show');
  clearTimeout(toastTimer);
  toastTimer = setTimeout(() => t.classList.remove('show'), 2800);
}

function escHTML(s) {
  const d = document.createElement('div'); d.textContent = String(s||''); return d.innerHTML;
}

// Swipe para cerrar drawer
let touchStartY = 0;
document.getElementById('drawer').addEventListener('touchstart', e => { touchStartY = e.touches[0].clientY; }, {passive:true});
document.getElementById('drawer').addEventListener('touchmove', e => {
  if (e.touches[0].clientY - touchStartY > 60) cerrarDrawer();
}, {passive:true});

document.addEventListener('keydown', e => {
  if (e.key === 'Escape') { cerrarModal(); cerrarDrawer(); }
});
</script>
</body>
</html>