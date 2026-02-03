<?php

namespace App\Controllers;

use CodeIgniter\Controller;
use Config\Database;

class JeuTest extends Controller
{
    public function generer()
    {
        $db = Database::connect();
        $db->query("SET @user_id = 1");

        $db->query("INSERT INTO UTILISATEURS (LOGIN, MDP, DROIT) VALUES
        ('admin', '\$2y\$10\$LQxaBBFyjfFbNT3D4CP2iuGjr0xMlUVZVfUNUdixx9AuuaEXDmb9i', 'rssi'),
        ('paul',  '\$2y\$10\$LQxaBBFyjfFbNT3D4CP2iuGjr0xMlUVZVfUNUdixx9AuuaEXDmb9i', 'utilisateur'),
        ('marie', '\$2y\$10\$LQxaBBFyjfFbNT3D4CP2iuGjr0xMlUVZVfUNUdixx9AuuaEXDmb9i', 'utilisateur')");

        $db->query("INSERT INTO TRAITEMENT (REF, NOM, DATECREATION, DATEMAJ, TRANSFERTHHORSUE) VALUES
        ('TRT001', 'Gestion des salariés', '2024-01-10', '2024-01-10', 0),
        ('TRT002', 'Suivi des tickets clients', '2024-02-15', '2024-02-15', 0),
        ('TRT003', 'Journalisation des accès', '2024-03-01', '2024-03-01', 1),
        ('TRT004', 'Campagnes emailing', '2024-03-20', '2024-03-20', 0),
        ('TRT005', 'Gestion des badges', '2024-04-05', '2024-04-05', 0)");

        $db->query("INSERT INTO FINALITE (REF, LIBELLE, ESTPRINCIPAL) VALUES
        ('TRT001', 'Gestion RH', 1),
        ('TRT002', 'Suivi des clients', 1),
        ('TRT003', 'Sécurité informatique', 1),
        ('TRT004', 'Marketing', 0),
        ('TRT005', 'Gestion des accès', 1)");

        $db->query("INSERT INTO CATEGDCP (LIBELLE) VALUES
        ('Etat civil, identité, données d''identification, images...'),
        ('Vie personnelle'),
        ('Information économique et financière'),
        ('Données de connexion'),
        ('Données de localisation'),
        ('Numéro de Sécurité Sociale')");

        $db->query("INSERT INTO CATEGDCPSENSIBLE (LIBELLE) VALUES
        ('Origine raciale ou ethnique'),
        ('Opinions politiques'),
        ('Convictions religieuses'),
        ('Appartenance syndicale'),
        ('Données génétiques'),
        ('Données biométriques'),
        ('Données de santé'),
        ('Vie sexuelle ou orientation sexuelle'),
        ('Condamnations pénales ou infractions')");

        $db->query("INSERT INTO LISTEDCP (REF, IDCATEG, DESCRIPTION, DUREECONSERVATION) VALUES
        ('TRT001', 1, 'Données RH', '5 ans'),
        ('TRT001', 3, 'Infos financières', '10 ans'),
        ('TRT002', 4, 'Logs clients', '1 an'),
        ('TRT003', 5, 'Logs de sécurité', '1 an'),
        ('TRT004', 2, 'Marketing', '3 ans'),
        ('TRT005', 1, 'Badges employés', '5 ans')");

        $db->query("INSERT INTO LISTEDCPSENSIBLE (REF, IDCATEG, DESCRIPTION, DUREECONSERVATION) VALUES
        ('TRT001', 7, 'Donnée sensible RH', '5 ans'),
        ('TRT003', 6, 'Donnée sensible sécurité', '1 an'),
        ('TRT004', 2, 'Donnée sensible marketing', '3 ans')");

        $db->query("INSERT INTO CATEGPERSONNECONCERNE (LIBELLE) VALUES
        ('Autres'),
        ('Candidats'),
        ('Clients'),
        ('Fournisseurs'),
        ('Prestataires'),
        ('Prospects'),
        ('Salariés'),
        ('Services Internes')");

        $db->query("INSERT INTO TYPEACTEUR (LIBELLE) VALUES
        ('Responsable du traitement'),
        ('DPO'),
        ('Société du DPO'),
        ('Représentant'),
        ('Responsables conjoints')");

        $db->query("INSERT INTO TYPEMESURESECURITE (LIBELLE) VALUES
        ('Autres mesures'),
        ('Chiffrement'),
        ('Contrôle d''accès'),
        ('Contrôle des sous-traitants'),
        ('Protection des logiciels'),
        ('Traçabilité'),
        ('Sauvegarde')");

        $db->query("INSERT INTO TYPEDESTINATAIRE (LIBELLE) VALUES
        ('Pays tiers / organisations internationales'),
        ('Partenaires'),
        ('Services internes'),
        ('Sous-traitants')");

        $db->query("INSERT INTO TYPEDEGARANTIE (LIBELLE) VALUES
        ('Certification'),
        ('Clauses contractuelles types'),
        ('Code de conduite'),
        ('Dérogation art 49'),
        ('Pays adéquat'),
        ('Privacy Shield'),
        ('BCR')");

        $db->query("INSERT INTO PAYS (NOMPAYS) VALUES
        ('Andorre'), ('Argentine'), ('Canada'), ('Etats-Unis'), ('Guernesey'),
        ('Ile de Man'), ('Iles FEROE'), ('Israël'), ('Jersey'), ('Nouvelle-Zélande'),
        ('Suisse'), ('Uruguay'), ('Afghanistan'), ('Afrique du Sud'), ('Albanie'),
        ('Algérie'), ('Angola'), ('Arabie saoudite'), ('Arménie'), ('Australie'),
        ('Azerbaïdjan'), ('Bahamas'), ('Bahreïn'), ('Bangladesh'), ('Barbade'),
        ('Belize'), ('Bénin'), ('Bhoutan'), ('Biélorussie'), ('Birmanie'),
        ('Bolivie'), ('Botswana'), ('Brésil'), ('Brunei'), ('Burkina Faso'),
        ('Burundi'), ('Cambodge'), ('Cameroun'), ('Cap-Vert'), ('Chili'),
        ('Chine'), ('Colombie'), ('Comores'), ('Congo'), ('Corée du Nord'),
        ('Corée du Sud'), ('Costa Rica'), ('Côte d''Ivoire'), ('Cuba'),
        ('Djibouti'), ('Egypte'), ('Emirats arabes unis'), ('Equateur'),
        ('Erythrée'), ('Éthiopie'), ('Fidji'), ('Gabon'), ('Gambie'),
        ('Géorgie'), ('Ghana'), ('Grenade'), ('Guatemala'), ('Guinée'),
        ('Guinée équatoriale'), ('Guinée-Bissau'), ('Guyana'), ('Haïti'),
        ('Honduras'), ('Hong-Kong'), ('Inde'), ('Indonésie'), ('Iraq'),
        ('Iran'), ('Jamaïque'), ('Japon'), ('Jordanie'), ('Kazakhstan'),
        ('Kenya'), ('Kirghizstan'), ('Kiribati'), ('Kosovo'), ('Koweït'),
        ('Laos'), ('Lesotho'), ('Liban'), ('Liberia'), ('Libye'),
        ('Macédoine'), ('Madagascar'), ('Malaisie'), ('Malawi'), ('Maldives'),
        ('Mali'), ('Maroc'), ('Maurice'), ('Mauritanie'), ('Mexique'),
        ('Micronésie'), ('Moldavie'), ('Monaco'), ('Mongolie'), ('Monténégro'),
        ('Mozambique'), ('Namibie'), ('Nauru'), ('Népal'), ('Nicaragua'),
        ('Niger'), ('Nigeria'), ('Oman'), ('Ouganda'), ('Ouzbékistan'),
        ('Pakistan'), ('Palaos'), ('Palestine'), ('Panama'),
        ('Papouasie-Nouvelle-Guinée'), ('Paraguay'), ('Pérou'),
        ('Philippines'), ('Porto Rico'), ('Qatar'), ('République Dominicaine'),
        ('Russie'), ('Rwanda'), ('Sénégal'), ('Serbie'), ('Seychelles'),
        ('Sierra Leone'), ('Singapour'), ('Somalie'), ('Soudan'),
        ('Soudan du Sud'), ('Sri Lanka'), ('Suriname'), ('Swaziland'),
        ('Syrie'), ('Tadjikistan'), ('Taiwan'), ('Tanzanie'), ('Tchad'),
        ('Thaïlande'), ('Timor oriental'), ('Togo'), ('Tonga'),
        ('Trinité-et-Tobago'), ('Tunisie'), ('Turkménistan'), ('Turquie'),
        ('Tuvalu'), ('Ukraine'), ('Vanuatu'), ('Venezuela'), ('Viêt Nam'),
        ('Yémen'), ('Zambie'), ('Zimbabwe')");

        return "Jeu de test créé avec succès.";
    }
}