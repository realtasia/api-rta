<? include('header.php') ?>
<div class="container-fluid">
    <div class="row-fluid" style="margin-top:20px">
        <div class="span4">
            <section class="RTAWindow">
                <section class="RTAWindowContainer">
                    <section class="RTAWindowContent noSidebar">
                        <div class="contentArea introBox">
                            <p>                                
                                Haven't received your welcome email? Interested in joining the Beta program?
                                <a href="#" onclick="javascript:jQuery('#sidebar-beta-link').click();return false;">Join the Beta Program</a>
                                and we'll invite you on the next beta cycle. <br><br> If you've already sent your interest via 'Join Beta Program', please be patient as
                                we are still testing the system with a 100 randomly selected users at a time. We extend this to another 100 each week.                                
                            </p><hr>
                            <form class="form-horizontal">
                                <div class="control-group">
                                    <label class="control-label" for="inputEmail">Beta Key</label>
                                    <div class="controls">
                                        <input type="text" id="inputEmail" placeholder="Provided in your Welcome Email">
                                    </div>
                                </div>
                                <div class="control-group">
                                    <div class="controls">
                                        <button class="btn btn btn-small">
                                            <img src="/assets/images/facebook-login.png">
                                            <span class="fb-auth-button" id="fb-auth">Submit</span>
                                        </button>
                                    </div>
                                </div>
                            </form>                                

                        </div>
                    </section>
                </section>
                <footer class="footerBar"></footer>
            </section>
        </div>    
    </div>    
    <div class="row-fluid" style="margin-top:20px">
        <div class="span4">
            <section class="RTAWindow">
                <section class="RTAWindowContainer">
                    <section class="RTAWindowContent noSidebar">
                        <div class="contentArea introBox">
                            <p>Forgot your Realtasia password? Just send us your email address and we'll tell you how to reset it. If you registered with your facebook profile, you only have to log in using Facebook! <br style="margin-bottom: 5px"/>
                            </p><hr>
                            <form class="form-horizontal">
                                <div class="control-group">
                                    <label class="control-label" for="inputEmail">Email</label>
                                    <div class="controls">
                                        <input type="text" id="inputEmail" placeholder="Realtasia Email">
                                    </div>
                                </div>
                                <div class="control-group">
                                    <div class="controls">
                                        <button class="btn btn btn-small">
                                            <img src="/assets/images/facebook-login.png">
                                            <span class="fb-auth-button" id="fb-auth">Reset</span>
                                        </button>
                                    </div>
                                </div>
                            </form>                                

                        </div>
                    </section>
                </section>
                <footer class="footerBar"></footer>
            </section>
        </div>
        <div class="span4">
            <section class="RTAWindow">
                <section class="RTAWindowContainer">
                    <section class="RTAWindowContent noSidebar">
                        <div class="contentArea introBox">
                            <p>Please log in to continue. If you haven't done so already, please
                                <a id="register-link" onclick="javascript:return false;" href="#beta-registration">register here.</a>
                                Forgotten your Realtasia password?
                                <a onclick="javascript:return false;" href="/user/reset_password">Click here</a>
                                to reset it. <br style="margin-bottom: 5px"/>
                                <button class="btn-fb btn btn-small btn-fb-login">
                                    <img src="/assets/images/facebook-login.png">
                                    <span class="fb-auth-button" id="fb-auth">login with facebook</span>
                                </button>
                                <small id="fb-auth-container">Only if you have registered with your facebook profile.</small>
                            </p>
                            <hr>
                            <p><small style="font-size:70%">Didn't register using Facebook? Please use your Realtasia credentials.</small></p>
                            <form class="form-horizontal">
                                <div class="control-group">
                                    <label class="control-label" for="inputEmail">Email</label>
                                    <div class="controls">
                                        <input type="text" id="inputEmail" placeholder="Realtasia Email">
                                    </div>
                                </div>
                                <div class="control-group">
                                    <label class="control-label" for="inputPassword">Realtasia Password</label>
                                    <div class="controls">
                                        <input type="password" id="inputPassword" placeholder="Realtasia Password">
                                    </div>
                                </div>
                                <div class="control-group">
                                    <div class="controls">
                                        <label class="checkbox">
                                            <input type="checkbox"> Remember me
                                        </label>
                                    </div>
                                </div>
                                <div class="control-group">
                                    <div class="controls">
                                        <button class="btn-rta btn btn-small btn-rta-login">
                                            <img src="/assets/images/facebook-login.png">
                                            <span class="fb-auth-button" id="fb-auth">login with realtasia</span>
                                        </button>
                                    </div>
                                </div>                                
                            </form>                                

                        </div>
                    </section>
                </section>
                <footer class="footerBar"></footer>
            </section>
        </div>
        <div class="span4">
            <section class="RTAWindow">
                <section class="RTAWindowContainer">
                    <section class="RTAWindowContent noSidebar">
                        <div class="contentArea introBox">
                            <p>Please fill up the following form if you are interested in the Beta Program. We will send you an invite on our next beta cycle.
                                If you have already received your welcome email, please
                                <a onclick="javascript:jQuery('#sidebar-reg-link').click();return false;" href="#">register here</a>.
                            </p><hr>
                            <form class="form-horizontal">
                                <div class="control-group">
                                    <label class="control-label" for="inputEmail">Name</label>
                                    <div class="controls">
                                        <input type="text" id="inputEmail" placeholder="Your full name">
                                    </div>
                                </div>
                                <div class="control-group">
                                    <label class="control-label" for="inputPassword">Email</label>
                                    <div class="controls">
                                        <input type="password" id="inputPassword" placeholder="Email">
                                    </div>
                                </div>
                                <div class="control-group">
                                    <label class="control-label" for="inputPassword">Message</label>
                                    <div class="controls">
                                        <textarea placeholder="Message"></textarea>
                                    </div>
                                </div>                                
                                <div class="control-group">
                                    <div class="controls">
                                        <label class="checkbox">
                                            <input type="checkbox"> Are you an Agent?
                                        </label>
                                    </div>
                                </div>
                                <div class="control-group">
                                    <div class="controls">
                                        <button class="btn btn-small">
                                            <span class="fb-auth-button" id="fb-auth">Submit</span>
                                        </button> 
                                    </div>
                                </div>
                            </form>                                

                        </div>
                    </section>
                </section>
                <footer class="footerBar"></footer>
            </section>
        </div>          
    </div>
    <hr class="bolderLine"/>    
    <div class="row-fluid" style="margin-top:20px">
        <div class="span12">
            <section class="RTAWindow slimWidth" id="listingCreationWizard">
                <header class="titleBar">
                    <div class="contentArea titleBarBG">
                        <h6>Listing Wizard <small>- Completed: <span class="number" data-bind="text: currentStep() / totalSteps * 100 + '%'"></span></small></h6>
                        <div class="RTprogressbar">
                            <div data-bind="style:{width: currentStep() / totalSteps * 100 + '%'}"></div>
                        </div>
                    </div>
                </header>
                <nav class="windowMenuBar">
                    <ul class="windowMenu">
                        <li class="hasHoverState">
                            <a class="windowMenuItem" href="#" data-bind="css:{disabled: !currentStep() > 0}, click:goBack"><i class="Round26 back"></i>Back</a>
                        </li>
                        <li class="hasHoverState">
                            <a class="windowMenuItem" href="#" data-bind="css:{disabled: enableNext()}, click:progressStep"><i class="Round26 next"></i>Next</a>
                        </li>
                        <li class="hasHoverState">
                            <a class="windowMenuItem" href="#" data-bind="css:{disabled: currentStep() <= 0}, click:resetSteps"><i class="Round26 reset"></i>Reset</a>
                        </li>                        
                        <li class="hasHoverState">
                            <a class="windowMenuItem" href="#" data-bind="css:{disabled: !currentStep() > 0}, click:save"><i class="Round26 draft"></i>Save</a>
                        </li>
                        <li class="hasHoverState">
                            <a class="windowMenuItem" href="#" data-bind="css:{disabled: !isStepComplete(5)}, click:publish"><i class="Round26 publish"></i>Publish</a>
                        </li> 
                        <li class="hasHoverState">
                            <a class="windowMenuItem" href="#" data-bind="click:cancel"><i class="Round26 cancel"></i>Cancel</a>
                        </li>                           
                    </ul>
                </nav>
                <section class="RTAWindowContainer">
                    <section class="RTAWindowContent noSidebar">
                        <div class="contentArea">
                            <form>
                                <fieldset class="accordion-inner form-inline" data-bind="visible: currentStep() >= 0 && !isListingStep() || isStepComplete(1)">
                                    <legend class="legend"><span class="badge">1</span>Listing Type</legend>
                                    <button class="btn">Sale</button>
                                    <button class="btn">Rent</button>
                                </fieldset> <!-- step 1-->
                                <fieldset class="accordion-inner" data-bind="visible: currentStep() >= 1 && !isListingStep() || isStepComplete(2)">
                                    <legend class="legend"><span class="badge">2</span>Location Details</legend>
                                    <div class="control-group warning">
                                        <div class="inlineMargin">
                                            <input class="inline" type="radio" name="locationSearchType" value="option1" checked> Name
                                            <input class="inline" type="radio" name="locationSearchType" value="option2"> Postal Code
                                            <input class="inline" type="radio" name="locationSearchType" value="option3"> Address <br style="display:none" />
                                        </div>
                                        <div class="controls">
                                            <input class="span3 " type="text" name="locationSearch" placeholder="Enter a location name"><br style="display:none" />
                                            <input class="span3 " type="text" name="locationSearch" placeholder="Enter a 6-digit postal code" style="display:none"><br style="display:none" />
                                            <input class="textNo " type="text" name="locationSearch" placeholder="No." style="display:none">
                                            <input class="textStreet " type="text" name="locationSearch" placeholder="Street Name" style="display:none">
                                            <span class="help-inline">This is either a tip or an error.</span>
                                        </div>
                                    </div>
                                </fieldset> <!-- step 2-->
                                <fieldset class="accordion-inner" data-bind="visible: currentStep() >= 2 && !isListingStep() || isStepComplete(3)">
                                    <legend class="legend"><span class="badge">3</span>Property Details</legend>
                                    <div class="btn-group">
                                        <button class="btn normalSelect">Property Type</button>
                                        <button data-toggle="dropdown" class="btn dropdown-toggle">
                                            <span class="caret"></span>
                                        </button>
                                        <ul class="dropdown-menu">
                                            <li><a>Private Apartment</a></li>
                                            <li><a>Serviced Apartment</a></li>
                                        </ul>
                                    </div><br/>
                                    <div class="btn-group">
                                        <button class="btn normalSelect">Tenure</button>
                                        <button data-toggle="dropdown" class="btn dropdown-toggle">
                                            <span class="caret"></span>
                                        </button>
                                        <ul class="dropdown-menu">
                                            <li><a>Freehold</a></li>
                                            <li><a>999 years</a></li>
                                            <li><a>99 years</a></li>
                                        </ul>
                                    </div>
                                </fieldset> <!-- step 3-->
                                <fieldset class="accordion-inner form-inline horizontal" data-bind="visible: isListingStep() || isStepComplete(4)">
                                    <legend class="legend"><span class="badge">4</span>Listing Details</legend>
                                    <div class="control-group warning">
                                        <label class="control-label" for="listingFloor">Unit No.</label>
                                        <div class="controls">
                                            <input class="textUnit " type="text" name="listingFloor" placeholder="Floor"> -
                                            <input class="textUnit " type="text" name="listingStack" placeholder="Stack">
                                            <span class="help-inline">This is either a tip or an error.</span>
                                        </div>
                                    </div>
                                    <div class="control-group error">
                                        <label class="control-label" for="listingBuilt">Built</label>
                                        <div class="controls">
                                            <input class="span1 " type="text" name="listingBuilt" placeholder="Built-Up Size (sqft)">
                                            <span class="label_checkbox_pair">
                                                <input type="checkbox" name="chkIsLanded" />
                                                <label for="chkIsLanded">Land Size?</label>
                                            </span>
                                            <span class="help-inline">This is either a tip or an error.</span>
                                        </div>
                                        <br>
                                        <label class="control-label" for="listingLand">Land</label>
                                        <input class="span1 " type="text" name="listingLand" placeholder="Land Size (sqft)">
                                    </div>
                                    <div class="control-group success">
                                        <label class="control-label" for="listingRooms">Rooms</label>
                                        <div class="controls">
                                            <input class="span1 " type="text" name="listingRooms" placeholder="No. of rooms">
                                            <span class="help-inline">This is either a tip or an error.</span>
                                        </div>
                                    </div>
                                    <div class="control-group">
                                        <label class="control-label" for="listingAsking">Price</label>
                                        <div class="controls">
                                            <input class="span1 " type="text" name="listingAsking" placeholder="Price">
                                            <span class="label_checkbox_pair">
                                                <input type="checkbox"  name="chkIsValued" />
                                                <label for="chkIsValued">Is valued?</label>
                                            </span>
                                            <span class="help-inline">This is either a tip or an error.</span>
                                        </div><br>
                                        <label class="control-label" for="listingValue">Valuation</label>
                                        <input class="span1 " type="text" name="listingValue" placeholder="Valuation Price"><br>
                                        <label class="control-label" for="listingCOV">COV</label>
                                        <input class="span1 " type="text" name="listingCOV" placeholder="Cash Over Valuation">
                                    </div>
                                    <div class="control-group">
                                        <label class="control-label" for="listingDescription">Description</label>
                                        <div class="controls">
                                            <textarea class="span3" name="listingDescription" placeholder="Description is seen by everyone"></textarea>
                                            <span class="help-inline">This is either a tip or an error.</span>
                                        </div>
                                    </div>
                                    <div class="control-group">
                                        <label class="control-label" for="listingCoBrokeNotes">Co-Broke Notes</label>
                                        <div class="controls">
                                            <textarea class="span3" name="listingCoBrokeNotes" placeholder="Notes are seen by Realt Agents only"></textarea>
                                            <span class="help-inline">This is either a tip or an error.</span>
                                        </div>
                                    </div>
                                </fieldset> <!-- step 4-->
                            </form>
                        </div>
                    </section>
                </section>
                <footer class="footerBar"></footer>
            </section>
        </div>
    </div>
    <hr class="bolderLine"/>

    <div class="row-fluid" style="margin-top:20px">
        <div class="span12">
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
                            <a class="windowMenuItem" href="#" data-bind="click:function(){return true}"><i class="social26 rta"></i>Realtasia</a>
                        </li>
                        <li class="hasHoverState hasActiveState isNavigation">
                            <a class="windowMenuItem" href="#" data-bind="click:function(){return true}"><i class="social26 facebook"></i>Facebook</a>
                        </li>
                        <li class="hasHoverState hasActiveState isNavigation">
                            <a class="windowMenuItem" href="#" data-bind="click:function(){return true}"><i class="social26 youtube"></i>YouTube</a>
                        </li>
                        <li class="hasHoverState hasActiveState isNavigation">
                            <a class="windowMenuItem" href="#" data-bind="click:function(){return true}"><i class="social26 flickr"></i>Flickr</a>
                        </li>
                        <li class="hasHoverState hasActiveState isNavigation">
                            <a class="windowMenuItem" href="#" data-bind="click:function(){return true}"><i class="social26 picasa"></i>Picasa</a>
                        </li>
                        <li class="hasHoverState hasActiveState rightMenuItem isNavigation">
                            <a class="windowMenuItem" href="#" data-bind="click:function(){return true}"><i class="action20 upload"></i>Upload</a>
                        </li>                        
                    </ul>
                </nav>
                <section class="RTAWindowContainer">
                    <section class="folderView">
                        <nav class="tabbable tabs-left">
                            <ul class="nav nav-list">
                                <li class="nav-header"><a>All Media</a></li>
                                <li><i><img src="/images/26X26/folderPic.png" width="16px"></i><a href="#" class="folderNode">Pictures<span> (150)</span></a></li>
                                <li><i><img src="/images/26X26/folderVid.png" width="16px"></i><a href="#" class="folderNode">Videos<span> (3)</span></a></li>
                                <li><i><img src="/images/26X26/folderDoc.png" width="16px"></i><a href="#" class="folderNode">Documents<span> (11)</span></a></li>
                                <li class="nav-header"><a>Albums</a></li>
                                <li><i><img src="/images/26X26/folderPic.png" width="16px"></i><a href="#" class="folderNode">Profile Pics<span> (8)</span></a></li>
                                <li><i><img src="/images/26X26/folderPic.png" width="16px"></i><a href="#" class="folderNode">Unsorted<span> (9)</span></a></li>
                            </ul>
                        </nav>
                    </section>
                    <section class="RTAWindowContent">
                        <ul class="thumbnails">
                            <li><a class="thumbnail" href=""><img src="/images/165x110.gif" alt="" /><div class="RTprogressbar"><div style="width: 35%"></div></div><span class="thumbCaption">Uploading</span></a></li>                         
                            <li><a class="thumbnail" href=""><img src="/images/165x110.gif" alt="" /><div class="RTprogressbar"><div style="width: 45%"></div></div><span class="thumbCaption">Uploading</span></a></li>                         
                            <li><a class="thumbnail" href=""><img src="/images/165x110.gif" alt="" /><div class="RTprogressbar"><div style="width: 25%"></div></div><span class="thumbCaption">Uploading</span></a></li>                         
                            <li><a class="thumbnail" href=""><img src="/images/165x110.gif" alt="" /><div class="RTprogressbar"><div style="width: 65%"></div></div><span class="thumbCaption">Uploading</span></a></li>                         
                            <li><a class="thumbnail" href=""><img src="/images/165x110.gif" alt="" /><div class="RTprogressbar"><div style="width: 85%"></div></div><span class="thumbCaption">Uploading</span></a></li>                         
                            <li><a class="thumbnail" href=""><img src="/images/165x110.gif" alt="" /><div class="RTprogressbar"><div style="width: 100%"></div></div><span class="thumbCaption">Upload Complete</span></a></li>                         
                            <li><a class="thumbnail" href=""><img src="/images/165x110.gif" alt="" /><div class="RTprogressbar"><div style="width: 100%"></div></div><span class="thumbCaption">Upload Complete</span></a></li>                         
                            <li><a class="thumbnail" href=""><img src="/images/165x110.gif" alt="" /><div class="RTprogressbar"><div style="width: 100%"></div></div><span class="thumbCaption">Upload Complete</span></a></li>                         
                            <li><a class="thumbnail" href=""><img src="/images/165x110.gif" alt=""></a><span class="thumbCaption">Thumbnail caption...</span></li>
                            <li><a class="thumbnail" href=""><img src="/images/165x110.gif" alt=""></a><span class="thumbCaption">Thumbnail caption...</span></li>
                            <li><a class="thumbnail" href=""><img src="/images/165x110.gif" alt=""></a><span class="thumbCaption">Thumbnail caption...</span></li>
                            <li><a class="thumbnail" href=""><img src="/images/165x110.gif" alt=""></a><span class="thumbCaption">Thumbnail caption...</span></li>
                            <li><a class="thumbnail" href=""><img src="/images/165x110.gif" alt=""></a><span class="thumbCaption">Thumbnail caption...</span></li>
                            <li><a class="thumbnail" href=""><img src="/images/165x110.gif" alt=""></a><span class="thumbCaption">Thumbnail caption...</span></li>
                            <li><a class="thumbnail" href=""><img src="/images/165x110.gif" alt=""></a><span class="thumbCaption">Thumbnail caption...</span></li>
                            <li><a class="thumbnail" href=""><img src="/images/165x110.gif" alt=""></a><span class="thumbCaption">Thumbnail caption...</span></li>
                            <li><a class="thumbnail" href=""><img src="/images/165x110.gif" alt=""></a><span class="thumbCaption">Thumbnail caption...</span></li>
                            <li><a class="thumbnail" href=""><img src="/images/165x110.gif" alt=""></a><span class="thumbCaption">Thumbnail caption...</span></li>
                            <li><a class="thumbnail" href=""><img src="/images/165x110.gif" alt=""></a><span class="thumbCaption">Thumbnail caption...</span></li>
                        </ul>
                    </section>
                </section>
                <footer class="footerBar"></footer>
            </section>
        </div>
    </div>
    <hr class="bolderLine"/>
    <div class="row-fluid" style="margin-top:200px">
        <div class="span4">
            <div class="tipLinkContainer"><a href="#" class="tipsTest">Company Hover</a></div>
            <section class="hoverCard regular" style="display:none">
                <section class="hoverCard contentArea">
                    <div style="clear: both;">
                        <div class="hoverCard contentLeft"><a href=""><img src="http://images.sg.ippstatic.com/agencies/806.jpg" /></div>
                        <div class="hoverCard contentRight">
                            <h5><a href="#" class="hoverCard header">ERA Real Estate</a></h5>
                            <span class="hoverCard subHeader">Current Members in RealtAsia <a>87</a></span>
                            <a href="#"class="hoverCard detail"><i class="postMini i-images"></i>5852 523 651D</a>
                            <a href="#"class="hoverCard detail"><i class="postMini i-images"></i>+65 [REDACTED]</a>
                            <a href="#"class="hoverCard detail"><i class="postMini i-images"></i>tan.hwee.peng@yahoo.com.sg</a>
                        </div>
                    </div>
                    <section class="postActions">
                        <ul class="postActionButtons">
                            <li class="hasHoverState hasActiveState"><a href="#"><i class="social26 subscribe"></i></a></li>
                            <li class="hasHoverState hasActiveState"><a href="#"><i class="social26 like"></i></a></li>
                        </ul>
                        <ul class="postActionInfo">
                            <li class="hasHoverState"><a href="#"><i class="Action16 people"></i>215</a></li>
                            <li class="hasHoverState"><a href="#" class="tipsLike"><i class="postMini i-likes"></i>167</a></li>
                        </ul>
                    </section> <!-- post action links -->

                </section>
            </section>
        </div>
        <div class="span4">
            <div class="tipLinkContainer"><a href="#" class="tipsTest">Profile Hover</a></div>
            <section class="hoverCard regular" style="display:none">
                <section class="hoverCard contentArea">
                    <div class="hoverCard contentLeft"><a href=""><img src="http://images.sg.ippstatic.com/agents/7175_19031224.jpg" /></div>
                    <div class="hoverCard contentRight">
                        <h5><a href="#" class="hoverCard header">Tan Hwee Pheng</a></h5>
                        <span class="hoverCard subHeader">Associate Director at <a>Tan Hwee Pheng & Associates</a></span>
                        <a href="#"class="hoverCard detail"><i class="postMini i-images"></i>+65 [REDACTED]</a>
                        <a href="mailto:tan.hwee.peng@yahoo.com.sg"class="hoverCard detail"><i class="postMini i-images"></i>tan.hwee.peng@yahoo.com.sg</a>
                    </div>
                    <section class="postActions">
                        <ul class="postActionButtons">
                            <li class="hasHoverState hasActiveState"><a href="#"><i class="social26 subscribe"></i></a></li>
                            <li class="hasHoverState hasActiveState"><a href="#"><i class="social26 like"></i></a></li>
                        </ul>
                        <ul class="postActionInfo">
                            <li class="hasHoverState"><a href="#"><i class="Action16 people"></i>215</a></li>
                            <li id="tanLikes" class="hasHoverState"><a href="#" class="tipsLike"><i class="postMini i-likes"></i>167</a></li>
                        </ul>
                    </section> <!-- post action links -->
                </section>
            </section>
        </div>
        <div class="span4">
            <div class="tipLinkContainer"><a href="#" class="tipsTest">Location Hover</a> </div>
            <section class="hoverCard regular" style="display:none">
                <section class="hoverCard contentArea">
                    <div class="hoverCard contentLeft"><i class="largeIcons i-hdb"></i></div>
                    <div class="hoverCard contentRight">
                        <h5><a href="#" class="hoverCard header">Jurong West HDB</a></h5>
                        <span class="hoverCard subHeader">Part of the <a>Jurong Estate</a></span>
                        <h6>Latest Pictures</h6>
                        <ul class="thumbnails">
                            <li class="span1"><a href="#" class="thumbnail"><img src="/images/35x35-black.gif" alt=""></a></li>
                            <li class="span1"><a href="#" class="thumbnail"><img src="/images/35x35-pink.gif" alt=""></a></li>
                            <li class="span1"><a href="#" class="thumbnail"><img src="/images/35x35-green.gif" alt=""></a></li>
                            <li class="span1"><a href="#" class="thumbnail"><img src="/images/35x35-blue.gif" alt=""></a></li>

                        </ul>
                    </div>
                    <section class="postActions">
                        <ul class="postActionButtons">
                            <li class="hasHoverState hasActiveState"><a href="#"><i class="social26 subscribe"></i></a></li>
                            <li class="hasHoverState hasActiveState"><a href="#"><i class="social26 like"></i></a></li>
                        </ul>
                        <ul class="postActionInfo">
                            <li class="hasHoverState"><a href="#"><i class="Action16 people"></i>215</a></li>
                            <li class="hasHoverState"><a href="#"><i class="postMini i-likes"></i>167</a></li>
                        </ul>
                    </section> <!-- post action links -->
                </section>
            </section>
        </div>
    </div>
    <hr class="bolderLine"/><div class="row-fluid" style="margin:150px 0">
        <div class="span3">
            <div class="tipLinkContainer"><a href="#" class="tipsTest tipsRight">Event Hover</a> </div>
            <section class="hoverCard large" style="display:none">
                <section class="hoverCard contentArea">
                    <div class="hoverCard contentLeft">
                        <h5><a href="#" class="hoverCard header"><i class="sidebar i-event Active"></i>15 Cuscaden Ave Open House</a></h5>
                        <div style="margin-top:10px">
                            <div class="withLabel"><label class="label">Type</label><span>Open House (Sale)</span></div>
                            <div class="withLabel"><label class="label">Date</label><span>December 28th, 2012</span></div>
                            <div class="withLabel"><label class="label">Time</label><span>All day from 8am</span></div>
                            <div class="withLabel"><label class="label">Cost</label><span>NA</span></div>
                            <div class="withLabel"><label class="label">Location</label><span>15 Cuscaden Avenue (Courtyard)</span></div>
                            <div class="withLabel"><label class="label">Description</label><span>Come by and see what this property is all about. Finger food, snacks & drinks provided</span></div>
                        </div>
                    </div>
                    <div class="hoverCard contentRight"><a href=""><img src="/images/90x90blue.gif" alt=""></div>
                            <section class="postActions">
                                <ul class="postActionButtons">
                                    <li class="hasHoverState hasActiveState"><a href="#"><i class="social26 subscribe"></i></a></li>
                                    <li class="hasHoverState hasActiveState"><a href="#"><i class="social26 like"></i></a></li>
                                </ul>
                                <ul class="postActionInfo">
                                    <li class="hasHoverState"><a href="#"><i class="Action16 people"></i>215</a></li>
                                    <li class="hasHoverState"><a href="#"><i class="postMini i-likes"></i>167</a></li>
                                </ul>
                            </section> <!-- post action links -->
                            </section>
                            </section>
                    </div>
                    <div class="span9">
                        <div class="tipLinkContainer"><a href="#" class="tipsLikeTest tipsTest tipsRight">Likes Hover</a> </div>
                        <section class="hoverCard regular tipsLikeContent" style="display:none">
                            <section class="hoverCard contentArea">
                                <h4 style="margin-bottom:10px;" class="hasHoverState"><i class="postMini i-likes"></i>People who like <a href="#" class="hoverCard header">Tan Hwee Pheng</a></h4>
                                <ul class="membersList contentArea">
                                    <li class="contentArea">
                                        <ul class="membersInline"><li class="commentThumb"><a href=""><img src="/images/40x40.gif" /></li><li class="commentBody"><h5><a class="commentAuthor" href="#">Susan Lim</a></h5></li></ul> <!-- comment inline -->
                                    </li> <!-- comment -->
                                    <li class="contentArea">
                                        <ul class="membersInline"><li class="commentThumb"><a href=""><img src="/images/40x40.gif" /></li><li class="commentBody"><h5><a class="commentAuthor" href="#">Jerry Lee Sim Kok</a></h5></li></ul> <!-- comment inline -->
                                    </li> <!-- comment -->
                                    <li class="contentArea">
                                        <ul class="membersInline"><li class="commentThumb"><a href=""><img src="/images/40x40.gif" /></li><li class="commentBody"><h5><a class="commentAuthor" href="#">Jerry Lee Sim Kok</a></h5></li></ul> <!-- comment inline -->
                                    </li> <!-- comment -->
                                    <li class="contentArea">
                                        <ul class="membersInline"><li class="commentThumb"><a href=""><img src="/images/40x40.gif" /></li><li class="commentBody"><h5><a class="commentAuthor" href="#">Jerry Lee Sim Kok</a></h5></li></ul> <!-- comment inline -->
                                    </li> <!-- comment -->
                                    <li class="contentArea">
                                        <ul class="membersInline"><li class="commentThumb"><a href=""><img src="/images/40x40.gif" /></li><li class="commentBody"><h5><a class="commentAuthor" href="#">Jerry Lee Sim Kok</a></h5></li></ul> <!-- comment inline -->
                                    </li> <!-- comment -->
                                    <li class="contentArea">
                                        <ul class="membersInline"><li class="commentThumb"><a href=""><img src="/images/40x40.gif" /></li><li class="commentBody"><h5><a class="commentAuthor" href="#">Jerry Lee Sim Kok Smith & Wesson</a></h5></li></ul> <!-- comment inline -->
                                    </li> <!-- comment -->
                                    <li class="contentArea">
                                        <ul class="membersInline"><li class="commentThumb"><a href=""><img src="/images/40x40.gif" /></li><li class="commentBody"><h5><a class="commentAuthor" href="#">Jerry Lee Sim Kok</a></h5></li></ul> <!-- comment inline -->
                                    </li> <!-- comment -->
                                </ul> <!-- comments -->
                            </section>
                        </section>
                    </div>
                    </div>
                    <hr class="bolderLine"/><div class="row-fluid" style="margin:150px 0">
                        <div class="span12">
                            <div class="tipLinkContainer"><a href="#" class="tipsTest tipsLeft">Subscribers Hover</a></div>
                            <section class="hoverCard regular" style="display:none">
                                <section class="hoverCard contentArea">
                                    <h4 style="margin-bottom:10px;" class="hasHoverState"><i class="Action16 people"></i>People subscribed to <a href="#" class="hoverCard header">Tan Hwee Pheng</a></h4>
                                    <ul class="membersList contentArea">
                                        <li class="contentArea">
                                            <ul class="membersInline"><li class="commentThumb"><a href=""><img src="/images/40x40.gif" /></li><li class="commentBody"><h5><a class="commentAuthor" href="#">Susan Lim</a></h5></li></ul> <!-- comment inline -->
                                        </li> <!-- comment -->
                                        <li class="contentArea">
                                            <ul class="membersInline"><li class="commentThumb"><a href=""><img src="/images/40x40.gif" /></li><li class="commentBody"><h5><a class="commentAuthor" href="#">Jerry Lee Sim Kok</a></h5></li></ul> <!-- comment inline -->
                                        </li> <!-- comment -->
                                        <li class="contentArea">
                                            <ul class="membersInline"><li class="commentThumb"><a href=""><img src="/images/40x40.gif" /></li><li class="commentBody"><h5><a class="commentAuthor" href="#">Jerry Lee Sim Kok</a></h5></li></ul> <!-- comment inline -->
                                        </li> <!-- comment -->
                                        <li class="contentArea">
                                            <ul class="membersInline"><li class="commentThumb"><a href=""><img src="/images/40x40.gif" /></li><li class="commentBody"><h5><a class="commentAuthor" href="#">Jerry Lee Sim Kok</a></h5></li></ul> <!-- comment inline -->
                                        </li> <!-- comment -->
                                        <li class="contentArea">
                                            <ul class="membersInline"><li class="commentThumb"><a href=""><img src="/images/40x40.gif" /></li><li class="commentBody"><h5><a class="commentAuthor" href="#">Jerry Lee Sim Kok</a></h5></li></ul> <!-- comment inline -->
                                        </li> <!-- comment -->
                                        <li class="contentArea">
                                            <ul class="membersInline"><li class="commentThumb"><a href=""><img src="/images/40x40.gif" /></li><li class="commentBody"><h5><a class="commentAuthor" href="#">Jerry Lee Sim Kok Smith & Wesson</a></h5></li></ul> <!-- comment inline -->
                                        </li> <!-- comment -->
                                        <li class="contentArea">
                                            <ul class="membersInline"><li class="commentThumb"><a href=""><img src="/images/40x40.gif" /></li><li class="commentBody"><h5><a class="commentAuthor" href="#">Jerry Lee Sim Kok</a></h5></li></ul> <!-- comment inline -->
                                        </li> <!-- comment -->
                                    </ul> <!-- comments -->
                                </section>
                            </section>
                        </div>
                    </div>
                    </div>
                    <hr class="bolderLine"/>
                    <footer id="footer"></footer>
                    </body>
                    </html>