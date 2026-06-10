CREATE DATABASE IF NOT EXISTS ecoalert
  DEFAULT CHARACTER SET utf8mb4
  DEFAULT COLLATE utf8mb4_general_ci;

USE ecoalert;

CREATE TABLE IF NOT EXISTS usuarios (
  id INT AUTO_INCREMENT PRIMARY KEY,
  email VARCHAR(160) NOT NULL UNIQUE,
  senha VARCHAR(255) NOT NULL,
  tipo VARCHAR(20) NOT NULL DEFAULT 'civil',
  nome VARCHAR(120),
  criado_em TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE IF NOT EXISTS denuncias (
  id INT AUTO_INCREMENT PRIMARY KEY,
  nome VARCHAR(120) NOT NULL,
  email VARCHAR(160) NOT NULL,
  categoria VARCHAR(80) NOT NULL,
  descricao TEXT NOT NULL,
  endereco VARCHAR(255) NOT NULL,
  latitude VARCHAR(30) NOT NULL,
  longitude VARCHAR(30) NOT NULL,
  status_denuncia VARCHAR(40) NOT NULL DEFAULT 'Recebida',
  criado_em TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Inserir usuário administrador padrão
INSERT INTO usuarios (email, senha, tipo, nome) VALUES ('admin@ecoalert.com', '$2y$10$N9qo8uLOickgx2ZMRZoMyeIjZAgcg7b3XeKeUxWdeS86E36P4/KLG', 'admin', 'Administrador')
ON DUPLICATE KEY UPDATE email=email;
