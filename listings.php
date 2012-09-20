<? include('header.php') ?>
<section id="container">
    <? //include('leftbar.php') ?>
    <div class="centerContent relative">
        <div class="outerBox centerWidth relative">
            <section class="contentArea introBox widgetContentBG relative">
                <h2 id="introHeader"><i class="directoryIcons i-listingsDirectory"></i>Listings Directory</h2>
                <p>Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book.</p>
            </section>
        </div> <!-- intro content -->
        <div class="outerBox centerWidth relative" style="margin-top:10px;">
            <div class="contentArea contentBox">
                <form class="listingsSearch" accept-charset="utf-8" id="filterform" method="GET" action="/members">
                    <div id="wraparound" class="outerBox relative">
                        <section id="search-wrap" class="filterContentBG contentArea contentNoBottom">
                            <div class="inputFill" style="height:29px;width:312px;">
                                <div class="inputFill" style="height:29px;width:220px;float:left">
                                    <input type="text" id="searchInput" style="width:218px;" placeholder="Search Listings" />
                                    <div class="btn-group">
                                        <button class="btn btn-small ">Rent & Sale</button>
                                        <button class="btn btn-small dropdown-toggle" data-toggle="dropdown">
                                            <span class="caret"></span>
                                        </button>
                                        <ul class="dropdown-menu">
                                            <li><a>Rent & Sale</a></li>
                                            <li><a>Rent</a></li>
                                            <li><a>Sale</a></li>
                                        </ul>
                                    </div>
                                </div>
                                <!--                                <button type="submit" class="btn" style="width:70px;margin:0;height:28px;">Search</button>-->
                            </div>
                            <button class="btnReset directoryIcons i-map" id="mapBtn"></button>
                        </section>
                    </div> <!-- SEARCH BOX -->
                    <div style="display:none;" id="drop-down-filter" class="centerWidth outerBox relative shadow">
                        <section class="filterBox filterContentBG contentArea contentNoTop">
                            <div class="formContainer">
                                <div class="containerLeft">
                                    <div class="btn-toolbar">
                                        <div class="btn-group">
                                            <button class="btn btn-mini ">Residential Apartments</button>
                                            <button class="btn btn-mini dropdown-toggle" data-toggle="dropdown">
                                                <span class="caret"></span>
                                            </button>
                                            <ul class="dropdown-menu">
                                                <li><a>All Residential</a></li>
                                                <li><a>HDB</a></li>
                                                <li><a>Residential Apartments</a></li>
                                                <li><a>Residential Landed</a></li>
                                                <li><a>Residential Serviced</a></li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                                <div class="containerRight">
                                    <div class="btn-toolbar">
                                        <div class="btn-group">
                                            <button class="btn btn-mini half">500 sqft</button>
                                            <button class="btn btn-mini dropdown-toggle" data-toggle="dropdown">
                                                <span class="caret"></span>
                                            </button>
                                            <ul class="dropdown-menu">
                                                <li><a>500 sqft</a></li>
                                                <li><a>1000 sqft</a></li>
                                                <li><a>2000 sqft</a></li>
                                                <li><a>3000 sqft</a></li>
                                                <li><a>4000 sqft</a></li>
                                                <li><a>5000 sqft</a></li>
                                                <li><a>6000 sqft</a></li>
                                                <li><a>7000 sqft</a></li>
                                                <li><a>8000 sqft</a></li>
                                                <li><a>9000 sqft</a></li>
                                            </ul>
                                            <label>&mdash;</label>
                                            <button class="btn btn-mini half">> 9000 sqft</button>
                                            <button class="btn btn-mini dropdown-toggle" data-toggle="dropdown">
                                                <span class="caret"></span>
                                            </button>
                                            <ul class="dropdown-menu">
                                                <li><a>500 sqft</a></li>
                                                <li><a>1000 sqft</a></li>
                                                <li><a>2000 sqft</a></li>
                                                <li><a>3000 sqft</a></li>
                                                <li><a>4000 sqft</a></li>
                                                <li><a>5000 sqft</a></li>
                                                <li><a>6000 sqft</a></li>
                                                <li><a>7000 sqft</a></li>
                                                <li><a>8000 sqft</a></li>
                                                <li><a>> 9000 sqft</a></li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                                <div class="containerLeft">
                                    <div class="btn-toolbar">
                                        <div class="btn-group leftNudge">
                                            <input placeholder="S$1,500"/>
                                            <label>&mdash;</label>
                                            <input placeholder="S$2 million"/>
                                        </div>
                                    </div>
                                </div>
                                <div class="containerRight">
                                    <div class="btn-toolbar">
                                        <div class="btn-group">
                                            <button class="btn btn-mini half">STUDIO</button>
                                            <button class="btn btn-mini dropdown-toggle" data-toggle="dropdown">
                                                <span class="caret"></span>
                                            </button>
                                            <ul class="dropdown-menu">
                                                <li><a>STUDIO</a></li>
                                                <li><a>1 Room</a></li>
                                                <li><a>2 Rooms</a></li>
                                                <li><a>3 Rooms</a></li>
                                                <li><a>4 Rooms</a></li>
                                                <li><a>5 Rooms</a></li>
                                                <li><a>6 Rooms</a></li>
                                                <li><a>7 Rooms</a></li>
                                                <li><a>8 Rooms</a></li>
                                                <li><a>9 Rooms</a></li>
                                            </ul>
                                            <label>&mdash;</label>
                                            <button class="btn btn-mini half">9 Rooms</button>
                                            <button class="btn btn-mini dropdown-toggle" data-toggle="dropdown">
                                                <span class="caret"></span>
                                            </button>
                                            <ul class="dropdown-menu">
                                                <li><a>STUDIO</a></li>
                                                <li><a>1 Room</a></li>
                                                <li><a>2 Rooms</a></li>
                                                <li><a>3 Rooms</a></li>
                                                <li><a>4 Rooms</a></li>
                                                <li><a>5 Rooms</a></li>
                                                <li><a>6 Rooms</a></li>
                                                <li><a>7 Rooms</a></li>
                                                <li><a>8 Rooms</a></li>
                                                <li><a>9 Rooms</a></li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                                <div class="containerLeft"></div>
                                <div class="containerRight">
                                    <div class="formButtons">
                                        <a class="btn" id="resetSearch"><i class="listingCreation i-reset"></i></a>
                                        <a class="btn" id="cancelSearch"><i class="listingCreation i-cancel"></i></a>
                                        <a class="btn" id="doSearch"><i class="listingCreation i-publish"></i></a>                                        
                                    </div> <!-- SEARCH FORM BUTTONS -->
                                </div>
                            </div>
                        </section>
                    </div>
                    <input type="hidden" value="c6d6581afc6dda3ec151f8353a96a8d1ca3d865e" name="token">
                </form> <!-- SEARCH AREA -->
                <section id="feedActionDiv">
                    <div class="relative" id="resultCount"><h3><small>534 results found</small><span> in Properties for Sale</span></h3></div>
                    <div id="sortButtonDiv" class="dropdown">
                        <a data-toggle="dropdown" class="btn btn-small dropdown-toggle" id="sortButton" href="#">Sort:&nbsp;<span class="sortSelected">Popular</span>&nbsp;<span class="caret"></span></a>
                        <ul class="dropdown-menu">
                            <li><a href="#" id="sortNewest">Newest</a></li>
                            <li><a href="#" id="sortPopular">Popular</a></li>
                        </ul>                    
                    </div>                    
                </section> <!--  sort button & result count -->
                <div class="widgetOuterBox relative">
                    <article class="contentArea widgetContentBG listing relative">
                        <div class="mainListingContent">
                            <div class="listingLeft relative">
                                <div class="thumbnail ">
                                    <img src="/images/imphoto.jpg">
                                </div>
                                <span class="thumbInfo"><a href="#"><i class="postMini i-images"></i>15 Photos</a></span>
                                <span class="thumbInfo"><a href="#"><i class="postMini i-videos"></i>1 Videos</a></span>
                            </div> <!-- listings thumbnail -->
                            <div class="listingRight relative">
                                <h3><a href="#">Carribean @ Keppel Bay</a></h3>
                                <span class="price relative">
                                    <span class="listingType">FOR<br />SALE</span>
                                    $200,000,000
                                </span>
                                <ul class="listingData">
                                    <li><i class="postMini i-sqft"></i>8,000 sqft</li>
                                    <li><i class="postMini i-bathrooms"></i>3 toilets</li>
                                    <li><i class="postMini i-bedrooms"></i>3 bedrooms</li>
                                    <li></li>
                                </ul>
                                <p>
                                    Need 1 Billion to vote for Palestine Google is having a vote on whether to name Israel or Palestine on its map on Google earth please vote for Palestine and share.
                                </p>
                            </div> <!-- listings content -->
                            <ul class="postedBy">
                                <li class="postedByLabel">Posted by:</li>
                                <li class="postedByThumb"><a href="#"><img src="/images/posterImg.jpg"/></a></li>
                                <li class="postedByName"><a href="#">Natalie Scott</a></li>
                            </ul>
                        </div> <!-- main listing content -->
                        <div class="postActions">
                            <ul class="postActionButtons">
                                <li><a href="#"><i class="postActionsIcons i-share"></i></a></li>
                                <li><a href="#"><i class="postActionsIcons i-subscribe"></i></a></li>
                                <li><a href="#"><i class="postActionsIcons i-like"></i></a></li>
                            </ul>
                            <ul class="postActionInfo">
                                <li><a href="#"><i class="postMini i-comments"></i>51</a></li>
                                <li><a href="#"><i class="postMini i-subscribers"></i>215</a></li>
                                <li><a href="#"><i class="postMini i-likes"></i>167</a></li>
                            </ul>
                        </div> <!-- postActions -->
                        <div class="widgetOuterBox relative comments">
                            <ul class="postComments contentArea">
                                <li class="commentBG">
                                    <ul class="commentInline">
                                        <li class="commentThumb"><img src="/images/commentImg.jpg" width="42" height="42" /></li>
                                        <li class="commentBody">
                                            <a class="commentAuthor" href="#">Susan Doyle</a>
                                            <p>
                                                Need 1 Billion to vote for Palestine Google is having a vote on whether to name israel or Palestine on its map on Google earth please vote for Palestine and share.
                                            <div class="commentInfo"><span class="timeAgo">27 minutes ago</span></div>
                                            </p>

                                        </li>
                                        <li class="commentActions"><i class="postMini i-likes"></i><a href="#"></a></li>
                                    </ul>
                                </li> <!-- comment -->
                                <li class="contentArea commentBG">
                                    <ul class="commentInline">
                                        <li class="commentThumb"><img src="/images/commentImg.jpg" width="42" height="42" /></li>
                                        <li class="commentBody">
                                            <a class="commentAuthor" href="#">Susan Doyle</a>
                                            <p>
                                                Need 1 Billion to vote for Palestine Google.
                                            <div class="commentInfo"><span class="timeAgo">27 minutes ago</span></div>
                                            </p>

                                        </li>
                                        <li class="commentActions"><i class="postMini i-likes"></i><a href="#">1</a></li>
                                    </ul>

                                </li> <!-- comment -->
                                <li class="contentArea commentBG contentNoBottom">
                                    <ul class="commentInline">
                                        <li class="commentThumb"><img src="/images/commentImg.jpg" width="42" height="42" /></li>
                                        <li class="commentTextarea">
                                            <textarea class="commentInput contentArea" placeholder="Your comment"></textarea>
                                        </li>
                                    </ul>
                                </li> <!-- comment -->
                            </ul>
                        </div> <!-- comments -->
                    </article>
                </div> <!-- listing -->
                <div class="widgetOuterBox relative">
                    <article class="contentArea widgetContentBG listing relative">
                        <div class="mainListingContent">
                            <div class="listingLeft relative">
                                <div class="thumbnail ">
                                    <img src="/images/imphoto.jpg">
                                </div>
                                <span class="thumbInfo"><a href="#"><i class="postMini i-images"></i>15 Photos</a></span>
                                <span class="thumbInfo"><a href="#"><i class="postMini i-videos"></i>1 Videos</a></span>
                            </div> <!-- listings thumbnail -->
                            <div class="listingRight relative">
                                <h3><a href="#">Carribean @ Keppel Bay</a></h3>
                                <span class="price relative"><span class="listingType">FOR<br />SALE</span>$200,000,000</span>
                                <ul class="listingData">
                                    <li><i class="postMini i-sqft"></i>8,000 sqft</li>
                                    <li><i class="postMini i-bathrooms"></i>3 toilets</li>
                                    <li><i class="postMini i-bedrooms"></i>3 bedrooms</li>
                                    <li></li>
                                </ul>
                                <p>
                                    Need 1 Billion to vote for Palestine Google is having a vote on whether to name Israel or Palestine on its map on Google earth please vote for Palestine and share.
                                </p>
                            </div> <!-- listings content -->
                            <ul class="postedBy">
                                <li class="postedByLabel">Posted by:</li>
                                <li class="postedByThumb"><a href="#"><img src="/images/posterImg.jpg"/></a></li>
                                <li class="postedByName"><a href="#">Natalie Scott</a></li>
                            </ul>
                        </div> <!-- main listing content -->
                        <div class="postActions">
                            <ul class="postActionButtons">
                                <li><a href="#"><i class="postActionsIcons i-share"></i></a></li>
                                <li><a href="#"><i class="postActionsIcons i-subscribe Active"></i></a></li>
                                <li><a href="#"><i class="postActionsIcons i-like Active"></i></a></li>
                            </ul>
                            <ul class="postActionInfo">
                                <li><a href="#"><i class="postMini i-comments"></i>51</a></li>
                                <li><a href="#"><i class="postMini i-subscribers"></i>215</a></li>
                                <li><a href="#"><i class="postMini i-likes"></i>167</a></li>
                            </ul>
                        </div> <!-- postActions -->
                        <div class="widgetOuterBox relative comments">
                            <ul class="postComments contentArea">
                                <li class="commentBG">
                                    <ul class="commentInline">
                                        <li class="commentThumb"><img src="/images/commentImg.jpg" width="42" height="42" /></li>
                                        <li class="commentBody">
                                            <a class="commentAuthor" href="#">Susan Doyle</a>
                                            <p>
                                                Need 1 Billion to vote for Palestine Google is having a vote on whether to name israel or Palestine on its map on Google earth please vote for Palestine and share.
                                            <div class="commentInfo"><span class="timeAgo">27 minutes ago</span></div>
                                            </p>

                                        </li>
                                        <li class="commentActions"><i class="postMini i-likes"></i><a href="#">167</a></li>
                                    </ul>

                                </li> <!-- comment -->
                                <li class="contentArea commentBG">
                                    <ul class="commentInline">
                                        <li class="commentThumb"><img src="/images/commentImg.jpg" width="42" height="42" /></li>
                                        <li class="commentBody">
                                            <a class="commentAuthor" href="#">Susan Doyle</a>
                                            <p>
                                                Need 1 Billion to vote for Palestine Google.
                                            <div class="commentInfo"><span class="timeAgo">27 minutes ago</span></div>
                                            </p>

                                        </li>
                                        <li class="commentActions"><i class="postMini i-likes"></i><a href="#">3</a></li>
                                    </ul>

                                </li> <!-- comment -->
                                <li class="contentArea commentBG contentNoBottom">
                                    <ul class="commentInline">
                                        <li class="commentThumb"><img src="/images/commentImg.jpg" width="42" height="42" /></li>
                                        <li class="commentTextarea">
                                            <textarea class="commentInput contentArea" placeholder="Your comment"></textarea>
                                        </li>
                                    </ul>
                                </li> <!-- comment -->
                            </ul>
                        </div> <!-- comments -->
                    </article>
                </div> <!-- listing -->
            </div>
        </div>
    </div>
<? //include('rightbar.php') ?>    
</section>
<footer id="footer"></footer>
</body>
</html>