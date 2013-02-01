<!DOCTYPE html>

<!-- paulirish.com/2008/conditional-stylesheets-vs-css-hacks-answer-neither/ -->
<!--[if IE 8]> <html class="no-js lt-ie9" lang="en"> <![endif]-->
<!--[if gt IE 8]><!--> <html class="no-js" lang="en"> <!--<![endif]-->

<head>
		<meta charset="utf-8" />
		<meta name="viewport" content="width=device-width, initial-scale=1.0" />
		<meta name="robots" content="follow" />
		<meta name="robots" content="index" />
		<meta name="revisit-after" content="20 days" />
		<meta name="author" content="Andreas Geibert">

		<title><?= $this->config->item("pageTitle"); ?></title>

		<link rel="stylesheet" href="<?php echo(base_url()); ?>_assets/stylesheets/app.css" type="text/css" />

		<!--[if IE]>
						<link href="<?php echo(base_url()); ?>_assets/css/ie.css" rel="stylesheet" type="text/css" />
						<![endif]-->

		<!--[if IE 6]>
						<link href="<?php echo(base_url()); ?>_assets/css/ie6.css" rel="stylesheet" type="text/css" />
						<![endif]-->

		<!--[if IE 7]>
						<link href="<?php echo(base_url()); ?>_assets/css/ie7.css" rel="stylesheet" type="text/css" />
		<![endif]-->

		<script src="<?php echo(base_url()); ?>_assets/js/foundation/modernizr.foundation.js"></script>


		<script>
			var baseUrl = "<?= base_url(); ?>";
		</script>

	</head>
	<body>

		<div id="wrapper">

			<header class="row">
				<div class="twelve columns">
					<h1>
						<a href="<?= base_url(); ?>">
							Vote For Nerdwords
						</a>
					</h1>
				</div>
			</header>
			<div id="content">
			

