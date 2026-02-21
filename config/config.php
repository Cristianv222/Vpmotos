<?php
// ── Base de datos ─────────────────────────────────────────────────
define('DB_HOST', getenv('DB_HOST') ?: 'db');
define('DB_USER', getenv('DB_USER') ?: 'root');
define('DB_PASS', getenv('DB_PASS') ?: '');
define('DB_NAME', getenv('DB_NAME') ?: 'vpmotos');

// ── API Inventario ────────────────────────────────────────────────
define('API_BASE_URL',    getenv('API_BASE_URL')           ?: 'http://inventario-web:8000');
define('API_TOKEN',       getenv('VPMOTOS_API_TOKEN') ?: '');
define('API_URL',         rtrim(API_BASE_URL, '/') . '/inventario/api/publica/productos/');
define('API_PEDIDOS_URL', rtrim(API_BASE_URL, '/') . '/ventas/api/publica/pedidos/crear/');

// ── WhatsApp tienda ───────────────────────────────────────────────
define('WHATSAPP_TIENDA', getenv('WHATSAPP_TIENDA') ?: '593996628440');

// ── BD — no bloquea si falla (tienda solo usa la API) ─────────────
$pdo = null;
try {
    $pdo = new PDO(
        "mysql:host=".DB_HOST.";dbname=".DB_NAME,
        DB_USER, DB_PASS,
        [PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8"]
    );
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) { $pdo = null; }