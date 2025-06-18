-- Εισαγωγή δεδομένων στον πίνακα katigoria_prod
INSERT INTO katigoria_prod (katigoria_prod_name) VALUES
('Η/Υ'),
('Οθόνη'),
('Εκτυπωτής'),
('Laptop'),
('Scanner'),
('Projector'),
('Φωτοτυπικό'),
('HDD'),
('Switch'),
('Router'),
('Hub'),
('Rack'),
('Server'),
('UPS'),
('Τηλέφωνο'),
('Tablet'),
('Κλιματιστικό');

-- Εισαγωγή δεδομένων στον πίνακα rolos
INSERT INTO rolos (id_rolos, rolos_name) VALUES
(0, 'admin'),
(1, 'adminProduct'),
(2, 'Γενικός Διαχειριστής Υλικού');

-- Εισαγωγή δεδομένων στον πίνακα monada
INSERT INTO monada (monada_name) VALUES
('ΚΕΠΥΕΣ'),
('ΓΕΣ/ΔΔΒ'),
('ΓΕΣ/ΔΠΖ'),
('ΓΕΣ/ΔΤΘ'),
('ΓΕΣ/ΔΠΒ'),
('ΓΕΣ/ΔΜΧ');

-- Εισαγωγή δεδομένων στον πίνακα tmima
INSERT INTO tmima (tmima_name, id_monada) VALUES
('Administrators', 1),
('Γραφείο Έρευνας', 1),
('Ηλεκτρονικού Πολέμου', 2),
('Επιτήρησης', 2),
('Εκπαίδευσης', 3),
('Σχεδίων', 3),
('Συντήρησης', 4),
('Εκπαίδευσης', 4),
('Παρατηρητών', 5),
('Σχεδίων', 5),
('Καταστροφών', 6),
('Έργων', 6);

-- Εισαγωγή δεδομένων στον πίνακα kataskeuastis
INSERT INTO kataskeuastis (kataskeuastis_name) VALUES
('HP'),
('DELL'),
('SONY'),
('LENOVO'),
('XIAOMI'),
('MICROSOFT'),
('SAMSUNG');

-- Εισαγωγή δεδομένων στον πίνακα diktyo
INSERT INTO diktyo (id_diktyo, diktyo_name) VALUES
(1, 'Γενικό'),
(2, 'Διαβαθμισμένο'),
(3, 'Αδιαβάθμητο');

-- Εισαγωγή δεδομένων στον πίνακα oplo
INSERT INTO oplo (oplo_name) VALUES
('Πεζικό'),
('Πυροβολικό'),
('Τεθωρακισμενα'),
('Διαβιβάσεις'),
('Μηχανικό'),
('Αεροπορία'),
('Πληροφορική');

-- Εισαγωγή δεδομένων στον πίνακα vathmos
INSERT INTO vathmos (vathmos_name) VALUES
('Δεκανέας'),
('Λοχίας'),
('Επιλοχίας'),
('Αρχιλοχίας'),
('Ανθυπασπιστής'),
('Ανθυπολοχαγός'),
('Υπολοχαγός'),
('Λοχαγός'),
('Ταγματάρχης'),
('Αντισυνταγματάρχης'),
('Συνταγματάρχης'),
('Ταξίαρχος');

-- Εισαγωγή δεδομένων στον πίνακα users
INSERT INTO users (username, firstname, lastname, user_password, email, id_oplo, id_vathmos, id_rolos, id_monada, energos) VALUES 
('admin', 'Admin', 'Users',             '$2y$10$9nrTZmsbfHN9eDFZtP1CHOzMjtUoqa/IbHVjJEOXNZHRVuiFMg5yq', 'admin@army.gr', 7, 3, 0, 2, true),
#--('admin_products', 'Admin', 'Products', '$2y$10$9nrTZmsbfHN9eDFZtP1CHOzMjtUoqa/IbHVjJEOXNZHRVuiFMg5yq', 'admin_products@army.gr', 7, 3, 1, 2, false), 
('kepyes', 'Νίκολαός', 'Νίκου', 			'$2y$10$9nrTZmsbfHN9eDFZtP1CHOzMjtUoqa/IbHVjJEOXNZHRVuiFMg5yq', 'nikos@army.gr', 7, 4, 2, 1, true),
('ges/ddb', 'Μαρίνα', 'Μαρίνου',            '$2y$10$9nrTZmsbfHN9eDFZtP1CHOzMjtUoqa/IbHVjJEOXNZHRVuiFMg5yq', 'marina@army.gr', 4, 5, 2, 2, true),
('ges/dpz', 'Σταύρος', 'Παππάς',            '$2y$10$9nrTZmsbfHN9eDFZtP1CHOzMjtUoqa/IbHVjJEOXNZHRVuiFMg5yq', 'stauros@army.gr', 1, 6, 2, 3, true),
('ges/dtth', 'Λάμπρος', 'Φωτίου',            '$2y$10$9nrTZmsbfHN9eDFZtP1CHOzMjtUoqa/IbHVjJEOXNZHRVuiFMg5yq', 'lampros@army.gr', 3, 7, 2, 4, true),
('ges/dpb', 'Γίωργος', 'Λάμπρου',            '$2y$10$9nrTZmsbfHN9eDFZtP1CHOzMjtUoqa/IbHVjJEOXNZHRVuiFMg5yq', 'giwrgos@army.gr', 2, 4, 2, 5, false)
#--,('ges/dmx', 'Παύλος', 'Παύλου',            '$2y$10$9nrTZmsbfHN9eDFZtP1CHOzMjtUoqa/IbHVjJEOXNZHRVuiFMg5yq', 'pavlos@army.gr', 5, 6, 2, 6, true)
;

-- Εισαγωγή δεδομένων στον πίνακα products
INSERT INTO products (id_tmima, id_katigoria_prod, id_kataskeuastis, id_diktyo, typos, merida, paratiriseis, leitourgiko, xreomeno, sn, import_date) VALUES
( 1, 1, 1, 1, 'i3', '1000', '8GB Ram - 500 GB SSD', true, true, 'SN123', '2024-04-23'),
( 1, 2, 2, 1, '19"', '2000', 'HDMI - VGA', false, false, 'SN456', '2024-04-23'),
( 1, 3, 3, 1, 'Color', '3000', '2 in 1', true, true, 'SN789', '2024-04-23'),
( 1, 4, 4, 1, 'i5', '1500', '4GB Ram - 128 GB SSD', true, false, 'SN781', '2024-04-23'),
( 1, 4, 4, 1, 'i5', '1500', '4GB Ram - 128 GB SSD', true, true, 'SN782', '2024-04-23'),
( 1, 4, 4, 1, 'i5', '1500', '4GB Ram - 128 GB SSD', true, true, 'SN783', '2024-04-23'),
( 1, 5, 7, 1, 'Πολλαπλών σελίδων', '3200', 'Πολύ μεγάλο κείμενο που θα πρέπει να κρύβεται και να μην εμφανίζεται ολόκληρο', true, false, 'SN784', '2024-04-23'),
( 1, 7, 1, 1, 'Black-white', '3100', 'Πολύ μεγάλο κείμενο που θα πρέπει να κρύβεται και να μην εμφανίζεται ολόκληρο', true, false, 'SN785', '2024-04-23'),
( 1, 7, 1, 1, 'Black-white', '3100', 'Πολύ μεγάλο κείμενο που θα πρέπει να κρύβεται και να μην εμφανίζεται ολόκληρο', false, false, 'SN786', '2024-04-23'),
( 1, 7, 7, 1, 'Black-white', '3110', 'Πολύ μεγάλο κείμενο που θα πρέπει να κρύβεται και να μην εμφανίζεται ολόκληρο', false, true, 'SN787', '2024-04-23'),
( 1, 8, 4, 1, '2 TB', '9000', 'Εξωτερικός', false, false, 'SN788', '2024-04-23')
                                                                                            ,                                                                                       
( 1, 4, 4, 1, 'i5', '1500', '4GB Ram - 128 GB SSD', true, true, 'SN726', '2024-04-23'),
( 1, 4, 2, 1, 'i3', '1510', '4GB Ram - 128 GB SSD', true, true, 'SN737', '2024-04-23'),
( 1, 4, 2, 1, 'i3', '1510', '4GB Ram - 128 GB SSD', true, true, 'SN736', '2024-04-23'),
( 1, 4, 2, 1, 'i3', '1520', '8GB Ram - 128 GB SSD', true, true, 'SN747', '2024-04-23'),
( 1, 4, 2, 1, 'i3', '1520', '8GB Ram - 128 GB SSD', true, true, 'SN746', '2024-04-23'),
( 1, 1, 2, 1, 'i3', '1010', '4GB Ram - 500 GB SSD', true, true, 'SN587', '2024-04-23'),
( 1, 1, 2, 1, 'i3', '1010', '4GB Ram - 500 GB SSD', true, true, 'SN586', '2024-04-23'),
( 1, 1, 1, 1, 'i5', '1020', '8GB Ram - 500 GB SSD', true, true, 'SN687', '2024-04-23'),
( 1, 1, 1, 1, 'i5', '1020', '8GB Ram - 500 GB SSD', true, true, 'SN986', '2024-04-23'),
( 1, 2, 3, 1, '19"', '2010', 'HDMI - VGA', true, true, 'SN087', '2024-04-23'),
( 1, 2, 3, 1, '19"', '2010', 'HDMI - VGA', true, true, 'SN706', '2024-04-23'),
( 1, 2, 1, 1, '24"', '2020', 'HDMI', true, true, 'SN723', '2024-04-23'),
( 1, 2, 1, 1, '24"', '2020', 'HDMI', true, false, 'SN725', '2024-04-23'),
( 1, 3, 1, 1, 'Color', '3010', '2 in 1', true, false, 'SN724', '2024-04-23'),
( 1, 3, 7, 1, 'Color', '3020', '2 in 1', false, true, 'SN754', '2024-04-23')
                                                                                            ;


CALL xreosi_procedure(10, 2, 3, CURDATE(), "Χρέωση από ΚΕΠΥΕΣ σε ΓΕΣ/ΔΔΒ", @error_bool);
CALL xreosi_procedure(10, 1, 4, CURDATE(), "Χρέωση από ΓΕΣ/ΔΔΒ σε ΚΕΠΥΕΣ", @error_bool);
UPDATE products SET diegrameno=true, delete_date=CURDATE() WHERE barcode=2;
UPDATE products SET diegrameno=true, delete_date=CURDATE() WHERE barcode=10;

CALL xreosi_procedure(3, 2, 3, CURDATE(), "Χρέωση από ΚΕΠΥΕΣ σε ΓΕΣ/ΔΔΒ", @error_bool);
CALL xreosi_procedure(4, 2, 3, CURDATE(), "Χρέωση από ΚΕΠΥΕΣ σε ΓΕΣ/ΔΔΒ", @error_bool);
CALL xreosi_procedure(11, 2, 3, CURDATE(), "Χρέωση από ΚΕΠΥΕΣ σε ΓΕΣ/ΔΔΒ", @error_bool);
CALL xreosi_procedure(11, 1, 4, CURDATE(), "Χρέωση από ΓΕΣ/ΔΔΒ σε ΚΕΠΥΕΣ", @error_bool);

CALL xreosi_procedure(8, 3, 3, CURDATE(), "Χρέωση από ΚΕΠΥΕΣ σε ΓΕΣ/ΔΠΖ", @error_bool);
CALL xreosi_procedure(7, 3, 3, CURDATE(), "Χρέωση από ΚΕΠΥΕΣ σε ΓΕΣ/ΔΠΖ", @error_bool);
CALL xreosi_procedure(12, 3, 3, CURDATE(), "Χρέωση από ΚΕΠΥΕΣ σε ΓΕΣ/ΔΠΖ", @error_bool);

CALL xreosi_procedure(13, 4, 3, CURDATE(), "Χρέωση από ΚΕΠΥΕΣ σε ΓΕΣ/ΔΤΘ", @error_bool);
CALL xreosi_procedure(15, 4, 3, CURDATE(), "Χρέωση από ΚΕΠΥΕΣ σε ΓΕΣ/ΔΤΘ", @error_bool);
CALL xreosi_procedure(16, 4, 3, CURDATE(), "Χρέωση από ΚΕΠΥΕΣ σε ΓΕΣ/ΔΤΘ", @error_bool);

CALL xreosi_procedure(17, 5, 3, CURDATE(), "Χρέωση από ΚΕΠΥΕΣ σε ΓΕΣ/ΔΠΒ", @error_bool);
CALL xreosi_procedure(18, 5, 3, CURDATE(), "Χρέωση από ΚΕΠΥΕΣ σε ΓΕΣ/ΔΠΒ", @error_bool);
