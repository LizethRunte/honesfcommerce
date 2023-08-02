<?php
// Conexão com o banco de dados
$db = mysqli_connect("localhost:3306", "lotzco01_Admloja", "Kaskudu1", "lotzco01_Loja");

// Verifique se a conexão foi bem-sucedida
if (!$db) {
    die("Conexão falhou: " . mysqli_connect_error());
}

// Obtenha os dados enviados pela requisição AJAX
$usuario = $_POST['usuario'];

// Consulta SQL para selecionar os dados da tabela do carrinho
$query = "SELECT * FROM carrinho WHERE usuario = '$usuario'";
$result = mysqli_query($db, $query);
if (!$result) {
    die("Consulta falhou: " . mysqli_error($db));
}
$carrinho = array();
while ($row = mysqli_fetch_assoc($result)) {
    $carrinho[] = $row;
}

// Gere o código JavaScript para atualizar a interface do usuário com os dados recuperados
foreach ($carrinho as $item_carrinho) {
    $item_id = $item_carrinho['item'];
    $quantidade = $item_carrinho['quantidade'];
    echo "document.getElementById('quantidade-$item_id').value = $quantidade;";
}
?>
