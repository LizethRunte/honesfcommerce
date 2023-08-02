function adicionarItem(itemId) {
    // Obtenha o valor atual da quantidade do item
    var quantidadeInput = document.getElementById("quantidade-" + itemId);
    var quantidadeAtual = parseInt(quantidadeInput.value);

    // Incremente a quantidade do item
    quantidadeInput.value = quantidadeAtual + 1;

    // Atualize o total gasto
    atualizarTotal();
    salvarCarrinho(itemId, quantidadeInput.value);
}

function removerItem(itemId) {
    // Obtenha o valor atual da quantidade do item
    var quantidadeInput = document.getElementById("quantidade-" + itemId);
    var quantidadeAtual = parseInt(quantidadeInput.value);

    // Decrementar a quantidade do item, se for maior que 0
    if (quantidadeAtual > 0) {
        quantidadeInput.value = quantidadeAtual - 1;
    }

    // Atualize o total gasto
    atualizarTotal();
    salvarCarrinho(itemId, quantidadeInput.value);
}

function atualizarTotal() {
    // Obtenha todos os inputs de quantidade de itens
    var quantidadeInputs = document.querySelectorAll("[id^='quantidade-']");

    // Calcule o total gasto com base nas quantidades e preços dos itens
    var total = 0;
    for (var i = 0; i < quantidadeInputs.length; i++) {
        var input = quantidadeInputs[i];
        var itemId = input.id.split("-")[1];
        var precoItem = parseFloat(document.querySelector("tr[data-item-id='" + itemId + "'] .preco").textContent);
        var quantidadeItem = parseInt(input.value);
        total += precoItem * quantidadeItem;
    }

   // Atualize o total gasto na página
   document.getElementById("total").textContent = total.toFixed(2);
}

function salvarCarrinho(itemId, quantidade) {
   var usuario = document.getElementById("usuario").value;
   var xhr = new XMLHttpRequest();
   xhr.open("POST", "salvar_carrinho.php");
   xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
   xhr.send("usuario=" + usuario + "&item=" + itemId + "&quantidade=" + quantidade);
}

function atualizarCarrinho() {
   var usuario = document.getElementById("usuario").value;
   var xhr = new XMLHttpRequest();
   xhr.open("POST", "atualizar_carrinho.php");
   xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
   xhr.onload = function() {
       if (xhr.status === 200) {
           eval(xhr.responseText);
           atualizarTotal();
       }
   };
   xhr.send("usuario=" + usuario);
}

window.addEventListener("load", atualizarCarrinho);
