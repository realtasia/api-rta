<div id="contentArea" class="centerContent">
    <section id="introContent">
        <!--        <nav class="windowMenuBar">
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

                    </ul>
                </nav>-->
    </section> <!--  introcontent -->
    <section id="theLoop">
        <section class="RTAWindowContainer">
            <div class="albumMenu">
                <ul class="nav nav-pills" >
                    <li class="albumLeft">
                        <h4><a href="#">Album Name</a></h4>
                        <input type="text" value="Album Name" style="display:none;" />
                        <a class="muted" href="#">Enter an album description.</a>
                        <input type="text" value="Enter an album description." style="display:none;" />
                    </li>
                    <li class="btn-toolbar albumRight">
                        <div class="btn-group">
                            <a href="#" class="btn btn-info btn-small" data-bind="click:showUpload">Add More Photos</a>
                        </div>
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
            <ul class="nav nav-pills nav-small">
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
            <section class="RTAWindowContent">
                <ul class="thumbnails" data-bind="foreach : files">
                    <!--ko ifnot : $data.uploading-->
                    <li style="position:relative;" class="imageItem">
                        <ul class="imageBar" data-bind="visible : isHovering">
                            <li>
                                <a href="#" style="" class="deleteFile">
                                    <i class="action16 xCircleBG"></i>
                                </a>
                            </li>
                            <li>
                                <a href="#" style="" data-bind="click: edit" class="editFile">
                                    <i class="action16 edit"></i>
                                </a>
                            </li>
                            <li>
                                <a href="#" style="margin-right: 1px;" class="hovering">
                                    <i class="action16 likeSmall"></i>
                                </a>
                            </li>
                            <li>
                                <a href="#" style="margin-right: 1px;" class="active">
                                    <i class="action16 subscribe"></i>
                                </a>
                            </li>
                        </ul>

                        <a class="thumbnail" href="#" data-bind="flash : src">
                            <img onLoad="$(this).fadeTo('slow', 1)" class="imgPreview" data-bind="attr : {src : src}" />
                        </a>
                        <p class="thumbCaption muted" data-bind="click: edit, text: name, visible : !editing()"></p>
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
    </section> <!--  theLoop -->
</div>
<div class="modal hide fade" id="uploadPopUp" style="display: none">
    <section class="RTAWindow">
        <header class="titleBar modal-header">
            <div class="contentArea titleBarBG">
                <h6>Upload Menu <small><span class="number"></span></small>
                    <button class="close" data-bind="click:hideUpload" type="button">×</button>
                </h6>
                <!--ko if : uploading-->
                <div class="RTprogressbar">
                    <div data-bind="style : { width : progress() + '%'}"></div>
                </div>
                <!--/ko-->
            </div>
        </header>
        <div class="albumMenu">
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
        <section class="RTAWindowContent">
            <section id="dropzone">
                <div class="centralise">
                    <!--ko if : droppable-->
                    <div id="droppable">
                        <h3>You can drag Pictures or PDFs here directly</h3>
                        <p class="muted">Or, you can</p>
                    </div>
                    <!--/ko-->
                    <a class="btn btn-inverse btn-small" id="pickfiles">Click here to add Files</a>
                </div>
            </section>
            <ul class="thumbnails uploadThumbs" data-bind="foreach : files">
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
                        <!--ko if : $data.uploading-->
                        <div class="progress progress-striped active">
                            <div class="bar" data-bind="style : { width : progress() + '%'}"></div>
                        </div>
                        <p class="thumbCaption" data-bind="text:'Uploading'"></p>
                        <!--/ko -->
                    </a>
                    <!--ko ifnot : $data.uploading-->
                    <p class="thumbCaption" data-bind="click: edit, text: name, visible : !editing()"></p>
                    <input class="edit" data-bind="value: name, visible : editing,
                           enterKey: stopEditing,
                           selectAndFocus: editing,
                           event: { blur: stopEditing }" />
                    <!--/ko -->
                </li>
            </ul>
            <div style="clear:both;"></div>
        </section>
        <footer class="footerBar">

        </footer>
    </section>
</div>