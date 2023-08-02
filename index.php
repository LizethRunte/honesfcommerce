<?php
// Conexão com o banco de dados
$db = mysqli_connect("localhost:3306", "lotzco01_Admloja", "Kaskudu1", "lotzco01_Loja");

// Verifique se a conexão foi bem-sucedida
if (!$db) {
    die("Conexão falhou: " . mysqli_connect_error());
}

// Consulta para obter todos os nomes de usuários
$query = "SELECT nome FROM usuarios";
$result = mysqli_query($db, $query);

// Verifique se a consulta foi bem-sucedida
if (!$result) {
    die("Consulta falhou: " . mysqli_error($db));
}

// Armazene os nomes dos usuários em um array
$usuarios = array();
while ($row = mysqli_fetch_assoc($result)) {
    $usuarios[] = $row['nome'];
}

// Consulta para obter todos os itens da loja
$query = "SELECT * FROM itens";
$result = mysqli_query($db, $query);

// Verifique se a consulta foi bem-sucedida
if (!$result) {
    die("Consulta falhou: " . mysqli_error($db));
}

// Armazene os itens da loja em um array
$itens = array();
while ($row = mysqli_fetch_assoc($result)) {
    $itens[] = $row;
}

if (isset($_POST['usuario'])) {
    $usuario_selecionado = $_POST['usuario'];
} else {
    $usuario_selecionado = $usuarios[0];
}

$query = "SELECT * FROM carrinho WHERE usuario = '$usuario_selecionado'";
$result = mysqli_query($db, $query);
if (!$result) {
    die("Consulta falhou: " . mysqli_error($db));
}
$carrinho = array();
while ($row = mysqli_fetch_assoc($result)) {
    $carrinho[] = $row;
}
// Query to get the leaderboard data
$query = "SELECT itens.nome AS item, carrinho.usuario AS usuario, carrinho.quantidade AS quantidade FROM carrinho JOIN itens ON carrinho.item = itens.id WHERE carrinho.quantidade = (SELECT MAX(c2.quantidade) FROM carrinho c2 WHERE c2.item = carrinho.item) GROUP BY carrinho.item ORDER BY carrinho.item, quantidade DESC";
$result = mysqli_query($db, $query);

// Check if the query was successful
if (!$result) {
    die("Query failed: " . mysqli_error($db));
}

// Store the leaderboard data in an array
$leaderboard = array();
while ($row = mysqli_fetch_assoc($result)) {
    $leaderboard[] = $row;
}


?>

<!DOCTYPE html>
<html>
<head>
    <title>Honest Commerce</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <h1>Honest Commerce</h1>
    <h2>Selecione seu nome:</h2>
    <select id="usuario" onchange="atualizarCarrinho()">
        <?php foreach ($usuarios as $usuario): ?>
            <option value="<?php echo $usuario; ?>" <?php if ($usuario == $usuario_selecionado) echo 'selected'; ?>><?php echo $usuario; ?></option>
        <?php endforeach; ?>
    </select>
    <h2>Itens disponíveis:</h2>
    <table>
        <tr>
            <th>Nome</th>
            <th>Preço</th>
            <th>Quantidade</th>
            <th>Ações</th>
        </tr>
        <?php foreach ($itens as $item): ?>
            <tr data-item-id="<?php echo $item['id']; ?>">
                <td><?php echo $item['nome']; ?></td>
                <td class="preco"><?php echo $item['preco']; ?></td>
                <td><input type="number" id="quantidade-<?php echo $item['id']; ?>" value="0"></td>
                <td><button onclick="adicionarItem(<?php echo $item['id']; ?>)">+</button> <button onclick="removerItem(<?php echo $item['id']; ?>)">-</button></td>
            </tr>
        <?php endforeach; ?>
    </table>

    <?php
    foreach ($carrinho as $item_carrinho) {
        $item_id = $item_carrinho['item'];
        $quantidade = $item_carrinho['quantidade'];
        echo "<script>document.getElementById('quantidade-$item_id').value = $quantidade;</script>";
    }
    ?>

    <h2>Total gasto: R$<span id="total">0.00</span></h2>

    <h2>Leaderboard:</h2>
    <table>
        <tr>
            <th>Item</th>
            <th>Usuário</th>
            <th>Quantidade</th>
        </tr>
        <?php foreach ($leaderboard as $row): ?>
            <tr>
                <td><?php echo $row['item']; ?></td>
                <td><?php echo $row['usuario']; ?></td>
                <td><?php echo $row['quantidade']; ?></td>
            </tr>
        <?php endforeach; ?>
    </table>

    <!-- Inclua o arquivo script.js aqui -->
    <script src="script.js"></script>
</body>
</html>
