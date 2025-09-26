# Hotel Sol&Mar - Sistema de Gestão de Reservas

Sistema de gestão de reservas para o Hotel Sol&Mar desenvolvido em PHP com MySQL e Bootstrap.

## Funcionalidades

- ✅ Autenticação de funcionários
- ✅ Gestão de reservas (CRUD)
- ✅ Interface responsiva com Bootstrap
- ✅ Sessões seguras
- ✅ Validação de dados

## Requisitos

- PHP 7.4+
- MySQL 5.7+
- Apache/Nginx

## MYSQL

create database hotel_sol_mar;
use hotel_sol_mar;

-- Criar tabela funcionarios
CREATE TABLE funcionarios (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(100) NOT NULL,
    email VARCHAR(100) UNIQUE NOT NULL,
    senha VARCHAR(255) NOT NULL,
    data_cadastro DATETIME NOT NULL,
    ultimo_login DATETIME NULL,
    tentativas_login INT DEFAULT 0,
    ativo TINYINT DEFAULT 1
);

-- Inserir funcionário admin padrão
INSERT INTO funcionarios (nome, email, senha, data_cadastro, ativo) 
VALUES (
    'Administrador', 
    'admin@hotel.com', 
    '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi',  -- password: password
    NOW(), 
    1
);

-- Criar tabela reservas com constraint correta
CREATE TABLE reservas (
    id INT AUTO_INCREMENT PRIMARY KEY,
    cliente VARCHAR(100) NOT NULL,
    quarto INT NOT NULL,
    checkin DATE NOT NULL,
    checkout DATE NOT NULL,
    estado ENUM('Ativa', 'Cancelada') DEFAULT 'Ativa',
    funcionario_id INT,
    data_criacao TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (funcionario_id) REFERENCES funcionarios(id) ON DELETE SET NULL
);


## Instalação

1. Clonar o repositório
2. Configurar base de dados em `config/database.php`
3. Importar o script SQL
4. Aceder via browser

## Estrutura

hotel-sol-mar/
├── config/
├── includes/
├── assets/
├── reservas/
└── README.md

## Contribuição

Pull requests são bem-vindos. Por favor, siga as boas práticas de codificação.

## Licença

MIT License