<?php
	// Author: Daniel Garcia

	include_once "includes/RESTClient.php";
	include_once "includes/TMDBClient.php";

	$API_ROOT_URL = 'http://api.themoviedb.org/3/';
	$API_KEY = '09adb7fbe6679564fd440a66bee70f5d';
?><!DOCTYPE html>
<html class="no-js" lang="en">
	<head>
		<meta charset="utf-8">
		<title>The Movie Database Search</title>
		<meta name="description" content="">
		<meta name="author" content="Daniel García">
		<meta name="viewport" content="width=device-width, initial-scale=1">

		<link rel="stylesheet" href="css/vendor/normalize.css" />
		<link rel="stylesheet" href="css/vendor/foundation.min.css" />

		<link rel="stylesheet" href="fonts/foundation_icons_general/stylesheets/general_foundicons.css" />
		<!--[if lte IE 7]>
			<link rel="stylesheet" href="fonts/foundation_icons_general/stylesheets/general_foundicons_ie7.css" />
		<![endif]-->

		<link rel="stylesheet" href="css/styles.css" />

		<script src="js/vendor/custom.modernizr.js"></script>
	</head>
	<body>
		<aside class="row">
			<header class="large-12 columns">
				<h1>The Movie Database Search</h1>
			</header>

			<form action="?" id="search-form" class="large-12 columns">
				<fieldset class="row">
					<legend>Search movies</legend>

					<div class="small-12 large-2 columns">
						<label for="q" class="inline">Actor/Actress name:</label>
					</div>

					<div class="small-12 large-8 columns">
						<input type="text" name="q" id="q" value="<?php if (array_key_exists('q',$_GET)) { echo $_GET['q']; } ?>">
					</div>

					<div class="small-12 large-2 columns">
						<input type="submit" class="button postfix" value="Search">
					</div>
				</fieldset>
			</form>
		</aside>
		<section class="row">
			<?php
				if ($_GET && $_GET['q']) {
			?>
			<h2>Search Results</h2>
			<?php
					$query = $_GET['q'];

					// Create a client instance
					$client = new TMDBClient($API_ROOT_URL, $API_KEY);

					// Look for the person's id
					$client->searchPerson($query);
					$actor = $client->response->results[0];

					// Look for all the movies where the person has acted, and sort them by release date
					$client->getMoviesByPerson($actor->id);
					$movies = $client->response->cast;
					if ($movies) {
						usort($movies, "release_date_cmp");
			?>

			<section id="movies" class="large-8 small-8 large-uncentered small-centered columns">
				<?php
					foreach ($movies as &$movie) {

						if ($movie->poster_path) {
							$img_url = $client->config->images->base_url . $client->config->images->poster_sizes[1] . $movie->poster_path;
						} else {
							$img_url = 'http://placehold.it/154x231/&amp;text=N/A';
						}

				?>
					<article class="row">
						<div class="large-3 columns">
							<img data-original="<?php echo $img_url; ?>" alt="<?php echo $movie->title; ?>" src="img/loader.gif">
						</div>
						<div class="large-9 columns">
							<header>
								<h3><?php echo $movie->title; ?></h3>
							</header>
							<section>
								<ul class="no-bullet">
									<?php if ($movie->release_date) { ?>
										<li>
											<i class="foundicon-calendar"></i>
											<strong>Release Date:</strong>
											<?php echo $movie->release_date; ?>
										</li>
									<?php } ?>
									<?php if ($movie->original_title) { ?>
										<li>
											<i class="foundicon-globe"></i>
											<strong>Original Title:</strong>
											<?php echo $movie->original_title; ?>
										</li>
									<?php } ?>
									<?php if ($movie->character) { ?>
										<li>
											<i class="foundicon-address-book"></i>
											<strong>Character:</strong>
											<?php echo $movie->character; ?>
										</li>
									<?php } ?>
								</ul>
							</section>
						</div>
					</article>
					<hr/>
				<?php
					}
				?>
			</section>
			<aside id="actor" class="large-3 small-4 large-uncentered small-centered columns panel">
				<?php
				if ($actor->profile_path) {
					$img_url = $client->config->images->base_url . $client->config->images->profile_sizes[2] . $actor->profile_path;
				} else {
					$img_url = 'http://placehold.it/208x271/&amp;text=N/A';
				}
				?>
				<img src="<?php echo $img_url; ?>" alt="<?php echo $actor->name; ?>">
				<h3><?php echo $actor->name; ?></h3>
			</aside>

			<?php
					} else {
			?>

			<p class="alert-box alert">We're sorry we couldn't find any results for "<?php echo $_GET['q']; ?>"</p>

			<?php

					}
				}
			?>
		</section>
		<script>
		// document.write('<script src=' +
		// ('__proto__' in {} ? 'js/vendor/zepto' : 'js/vendor/jquery') +
		// '.js><\/script>')
		</script>
		<script src="js/vendor/jquery-1.10.1.min.js"></script>
		<!-- // <script src="js/vendor/foundation.min.js"></script> -->
		<script src="js/vendor/jquery.lazyload.min.js"></script>
		<script type="text/javascript">
			$("img").lazyload();
		</script>
	</body>
</html>