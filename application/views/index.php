<? 
include('header.php') ?>
<section id="container">
    <? include('leftbar.php') ?>
    <div id="contentArea" class="centerContent">
        <section id="introContent">
            <h2>Alifah binte Abdul Salman</h2>
            <div class="pageInfo">
                <span class="pageType">Associate Director</span> at <span class="parentDesc">
                    <a href="#">DTZ Debenham Tie Leung</a>
                </span>  
                <div class="pageActions">
                    <a class="pageActionsButton btn btn-mini hasActiveState hasHoverState"><i class="action16 subscribe"></i>Subscribe</a>
                    <a class="pageActionsButton btn btn-mini hasActiveState hasHoverState"><i class="action16 likeSmall"></i>Like</a>                     
                    <a class="moreLink">View More<i class="Action16 infoCircleBG"></i></a>
                </div>
            </div>
        </section> <!--  introcontent -->
        <div class="outerBox rounded">
            <section class="contentArea widgetContentBG rounded" id="newPostArea">
                <ul class="newPostButtons">
                    <li class="hasActiveState hasHoverState"><a href="#"><i class="action16 postStatus"></i></a></li>
                    <li class="hasActiveState hasHoverState"><a href="#"><i class="action16 postTestimonial"></i></a></li>
                    <li class="statusRight" style="display:none"><button class="close" type="button">&times;</button></li>
                </ul>
                <div id="statusFormTA">                    
                    <textarea id="statusFormTextarea" class="rounded" placeholder="What's New"></textarea>
                </div>
                <div class="statusBoxExpand" style="display:none">
                    <ul class="newPostMediaButtons">
                        <li class="hasHoverState"><a href="#"><i class="action16 postImages"></i></a></li>
                        <li class="hasHoverState"><a href="#"><i class="action16 postVideo"></i></a></li>
                    </ul>
                    <button class="btn btn-info relative">Share</button>
                </div>
            </section>
        </div> <!--  status form -->        
        <section id="theLoop">
            <section id="feedActionDiv">                
                <div id="filterButtonDiv" class="dropdown hasHoverState hasActiveState">
                    <a data-toggle="dropdown" class="dropdown-toggle" id="filterButton" href="#">Show:&nbsp;<span class="dropDownSelected">All</span>&nbsp;<span class="caret"></span></a>
                    <ul class="dropdown-menu">
                        <li><a href="#" id="filterAll">All</a></li>
                        <li><a href="#" id="filterPopular">Published</a></li>
                        <li><a href="#" id="filterDraft">Draft</a></li>
                    </ul>                    
                </div>                
                <div id="sortButtonDiv" class="dropdown hasHoverState hasActiveState">
                    <a data-toggle="dropdown" class="btn btn-small dropdown-toggle" id="sortButton" href="#">Sort:&nbsp;<span class="sortSelected">Popular</span>&nbsp;<span class="caret"></span></a>
                    <ul class="dropdown-menu">
                        <li><a href="#" id="sortNewest">Newest</a></li>
                        <li><a href="#" id="sortPopular">Popular</a></li>
                    </ul>                    
                </div>
            </section> <!--  sort button & result count -->
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
                            <img src="/img/thumbs/1.jpg" alt="" />
                        </a>
                    </span>
                </div> <!-- post profile thumb -->
                <div class="postTitle">
                    <a href="#">Aishwarya Rai</a> shared a link in <a href="#">Casa Pasir Ris</a> Lorem ipsum dolar shit I
                    <p>I think this is an awesome script!!</p>
                </div> <!-- post Title -->
                <article class="postContent">
                    <ul class="postThumbnail thumbnails">
                        <li>
                            <div href="#" class="thumbnail">
                                <a href="#">
                                    <img src="/img/play_button.png" style="background:URL(http://img.youtube.com/vi/1kpBN4hCOps/2.jpg) center center black;background-size: 135px 110px;"  />
                                </a>
                                <span class="thumbLinks"><a href="www.youtube.com">www.youtube.com</a></span>
                            </div>
                        </li>
                    </ul> <!-- post thumbnail -->
                    <p class="thumbPostDesc">
                        <a href="#">This home is located in The Woodlands, TX.</a> Updated kitchen! Large master with volumous ceiling!Large walk-in closet! Master bath upgraded! Darling kids bedrooms! Approx 600 s.f. extra room was 3-car garage converted to bonus/playroom with laminate floors, fantastic room! much more!
                    </p> <!-- post description -->
                </article> <!-- post content -->
                <div class="postActions">
                    <ul class="postActionButtons">
                        <li class="hasActiveState hasHoverState"><a href="#"><i class="action20 share"></i></a></li>
                        <li class="hasActiveState hasHoverState"><a href="#"><i class="action20 subscribe"></i></a></li>
                        <li class="hasActiveState hasHoverState"><a href="#"><i class="action20 like"></i></a></li>
                    </ul>
                    <ul class="postActionInfo">
                        <li class="hasHoverState"><a href="#"><i class="action16 comments"></i>51</a></li>
                        <li class="hasHoverState"><a href="#"><i class="info16 people"></i>215</a></li>
                        <li class="hasHoverState"><a href="#"><i class="action16 likeSmall"></i>167</a></li>
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
                                    <div class="commentInfo"><span class="timeAgo">3 hours ago</span>&nbsp;&middot;&nbsp;<span class="commentActions"><i class="action16 likeSmall"></i><a href="#">2</a></span></div>
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
                                    <div class="commentInfo"><span class="timeAgo">17 minutes ago</span>&nbsp;&middot;&nbsp;<span class="commentActions"><i class="action16 likeSmall"></i><a href="#">2</a></span></div>
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
            </article> <!--  youtube -->
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
                    <a href="#">Aishwarya Rai</a> wrote a post on her profile page
                    <p>Lorem Ipsum is simply dummy text of the printing and typesetting industry.
                        <br />
                        Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book.
                </div> <!-- post Title -->
                <div class="postActions">
                    <ul class="postActionButtons">
                        <li class="hasActiveState hasHoverState"><a href="#"><i class="action20 share"></i></a></li>
                        <li class="hasActiveState hasHoverState"><a href="#"><i class="action20 subscribe"></i></a></li>
                        <li class="hasActiveState hasHoverState"><a href="#"><i class="action20 like"></i></a></li>
                    </ul>
                    <ul class="postActionInfo">
                        <li class="hasHoverState"><a href="#"><i class="action16 comments"></i>51</a></li>
                        <li class="hasHoverState"><a href="#"><i class="info16 people"></i>215</a></li>
                        <li class="hasHoverState"><a href="#"><i class="action16 likeSmall"></i>167</a></li>
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
                                    <div class="commentInfo"><span class="timeAgo">3 hours ago</span>&nbsp;&middot;&nbsp;<span class="commentActions"><i class="action16 likeSmall"></i><a href="#">2</a></span></div>
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
                                    <div class="commentInfo"><span class="timeAgo">3 hours ago</span>&nbsp;&middot;&nbsp;<span class="commentActions"><i class="action16 likeSmall"></i><a href="#">2</a></span></div>
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
            </article> <!--  status -->
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
                        <li class="hasActiveState hasHoverState"><a href="#"><i class="action20 share"></i></a></li>
                        <li class="hasActiveState hasHoverState"><a href="#"><i class="action20 subscribe"></i></a></li>
                        <li class="hasActiveState hasHoverState"><a href="#"><i class="action20 like"></i></a></li>
                    </ul>
                    <ul class="postActionInfo">
                        <li class="hasHoverState"><a href="#"><i class="action16 comments"></i>51</a></li>
                        <li class="hasHoverState"><a href="#"><i class="info16 people"></i>215</a></li>
                        <li class="hasHoverState"><a href="#"><i class="action16 likeSmall"></i>167</a></li>
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
                                    <div class="commentInfo"><span class="timeAgo">3 hours ago</span>&nbsp;&middot;&nbsp;<span class="commentActions"><a href="#"><i class="Action16 likeSmall Active"></i>2</a></span></div>
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
                                    <div class="commentInfo"><span class="timeAgo">17 minutes ago</span>&nbsp;&middot;&nbsp;<span class="commentActions"><a href="#"><i class="action16 likeSmall"></i>2</a></span></div>
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
            </article> <!--  yotube -->
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
                    <a href="#">Sweet Stuff</a> wrote a testimonial about <a href="#">Jason Nathan</a>
                    <p><?= nl2br("<blockquote class='triangle-border left'><p class='limitLines testimonial'>When I relocated to Singapore I looked at the services provided by a multitude of real estate agents and sadly found most of them lacking in a service-minded approach to the task. Jason proved to be the real exception to the rule.

                                He actively listened to my needs, was thorough in his search process and able and willing to adapt accordingly to my changing needs.

                                I found Jason to be very well acquainted with the Singapore real estate scene. He was reliable, dependent and always on time for multiple viewings. Jason's amiable and outgoing personality also meant that he negotiated successfully on my behalf with potential landlords.

                                Jason's commitment did not stop only at the contract signature stage. He also ensured that my family and I had a hassle-free settling in, also with respect to services (phone, gas, electricity etc.). In fact I ended up recommending Jason's services also to my employer at that time for other people relocating to Singapore. All of them also shared my views on Jason.

                                In short, if you are looking for a reliable, amiable and partner-friendly real estate agent, then Jason would be your man in Singapore! I am available for any futher information needed.</p></blockquote>") ?></p>
                </div> <!-- post Title -->
                <div class="postActions">
                    <ul class="postActionButtons">
                        <li class="hasActiveState hasHoverState"><a href="#"><i class="action20 like"></i></a></li>
                    </ul>
                    <ul class="postActionInfo">
                        <li class="hasHoverState"><a href="#"><i class="action16 likeSmall"></i>167</a></li>
                    </ul>
                </div> <!-- post action links -->
            </article> <!--  testimonial -->
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
                                <span class="thumbInfo"><a href="#"><i class="Info16 folderPic"></i>15 images</a></span>
                                <span class="thumbInfo"><a href="#"><i class="Info16 folderVid"></i>1 video</a></span>
                            </div> <!-- listings thumbnail -->
                            <div class="listingRight relative">
                                <h3><a href="#">Carribean @ Keppel Bay</a></h3>
                                <span class="price relative">
                                    <span class="listingType">FOR<br />SALE</span>
                                    $200,000,000
                                </span>
                                <ul class="listingData">
                                    <li><i class="Info16 sqft"></i>8,000 sqft</li>
                                    <li><i class="Info16 bathrooms"></i>3 toilets</li>
                                    <li><i class="Info16 bedrooms"></i>3 bedrooms</li>
                                    <li></li>
                                </ul>
                                <p>
                                    Need 1 Billion to vote for Palestine Google is having a vote on whether to name Israel or Palestine on its map on Google earth please vote for Palestine and share.
                                </p>
                            </div> <!-- listings content -->
                        </div>
                    </article>
                </div> <!-- post content -->
                <div class="postActions">
                    <ul class="postActionButtons">
                        <li class="hasActiveState hasHoverState"><a href="#"><i class="action20 share"></i></a></li>
                        <li class="hasActiveState hasHoverState"><a href="#"><i class="action20 subscribe"></i></a></li>
                        <li class="hasActiveState hasHoverState"><a href="#"><i class="action20 like"></i></a></li>
                    </ul>
                    <ul class="postActionInfo">
                        <li class="hasHoverState"><a href="#"><i class="action16 comments"></i>51</a></li>
                        <li class="hasHoverState"><a href="#"><i class="info16 people"></i>215</a></li>
                        <li class="hasHoverState"><a href="#"><i class="action16 likeSmall"></i>167</a></li>
                    </ul>
                </div> <!-- post action links -->
                <div class="indentedComment widgetOuterBox">
                    <ul class="commentList contentArea commentBG">
                        <li class="contentArea commentBG">
                            <ul class="commentInline">
                                <li class="commentThumb"><img src="/img/thumbs/2.jpg" width="40" height="40" /></li>
                                <li class="commentBody">
                                    <a class="commentAuthor" href="#">Lottie Lot</a>
                                    <p>
                                        I wouldn't know. I am a bimbo. What's a script?
                                    </p>
                                    <div class="commentInfo"><span class="timeAgo">3 hours ago</span>&nbsp;&middot;&nbsp;<span class="commentActions"><a href="#"><i class="Action16 likeSmall"></i>2</a></span></div>
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
                                    <div class="commentInfo"><span class="timeAgo">17 minutes ago</span>&nbsp;&middot;&nbsp;<span class="commentActions"><a href="#"><i class="action16 likeSmall"></i>2</a></span></div>
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
        </section> <!--  theLoop -->        
    </div>
    <? include('rightbarMember.php') ?>
</section>
<footer id="footer"></footer>
</body>
</html>