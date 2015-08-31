<?php 
include('header.php');
include('functions.php');
?>

<main role="main" class="programmation row">

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

		        <a itemprop="item" href="programmation.php">
		          <span itemprop="name">Programmation</span>
		        </a>

		        <meta itemprop="position" content="3" />

		      </li>

		    </ol>

		</nav>

		<h1 class="col-10 col-offset-1">Programmation Relache 2015</h1>

		<aside class="aside-filtre col-10 col-offset-1 col-l-2 row">

				<section class="filtre-mois col-m-6 col-l-12">

					<h3>Choix par mois</h3>

					<ul>
						<li class="selecta"><a href="#juin">Juin</a></li>
						<li><a href="#juillet">Juillet</a></li>
						<li><a href="#aout">Août</a></li>
						<li><a href="#septembre">Septembre</a></li>
					</ul>

				</section>

				<section class="filtre-lieux col-m-6 col-l-12">

					<h3>Choix par lieux</h3>

					<div class="button-group filter-button-group">

						<div class="radio">
							<input type="radio" id="tous" name="lieux" data-filter="*" checked><label for="tous"></label><a href="lieux.php?lieu=Mairie de Bordeaux">Tous</a>
						</div>

						<?php $lieux=get_lieux(); 

			            foreach ($lieux as $lieu): 
			            	
			            	//si il n'y a aucun évènement à cet endroit, le bouton ne s'affiche pas
			            	if (get_prog_lieu($lieu['lieu']) !== "Aucun évènement n'est prévu à cet endroit pour le moment") {

						?>
							
							<div class="radio">
								<input type="radio" id="<?=recup_dernier_mot($lieu['lieu']);?>" name="lieux" data-filter=".<?=recup_dernier_mot($lieu['lieu']);?>"><label for="<?=recup_dernier_mot($lieu['lieu']);?>"></label>
			    	            	<a href="lieux.php?lieu=<?=$lieu['lieu']?>#map-canvas"><?=$lieu['lieu']?></a>
			            	</div>

		    	        <?php 

		    	        	}

		    	        endforeach ?>

					</div>

				</section>

		</aside>

		<article class="article-programmation col-10 col-offset-1 col-l-7 row">

			<?php 

			for ($i = 6; $i < 10; $i++) {

				?>

				<h2 id="<?php

					switch ($i) {
					    case 6:
					        echo "juin";
					        break;
					    case 7:
					        echo "juillet";
					        break;
					    case 8:
					        echo "aout";
					        break;
					    case 9:
					        echo "septembre";
					        break;
					}

					?>">

					<?php

					switch ($i) {
					    case 6:
					        echo "Juin";
					        break;
					    case 7:
					        echo "Juillet";
					        break;
					    case 8:
					        echo "Août";
					        break;
					    case 9:
					        echo "Septembre";
					        break;
					}

					?>

				</h2>

				<div class="row por">

				<?php

					for ($j = 1; $j < 32; $j++) {

					$prog = get_prog($i, $j);

					if (isset($prog)) {

						for ($k = 0; $k < count($prog); $k++) {	//if there are two events of a different type at the same date

						$infos = $prog[$k]; //ligne actuelle

						if ($k > 0) {

							$infos_previous_row = $prog[$k-1];	//previous row if k >= 1

						}else{
							$infos_previous_row = 0;	//if k == 0, previous row == 0
						}

						if ($infos['Type'] != $infos_previous_row['Type'] || $infos_previous_row = 0) {	// we want to display the bloc if : * it is the first row, i.e k = 0, or if the type of the event is not the same


					?>

			<!--BLOC MOIS JOUR EVT LIEU-->

			<div class="evenement col-6 col-m-3 <?= recup_dernier_mot($infos['lieu']); ?>">

				<div class="<?= recup_dernier_mot($infos['Type']); ?>">
					<label>
						<?=$infos['jour']?>.<?=$infos['mois']?> <br>
						<time><?=$infos['heure']?></time> <br>
					</label>
					
					<a href="lieux.php?lieu=<?=$infos['lieu']?>">
						<h2>&mdash;<br><?=$infos['lieu']?><br>&mdash;</h2>
					</a>
					
					<!--lien vers les siestes souls et dancing, pas de lien pour les concerts-->
					<?php if (recup_premier_mot($infos['Type']) == "Concert") : ?>

					<h4><?= recup_premier_mot($infos['Type']) ?></h4>

					<?php else : ?>

					<a href="artiste.php?artiste=<?= $infos['Type'] ?>"><h4><?= $infos['Type'] ?></h4></a>

					<?php endif ?>
					
				</div>
			
			</div>

							<?php foreach ($prog as $programmation): 

							if ($programmation['Type'] != 'Siestes Soul' &&
								$programmation['Type'] != 'Dancing in the Street' &&
								$infos['Type']  != 'Siestes Soul' &&
								$infos['Type'] != 'Dancing in the Street') { 

							?>

							<div class="evenement <?=$programmation['mois']?> <?= recup_dernier_mot($programmation['lieu']); ?> col-6 col-m-3">

								<a href="artiste.php?artiste=<?=$programmation['artiste'] ?>">

									<div class="zone-img calque">
										<img src="<?=$programmation['image']?>" alt="" width="100%">
									</div>

									<label for="">
										<?= $programmation['artiste'] ?>
									</label>

									<p>
										<?= $programmation['Type_Art'] ?> - <?= $programmation['Pays_Art'] ?>
									</p>

								</a>

							</div>

							<?php 

							}	//fin if type=! siestes et dancing

							endforeach;

							} //end of (if there are two events of a different type at the same date )

						} //end of for $k

					} 	//fin if prog définie

				}	//fin for j (jour)

				?> 

				</div> <!--fin row por-->

			<?php 

			}	//fin for i (mois)

			?>

		</article>

	</div> <!-- fin row-->

</main> <!-- fin main -->

<script>$('.por').isotope({
          // options
          itemSelector: '.evenement',
          layoutMode: 'fitRows'
        });</script>

<?php include('footer.php'); ?>
