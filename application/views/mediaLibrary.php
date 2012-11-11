<div style="margin:0 auto 10px auto;text-align: center">
    <textarea id="log" style="width:60%;height:100px;border:1px solid #999;text-align: left"></textarea>
</div>
<section class="RTAWindow fatWidth" id="mediaLibrary">
    <header class="titleBar">
        <div class="contentArea titleBarBG">
            <h6>Media Library <small><span class="number"></span></small></h6>
            <div class="RTprogressbar">
                <div></div>
            </div>
        </div>
    </header>
    <!--</i>-->
    <nav class="windowMenuBar">
        <ul class="windowMenu">
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
                <a class="windowMenuItem" href="#" id="pickfiles"><i class="action20 upload"></i>Upload</a>
            </li>
        </ul>
    </nav>
    <section class="RTAWindowContainer">
        <section class="folderView">
            <nav class="tabbable tabs-left">
                <ul class="nav nav-list">
                    <li class="nav-header"><a>All Media</a></li>
                    <li><i><img src="" width="16px"></i><a href="#" class="folderNode">Pictures<span> (150)</span></a></li>
                    <li><i><img src="" width="16px"></i><a href="#" class="folderNode">Videos<span> (3)</span></a></li>
                    <li><i><img src="" width="16px"></i><a href="#" class="folderNode">Documents<span> (11)</span></a></li>
                    <li class="nav-header"><a>Albums</a></li>
                    <li><i><img src="" width="16px"></i><a href="#" class="folderNode">Profile Pics<span> (8)</span></a></li>
                    <li><i><img src="" width="16px"></i><a href="#" class="folderNode">Unsorted<span> (9)</span></a></li>
                </ul>

            </nav>
            <div style="clear:both;"></div>
        </section>
        <section class="RTAWindowContent" id="droppable">
            <!--ko if : droppable-->
                <h3 id="dropzone">You can drag files here or hit the upload button</h3>
            <!--/ko-->


            <ul class="thumbnails" id="thumbnails">
                <!--ko foreach : {data : files}-->
                <li style="position:relative;">
                    <a href="#" style="position:absolute;right:0;top:0;" data-bind="attr : {id : id}" class="deleteFile">X</a>
                    <a class="thumbnail" href="">
                        <img onLoad="$(this).fadeTo('slow', 1)" class="imgPreview" data-bind="attr : {src : src() || '/images/165x110.gif'}" />
                        <!--ko if : $data.uploading-->
                        <div class="RTprogressbar">
                            <div data-bind="style : { width : progress() + '%'}"></div>
                        </div>
                        <span class="thumbCaption" data-bind="text:'Uploading'"></span>
                        <!--/ko -->
                    </a>
                    <!--ko if : $data.uploaded-->
                        <span class="thumbCaption" data-bind="text: name"></span>
                    <!--/ko -->
                </li>
                <!--/ko-->
            </ul>
                <div style="clear:both;"></div>
        </section>
    </section>
    <footer class="footerBar"></footer>
</section>