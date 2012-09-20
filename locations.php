<? include('header.php') ?>
<section id="container">
    <? //include('leftbar.php') ?>
    <div class="centerContent relative">
        <div class="outerBox centerWidth relative">
            <section class="contentArea introBox widgetContentBG relative">
                <h2 id="introHeader"><i class="directoryIcons i-locationsDirectory"></i>Locations Directory</h2>
                <p>Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book.</p>
            </section>
        </div> <!-- intro content -->
        <div class="outerBox centerWidth relative" style="margin-top:10px;">
            <div class="contentArea contentBox">
                <form class="listingsSearch" accept-charset="utf-8" id="filterform" method="GET" action="/members">
                    <div id="wraparound" class="outerBox relative">
                        <div id="search-wrap" class="filterContentBG contentArea contentNoBottom">
                            <div class="inputFill" style="height:29px;width:270px;">
                                <div class="inputFill" style="height:29px;width:220px;float:left">
                                    <input type="text" id="searchInput" style="width:218px;" placeholder="Search Locations" />
                                    <div class="btn-group">
                                        <button class="btn btn-small ">Search</button>
                                    </div>
                                </div>
                                <!--                                <button type="submit" class="btn" style="width:70px;margin:0;height:28px;">Search</button>-->
                            </div>
                            <button class="btnReset directoryIcons i-map" id="mapBtn"></button>
                        </div>
                    </div> <!-- SEARCH BOX -->
                    <input type="hidden" value="c6d6581afc6dda3ec151f8353a96a8d1ca3d865e" name="token">
                </form> <!-- SEARCH AREA -->
                <section id="feedActionDiv">
                    <div class="relative" id="resultCount"><h3><small>534 results found</small><span></span></h3></div>
                    <div id="sortButtonDiv" class="dropdown">
                        <a data-toggle="dropdown" class="btn btn-small dropdown-toggle" id="sortButton" href="#">Sort:&nbsp;<span class="sortSelected">Popular</span>&nbsp;<span class="caret"></span></a>
                        <ul class="dropdown-menu">
                            <li><a href="#" id="sortNewest">Newest</a></li>
                            <li><a href="#" id="sortPopular">Popular</a></li>
                        </ul>                    
                    </div>                    
                </section> <!--  sort button & result count -->
                <div class="widgetOuterBox relative">
                    <article class="contentArea location widgetContentBG relative">
                        <div class="mainListingContent">
                            <div class="locationLeft relative">
                                <i class="largeIcons i-landed"></i>
                            </div> <!-- listings thumbnail -->
                            <div class="locationRight relative">
                                <h3><a href="#">Lakeside Grove <a><small>Private Landed</small></a></a></h3>
                                <ul class="locationDetails">
                                    <li><label>Estate</label><span>Jurong</span></li>
                                    <li><label>Locality</label><span>Lakeside</span></li>
                                    <li><label>Type</label><span>Landed Housing</span></li>                                    
                                    <li><label>Listings</label><span>0</span></li>
                                </ul>                                   
                            </div> <!-- listings content -->
                        </div> <!-- main listing content -->
                        <div class="postActions">
                            <ul class="postActionButtons">
                                <li><a href="#"><i class="postActionsIcons i-share"></i></a></li>
                                <li><a href="#"><i class="postActionsIcons i-subscribe"></i></a></li>
                                <li><a href="#"><i class="postActionsIcons i-like"></i></a></li>
                            </ul>
                            <ul class="postActionInfo">
                                <li><a href="#"><i class="postMini i-subscribers"></i>215</a></li>
                                <li><a href="#"><i class="postMini i-likes"></i>167</a></li>
                            </ul>
                        </div> <!-- postActions -->
                    </article>
                </div> <!-- location -->
                <div class="widgetOuterBox relative">
                    <article class="contentArea location widgetContentBG relative">
                        <div class="mainListingContent">
                            <div class="locationLeft relative">
                                <i class="largeIcons i-privateApt"></i>
                            </div> <!-- listings thumbnail -->
                            <div class="locationRight relative">
                                <h3><a href="#">The Mayfair</a> <a><small>Private Residential</small></a></h3>
                                <ul class="locationDetails">
                                    <li><label>Estate</label><span>Jurong</span></li>
                                    <li><label>Locality</label><span>Jurong East</span></li>
                                    <li><label>Type</label><span>Apartments</span></li>                                    
                                    <li><label>Listings</label><span>0</span></li>
                                </ul>                                   
                            </div> <!-- listings content -->
                        </div> <!-- main listing content -->
                        <div class="postActions">
                            <ul class="postActionButtons">
                                <li><a href="#"><i class="postActionsIcons i-share"></i></a></li>
                                <li><a href="#"><i class="postActionsIcons i-subscribe"></i></a></li>
                                <li><a href="#"><i class="postActionsIcons i-like"></i></a></li>
                            </ul>
                            <ul class="postActionInfo">
                                <li><a href="#"><i class="postMini i-subscribers"></i>215</a></li>
                                <li><a href="#"><i class="postMini i-likes"></i>167</a></li>
                            </ul>
                        </div> <!-- postActions -->
                    </article>
                </div> <!-- location -->                
                <div class="widgetOuterBox relative">
                    <article class="contentArea location widgetContentBG relative">
                        <div class="mainListingContent">
                            <div class="locationLeft relative">
                                <i class="largeIcons i-hdb"></i>
                            </div> <!-- listings thumbnail -->
                            <div class="locationRight relative">
                                <h3><a href="#">Jurong East <a><small>HDB</small></a></a></h3>
                                <ul class="locationDetails">
                                    <li><label>Estate</label><span>Jurong</span></li>
                                    <li><label>Locality</label><span>Teban Gardens, Pandan Gardens</span></li>
                                    <li><label>Type</label><span>Apartment</span></li>                                    
                                    <li><label>Listings</label><span>25</span></li>
                                </ul>                                
                            </div> <!-- listings content -->
                        </div> <!-- main listing content -->
                        <div class="postActions">
                            <ul class="postActionButtons">
                                <li><a href="#"><i class="postActionsIcons i-share"></i></a></li>
                                <li><a href="#"><i class="postActionsIcons i-subscribe"></i></a></li>
                                <li><a href="#"><i class="postActionsIcons i-like"></i></a></li>
                            </ul>
                            <ul class="postActionInfo">
                                <li><a href="#"><i class="postMini i-subscribers"></i>215</a></li>
                                <li><a href="#"><i class="postMini i-likes"></i>167</a></li>
                            </ul>
                        </div> <!-- postActions -->
                    </article>
                </div> <!-- location -->
                <div class="widgetOuterBox relative">
                    <article class="contentArea location widgetContentBG relative">
                        <div class="mainListingContent">
                            <div class="locationLeft relative">
                                <i class="largeIcons i-hdb"></i>
                            </div> <!-- listings thumbnail -->
                            <div class="locationRight relative">
                                <h3><a href="#">Jurong West <a><small>HDB</small></a></a></h3>
                                <ul class="locationDetails">
                                    <li><label>Estate</label><span>Jurong</span></li>
                                    <li><label>Type</label><span>Apartment</span></li>
                                    <li><label>Locality</label><span>Boon Lay, Taman Jurong, Lakeside</span></li>
                                    <li><label>Listings</label><span>15</span></li>
                                </ul>
                            </div> <!-- listings content -->
                        </div> <!-- main listing content -->
                        <div class="postActions">
                            <ul class="postActionButtons">
                                <li><a href="#"><i class="postActionsIcons i-share"></i></a></li>
                                <li><a href="#"><i class="postActionsIcons i-subscribe"></i></a></li>
                                <li><a href="#"><i class="postActionsIcons i-like"></i></a></li>
                            </ul>
                            <ul class="postActionInfo">
                                <li><a href="#"><i class="postMini i-subscribers"></i>215</a></li>
                                <li><a href="#"><i class="postMini i-likes"></i>167</a></li>
                            </ul>
                        </div> <!-- postActions -->
                    </article>
                </div> <!-- location -->
            </div>
        </div>
    </div>
</section>

<? //include('rightbar.php') ?>
<footer id="footer"></footer>
</body>
</html>