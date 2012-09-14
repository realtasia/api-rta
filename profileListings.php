<? include('header.php') ?>
<div id="container">
    <? include('leftbar.php') ?>
    <div class="centerContent relative">
        <div id="introContent">
            <h2>Aishwarya Rai</h2>
            <div class="pageInfo">
                <span class="pageType">Director, Regional Sales & Marketing</span> at <span class="parentDesc">
                    <a href="#">DTZ Debenham Tie Leung</a>
                </span>
            </div>
            <p>
                <?= nl2br('Aishwarya Rai the Miss World stunning beauty queen is considered to be one of the beautiful actresses in the movie industry. She has done movies in languages like Hindi, Tamil, English and Bengali.
                            In the year 1994, she won the Miss World title and brought fame to her mother land.') ?>
            </p>
        </div> <!--  introcontent -->       
                <form class="profileListings listingsSearch" accept-charset="utf-8" id="filterform" method="GET" action="/members">
                    <div id="wraparound" class="outerBox relative">
                        <div id="search-wrap" class="filterContentBG contentArea contentNoBottom">
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
                        </div>
                    </div> <!-- SEARCH BOX -->
                    <div style="display:none;" id="drop-down-filter" class="centerWidth outerBox relative shadow">
                        <div class="filterBox filterContentBG contentArea contentNoTop">
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
                        </div>
                    </div>
                    <input type="hidden" value="c6d6581afc6dda3ec151f8353a96a8d1ca3d865e" name="token">
                </form> <!-- SEARCH AREA -->        
        <div id="theLoop">
            <div id="feedActionDiv">                
                <div id="filterButtonDiv" class="dropdown">
                    <a data-toggle="dropdown" class="dropdown-toggle" id="filterButton" href="#">Show:&nbsp;<span class="dropDownSelected">All</span>&nbsp;<span class="caret"></span></a>
                    <ul class="dropdown-menu">
                        <li><a href="#" id="filterAll">All</a></li>
                        <li><a href="#" id="filterPopular">Published</a></li>
                        <li><a href="#" id="filterDraft">Draft</a></li>
                    </ul>                    
                </div>                
                <div id="sortButtonDiv" class="dropdown">
                    <a data-toggle="dropdown" class="btn btn-small dropdown-toggle" id="sortButton" href="#">Sort:&nbsp;<span class="sortSelected">Popular</span>&nbsp;<span class="caret"></span></a>
                    <ul class="dropdown-menu">
                        <li><a href="#" id="sortNewest">Newest</a></li>
                        <li><a href="#" id="sortPopular">Popular</a></li>
                    </ul>                    
                </div>
            </div> <!--  sort button & result count -->
            <article class="postFeed">
                <div class="dropdown postSettings">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown"></a>
                    <ul class="dropdown-menu">
                        <li><a href="#">Hide Post</a></li>
                        <li><a href="#">UnSubscribe</a></li>
                        <li class="divider"></li>
                        <li><a href="#">Report Post</a></li>
                    </ul>
                </div> <!-- post settings -->
                <div class="feedProfileThumbs thumbnails">
                    <span class="mediumNail">
                        <a href="#" class="thumbnail">
                            <img src="/img/thumbs/1.jpg" alt="">
                        </a>
                    </span>
                </div> <!-- post profile thumb -->
                <div class="postTitle">
                    <a href="#">Pretty Gal</a> posted a listing for <b>Sale</b> in <a href="#">Lakepoint Condominium</a>
                </div> <!-- post Title -->
                <div class="widgetOuterBox relative isListing">
                    <article class="contentArea widgetContentBG listing relative">
                        <div class="mainListingContent">
                            <div class="listingLeft relative">
                                <div class="thumbnail ">
                                    <img src="/images/imphoto.jpg">
                                </div>
                                <span class="thumbInfo"><a href="#"><i class="postMini i-images"></i>15 Photos</a></span>
                                <span class="thumbInfo"><a href="#"><i class="postMini i-videos"></i>1 Video</a></span>
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
                        </div>
                    </article>
                </div>
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
                </div> <!-- post action links -->
                <div class="indentedComment widgetOuterBox">
                    <ul class="commentList contentArea commentBG">
                        <li class="commentBG">
                            <ul class="commentInline">
                                <li class="commentThumb"><img src="/img/thumbs/1.jpg" width="40" height="40" /></li>
                                <li class="commentBody">
                                    <a class="commentAuthor" href="#">Lottie Lot</a>
                                    <p>I think it is probably over-rated actually!</p>
                                    <div class="commentInfo"><span class="timeAgo">3 hours ago</span>&nbsp;&middot;&nbsp;<span class="commentActions"><i class="postMini i-likes"></i><a href="#">2</a></span></div>
                                </li>
                            </ul> <!-- comment inline -->
                        </li> <!-- comment -->
                        <li class="contentArea commentBG">
                            <ul class="commentInline">
                                <li class="commentThumb"><img src="/img/thumbs/2.jpg" width="40" height="40" /></li>
                                <li class="commentBody">
                                    <a class="commentAuthor" href="#">Lottie Lot</a>
                                    <p>
                                        I wouldn't know. I am a bimbo. What's a script?
                                    </p>
                                    <div class="commentInfo"><span class="timeAgo">3 hours ago</span>&nbsp;&middot;&nbsp;<span class="commentActions"><i class="postMini i-commentLike Active"></i><a href="#">2</a></span></div>
                                </li>
                            </ul> <!-- comment inline -->
                        </li> <!-- comment -->
                        <li class="commentForm contentArea contentNoBottom commentBG">
                            <ul class="commentInline">
                                <li class="commentThumb"><img src="/images/commentImg.jpg" width="40" height="40" /></li>
                                <li class="commentTextarea">
                                    <textarea class="commentInput contentArea" placeholder="Your comment"></textarea>
                                </li>
                            </ul> <!-- comment inline -->                            
                        </li> <!-- comment form--> 
                    </ul> <!-- comments -->
                </div> <!-- comment OuterBox-->
            </article> <!--  listing -->
        </div> <!--  theLoop -->
    </div>
    <? include('rightbar.php') ?>
</div>

<a href="#" style="display:block;width:50px;border:1px solid #ddd;margin:0 150px auto;text-align: left" id="tips">TIPS</a>
<footer id="footer"></footer>
</body>
</html>