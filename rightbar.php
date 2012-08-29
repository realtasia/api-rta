<aside id="rightbar">
                <div class="outerBox">
                    <div class="contentArea widgetContentBG">

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
            </aside> <!--  rightbar -->