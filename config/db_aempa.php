<?php
$sql_code ="-- Criar o banco de dados
CREATE DATABASE IF NOT EXISTS db_aempa;
USE db_aempa;

-- Tabela de usuários (login)

CREATE TABLE usuario (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(100) NOT NULL,
    email VARCHAR(150) NOT NULL UNIQUE,
    senha VARCHAR(255) NOT NULL,
    data_criacao TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Tabela de entradas financeiras

CREATE TABLE IF NOT EXISTS entrada (
  id INT AUTO_INCREMENT PRIMARY KEY,
  valor DECIMAL(10,2) NOT NULL,
  nome_completo VARCHAR(100) NOT NULL,
  dia DATE NOT NULL
);

-- Tabela de associados

CREATE TABLE IF NOT EXISTS associado (
  id INT AUTO_INCREMENT PRIMARY KEY,
  cpf VARCHAR(14) NOT NULL UNIQUE,
  nome_completo VARCHAR(100) NOT NULL,
  data_nascimento DATE NOT NULL,
  endereco VARCHAR(150) NOT NULL,
  bairro VARCHAR(80) NOT NULL,
  telefone VARCHAR(20) NOT NULL,
  email VARCHAR(100) NOT NULL,
  data_criacao TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Tabela de saídas financeiras

CREATE TABLE IF NOT EXISTS saida (
  id INT AUTO_INCREMENT PRIMARY KEY,
  valor DECIMAL(10,2) NOT NULL,
  especificacao VARCHAR(255) NOT NULL,
  data DATE NOT NULL
);

-- Verificação de associado ativo
ALTER TABLE associado ADD COLUMN ativo CHAR(1) DEFAULT 'S';

-- Validação de nível de acesso
ALTER TABLE usuario ADD COLUMN nivel_acesso INT DEFAULT 1;

-- Atualização para atualizacao.php
ALTER TABLE associado 
MODIFY COLUMN nome_completo VARCHAR(100) NULL,
MODIFY COLUMN data_nascimento DATE NULL;

";
?>