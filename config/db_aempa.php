<?php
$sql_code ="

-- Criar banco de dados 
CREATE DATABASE IF NOT EXISTS db_aempa
  DEFAULT CHARACTER SET utf8mb4
  DEFAULT COLLATE utf8mb4_unicode_ci;
USE db_aempa;

-- Tabela de usuários (login) - já com nivel_acesso
CREATE TABLE IF NOT EXISTS usuario (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(100) NOT NULL,
    email VARCHAR(150) NOT NULL UNIQUE,
    senha VARCHAR(255) NOT NULL,
    nivel_acesso INT NOT NULL DEFAULT 1,
    data_criacao TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Tabela de entradas financeiras
CREATE TABLE IF NOT EXISTS entrada (
  id INT AUTO_INCREMENT PRIMARY KEY,
  valor DECIMAL(10,2) NOT NULL,
  nome_completo VARCHAR(100) NOT NULL,
  usuario_email VARCHAR(150) NOT NULL,
  dia DATE NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Tabela de associados
CREATE TABLE IF NOT EXISTS associado (
  id INT AUTO_INCREMENT PRIMARY KEY,
  cpf VARCHAR(14) NOT NULL UNIQUE,
  nome_completo VARCHAR(100),
  data_nascimento DATE,
  endereco VARCHAR(150),
  bairro VARCHAR(80),
  telefone VARCHAR(20),
  email VARCHAR(100),
  ativo CHAR(1) NOT NULL DEFAULT 'S',
  data_criacao TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Tabela de saídas financeiras
CREATE TABLE IF NOT EXISTS saida (
  id INT AUTO_INCREMENT PRIMARY KEY,
  valor DECIMAL(10,2) NOT NULL,
  especificacao VARCHAR(255) NOT NULL,
  usuario_email VARCHAR(150) NOT NULL,
  dia DATE NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;


ALTER TABLE entrada ADD COLUMN usuario_nome VARCHAR(100);
ALTER TABLE saida ADD COLUMN usuario_nome VARCHAR(100);

" ;
?>