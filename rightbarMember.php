<aside id="rightbar">
    <div class="outerBox">
        <div class="contentArea widgetContentBG">
            <h5 class="sideBarHeader">
                <a href="#"><i class="postMini i-likes"></i>Likes&nbsp;</a><span class="badge badge-blue">54</span>
            </h5>
            <ul class="thumbnails thumbnailGallery">
                <?php
                $a_rand = array('00', 'ff', '00', 'ff');
                for ($i = 0; $i <= 3; $i++)
                {
                    $r    = array_rand($a_rand);
                    $g    = array_rand($a_rand);
                    $b    = array_rand($a_rand);
                    $rand = $a_rand[$r] . $a_rand[$g] . $a_rand[$b];
                    $font = ($rand == 'ffffff') ? '000' : 'fff';
                    ?>
                    <li class="smallNail"><div class="thumbnail"><a href="#"><img src="http://placehold.it/45" alt="image" /></div></a></li>
                    <?
                }
                ?>    
            </ul>
            <div class="moreContainer">
                <h5><a class="moreLink" href="#">More&hellip;</a></h5>
            </div>                        
            <hr class="thumbnailGallerySeperator" />
            <h5 class="sideBarHeader">
                <a href="#"><i class="sidebar i-subscribers"></i>Subscribers&nbsp;</a>&nbsp;<span class="badge badge-blue">14</span>                            
            </h5>        
            <ul class="thumbnails thumbnailGallery">
                <?php
                for ($i    = 0; $i <= 3; $i++)
                {
                    $r    = array_rand($a_rand);
                    $g    = array_rand($a_rand);
                    $b    = array_rand($a_rand);
                    $rand = $a_rand[$r] . $a_rand[$g] . $a_rand[$b];
                    $font = ($rand == 'ffffff') ? '000' : 'fff';
                    ?>
                    <li class="smallNail"><div class="thumbnail"><a href="#"><img src="http://placehold.it/45" alt="image" /></div></a></li>
                    <?
                }
                ?>    
            </ul>
            <div class="moreContainer">
                <h5><a class="moreLink" href="#">More&hellip;</a></h5>
            </div>                
        </div>
    </div>    
    <div class="outerBox">
        <div class="contentArea widgetContentBG">
            <h5 class="sideBarHeader">
                <a href="#"><i class="formIcons i-reputation"></i>Reputation&nbsp;</a>&nbsp;<a href="#"><span class="badge badge-warning">beta</span></a>                            
            </h5>        
            <ul class="reputationContainer">
                <li class="outerBox reputationScore"><div class="contentArea"><span class="reputation">0</span></li>
                <li class="outerBox reputationScore"><div class="contentArea"><span class="reputation">0</span></li>
                <li class="outerBox reputationScore"><div class="contentArea"><span class="reputation">8</span></li>
                <li class="outerBox reputationScore"><div class="contentArea"><span class="reputation">1</span></li>
            </ul>             
        </div>
    </div>    
</aside> <!--  rightbar -->