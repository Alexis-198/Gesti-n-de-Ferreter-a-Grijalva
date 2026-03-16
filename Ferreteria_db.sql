DROP DATABASE IF EXISTS Ferreteria_db;

CREATE DATABASE Ferreteria_db;
USE Ferreteria_db;

-- CLIENTE
CREATE TABLE Cliente (
    id_cliente INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(100) NOT NULL,
    direccion VARCHAR(100),
    telefono VARCHAR(20)
);

-- MATERIAL
CREATE TABLE Material (
    id_material INT AUTO_INCREMENT PRIMARY KEY,
    nom_producto VARCHAR(100) NOT NULL UNIQUE,
    precio DECIMAL(10,2),
    cantidad INT,
    descripcion VARCHAR(100),
    imagen VARCHAR(255) UNIQUE
);

-- PEDIDO
CREATE TABLE Pedido (
    id_pedido INT AUTO_INCREMENT PRIMARY KEY,
    fecha_pedido DATE,
    modalidad_pedido VARCHAR(255),
    fecha_entrega DATE,
    direccion VARCHAR(255),
    id_cliente INT,
    
    FOREIGN KEY (id_cliente) REFERENCES Cliente(id_cliente)
);

-- DETALLE PEDIDO
CREATE TABLE Detalle_pedido (
    id_detalle_pedido INT AUTO_INCREMENT PRIMARY KEY,
    precio_unitario DECIMAL(10,2),
    cantidad INT,
    id_pedido INT,
    id_material INT,

    FOREIGN KEY (id_pedido) REFERENCES Pedido(id_pedido),
    FOREIGN KEY (id_material) REFERENCES Material(id_material)
);

-- VENTA_FACTURA
CREATE TABLE Venta_Factura (
    id_venta_factura INT AUTO_INCREMENT PRIMARY KEY,
    fecha DATE,
    total DECIMAL(10,2),
    id_pedido INT,

    FOREIGN KEY (id_pedido) REFERENCES Pedido(id_pedido)
);

-- TRANSPORTE
CREATE TABLE Transporte (
    id_transporte INT AUTO_INCREMENT PRIMARY KEY,
    tipo_transporte VARCHAR(50),
    estado VARCHAR(50),
    id_pedido INT
);

-- TRABAJADOR
CREATE TABLE Trabajador (
    id_trabajador INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(100),
    puesto VARCHAR(100),
    horario VARCHAR(50),
    id_pedido INT,

    FOREIGN KEY (id_pedido) REFERENCES Pedido(id_pedido)
);

-- DETALLE TRANSPORTE
CREATE TABLE Detalle_transporte (
    id_detalle_transporte INT AUTO_INCREMENT PRIMARY KEY,
    precio_envio DECIMAL(10,2),
    distancia VARCHAR(100),
    horario VARCHAR(50),
    id_pedido INT,
    id_transporte INT,

    FOREIGN KEY (id_pedido) REFERENCES Pedido(id_pedido),
    FOREIGN KEY (id_transporte) REFERENCES Transporte(id_transporte)
);

--------------------------------------------------
-- CLIENTES
--------------------------------------------------

INSERT INTO Cliente (nombre, direccion, telefono) VALUES
('Juan Perez', 'Av. Principal 123', '555-1001'),
('Maria Fernanda', 'Col. San Jose', '555-2002'),
('Pedro Gomez', 'Barrio El Centro', '555-3003'),
('Carlos Mendoza', 'Colonia Escalon', '555-4004'),
('Laura Gutierrez', 'San Marcos', '555-5005'),
('Daniel Flores', 'Apopa', '555-6006'),
('Sandra Lopez', 'Mejicanos', '555-7007'),
('Fernando Castillo', 'Santa Tecla', '555-8008'),
('Roberto Hernandez', 'Soyapango', '555-9009'),
('Patricia Rivera', 'Ilopango', '555-1010');

--------------------------------------------------
-- MATERIALES
--------------------------------------------------

INSERT INTO Material (nom_producto, precio, cantidad, descripcion, imagen) VALUES
('Martillo', 15.99, 50, 'Martillo de acero reforzado', 'martillo.jpg'),
('Destornillador', 8.50, 100, 'Destornillador plano', 'destornillador.jpg'),
('Taladro', 120.00, 20, 'Taladro electrico profesional', 'taladro.jpg'),
('Cinta metrica', 5.75, 80, 'Cinta de medir 5m', 'cinta.jpg'),
('Clavos', 3.50, 500, 'Caja de clavos galvanizados', 'clavos.jpg'),
('Serrucho', 18.90, 40, 'Serrucho para madera', 'serrucho.jpg'),
('Alicate', 12.00, 60, 'Alicate de presion', 'alicate.jpg'),
('Llave inglesa', 14.25, 35, 'Llave ajustable', 'llave.jpg'),
('Brocha', 4.50, 120, 'Brocha para pintura', 'brocha.jpg'),
('Nivel', 9.99, 25, 'Nivel de burbuja', 'nivel.jpg');

--------------------------------------------------
-- PEDIDOS
--------------------------------------------------

INSERT INTO Pedido (fecha_pedido, modalidad_pedido, fecha_entrega, direccion, id_cliente) VALUES
('2024-03-01', 'Entrega a domicilio', '2024-03-03', 'Av. Principal 123', 1),
('2024-03-02', 'Retiro en tienda', '2024-03-02', 'Ferreteria Central', 2),
('2024-03-03', 'Entrega a domicilio', '2024-03-05', 'Barrio El Centro', 3),
('2024-03-04', 'Entrega a domicilio', '2024-03-06', 'Colonia Escalon', 4),
('2024-03-05', 'Retiro en tienda', '2024-03-05', 'Sucursal San Marcos', 5),
('2024-03-06', 'Entrega a domicilio', '2024-03-08', 'Apopa', 6),
('2024-03-07', 'Entrega a domicilio', '2024-03-09', 'Mejicanos', 7),
('2024-03-08', 'Retiro en tienda', '2024-03-08', 'Sucursal Santa Tecla', 8);

--------------------------------------------------
-- DETALLE PEDIDO
--------------------------------------------------

INSERT INTO Detalle_pedido (precio_unitario, cantidad, id_pedido, id_material) VALUES
(15.99, 2, 1, 1),
(8.50, 3, 1, 2),
(120.00, 1, 2, 3),
(5.75, 4, 3, 4),
(3.50, 10, 3, 5),
(18.90, 1, 4, 6),
(12.00, 2, 4, 7),
(14.25, 1, 5, 8),
(4.50, 6, 6, 9),
(9.99, 2, 6, 10),
(15.99, 1, 7, 1),
(3.50, 20, 7, 5),
(8.50, 5, 8, 2);

--------------------------------------------------
-- VENTA FACTURA
--------------------------------------------------

INSERT INTO Venta_Factura (fecha, total, id_pedido) VALUES
('2024-03-03', 57.48, 1),
('2024-03-02', 120.00, 2),
('2024-03-05', 37.50, 3),
('2024-03-06', 42.90, 4),
('2024-03-05', 14.25, 5),
('2024-03-08', 39.98, 6),
('2024-03-09', 85.99, 7),
('2024-03-08', 42.50, 8);

--------------------------------------------------
-- TRANSPORTE
--------------------------------------------------

INSERT INTO Transporte (tipo_transporte, estado, id_pedido) VALUES
('Camion', 'Disponible', 1),
('Moto', 'No disponible', 3),
('Pickup', 'Disponible', 4),
('Moto', 'Disponible', 6),
('Camion', 'Disponible', 7);

--------------------------------------------------
-- TRABAJADORES
--------------------------------------------------

INSERT INTO Trabajador (nombre, puesto, horario, id_pedido) VALUES
('Pedro Ramirez', 'Vendedor', 'Mañana', 1),
('Luis Martinez', 'Cajero', 'Tarde', 2),
('Ana Gomez', 'Encargada de pedidos', 'Mañana', 3),
('Jose Hernandez', 'Bodeguero', 'Tarde', 4),
('Marta Castillo', 'Administradora', 'Mañana', 5),
('Ricardo Diaz', 'Repartidor', 'Tarde', 6),
('Carlos Lopez', 'Repartidor', 'Mañana', 7),
('Sofia Torres', 'Vendedora', 'Tarde', 8);

--------------------------------------------------
-- DETALLE TRANSPORTE
--------------------------------------------------

INSERT INTO Detalle_transporte (precio_envio, distancia, horario, id_pedido, id_transporte) VALUES
(5.00, '10 km', '3:00 PM', 1, 1),
(4.50, '8 km', '5:00 PM', 3, 2),
(6.00, '12 km', '2:00 PM', 4, 3),
(3.50, '6 km', '6:00 PM', 6, 4),
(7.00, '15 km', '4:00 PM', 7, 5);

--------------------------------------------------
-- CONSULTAS
--------------------------------------------------

SELECT * FROM Cliente;
SELECT * FROM Material;
SELECT * FROM Pedido;
SELECT * FROM Detalle_pedido;
SELECT * FROM Venta_Factura;
SELECT * FROM Transporte;
SELECT * FROM Trabajador;
SELECT * FROM Detalle_transporte;