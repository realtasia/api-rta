<?php
$currentDom = $_SERVER['HTTP_HOST'];
$domParts   = explode('.', $currentDom);
$rootDomain = $domParts[1] . '.' . $domParts[2];
$rootURL    = 'http://' . $rootDomain;
$currentURL = 'http://' . $currentDom;
?>
<!DOCTYPE html>
<html>
    <head>
        <title>Aishwarya Rai</title>
        <meta charset="utf-8" />
        <link rel="stylesheet" type="text/css" href="<?= $rootURL ?>/common/css/bootstrap.min.css" />
        <link rel="stylesheet" type="text/css" href="/css/icons.css" />
        <link rel="stylesheet" type="text/css" href="/css/style.css" />
        <link rel="stylesheet" type="text/css" href="/css/RTA.css" />
        <script type="text/javascript" src="<?= $rootURL ?>/common/js/jquery.min.js"></script>
        <script type="text/javascript" src="<?= $rootURL ?>/common/js/jquery-ui.min.js"></script>
        <script type="text/javascript" src="<?= $rootURL ?>/common/js/bootstrap.min.js"></script>
        <script type="text/javascript" src="/js/dropdown.min.js"></script>
        <script type="text/javascript" src="<?= $rootURL ?>/common/js/jquery.qtip.min.js"></script>
        <script type="text/javascript" src="<?= $rootURL ?>/common/js/knockout.js"></script>
        <script type="text/javascript" src="<?= $rootURL ?>/common/js/knockout.mapping-latest.js"></script>
        <script type="text/javascript" src="/js/plupload.full.js"></script>
        <!--<script type="text/javascript" src="/js/script.js"></script>-->
        <script type="text/javascript" src="/js/medialib.js"></script>
        <!--[if IE]>
        <link rel="stylesheet" type="text/css" href="<?= $rootURL ?>/common/css/bubbletip-IE.css" />
        <script src="http://html5shiv.googlecode.com/svn/trunk/html5.js"></script>
        <script type="text/javascript" src="<?= $rootURL ?>/common/js/jquery.placeholder.min.js"></script>
        <script type ="text/javascript">
            jQuery(document).ready(function($){
                $('input, textarea').placeholder();
            });
        </script>
        <![endif]-->
    </head>
    <body>
        <header id="header">
            <section class="navbar navbar-fixed-top">
                <div class="navbar-inner">
                    <nav class="container">
                        <ul class="nav" style="padding:0;margin:0 auto;text-align:center;display:block;" id="pageNavigation">
                            <li id="realtLogo"><a style="margin:0;padding:0;" href="<?= $currentURL ?>"><img src="/img/realtlogo6.png" /></a></li>
                            <li class="menuItem leftMenuItem"><a href="/people.php" id="navPeople">People</a></li>
                            <li class="menuItem leftMenuItem rightMenuItem"><a href="/locations.php" id="navLocations">Locations</a></li>
                            <li class="menuItem rightMenuItem"><a href="/listings.php" id="navListings">Listings</a></li>
                        </ul>
                        <ul class="nav" id="mainNav">
                            <li><a href="#"><i class="headerIcons i-notification Active"></i></a></li>
                            <li><a href="#"><i class="headerIcons i-inbox Active"></i></a></li>
                            <li class="dropdown accountBtnLi">
                                <div class="dropdown" id="accountDiv">
                                    <img style="padding:2px;background-color: #bbb;vertical-align: bottom;line-height:normal;float: left;" src="/img/thumbs/1.jpg" width="35" height="35">
                                    <a class="dropdown-toggle" data-toggle="dropdown" style="float:left;cursor:pointer;margin-top:4px;" id="accountLink">
                                        Hello Aishwarya! <b class="caret"></b>
                                    </a>
                                    <ul class="dropdown-menu">
                                        <li><a href="#navProfile" id="navProfile">View Profile</a></li>
                                        <li><a href="#navSettings" id="navSettings">Settings</a></li>
                                        <li class="divider"></li>
                                        <li><a href="#navLogOut" id="navLogOut">Log Out</a></li>
                                    </ul>
                                </div>
                            </li>
                        </ul>

                    </nav>
                </div><!-- /navbar-inner -->
            </section>
        </header>
