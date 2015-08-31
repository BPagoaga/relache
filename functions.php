<?php 





	/*=========================
	 * CONNECTION TO DATABASE *
	 =========================*/ 

	function connection(){

		$dsn = '';//server and database
		$user = ''; //login
		$password = ''; //password

		try{

			$bdd = new PDO($dsn, $user, $password, array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8"));

		}catch( Exception $e ){
			die('Erreur : '.$e->getMessage());
		}
		
		return $bdd;

	}





	/*=======================
	 *  ADD EMAIL INTO DB   *
	 =======================*/ 

	function add_email($data){
		
		$bdd = connection();	//rappel de la connection à chaque page
		$email = htmlentities($data['email']);	//on élimine les caractères spéciaux

		$bdd -> exec('
			INSERT INTO emails (
				id,
				email
				)
			VALUES(
				"",
				"'.$email.'"
				)
			');
	}





	/*=========================================
	 * ON VÉRIFIE QU'IL N'Y A PAS DE DOUBLONS *
	 =========================================*/ 

	function email_exist($data) {

		$bdd = connection();
		$email = htmlentities($data['email']);	//on élimine les caractères spéciaux

		$emails = $bdd -> query(
			'SELECT COUNT(email) AS compteur
			FROM emails
			WHERE email = \''.$email.'\''
		);

		$donnees = $emails->fetch();
		$emails->closeCursor();

		if( $donnees['compteur'] === '0' ){
			return true;
		}
	}





	/*=====================
	 * DESINSCRIPTION BDD *
	 =====================*/ 

	function delete_email() {

		$bdd = connection();

	    $delete = $bdd -> exec('
	        DELETE FROM emails
	        WHERE email="'.$_GET['email'].'"
	    ');

        $resultat = $delete -> query($bdd);
        if( $resultat )
            echo '<p>Votre email '.$_GET['email'].' a bien été supprimé de notre base de données</p>';
        else
            echo '<p>Cet email ne figure pas dans notre base de données</p>';

    }





	/*=========================================
	 *      RECUPERER LA PROG DANS LA BDD     *
	 =========================================*/

	function get_prog($i, $j) {		//récupération des évènements pour un mois $i et un jour $j donnés

		$bdd = connection();

		if ($j < 10) {		//les jours sont en "01", etc, mais le compteur j part de 1 => il faut donc ajouter le "0" à la main

			$prog = $bdd->query(
				'SELECT artiste, mois, jour, heure, lieu, image, Type_Art, Pays_Art, Type
				FROM prog
				WHERE mois="0'.$i.'" AND jour="0'.$j.'"
				ORDER BY jour ASC
			');

		}else{		//quand $j est >=10, plus besoin de rajouter le "0"

			$prog = $bdd->query(
				'SELECT artiste, mois, jour, heure, lieu, image, Type_Art, Pays_Art, Type
				FROM prog
				WHERE mois="0'.$i.'" AND jour="'.$j.'"
				ORDER BY jour ASC
			');

		}

		while ($programmation = $prog->fetch()){	//récupération des valeurs dans un tableau
			$return[] = $programmation;
		}

		$prog->closeCursor();

		if (!empty($return)) {
			return $return;		//la fonction retourne le tableau s'il n'est pas vide
		}

	}

	function get_prog_lieu($lieu) {		//récupération des évènements à un lieu donné

		$bdd = connection();

		$prog = $bdd->query(
			'SELECT artiste, mois, jour, heure, lieu
			FROM prog
			WHERE lieu="'.$lieu.'"'
		);

		while( $programmation = $prog->fetch() ){
			$return[] = $programmation;
		}

		$prog->closeCursor();

		if (!empty($return)) {
			return $return;
		}else{
			return "Aucun évènement n'est prévu à cet endroit pour le moment";
		}
		
	}





	/*=========================================
	 *   RECUPERER LES ARTISTES DANS LA BDD   *
	 =========================================*/

	function get_artiste($art){		//récupération de toutes les infos d'un artiste lorsqu'on arrive sur la page artiste via la prog

		$bdd = connection();	//rappel de la connection à chaque page

		$artiste = $bdd->query(
		 	'SELECT artiste, Desc_Art, image, Video, Site, Facebook, Instagram, Tweeter, Soundcloud, Youtube, mois, jour, lieu, evenement
			FROM prog
			WHERE artiste="'.$art.'"'		 	
		);

		$return = $artiste->fetch();
		$artiste->closeCursor();

		return $return;
	}

	function get_events($type) {

		$bdd = connection();	//rappel de la connection à chaque page

		$events = $bdd->query(
			'SELECT Type, mois, jour, lieu, image, Desc_Art, evenement
			FROM prog
			WHERE Type="'.$type.'"
		');

		while ($event = $events->fetch()){
			$return[] = $event;
		}

		$events->closeCursor();

		return $return;

	}





	/*=========================================
	 *    RECUPERER LES LIEUX DANS LA BDD     *
	 =========================================*/

	function get_lieu($lieu){	//récupération d'un lieu précis

		$bdd = connection();	//rappel de la connection à chaque page

		$lieu = $bdd->query(
		 	'SELECT lieu, description
			FROM lieux
			WHERE lieu="'.$lieu.'"'		 	
		);

		$return = $lieu->fetch();
		$lieu->closeCursor();

		return $return;
	}

	function get_lieux(){	//récupération de tous les lieux, le tableau obtenu à la fin sera retourné et éclaté dans un foreach pour créer dynamiquement tous les boutons de filtre par lieu
		$bdd = connection();	//rappel de la connection à chaque page

		$lieu = $bdd->query(
		 	'SELECT lieu, description
			FROM lieux'		 	
		);

		while ($lieux = $lieu->fetch()){
			$return[] = $lieux;
		}

		$lieu->closeCursor();

		return $return;
	}





	/*=========================================
	 *    RECUPERER LES ARTICLES DANS WP      *
	 =========================================*/

	function get_articles(){	//récupération des articles du blog pour les afficher sur la page d'accueil
		$bdd = connection();

		$articles = $bdd->query(
			'SELECT ID, post_title, post_author, guid, post_date, post_content, post_status, post_excerpt
			FROM wor1107_posts
			WHERE post_status= "publish"
			ORDER BY post_date DESC
			LIMIT 3'
			
		);

		while ($article = $articles->fetch()) {
			$return[] = $article;
		}

		$articles->closeCursor();

		return $return;
	}

	function get_thumbnail($ID){	//récupération du thumbnail, the_thumbnail n'est pas utilisable car index.php n'est pas dans le wordpress
	//$ID correspond à l'id du post + 1, soit la ligne contenant le lien vers l'image

		$bdd = connection();

		$thumbnails = $bdd->query(
			'SELECT guid
			FROM wor1107_posts
			WHERE post_parent= "'.$ID.'" AND post_type= "attachment"		
		');

		$return = $thumbnails->fetch();
		$thumbnails->closeCursor();

		return $return;

	}

	function get_author($ID) {		//récupération de l'auteur : "nicename" de la table users, où ID = post_author de la table posts

		$bdd = connection();

		$authors = $bdd->query(
			'SELECT display_name
			FROM wor1107_users
			WHERE ID= "'.$ID.'"	
		');

		$return = $authors->fetch();
		$authors->closeCursor();

		return $return;

	}





	/*=========================================
	 *     RECUPERER LE DERNIER MOT STR       *
	 =========================================*/

	function recup_dernier_mot($str) {	//fonction pour découper une chaîne et récupérer le dernier mot

		$str = htmlentities($str); 

		$apostrophe = strrpos($str, "'");

		if ($apostrophe) {	//test de la présence d'un apostrophe

			$tab = explode("'", $str); //on explose ce nom dans un tableau à chaque apostrophe

		}else{	//si pas d'apostrophe

			$tab = explode(" ", $str); //on explose ce nom dans un tableau à chaque espace
			
		}

		$dernier_mot = $tab[count($tab)-1];
		return $dernier_mot;
	}

	function recup_premier_mot($str) {	//fonction pour découper une chaîne et récupérer le premier mot

		$str = htmlentities($str); 
		$tab = explode(" ", $str); //on explose ce nom dans un tableau à chaque espace

		if	(count($tab > 0)) {

			$premier_mot = $tab[0];

			if ($premier_mot === "Concert") {
				return $premier_mot;
			}else{
				return $str;
			}			
		}
	}





	/*=========================================
	 *         RECUPERER LA GALERIE           *
	 =========================================*/
	
	function get_galerie() {	//récupération de la galerie (no shit sherlock !)

		$bdd = connection();

		$galerie = $bdd->query(
			'SELECT Type, Titre, Legende, Auteur, Copyright, Lien
			FROM galerie
		');

		while ($photo = $galerie->fetch()) {
			$return[] = $photo;
		}

		$galerie->closeCursor();

		if (!empty($return)) {

			return $return;

		}else{
			return "La galerie est vide";
		}
	}





	/*=========================================
	 *    PARTENAIRES ORDERED BY TYPE         *
	 =========================================*/

	function get_partenaires($type) {

		$bdd = connection();

		$partenaires = $bdd -> query (
			'SELECT lien, Logo, Type
			FROM partenaires
			WHERE Type = "'.$type.'"
			');

		while ($partenaire = $partenaires -> fetch()){
			$return[] = $partenaire;
		}

		$partenaires -> closeCursor();

		if (!empty($return)) {

			echo '<h3>'.$type.'</h3>';

			return $return;

		}else{
			return "Nous n'avons pas de partenaires pour le moment";
		}
	}

?>
