<?php

// ============================================================================
// PHP Setups
// ============================================================================

date_default_timezone_set('Asia/Kuala_Lumpur');
session_start();

// ============================================================================
// General Page Functions
// ============================================================================

// Is GET request?
function is_get() {
    return $_SERVER['REQUEST_METHOD'] == 'GET';
}

// Is POST request?
function is_post() {
    return $_SERVER['REQUEST_METHOD'] == 'POST';
}

// Obtain GET parameter
function get($key, $value = null) {
    $value = $_GET[$key] ?? $value;
    return is_array($value) ? array_map('trim', $value) : (is_string($value) ? trim($value) : $value);
}

// Obtain POST parameter
function post($key, $value = null) {
    $value = $_POST[$key] ?? $value;
    return is_array($value) ? array_map('trim', $value) : (is_string($value) ? trim($value) : $value);
}

// Obtain REQUEST (GET and POST) parameter
function req($key, $value = null) {
    $value = $_REQUEST[$key] ?? $value;
    return is_array($value) ? array_map('trim', $value) : (is_string($value) ? trim($value) : $value);
}

// Redirect to URL
function redirect($url = null) {
    $url ??= $_SERVER['REQUEST_URI'];
    header("Location: $url");
    exit();
}

// Set or get temporary session variable
function temp($key, $value = null) {
    if ($value !== null) {
        $_SESSION["temp_$key"] = $value;
    }
    else {
        $value = $_SESSION["temp_$key"] ?? null;
        unset($_SESSION["temp_$key"]);
        return $value;
    }
}

// Obtain uploaded file --> cast to object
function get_file($key) {
    $f = $_FILES[$key] ?? null;
    
    if ($f && $f['error'] == 0) {
        return (object)$f;
    }

    return null;
}

// Crop, resize and save photo
function save_photo($f, $folder) {
    if (!file_exists($folder)) {
        mkdir($folder, 0777, true); // Create folder if it doesn't exist
    }
    $photo = uniqid() . '.jpg';
    move_uploaded_file($f->tmp_name, "$folder/$photo");
    return $photo;
}

// Is money?
function is_money($value) {
    return preg_match('/^\-?\d+(\.\d{1,2})?$/', $value);
}

// Is email?
function is_email($value) {
    return filter_var($value, FILTER_VALIDATE_EMAIL) !== false;
}

// Return local root path
function root($path = '') {
    return "$_SERVER[DOCUMENT_ROOT]/$path";
}

// Return base url (host + port)
function base($path = '') {
    return "http://$_SERVER[SERVER_NAME]:$_SERVER[SERVER_PORT]/$path";
}

// ============================================================================
// HTML Helpers
// ============================================================================

// Encode HTML special characters
function encode($value) {
    return htmlentities($value ?? '');
}

// NEW: Added to fix red lines in admin pages
function html_options($items, $selected = null) {
    foreach ($items as $key => $value) {
        $v = is_numeric($key) ? $value : $key;
        $active = ($v == $selected) ? 'selected' : '';
        printf("<option value='%s' %s>%s</option>\n", encode($v), $active, encode($value));
    }
}

// Generate <input type='hidden'>
function html_hidden($key, $value = '', $attr = '') {
    $value = encode($value);
    echo "<input type='hidden' id='$key' name='$key' value='$value' $attr>";
}

// Generate <input type='text'>
function html_text($key, $attr = '') {
    $value = encode($_POST[$key] ?? '');
    echo "<input type='text' id='$key' name='$key' value='$value' $attr>";
}

// Generate <input type='password'>
function html_password($key, $attr = '') {
    $value = encode($_POST[$key] ?? '');
    echo "<input type='password' id='$key' name='$key' value='$value' $attr>";
}

// Generate <input type='number'>
function html_number($key, $min = '', $max = '', $step = '', $attr = '') {
    $value = encode($_POST[$key] ?? '');
    echo "<input type='number' id='$key' name='$key' value='$value' min='$min' max='$max' step='$step' $attr>";
}

// Generate <select>
function html_select($key, $items, $value = '', $default = '- Select One -', $attr = '') {
    $value = encode($value);
    echo "<select id='$key' name='$key' $attr>";
    if ($default !== null) {
        echo "<option value=''>$default</option>";
    }
    foreach ($items as $id => $text) {
        $state = $id == $value ? 'selected' : '';
        echo "<option value='$id' $state>$text</option>";
    }
    echo '</select>';
}

// Generate <input type='file'>
function html_file($key, $accept = '', $attr = '') {
    echo "<input type='file' id='$key' name='$key' accept='$accept' $attr>";
}

// ============================================================================
// Error Handlings
// ============================================================================

$_err = [];

function err($key) {
    global $_err;
    if ($_err[$key] ?? false) {
        echo "<span class='err'>$_err[$key]</span>";
    }
    else {
        echo '<span></span>';
    }
}

// ============================================================================
// Security
// ============================================================================

$_user = $_SESSION['user'] ?? null;

function login($user, $url = '/') {
    $_SESSION['user'] = $user;
    redirect($url);
}

function logout($url = '/') {
    unset($_SESSION['user']);
    $_SESSION['logout'] = true; 
    redirect($url);
}

// Roles-based access control
function auth(...$roles) {
    global $_user;
    if ($_user) {
        if ($roles) {
            if (in_array($_user->role, $roles)) {
                return; 
            }
        }
    }
    redirect('/login.php'); 
}

// ============================================================================
// Shopping Cart
// ============================================================================

function get_cart() {
    return $_SESSION['cart'] ?? [];
}

function set_cart($cart = []) {
    $_SESSION['cart'] = $cart;
}

function update_cart($id, $unit) {
    // Change 'item' to 'product_variants' and ensure it uses 'variant_id'
    if (is_exists($id, 'product_variants', 'variant_id')) {
        $_SESSION['cart'][$id] = (int)$unit;
        if ($_SESSION['cart'][$id] <= 0) {
            unset($_SESSION['cart'][$id]);
        }
    }
}

// ============================================================================
// Database Setups and Functions
// ============================================================================

// Database connection
$_db = new PDO('mysql:host=localhost;dbname=shopping_cart', 'root', '', [
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_OBJ,
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
]);

// Auto-login logic
if (!$_user && isset($_COOKIE['remember']) && !isset($_SESSION['logout'])) {
    $email = $_COOKIE['remember'];
    $stm = $_db->prepare("SELECT * FROM user WHERE email = ?");
    $stm->execute([$email]);
    $u = $stm->fetch();
    if ($u) {
        $_user = $_SESSION['user'] = $u; 
    }
}

function is_unique($value, $table, $field) {
    global $_db;
    $stm = $_db->prepare("SELECT COUNT(*) FROM $table WHERE $field = ?");
    $stm->execute([$value]);
    return $stm->fetchColumn() == 0;
}

function is_exists($value, $table, $field) {
    global $_db;
    $stm = $_db->prepare("SELECT COUNT(*) FROM $table WHERE $field = ?");
    $stm->execute([$value]);
    return $stm->fetchColumn() > 0;
}

function generate_id($table, $column, $prefix, $length) {
    global $_db;
    $stm = $_db->prepare("SELECT MAX($column) FROM `$table` WHERE $column LIKE ?");
    $stm->execute(["$prefix%"]);
    $max = $stm->fetchColumn();

    if ($max) {
        $n = (int)substr($max, strlen($prefix)) + 1;
    } else {
        $n = 1;
    }

    return $prefix . str_pad($n, $length, '0', STR_PAD_LEFT);
}

// ============================================================================
// Global Constants and Variables
// ============================================================================

$_units = array_combine(range(1, 10), range(1, 10));