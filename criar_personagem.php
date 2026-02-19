<?php
include 'conexao.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && !empty($_POST['nome'])) {
    $nome = $_POST['nome'];

    // Preparamos o SQL com todos os campos zerados ou padrão
    // Importante: Verifique se os nomes das colunas batem com o seu banco
    $sql = "INSERT INTO personagens (nome, vidaat, vidamax, manaat, manamax, forca, agilidade, constituicao, carisma, inteligencia, sabedoria, feitico, inventario, pericias, proficiencias, magias, habilidades, imagem) 
        VALUES (?, 10, 10, 10, 10, 0, 0, 0, 0, 0, 0, 0, '', '', '', '', '', 'base.jpg')";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $nome);

    if ($stmt->execute()) {
        // Após criar, redireciona para a ficha nova usando o nome
        header("Location: ficha.php?nome=" . urlencode($nome));
        exit();
    } else {
        echo "Erro ao criar personagem: " . $conn->error;
    }
} else {
    header("Location: menu.php");
    exit();
}
?>