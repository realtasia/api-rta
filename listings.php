<? include('header.php') ?>   
<div id="container">
    <? //include('leftbar.php') ?>
    <div class="centerContent relative">
        <div class="outerBox centerWidth relative">
            <div class="contentArea introBox widgetContentBG relative">
                <h2 id="introHeader"><i class="directoryIcons i-listingsDirectory"></i>Listings Directory</h2>
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
                                    <li class="sqft"><i class="postMini i-sqft"></i>8,000 sqft</li>
                                    <li class="bathrooms"><i class="postMini i-bathrooms"></i>3 toilets</li>
                                    <li class="bedrooms"><i class="postMini i-bedrooms"></i>3 bedrooms</li>
                                    <li class="proptype"></li>
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
                                    <li class="sqft"><i class="postMini i-sqft"></i>8,000 sqft</li>
                                    <li class="bathrooms"><i class="postMini i-bathrooms"></i>3 toilets</li>
                                    <li class="bedrooms"><i class="postMini i-bedrooms"></i>3 bedrooms</li>
                                    <li class="proptype"></li>
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
    <footer id="footer"></footer>
</body>
</html>