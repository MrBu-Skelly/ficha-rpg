<?php
$host = "sql113.infinityfree.com"; // MySQL Hostname da imagem
$usuario = "if0_41085476";         // MySQL Username da imagem
$senha = "9uKQmM0IF5azr1";       // MySQL Password da imagem
$banco = "if0_41085476_epizy_123_rpg_db"; // O DATABASE NAME real da imagem

$conn = new mysqli($host, $usuario, $senha, $banco);

if ($conn->connect_error) {
    die("Falha na conexão: " . $conn->connect_error);
}

$conn->set_charset("utf8mb4");
?>