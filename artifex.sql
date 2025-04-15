-- Progetto artifex database
create database artifex_buttini;

use artifex_buttini;

create table amministratori(
	username varchar(100) primary key,
	email varchar(100),
	password varchar(100)
);


create table visitatori(
	id_visitatore int primary key auto_increment,
	nome varchar(100),
	email varchar(100),
	nazionalita varchar(100),
	telefono varchar(50),
	lingua_base varchar(50),
	password varchar(100)
);


create table visite(
	id_visita int primary key auto_increment,
 	titolo varchar(100),
 	durata_media time,
 	luogo varchar(100)
);

create table guide(
id_guida int primary key auto_increment,
 nome varchar(100),
 cognome varchar(100),
 data_nascita date,
 luogo_nascita varchar(100)
);



create table eventi(
	id_evento int primary key auto_increment,
	prezzo double,
	min_persone int,
	max_persone int,
	guida int,
	foreign key (guida) references guide(id_guida)
);


-- funzione come carrello 
create table prenotazioni(
id_visitatore int,
id_evento int,
primary key(id_visitatore, id_evento),
foreign key (id_visitatore) references visitatori(id_visitatore),
foreign key (id_evento) references eventi(id_evento)
);



create table eventi_visite(
	id_visita int,
	id_evento int,
	data_visita datetime,
	primary key(id_visita, id_evento),
	foreign key (id_visita) references visite(id_visita),
	foreign key (id_evento) references eventi(id_evento)
);


create table lingue(
	id_lingua int primary key auto_increment,
	nome varchar(50) 
);

create table conoscenze(
	id_conoscenza int primary key auto_increment,
	livello varchar(50)
);

-- ternaria lingua conoscenza e guida
create table avere(
id_guida int,
id_lingua int,
id_conoscenza int,
primary key(id_guida, id_lingua, id_conoscenza),
foreign key (id_guida) references guide(id_guida),
foreign key (id_lingua) references lingue(id_lingua),
foreign key (id_conoscenza) references conoscenze(id_conoscenza)
);



