# Converted with pg2mysql-1.9
# Converted on Fri, 28 Jun 2019 23:49:20 -0400
# Lightbox Technologies Inc. http://www.lightbox.ca

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone="+00:00";

CREATE TABLE users (
    id_user int(11) NOT NULL AUTO_INCREMENT,
    user_name varchar(25) NOT NULL,
    password text NOT NULL,
    first_name varchar(50) NOT NULL,
    last_name varchar(50) NOT NULL,
    email varchar(50) NOT NULL,
    PRIMARY KEY (id_user)
) ENGINE=InnoDB;

CREATE TABLE recovered_accounts (
    recovered_account_id int(11) NOT NULL AUTO_INCREMENT,
    id_user int(11) NOT NULL,
    single_use_token varchar(200) NOT NULL,
    expired_token_date timestamp NOT NULL,
    PRIMARY KEY (recovered_account_id)
) ENGINE=InnoDB;

CREATE TABLE products (
    id_product int(11) NOT NULL AUTO_INCREMENT,
    name varchar(144) NOT NULL,
    price real NOT NULL,
    id_unit_measurement int(11) NOT NULL,
    id_category int(11) NOT NULL,
    itbis varchar(1) NOT NULL,
    description varchar(254) NOT NULL,
    id_user int(11) NOT NULL,
    PRIMARY KEY (id_product)
) ENGINE=InnoDB;

CREATE TABLE measurement_units (
    id_unit_measurement int(11) NOT NULL AUTO_INCREMENT,
    name varchar(254) NOT NULL,
    id_user int(11) NOT NULL,
    PRIMARY KEY (id_unit_measurement)
) ENGINE=InnoDB;

CREATE TABLE categories (
    id_category int(11) NOT NULL AUTO_INCREMENT,
    name varchar(254) NOT NULL,
    id_user int(11) NOT NULL,
    PRIMARY KEY (id_category)
) ENGINE=InnoDB;

CREATE TABLE currencies (
    id_currency int(11) NOT NULL AUTO_INCREMENT,
    symbol varchar(5) NOT NULL,
    PRIMARY KEY (id_currency)
) ENGINE=InnoDB;

CREATE TABLE itbis (
    id_itbis int(11) NOT NULL AUTO_INCREMENT,
    quantity int(11) NOT NULL,
    id_user int(11) NOT NULL,
    PRIMARY KEY (id_itbis)
) ENGINE=InnoDB;

CREATE TABLE purchases_details (
    id_purchase_detail int(11) NOT NULL AUTO_INCREMENT,
    id_purchase int(11) NOT NULL,
    product_name varchar(254) NOT NULL,
    unit_price real NOT NULL,
    quantity int(11) NOT NULL,
    apply_itbis int(11) NOT NULL,
    category varchar(254) NOT NULL,
    measurement_unit varchar(254) NOT NULL,
    PRIMARY KEY (id_purchase_detail)
) ENGINE=InnoDB;

CREATE TABLE names_establishments (
    id_establishment_name int(11) NOT NULL AUTO_INCREMENT,
    id_purchase int(11) NOT NULL,
    name varchar(254) NOT NULL,
    PRIMARY KEY (id_establishment_name)
) ENGINE=InnoDB;

CREATE TABLE purchases (
    id_purchase int(11) NOT NULL AUTO_INCREMENT,
    date timestamp NOT NULL,
    id_user int(11) NOT NULL,
    PRIMARY KEY (id_purchase)
) ENGINE=InnoDB;

