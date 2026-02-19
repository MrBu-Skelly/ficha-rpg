<?php
include 'conexao.php';

$nome_url = isset($_GET['nome']) ? urldecode($_GET['nome']) : '∆KORUJO∆';

$sql = "SELECT * FROM personagens WHERE nome = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $nome_url);
$stmt->execute();
$p = $stmt->get_result()->fetch_assoc();

if (!$p) { die("Personagem não encontrado!"); }
$id = $p['id'];
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="style.css">
    <title>Ficha - <?php echo $p['nome']; ?></title>
    <style>
        /* Estilo solicitado para os inputs numéricos */
        .status-input {
            width: 60px;
            background: #111;
            color: #fff;
            border: 1px solid #555;
            border-radius: 6px;
            padding: 4px;
            text-align: center;
            font-family: inherit;
        }
        
        /* Ajuste para remover as setas nativas do input number (opcional, para estética) */
        input::-webkit-outer-spin-button,
        input::-webkit-inner-spin-button {
            -webkit-appearance: none;
            margin: 0;
        }
    </style>
</head>
<body>
<div class="container">
    <h1><?php echo $p['nome']; ?></h1>
    <div class="portrait">
    <label for="foto_input" style="cursor: pointer;">
        <img src="<?php echo (!empty($p['imagem']) && file_exists('uploads/'.$p['imagem'])) ? 'uploads/'.$p['imagem'] : 'base.jpg'; ?>" 
             id="img_personagem">
    </label>
    <input type="file" id="foto_input" style="display: none;" accept="image/*" onchange="subirFoto(this, <?php echo $id; ?>)">
</div>

    <div class="card">
        <h2>Status</h2>
        <strong>Vida</strong>
        <div class="status-row">
            Atu: <input type="number" id="va" class="status-input" value="<?php echo $p['vidaat']; ?>" oninput="updateBars()" onchange="salvar('vidaat', this.value, <?php echo $id; ?>)">
            Máx: <input type="number" id="vm" class="status-input" value="<?php echo $p['vidamax']; ?>" oninput="updateBars()" onchange="salvar('vidamax', this.value, <?php echo $id; ?>)">
        </div>
        <div class="bar"><div class="bar-fill life" id="barV"></div></div>
        <br>
        <strong>Energia</strong>
        <div class="status-row">
            Atu: <input type="number" id="ma" class="status-input" value="<?php echo $p['manaat']; ?>" oninput="updateBars()" onchange="salvar('manaat', this.value, <?php echo $id; ?>)">
            Máx: <input type="number" id="mm" class="status-input" value="<?php echo $p['manamax']; ?>" oninput="updateBars()" onchange="salvar('manamax', this.value, <?php echo $id; ?>)">
        </div>
        <div class="bar"><div class="bar-fill mana" id="barM"></div></div>
    </div>

    <div class="card">
        <h2>Atributos</h2>
        <div class="stats-grid">
            <?php 
            $atr = ['forca'=>'FOR:', 'agilidade'=>'AGI:', 'constituicao'=>'CON:', 'carisma'=>'CAR:', 'inteligencia'=>'INT:', 'sabedoria'=>'SAB:', 'feitico'=>'FEI:'];
            foreach($atr as $col => $label) {
                echo "<div class='status-box'>
                <label>$label</label>
                <input type='number' class='status-input' value='{$p[$col]}' onchange=\"salvar('$col', this.value, $id)\">
                </div>";
            }
            ?>
        </div>
    </div>

    <div class="card">
        <h2>Inventário</h2>
        <textarea onchange="salvar('inventario', this.value, <?php echo $id; ?>)" style="width: 95%; height: 150px; background: #222; color: white; border: 1px solid #444; border-radius: 5px; padding: 10px;"><?php echo $p['inventario']; ?></textarea>
    </div>

    <div class="card">
        <h2>Perícias</h2>
        <textarea onchange="salvar('pericias', this.value, <?php echo $id; ?>)" style="width: 95%; height: 100px; background: #222; color: white; border: 1px solid #444; border-radius: 5px; padding: 10px;"><?php echo $p['pericias']; ?></textarea>
    </div>

    <div class="card">
        <h2>Proficiências</h2>
        <textarea onchange="salvar('proficiencias', this.value, <?php echo $id; ?>)" style="width: 95%; height: 100px; background: #222; color: white; border: 1px solid #444; border-radius: 5px; padding: 10px;"><?php echo $p['proficiencias']; ?></textarea>
    </div>
<div class="card">
        <h2>Habilidades</h2>
        <textarea onchange="salvar('habilidades', this.value, <?php echo $id; ?>)" 
                  style="width: 95%; height: 100px; background: #222; color: white; border: 1px solid #444; border-radius: 5px; padding: 10px;"><?php echo $p['habilidades']; ?></textarea>
    </div>

    <div class="card">
        <h2>Magias</h2>
        <textarea onchange="salvar('magias', this.value, <?php echo $id; ?>)" 
                  style="width: 95%; height: 100px; background: #222; color: white; border: 1px solid #444; border-radius: 5px; padding: 10px;"><?php echo $p['magias']; ?></textarea>
    </div>

    <div class="footer">Ficha de RPG • <?php echo $p['nome']; ?></div>
</div>

<script>
function updateBars(){
    let va = document.getElementById("va").value;
    let vm = document.getElementById("vm").value;
    let ma = document.getElementById("ma").value;
    let mm = document.getElementById("mm").value;
    document.getElementById("barV").style.width = (vm > 0 ? (va/vm*100) : 0) + "%";
    document.getElementById("barM").style.width = (mm > 0 ? (ma/mm*100) : 0) + "%";
}

function salvar(coluna, valor, id) {
    let formData = new FormData();
    formData.append('coluna', coluna);
    formData.append('valor', valor);
    formData.append('id', id);

    fetch('atualizar.php', { method: 'POST', body: formData })
    .then(r => r.text())
    .then(d => console.log("Salvo: " + d));
}
function subirFoto(input, id) {
    if (input.files && input.files[0]) {
        let formData = new FormData();
        formData.append('foto', input.files[0]);
        formData.append('id', id);

        fetch('upload_foto.php', {
            method: 'POST',
            body: formData
        })
        .then(r => r.text())
        .then(nomeArquivo => {
            if (nomeArquivo !== "erro") {
                // Atualiza a imagem na tela na hora
                document.getElementById('img_personagem').src = 'uploads/' + nomeArquivo;
                alert("Imagem atualizada!");
            } else {
                alert("Erro ao subir imagem.");
            }
        });
    }
}
updateBars();
</script>
</body>
</html>