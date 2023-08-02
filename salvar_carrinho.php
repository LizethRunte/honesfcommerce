<?php
// Conexão com o banco de dados
$db = mysqli_connect("localhost:3306", "lotzco01_Admloja", "Kaskudu1", "lotzco01_Loja");

// Verifique se a conexão foi bem-sucedida
if (!$db) {
    die("Conexão falhou: " . mysqli_connect_error());
}

// Obtenha os dados enviados pela requisição AJAX
$usuario = $_POST['usuario'];
$item = $_POST['item'];
$quantidade = $_POST['quantidade'];

// Consulta SQL para inserir ou atualizar os dados
$query = "INSERT INTO carrinho (usuario, item, quantidade) VALUES ('$usuario', $item, $quantidade) ON DUPLICATE KEY UPDATE quantidade=$quantidade";
$result = mysqli_query($db, $query);

// Verifique se a consulta foi bem-sucedida
if (!$result) {
    die("Consulta falhou: " . mysqli_error($db));
}
?>
