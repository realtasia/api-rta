<? include('header.php') ?>
<div id="container">
    <? include('leftbar.php') ?>
    <div id="contentArea" class="centerContent">
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
        <div class="outerBox rounded" style="display:none;">
            <div class="contentArea widgetContentBG rounded" id="newPostArea">
                <ul class="newPostButtons">
                    <li><a href="#"><i class="postMini i-postStatus"></i></a></li>
                    <li><a href="#"><i class="postMini i-postTestimonial"></i></a></li>
                </ul>
                <div id="statusFormTA">
                    <button class="close" type="button">&times;</button>
                    <textarea id="statusFormTextarea" class="rounded" placeholder="What's New"></textarea>
                </div>
                <ul class="newPostMediaButtons">
                    <li><a href="#"><i class="postMini i-postImages"></i></a></li>
                    <li><a href="#"><i class="postMini i-postVideo"></i></a></li>
                </ul>
                <button class="btn btn-info relative">Share</button>
            </div>
        </div> <!--  status form -->
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
            <article class="postFeed editMode Approved infoBorderColor">
                <div class="dropdown postSettings infoBorderColor infoBGColor">
                    <div class="containerLeft">
                        <label class="label label-info">Approved</label>
                    </div>
                    <div class="containerRight">
                        <div class="btn-group">
                            <a class="btn btn action"><i class="formIcons i-approve Deactive"></i>UnApprove</a>
                            <a class="btn dropdown-toggle" data-toggle="dropdown">
                                <span class="caret"></span>
                            </a>
                            <ul class="dropdown-menu">
                                <li><a><i class="formIcons i-approve Deactive"></i>UnApprove</a></li>
                                <li><a><i class="formIcons i-archive"></i>Archive</a></li>
                                <li><a><i class="formIcons i-trash"></i>Trash</a></li>
                            </ul>
                        </div>
                    </div>
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
                <div class="postActions" style="display:none">
                    <ul class="postActionButtons">
                        <li><a href="#"><i class="postActionsIcons i-like"></i></a></li>
                    </ul>
                    <ul class="postActionInfo">
                        <li><a href="#"><i class="postMini i-likes"></i>167</a></li>
                    </ul>
                </div> <!-- post action links -->
            </article> <!--  approved -->
            <article class="postFeed editMode unApproved warningBorderColor">
                <div class="dropdown postSettings warningBorderColor warningBGColor">
                    <div class="containerLeft">
                        <label class="label label-warning">Pending Approval</label>
                    </div>
                    <div class="containerRight">
                        <div class="btn-group">
                            <a class="btn btn action"><i class="formIcons i-approve"></i>Approve</a>
                            <a class="btn dropdown-toggle" data-toggle="dropdown">
                                <span class="caret"></span>
                            </a>
                            <ul class="dropdown-menu">
                                <li><a><i class="formIcons i-approve Deactive"></i>Un Approve</a></li>
                                <li><a><i class="formIcons i-archive"></i>Archive</a></li>
                                <li><a><i class="formIcons i-trash"></i>Trash</a></li>
                            </ul>
                        </div>
                    </div>
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
                        <li><a href="#"><i class="postActionsIcons i-like"></i></a></li>
                    </ul>
                    <ul class="postActionInfo">
                        <li><a href="#"><i class="postMini i-likes"></i>167</a></li>
                    </ul>
                </div> <!-- post action links -->
            </article> <!--  pending -->
            <article class="postFeed editMode inverseBorderColor unApproved">
                <div class="dropdown postSettings inverseBorderColor inverseBGColor">
                    <div class="containerLeft">
                        <label class="label label-inverse">Trashed</label>
                    </div>
                    <div class="containerRight">
                        <div class="btn-group">
                            <a class="btn btn action"><i class="formIcons i-approve"></i>Approve</a>
                            <a class="btn dropdown-toggle" data-toggle="dropdown">
                                <span class="caret"></span>
                            </a>
                            <ul class="dropdown-menu">
                                <li><a><i class="formIcons i-approve Deactive"></i>Un Approve</a></li>
                                <li><a><i class="formIcons i-archive"></i>Archive</a></li>
                                <li><a><i class="formIcons i-trash"></i>Trash</a></li>
                            </ul>
                        </div>
                    </div>
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
                <div class="postActions" style="display:none">
                    <ul class="postActionButtons">
                        <li><a href="#"><i class="postActionsIcons i-like"></i></a></li>
                    </ul>
                    <ul class="postActionInfo">
                        <li><a href="#"><i class="postMini i-likes"></i>167</a></li>
                    </ul>
                </div> <!-- post action links -->
            </article> <!--  trashed -->
        </div> <!--  theLoop -->
    </div>
    <? include('rightbarMember.php') ?>
</div>
<footer id="footer"></footer>
</body>
</html>