create database db_s2_ETU003339;
use db_s2_ETU003339;

create table region (
    id int auto_increment primary key,
    nom varchar(255) not null
);

create table villes (
    id int auto_increment primary key,
    nom varchar(255) not null,
    region_id int,
    foreign key (region_id) references region(id)
);

create table type_besoin (
    id int auto_increment primary key,
    code varchar(255) not null,
    libelle varchar(255) not null
);

create table besoin (
    id int auto_increment primary key,
    type_besoin_id int,
    ville_id int,
    description text,
    prix_unitaire decimal(10,2) not null,
    quantite int not null,
    quantite_restante int not null,
    date_creation datetime default current_timestamp,
    foreign key (type_besoin_id) references type_besoin(id),
    foreign key (ville_id) references villes(id)
);


create table donateurs (
    id int auto_increment primary key,
    nom varchar(255) not null,
    prenom varchar(255) not null,
    email varchar(255) not null unique,
    telephone varchar(20)
);

create table dons (
    id int auto_increment primary key,
    donateur_id int,
    type_besoin_id int,
    designation varchar(255),
    quantite int,
    montant decimal(10,2),
    date_don datetime default current_timestamp,
    foreign key (donateur_id) references donateurs(id),
    foreign key (type_besoin_id) references type_besoin(id)
);


create table attributions (
    id int auto_increment primary key,
    besoin_id int,
    donateur_id int,
    date_attribution datetime default current_timestamp,
    foreign key (besoin_id) references besoin(id),
    foreign key (donateur_id) references donateurs(id)
);

create table dispatch (
    id int auto_increment primary key,
    don_id int,
    besoin_id int,
    quantite_attribuee int,
    montant_attribue decimal(10,2),
    date_dispatch datetime default current_timestamp,
    foreign key (don_id) references dons(id),
    foreign key (besoin_id) references besoin(id)
);


