-- Progetto artifex database
create database artifex_buttini;

use artifex_buttini;

create table amministratori(
	email varchar(100) primary key,
	password varchar(100)
);


create table visitatori(
	codice_fiscale varchar(50) primary key,
	nome varchar(100),
	email varchar(100),
	nazionalita varchar(100),
	telefono varchar(50),
	lingua_base varchar(50),
	password varchar(100)
);


create table visite(
 	titolo varchar(100) primary key,
 	durata_media time,
 	luogo varchar(100)
);

create table guide(
 matricola_guida int primary key auto_increment,
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
	foreign key (guida) references guide(matricola_guida)
);

-- funzione come carrello 
create table prenotazioni(
visitatore varchar(50),
id_evento int,
primary key(visitatore, id_evento),
foreign key (visitatore) references eventi(codice_fiscale)
foreign key (id_evento) references eventi(id_evento)
);



create table eventi_visite(
	titolo_visita varchar(100),
	id_evento int,
	data_visita datetime,
	primary key(titolo_visita, id_evento),
	foreign key (titolo_visita) references visite(titolo),
	foreign key (id_evento) references eventi(id_evento)
);


create table lingue(
	nome varchar(50) primary key
);

create table conoscenze(
	livello varchar(50) primary key
);

-- ternaria lingua conoscenza e guida
create table avere(
guida int,
lingua varchar(50),
livello varchar(50),
primary key(guida, lingua, livello),
foreign key (guida) references guide(matricola_guida),
foreign key (lingua) references lingue(nome),
foreign key (livello) references conoscenze(livello)
);

