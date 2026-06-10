const formContato = document.getElementById("formContato");
const mensagemContato = document.getElementById("mensagemContato");

formContato.addEventListener("submit", function (evento) {
  evento.preventDefault();
  mensagemContato.textContent = "Mensagem registrada para demonstração. Em uma próxima etapa, ela pode ser enviada por PHP.";
  formContato.reset();
});
