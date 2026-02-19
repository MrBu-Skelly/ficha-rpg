<?php
include 'conexao.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $coluna = $_POST['coluna']; 
    $valor = $_POST['valor'];   
    $id = $_POST['id'];

    // LISTA ATUALIZADA - Se faltar um aqui, não salva!
$permitidas = [
    'vidaat', 'vidamax', 'manaat', 'manamax', 
    'forca', 'agilidade', 'constituicao', 'carisma', 
    'inteligencia', 'sabedoria', 'feitico', 
    'inventario', 'pericias', 'proficiencias', 
    'magias', 'habilidades' // ADICIONE AQUI
];

    if (in_array($coluna, $permitidas)) {
        $stmt = $conn->prepare("UPDATE personagens SET $coluna = ? WHERE id = ?");
        $stmt->bind_param("si", $valor, $id); 
        $stmt->execute();
        $stmt->close();
        echo "Sucesso";
    }
}
?>