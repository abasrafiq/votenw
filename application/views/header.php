<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="de-DE" lang="de-DE" ng-app>
  <head>

    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta name="robots" content="follow" />
    <meta name="robots" content="index" />
    <meta name="revisit-after" content="20 days" />
    <meta name="author" content="Andreas Geibert">

    <title><?= $this->config->item("pageTitle"); ?></title>

    <link rel="stylesheet" href="<?php echo(base_url()); ?>_assets/css/styles.css" type="text/css" />

    <!--[if IE]>
            <link href="<?php echo(base_url()); ?>_assets/css/ie.css" rel="stylesheet" type="text/css" />
            <![endif]-->

    <!--[if IE 6]>
            <link href="<?php echo(base_url()); ?>_assets/css/ie6.css" rel="stylesheet" type="text/css" />
            <![endif]-->

    <!--[if IE 7]>
            <link href="<?php echo(base_url()); ?>_assets/css/ie7.css" rel="stylesheet" type="text/css" />
    <![endif]-->

  </head>
  <body>

    <div class="navbar navbar-fixed-top">
      <div class="navbar-inner">
        <div class="container">
          <a class="btn btn-navbar" data-toggle="collapse" data-target=".nav-collapse">
            <span class="i-bar"><i class="icon-chevron-down icon-white"></i></span>
          </a>
          <a class="brand" href="<?= base_url(); ?>home"><?= $this->config->item('pageTitle'); ?></a>
          <div class="nav-collapse">

            <?php if(!($controllerNameShort == "login") && !($controllerNameShort == "verifylogin")){ ?>
              <ul class="nav">
                <?php
                $countNavitems = 1;
                $first = "";
                $last = "";

                foreach ($navItems as $controllerName => $item) {
                  if ($countNavitems == 1) {
                    $first = " first";
                  }
                  if ($countNavitems == count($navItems)) {
                    $last = " last";
                  }
                  ?>

                  <li class="<?php echo $item["active"] ? "active" : ""; ?><?php echo($first . $last); ?>"><a href="<?php echo base_url() . $item["url"] ?>"><?php echo $item["title"]; ?></a></li>
                  <?php
                  $countNavitems++;
                }
                ?>
              </ul>
              <ul class="nav pull-right">
                <li><a href="#">Eingeloggt als <?= $userdata["vorname"]." ".$userdata["nachname"]; ?></a></li>
                <li class="logout"><a href="<?= base_url(); ?>login/logout">Logout</a></li>
              </ul>
            <?php } ?>
          </div><!--/.nav-collapse -->
        </div>
      </div>
    </div>

    <? if($this->session->flashdata('flashMessages')){ ?>
      <div id="flashMessages"><?= $this->session->flashdata('flashMessages') ?></div>
    <?php } ?>


    <div id="wrapper">

      <div id="content">
      

      