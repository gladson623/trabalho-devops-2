CREATE TABLE IF NOT EXISTS produtos (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  nome VARCHAR(120) NOT NULL,
  descricao TEXT NULL,
  preco DECIMAL(10,2) NOT NULL,
  estoque INT UNSIGNED NOT NULL DEFAULT 0,
  criado_em TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  atualizado_em TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) CHARACTER SET utf8mb4;

INSERT INTO produtos (nome, descricao, preco, estoque) VALUES
('Teclado mecânico', 'Switch azul, layout ABNT2', 249.90, 15),
('Mouse sem fio', 'Sensor óptico 1600 DPI', 89.50, 40),
('Monitor 24"', 'Full HD, painel IPS', 799.00, 8);
