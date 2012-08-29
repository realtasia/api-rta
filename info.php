<? include('header.php') ?>   
<div id="container">
    <? include('leftbar.php') ?>
    <div id="contentArea">
        <div id="introContent">
            <h2>Aishwarya Rai</h2>
            <div class="pageInfo"><span class="pageType">Director, Regional Sales & Marketing</span> at <span class="parentDesc"><a href="#">DTZ Debenham Tie Leung</a></span></div>


            <p>
                <?= nl2br('Aishwarya Rai the Miss World stunning beauty queen is considered to be one of the beautiful actresses in the movie industry. She has done movies in languages like Hindi, Tamil, English and Bengali. 
                            In the year 1994, she won the Miss World title and brought fame to her mother land.') ?>
            </p>
        </div>
        <div class="infoContent">
            <h6 class="wrapperHeader"><img src="/img/png/glyphicons_352_nameplate.png" /><p>Personal & Contact Details</p></h6>
            <ul class="infoLists">
                <li><img src="/img/png/glyphicons_139_phone.png" width="8" /><p><b>Mobile:</b> +65 89259845 &middot; <b>Fax:</b> +65 68524563</li>
                <li><img src="/img/png/glyphicons_399_e-mail.png" width="12" /><p><a href="mailto:aishwarya.rai@bollywoodstars.com">aishwarya.rai@bollywoodstars.com</a></p></li>
                <li><img src="/img/png/glyphicons_278_birthday_cake.png" width="12" /><p>12th August 1978</p></li>
            </ul>
        </div>
        <div class="infoContent">
            <h6 class="wrapperHeader"><img src="/img/png/glyphicons_332_certificate.png" /><p>Education & Certification</p></h6>
            <ul class="infoLists">
                <li><p>2010 : MsC Real Estate Related Shit (NUS)</p></li>
                <li><p>2008 : BsC Property Management (NUS)</p></li>
                <li><p>2005 : Certificate in CEHA</p></li>
            </ul>
        </div>
        <div class="infoContent">
            <h6 class="wrapperHeader"><img src="/img/png/glyphicons_074_cup.png" /><p>Awards & Achievements</p></h6>
            <ul class="infoLists">
                <li><p>2011 : Top 10 Sales Director</p></li>
                <li><p>2009 : Manager of the year</p></li>
                <li><p>2007 : Top Rookie</p></li>
                <li><p>2007 : Team Player Award</p></li>
            </ul>
        </div>
        <div class="infoContent">
            <h6 class="wrapperHeader"><img src="/img/png/glyphicons_043_group.png" /><p>Social Connections</p></h6>
            <ul class="infoLists">
                <li id="socialImgs">
                    <img src="/img/png/glyphicons_390_facebook.png" />
                    <img src="/img/png/glyphicons_362_google+_alt.png" />
                    <img src="/img/png/glyphicons_391_twitter_t.png" />
                    <img src="/img/png/glyphicons_398_skype.png" />
                    <img src="/img/png/glyphicons_382_youtube.png" />
                    <img src="/img/png/glyphicons_395_flickr.png" />
                </li>
            </ul>
        </div>
        <div class="infoContent">
            <h6 class="wrapperHeader"><img src="/img/png/glyphicons_340_globe.png" /><p>Other Websites</p></h6>
            <ul class="infoLists">
                <li><p><a href="http://www.wordpress.com">My Property Blog</a></p></li>
                <li><p><a href="http://www.iproperty.com">My Listings on iProperty</a></p></li>
                <li><p><a href="http://www.propertyguru.com">My Listings on PropertyGuru</a></p></li>
            </ul>
        </div>                  
    </div>
    <aside id="rightbar">
        <div class="wrapper">
            <div class="wrapperInner">
                <h5>
                    <a href="#">Likes&nbsp;</a><span class="badge">54</span>
                </h5>
                <ul class="thumbnails thumbnailGallery">
                    <?php
                    $a_rand = range(1, 14);
                    for ($i = 1; $i <= 5; $i++) {
                        $r = array_rand($a_rand);
                        $rand = $a_rand[$r];
                        unset($a_rand[$r]);
                        ?>
                        <li class="smallNail"><div class="thumbnail"><a href="#"><img width="35" height="35" src="/img/thumbs/<?= $rand ?>.jpg" alt="image" /></div></a></li>
                        <?
                    }
                    ?>    
                    <li style="width:100%;text-align: right;padding:12px 0;position:relative;">
                        <h5><a style="position:absolute;right:25px;bottom:0;right:5px;font-size: 90%;top:px;" href="#">More&hellip;</a></h5>
                    </li>
                </ul>        
                <hr class="thumbnailGallerySeperator" />
                <h5>
                    <a href="#">Subscribers&nbsp;</a>&nbsp;<span class="badge">14</span>                            
                </h5>        
                <ul class="thumbnails thumbnailGallery">
                    <?php
                    for ($i = 1; $i <= 5; $i++) {
                        $r = array_rand($a_rand);
                        $rand = $a_rand[$r];
                        unset($a_rand[$r]);
                        ?>
                        <li class="smallNail"><div class="thumbnail"><a href="#"><img width="35" height="35" src="/img/thumbs/<?= $rand ?>.jpg" alt="image" /></div></a></li>
                        <?
                    }
                    ?>    
                    <li style="width:100%;text-align: right;padding:12px 0;position:relative;">
                        <h5><a style="position:absolute;right:25px;bottom:0;right:5px;font-size: 90%;top:px;" href="#">More&hellip;</a></h5>
                    </li>
                </ul>
                <div style="clear:both"></div>                 
            </div>
        </div>
    </aside>
</div>
<footer id="footer"></footer>
</body>
</html>