что нужно для запуска:
установленный в системе apache или nginx
установленный php не ниже 5.6
mysql локальный или удаленный

файлы проекта поместите в каталог сайта так чтобы index.php был в его корне
в проекте уже есть файлы настроки для указанных вебсерверов(скопипащены из laravel)
     которые перенаправят все запросы к сайту на файл index.php
далее в файле config.php укажите значения для соединения с базой данных
таблицы в базе можно создать такими скриптами
CREATE TABLE clients_db.clients (
  id INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  name VARCHAR(255) NOT NULL,
  surname VARCHAR(255) NOT NULL,
  fathername VARCHAR(255) NOT NULL,
  birthday DATE NOT NULL,
  sex TINYINT(1) NOT NULL,
  created_at TIMESTAMP NULL DEFAULT NULL,
  updated_at TIMESTAMP NULL DEFAULT NULL,
  PRIMARY KEY (id)
)
ENGINE = INNODB
AVG_ROW_LENGTH = 8192
CHARACTER SET utf8
COLLATE utf8_general_ci
ROW_FORMAT = DYNAMIC;

CREATE TABLE clients_db.phonenumber (
  id INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  client_id INT(11) UNSIGNED NOT NULL,
  number VARCHAR(20) NOT NULL,
  created_at TIMESTAMP NULL DEFAULT NULL,
  updated_at TIMESTAMP NULL DEFAULT NULL,
  PRIMARY KEY (id),
  CONSTRAINT FK_phone_numbers_client_id FOREIGN KEY (client_id)
    REFERENCES clients_db.clients(id) ON DELETE CASCADE ON UPDATE RESTRICT
)
ENGINE = INNODB
AVG_ROW_LENGTH = 1638
CHARACTER SET utf8
COLLATE utf8_general_ci
ROW_FORMAT = DYNAMIC;

при работе с веб интерфейсом обратите внимание что в поле с номерами телефонов эти самые номера 
нужно указывать по одному на строчку