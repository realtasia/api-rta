<script type="text/javascript" src="/assets/js/medialib.js"></script>
<div style="margin:0 auto 10px auto;text-align: center;width:60%;">
<!--    <textarea id="log" style="height:100px;border:1px solid #999;text-align: left;float:none;overflow:auto;overflow-x:hidden;"></textarea>-->
</div>
<section class="RTAWindow fatWidth" id="mediaLibrary">
    <header class="titleBar">
        <div class="contentArea titleBarBG">
            <h6>Media Library <small><span class="number"></span></small>
                <button class="close" type="button">×</button>
            </h6>
        </div>
    </header>
    <!--    <nav class="windowMenuBar">
            <ul class="windowMenu" style="position:relative;">
                <li class="hasHoverState hasActiveState isNavigation">
                    <a class="windowMenuItem" href="#"><i class="social26 rta"></i>Realtasia</a>
                </li>
                <li class="hasHoverState hasActiveState isNavigation">
                    <a class="windowMenuItem" href="#"><i class="social26 facebook"></i>Facebook</a>
                </li>
                <li class="hasHoverState hasActiveState isNavigation">
                    <a class="windowMenuItem" href="#"><i class="social26 youtube"></i>YouTube</a>
                </li>
                <li class="hasHoverState hasActiveState isNavigation">
                    <a class="windowMenuItem" href="#"><i class="social26 flickr"></i>Flickr</a>
                </li>
                <li class="hasHoverState hasActiveState isNavigation">
                    <a class="windowMenuItem" href="#"><i class="social26 picasa"></i>Picasa</a>
                </li>
                <li class="hasHoverState hasActiveState rightMenuItem isNavigation">
                    <a class="windowMenuItem" href="#" data-bind="click:showUpload"><i class="action20 upload"></i>Upload</a>
                </li>
            </ul>
        </nav>-->
    <section class="RTAWindowContainer">
        <div class="leftMargin albumMenu">
            <ul class="nav nav-pills" style="margin-right:5px;">
                <li class="albumLeft">
                    <h4><a href="#">Album Name</a></h4>
                    <input type="text" value="Album Name" style="display:none;" />
                    <a class="muted" href="#">Enter an album description.</a>
                    <input type="text" value="Enter an album description." style="display:none;" />
                </li>
                <li class="btn-toolbar albumRight">
                    <div class="btn-group">
                        <a href="#" class="btn btn-small">Visibility <span class="caret"></span></a>
                        <ul class="dropdown-menu" role="menu" aria-labelledby="dLabel">
                            <li><a tabindex="-1" href="#">Action</a></li>
                            <li><a tabindex="-1" href="#">Another action</a></li>
                            <li><a tabindex="-1" href="#">Something else here</a></li>
                            <li class="divider"></li>
                            <li><a tabindex="-1" href="#">Separated link</a></li>
                        </ul>
                    </div>

                </li>
            </ul>
        </div>
        <ul class="nav nav-pills nav-small" style="margin-left:15%">
            <li class="active">
                <a href="#">All
                    <span data-bind="text: '(' + allMedia() + ')'"></span>
                </a>
            </li>
            <li>
                <a href="#">Pictures
                    <span data-bind="text: '(' + allMedia() + ')'"></span>
                </a>
            </li>
            <li>
                <a href="#">Documents
                    <span data-bind="text: '(' + allMedia() + ')'"></span>
                </a>
            </li>
        </ul>
        <section class="folderView">
            <nav class="tabbable tabs-left">
                <ul class="nav nav-list">
                    <li><i><img src="" width="16px"></i><a href="#" class="folderNode">All Media
                            <span class="muted" data-bind="text: '(' + counts.pictures() + ')'"></span></a>
                    </li>
                    <li><i><img src="" width="16px"></i><a href="#" class="folderNode">Albums
                            <span class="muted" data-bind="text: '(' + counts.albums.public() + ')'"></span></a>
                    </li>
                </ul>
            </nav>
            <div style="clear:both;"></div>
        </section>
        <section class="RTAWindowContent">
            <ul class="thumbnails" id="thumbnails">
                <!--ko foreach : {data : files}-->
                <li style="position:relative;" class="imageItem">
                    <ul class="imageBar" data-bind="visible : isHovering">
                        <li>
                            <a href="#" class="deleteFile">
                                <i class="action16 xCircleBG"></i>
                            </a>
                        </li>
                        <li>
                            <a href="#" data-bind="click: edit" class="editFile">
                                <i class="action16 edit"></i>
                            </a>
                        </li>
                        <li>
                            <a href="#" style="margin-right: 1px;" class="hovering">
                                <i class="action16 likeSmall"></i>
                            </a>
                        </li>
                        <li>
                            <a href="#" style="margin-right: 1px;" class="hovering">
                                <i class="action16 subscribe"></i>
                            </a>
                        </li>
                    </ul>

                    <a class="thumbnail" href="#" data-bind="flash : src">
                        <img onLoad="$(this).fadeTo('slow', 1)" class="imgPreview" data-bind="attr : {src : src}" />
                    </a>
                    <p class="thumbCaption" data-bind="click: edit, text: name, visible : !editing()"></p>
                    <input class="edit" data-bind="value: name, visible : editing,
                           enterKey: stopEditing,
                           selectAndFocus: editing,
                           event: { blur: stopEditing }" />
                </li>
                <!--/ko-->
            </ul>
            <div style="clear:both;"></div>
        </section>
    </section>
    <footer class="footerBar"></footer>
</section>