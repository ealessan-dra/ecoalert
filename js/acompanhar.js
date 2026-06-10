const formBuscaDemo = document.getElementById("formBuscaDemo");
const mensagemBusca = document.getElementById("mensagemBusca");

formBuscaDemo.addEventListener("submit", function (evento) {
  evento.preventDefault();
  mensagemBusca.textContent = "Esta é uma busca demonstrativa. A consulta real das denúncias acontece em acompanhar.php com PHP e MySQL.";
});
