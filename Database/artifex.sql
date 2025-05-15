-- Progetto artifex database
create database artifex_buttini;

use artifex_buttini;

create table amministratori(
                               username varchar(100) primary key,
                               email varchar(100),
                               password varchar(100)
);

create table lingue(
                       id_lingua int primary key auto_increment,
                       nome varchar(50)
);

create table visitatori(
                           id_visitatore int primary key auto_increment,
                           nome varchar(100),
                           email varchar(100),
                           nazionalita varchar(100),
                           telefono varchar(50),
                           lingua_base int,
                           password varchar(100),
                           foreign key (lingua_base) references lingue(id_lingua)
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
                      luogo_nascita varchar(100),
                      titolo_studio varchar(100)
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
                             pagata boolean default false,
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



INSERT INTO lingue (nome) VALUES
                              ('Italiano'),
                              ('Inglese'),
                              ('Francese'),
                              ('Spagnolo'),
                              ('Tedesco'),
                              ('Cinese'),
                              ('Giapponese'),
                              ('Russo');



INSERT INTO conoscenze (livello) VALUES
                                     ('Base'),
                                     ('Intermedio'),
                                     ('Avanzato'),
                                     ('Madrelingua');


-- 1) VISITE GUIDATE
INSERT INTO visite (titolo, durata_media, luogo) VALUES
                                                     ('Musei Vaticani e Cappella Sistina', '02:30:00', 'Città del Vaticano'),
                                                     ('Sito archeologico di Pompei',       '03:00:00', 'Pompei'),
                                                     ('Galleria degli Uffizi',             '02:00:00', 'Firenze'),
                                                     ('Colosseo e Foro Romano',            '02:00:00', 'Roma'),
                                                     ('Reggia di Caserta',                 '02:45:00', 'Caserta'),
                                                     ('Basilica di San Marco e Palazzo Ducale', '02:15:00', 'Venezia');

-- 2) GUIDE
INSERT INTO guide (nome, cognome, data_nascita, luogo_nascita, titolo_studio) VALUES
                                                                                  ('Marco',  'Rossi',  '1980-05-10', 'Milano',  'Laurea in Storia dell’Arte'),
                                                                                  ('Anna',   'Bianchi','1975-09-22', 'Roma',    'Laurea in Lettere'),
                                                                                  ('Luca',   'Verdi',  '1990-12-05', 'Firenze', 'Diploma di Scuola Superiore'),
                                                                                  ('Giulia', 'Neri',   '1985-03-15', 'Napoli',  'Laurea in Archeologia');


-- 3) RELAZIONE GUIDA ↔️ LINGUA ↔️ CONOSCENZA
-- (lingue e conoscenze sono già state inserite con id 1–8 e 1–4)
INSERT INTO avere (id_guida, id_lingua, id_conoscenza) VALUES
                                                           -- Marco Rossi parla Italiano (madre lingua) e Inglese (avanzato)
                                                           (1, 1, 4), (1, 2, 3),
                                                           -- Anna Bianchi: Italiano (madre), Francese (intermedio), Inglese (base)
                                                           (2, 1, 4), (2, 3, 2), (2, 2, 1),
                                                           -- Luca Verdi: Italiano (madre), Inglese (madre), Spagnolo (intermedio)
                                                           (3, 1, 4), (3, 2, 4), (3, 4, 2),
                                                           -- Giulia Neri: Italiano (madre), Inglese (intermedio), Tedesco (base)
                                                           (4, 1, 4), (4, 2, 2), (4, 5, 1);

-- 4) EVENTI (prezzo, min/max partecipanti, guida)
INSERT INTO eventi (prezzo, min_persone, max_persone, guida) VALUES
                                                                 (35.00, 5, 20, 1),  -- evento condotto da Marco Rossi
                                                                 (30.00, 4, 15, 2),  -- Anna Bianchi
                                                                 (25.00, 6, 18, 3),  -- Luca Verdi
                                                                 (40.00, 5, 12, 4),  -- Giulia Neri
                                                                 (38.00, 5, 20, 1),
                                                                 (32.00, 3, 14, 2);  -- un secondo evento con Marco

-- 5) PROGRAMMAZIONE EVENTI ‒ associazione visita ↔ evento con data/ora
INSERT INTO eventi_visite (id_visita, id_evento, data_visita) VALUES
                                                                  (1, 1, '2025-05-10 10:00:00'),
                                                                  (2, 2, '2025-05-12 09:30:00'),
                                                                  (3, 3, '2025-05-13 11:00:00'),
                                                                  (4, 4, '2025-05-14 15:00:00'),
                                                                  (5, 5, '2025-05-15 10:00:00'),
                                                                  (6,6, '2025-11-12 16:00:00');




-- Seleziona tutti gli amministratori
SELECT * FROM amministratori;

-- Seleziona tutti i visitatori
SELECT * FROM visitatori;

delete from visitatori;

-- Seleziona tutte le visite
SELECT * FROM visite;

-- Seleziona tutte le guide
SELECT * FROM guide;

-- Seleziona tutti gli eventi
SELECT * FROM eventi;

-- Seleziona tutte le prenotazioni (relazione tra visitatori e eventi)
SELECT * FROM prenotazioni;

delete from prenotazioni;

-- Seleziona tutte le associazioni tra eventi e visite
SELECT * FROM eventi_visite;

-- Seleziona tutte le lingue
SELECT * FROM lingue;

-- Seleziona tutti i livelli di conoscenza
SELECT * FROM conoscenze;

-- Seleziona tutte le relazioni guida-lingua-conoscenza
SELECT * FROM avere;



SELECT p.id_evento
FROM prenotazioni p
         LEFT JOIN eventi_visite ev ON p.id_evento = ev.id_evento
WHERE ev.id_evento IS NULL;



SELECT e.*, ev.data_visita, v.titolo AS titolo_visita, v.luogo, v.durata_media, g.nome AS guida_nome, g.cognome AS guida_cognome
FROM eventi e
         JOIN eventi_visite ev ON e.id_evento=ev.id_evento
         JOIN visite v ON ev.id_visita=v.id_visita
         JOIN guide g ON e.guida=g.id_guida
WHERE e.id_evento = :id


SELECT *
FROM prenotazioni
WHERE id_visitatore = 2 AND pagata = 1;

SELECT
    vi.data_visita,
    vt.titolo,
    vt.luogo
FROM prenotazioni p
         JOIN eventi_visite vi ON p.id_evento = vi.id_evento
         JOIN visite vt ON vi.id_visita = vt.id_visita
WHERE p.id_visitatore = 2
  AND p.pagata = 1
ORDER BY vi.data_visita



SELECT vi.data_visita, vt.titolo, vt.luogo
FROM prenotazioni p
         JOIN eventi_visite vi ON p.id_evento = vi.id_evento
         JOIN visite vt ON vi.id_visita = vt.id_visita
WHERE p.id_visitatore = :id AND p.pagata = 1
ORDER BY vi.data_visita;