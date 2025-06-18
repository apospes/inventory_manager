
DROP DATABASE IF EXISTS diaxirisi;
CREATE DATABASE diaxirisi CHARACTER SET utf8mb4;
USE diaxirisi;
SET NAMES 'utf8mb4' COLLATE 'utf8mb4_unicode_ci';


DROP TABLE IF EXISTS katigoria_prod;
CREATE TABLE katigoria_prod (
	id_katigoria_prod	INTEGER 		NOT NULL UNIQUE	AUTO_INCREMENT,
	katigoria_prod_name  	VARCHAR(200)	NOT NULL,
	CONSTRAINT katigoria_prod_pk PRIMARY KEY (id_katigoria_prod)
)ENGINE=InnoDB;
	
DROP TABLE IF EXISTS rolos;
CREATE TABLE rolos (
	id_rolos		  	INTEGER 		NOT NULL UNIQUE,
	rolos_name  		VARCHAR(200) 	NOT NULL,
	CONSTRAINT rolos_pk PRIMARY KEY (id_rolos)
)ENGINE=InnoDB;
				
DROP TABLE IF EXISTS monada;
CREATE TABLE monada (
	id_monada			INTEGER 		NOT NULL UNIQUE AUTO_INCREMENT,
	monada_name  		VARCHAR(200) 	NOT NULL UNIQUE,
	CONSTRAINT monada_pk PRIMARY KEY (id_monada)
)ENGINE=InnoDB;

DROP TABLE IF EXISTS oplo;
CREATE TABLE oplo (
	id_oplo			INTEGER			NOT NULL UNIQUE AUTO_INCREMENT,
	oplo_name		VARCHAR(50)		NOT NULL UNIQUE,
	CONSTRAINT oplo_pk PRIMARY KEY (id_oplo)
)ENGINE=InnoDB;

DROP TABLE IF EXISTS vathmos;
CREATE TABLE vathmos (
	id_vathmos			INTEGER			NOT NULL UNIQUE AUTO_INCREMENT,
	vathmos_name		VARCHAR(50)		NOT NULL UNIQUE,
	CONSTRAINT vathmos_pk PRIMARY KEY (id_vathmos)
)ENGINE=InnoDB;
	
DROP TABLE IF EXISTS tmima;
CREATE TABLE tmima (
	id_tmima 			INTEGER 		NOT NULL UNIQUE AUTO_INCREMENT,
	tmima_name  		VARCHAR(200) 	NOT NULL,
	id_monada			INTEGER 		NOT NULL,
	CONSTRAINT tmima_pk PRIMARY KEY (id_tmima),
	CONSTRAINT tmima_fk_monada FOREIGN KEY (id_monada) REFERENCES monada(id_monada) ON UPDATE CASCADE ON DELETE RESTRICT
)ENGINE=InnoDB;
	
DROP TABLE IF EXISTS kataskeuastis;
CREATE TABLE kataskeuastis (
	id_kataskeuastis	INTEGER 		NOT NULL UNIQUE AUTO_INCREMENT,
	kataskeuastis_name  VARCHAR(200) 	NOT NULL,
	CONSTRAINT kataskeuastis_pk PRIMARY KEY (id_kataskeuastis)
)ENGINE=InnoDB;
	
DROP TABLE IF EXISTS diktyo;
CREATE TABLE diktyo (
	id_diktyo		INTEGER 	NOT NULL UNIQUE,							
	diktyo_name  	VARCHAR(50) NOT NULL,								
	CONSTRAINT diktyo_pk PRIMARY KEY (id_diktyo)
)ENGINE=InnoDB;
	
DROP TABLE IF EXISTS users;
CREATE TABLE users (
	id_users 			INTEGER 		NOT NULL UNIQUE AUTO_INCREMENT,
	username  			VARCHAR(100) 	NOT NULL UNIQUE,
	firstname			VARCHAR(100)	NOT NULL,
	lastname			VARCHAR(100)	NOT NULL,
	user_password		CHAR(255) 		NOT NULL,
	email				VARCHAR(100) 	NULL,
	id_oplo				INTEGER			NOT NULL,
	id_vathmos			INTEGER			NOT NULL,
	id_rolos			INTEGER 		NOT NULL DEFAULT '2',
	id_monada			INTEGER 		NOT NULL,
	energos				BOOLEAN 		NOT NULL DEFAULT false,
	CONSTRAINT users_pk 		PRIMARY KEY (id_users),
	CONSTRAINT users_fk_rolos 	FOREIGN KEY (id_rolos) REFERENCES rolos(id_rolos) ON UPDATE CASCADE ON DELETE RESTRICT,
	CONSTRAINT users_fk_monada 	FOREIGN KEY (id_monada) REFERENCES monada (id_monada) ON UPDATE CASCADE ON DELETE RESTRICT,
	CONSTRAINT users_fk_oplo 	FOREIGN KEY (id_oplo) REFERENCES oplo (id_oplo) ON UPDATE CASCADE ON DELETE RESTRICT,
	CONSTRAINT users_fk_vathmos	FOREIGN KEY (id_vathmos) REFERENCES vathmos (id_vathmos) ON UPDATE CASCADE ON DELETE RESTRICT
)ENGINE=InnoDB; 

DROP TABLE IF EXISTS products;
CREATE TABLE products (
	barcode				BIGINT			NOT NULL UNIQUE AUTO_INCREMENT,
	id_tmima 			INTEGER			NOT NULL,
	id_katigoria_prod	INTEGER			NOT NULL,
	id_kataskeuastis	INTEGER			NOT NULL,
	id_diktyo 			INTEGER			NOT NULL DEFAULT '1',
	typos 				VARCHAR(200)	NULL,
	merida 				VARCHAR(100)	NULL,
	paratiriseis 		VARCHAR(400)	NULL,
	leitourgiko 		BOOLEAN 		NULL DEFAULT true,
	xreomeno 			BOOLEAN 		NULL DEFAULT false,
	sn 					VARCHAR(200)	NULL,
	import_date 		DATE 			NOT NULL,
	diegrameno 			BOOLEAN 		DEFAULT false,
	delete_date			DATE			NULL DEFAULT NULL,
	CONSTRAINT prod_pk 				 PRIMARY KEY (barcode),
	CONSTRAINT prod_fk_katigoria 	 FOREIGN KEY (id_katigoria_prod) REFERENCES katigoria_prod (id_katigoria_prod) ON UPDATE CASCADE ON DELETE RESTRICT,
	CONSTRAINT prod_fk_kataskeuastis FOREIGN KEY (id_kataskeuastis) REFERENCES kataskeuastis (id_kataskeuastis) ON UPDATE CASCADE ON DELETE RESTRICT,
	CONSTRAINT prod_fk_tmima 		 FOREIGN KEY (id_tmima) REFERENCES tmima(id_tmima) ON UPDATE CASCADE ON DELETE RESTRICT,
	CONSTRAINT prod_fk_diktyo 		 FOREIGN KEY (id_diktyo) REFERENCES diktyo(id_diktyo) ON UPDATE CASCADE ON DELETE RESTRICT
)ENGINE=InnoDB;

DROP TABLE IF EXISTS products_history;
CREATE TABLE products_history (
	id_products_history			INTEGER 		NOT NULL UNIQUE AUTO_INCREMENT,
	barcode 					BIGINT 			NOT NULL,
	date_history_xreosis 		DATE 			NOT NULL,
	id_monada					INTEGER			NOT NULL,
	date_history_ksexreosis 	DATE 			NULL DEFAULT NULL,
	id_users 					INTEGER			NULL DEFAULT NULL,	# Ο ΧΡΗΣΤΗΣ ΠΟΥ ΧΡΕΩΣΕ ΤΟ ΥΛΙΚΟ ΣΤΗΝ ΕΠΟΜΕΝΗ ΜΟΝΑΔΑ
	perigrafh_xreosis			VARCHAR(300)	NULL DEFAULT NULL,	# ΣΧΟΛΙΑ ΑΥΤΟΥ ΠΟΥ ΧΡΕΩΣΕ ΤΟ ΥΛΙΚΟ ΣΤΗΝ ΕΠΟΜΕΝΗ ΜΟΝΑΔΑ
	id_products_history_before	INTEGER			NULL DEFAULT NULL,
	CONSTRAINT prod_hist_pk 		PRIMARY KEY (id_products_history),
	CONSTRAINT prod_hist_fk_monada 	FOREIGN KEY (id_monada) REFERENCES monada(id_monada) ON UPDATE CASCADE ON DELETE RESTRICT,
	CONSTRAINT prod_hist_fk_barcode FOREIGN KEY (barcode) REFERENCES products(barcode) ON UPDATE CASCADE ON DELETE RESTRICT,
	CONSTRAINT prod_hist_fk_user 	FOREIGN KEY (id_users) REFERENCES users(id_users) ON UPDATE CASCADE ON DELETE SET NULL,
	CONSTRAINT prod_hist_fk_prod_hist FOREIGN KEY (id_products_history_before) REFERENCES products_history(id_products_history) 
														ON UPDATE RESTRICT ON DELETE RESTRICT
)ENGINE=InnoDB;

DROP TABLE IF EXISTS ekkremeis_xreoseis;
CREATE TABLE ekkremeis_xreoseis(
	barcode				BIGINT	 	NOT NULL UNIQUE,
	id_monada_before	INTEGER		NOT NULL,
	id_monada_after		INTEGER 	NOT NULL,
	id_users			INTEGER		NOT NULL, 
	perigrafh_xreosis			VARCHAR(300)	NULL DEFAULT NULL,
	CONSTRAINT ek_xreos_pk					PRIMARY KEY (barcode, id_monada_before, id_monada_after),
	CONSTRAINT ek_xreos_fk_monada_before	FOREIGN KEY (id_monada_before) REFERENCES monada(id_monada) ON UPDATE CASCADE ON DELETE CASCADE,
	CONSTRAINT ek_xreos_fk_monada_after		FOREIGN KEY (id_monada_after) REFERENCES monada(id_monada) ON UPDATE CASCADE ON DELETE CASCADE,
	CONSTRAINT ek_xreos_fk_products			FOREIGN KEY (barcode) REFERENCES products(barcode) ON UPDATE CASCADE ON DELETE CASCADE,
	CONSTRAINT ek_xreos_fk_users			FOREIGN KEY (id_users) REFERENCES users(id_users) ON UPDATE CASCADE ON DELETE CASCADE
)ENGINE=InnoDB;

DROP TRIGGER IF EXISTS nea_monada;
DELIMITER $$
CREATE TRIGGER nea_monada AFTER INSERT ON monada FOR EACH ROW
BEGIN
	INSERT INTO tmima(tmima_name, id_monada) VALUES("Γενική Διαχείριση", new.id_monada);
END
$$ 
DELIMITER ;

DROP TRIGGER IF EXISTS nea_xreosi;
DELIMITER $$
CREATE TRIGGER nea_xreosi AFTER INSERT ON products FOR EACH ROW 
BEGIN
	DECLARE id_monada_par INTEGER;
	SET id_monada_par = (get_monada_by_tmima(new.id_tmima));
	INSERT INTO products_history(barcode, id_monada, date_history_xreosis) VALUES(new.barcode, id_monada_par, CURDATE());
END
$$ 
DELIMITER ;


######################################################################################################################
# FUNCTIONS ΠΟΥ ΘΑ ΧΡΗΣΙΜΟΠΟΙΗΘΟΥΝ ΓΙΑ ΝΑ ΤΡΑΒΗΧΤΟΥΝ ΔΕΔΟΜΕΝΑ ΑΠΟ ΤΑ ΒΟΗΘΗΤΙΚΑ VIEWS ΜΕ ΧΡΗΣΗ ΦΙΛΤΡΩΝ
######################################################################################################################

DROP FUNCTION IF EXISTS get_monada_name;
DELIMITER $$
CREATE FUNCTION get_monada_name(monadaid_par INTEGER) RETURNS VARCHAR(200) DETERMINISTIC
BEGIN
	DECLARE monada_name_par VARCHAR(200);
	SET monada_name_par = (SELECT monada_name FROM monada WHERE id_monada=monadaid_par);
    RETURN monada_name_par;
END
$$ 
DELIMITER ;

DROP FUNCTION IF EXISTS get_monada_by_tmima;
DELIMITER $$
CREATE FUNCTION get_monada_by_tmima(tmimaid_par INTEGER) RETURNS INTEGER DETERMINISTIC
BEGIN
	DECLARE monadaid_par INTEGER;
	SET monadaid_par = (SELECT id_monada FROM tmima WHERE id_tmima=tmimaid_par);
    RETURN monadaid_par;
END
$$ 
DELIMITER ;

DROP FUNCTION IF EXISTS get_default_tmima_by_monada;
DELIMITER $$
CREATE FUNCTION get_default_tmima_by_monada(monadaid_par INTEGER) RETURNS INTEGER DETERMINISTIC
BEGIN
	DECLARE tmimaid_par INTEGER;
	SET tmimaid_par = (SELECT id_tmima FROM tmima WHERE id_monada=monadaid_par AND STRCMP(tmima_name, 'Γενική Διαχείριση')=0);
    RETURN tmimaid_par;
END
$$ 
DELIMITER ;

###########################################################################################################
# PROCEDURE ΜΕ TRANSACTION ΓΙΑ ΤΗΝ ΑΠΟΧΡΕΩΣΗ ΚΑΙ ΧΡΕΩΣΗ ΥΛΙΚΩΝ ΑΠΟ ΜΟΝΑΔΑ ΣΕ ΜΟΝΑΔΑ
# ΤΑ ΥΛΙΚΑ ΘΑ ΧΡΕΩΝΟΝΤΑΙ ΣΤΟ "DEFAULT" ΤΜΗΜΑ ΤΗΣ ΕΚΑΣΤΟΤΕ ΜΟΝΑΔΑΣ, ΔΛΔ ΤΟ "ΓΕΝΙΚΗ ΔΙΑΧΕΙΡΙΣΗ"
###########################################################################################################

# ΠΑΙΡΝΕΙ ΣΑΝ ΕΙΣΟΔΟ ΤΗ ΜΟΝΑΔΑ ΣΤΗΝ ΟΠΟΙΑ ΘΑΑΑ ΧΡΕΩΘΕΙ, ΤΟΝ USER ΠΟΥ ΚΑΤΑΧΩΡΗΣΕ ΤΗΝ ΧΡΕΩΣΗ (ΠΡΟΗΓΟΥΜΕΝΗΣ ΜΟΝΑΔΑΣ ΔΛΔ)
# ΣΧΟΛΙΑ ΑΥΤΟΥ ΠΟΥ ΚΑΤΑΧΩΡΕΙ ΤΗΝ ΧΡΕΩΣΗ, ΚΑΙ PATH ΧΡΕΩΣΤΙΚΟΥ ΑΥΤΟΥ ΠΟΥ ΚΑΤΑΧΩΡΕΙ ΤΗΝ ΧΡΕΩΣΗ
DROP PROCEDURE IF EXISTS xreosi_procedure;
DELIMITER //
CREATE PROCEDURE xreosi_procedure (IN barcode_in BIGINT, IN id_monada_in INT, IN id_user_in INT, 
									IN date_xreosis DATE, IN comments VARCHAR(300), 
                                    OUT error_out BOOLEAN)
BEGIN
    DECLARE t_error BOOLEAN DEFAULT FALSE;
    DECLARE error_message VARCHAR(40);
    DECLARE id_tmima_par INTEGER;
	DECLARE id_products_history_before_par INTEGER;
    DECLARE CONTINUE HANDLER FOR SQLEXCEPTION 
    BEGIN
        SET t_error = TRUE;
        SET error_message = "System error";
        ROLLBACK;
    END;
    START TRANSACTION;
    IF (barcode_in NOT IN (SELECT barcode FROM products)) THEN
        SET error_message = "No product with this id has been found";
        SET t_error = TRUE;
    ELSE
        -- Update products table
		SET id_products_history_before_par = (SELECT id_products_history FROM products_history 
											WHERE barcode = barcode_in AND date_history_ksexreosis IS NULL);
		SET id_tmima_par = (SELECT get_default_tmima_by_monada(id_monada_in));
        UPDATE products SET id_tmima = id_tmima_par WHERE barcode = barcode_in;
        -- Update products_history table
        UPDATE products_history SET date_history_ksexreosis = date_xreosis, id_users=id_user_in,
									perigrafh_xreosis=comments 
								WHERE barcode = barcode_in AND date_history_ksexreosis IS NULL;
        -- Insert into products_history table
        INSERT INTO products_history (barcode, id_monada, date_history_xreosis, id_products_history_before) 
				VALUES (barcode_in, id_monada_in, date_xreosis, id_products_history_before_par);
		DELETE FROM ekkremeis_xreoseis WHERE barcode = barcode_in;
        COMMIT;
    END IF;
    -- Error handling
    IF (t_error) THEN
        SELECT error_message;
    ELSE
        SELECT "Success!";
    END IF;
    SET error_out = t_error;
END
//
DELIMITER ;

######################################################################################################################
# VIEW ΠΟΥ ΘΑ ΧΡΗΣΙΜΟΠΟΙΗΘΟΥΝ ΓΙΑ ΝΑ ΤΡΑΒΗΧΤΟΥΝ ΕΤΟΙΜΑ-ΧΡΗΣΙΜΑ ΔΕΔΟΜΕΝΑ (ΓΙΑ ΕΜΦΑΝΙΣΗ ΜΟΝΟ)
######################################################################################################################

DROP VIEW IF EXISTS all_product_info;
CREATE VIEW all_product_info AS
SELECT barcode, typos, katigoria_prod_name, kataskeuastis_name, merida, paratiriseis, leitourgiko, xreomeno, sn, import_date, diegrameno, monada_name, tmima_name, diktyo_name
FROM products NATURAL JOIN tmima NATURAL JOIN katigoria_prod NATURAL JOIN monada NATURAL JOIN diktyo NATURAL JOIN kataskeuastis;

DROP VIEW IF EXISTS deleted_product_info;
CREATE VIEW deleted_product_info AS
SELECT barcode, typos, katigoria_prod_name, kataskeuastis_name, merida, paratiriseis, leitourgiko, xreomeno, sn, import_date, monada_name, tmima_name, diktyo_name, delete_date
FROM products NATURAL JOIN tmima NATURAL JOIN katigoria_prod NATURAL JOIN monada NATURAL JOIN diktyo NATURAL JOIN kataskeuastis
WHERE diegrameno=1;

DROP VIEW IF EXISTS not_deleted_product_info;
CREATE VIEW not_deleted_product_info AS
SELECT barcode, typos, katigoria_prod_name, kataskeuastis_name, merida, paratiriseis, leitourgiko, xreomeno, sn, import_date, monada_name, tmima_name, diktyo_name
FROM products NATURAL JOIN tmima NATURAL JOIN katigoria_prod NATURAL JOIN monada NATURAL JOIN diktyo NATURAL JOIN kataskeuastis
WHERE diegrameno=0;

DROP VIEW IF EXISTS all_user_info;
CREATE VIEW all_user_info AS
SELECT id_users, email, firstname, lastname, oplo_name, vathmos_name, rolos_name, monada_name, energos
FROM users NATURAL JOIN oplo NATURAL JOIN vathmos NATURAL JOIN rolos NATURAL JOIN monada;

DROP VIEW IF EXISTS active_user_info;
CREATE VIEW active_user_info AS
SELECT id_users, email, firstname, lastname, oplo_name, vathmos_name, rolos_name, monada_name
FROM users NATURAL JOIN oplo NATURAL JOIN vathmos NATURAL JOIN rolos NATURAL JOIN monada
WHERE energos=1;

DROP VIEW IF EXISTS product_history_view;
CREATE VIEW product_history_view AS
SELECT DISTINCT p1.barcode, get_monada_name(p2.id_monada) AS "MONADA APO", p1.date_history_xreosis, p1.id_monada, p1.date_history_ksexreosis, 
							get_monada_name(p3.id_monada) AS "MONADA PROS", p1.id_users, p1.perigrafh_xreosis
		FROM products_history p1 LEFT JOIN products_history p2 ON p1.id_products_history_before=p2.id_products_history
							LEFT JOIN products_history p3 ON p1.id_products_history=p3.id_products_history_before;

######################################################################################################################
# PROCEDURES ΠΟΥ ΘΑ ΧΡΗΣΙΜΟΠΟΙΗΘΟΥΝ ΓΙΑ ΑΥΤΟΜΑΤΕΣ ΔΙΑΔΙΚΑΣΙΕΣ ΣΕ ΣΥΓΚΕΚΡΙΜΕΝΕΣ ΕΝΕΡΓΕΙΕΣ
######################################################################################################################

DROP PROCEDURE IF EXISTS delete_product_change;
DELIMITER $$
CREATE PROCEDURE delete_product_change(IN diegrameno_par BOOLEAN, IN barcode_par INTEGER)
BEGIN
	IF (diegrameno_par IS true) THEN
		UPDATE products SET delete_date=CURDATE(), diegrameno=true WHERE barcode=barcode_par;
	ELSE
		UPDATE products SET delete_date=NULL, diegrameno=false WHERE barcode=barcode_par;
	END IF;
END
$$ 
DELIMITER ;

######################################################################################################################
# PROCEDURES ΠΟΥ ΘΑ ΧΡΗΣΙΜΟΠΟΙΗΘΟΥΝ ΓΙΑ ΝΑ ΤΡΑΒΗΧΤΟΥΝ ΔΕΔΟΜΕΝΑ ΑΠΟ ΤΑ ΒΟΗΘΗΤΙΚΑ VIEWS ΜΕ ΧΡΗΣΗ ΦΙΛΤΡΩΝ
######################################################################################################################

DROP PROCEDURE IF EXISTS get_not_deleted_product_info_by_monada;
DELIMITER $$
CREATE PROCEDURE get_not_deleted_product_info_by_monada(IN id_monada_par INTEGER)
BEGIN
	DECLARE monada_name_par VARCHAR(100);
    SET monada_name_par = (SELECT monada_name FROM monada WHERE id_monada = id_monada_par);
	SELECT * FROM not_deleted_product_info WHERE monada_name = monada_name_par;
END
$$ 
DELIMITER ;



DROP PROCEDURE IF EXISTS get_pros_xreosi_product_info_by_monada;
DELIMITER $$
CREATE PROCEDURE get_pros_xreosi_product_info_by_monada(IN id_monada_par INTEGER)
BEGIN
DECLARE monada_name_par VARCHAR(100);
    SET monada_name_par = (SELECT monada_name FROM monada WHERE id_monada = id_monada_par);
SELECT * FROM not_deleted_product_info n left join ekkremeis_xreoseis e on n.barcode=e.barcode WHERE monada_name = monada_name_par and id_monada_before is null ;
END
$$ 
DELIMITER ;

DROP PROCEDURE IF EXISTS get_deleted_product_info_by_monada;
DELIMITER $$
CREATE PROCEDURE get_deleted_product_info_by_monada(IN id_monada_par INTEGER)
BEGIN
	DECLARE monada_name_par VARCHAR(100);
    SET monada_name_par = (SELECT monada_name FROM monada WHERE id_monada = id_monada_par);
	SELECT * FROM deleted_product_info WHERE monada_name = monada_name_par;
END
$$ 
DELIMITER ;

# ΔΗΜΙΟΥΡΓΙΑ VIEWS ΓΙΑ ΤΑ ΧΡΗΣΙΜΑ ΣΤΟΙΧΕΙΑ ΑΠΟ ΧΡΕΩΣΕΙΣ, ΥΛΙΚΑ, ΧΡΗΣΤΗΣ, ?ΤΜΗΜΑ?


# ΕΙΣΑΓΩΓΗ ΣΤΟΙΧΕΙΩΝ ΣΤΟ PRODUCTS HISTORY ΠΟΤΕ?? ΓΙΝΕΤΑΙ ΧΕΙΡΟΚΙΝΗΤΑ?? ΓΙΝΕΤΑΙ ΜΑΖΙ ΜΕ ΤΗΝ ΧΡΕΩΣΗ Ή ΜΠΑΙΝΟΥΝ ΛΕΠΤΟΜΕΡΕΙΕΣ ΜΕΤΑ??
