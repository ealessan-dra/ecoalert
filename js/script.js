const descricoesCategoria = {
  "Desmatamento": "Remoção ilegal de vegetação, corte de árvores ou destruição de área verde.",
  "Queimada": "Foco de fogo, incêndio em vegetação, fumaça intensa ou uso irregular de queimadas.",
  "Poluição da água": "Despejo de produtos, esgoto, óleo ou resíduos em rios, lagos e nascentes.",
  "Descarte irregular de lixo": "Lixo, entulho, móveis, restos de obra ou resíduos descartados em local proibido.",
  "Maus-tratos a animais": "Agressão, abandono, captura ou comércio irregular de animais silvestres.",
  "Ocupação irregular": "Construção, invasão ou uso indevido de área de preservação ambiental."
};

const categoria = document.getElementById("categoria");
const descricaoCategoria = document.getElementById("descricaoCategoria");
const latitude = document.getElementById("latitude");
const longitude = document.getElementById("longitude");
const formulario = document.getElementById("formDenuncia");

categoria.addEventListener("change", function () {
  const valor = categoria.value;
  descricaoCategoria.textContent = descricoesCategoria[valor] || "Selecione uma categoria para ver a explicação.";
});

const mapa = L.map("mapa").setView([-23.5505, -46.6333], 12);

L.tileLayer("https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png", {
  attribution: "&copy; OpenStreetMap"
}).addTo(mapa);

let marcador = null;

mapa.on("click", function (evento) {
  const lat = evento.latlng.lat.toFixed(6);
  const lng = evento.latlng.lng.toFixed(6);

  latitude.value = lat;
  longitude.value = lng;

  if (marcador) {
    marcador.setLatLng(evento.latlng);
  } else {
    marcador = L.marker(evento.latlng).addTo(mapa);
  }

  marcador.bindPopup("Local da denúncia selecionado.").openPopup();
});

formulario.addEventListener("submit", function (evento) {
  if (!latitude.value || !longitude.value) {
    evento.preventDefault();
    alert("Clique no mapa para marcar o local exato da denúncia antes de enviar.");
  }
});
