<?php
include 'conexao.php';
// Adicionamos o 'id' na busca abaixo:
$res = $conn->query("SELECT id, nome FROM personagens");
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="style.css">
    <style>
        body {
            margin: 0;
            background-image: linear-gradient(180deg, #ffbb00, #120000);
            background-repeat: no-repeat;
            background-attachment: fixed;
            background-size: cover;
            min-height: 100vh;
        }
    </style>
    <title>【𖤐 Kaøs ┃ Beco dos Magos 𖤐】</title>
</head>
<body>
<div class="container">
    <h1>Escolha seu Personagem:</h1>

    <div class="card">
    <?php 
    if ($res && $res->num_rows > 0) {
        while($row = $res->fetch_assoc()) {
            $n = $row['nome'];
            $id_del = $row['id']; // Agora o ID vai existir aqui!
            echo "
            <div style='display: flex; justify-content: space-between; align-items: center; margin-bottom: 10px; border-bottom: 1px solid #333; padding-bottom: 5px;'>
                <a href='ficha.php?nome=".urlencode($n)."' style='color: #ffbb00; text-decoration: none; font-size: 1.2rem; font-weight: bold;'>
                   ⚔️ $n
                </a>
                
                <a href='deletar_personagem.php?id=$id_del' 
                   onclick='return confirm(\"Tem certeza que deseja apagar a ficha de $n?\")' 
                   style='color: #ff4444; text-decoration: none; font-size: 0.8rem; background: rgba(255,0,0,0.1); padding: 4px 8px; border-radius: 5px; border: 1px solid #ff4444;'>
                   EXCLUIR
                </a>
            </div>";
        }
    } else {
        echo "<p>Nenhum personagem encontrado.</p>";
    }
    ?>
    </div>

    <div class="card">
        <h2>Criar Nova Ficha</h2>
        <form action="criar_personagem.php" method="POST" style="display: flex; gap: 10px;">
            <input type="text" name="nome" placeholder="Nome do Personagem" required 
                   style="flex: 1; padding: 10px; border-radius: 8px; border: 1px solid #444; background: #222; color: white;">
            <button type="submit" style="padding: 10px 20px; border-radius: 8px; background: #ffbb00; border: none; cursor: pointer; font-weight: bold;">
                CRIAR
            </button>
        </form>
    </div>
</div>
</body>
</html>