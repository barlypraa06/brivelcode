<?php
session_start();

$tool = $_GET['tool'] ?? 'home';
$result = "";
$error = "";

// =========================
// CAESAR CIPHER
// =========================
function caesarEncrypt($text, $shift)
{
    $result = "";

    for ($i = 0; $i < strlen($text); $i++) {

        $char = $text[$i];

        if (ctype_alpha($char)) {

            $ascii = ord($char);
            $base = ctype_upper($char) ? 65 : 97;

            $result .= chr((($ascii - $base + $shift) % 26) + $base);
        } else {
            $result .= $char;
        }
    }

    return $result;
}

function caesarDecrypt($text, $shift)
{
    return caesarEncrypt($text, 26 - $shift);
}

// =========================
// XOR CIPHER
// =========================
function xorEncrypt($text, $key)
{
    $output = '';

    for ($i = 0; $i < strlen($text); $i++) {

        $output .= chr(
            ord($text[$i]) ^ ord($key[$i % strlen($key)])
        );
    }

    return bin2hex($output);
}

function xorDecrypt($hex, $key)
{
    $text = hex2bin($hex);
    $output = '';

    for ($i = 0; $i < strlen($text); $i++) {

        $output .= chr(
            ord($text[$i]) ^ ord($key[$i % strlen($key)])
        );
    }

    return $output;
}

// =========================
// FORM PROCESS
// =========================
if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    if (isset($_POST['caesar_encrypt'])) {
        $text = $_POST['text'];
        $shift = (int)$_POST['shift'];
        $result = caesarEncrypt($text, $shift);
    }

    if (isset($_POST['caesar_decrypt'])) {
        $text = $_POST['text'];
        $shift = (int)$_POST['shift'];
        $result = caesarDecrypt($text, $shift);
    }

    if (isset($_POST['xor_encrypt'])) {
        $text = $_POST['text'];
        $key = $_POST['key'];
        $result = xorEncrypt($text, $key);
    }

    if (isset($_POST['xor_decrypt'])) {
        $text = $_POST['text'];
        $key = $_POST['key'];
        $result = xorDecrypt($text, $key);
    }

    if (isset($_POST['generate_hash'])) {
        $text = $_POST['text'];
        $result = hash('sha256', $text);
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>CryptoTools PHP</title>

    <style>

        *{
            margin:0;
            padding:0;
            box-sizing:border-box;
        }

        body{
            font-family:Arial;
            background:#0f172a;
            color:white;
        }

        .container{
            width:90%;
            max-width:1000px;
            margin:auto;
            padding:30px 0;
        }

        .header{
            text-align:center;
            margin-bottom:30px;
        }

        .header h1{
            font-size:40px;
            color:#38bdf8;
            margin-bottom:10px;
        }

        .menu{
            display:flex;
            gap:10px;
            flex-wrap:wrap;
            justify-content:center;
            margin-bottom:30px;
        }

        .menu a{
            text-decoration:none;
            background:#1e293b;
            color:white;
            padding:15px 20px;
            border-radius:10px;
        }

        .menu a:hover{
            background:#38bdf8;
            color:black;
        }

        .card{
            background:#1e293b;
            padding:30px;
            border-radius:20px;
        }

        input, textarea{
            width:100%;
            padding:12px;
            margin-top:10px;
            margin-bottom:20px;
            border:none;
            border-radius:10px;
            background:#334155;
            color:white;
        }

        button{
            padding:12px 20px;
            border:none;
            border-radius:10px;
            background:#38bdf8;
            font-weight:bold;
            cursor:pointer;
        }

        .result{
            margin-top:20px;
            background:#0f172a;
            padding:20px;
            border-radius:10px;
            word-wrap:break-word;
        }

    </style>
</head>

<body>

<div class="container">

    <div class="header">
        <h1>CryptoTools PHP</h1>
        <p>Aplikasi Kriptografi PHP</p>
    </div>

    <div class="menu">
        <a href="?tool=home">Home</a>
        <a href="?tool=caesar">Caesar</a>
        <a href="?tool=xor">XOR</a>
        <a href="?tool=sha">SHA-256</a>
    </div>

    <div class="card">

    <?php switch($tool){

        case 'caesar':
    ?>

        <h2>Caesar Cipher</h2>

        <form method="POST">

            <textarea name="text" placeholder="Masukkan text"></textarea>

            <input type="number" name="shift" value="3">

            <button type="submit" name="caesar_encrypt">Encrypt</button>
            <button type="submit" name="caesar_decrypt">Decrypt</button>

        </form>

    <?php
        break;

        case 'xor':
    ?>

        <h2>XOR Cipher</h2>

        <form method="POST">

            <textarea name="text" placeholder="Masukkan text / hex"></textarea>

            <input type="text" name="key" placeholder="Masukkan key">

            <button type="submit" name="xor_encrypt">Encrypt</button>
            <button type="submit" name="xor_decrypt">Decrypt</button>

        </form>

    <?php
        break;

        case 'sha':
    ?>

        <h2>SHA-256 Generator</h2>

        <form method="POST">

            <textarea name="text" placeholder="Masukkan text"></textarea>

            <button type="submit" name="generate_hash">Generate Hash</button>

        </form>

    <?php
        break;

        default:
    ?>

        <h2>Selamat Datang</h2>

        <p>
            Project PjBL Kriptografi PHP Single File.
        </p>

    <?php } ?>

    <?php if($result != ""): ?>

        <div class="result">
            <strong>Hasil:</strong>
            <br><br>
            <?= htmlspecialchars($result) ?>
        </div>

    <?php endif; ?>

    </div>

</div>

</body>
</html>
