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
- Apache ou Nginx

## Instalação

1. **Clone o repositório:**
   ```sh
   git clone https://github.com/Allgommes/HOTEL-SOL-MAR
   ```
2. **Configure a base de dados**  
   Edite `config/database.php` com as credenciais do seu MySQL.

3. **Importe o script SQL abaixo no seu MySQL:**
   ```sql
   CREATE DATABASE hotel_sol_mar;
   USE hotel_sol_mar;

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
     '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', -- password: password
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
   ```

4. **Acesse via browser:**  
   Abra o projeto em seu navegador.

## Estrutura do Projeto

```
hotel-sol-mar/
├── config/
├── includes/
├── assets/
├── reservas/
└── README.md
```

## Contribuição

Pull requests são bem-vindos. Por favor, siga as boas práticas de codificação.

## Licença

MIT License

