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
        <form class="profileListings" accept-charset="utf-8" id="filterform" method="GET" action="/members">
            <div id="wraparound" class="outerBox relative">
                <div id="search-wrap" class="filterContentBG contentArea contentNoBottom">
                    <div class="inputFill" style="height:29px;width:220px;">
                        <div class="inputFill" style="height:29px;width:160px;float:left">
                            <input type="text" id="searchInput" style="width:160px;" placeholder="Search Listings" />
                        </div>
                        <button type="submit" class="btn" style="width:60px;margin:0;height:28px;">Search</button>
                    </div>
                    <button class="btnReset directoryIcons i-map" id="mapBtn"></button>
                </div>
            </div> <!-- SEARCH BOX -->
            <div style="display:none;" id="drop-down-filter" class="centerWidth outerBox relative shadow">
                <div class="filterBox filterContentBG contentArea contentNoTop">
                    <div class="formContainer">
                        <div class="containerLeft">
                            <label>Property Type</label>
                            <select class="full" id="propType">
                                <option></option>
                                <option>Residential Apartments</option>
                                <option>Residential Landed</option>
                                <option>HDB</option>
                            </select>
                        </div>
                        <div class="containerRight">
                            <label>Size (sqft)</label>
                            <select id="sizeFrom" class="half">
                                <option></option>
                                <option>1</option>
                                <option>2</option>
                                <option>3</option>
                                <option>4</option>
                                <option>5</option>
                                <option>6</option>
                                <option>7</option>
                                <option>8</option>
                                <option>9</option>
                                <option>10</option>
                            </select>
                            <select id="sizeTo" class="half">
                                <option></option>
                                <option>1</option>
                                <option>2</option>
                                <option>3</option>
                                <option>4</option>
                                <option>5</option>
                                <option>6</option>
                                <option>7</option>
                                <option>8</option>
                                <option>9</option>
                                <option>10</option>
                            </select>                            
                        </div>
                        <div class="containerLeft">
                            <label>Price Range</label>
                            <label id="slidePriceLabel">$700,000 - $1,000,000</label>
                        </div>
                        <div class="containerRight">
                            <label>Bedrooms</label>
                            <select class="half" id="roomsFrom">
                                <option>STUDIO</option>
                                <option>2</option>
                                <option>3</option>
                                <option>4</option>
                                <option>5</option>
                                <option>6</option>
                                <option>7</option>
                                <option>8</option>
                                <option>9</option>
                                <option>10</option>
                            </select>
                            <select class="half" id="roomsTo">
                                <option>STUDIO</option>
                                <option>2</option>
                                <option>3</option>
                                <option>4</option>
                                <option>5</option>
                                <option>6</option>
                                <option>7</option>
                                <option>8</option>
                                <option>9</option>
                                <option>10</option>
                            </select>                            
                        </div>
                        <div class="containerLeft"></div>
                        <div class="containerRight">
                        <div class="formButtons">
                            <button class="btnRound btnReset" id="resetSearch">Reset</button>
                            <button class="btnRound btnReset" id="cancelSearch">Close</button>
                            <button class="btnRound btnReset" id="doSearch">Go</button>
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
                                    <li class="sqft"><i class="postMini i-sqft"></i>8,000 sqft</li>
                                    <li class="bathrooms"><i class="postMini i-bathrooms"></i>3 toilets</li>
                                    <li class="bedrooms"><i class="postMini i-bedrooms"></i>3 bedrooms</li>
                                    <li class="proptype"></li>
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