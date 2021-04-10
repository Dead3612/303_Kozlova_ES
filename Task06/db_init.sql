PRAGMA foreign_keys=ON;


DROP TABLE IF EXISTS workbooks;
CREATE TABLE IF NOT EXISTS workbooks(id INTEGER PRIMARY KEY,
									 name VARCHAR(30) NOT NULL,  
									 surname VARCHAR(30) NOT NULL,
									 patronymic VARCHAR(30), 
									 gender VARCHAR(5),
									 date_of_birth DATE NOT NULL,
									 percentage_of_revenue REAL DEFAULT 0.1, 
									 status VARCHAR(10),
									 CHECK (gender = 'муж' OR gender = 'жен'),
									 CHECK (percentage_of_revenue > 0.0 AND (percentage_of_revenue < 5.0) AND (status = 'работает' OR status = 'уволен')));
BEGIN;
INSERT INTO workbooks(name, surname, patronymic, gender, date_of_birth, percentage_of_revenue, status) VALUES
('Петр', 'Петрович', 'Петров', 'муж', '2000-01-02', 1.5, 'работает'),
('Иван', 'Иванович', 'Иванов', 'муж', '2000-03-04', 2.5, 'работает'),
('Сидор', 'Сидорович', 'Сидоров', 'муж', '2000-05-06', 2.0, 'работает'),
('Николай', 'Николаевич', 'Николаев', 'муж', '2000-07-08', 1.25, 'работает'),
('Александр', 'Александрович', 'Александров', 'муж', '2000-09-10', 3.0, 'работает');
;
COMMIT;


DROP TABLE IF EXISTS services;
CREATE TABLE IF NOT EXISTS services(id INTEGER PRIMARY KEY NOT NULL, 
									service VARCHAR(50) NOT NULL);
BEGIN;
INSERT INTO services(service) VALUES
('Ручная мойка кузова'),
('Бесконтактная мойка кузова'),
('Чистка салона пылесосом и влажная уборка пластмассовых деталей'),
('Чистка стекол изнутри химическими средствами'),
('Полировка пластмассовых деталей салона химическими средствами'),
('Чистка багажника'),
('Комплексная мойка'),
('Мойка двигателя и моторного отсека'),
('Покрытие кузова воском на основе тефлоновой полировки'),
('Кондиционер кожанных сидений');
COMMIT;

DROP TABLE IF EXISTS car_categories;
CREATE TABLE IF NOT EXISTS car_categories(id INTEGER PRIMARY KEY NOT NULL, 
										  class VARCHAR(30));
										  
BEGIN;
INSERT INTO car_categories(class) VALUES
('A-класс'),
('B-класс'),
('C-класс'),
('D-класс'),
('M-класс');
COMMIT;

DROP TABLE IF EXISTS prices_categories;
CREATE TABLE IF NOT EXISTS prices(service_id INTEGER,
								  class_id INTEGER,
								  servise_in_minutes INTEGER, 
								  prise REAL, 
								  FOREIGN KEY (class_id) REFERENCES car_categories (id)ON DELETE RESTRICT ON UPDATE CASCADE,
								  FOREIGN KEY (service_id) REFERENCES services(id)ON DELETE RESTRICT ON UPDATE CASCADE, 
								  CHECK (servise_in_minutes > 0 AND price > 0));
BEGIN;
INSERT INTO prices(service_id, class_id, servise_in_minutes, prise) VALUES
(1, 1, 20, 100.00),
(2, 1, 25, 120.00),
(3, 1, 10, 110.00),
(4, 1, 20, 150.00),
(5, 1, 30, 180.00),
(6, 1, 60, 140.00),
(7, 1, 40, 250.00),
(8, 1, 30, 200.00),
(9, 1, 25, 190.00),
(10, 1, 20, 120.00),
(1, 2, 20, 110.00),
(2, 2, 25, 130.00),
(3, 2,  10, 120.00),
(4, 2, 20, 160.00),
(5, 2, 30, 190.00),
(6, 2, 60, 150.00),
(7, 2, 40, 260.00),
(8, 2, 30, 210.00),
(9, 2, 25, 200.00),
(10, 2, 20, 130.00),
(1, 2, 20, 100.00),
(2, 3, 20, 120.00),
(3, 3, 10, 110.00),
(4, 3, 20, 150.00),
(5, 3, 30, 180.00),
(6, 3, 60, 140.00),
(7, 3, 40, 250.00),
(8, 3, 30, 200.00),
(9, 3, 25, 190.00),
(10, 3, 20, 120.00),
(1, 4, 40, 140.00),
(2, 4, 45, 160.00),
(3, 4, 30, 170.00),
(4, 4, 40, 210.00),
(5, 4, 50, 240.00),
(6, 4, 80, 200.00),
(7, 4, 60, 310.00),
(8, 4, 50, 240.00),
(9, 4, 45, 230.00),
(10, 4, 40, 160.00),
(1, 5, 40, 140.00),
(2, 5, 45, 160.00),
(3, 5, 30, 170.00),
(4, 5, 40, 210.00),
(5, 5, 50, 240.00),
(6, 5, 80, 200.00),
(7, 5, 60, 310.00),
(8, 5, 50, 240.00),
(9, 5, 45, 230.00),
(10, 5, 40, 160.00);
COMMIT;

DROP TABLE IF EXISTS box;
CREATE TABLE IF NOT EXISTS box(id INTEGER PRIMARY KEY,
							   number INTEGER NOT NULL);
							   
BEGIN;
INSERT INTO box(number) VALUES
(1),
(2),
(3),
(4),
(5);
COMMIT;			   


DROP TABLE IF EXISTS services_performed;
CREATE TABLE IF NOT EXISTS services_performed( workbook_id INTEGER,
										       service_id INTEGER,
											   car_class INTEGER,
											   data DATE,
											   FOREIGN KEY (workbook_id) REFERENCES workbooks(id),
											   FOREIGN KEY (service_id) REFERENCES services(id)
											   FOREIGN KEY (car_class) REFERENCES car_categories(id)
											   CHECK(data < DATE())));
											   
											   
BEGIN;
INSERT INTO services_performed(service_id, car_class, workbook_id, data) VALUES
(1, 4, 1, '2021-02-06'),
(1, 2, 1, '2021-02-06'),
(1, 2, 3, '2021-02-06'),
(1, 3, 2, '2021-02-06'),
(1, 4, 3, '2021-02-06'),
(1, 3, 3, '2021-02-06'),
(1, 2, 4, '2021-02-06'),
(1, 4, 2, '2021-02-06'),
(1, 5, 1, '2021-02-06'),
(1, 5, 3, '2021-02-06');
COMMIT;

DROP TABLE IF EXISTS service_record;
CREATE TABLE IF NOT EXISTS service_record (id INTEGER PRIMARY KEY,
										   workbook_id INTEGER,
										   car_class INTEGER,
										   service_id INTEGER,
										   number_box INTEGER,
										   data DATE,
										   time TIME,
										   FOREIGN KEY (workbook_id) REFERENCES workbooks(id),
										   FOREIGN KEY (car_class) REFERENCES car_categories(id),
										   FOREIGN KEY (servise_id) REFERENCES services(id),
										   FOREIGN KEY (number_box) REFERENCES box (id)
										   CHECK(data > DATE()));
										   

BEGIN;
INSERT INTO service_record(	service_id, car_class, 	workbook_id, number_box, data, time) VALUES		   
(1, 1, 1, 1, '2021-05-10', '20:00'),
(2, 1, 2, 2, '2021-06-11', '09:00'),
(1, 3, 3, 3, '2021-04-16', '18:00'),
(4, 1, 4, 4, '2021-05-10', '10:00'),
(2, 3, 5, 5, '2021-06-16', '17:00');
COMMIT;


DROP TABLE IF EXISTS work_schedule;
CREATE TABLE IF NOT EXISTS work_schedule(workbook_id INTEGER NOT NULL,
										 start_time TIME,
										 end_time TIME,
										 day_of_week TEXT
										 FOREIGN KEY (workbook_id) REFERENCES workbooks(id),
										 CHECK(start_time >= '08:00' AND start_time <= '14:00')
										 CHECK(end_time >= '14:00' AND end_time <= '22:00')
										 CHECK (day_of_week = 'Пн' OR day_of_week = 'Вт' OR day_of_week = 'Ср' OR day_of_week = 'Чт' OR day_of_week = 'Пт' OR day_of_week = 'Сб' OR day_of_week = 'Вс'));
 BEGIN;
 INSERT INTO work_schedule(start_time, end_time, workbook_id, day_of_week) VALUES
 ('08:00', '16:00', 1, 'Пн'),
('09:00', '17:00', 1, 'Вт'),
('08:00', '16:00', 1, 'Ср'),
('08:00', '16:00', 1, 'Чт'),
('08:00', '16:00', 1, 'Пт'),
('08:00', '16:00', 1, 'Сб'),
('08:00', '16:00', 1, 'Вс'),
('09:00', '17:00', 2, 'Пн'),
('09:00', '17:00', 2, 'Вт'),
('08:00', '16:00', 2, 'Ср'),
('08:00', '16:00', 2, 'Чт'),
('08:00', '16:00', 2, 'Пт'),
('09:00', '17:00', 2, 'Сб'),
('09:00', '17:00', 2, 'Вс'),
('09:00', '17:00', 3, 'Пн'),
('08:00', '16:00', 3, 'Вт'),
('09:00', '17:00', 3, 'Ср'),
('08:00', '16:00', 3, 'Чт'),
('08:00', '16:00', 3, 'Пт'),
('12:00', '20:00', 4, 'Пн'),
('12:00', '20:00', 4, 'Вт'),
('14:00', '22:00', 4, 'Ср'),
('14:00', '22:00', 4, 'Чт'),
('12:00', '20:00', 4, 'Пт'),
('12:00', '20:00', 4, 'Сб'),
('12:00', '20:00', 4, 'Вс'),
('14:00', '22:00', 5, 'Пн'),
('08:00', '16:00', 5, 'Вт'),
('08:00', '16:00', 5, 'Ср'),
('10:00', '18:00', 5, 'Чт'),
('10:00', '18:00', 5, 'Пт');
COMMIT;