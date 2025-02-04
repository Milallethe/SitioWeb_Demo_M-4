CREATE TABLE PRODUCTOS (
    CODPRODUCTO varchar(10),
    DESCRIPCION varchar(50),
    EXISTENCIA decimal(18,0),
    PRIMARY KEY (CODPRODUCTO)
);

CREATE TABLE USUARIOS (
    USUARIOID varchar(10),
    USUARIO varchar(50),
    CLAVE decimal(18,0),
    PRIMARY KEY (USUARIOID)
);

CREATE TABLE CLIENTES (
    CODCLIENTE varchar(10),
    NOMBRE varchar(50),
    APELLIDO varchar(50),
    PRIMARY KEY (CODCLIENTE)
);

CREATE TABLE COMPRAS (
    CODCOMPRA varchar(10),
    CODPROVEEDOR varchar(10),
    CODPRODUCTO varchar(10),
    FECHA date,
    CANTIDAD decimal(18,0),
    PRIMARY KEY (CODCOMPRA)
);

CREATE TABLE BITACORA (
    USUARIO varchar(50),
    FECHAHORA datetime(0),
    REGISTRO varchar(10),
    TABLA varchar(50),
    OPERACION varchar(50)
);

CREATE TABLE VENTAS (
    CODVENTA varchar(10),
    CODCLIENTE varchar(10),
    CODPRODUCTO varchar(10),
    FECHA date,
    CANTIDAD decimal(18,0),
    PRIMARY KEY (CODVENTA)
);

create table PROVEEDORES (
CODPROVEEDOR varchar(10),
NOMBRE varchar(50), 
primary key (CODPROVEEDOR)
);


-- Proveedores (Delimitados)

DELIMITER $$
CREATE PROCEDURE spBorrarProveedor(
    IN p_CODPROVEEDOR VARCHAR(10)
)
BEGIN
    DELETE FROM PROVEEDORES
    WHERE CODPROVEEDOR = p_CODPROVEEDOR;
END$$
DELIMITER ;
DELIMITER $$
CREATE PROCEDURE spModificarProveedor(
    IN p_CODPROVEEDOR VARCHAR(10),
    IN p_NOMBRE VARCHAR(50)
)
BEGIN
    UPDATE PROVEEDORES
    SET NOMBRE = p_NOMBRE
    WHERE CODPROVEEDOR = p_CODPROVEEDOR;
END$$
DELIMITER ;
DELIMITER $$
CREATE PROCEDURE spObtenerProveedor(IN p_codigoProveedor VARCHAR(10))
BEGIN
    SELECT * FROM PROVEEDORES WHERE CODPROVEEDOR = p_codigoProveedor;
END $$
DELIMITER ;

DELIMITER $$
CREATE PROCEDURE INSERT_PROV(
    IN p_CodProv VARCHAR(10),
    IN p_Nombre VARCHAR(50)
)
BEGIN
    INSERT INTO PROVEEDORES (CODPROVEEDOR, NOMBRE)
    VALUES (p_CodProv, p_Nombre);
END$$
DELIMITER ;
DESCRIBE PROVEEDORES;
DELIMITER $$
CREATE PROCEDURE spObtenerProveedor(IN p_codigoProveedor VARCHAR(10))
BEGIN
    IF p_codigoProveedor = '' THEN
        SELECT * FROM PROVEEDORES;
    ELSE
        SELECT * FROM PROVEEDORES WHERE CODPROVEEDOR = p_codigoProveedor;
    END IF;
END $$
DELIMITER ;
DROP PROCEDURE IF EXISTS spObtenerProveedor;


DELIMITER $$
CREATE PROCEDURE INSERTAR_PROD(
    IN p_CodProducto VARCHAR(10),
    IN p_Descripcion VARCHAR(50),
    IN p_Existencia DECIMAL(18, 0)
)
BEGIN
    INSERT INTO PRODUCTOS (CODPRODUCTO, DESCRIPCION, EXISTENCIA)
    VALUES (p_CodProducto, p_Descripcion, p_Existencia);
END$$
DELIMITER ;


DELIMITER $$
CREATE PROCEDURE INSERT_CLIENTE(
    IN p_CodCliente VARCHAR(10),
    IN p_Nombre VARCHAR(50),
    IN p_Apellido VARCHAR(50)
)
BEGIN
    INSERT INTO CLIENTES (CODCLIENTE, NOMBRE, APELLIDO)
    VALUES (p_CodCliente, p_Nombre, p_Apellido);
END$$
DELIMITER ;
-- proveedores
DELIMITER $$
CREATE PROCEDURE INSERT_PROV(
    IN p_CodProv VARCHAR(10),
    IN p_Nombre VARCHAR(50)
)
BEGIN
    INSERT INTO PROVEEDORES (CODPROVEEDOR, NOMBRE)
    VALUES (p_CodProv, p_Nombre);
END$$
DELIMITER ;

-- ventas
DELIMITER $$
CREATE PROCEDURE spInsertarVenta(
    IN p_CODVENTA VARCHAR(10),
    IN p_CODCLIENTE VARCHAR(10),
    IN p_CODPRODUCTO VARCHAR(10),
    IN p_FECHA DATE,
    IN p_CANTIDAD DECIMAL(18, 0)
)
BEGIN
    INSERT INTO VENTAS (CODVENTA, CODCLIENTE, CODPRODUCTO, FECHA, CANTIDAD)
    VALUES (p_CODVENTA, p_CODCLIENTE, p_CODPRODUCTO, p_FECHA, p_CANTIDAD);
END $$
DELIMITER ;

-- Modificacion de las ventas------
 DELIMITER $$
CREATE PROCEDURE spModificarVenta(
    IN p_CODVENTA VARCHAR(10),
    IN p_CODCLIENTE VARCHAR(10),
    IN p_CODPRODUCTO VARCHAR(10),
    IN p_FECHA DATE,
    IN p_CANTIDAD DECIMAL(18, 0)
)
BEGIN
    UPDATE VENTAS
    SET CODCLIENTE = p_CODCLIENTE,
        CODPRODUCTO = p_CODPRODUCTO,
        FECHA = p_FECHA,
        CANTIDAD = p_CANTIDAD
    WHERE CODVENTA = p_CODVENTA;
END $$
DELIMITER ;

-- modificar USUARIO
DELIMITER $$

CREATE PROCEDURE spModificarUsuario(
    IN p_USUARIOID INT,
    IN p_USUARIO VARCHAR(50),
    IN p_CLAVE DECIMAL(18,0)
)
BEGIN
    UPDATE USUARIOS
    SET USUARIO = p_USUARIO,
        CLAVE = p_CLAVE
    WHERE USUARIOID = p_USUARIOID;
END$$

DELIMITER ;


DELIMITER $$

CREATE PROCEDURE spBorrarUsuario(
    IN p_USUARIOID INT
)
BEGIN
    DELETE FROM USUARIOS
    WHERE USUARIOID = p_USUARIOID;
END$$

DELIMITER ;

DELIMITER $$

CREATE PROCEDURE spObtenerUsuario(
    IN p_USUARIOID INT
)
BEGIN
    SELECT *
    FROM USUARIOS
    WHERE USUARIOID = p_USUARIOID;
END$$

DELIMITER ;
-- obtener todas las ventas
DELIMITER $$

CREATE PROCEDURE spObtenerVenta()
BEGIN
    SELECT * FROM VENTAS;
END$$

DELIMITER ;


 DELIMITER $$
CREATE PROCEDURE spBorrarVenta(
    IN p_CODVENTA VARCHAR(10)
)
BEGIN
    DELETE FROM VENTAS WHERE CODVENTA = p_CODVENTA;
END $$
DELIMITER ;


DELIMITER $$
CREATE FUNCTION OBT_DES(
    f_CodProducto VARCHAR(10)
) RETURNS VARCHAR(50)
DETERMINISTIC
BEGIN
    DECLARE descripcion VARCHAR(50);
    SELECT DESCRIPCION INTO descripcion
    FROM PRODUCTOS
    WHERE CODPRODUCTO = f_CodProducto;
    RETURN descripcion;
END$$
DELIMITER ;


DELIMITER $$
CREATE FUNCTION OBT_NCLIENTE(
    f_CodVenta VARCHAR(10)
) RETURNS VARCHAR(100)
DETERMINISTIC
BEGIN
    DECLARE NCOMPLETO VARCHAR(100);
    SELECT CONCAT(CLIENTES.NOMBRE, ' ', CLIENTES.APELLIDO) INTO NCOMPLETO
    FROM CLIENTES
    JOIN VENTAS ON CLIENTES.CODCLIENTE = VENTAS.CODCLIENTE
    WHERE VENTAS.CODVENTA = f_CodVenta;
    RETURN NCOMPLETO;
END$$
DELIMITER ;


DELIMITER $$
CREATE FUNCTION OBT_NPROVEEDOR(
    f_CodCompra VARCHAR(10)
) RETURNS VARCHAR(50)
DETERMINISTIC
BEGIN
    DECLARE nproveedor VARCHAR(50);
    SELECT PROVEEDORES.NOMBRE INTO nproveedor
    FROM PROVEEDORES
    JOIN COMPRAS ON PROVEEDORES.CODPROVEEDOR = COMPRAS.CODPROVEEDOR
    WHERE COMPRAS.CODCOMPRA = f_CodCompra;
    RETURN nproveedor;
END$$
DELIMITER ;


DELIMITER $$
CREATE TRIGGER Descontar_Exis_Venta
AFTER INSERT ON VENTAS
FOR EACH ROW
BEGIN
    UPDATE PRODUCTOS
    SET EXISTENCIA = EXISTENCIA - NEW.CANTIDAD
    WHERE CODPRODUCTO = NEW.CODPRODUCTO;
END$$
DELIMITER ;


DELIMITER $$
CREATE TRIGGER Incrementar_Exis_Compra
AFTER INSERT ON COMPRAS
FOR EACH ROW
BEGIN
    UPDATE PRODUCTOS
    SET EXISTENCIA = EXISTENCIA + NEW.CANTIDAD
    WHERE CODPRODUCTO = NEW.CODPRODUCTO;
END$$
DELIMITER ;


DELIMITER $$
CREATE TRIGGER BITACORA_IMB_CLIENTE
AFTER INSERT ON CLIENTES
FOR EACH ROW
BEGIN
    INSERT INTO BITACORA (USUARIO, FECHAHORA, REGISTRO, TABLA, OPERACION)
    VALUES (USER(), NOW(), NEW.CODCLIENTE, 'CLIENTES', 'INSERT');
END$$
DELIMITER ;

DELIMITER $$
CREATE TRIGGER BITACORA_BCLIENTE
AFTER UPDATE ON CLIENTES
FOR EACH ROW
BEGIN
    INSERT INTO BITACORA (USUARIO, FECHAHORA, REGISTRO, TABLA, OPERACION)
    VALUES (USER(), NOW(), NEW.CODCLIENTE, 'CLIENTES', 'UPDATE');
END$$
DELIMITER ;
DELIMITER $$

CREATE PROCEDURE INSERT_USUARIO (
    IN p_USUARIOID INT,
    IN p_USUARIO VARCHAR(50),
    IN p_CLAVE VARCHAR(255)
)
BEGIN
    INSERT INTO Usuarios (USUARIOID, USUARIO, CLAVE)
    VALUES (p_USUARIOID, p_USUARIO, p_CLAVE);
END$$

DELIMITER ;

DELIMITER $$

CREATE PROCEDURE spModificarUsuario (
    IN p_USUARIOID INT,
    IN p_USUARIO VARCHAR(50),
    IN p_CLAVE VARCHAR(255) 
)
BEGIN
    UPDATE USUARIOS
    SET USUARIO = p_USUARIO,
        CLAVE = p_CLAVE
    WHERE USUARIOID = p_USUARIOID;
END$$

DELIMITER ;



DELIMITER $$

CREATE PROCEDURE spObtenerUsuario (
    IN p_USUARIOID INT
)
BEGIN
    SELECT *
    FROM USUARIOS
    WHERE USUARIOID = p_USUARIOID;
END$$

DELIMITER ;




DELIMITER $$

CREATE PROCEDURE spBorrarUsuario (
    IN p_USUARIOID INT
)
BEGIN
    DELETE FROM USUARIOS
    WHERE USUARIOID = p_USUARIOID;
END$$


DELIMITER ;




DELIMITER $$

CREATE PROCEDURE spModificarUsuario (
    IN p_USUARIOID INT,
    IN p_USUARIO VARCHAR(50),
    IN p_CLAVE VARCHAR(255)  
)
BEGIN
    UPDATE USUARIOS
    SET USUARIO = p_USUARIO,
        CLAVE = p_CLAVE
    WHERE USUARIOID = p_USUARIOID;
END$$

DELIMITER ;




DELIMITER $$

CREATE PROCEDURE spObtenerVenta(IN p_CodigoVenta VARCHAR(10))
BEGIN
    IF p_CodigoVenta = '' THEN
        SELECT * FROM VENTAS;
    ELSE
        SELECT * FROM VENTAS WHERE CODVENTA = p_CodigoVenta;
    END IF;
END $$

DELIMITER ;

DELIMITER $$

CREATE PROCEDURE spObtenerCompra(IN p_CodigoCompra VARCHAR(10))
BEGIN
    IF p_CodigoCompra = '' THEN
        SELECT 
            C.CODCOMPRA,
            C.FECHA,
            P.NOMBRE AS CODPROVEEDOR, 
            PR.DESCRIPCION AS CODPRODUCTO, -- Adjunta la descripcion del producto
            C.CANTIDAD
        FROM COMPRAS C
        JOIN PROVEEDORES P ON C.CODPROVEEDOR = P.CODPROVEEDOR  -- Unimos con la tabla Proveedor
        JOIN PRODUCTOS PR ON C.CODPRODUCTO = PR.CODPRODUCTO -- Une con la tabla Productos...
        ORDER BY C.FECHA DESC;
    ELSE
        SELECT 
            C.CODCOMPRA,
            C.FECHA,
            P.NOMBRE AS PROVEEDORES, 
            PR.DESCRIPCION AS CODPRODUCTO,
            C.CANTIDAD
        FROM COMPRAS C
        JOIN PROVEEDORES P ON C.CODPROVEEDOR = P.CODPROVEEDOR  
        JOIN PRODUCTOS PR ON C.CODPRODUCTO = PR.CODPRODUCTO -- Volvemos a repetir las mismas lineas en Else para la base
        WHERE C.CODCOMPRA = p_CodigoCompra;
    END IF;
END $$

DELIMITER ;


DELIMITER $$

CREATE PROCEDURE spObtenerVenta(IN p_CodigoVenta VARCHAR(10))
BEGIN
    IF p_CodigoVenta = '' THEN
        SELECT 
            V.CODVENTA,
            V.FECHA,
            C.NOMBRE AS CLIENTE,  
            C.APELLIDO AS APELLIDO, 
            P.DESCRIPCION AS PRODUCTO, 
            V.CANTIDAD
        FROM VENTAS V
        JOIN CLIENTES C ON V.CODCLIENTE = C.CODCLIENTE  
        JOIN PRODUCTOS P ON V.CODPRODUCTO = P.CODPRODUCTO 
        ORDER BY V.FECHA DESC;
    ELSE
        SELECT 
            V.CODVENTA,
            V.FECHA,
            C.NOMBRE AS CLIENTE,  
            C.APELLIDO AS APELLIDO, 
            P.DESCRIPCION AS PRODUCTO,
            V.CANTIDAD
        FROM VENTAS V
        JOIN CLIENTES C ON V.CODCLIENTE = C.CODCLIENTE  
        JOIN PRODUCTOS P ON V.CODPRODUCTO = P.CODPRODUCTO 
        WHERE V.CODVENTA = p_CodigoVenta;
    END IF;
END $$

DELIMITER ;


-- Trigger para eliminar directamente en bitacoras para cliente...
DELIMITER $$
CREATE TRIGGER BITACORA_DCLIENTE
AFTER DELETE ON CLIENTES
FOR EACH ROW
BEGIN
    INSERT INTO BITACORA (USUARIO, FECHAHORA, REGISTRO, TABLA, OPERACION)
    VALUES (USER(), NOW(), OLD.CODCLIENTE, 'CLIENTES', 'DELETE');
END$$
DELIMITER ;
-- Relaciones entre tablas
ALTER TABLE COMPRAS ADD CONSTRAINT FK_PROV_COMPRAS FOREIGN KEY (CODPROVEEDOR) 
REFERENCES PROVEEDORES (CODPROVEEDOR);

ALTER TABLE VENTAS ADD CONSTRAINT FK_CLIENTES_VENTAS FOREIGN KEY (CODCLIENTE) 
REFERENCES CLIENTES (CODCLIENTE);

ALTER TABLE COMPRAS ADD CONSTRAINT FK_PROD_COMPRAS FOREIGN KEY (CODPRODUCTO) 
REFERENCES PRODUCTOS (CODPRODUCTO);

ALTER TABLE VENTAS ADD CONSTRAINT FK_PROD_VENTAS FOREIGN KEY (CODPRODUCTO) 
REFERENCES PRODUCTOS (CODPRODUCTO);
