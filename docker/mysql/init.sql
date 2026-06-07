CREATE DATABASE IF NOT EXISTS crud_db;
USE crud_db;

CREATE TABLE IF NOT EXISTS productos (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(100) NOT NULL,
    descripcion TEXT,
    precio DECIMAL(10,2) NOT NULL,
    stock INT DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

INSERT INTO productos (nombre, descripcion, precio, stock) VALUES
('Laptop Lenovo', 'Portátil 15" Intel i5 8GB RAM', 599.99, 10),
('Ratón inalámbrico', 'Ratón ergonómico 2.4GHz', 19.99, 50),
('Teclado mecánico', 'Teclado gaming RGB switches azules', 79.99, 25),
('Monitor 24"', 'Monitor Full HD IPS 75Hz', 189.99, 15),
('Auriculares USB', 'Auriculares con micrófono para PC', 34.99, 30);
