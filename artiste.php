<?php 

include('functions.php');
include('header.php');

$artiste=get_artiste($_GET['artiste']); //déclaration de la variable $artiste dans laquelle on rentre la ligne de la table 'artistes' ou le champs 'artiste' équivaut au nom de l'artiste passé dans l'url via a href="artiste.php?artiste=..."?>


	<main role="main" class="artiste row">

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

			      ›
			      <li itemprop="itemListElement" itemscope
			          itemtype="http://schema.org/ListItem">

			        <a itemprop="item" href="programmation.php">
			          <span itemprop="name">Artiste</span>
			        </a>

			        <meta itemprop="position" content="4" />

			      </li>

			    </ol>

			</nav>





		<!--Cas 1 : Dancing et Siestes-->

		<?php 

		if ($_GET['artiste'] === "Siestes Soul" || $_GET['artiste'] === "Dancing in the Street") :

			$events=get_events($_GET['artiste']); 

		?>

			<h1 class="col-10 col-offset-1">Les <?= $_GET['artiste']; ?> à Relache 2015</h1>

			<article class="article-artiste col-10 col-l-4 col-offset-1 ">

				<h2>Les <?= $_GET['artiste']; ?></h2>

				<p><?= $artiste['Desc_Art']; ?></p>
				
				<h2>Liste des <?= $_GET['artiste']; ?> à Relache 2015</h2>

				<ul>

				<?php if ($_GET['artiste'] === "Siestes Soul") { // if event Type == Siestes Soul, there is only one fb event, so no need to get the link into the foreach?>

					<?php foreach ($events as $event) : ?>

						<li>
						Le <?= $event['jour']; ?> / <?= $event['mois']; ?> à <a href="lieux.php?lieu=<?= $event['lieu']; ?>"> <?= $event['lieu']; ?></a>

						</li>

					<?php endforeach;

					if ($event['evenement'] !== 0 && !empty($event['evenement'])) { ?>

					<li><a href="<?= $event['evenement'] ?>" class="btn" target="_blank">Retrouvez l'évènement sur facebook !</a></li>

					<br>

					<?php 

					} 

				}else{ ?>

					<?php foreach ($events as $event) : ?>

					<li>
					Le <?= $event['jour']; ?> / <?= $event['mois']; ?> à <a href="lieux.php?lieu=<?= $event['lieu']; ?>"> <?= $event['lieu']; ?></a>

					</li>

					<?php 

					if ($event['evenement'] !== 0 && !empty($event['evenement'])) { ?>

					<li><a href="<?= $event['evenement'] ?>" class="btn" target="_blank">Retrouvez l'évènement sur facebook !</a></li>

					<br>

				<?php } ?>

				<?php endforeach;

				}

				?>

				

				</ul>

			</article>

			<article class="article-video col-10 col-l-5 col-offset-1 ">
				
				<div class="zone-img">
					<img src="<?= $event['image']; ?>" alt="<?= $GET_['artiste']; ?> Relache 2015">
				</div>

			</article>





		<!--Cas 2 : Concerts-->

		<?php else : ?>

		<h1 class="col-10 col-offset-1">Artistes Relache 2015</h1>

			<article class="article-artiste col-10 col-l-4 col-offset-1 ">
				
				<h2><?= $artiste['artiste']; ?></h2>

				<h3>Le <?= $artiste['jour']; ?> / <?= $artiste['mois']; ?> à <a href="lieux.php?lieu=<?= $artiste['lieu']; ?>"> <?= $artiste['lieu']; ?></a></h3>

				<p><?= $artiste['Desc_Art']; ?></p>

				<br>

				<p>


					<?php 

					if( !empty($artiste['Site'])) { ?>

					<a href="<?= $artiste['Site']; ?>" target="_blank">
						<img src="img/icons/link.png" alt="website relache 2015 bordeaux">
					</a> 

					<?php } 

					if( !empty($artiste['Facebook'])) {

					?>

					<a href="<?= $artiste['Facebook']; ?>" target="_blank">
						<img src="img/icons/facebook.png" alt="facebook relache 2015 bordeaux">
					</a> 

					<?php } 

					if( !empty($artiste['Tweeter'])) {

					?>

					<a href="<?= $artiste['Tweeter']; ?>" target="_blank">
						<img src="img/icons/twitter.png" alt="twitter relache 2015 bordeaux">
					</a> 

					<?php } 

					if( !empty($artiste['Instagram'])) {

					?>

					<a href="<?= $artiste['Instagram']; ?>" target="_blank">
						<img src="img/icons/instagram.png" alt="instagram relache 2015 bordeaux">
					</a> 

					<?php } 

					if( !empty($artiste['Youtube'])) {

					?>

					<a href="<?= $artiste['Youtube']; ?>" target="_blank">
						<img src="img/icons/youtube.png" alt="youtube relache 2015 bordeaux">
					</a> 

					<?php } 

					if( !empty($artiste['Soundcloud'])) {

					?>

					<a href="<?= $artiste['Soundcloud']; ?>" target="_blank">
						<img src="img/icons/soundcloud.png" alt="soundcloud relache 2015 bordeaux">
					</a>

					<?php } ?>

				</p>

				<br>

				<?php 

				if ($artiste['evenement'] !== 0 && !empty($artiste['evenement'])) { ?>

				<a href="<?= $artiste['evenement'] ?>" class="btn">Retrouvez l'évènement sur facebook !</a>

				<?php } ?>

			</article>

			<article class="article-video col-10 col-l-5 col-offset-1 ">
				
				<div class="zone-img">
					<img src="<?= $artiste['image']; ?>" alt="">
				</div>

				<?php if (!empty($artiste['Video'])) : ?>

				<div class="zone-img">
					<iframe width="100%" height="315" src="<?= $artiste['Video']; ?>" frameborder="0" allowfullscreen></iframe>
				</div>

				<?php endif ?>

			</article>

	<?php endif ?>

		</div> <!--fin row-->

	</main>

<?php include('footer.php'); ?>
