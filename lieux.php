<?php 

include('functions.php');
include('header.php');

?>

	<main class="lieux row" >

        <div class="row">

            <nav class="ariane col-offset-1 col-10">

                <ol itemscope itemtype="http://schema.org/BreadcrumbList">

                  <li itemprop="itemListElement" itemscope
                      itemtype="http://schema.org/ListItem">

                    <a itemprop="item" href="index.php">
                      <span itemprop="name" class="fa fa-home"></span>
                    </a>

                    <meta itemprop="position" content="1" />

                  </li>
                › 
                  <li itemprop="itemListElement" itemscope
                      itemtype="http://schema.org/ListItem">
                    
                      <span itemprop="name">Édition 2015</span>
                    

                    <meta itemprop="position" content="2" />

                  </li>
                ›
                  <li itemprop="itemListElement" itemscope
                      itemtype="http://schema.org/ListItem">

                    <a itemprop="item" href="lieux.php">
                      <span itemprop="name">Lieux</span>
                    </a>

                    <meta itemprop="position" content="3" />

                  </li>

                </ol>

             </nav>

    		<h1 class="col-10 col-offset-1">Où trouver Relache 2015 ?</h1>

            <article class="article-description col-10 col-offset-1 col-l-4 col-l-offset-1">

            <!-- CAS 1 : AUCUN LIEU N'EST RENTRé-->

            <?php //si aucun lieu n'est rentré : texte du concept, en dur
            	if( empty($_GET['lieu']) ) {
    				?>

                <h2>Relache : un évènement itinérant</h2>   

                <p>
                    Relache a pour volonté de faire découvrir et redécouvrir Bordeaux à ses festivaliers, qu'ils soient bordelais ou non. En sélectionnant un lieu, vous aurez ainsi accès à une courte présentation de chaque lieu, accompagné d'une photo et de son emplacement sur la carte.
                </p>

                <!--<img src="img/lieux/<?=$_GET['lieu']?>.jpg" alt="Mairie de Bordeaux Relache 2015"> -->


            <!-- CAS 2 : UN LIEU A ETE SELECTIONNE-->

                <?php }else{
               		$lieu=get_lieu($_GET['lieu']);
               	?>

               	<h2><?= $lieu['lieu']?></h2>    <!--php : get_lieux lieu-->

                <img src="img/lieux/<?=$lieu['lieu']?>.jpg" alt="Image Lieux Bordeaux Relache 2015"> <!--php : get_lieux img-->

                <p> <!--php : get_lieux description-->
                    <?= $lieu['description']?>
                </p>

               <!-- <h3>Comment se rendre à <?= $lieu['lieu']?> ?</h3>   

                <ul>   
                    <li>Tram</li>
                    <li>Bus</li>
                    <li>Parking-relais</li>
                    <li>VCub</li>
                </ul>-->

                <h3>Relache 2015 à <?= $lieu['lieu']?> :</h3>     <!--php : get_lieux lieu-->

                <ul>    <!--php : get_events lieu-->

    	            <?php $prog=get_prog_lieu($_GET['lieu']);

                    if ($prog == "Aucun évènement n'est prévu à cet endroit pour le moment") {
                        echo '<p>'.$prog.'</p>';
                    }else {

        		        foreach ($prog as $lieu_prog): ?>

        		            <li> <a href="artiste.php?artiste=<?=$lieu_prog['artiste']?>"> <?= ucfirst($lieu_prog['artiste']) ?> </a> le  <a href="programmation.php"> <?=$lieu_prog['jour']?> / <?=$lieu_prog['mois']?> </a> </li>
        		            
        		        <?php endforeach; 

                    } ?>
                    

                </ul>

            <?php } ?>

            </article>

            <article class="article-map col-10 col-offset-1 col-l-5 col-l-offset-1">
                
                <div class="liens">

                    <h3>Sélectionnez un lieu pour afficher sa localisation :</h3>

    	            <?php $lieux=get_lieux(); 

    	            foreach ($lieux as $lieu): 

                        if (get_prog_lieu($lieu['lieu']) !== "Aucun évènement n'est prévu à cet endroit pour le moment") {

                    ?>

    	            	<a href="?lieu=<?=$lieu['lieu']?>#map-canvas"><?=$lieu['lieu']?></a>
    	            
    	            <?php 

                        }

                    endforeach ?>

                </div>

                <div id="map-canvas"></div>

            </article>

            <div id="coordonnees">
            <?php 
                
                $bdd = connection();

                    if( isset($_GET['lieu']) ) {    //si un lieu est sélectionné, on lui donne ses coordonnées

                        $lieu = $bdd->query(
                            'SELECT Lat, Lng
                            FROM lieux
                            WHERE lieu ="'.$_GET['lieu'].'"
                        ');

                        $return = $lieu->fetch();
                        $lieu->closeCursor();;

                        ?>

                        <div id="lat"><?=$return[0];?></div>
                        <div id="lng"><?=$return[1];?></div>

                        
                        <?php
                     
                                   
                    }else{      //si pas de lieu sélectionné, on donne par défaut les coordonnées de la mairie de bordeaux

                        ?>

                        <div id="lat">44.838086</div>
                        <div id="lng">-0.579626</div>

                        <?php

                    }  

            ?> 
            </div>

            <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyA1fD1UeEesqm8Gy3GgI2SbV2zNlayZkZY" type="text/javascript"></script>

            <!--<script type="text/javascript" src="http://maps.google.com/maps/api/js?language=fr-FR"></script>-->

            <script type="text/javascript">
                function initialize() {
                    map = new google.maps.Map(document.getElementById("map-canvas"), {
                        zoom: 15,
                        center: new google.maps.LatLng(44.834417, -0.565051),
                        mapTypeId: google.maps.MapTypeId.ROADMAP
                    });
                }
            </script>

            <script>

                function initialize() {

                    var data = "<?=$_GET['lieu']?>";

                    var lat = $("#lat").html();
                    var lng = $("#lng").html();

                    var myLatLng = new google.maps.LatLng(lat, lng);

                    /*$.ajax({
                        type: "GET",
                        url: "marker.php",
                        data: data,
                        success: function(server_response){

                            $("#coordonnees").html(server_response).show();
                            var coordonnees = $("#coordonnees").html();
                            alert(coordonnees);

                            var myLatlng = new google.maps.LatLng(coordonnees);

                        }
                    });*/
                
                    

                    var mapOptions = {
                    zoom: 15,
                    center: myLatLng
                    };

                    var map = new google.maps.Map(document.getElementById('map-canvas'),
                      mapOptions);

                    var contentString = '<h2>'+data+'</h2>';

                    var infowindow = new google.maps.InfoWindow({
                        content: contentString
                    });

                    var marker = new google.maps.Marker({
                        map: map,
                        animation: google.maps.Animation.DROP,
                        position: myLatLng,
                        icon: 'img/icons/flag.png'                
                    });

                    google.maps.event.addListener(marker, 'click', function() { //ouverture de la fenêtre d'info au clic sur un marqueur
                        infowindow.open(map,marker);
                    });

                    var transitLayer = new google.maps.TransitLayer();  //lignes de tram
                    transitLayer.setMap(map);

                    var trafficLayer = new google.maps.TrafficLayer();  //bouchons sur la route
                    trafficLayer.setMap(map);

                }

                function loadScript() {
                    var script = document.createElement('script');
                    script.type = 'text/javascript';
                    script.src = 'https://maps.googleapis.com/maps/api/js?v=3.exp' +
                      '&signed_in=true&callback=initialize';
                    document.body.appendChild(script);
                }

                window.onload = loadScript();

            </script>

        </div> <!--fin row-->
        
    </main>

<?php include('footer.php'); ?>
