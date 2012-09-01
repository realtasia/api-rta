<?
$domParts = explode('.', $_SERVER['SERVER_NAME']);
$domain = preg_replace("/[^A-Za-z0-9?!]/",'',$domParts[1]);

$dom      = 'http://' . $domain . '.' . $domParts[2];
echo $dom;
?>
<!DOCTYPE html>
<html>    
    <head>
        <title>Aishwarya Rai</title>
        <!--[if IE]><script src="http://html5shiv.googlecode.com/svn/trunk/html5.js"></script><![endif]-->
        <link rel="stylesheet" type="text/css" href="<?=$dom?>/common/css/bootstrap.css" />        
        <link rel="stylesheet" type="text/css" href="/css/style.css" />
        <link rel="stylesheet" type="text/css" href="/css/bubbletip.css" />
        <link rel="stylesheet" type="text/css" href="/css/bubbletip-IE.css" />
        <link rel="stylesheet" type="text/css" href="/css/RTA.css" />
        <script type="text/javascript" src="<?=$dom?>/common/js/jquery.min.js"></script>
        <script type="text/javascript" src="<?=$dom?>/common/js/bootstrap.min.js"></script>
        <script type="text/javascript" src="/js/jQuery.bubbletip-1.1.0.js"></script>
        <script type="text/javascript" src="/js/dropdown.min.js"></script>
        <title></title>
        <!--[if IE]>
        <link rel="stylesheet" type="text/css" href="/css/bubbletip-IE.css" />
        <script src="http://html5shiv.googlecode.com/svn/trunk/html5.js"></script>
        <script type="text/javascript" src="/js/jquery.placeholder.min.js"></script>
        <script type ="text/javascript">
            jQuery(document).ready(function($){
                $('input, textarea').placeholder();
            });
        </script>        
        <![endif]-->
        <!--<link rel="stylesheet" type="text/css" href="/css/style.css" />-->        

        <script type="text/javascript" src="/js/script.js"></script>		
    </head>
    <body>
        <header id="header">
            <div class="navbar navbar-fixed-top">
                <div class="navbar-inner">                    
                    <div class="container">
                        <ul class="nav" style="padding:0;margin:0 auto;text-align:center;display:block;" id="pageNavigation">
                            <li id="realtLogo"><a style="margin:0;padding:0;"><img src="/img/realtlogo6.png" /></a></li>
                            <li class="menuItem leftMenuItem"><a href="#navPeople" id="navPeople">People</a></li>
                            <li class="menuItem leftMenuItem rightMenuItem"><a href="#navLocations" id="navLocations">Locations</a></li>
                            <li class="menuItem rightMenuItem"><a href="#navLocations" id="navListings">Listings</a></li>                         
                        </ul>                       
                        <div class="nav-collapse">
                            <ul class="nav" id="mainNav">
                                <a class="btn btn-navbar" data-toggle="collapse" data-target=".nav-collapse">
                                    <span class="icon-bar"></span>
                                    <span class="icon-bar"></span>
                                    <span class="icon-bar"></span>
                                </a>                             
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
                        </div><!-- /.nav-collapse -->
                    </div>
                </div><!-- /navbar-inner -->
            </div>
        </header>
