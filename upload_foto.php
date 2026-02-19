<?php
include 'conexao.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['foto'])) {
    $id = $_POST['id'];
    $arquivo = $_FILES['foto'];
    
    // 1. Buscar o nome da imagem antiga no banco de dados
    $sql_busca = "SELECT imagem FROM personagens WHERE id = ?";
    $stmt_busca = $conn->prepare($sql_busca);
    $stmt_busca->bind_param("i", $id);
    $stmt_busca->execute();
    $resultado = $stmt_busca->get_result();
    $p_antigo = $resultado->fetch_assoc();
    $foto_antiga = $p_antigo['imagem'];

    // 2. Preparar a nova imagem
    $extensao = pathinfo($arquivo['name'], PATHINFO_EXTENSION);
    $novo_nome = "personagem_" . $id . "_" . time() . "." . $extensao;
    $destino = "uploads/" . $novo_nome;

    if (move_uploaded_file($arquivo['tmp_name'], $destino)) {
        
        // 3. Deletar o arquivo antigo da pasta (se ele existir e não for a imagem padrão)
        if (!empty($foto_antiga) && $foto_antiga !== 'kurujo.jpg') {
            $caminho_antigo = "uploads/" . $foto_antiga;
            if (file_exists($caminho_antigo)) {
                unlink($caminho_antigo); // APAGA O ARQUIVO FISICAMENTE
            }
        }

        // 4. Salvar o novo nome no banco
        $stmt = $conn->prepare("UPDATE personagens SET imagem = ? WHERE id = ?");
        $stmt->bind_param("si", $novo_nome, $id);
        $stmt->execute();
        
        echo $novo_nome;
    } else {
        echo "erro";
    }
}
?>