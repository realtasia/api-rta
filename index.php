<? include('header.php') ?>
<div id="container">
    <? include('leftbar.php') ?>
    <div id="contentArea" class="centerContent">
        <div id="introContent">
            <h2>Aishwarya Rai</h2>
            <div class="pageInfo"><span class="pageType">Director, Regional Sales & Marketing</span> at <span class="parentDesc"><a href="#">DTZ Debenham Tie Leung</a></span></div>


            <p>
                <?= nl2br('Aishwarya Rai the Miss World stunning beauty queen is considered to be one of the beautiful actresses in the movie industry. She has done movies in languages like Hindi, Tamil, English and Bengali.
                            In the year 1994, she won the Miss World title and brought fame to her mother land.') ?>
            </p>
        </div> <!--  introcontent -->
        <div class="outerBox">
            <div class="contentArea">
                <div id="statusFormTA">
                    <textarea id="statusFormTextarea" placeholder="What's New"></textarea>
                </div>
            </div>
        </div> <!--  status form -->
        <div id="theLoop">
            <div class="dropdown" id="feedActionDiv">
                
                <div id="filterButtonDiv" class="dropdown">
                    <a data-toggle="dropdown" class="dropdown-toggle" id="filterButton" href="#">Show:&nbsp;<span class="dropDownSelected">All</span>&nbsp;<span class="caret"></span></a>
                    <ul class="dropdown-menu">
                        <li><a href="#sortNewest" id="sortNewest">Newest</a></li>
                        <li><a href="#sortPopular" id="sortPopular">Popular</a></li>
                    </ul>                    
                </div>                
                
                <div id="sortButtonDiv" class="dropdown">
                    <button data-toggle="dropdown" class="btn btn-small dropdown-toggle" id="sortButton">Sort:&nbsp;<span class="sortSelected">Popular</span>&nbsp;<span class="caret"></span></button>
                    <ul class="dropdown-menu">
                        <li><a href="#sortNewest" id="sortNewest">Newest</a></li>
                        <li><a href="#sortPopular" id="sortPopular">Popular</a></li>
                    </ul>                    
                </div>
            </div> <!--  sort button & result count -->
            <article class="postFeed">
                <div class="dropdown postSettings">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown"><b class="icon-cog"></b></a>
                    <ul class="dropdown-menu">
                        <li><a href="#">Hide Post</a></li>
                        <li><a href="#">UnSubscribe</a></li>
                        <li class="divider"></li>
                        <li><a href="#">Report Post</a></li>
                    </ul>
                </div> <!-- post settings -->
                <ul class="feedProfileThumbs thumbnails">
                    <li class="mediumNail">
                        <a href="#" class="thumbnail">
                            <img src="/img/thumbs/1.jpg" alt="">
                        </a>
                    </li>
                </ul> <!-- post profile thumb -->
                <div class="postTitle">
                    <a href="#">Aishwarya Rai</a> shared a link in <a href="#">Casa Pasir Ris</a> Lorem ipsum dolar shit I
                    <p>I think this is an awesome script!!</p>
                </div> <!-- post Title -->
                <article class="postContent">
                    <ul class="postThumbnail thumbnails">
                        <li>
                            <div href="#" class="thumbnail">
                                <a href="#">
                                    <img src="/img/play_button.png" style="background:URL(http://i4.ytimg.com/vi/oCvkb8aNuys/hqdefault.jpg) center center black;background-size: 135px 110px;"  />
                                </a>
                                <span class="thumbLinks"><a href="www.youtube.com">www.youtube.com</a></span>
                            </div>
                        </li>
                    </ul> <!-- post thumbnail -->
                    <p class="thumbPostDesc">
                        <a href="#">This will run the script from an online directory</a> (a.k.a. folder). Alternatively you can download [Ref 3] the files choosing the latest version � 2.1(beta4) at the time of writing � and unzip them. You will find a directory 'ie7' and a sub directory 'src'. Both contain essentially the same files.
                    </p> <!-- post description -->
                </article> <!-- post content -->
                <div class="postActions">
                    <ul class="postActionButtons">
                        <li><a href="#"><i class="icons i-share"></i></a></li>
                        <li><a href="#"><i class="icons i-subscribe"></i></a></li>
                        <li><a href="#"><i class="icons i-like"></i></a></li>
                    </ul>
                    <ul class="postActionInfo">
                        <li class="icons i-comments"><a href="#">1</a></li>
                        <li class="icons i-subscribers"><a href="#">1</a></li>
                        <li class="icons i-likes"><a href="#">67</a></li>
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
                                    <div class="commentInfo"><span class="timeAgo">3 hours ago</span>&nbsp;&middot;&nbsp;<span class="icons i-likes commentActions"><a href="#">2</a></span></div>
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
                                    <div class="commentInfo"><span class="timeAgo">3 hours ago</span>&nbsp;&middot;&nbsp;<span class="icons i-likes commentActions"><a href="#">2</a></span></div>
                                </li>
                            </ul> <!-- comment inline -->
                        </li> <!-- comment -->
                        <li class="contentArea commentBG">
                            <ul class="commentInline">
                                <li class="commentThumb"><img src="/images/commentImg.jpg" width="40" height="40" /></li>
                                <li class="commentBody">
                                    <a class="commentAuthor" href="#">Susan Doyle</a>
                                    <p>
                                        Need 1 Billion to vote for Palestine Google is having a vote on whether to name israel or Palestine on its map on Google earth please vote for Palestine and share.
                                    </p>
                                    <div class="commentInfo"><span class="timeAgo">17 minutes ago</span>&nbsp;&middot;&nbsp;<span class="icons i-likes commentActions"><a href="#">2</a></span></div>
                                </li>
                            </ul> <!-- comment inline -->
                        </li> <!-- comment -->
                        <li class="commentForm contentArea contentNoBottom commentBG">
                            <textarea placeholder="Write your comment"></textarea>
                        </li> <!-- comment form-->
                    </ul> <!-- comments -->
                </div> <!-- comment OuterBox-->
            </article> <!--  youtube -->
            <article class="postFeed">
                <div class="dropdown postSettings">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown"><b class="icon-cog"></b></a>
                    <ul class="dropdown-menu">
                        <li><a href="#">Hide Post</a></li>
                        <li><a href="#">UnSubscribe</a></li>
                        <li class="divider"></li>
                        <li><a href="#">Report Post</a></li>
                    </ul>
                </div> <!-- post settings -->
                <ul class="feedProfileThumbs thumbnails">
                    <li class="mediumNail">
                        <a href="#" class="thumbnail">
                            <img src="/img/thumbs/1.jpg" alt="">
                        </a>
                    </li>
                </ul> <!-- post profile thumb -->
                <div class="postTitle">
                    <a href="#">Aishwarya Rai</a> wrote a post on her profile page
                    <p>Lorem Ipsum is simply dummy text of the printing and typesetting industry.
                        <br />
                        Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book.
                </div> <!-- post Title -->
                <div class="postActions">
                    <ul class="postActionButtons">
                        <li><a href="#"><i class="icons i-share"></i></a></li>
                        <li><a href="#"><i class="icons i-subscribe"></i></a></li>
                        <li><a href="#"><i class="icons i-like"></i></a></li>
                    </ul>
                    <ul class="postActionInfo">
                        <li class="icons i-comments"><a href="#">1</a></li>
                        <li class="icons i-subscribers"><a href="#">1</a></li>
                        <li class="icons i-likes"><a href="#">67</a></li>
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
                                    <div class="commentInfo"><span class="timeAgo">3 hours ago</span>&nbsp;&middot;&nbsp;<span class="icons i-likes commentActions"><a href="#">2</a></span></div>
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
                                    <div class="commentInfo"><span class="timeAgo">3 hours ago</span>&nbsp;&middot;&nbsp;<span class="icons i-likes commentActions"><a href="#">2</a></span></div>
                                </li>
                            </ul> <!-- comment inline -->
                        </li> <!-- comment -->
                        <li class="contentArea commentBG">
                            <ul class="commentInline">
                                <li class="commentThumb"><img src="/images/commentImg.jpg" width="40" height="40" /></li>
                                <li class="commentBody">
                                    <a class="commentAuthor" href="#">Susan Doyle</a>
                                    <p>
                                        Need 1 Billion to vote for Palestine Google is having a vote on whether to name israel or Palestine on its map on Google earth please vote for Palestine and share.
                                    </p>
                                    <div class="commentInfo"><span class="timeAgo">17 minutes ago</span>&nbsp;&middot;&nbsp;<span class="icons i-likes commentActions"><a href="#">2</a></span></div>
                                </li>
                            </ul> <!-- comment inline -->
                        </li> <!-- comment -->
                        <li class="commentForm contentArea contentNoBottom commentBG">
                            <textarea placeholder="Write your comment"></textarea>
                        </li> <!-- comment form-->
                    </ul> <!-- comments -->
                </div> <!-- comment OuterBox-->
            </article> <!--  status -->
            <article class="postFeed">
                <div class="dropdown postSettings">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown"><b class="icon-cog"></b></a>
                    <ul class="dropdown-menu">
                        <li><a href="#">Hide Post</a></li>
                        <li><a href="#">UnSubscribe</a></li>
                        <li class="divider"></li>
                        <li><a href="#">Report Post</a></li>
                    </ul>
                </div> <!-- post settings -->
                <ul class="feedProfileThumbs thumbnails">
                    <li class="mediumNail">
                        <a href="#" class="thumbnail">
                            <img src="/img/thumbs/3.jpg" alt="">
                        </a>
                    </li>
                </ul> <!-- post profile thumb -->
                <div class="postTitle">
                    <a href="#">Pretty Gal</a> shared a link in <a href="#">Lakepoint Condominium</a>
                    <p>This is like a castle. Off the western coast on the U.S., I think</p>
                </div> <!-- post Title -->
                <article class="postContent">
                    <ul class="postThumbnail thumbnails">
                        <li>
                            <div href="#" class="thumbnail">
                                <a href="#">
                                    <img src="/img/play_button.png" style="background:URL(http://i2.ytimg.com/vi/q2lulpmMS2k/hqdefault.jpg) center center black; background-size: 130px 100px;" />
                                </a>
                                <span class="thumbLinks"><a href="www.youtube.com">www.youtube.com</a></span>
                            </div>
                        </li>
                    </ul> <!-- post thumbnail -->
                    <p class="thumbPostDesc">
                        <a href="#">Luxurious Houses</a>, expensive villas, beach maisons in Dubai, Caribic, Hawai, Barbados, Europe, California and Florida ...
                    </p> <!-- post description -->
                </article> <!-- post content -->
                <div class="postActions">
                    <ul class="postActionButtons">
                        <li><a href="#"><i class="icons i-share"></i></a></li>
                        <li><a href="#"><i class="icons i-subscribe"></i></a></li>
                        <li><a href="#"><i class="icons i-like"></i></a></li>
                    </ul>
                    <ul class="postActionInfo">
                        <li class="icons i-comments"><a href="#">1</a></li>
                        <li class="icons i-subscribers"><a href="#">1</a></li>
                        <li class="icons i-likes"><a href="#">67</a></li>
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
                                    <div class="commentInfo"><span class="timeAgo">3 hours ago</span>&nbsp;&middot;&nbsp;<span class="icons i-likes commentActions"><a href="#">2</a></span></div>
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
                                    <div class="commentInfo"><span class="timeAgo">3 hours ago</span>&nbsp;&middot;&nbsp;<span class="icons i-likes commentActions"><a href="#">2</a></span></div>
                                </li>
                            </ul> <!-- comment inline -->
                        </li> <!-- comment -->
                        <li class="contentArea commentBG">
                            <ul class="commentInline">
                                <li class="commentThumb"><img src="/images/commentImg.jpg" width="40" height="40" /></li>
                                <li class="commentBody">
                                    <a class="commentAuthor" href="#">Susan Doyle</a>
                                    <p>
                                        Need 1 Billion to vote for Palestine Google is having a vote on whether to name israel or Palestine on its map on Google earth please vote for Palestine and share.
                                    </p>
                                    <div class="commentInfo"><span class="timeAgo">17 minutes ago</span>&nbsp;&middot;&nbsp;<span class="icons i-likes commentActions"><a href="#">2</a></span></div>
                                </li>
                            </ul> <!-- comment inline -->
                        </li> <!-- comment -->
                        <li class="commentForm contentArea contentNoBottom commentBG">
                            <textarea placeholder="Write your comment"></textarea>
                        </li> <!-- comment form-->
                    </ul> <!-- comments -->
                </div> <!-- comment OuterBox-->
            </article> <!--  yotube -->
            <article class="postFeed">
                <div class="dropdown postSettings">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown"><b class="icon-cog"></b></a>
                    <ul class="dropdown-menu">
                        <li><a href="#">Hide Post</a></li>
                        <li><a href="#">UnSubscribe</a></li>
                        <li class="divider"></li>
                        <li><a href="#">Report Post</a></li>
                    </ul>
                </div> <!-- post settings -->
                <ul class="feedProfileThumbs thumbnails">
                    <li class="mediumNail">
                        <a href="#" class="thumbnail">
                            <img src="/img/thumbs/7.jpg" alt="">
                        </a>
                    </li>
                </ul> <!-- post profile thumb -->
                <div class="postTitle">
                    <a href="#">Sweet Stuff</a> wrote a testimonial about <a href="#">Jason Nathan</a>
                    <p><?= nl2br("<blockquote class='triangle-border left'><p class='limitLines testimonial'>When I relocated to Singapore I looked at the services provided by a multitude of real estate agents and sadly found most of them lacking in a service-minded approach to the task. Jason proved to be the real exception to the rule.

                                He actively listened to my needs, was thorough in his search process and able and willing to adapt accordingly to my changing needs.

                                I found Jason to be very well acquainted with the Singapore real estate scene. He was reliable, dependent and always on time for multiple viewings. Jason's amiable and outgoing personality also meant that he negotiated successfully on my behalf with potential landlords.

                                Jason's commitment did not stop only at the contract signature stage. He also ensured that my family and I had a hassle-free settling in, also with respect to services (phone, gas, electricity etc.). In fact I ended up recommending Jason's services also to my employer at that time for other people relocating to Singapore. All of them also shared my views on Jason.

                                In short, if you are looking for a reliable, amiable and partner-friendly real estate agent, then Jason would be your man in Singapore! I am available for any futher information needed.</p></blockquote>") ?></p>
                </div> <!-- post Title -->
                <div class="postLinks">
                    <a href="#"><img src="/img/png/glyphicons_343_thumbs_up.png" />&nbsp;6</a>
                    <span class="preComment"></span>
                </div> <!-- post action links -->
            </article> <!--  testimonial -->
            <article class="postFeed">
                <div class="dropdown postSettings">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown"><b class="icon-cog"></b></a>
                    <ul class="dropdown-menu">
                        <li><a href="#">Hide Post</a></li>
                        <li><a href="#">UnSubscribe</a></li>
                        <li class="divider"></li>
                        <li><a href="#">Report Post</a></li>
                    </ul>
                </div> <!-- post settings -->
                <ul class="feedProfileThumbs thumbnails">
                    <li class="mediumNail">
                        <a href="#" class="thumbnail">
                            <img src="/img/thumbs/3.jpg" alt="">
                        </a>
                    </li>
                </ul> <!-- post profile thumb -->
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
                                <span class="thumbInfo imgInfo"><a href="#">15</a></span>
                                <span class="thumbInfo vidInfo"><a href="#">1</a></span>
                            </div> <!-- listings thumbnail -->
                            <div class="listingRight relative">
                                <h3><a href="#">Carribean @ Keppel Bay</a></h3>
                                <span class="price relative">
                                    <span class="listingType">FOR<br />SALE</span>
                                    $200,000,000
                                </span>
                                <ul class="listingData">
                                    <li class="sqft">8,000 sqft</li>
                                    <li class="bathrooms">3 toilets</li>
                                    <li class="bedrooms">3 bedrooms</li>
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
                        <li><a href="#"><i class="icons i-share"></i></a></li>
                        <li><a href="#"><i class="icons i-subscribe"></i></a></li>
                        <li><a href="#"><i class="icons i-like"></i></a></li>
                    </ul>
                    <ul class="postActionInfo">
                        <li class="icons i-comments"><a href="#">1</a></li>
                        <li class="icons i-subscribers"><a href="#">1</a></li>
                        <li class="icons i-likes"><a href="#">67</a></li>
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
                                    <div class="commentInfo"><span class="timeAgo">3 hours ago</span>&nbsp;&middot;&nbsp;<span class="icons i-likes commentActions"><a href="#">2</a></span></div>
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
                                    <div class="commentInfo"><span class="timeAgo">3 hours ago</span>&nbsp;&middot;&nbsp;<span class="icons i-likes commentActions"><a href="#">2</a></span></div>
                                </li>
                            </ul> <!-- comment inline -->
                        </li> <!-- comment -->
                        <li class="contentArea commentBG">
                            <ul class="commentInline">
                                <li class="commentThumb"><img src="/images/commentImg.jpg" width="40" height="40" /></li>
                                <li class="commentBody">
                                    <a class="commentAuthor" href="#">Susan Doyle</a>
                                    <p>
                                        Need 1 Billion to vote for Palestine Google is having a vote on whether to name israel or Palestine on its map on Google earth please vote for Palestine and share.
                                    </p>
                                    <div class="commentInfo"><span class="timeAgo">17 minutes ago</span>&nbsp;&middot;&nbsp;<span class="icons i-likes commentActions"><a href="#">2</a></span></div>
                                </li>
                            </ul> <!-- comment inline -->
                        </li> <!-- comment -->
                        <li class="commentForm contentArea commentBG">
                            <textarea placeholder="Write your comment"></textarea>
                        </li> <!-- comment form-->
                    </ul> <!-- comments -->
                </div> <!-- comment OuterBox-->
            </article> <!--  listing -->

        </div> <!--  theLoop -->
    </div>
    <? include('rightbar.php') ?>
</div>


<div class="centerContent relative">
    <div class="outerBox centerWidth relative">
        <div class="contentArea introBox widgetContentBG relative">
            <h2 id="introHeader">Listings Directory</h2>
            <p>Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book.</p>
        </div>
    </div> <!-- intro content -->

    <div class="outerBox centerWidth relative" style="margin-top:10px;">
        <div class="contentArea contentBox">
            <form class="member" accept-charset="utf-8" id="filterform" method="GET" action="/members">
                <div id="wraparound" class="outerBox relative">
                    <div id="search-wrap" class="filterContentBG contentArea contentNoBottom">
                        <div class="inputFill" style="height:29px;width:290px;">
                            <div class="inputFill" style="height:29px;width:220px;float:left">
                                <input type="text" id="searchInput" style="width:218px;" placeholder="Search Listings" />
                            </div>
                            <button type="submit" class="btn" style="width:70px;margin:0;height:28px;">Search</button>
                        </div>
                        <button class="btnRound" id="mapBtn"><img src="/images/map.png" /></button>
                    </div>
                </div> <!-- SEARCH BOX -->
                <div style="display:none;" id="drop-down-filter" class="centerWidthWide outerBox relative shadow">
                    <div class="filterBox filterContentBG contentArea contentNoTop">
                        <ul class="formTable">
                            <li class="span4 hasLabel rows">
                                <label class="formDesc">Property Type</label>
                                <select id="propType">
                                    <option></option>
                                    <option>Residential Apartments</option>
                                    <option>Residential Landed</option>
                                    <option>HDB</option>
                                </select>
                            </li> <!-- SELECT PROPTYPE -->
                            <li class="span3 hasLabel rows">
                                <label class="formDesc">Size (sq ft)</label>
                                <select id="sizeFrom">
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
                            </li> <!-- SELECT SIZE FROM -->
                            <li class="span1 noLabel rows">
                                <select id="sizeTo">
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
                            </li> <!-- SELECT SIZE TO -->
                            <li class="span4 hasLabel rows">
                                <label class="formDesc">Price Range</label>
                                <label id="slidePriceLabel">$700,000 - $1,000,000</label>
                            </li> <!-- PRICE LABEL -->
                            <li class="span3 hasLabel rows">
                                <label class="formDesc">Bedrooms</label>
                                <select id="roomsFrom">
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
                            </li> <!-- SELECT ROOMS FROM -->
                            <li class="span1 noLabel rows">
                                <select id="roomsTo">
                                    <option></option>
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
                            </li> <!-- SELECT ROOMS TO -->
                            <li class="formButtons">
                                <button class="btnRound" id="resetSearch">Reset</button>
                                <button class="btnRound" id="cancelSearch">Close</button>
                                <button class="btnRound" id="doSearch">Go</button>
                            </li> <!-- SEARCH FORM BUTTONS -->
                        </ul>
                    </div>
                </div>
                <input type="hidden" value="c6d6581afc6dda3ec151f8353a96a8d1ca3d865e" name="token">
            </form> <!-- SEARCH AREA -->
            <div id="sortBox">
                <div class="relative" id="resultCount"><h3><small>534 results found</small><span> in Properties for Sale</span></h3></div>
            </div> <!--  sort button & result count -->
            <div class="widgetOuterBox relative">
                <article class="contentArea widgetContentBG listing relative">
                    <div class="mainListingContent">
                        <div class="listingLeft relative">
                            <div class="thumbnail ">
                                <img src="/images/imphoto.jpg">
                            </div>
                            <span class="thumbInfo imgInfo"><a href="#">15 images</a></span>
                            <span class="thumbInfo vidInfo"><a href="#">1 video</a></span>
                        </div> <!-- listings thumbnail -->
                        <div class="listingRight relative">
                            <h3><a href="#">Carribean @ Keppel Bay</a></h3>
                            <span class="price relative">
                                <span class="listingType">FOR<br />SALE</span>
                                $200,000,000
                            </span>
                            <ul class="listingData">
                                <li class="sqft">8,000 sqft</li>
                                <li class="bathrooms">3 toilets</li>
                                <li class="bedrooms">3 bedrooms</li>
                                <li class="proptype"></li>
                            </ul>
                            <p>
                                Need 1 Billion to vote for Palestine Google is having a vote on whether to name Israel or Palestine on its map on Google earth please vote for Palestine and share.
                            </p>
                        </div> <!-- listings content -->
                    </div>
                    <div class="postActions">
                        <ul class="postedBy">
                            <li class="postedByLabel">Posted by:</li>
                            <li class="postedByThumb"><a href="#"><img src="/images/posterImg.jpg"/></a></li>
                            <li class="postedByName"><a href="#">Natalie Scott</a></li>
                        </ul>
                        <ul class="postActionButtons">
                            <li><a href="#"><i class="icons i-share"></i></a></li>
                            <li><a href="#"><i class="icons i-subscribe"></i></a></li>
                            <li><a href="#"><i class="icons i-like"></i></a></li>
                        </ul>
                        <ul class="postActionInfo">
                            <li class="icons i-comments"><a href="#">1</a></li>
                            <li class="icons i-subscribers"><a href="#">1</a></li>
                            <li class="icons i-likes"><a href="#">67</a></li>
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
                                    <li class="icons i-likes commentActions"><a href="#">2</a></li>
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
                                    <li class="icons i-likes commentActions"><a href="#">7</a></li>
                                </ul>

                            </li> <!-- comment -->
                            <li class="contentArea commentBG contentNoBottom">
                                <ul class="commentInline">
                                    <li class="commentThumb"><img src="/images/commentImg.jpg" width="42" height="42" /></li>
                                    <li class="commentTextarea">
                                        <textarea class="commentInput contentArea" placeholder="Write your comment"></textarea>
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
                            <span class="thumbInfo imgInfo"><a href="#">15 images</a></span>
                            <span class="thumbInfo vidInfo"><a href="#">1 video</a></span>
                        </div> <!-- listings thumbnail -->
                        <div class="listingRight relative">
                            <h3><a href="#">Carribean @ Keppel Bay</a></h3>
                            <span class="price relative"><span class="listingType">FOR<br />SALE</span>$200,000,000</span>
                            <ul class="listingData">
                                <li class="sqft">8,000 sqft</li>
                                <li class="bathrooms">3 toilets</li>
                                <li class="bedrooms">3 bedrooms</li>
                                <li class="proptype"></li>
                            </ul>
                            <p>
                                Need 1 Billion to vote for Palestine Google is having a vote on whether to name Israel or Palestine on its map on Google earth please vote for Palestine and share.
                            </p>
                        </div> <!-- listings content -->
                    </div>
                    <div class="postActions">
                        <ul class="postedBy">
                            <li class="postedByLabel">Posted by:</li>
                            <li class="postedByThumb"><a href="#"><img src="/images/posterImg.jpg"/></a></li>
                            <li class="postedByName"><a href="#">Natalie Scott</a></li>
                        </ul>
                        <ul class="postActionButtons">
                            <li><a href="#"><i class="icons i-share"></i></a></li>
                            <li><a href="#"><i class="icons i-subscribe"></i></a></li>
                            <li><a href="#"><i class="icons i-like"></i></a></li>
                        </ul>
                        <ul class="postActionInfo">
                            <li class="icons i-comments"><a href="#">1</a></li>
                            <li class="icons i-subscribers"><a href="#">1</a></li>
                            <li class="icons i-likes"><a href="#">67</a></li>
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
                                    <li class="icons i-likes commentActions"><a href="#">2</a></li>
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
                                    <li class="icons i-likes commentActions"><a href="#">7</a></li>
                                </ul>

                            </li> <!-- comment -->
                            <li class="contentArea commentBG contentNoBottom">
                                <ul class="commentInline">
                                    <li class="commentThumb"><img src="/images/commentImg.jpg" width="42" height="42" /></li>
                                    <li class="commentTextarea">
                                        <textarea class="commentInput contentArea" placeholder="Write your comment"></textarea>
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

<a href="#" style="display:block;width:50px;border:1px solid #ddd;margin:0 150px auto;text-align: left" id="tips">TIPS</a>
<script type="text/javascript">
    jQuery(document).ready(function($){
        $('#cancelSearch').click(function(e){e.preventDefault();$('#drop-down-filter').slideUp();})
        $('#searchInput').focus(function(e){e.preventDefault();$('#drop-down-filter').slideDown();})
        $('#propType').ddslick({
            width:180,
            selectText: "",
            background : "transparent url('/images/selectBG.gif') repeat-x" ,
            onSelected: function(selectedData){
                //callback function: do something with selectedData;
            }
        });
        $('#sizeFrom').ddslick({
            width:100,
            selectText: "",
            background : "transparent url('/images/selectBG.gif') repeat-x" ,
            onSelected: function(selectedData){
                //callback function: do something with selectedData;
            }
        });
        $('#sizeTo').ddslick({
            width:100,
            selectText: "",
            background : "transparent url('/images/selectBG.gif') repeat-x" ,
            onSelected: function(selectedData){
                //callback function: do something with selectedData;
            }
        });
        $('#roomsFrom').ddslick({
            width:100,
            selectText: "",
            background : "transparent url('/images/selectBG.gif') repeat-x" ,
            onSelected: function(selectedData){
                //callback function: do something with selectedData;
            }
        });
        $('#roomsTo').ddslick({
            width:100,
            selectText: "",
            background : "transparent url('/images/selectBG.gif') repeat-x" ,
            onSelected: function(selectedData){
                //callback function: do something with selectedData;
            }
        });
        $(window).bind('load', function() {
            $('#tips').bubbletip($('<div><h1>This is TIP</h1></div>'), {bindHide:false});
        });
    })
</script>


<footer id="footer"></footer>
</body>
</html>