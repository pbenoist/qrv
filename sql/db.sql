--alter table em_animal add code_postal char(10);
--alter table em_animal add cpt_modif_img int default 0;
--
alter table em_animal add date_naissance char(15);
alter table em_animal add espece int default 0;
alter table em_animal add race char(30);
--
create table em_trace (info char(127));
--
-- Commentaire sur l'etat d'un enr.
--
-- 0 = En cours de création (l'utilisateur n'a pas validé son inscription 
-- (validation par clic sur lien dans l'email de confirmation d'ouverture de compte)
-- les enregistrements non validés sont gardés 24h. Ensuite, ils sont détruits.
--
-- 1 = Enr validé
--
drop table em_animal;
--
create table em_animal (
id_animal int not null auto_increment , 
nuserie char(15), 
codepin char(7),
email_proprio char(50), 
password_proprio char(50), 
nom_proprio char(50), 
prenom_proprio char(50), 
tel_proprio char(20), 
etat int default 0,
nom_animal char(20), 
UID char(20),
code_postal char(10),
cpt_modif_img int default 0,
datCreat TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
primary key (id_animal) );
--
--
insert into em_animal (id_animal, nuserie, codepin, nom_animal, email_proprio, password_proprio, nom_proprio, prenom_proprio, tel_proprio) values (1, '256012345678901', '001', 'GAIA',    'pbenoist@emergensoft.fr'    , 'aze', 'BENOIST', 'Philippe',   '06 76 64 55 04');
insert into em_animal (id_animal, nuserie, codepin, nom_animal, email_proprio, password_proprio, nom_proprio, prenom_proprio, tel_proprio) values (2, '256012345678902', '002', 'TOUKY',   ''                           , 'qsd', 'BENOIST', 'Fanny',      '06 27 15 67 67');
insert into em_animal (id_animal, nuserie, codepin, nom_animal, email_proprio, password_proprio, nom_proprio, prenom_proprio, tel_proprio) values (3, '256012345678903', '003', 'ITSY',    ''                           , 'wxc', 'BASSIER', 'Joël',       '06 26 98 33 40');
insert into em_animal (id_animal, nuserie, codepin, nom_animal, email_proprio, password_proprio, nom_proprio, prenom_proprio, tel_proprio) values (4, '256012345678904', '004', 'GAUZOR',  ''                           , 'rty', 'HAMMI',   'Sandrine',   '06 82 47 55 65');
insert into em_animal (id_animal, nuserie, codepin, nom_animal, email_proprio, password_proprio, nom_proprio, prenom_proprio, tel_proprio) values (5, '256012345678905', '005', 'TOTOB',   ''                           , 'fgh', 'COSTE',   'Jean',       '06 60 96 75 05');
--
--
--
drop table em_iso_pin;
--
create table em_iso_pin (
id_iso_pin int not null auto_increment , 
nuserie char(15), 
codepin char(7),
primary key (id_iso_pin) );
--
insert into em_iso_pin (nuserie, codepin) values ( '256012345678001', '001');
insert into em_iso_pin (nuserie, codepin) values ( '256012345678100', '100');
--
drop table em_historique;
create table em_historique 
(
id_histo int not null auto_increment , 
datEnr TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
codepin char(7),
browser char(20),
address_ip char(15),
primary key (id_histo)
);

