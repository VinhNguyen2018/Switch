CREATE DATABASE switch;

USE switch;

CREATE TABLE salle (
  id_salle int(3) NOT NULL AUTO_INCREMENT,
  titre varchar(200) NOT NULL,
  description text NOT NULL,
  photo varchar(200) NOT NULL,
  pays varchar(20) NOT NULL,
  ville varchar(20) NOT NULL,
  adresse varchar(50) NOT NULL,
  cp varchar(5) NOT NULL,
  capacite int(3) NOT NULL,
  categorie enum('réunion','bureau','formation') NOT NULL,
  PRIMARY KEY (id_salle)
) ENGINE=InnoDB;
-- bdd pour localhost mamp mac
INSERT INTO salle(id_salle, titre, description, photo, pays, ville, adresse, cp, capacite, categorie) VALUES
  (1,'Paris' ,'Salle de réunion dans les bureau de paris, grande capacité, vous pourriez ensuite ramener vos dj et faire des battle de breakdance.' ,'http://localhost:8888/cours_ifocop_php/Switch/photo/paris.jpg' ,'France' ,'Paris' , '30 rue Champetre' , 75009 , 50 ,'réunion' ),
  (2,'Francfort' ,'Bureau à Francfort, petite capacité, pour une ambiance privative,parfait pour organiser un petit tournoi de poker.' ,'http://localhost:8888/cours_ifocop_php/Switch/photo/francfort.jpg' ,'Allemagne' ,'Francfort' ,'30 Boulevard Saucisse' ,88888 ,10 ,'bureau' ),
  (3,'Milan' , 'Salle de formation à Milan, capacité moyenne pour faire des team building, ou faire une session de salsa pour coller la petite.' ,'http://localhost:8888/cours_ifocop_php/Switch/photo/milan.jpg' ,'Italie' ,'Milan' ,'30 avenue de l\'Escalope' ,99999 ,25 ,'formation');

CREATE TABLE membre (
  id_membre int(3) NOT NULL auto_increment,
  pseudo varchar(20) NOT NULL,
  mdp varchar(60) NOT NULL,
  nom varchar(20) NOT NULL,
  prenom varchar(20) NOT NULL,
  email varchar(50) NOT NULL,
  civilite enum('m','f') NOT NULL,
  statut int(1) NOT NULL,
  date_enregistrement datetime NOT NULL,
  PRIMARY KEY (id_membre),
  UNIQUE KEY pseudo (pseudo)
) ENGINE=InnoDB;

INSERT INTO membre (id_membre, pseudo, mdp, nom, prenom, email, civilite, statut, date_enregistrement) VALUES
(1, 'test', 'test', 'test', 'test', 'test@site.fr', 'm', 0, NOW()),
(2, 'admin', 'admin', 'admin', 'admin', 'admin@site.fr', 'f', 1, NOW());

CREATE TABLE produit (
  id_produit int(3) NOT NULL auto_increment,
  id_salle int(3) NOT NULL,
  date_arrivee datetime NOT NULL,
  date_depart datetime NOT NULL,
  prix int(3) NOT NULL,
  etat enum('libre','reservation') NOT NULL,
  PRIMARY KEY  (id_produit),
  KEY id_salle (id_salle)
) ENGINE=InnoDB;

CREATE TABLE commande (
  id_commande int(3) NOT NULL auto_increment,
  id_membre int(3) NOT NULL,
  id_produit int(3) NOT NULL,
  date_enregistrement datetime NOT NULL,
  PRIMARY KEY  (id_commande),
  KEY id_membre (id_membre),
  KEY id_produit (id_produit)
) ENGINE=InnoDB;

CREATE TABLE avis (
  id_avis int(3) NOT NULL auto_increment,
  id_membre int(3) NOT NULL,
  id_salle int(3) NOT NULL,
  commentaire text NOT NULL,
  note int(2) NOT NULL,
  date_enregistrement datetime NOT NULL,
  PRIMARY KEY  (id_avis),
  KEY id_membre (id_membre),
  KEY id_salle (id_salle)
) ENGINE=InnoDB;
