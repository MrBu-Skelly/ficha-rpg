<?php
include 'conexao.php';

if (isset($_GET['id'])) {
    $id = $_GET['id'];

    // 1. Buscar o nome da imagem para deletar o arquivo físico
    $stmt = $conn->prepare("SELECT imagem FROM personagens WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $foto = $stmt->get_result()->fetch_assoc()['imagem'];

    if ($foto && $foto !== 'kurujo.jpg') {
        if (file_exists("uploads/" . $foto)) {
            unlink("uploads/" . $foto);
        }
    }

    // 2. Deletar do banco de dados
    $stmt_del = $conn->prepare("DELETE FROM personagens WHERE id = ?");
    $stmt_del->bind_param("i", $id);
    
    if ($stmt_del->execute()) {
        header("Location: index.php?msg=deletado");
    } else {
        echo "Erro ao deletar.";
    }
}
?>