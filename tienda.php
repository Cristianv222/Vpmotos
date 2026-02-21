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

// ── SEO dinámico según filtros ──────────────────────────────────
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
  <meta name="viewport" content="width=device-width, initial-scale=1.0">

  <!-- ═══ SEO ═══════════════════════════════════════════════════════ -->
  <title><?= $page_title ?></title>
  <meta name="description" content="<?= $page_desc ?>">
  <meta name="keywords" content="repuestos motos Quito, accesorios motos Ecuador, llantas moto Ecuador, aceite moto Quito, comprar repuestos moto online Ecuador, tienda motos Quito, repuestos Honda Ecuador, repuestos Yamaha Ecuador, repuestos Bajaj Ecuador, repuestos KTM Quito<?= $categoria ? ', ' . htmlspecialchars($categoria) . ' moto Ecuador' : '' ?>">
  <meta name="author" content="VP Motos">
  <meta name="robots" content="index, follow, max-snippet:-1, max-image-preview:large">
  <link rel="canonical" href="https://www.vpmotos.ec/tienda.php<?= $categoria ? '?categoria=' . urlencode($categoria) : '' ?>">
  <meta name="geo.region" content="EC-P">
  <meta name="geo.placename" content="Quito, Ecuador">

  <!-- ═══ OPEN GRAPH ════════════════════════════════════════════════ -->
  <meta property="og:type" content="website">
  <meta property="og:url" content="https://www.vpmotos.ec/tienda.php">
  <meta property="og:title" content="<?= $page_title ?>">
  <meta property="og:description" content="<?= $page_desc ?>">
  <meta property="og:image" content="https://www.vpmotos.ec/images/logo_vp.png">
  <meta property="og:locale" content="es_EC">
  <meta property="og:site_name" content="VP Motos">

  <!-- ═══ TWITTER CARD ═════════════════════════════════════════════ -->
  <meta name="twitter:card" content="summary_large_image">
  <meta name="twitter:title" content="<?= $page_title ?>">
  <meta name="twitter:description" content="<?= $page_desc ?>">
  <meta name="twitter:image" content="https://www.vpmotos.ec/images/logo_vp.png">

  <!-- ═══ FAVICON ═══════════════════════════════════════════════════ -->
  <link rel="icon" type="image/png" href="./images/logo_vp.png">
  <link rel="apple-touch-icon" href="./images/logo_vp.png">
  <meta name="theme-color" content="#D2ED05">

  <!-- ═══ SCHEMA.ORG — ItemList de productos ═══════════════════════ -->
  <script type="application/ld+json">
  {
    "@context": "https://schema.org",
    "@type": "ItemList",
    "name": "<?= $categoria ?: 'Repuestos y Accesorios para Motos' ?> — VP Motos Quito",
    "description": "<?= $page_desc ?>",
    "url": "https://www.vpmotos.ec/tienda.php",
    "numberOfItems": <?= count($productos) ?>,
    "itemListElement": <?= json_encode(
      array_map(fn($p, $i) => [
        '@type'    => 'ListItem',
        'position' => $i + 1,
        'item'     => [
          '@type'       => 'Product',
          'name'        => $p['nombre'],
          'description' => $p['descripcion'] ?? '',
          'brand'       => ['@type' => 'Brand', 'name' => $p['marca'] ?? 'VP Motos'],
          'offers'      => [
            '@type'         => 'Offer',
            'price'         => number_format($p['precio'], 2, '.', ''),
            'priceCurrency' => 'USD',
            'availability'  => ($p['stock'] > 0)
                               ? 'https://schema.org/InStock'
                               : 'https://schema.org/OutOfStock',
            'seller'        => ['@type' => 'Organization', 'name' => 'VP Motos'],
          ],
        ],
      ], array_slice($productos, 0, 10), range(0, 9))
    ) ?>
  }
  </script>

  <!-- ═══ BREADCRUMB Schema ════════════════════════════════════════ -->
  <script type="application/ld+json">
  {
    "@context": "https://schema.org",
    "@type": "BreadcrumbList",
    "itemListElement": [
      { "@type": "ListItem", "position": 1, "name": "Inicio", "item": "https://www.vpmotos.ec/" },
      { "@type": "ListItem", "position": 2, "name": "Tienda", "item": "https://www.vpmotos.ec/tienda.php" }
      <?= $categoria ? ', { "@type": "ListItem", "position": 3, "name": "' . htmlspecialchars($categoria) . '", "item": "https://www.vpmotos.ec/tienda.php?categoria=' . urlencode($categoria) . '" }' : '' ?>
    ]
  }
  </script>

  <!-- Fonts -->
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

    /* ══ HERO ══ */
    .shop-hero {
      position:relative; width:100%; min-height:360px; overflow:hidden;
      display:flex; align-items:flex-end; justify-content:flex-start; padding:50px 60px;
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
    .hero-breadcrumb {
      font-size:0.6rem; letter-spacing:0.2em; color:rgba(255,255,255,0.35);
      margin-bottom:16px; display:flex; align-items:center; gap:8px;
    }
    .hero-breadcrumb a { color:var(--accent); text-decoration:none; transition:opacity .2s; }
    .hero-breadcrumb a:hover { opacity:.7; }
    .shop-hero-content h1 {
      font-family:'Orbitron',sans-serif; font-size:clamp(2rem,5vw,3.8rem);
      font-weight:400; letter-spacing:0.2em; text-transform:uppercase; color:#fff; line-height:1;
    }
    .shop-hero-content h1 span { color:var(--accent); }
    .shop-hero-content p { margin-top:14px; color:var(--muted); font-size:0.72rem; letter-spacing:0.12em; line-height:1.7; max-width:500px; }
    .hero-line { width:60px; height:2px; background:var(--accent); margin-bottom:20px; }

    /* ── SEO TEXT EN HERO — visible para Google ───────────────── */
    .hero-seo-tags {
      display:flex; flex-wrap:wrap; gap:8px; margin-top:20px;
    }
    .hero-seo-tag {
      font-size:0.55rem; letter-spacing:0.12em; padding:4px 12px;
      border:1px solid rgba(210,237,5,0.2); border-radius:20px;
      color:rgba(255,255,255,0.3); background:rgba(210,237,5,0.04);
      text-decoration:none; transition:all .2s;
    }
    .hero-seo-tag:hover {
      border-color:rgba(210,237,5,0.5); color:var(--accent); background:rgba(210,237,5,0.08);
    }

    /* ══ CART FLOAT BUTTON ══ */
    .cart-float {
      position:fixed; bottom:28px; right:28px; z-index:500;
      display:flex; align-items:center; gap:10px;
      background:var(--accent); color:#000;
      font-family:'Orbitron',sans-serif; font-size:0.65rem; font-weight:700; letter-spacing:0.1em;
      padding:12px 20px; border-radius:50px; text-decoration:none;
      box-shadow:0 8px 32px rgba(210,237,5,0.4);
      transition:all .25s cubic-bezier(0.16,1,0.3,1);
    }
    .cart-float:hover { background:var(--accent2); transform:translateY(-4px) scale(1.03); box-shadow:0 16px 48px rgba(210,237,5,0.5); }
    .cart-float-badge {
      background:#000; color:var(--accent); font-size:0.6rem;
      width:20px; height:20px; border-radius:50%; display:flex; align-items:center; justify-content:center;
      font-weight:700;
    }
    .cart-float[data-count="0"] { display:none; }

    /* ══ TOAST ══ */
    .toast {
      position:fixed; bottom:100px; right:28px; z-index:600;
      background:var(--surface); border:1px solid rgba(210,237,5,0.3);
      border-radius:10px; padding:12px 18px;
      display:flex; align-items:center; gap:10px;
      font-size:0.65rem; color:var(--text);
      box-shadow:0 8px 32px rgba(0,0,0,0.5);
      transform:translateX(120%); transition:transform .35s cubic-bezier(0.16,1,0.3,1);
      pointer-events:none; min-width:240px;
    }
    .toast.show { transform:translateX(0); }
    .toast-icon { font-size:1.2rem; }
    .toast-txt strong { display:block; color:#fff; margin-bottom:2px; }
    .toast-txt span { color:var(--muted); }

    /* ══ LAYOUT ══ */
    .shop-layout { display:grid; grid-template-columns:260px 1fr; gap:0; min-height:80vh; }

    /* ══ SIDEBAR ══ */
    .sidebar {
      background:var(--surface); border-right:1px solid var(--border);
      padding:32px 24px; position:sticky; top:0; height:100vh; overflow-y:auto;
    }
    .sidebar::-webkit-scrollbar { width:4px; }
    .sidebar::-webkit-scrollbar-thumb { background:var(--border); border-radius:2px; }
    .sidebar-section { margin-bottom:36px; }
    .sidebar-label {
      font-family:'Orbitron',sans-serif; font-size:0.6rem; letter-spacing:0.25em;
      text-transform:uppercase; color:var(--accent); margin-bottom:16px;
      display:flex; align-items:center; gap:8px;
    }
    .sidebar-label::after { content:''; flex:1; height:1px; background:var(--border); }

    .search-wrap { position:relative; }
    .search-wrap input {
      width:100%; background:var(--surface2); border:1px solid var(--border);
      border-radius:var(--radius); padding:10px 14px 10px 38px;
      color:var(--text); font-family:'Audiowide',sans-serif; font-size:0.7rem;
      outline:none; transition:border-color .2s;
    }
    .search-wrap input:focus { border-color:rgba(210,237,5,0.4); }
    .search-wrap input::placeholder { color:var(--muted); }
    .search-icon { position:absolute; left:12px; top:50%; transform:translateY(-50%); color:var(--muted); font-size:0.8rem; pointer-events:none; }

    .cat-list { list-style:none; display:flex; flex-direction:column; gap:4px; }
    .cat-list li a {
      display:flex; align-items:center; justify-content:space-between;
      padding:8px 12px; border-radius:6px; text-decoration:none;
      color:var(--muted); font-size:0.7rem; letter-spacing:0.05em;
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
      font-family:'Audiowide',sans-serif; font-size:0.7rem; outline:none;
      cursor:pointer; appearance:none;
      background-image:url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' viewBox='0 0 24 24' fill='none' stroke='%23666' stroke-width='2'%3E%3Cpolyline points='6 9 12 15 18 9'%3E%3C/polyline%3E%3C/svg%3E");
      background-repeat:no-repeat; background-position:right 12px center;
    }
    .order-select:focus { border-color:rgba(210,237,5,0.4); }

    .stat-box { background:var(--surface2); border:1px solid var(--border); border-radius:var(--radius); padding:16px; text-align:center; }
    .stat-box .num { font-family:'Orbitron',sans-serif; font-size:2rem; color:var(--accent); line-height:1; }
    .stat-box .lbl { font-size:0.6rem; color:var(--muted); letter-spacing:0.15em; margin-top:6px; }

    /* Bloque SEO en sidebar */
    .sidebar-seo-block {
      background: rgba(210,237,5,0.04);
      border: 1px solid rgba(210,237,5,0.1);
      border-radius: 8px;
      padding: 14px;
    }
    .sidebar-seo-block p {
      font-size: 0.58rem;
      color: rgba(255,255,255,0.2);
      line-height: 1.8;
      letter-spacing: 0.04em;
    }
    .sidebar-seo-block strong { color: rgba(210,237,5,0.3); }

    /* ══ MAIN ══ */
    .shop-main { padding:32px 40px; }
    .shop-topbar {
      display:flex; align-items:center; justify-content:space-between;
      margin-bottom:28px; padding-bottom:20px; border-bottom:1px solid var(--border);
    }
    .results-info { font-size:0.7rem; color:var(--muted); letter-spacing:0.1em; }
    .results-info strong { color:var(--accent); font-size:0.9rem; }
    .active-filters { display:flex; gap:8px; flex-wrap:wrap; }
    .filter-tag {
      display:inline-flex; align-items:center; gap:6px;
      background:rgba(210,237,5,0.1); border:1px solid rgba(210,237,5,0.3);
      border-radius:20px; padding:4px 12px; font-size:0.65rem; color:var(--accent);
      letter-spacing:0.08em; text-decoration:none;
    }
    .filter-tag:hover { background:rgba(210,237,5,0.2); }

    /* ══ PRODUCT GRID ══ */
    .products-grid { display:grid; grid-template-columns:repeat(auto-fill,minmax(240px,1fr)); gap:20px; }

    .product-card {
      background:var(--surface); border:1px solid var(--border);
      border-radius:var(--radius); overflow:hidden;
      transition:transform .3s cubic-bezier(0.16,1,0.3,1), border-color .3s, box-shadow .3s;
      cursor:pointer; position:relative;
    }
    .product-card:hover {
      transform:translateY(-6px); border-color:rgba(210,237,5,0.3);
      box-shadow:0 20px 60px rgba(0,0,0,0.6), 0 0 0 1px rgba(210,237,5,0.1);
    }

    .stock-badge {
      position:absolute; top:12px; right:12px; z-index:2;
      font-size:0.55rem; letter-spacing:0.12em; padding:4px 8px; border-radius:20px;
      font-family:'Orbitron',sans-serif;
    }
    .stock-badge.in  { background:rgba(210,237,5,0.15); color:var(--accent); border:1px solid rgba(210,237,5,0.3); }
    .stock-badge.out { background:rgba(255,68,68,0.15); color:var(--danger); border:1px solid rgba(255,68,68,0.3); }

    .card-img { position:relative; width:100%; height:200px; overflow:hidden; background:var(--surface2); }
    .card-img img { width:100%; height:100%; object-fit:cover; transition:transform .5s cubic-bezier(0.16,1,0.3,1); }
    .product-card:hover .card-img img { transform:scale(1.08); }
    .no-img { width:100%; height:100%; display:flex; flex-direction:column; align-items:center; justify-content:center; gap:10px; color:var(--muted); }
    .no-img svg { opacity:.3; }
    .no-img span { font-size:0.6rem; letter-spacing:0.15em; opacity:.5; }
    .card-img-overlay {
      position:absolute; inset:0;
      background:linear-gradient(180deg,transparent 50%,rgba(0,0,0,0.8) 100%);
      opacity:0; transition:opacity .3s; display:flex; align-items:flex-end; padding:16px;
    }
    .product-card:hover .card-img-overlay { opacity:1; }
    .overlay-codigo { font-family:'Orbitron',sans-serif; font-size:0.55rem; letter-spacing:0.15em; color:var(--accent); }

    .card-body { padding:18px; }
    .card-categoria { font-size:0.55rem; letter-spacing:0.2em; text-transform:uppercase; color:var(--accent); margin-bottom:8px; opacity:.8; }
    .card-nombre {
      font-family:'Orbitron',sans-serif; font-size:0.8rem; font-weight:500; color:#fff;
      line-height:1.4; margin-bottom:6px; letter-spacing:0.05em;
      display:-webkit-box; -webkit-line-clamp:2; -webkit-box-orient:vertical; overflow:hidden;
    }
    .card-marca { font-size:0.6rem; color:var(--muted); letter-spacing:0.1em; margin-bottom:14px; }
    .card-footer {
      display:flex; align-items:center; justify-content:space-between;
      padding-top:14px; border-top:1px solid var(--border);
    }
    .card-precio { font-family:'Orbitron',sans-serif; font-size:1.1rem; color:var(--accent); font-weight:700; }
    .card-precio small { font-size:0.55rem; color:var(--muted); display:block; letter-spacing:0.1em; font-weight:400; }
    .card-stock { font-size:0.6rem; color:var(--muted); text-align:right; }
    .card-stock strong { color:var(--text); }

    .btn-add-card {
      position:absolute; bottom:0; left:0; right:0;
      background:rgba(210,237,5,0.9); color:#000;
      font-family:'Orbitron',sans-serif; font-size:0.6rem; font-weight:700; letter-spacing:0.12em;
      border:none; padding:10px; cursor:pointer;
      transform:translateY(100%); transition:transform .25s cubic-bezier(0.16,1,0.3,1);
      display:flex; align-items:center; justify-content:center; gap:8px;
    }
    .product-card:hover .btn-add-card { transform:translateY(0); }
    .btn-add-card:active { background:var(--accent2); }
    .btn-add-card.agotado { background:rgba(255,68,68,0.7); color:#fff; cursor:not-allowed; }

    /* ══ EMPTY / ERROR ══ */
    .empty-state {
      grid-column:1/-1; display:flex; flex-direction:column; align-items:center;
      justify-content:center; padding:80px 40px; text-align:center; gap:16px;
    }
    .empty-state svg { opacity:.2; }
    .empty-state h3 { font-family:'Orbitron',sans-serif; font-size:1rem; letter-spacing:0.2em; color:var(--muted); }
    .empty-state p { font-size:0.7rem; color:var(--muted); opacity:.7; }
    .empty-state a {
      margin-top:8px; display:inline-block; padding:10px 24px;
      border:1px solid rgba(210,237,5,0.4); border-radius:6px; color:var(--accent);
      text-decoration:none; font-size:0.65rem; letter-spacing:0.15em; transition:background .2s;
    }
    .empty-state a:hover { background:rgba(210,237,5,0.08); }

    .api-error {
      grid-column:1/-1; background:rgba(255,68,68,0.08); border:1px solid rgba(255,68,68,0.2);
      border-radius:var(--radius); padding:24px; display:flex; align-items:center; gap:16px;
      color:#ff8888; font-size:0.75rem;
    }

    /* ══ MODAL ══ */
    .modal-overlay {
      position:fixed; inset:0; background:rgba(0,0,0,0.85); z-index:1000;
      display:flex; align-items:center; justify-content:center; padding:20px;
      opacity:0; pointer-events:none; transition:opacity .3s; backdrop-filter:blur(8px);
    }
    .modal-overlay.open { opacity:1; pointer-events:all; }
    .modal {
      background:var(--surface); border:1px solid rgba(210,237,5,0.2);
      border-radius:16px; width:100%; max-width:720px; max-height:90vh; overflow-y:auto;
      transform:translateY(30px) scale(0.97); transition:transform .3s cubic-bezier(0.16,1,0.3,1);
    }
    .modal-overlay.open .modal { transform:translateY(0) scale(1); }
    .modal::-webkit-scrollbar { width:4px; }
    .modal::-webkit-scrollbar-thumb { background:var(--border); }

    .modal-header {
      display:flex; align-items:center; justify-content:space-between;
      padding:22px 28px; border-bottom:1px solid var(--border);
    }
    .modal-title { font-family:'Orbitron',sans-serif; font-size:0.85rem; letter-spacing:0.12em; color:#fff; }
    .modal-close {
      background:var(--surface2); border:1px solid var(--border); color:var(--muted);
      width:36px; height:36px; border-radius:50%; cursor:pointer;
      display:flex; align-items:center; justify-content:center; font-size:1rem; transition:all .2s;
    }
    .modal-close:hover { border-color:rgba(210,237,5,0.4); color:var(--accent); }

    .modal-body { display:grid; grid-template-columns:1fr 1fr; gap:0; }
    .modal-gallery { padding:24px; border-right:1px solid var(--border); }
    .modal-img-main { width:100%; aspect-ratio:1; object-fit:cover; border-radius:10px; background:var(--surface2); }
    .modal-img-main.no-img-modal {
      display:flex; align-items:center; justify-content:center;
      background:#1a1a1a; border-radius:10px; aspect-ratio:1;
      font-size:0.65rem; letter-spacing:0.15em; color:#444;
    }
    .modal-thumbs { display:flex; gap:8px; margin-top:12px; }
    .modal-thumb { width:60px; height:60px; object-fit:cover; border-radius:6px; border:2px solid transparent; cursor:pointer; transition:border-color .2s; }
    .modal-thumb:hover, .modal-thumb.active { border-color:var(--accent); }

    .modal-info { padding:24px; display:flex; flex-direction:column; }
    .modal-categoria { font-size:0.6rem; letter-spacing:0.2em; color:var(--accent); margin-bottom:10px; }
    .modal-nombre { font-family:'Orbitron',sans-serif; font-size:1.05rem; color:#fff; line-height:1.4; margin-bottom:6px; }
    .modal-marca { font-size:0.65rem; color:var(--muted); margin-bottom:18px; }
    .modal-precio { font-family:'Orbitron',sans-serif; font-size:1.9rem; color:var(--accent); margin-bottom:18px; }
    .modal-precio small { font-size:0.7rem; color:var(--muted); font-weight:400; }

    .modal-meta { display:flex; flex-direction:column; gap:8px; margin-bottom:16px; }
    .meta-row { display:flex; align-items:center; justify-content:space-between; padding:9px 13px; background:var(--surface2); border-radius:8px; font-size:0.65rem; }
    .meta-row .meta-key { color:var(--muted); letter-spacing:0.1em; }
    .meta-row .meta-val { color:#fff; font-weight:500; }
    .meta-row .meta-val.accent { color:var(--accent); }

    .modal-desc { font-size:0.68rem; color:var(--muted); line-height:1.8; padding:13px; background:var(--surface2); border-radius:8px; border-left:3px solid var(--accent); margin-bottom:18px; }

    .modal-cart-section { margin-top:auto; padding-top:16px; border-top:1px solid var(--border); }
    .modal-qty-row { display:flex; align-items:center; gap:12px; margin-bottom:12px; }
    .modal-qty-label { font-size:0.6rem; color:var(--muted); letter-spacing:.1em; white-space:nowrap; }
    .qty-control { display:flex; align-items:center; background:var(--surface2); border:1px solid var(--border); border-radius:8px; overflow:hidden; }
    .qty-btn-m { width:36px; height:36px; border:none; background:transparent; color:var(--text); font-size:1.2rem; cursor:pointer; transition:all .2s; display:flex; align-items:center; justify-content:center; }
    .qty-btn-m:hover { background:rgba(210,237,5,0.1); color:var(--accent); }
    .qty-val { width:44px; height:36px; background:transparent; border:none; color:#fff; font-family:'Orbitron',sans-serif; font-size:0.85rem; text-align:center; outline:none; }
    .qty-val::-webkit-outer-spin-button, .qty-val::-webkit-inner-spin-button { -webkit-appearance:none; }

    .btn-add-modal {
      display:flex; align-items:center; justify-content:center; gap:10px;
      width:100%; padding:13px; background:var(--accent); color:#000;
      font-family:'Orbitron',sans-serif; font-size:0.72rem; font-weight:700; letter-spacing:0.12em;
      border:none; border-radius:var(--radius); cursor:pointer; transition:all .25s;
    }
    .btn-add-modal:hover { background:var(--accent2); transform:translateY(-2px); box-shadow:0 8px 28px rgba(210,237,5,.35); }
    .btn-add-modal:disabled { opacity:.4; cursor:not-allowed; transform:none; box-shadow:none; }
    .btn-ver-carrito {
      display:flex; align-items:center; justify-content:center; gap:8px;
      width:100%; padding:10px; margin-top:8px;
      background:transparent; color:var(--accent);
      font-family:'Audiowide',sans-serif; font-size:0.65rem; letter-spacing:0.08em;
      border:1px solid rgba(210,237,5,0.3); border-radius:var(--radius);
      cursor:pointer; text-decoration:none; transition:all .2s;
    }
    .btn-ver-carrito:hover { background:rgba(210,237,5,0.08); }

    /* ══ RESPONSIVE ══ */
    @media(max-width:1024px) { .shop-hero { padding:36px 36px; } .shop-main { padding:24px; } }
    @media(max-width:900px) {
      .shop-layout { grid-template-columns:1fr; }
      .sidebar { position:static; height:auto; display:grid; grid-template-columns:1fr 1fr; gap:16px; padding:20px; }
      .shop-main { padding:20px; }
      .shop-hero { padding:30px 20px; min-height:240px; }
      .modal-body { grid-template-columns:1fr; }
      .modal-gallery { border-right:none; border-bottom:1px solid var(--border); }
      .cart-float { bottom:20px; right:20px; }
    }
    @media(max-width:600px) {
      .sidebar { grid-template-columns:1fr; }
      .products-grid { grid-template-columns:1fr 1fr; gap:12px; }
      .modal { max-width:100%; border-radius:12px 12px 0 0; }
      .modal-overlay { align-items:flex-end; padding:0; }
      .cart-float span { display:none; }
      .cart-float { padding:14px 16px; border-radius:50%; }
      .hero-seo-tags { display:none; }
    }
  </style>
</head>
<body>

<?php include './includes/menu.php'; ?>

<!-- ══ HERO SEO-OPTIMIZADO ══ -->
<header class="shop-hero" role="banner">
  <div class="shop-hero-bg"></div>
  <div class="shop-hero-content">
    <nav class="hero-breadcrumb" aria-label="Ruta de navegación">
      <a href="index.php">Inicio</a>
      <span>›</span>
      <span>Tienda</span>
      <?php if ($categoria): ?>
        <span>›</span>
        <span><?= htmlspecialchars($categoria) ?></span>
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
        Repuestos originales para Honda, Yamaha, Bajaj, KTM, Kawasaki y más en Quito, Ecuador.
      <?php elseif ($categoria === 'Llantas'): ?>
        Llantas para moto en Quito. Todas las medidas y marcas con envío a todo Ecuador.
      <?php elseif ($categoria === 'Aceites'): ?>
        Aceites y lubricantes para moto en Ecuador. Motul, Castrol, Amsoil y más marcas certificadas.
      <?php elseif ($categoria === 'Accesorios'): ?>
        Accesorios y equipamiento para motocicletas en Quito. Calidad garantizada.
      <?php else: ?>
        Repuestos originales · Accesorios · Llantas · Aceites — Envíos a todo Ecuador
      <?php endif; ?>
    </p>
    <!-- Tags SEO clickeables — texto real indexable por Google -->
    <div class="hero-seo-tags" role="navigation" aria-label="Categorías">
      <a href="tienda.php?categoria=Repuestos" class="hero-seo-tag">Repuestos originales Quito</a>
      <a href="tienda.php?categoria=Accesorios" class="hero-seo-tag">Accesorios moto Ecuador</a>
      <a href="tienda.php?categoria=Llantas" class="hero-seo-tag">Llantas moto Quito</a>
      <a href="tienda.php?categoria=Aceites" class="hero-seo-tag">Aceites lubricantes Ecuador</a>
    </div>
  </div>
</header>

<!-- BOTÓN FLOTANTE CARRITO -->
<a href="cart.php" class="cart-float" id="cart-float"
   data-count="<?= $carrito_count ?>"
   aria-label="Ver carrito de compras">
  <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" aria-hidden="true">
    <path d="M6 2 3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4z"/>
    <line x1="3" y1="6" x2="21" y2="6"/>
    <path d="M16 10a4 4 0 0 1-8 0"/>
  </svg>
  <span>Carrito</span>
  <div class="cart-float-badge" id="cart-badge"><?= $carrito_count ?></div>
</a>

<!-- TOAST -->
<div class="toast" id="toast" role="status" aria-live="polite">
  <div class="toast-icon" id="toast-icon">✓</div>
  <div class="toast-txt">
    <strong id="toast-title">Producto agregado</strong>
    <span id="toast-sub">Agregado al carrito ✓</span>
  </div>
</div>

<!-- LAYOUT -->
<div class="shop-layout">

  <!-- SIDEBAR -->
  <aside class="sidebar" aria-label="Filtros de productos">
    <form method="GET" action="" id="filter-form">

      <div class="sidebar-section">
        <div class="sidebar-label">Buscar</div>
        <div class="search-wrap">
          <span class="search-icon" aria-hidden="true">⌕</span>
          <input type="text" name="q"
            placeholder="Nombre o código..."
            value="<?= htmlspecialchars($busqueda) ?>"
            aria-label="Buscar productos"
            oninput="document.getElementById('filter-form').submit()">
        </div>
      </div>

      <div class="sidebar-section">
        <div class="sidebar-label">Categorías</div>
        <ul class="cat-list" role="list">
          <li>
            <a href="?<?= http_build_query(array_merge($_GET,['categoria'=>''])) ?>"
               class="<?= !$categoria ? 'active' : '' ?>"
               aria-current="<?= !$categoria ? 'page' : 'false' ?>">
              <span class="cat-dot" aria-hidden="true"></span>Todos los productos
            </a>
          </li>
          <?php foreach ($categorias as $cat): ?>
          <li>
            <a href="?<?= http_build_query(array_merge($_GET,['categoria'=>$cat])) ?>"
               class="<?= $categoria===$cat ? 'active' : '' ?>"
               aria-current="<?= $categoria===$cat ? 'page' : 'false' ?>">
              <span class="cat-dot" aria-hidden="true"></span><?= htmlspecialchars($cat) ?>
            </a>
          </li>
          <?php endforeach; ?>
        </ul>
      </div>

      <div class="sidebar-section">
        <div class="sidebar-label">Ordenar por</div>
        <select class="order-select" name="orden" onchange="this.form.submit()" aria-label="Ordenar productos">
          <option value="" <?= !$orden?'selected':'' ?>>Relevancia</option>
          <option value="precio_asc"  <?= $orden==='precio_asc' ?'selected':'' ?>>Precio: menor a mayor</option>
          <option value="precio_desc" <?= $orden==='precio_desc'?'selected':'' ?>>Precio: mayor a menor</option>
          <option value="nombre_asc"  <?= $orden==='nombre_asc' ?'selected':'' ?>>Nombre A–Z</option>
        </select>
      </div>
    </form>

    <div class="sidebar-section">
      <div class="sidebar-label">Total</div>
      <div class="stat-box">
        <div class="num"><?= count($productos) ?></div>
        <div class="lbl">Productos<?= $categoria ? ' en ' . htmlspecialchars($categoria) : ' disponibles' ?></div>
      </div>
    </div>

    <!-- Bloque SEO en sidebar — Google lo indexa como contenido relevante -->
    <div class="sidebar-section">
      <div class="sidebar-seo-block">
        <p>
          <strong>VP Motos Quito</strong> — Repuestos originales y accesorios para
          <strong>Honda, Yamaha, Bajaj, KTM, Kawasaki</strong> y más marcas.
          Envíos a todo Ecuador. Precios en USD con IVA incluido.
        </p>
      </div>
    </div>
  </aside>

  <!-- MAIN -->
  <main class="shop-main" role="main">
    <div class="shop-topbar">
      <div class="results-info">
        <strong><?= count($productos) ?></strong> resultado<?= count($productos)!==1?'s':'' ?>
        <?php if ($busqueda): ?> para "<em><?= htmlspecialchars($busqueda) ?></em>"<?php endif; ?>
        <?php if ($categoria): ?> en <em><?= htmlspecialchars($categoria) ?></em><?php endif; ?>
      </div>
      <div class="active-filters">
        <?php if ($busqueda): ?>
          <a href="?<?= http_build_query(array_merge($_GET,['q'=>''])) ?>" class="filter-tag">
            🔍 <?= htmlspecialchars($busqueda) ?> ×
          </a>
        <?php endif; ?>
        <?php if ($categoria): ?>
          <a href="?<?= http_build_query(array_merge($_GET,['categoria'=>''])) ?>" class="filter-tag">
            📦 <?= htmlspecialchars($categoria) ?> ×
          </a>
        <?php endif; ?>
      </div>
    </div>

    <!-- GRID -->
    <div class="products-grid" role="list" aria-label="Productos VP Motos">
      <?php if ($error): ?>
        <div class="api-error" role="alert">
          <span style="font-size:1.5rem">⚠️</span>
          <div>
            <strong>Error al conectar con el inventario:</strong><br>
            <?= htmlspecialchars($error) ?><br>
            <small style="opacity:.6">Verifica que el servidor esté activo y el token sea correcto.</small>
          </div>
        </div>

      <?php elseif (empty($productos)): ?>
        <div class="empty-state">
          <svg width="64" height="64" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1">
            <circle cx="11" cy="11" r="8"/><path d="m21 21-4.35-4.35"/>
          </svg>
          <h3>Sin resultados</h3>
          <p>No encontramos productos con esos criterios</p>
          <a href="tienda.php">Ver todos los productos</a>
        </div>

      <?php else: ?>
        <?php foreach ($productos as $p):
          $en_stock = (int)$p['stock'] > 0;
        ?>
        <article class="product-card" role="listitem"
             onclick="abrirModal(<?= htmlspecialchars(json_encode($p), ENT_QUOTES) ?>)"
             aria-label="<?= htmlspecialchars($p['nombre']) ?> — $<?= number_format($p['precio'],2) ?> USD">

          <span class="stock-badge <?= $en_stock?'in':'out' ?>" aria-label="<?= $en_stock?'En stock':'Agotado' ?>">
            <?= $en_stock?'EN STOCK':'AGOTADO' ?>
          </span>

          <div class="card-img">
            <?php if ($p['imagen_url'] ?? null): ?>
              <img src="<?= htmlspecialchars($p['imagen_url']) ?>"
                   alt="<?= htmlspecialchars($p['nombre']) ?> — Repuesto moto <?= htmlspecialchars($p['marca'] ?? '') ?> Quito Ecuador"
                   loading="lazy" width="300" height="200">
            <?php else: ?>
              <div class="no-img" aria-hidden="true">
                <svg width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1">
                  <rect x="3" y="3" width="18" height="18" rx="2"/>
                  <circle cx="8.5" cy="8.5" r="1.5"/>
                  <polyline points="21 15 16 10 5 21"/>
                </svg>
                <span>SIN IMAGEN</span>
              </div>
            <?php endif; ?>
            <div class="card-img-overlay" aria-hidden="true">
              <span class="overlay-codigo"><?= htmlspecialchars($p['codigo']) ?></span>
            </div>
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
                <small>IVA incluido</small>
              </div>
              <div class="card-stock">
                Stock<br><strong><?= (int)$p['stock'] ?></strong>
              </div>
            </div>
          </div>

          <?php if ($en_stock): ?>
          <button class="btn-add-card"
            aria-label="Agregar <?= htmlspecialchars($p['nombre']) ?> al carrito"
            onclick="event.stopPropagation(); addToCart(
              <?= $p['id'] ?>,
              <?= htmlspecialchars(json_encode($p['nombre']), ENT_QUOTES) ?>,
              <?= $p['precio'] ?>,
              <?= htmlspecialchars(json_encode($p['imagen_url'] ?? ''), ENT_QUOTES) ?>,
              <?= htmlspecialchars(json_encode($p['codigo']), ENT_QUOTES) ?>,
              1
            )">
            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" aria-hidden="true">
              <path d="M6 2 3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4z"/>
              <line x1="3" y1="6" x2="21" y2="6"/>
              <path d="M16 10a4 4 0 0 1-8 0"/>
            </svg>
            AGREGAR
          </button>
          <?php else: ?>
          <button class="btn-add-card agotado" disabled aria-disabled="true" onclick="event.stopPropagation()">
            AGOTADO
          </button>
          <?php endif; ?>

        </article>
        <?php endforeach; ?>
      <?php endif; ?>
    </div>
  </main>
</div>

<!-- MODAL DETALLE -->
<div class="modal-overlay" id="modal" role="dialog" aria-modal="true" aria-labelledby="modal-titulo"
     onclick="cerrarModalClick(event)">
  <div class="modal">
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

<script>
let _modalProducto = null;

function abrirModal(p) {
  _modalProducto = p;
  const overlay = document.getElementById('modal');
  document.getElementById('modal-titulo').textContent = p.nombre;

  const imgs = [p.imagen_url, p.imagen_2_url, p.imagen_3_url].filter(Boolean);
  const imgWrap = document.getElementById('modal-img-wrap');
  const thumbsWrap = document.getElementById('modal-thumbs');

  if (imgs.length > 0) {
    imgWrap.innerHTML = `<img class="modal-img-main" id="modal-main-img" src="${imgs[0]}" alt="${escHTML(p.nombre)} — VP Motos Quito Ecuador">`;
    thumbsWrap.innerHTML = imgs.map((src, i) =>
      `<img class="modal-thumb ${i===0?'active':''}" src="${src}" alt="${escHTML(p.nombre)}" onclick="cambiarImg(this,'${src}')">`
    ).join('');
  } else {
    imgWrap.innerHTML = `<div class="modal-img-main no-img-modal">SIN IMAGEN</div>`;
    thumbsWrap.innerHTML = '';
  }

  const stockColor = p.stock > 0 ? 'var(--accent)' : 'var(--danger)';
  const enStock = p.stock > 0;

  document.getElementById('modal-info').innerHTML = `
    ${p.categoria ? `<div class="modal-categoria">${escHTML(p.categoria)}</div>` : ''}
    <div class="modal-nombre">${escHTML(p.nombre)}</div>
    ${p.marca ? `<div class="modal-marca">Marca: ${escHTML(p.marca)}</div>` : ''}
    <div class="modal-precio">$${parseFloat(p.precio).toFixed(2)} <small>USD · IVA inc.</small></div>
    <div class="modal-meta">
      <div class="meta-row">
        <span class="meta-key">CÓDIGO</span>
        <span class="meta-val accent">${escHTML(p.codigo)}</span>
      </div>
      <div class="meta-row">
        <span class="meta-key">STOCK</span>
        <span class="meta-val" style="color:${stockColor}">${p.stock > 0 ? p.stock + ' unidades' : 'Agotado'}</span>
      </div>
      ${p.marca ? `<div class="meta-row"><span class="meta-key">MARCA</span><span class="meta-val">${escHTML(p.marca)}</span></div>` : ''}
    </div>
    ${p.descripcion ? `<div class="modal-desc">${escHTML(p.descripcion)}</div>` : ''}
    <div class="modal-cart-section">
      ${enStock ? `
      <div class="modal-qty-row">
        <span class="modal-qty-label">CANTIDAD</span>
        <div class="qty-control">
          <button class="qty-btn-m" type="button" onclick="adjModalQty(-1)" aria-label="Reducir cantidad">−</button>
          <input class="qty-val" id="modal-qty" type="number" value="1" min="1" max="${p.stock}" aria-label="Cantidad">
          <button class="qty-btn-m" type="button" onclick="adjModalQty(1)" aria-label="Aumentar cantidad">+</button>
        </div>
        <span style="font-size:0.6rem;color:var(--muted)">Máx. ${p.stock}</span>
      </div>
      <button class="btn-add-modal" id="btn-add-modal" onclick="addFromModal()">
        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" aria-hidden="true">
          <path d="M6 2 3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4z"/>
          <line x1="3" y1="6" x2="21" y2="6"/>
          <path d="M16 10a4 4 0 0 1-8 0"/>
        </svg>
        AGREGAR AL CARRITO
      </button>
      ` : `
      <button class="btn-add-modal" disabled aria-disabled="true">PRODUCTO AGOTADO</button>
      `}
      <a href="cart.php" class="btn-ver-carrito" aria-label="Ver carrito">
        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true">
          <path d="M6 2 3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4z"/>
          <line x1="3" y1="6" x2="21" y2="6"/>
          <path d="M16 10a4 4 0 0 1-8 0"/>
        </svg>
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
  document.getElementById('modal-main-img').src = src;
  document.querySelectorAll('.modal-thumb').forEach(t => t.classList.remove('active'));
  thumb.classList.add('active');
}

function adjModalQty(d) {
  const inp = document.getElementById('modal-qty');
  if (!inp) return;
  const max = parseInt(inp.max) || 99;
  inp.value = Math.max(1, Math.min(max, parseInt(inp.value || 1) + d));
}

function addFromModal() {
  if (!_modalProducto) return;
  const qty = parseInt(document.getElementById('modal-qty')?.value || 1);
  addToCart(_modalProducto.id, _modalProducto.nombre, _modalProducto.precio,
    _modalProducto.imagen_url || '', _modalProducto.codigo, qty);
}

function addToCart(id, nombre, precio, imagen, codigo, cantidad) {
  const body = new URLSearchParams({
    action:'agregar', producto_id:id, nombre, precio, imagen, codigo, cantidad, ajax:1
  });
  fetch('cart.php', { method:'POST', headers:{'Content-Type':'application/x-www-form-urlencoded'}, body })
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
  if (float) { float.dataset.count = n; float.style.display = n > 0 ? 'flex' : 'none'; }
}

let toastTimer;
function showToast(nombre, cantidad) {
  const t = document.getElementById('toast');
  document.getElementById('toast-title').textContent = `${cantidad}× ${nombre.substring(0,30)}${nombre.length>30?'…':''}`;
  document.getElementById('toast-sub').textContent = 'Agregado al carrito ✓';
  t.classList.add('show');
  clearTimeout(toastTimer);
  toastTimer = setTimeout(() => t.classList.remove('show'), 2800);
}

document.addEventListener('keydown', e => { if (e.key === 'Escape') cerrarModal(); });

document.addEventListener('DOMContentLoaded', () => {
  const count = parseInt(document.getElementById('cart-badge')?.textContent || 0);
  const float = document.getElementById('cart-float');
  if (float) float.style.display = count > 0 ? 'flex' : 'none';
});

function escHTML(s) {
  const d = document.createElement('div'); d.textContent = s; return d.innerHTML;
}
</script>
</body>
</html>