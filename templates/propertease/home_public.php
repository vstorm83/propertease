<!DOCTYPE html>  
 <html id="ls-global" dir="ltr" class="com_content view-featured itemid-101 home j34 mm-hover no-touch" lang="en-gb">  
 <head>  
  <meta http-equiv="content-type" content="text/html; charset=UTF-8">  
  <meta name="generator" content="Joomla! - Open Source Content Management">  
  <title>Home</title>  
  <meta name="viewport" content="width=device-width, initial-scale=1.0">  
  <link rel="stylesheet" href="<?php echo $this->baseurl ?>/templates/<?php echo $this->template ?>/css/bootstrap.css" type="text/css">  
  <link rel="stylesheet" href="<?php echo $this->baseurl ?>/templates/<?php echo $this->template ?>/css/main.minified.css" type="text/css">  
  <link rel="stylesheet" href="<?php echo $this->baseurl ?>/templates/<?php echo $this->template ?>/css/font-awesome.css" type="text/css">  
  <link rel="stylesheet" href="<?php echo $this->baseurl ?>/templates/<?php echo $this->template ?>/css/animate.css" type="text/css">  
  <link rel="stylesheet" href="<?php echo $this->baseurl ?>/templates/<?php echo $this->template ?>/css/djimageslider.css" type="text/css">  
  <link href="<?php echo $this->baseurl ?>/templates/<?php echo $this->template ?>/css/bootstrap-gbs3.css" media="screen" rel="stylesheet" type="text/css">  
  <link href="<?php echo $this->baseurl ?>/templates/<?php echo $this->template ?>/css/bootstrap-theme-gbs3.css" media="screen" rel="stylesheet" type="text/css">  
  <link href="<?php echo $this->baseurl ?>/templates/<?php echo $this->template ?>/css/bootstrap-gcore-gbs3.css" media="screen" rel="stylesheet" type="text/css">  
  <script src="<?php echo $this->baseurl ?>/templates/<?php echo $this->template ?>/js/jquery.js" type="text/javascript"></script>  
  <script src="<?php echo $this->baseurl ?>/templates/<?php echo $this->template ?>/js/plugins.js" type="text/javascript"></script>  
  <script src="<?php echo $this->baseurl ?>/templates/<?php echo $this->template ?>/js/bootstrap.js" type="text/javascript"></script>  
  <script src="<?php echo $this->baseurl ?>/templates/<?php echo $this->template ?>/js/layerslider.js" type="text/javascript"></script>  
  <script src="<?php echo $this->baseurl ?>/templates/<?php echo $this->template ?>/js/gtooltip.js" type="text/javascript"></script>  
  <script src="<?php echo $this->baseurl ?>/templates/<?php echo $this->template ?>/js/gvalidation.js" type="text/javascript"></script>  
  <script type="text/javascript">  
 jQuery(window).on('load', function() {  
                     new JCaption('img.caption');  
                }); 
				

jQuery(function() {
  jQuery('a#take-the-tour').click(function() {
	 
	if (location.pathname.replace(/^\//,'') == this.pathname.replace(/^\//,'') && location.hostname == this.hostname) {
	  var target = jQuery(this.hash);
	  target = target.length ? target : jQuery('[name=' + this.hash.slice(1) +']');
	  if (target.length) {
		jQuery('html,body').animate({
		  scrollTop: target.offset().top
		}, 1000);
		return false;
	  }
	}
  });
});
	
	
  </script>  
  <script type="text/javascript">  
   (function() {  
    Joomla.JText.load({"REQUIRED_FILL_ALL":"Please enter data in all fields.","E_LOGIN_AUTHENTICATE":"Username and password do not match or you do not have an account yet.","REQUIRED_NAME":"Please enter your name!","REQUIRED_USERNAME":"Please enter your username!","REQUIRED_PASSWORD":"Please enter your password!","REQUIRED_VERIFY_PASSWORD":"Please re-enter your password!","PASSWORD_NOT_MATCH":"Password does not match the verify password!","REQUIRED_EMAIL":"Please enter your email!","EMAIL_INVALID":"Please enter a valid email!","REQUIRED_VERIFY_EMAIL":"Please re-enter your email!","EMAIL_NOT_MATCH":"Email does not match the verify email!","CAPTCHA_REQUIRED":"Please enter captcha key"});  
   })();  
  </script>  
 <!-- META FOR IOS & HANDHELD -->  
      <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">  
      <style type="text/stylesheet">  
           @-webkit-viewport  { width: device-width; }  
           @-moz-viewport   { width: device-width; }  
           @-ms-viewport    { width: device-width; }  
           @-o-viewport    { width: device-width; }  
           @viewport      { width: device-width; }  
      </style>  
      <script type="text/javascript">  
           //<![CDATA[  
           if (navigator.userAgent.match(/IEMobile\/10\.0/)) {  
                var msViewportStyle = document.createElement("style");  
                msViewportStyle.appendChild(  
                     document.createTextNode("@-ms-viewport{width:auto!important}")  
                );  
                document.getElementsByTagName("head")[0].appendChild(msViewportStyle);  
           }  
           //]]>  
      </script>  
 <meta name="HandheldFriendly" content="true">  
 <meta name="apple-mobile-web-app-capable" content="YES">  
 <!-- //META FOR IOS & HANDHELD -->  
 <!-- Le HTML5 shim and media query for IE8 support -->  
 <!--[if lt IE 9]>  
 <script src="//html5shim.googlecode.com/svn/trunk/html5.js"></script>  
 <script type="text/javascript" src="/properteasenew/plugins/system/t3/base-bs3/js/respond.min.js"></script>  
 <![endif]-->  
 <!-- You can add Google Analytics here or use T3 Injection feature -->  
 <!--   
  <jdoc:include type="head" /> -->  
 <body>  
 <div class="t3-wrapper"> <!-- Need this wrapper for off-canvas menu. Remove if you don't use of-canvas -->  
  <div class="head-top">  
   <div class="container">  
 <!-- HEADER -->  
 <div id="t3-header" class=" t3-header">  
      <div class="row ">  
      <div class="top-left col-md-6 ">  
           <!-- LOGO -->  
           <div class="pull-left logo">  
                <div class="logo-image">  
                     <a href="<?php echo $mainURL; ?>" title="Propertease">  
                                                        <img class="logo-img" src="<?php echo $mainURL; ?>images/logo.png" alt="Propertease">  
                                                                            <span>Propertease</span>  
                     </a>  
                     <small class="site-slogan"></small>  
                </div>  
           </div>  
           <!-- //LOGO -->  
      </div>  
                <!-- HEAD SEARCH -->  
      <div class="top-right col-md-6 ">  
          <?php include_once("element/header_nav.php"); ?>
        <div class="clear"></div>  
      </div>  

 
             <!--<button>  
               <span class="icon-bar"></span>  
               <span class="icon-bar"></span>  
               <span class="icon-bar"></span>  
             </button>  -->
           </div>  
           <!-- //HEAD SEARCH -->  
           </div></div>  
 <!-- //HEADER -->  
    <!-- <!?php $this->loadBlock('mainnav') ?> -->  
   </div>  
  </div>  
   <!-- slideshow -->  
  <div id="slideshow" class="slideshow">  
 <div class="custom_banner_content">  
      <div class="ls-container ls-glass" id="layerslider" style="width: 100%; height: 700px; visibility: visible;"><div class="ls-webkit-hack"></div>  
 <div style="background-color: transparent; width: 1349px; height: 700px;" class="ls-inner"><div class="ls-layer ls-animating" style="width: 1349px; height: 700px; visibility: visible; display: none; left: auto; right: auto; top: auto; bottom: auto;">  
 <img style="width: 1500px; height: 768px; margin-left: -750px; margin-top: -384px;" class="ls-bg" src="<?php echo $this->baseurl ?>/templates/<?php echo $this->template ?>/images/slideshow1.png" alt=""><div class="ls-s1 heading" style="left: 230px; top: 130px; opacity: 1; transform: rotate(0deg) scale(1, 1); display: block; visibility: visible; margin-left: 0px; margin-top: 0px;"><span class="med">Before buying </span>that property,<span class="med"> find out</span></div><div class="ls-s1 heading" style="left: 230px; top: 170px; opacity: 1; transform: rotate(0deg) scale(1, 1); display: block; visibility: visible; margin-left: 0px; margin-top: 0px;">what <span class="med">you can or can`t do </span>with it</div><p class="ls-s3 text" style="left: 960px; top: 130px; opacity: 1; transform: rotate(0deg) scale(1, 1); display: block; visibility: visible; margin-left: 0px; margin-top: 0px;">A once off search is<span class="med"> just $59</span></p><p class="ls-s3 text" style="left: 838px; top: 160px; opacity: 1; transform: rotate(0deg) scale(1, 1); display: block; visibility: visible; margin-left: 0px; margin-top: 0px;"><em>(instant answers but some research involved)</em></p><p class="ls-s3 text" style="left: 800px; top: 190px; opacity: 1; transform: rotate(0deg) scale(1, 1); display: block; visibility: visible; margin-left: 0px; margin-top: 0px;">with<span class="med"> monthly or<span class="med"> yearly</span> subscriptions also offered.</span></p><img class="ls-s5" style="left: 300px; top: 253px; opacity: 1; transform: rotate(0deg) scale(1, 1); display: block; visibility: visible; margin-left: 0px; margin-top: 0px;" src="<?php echo $mainURL; ?>images/banner.png" alt=""><div class="ls-s6" style="left: 956px; top: 300px; opacity: 1; transform: rotate(0deg) scale(1, 1); display: block; visibility: visible; margin-left: 0px; margin-top: 0px;">  
 <p><a class="btn btn-default btn-warning" href="#benefits" id="take-the-tour">Take the <span class="med">Tour</span></a></p>  
 <p><a class="btn btn-default btn-primary ">Try it <span class="med">NOW</span></a></p>  
 </div>  
 </div><div class="ls-circle-timer"><div class="ls-ct-left"><div style="transform: rotate(0deg);" class="ls-ct-rotate"><div class="ls-ct-hider"><div class="ls-ct-half"></div></div></div></div><div class="ls-ct-right"><div style="transform: rotate(0deg);" class="ls-ct-rotate"><div class="ls-ct-hider"><div class="ls-ct-half"></div></div></div></div><div class="ls-ct-center"></div></div></div>  
 <div class="ls-loading-container" style="z-index: -1; display: none;"><div class="ls-loading-indicator"></div></div><div class="ls-shadow"></div></div></div>  
  </div>  
  <!-- //slideshow -->  
                     <!-- HEAD SEARCH -->  
                     <div class="custom-position ">  
                                    <div class="moduletable green-bg first-custom">  
 <div class="custom green-bg first-custom">  
      <div style="visibility: hidden; animation-duration: 1500ms; animation-name: none;" class="container wow fadeInDown" data-wow-duration="1500ms"><center>  
 <p class="heading-text">Too busy? <span class="bold">Let us do it for you.&nbsp;<img src="<?php echo $mainURL; ?>images/arrow.png" alt="">&nbsp;<a class="s-btn">HIRE A <span class="blk">CONSCIERGE</span></a></span></p>  
 <p>Currently Servicinvg Queensland Councils: Brisbane, Redland, Moreton   
 Bay, Gold Coast, Ipswich, Logan, Sunshine Coast, Toowoomba, Fraser   
 Coast...</p>  
 </center></div></div>  
           </div>  
<!--  start   of tabs --> 



                <div class="moduletablebenefits">  
<div class="custombenefits">
    <div class="container">
        <h3 id="benefits">Benefits For You</h3>
        <div class="row first-row nav nav-tabs">
       
           <div style="visibility: hidden; animation-delay: 0.1s; animation-name: none;" class="col-md-2 col-xs-12 wow slideInLeft" data-wow-delay="0.1s">
           		<a data-toggle="tab" class="benefit-item active" href="#architects"> Architects <i style="font-size: 0;">icon</i> </a>
           </div>
           <div style="visibility: hidden; animation-delay: 0.2s; animation-name: none;" class="col-md-2 col-xs-12 wow slideInLeft" data-wow-delay="0.2s">
           		<a data-toggle="tab" class="benefit-item Developers" href="#developers"> Developers <i style="font-size: 0;">icon</i></a>
           </div>
           <div style="visibility: hidden; animation-delay: 0.3s; animation-name: none;" class="col-md-2 col-xs-12 wow slideInLeft" data-wow-delay="0.3s">
           		<a data-toggle="tab" class="benefit-item TownPlanners" href="#town-planners"> Town Planners <i style="font-size: 0;">icon</i></a>
           </div>
           <div style="visibility: hidden; animation-delay: 0.1s; animation-name: none;" class="col-md-2 col-xs-12 wow slideInRight" data-wow-delay="0.1s">
           		<a data-toggle="tab" class="benefit-item RealEstate" href="#real-estate-agents"> Real Estate Agents <i style="font-size: 0;">icon</i></a>
           </div>
           <div style="visibility: hidden; animation-delay: 0.2s; animation-name: none;" class="col-md-2 col-xs-12 wow slideInRight" data-wow-delay="0.2s">
           		<a data-toggle="tab" class="benefit-item Investors" href="#investors"> Investors <i style="font-size: 0;">icon</i></a>
           </div>
           <div style="visibility: hidden; animation-delay: 0.2s; animation-name: none;" class="col-md-2 col-xs-12 wow slideInRight" data-wow-delay="0.2s">
           		<a data-toggle="tab" class="benefit-item Builders" href="#builders"> Builders <i style="font-size: 0;">icon</i> </a>
           </div>
        </div>
        
        <div style="visibility: hidden; animation-name: none;" class="wow fadeInDown tab-content">
        	<div id="architects" class="tab-pane fade in active"><!-- tab pane -->
                <h1 style="font-size: 37px;">Benefits for Architects</h1>
                <p class="med" style="font-size: 20px;">The PropertEASE online system can provide you with instant answers when looking at a property for a prospective client.</p>
                <p style="font-size: 19px;">A month to month or yearly unlimited subscription can ensure that you get the best value for money to utilize the system.
                    <br>
                    <br>
                </p>
                <h4 style="font-size: 23px;"><strong>PropertEASE can assist architects in many ways:</strong></h4>
                <div class="medias"><a class="pull-right img-c" href="#"> <img class="media-object" src="<?php echo $mainURL; ?>images/architects-ico.png" alt=""> </a>
                    <div class="media-body">
                        <ul class="green-list">
                            <li>Getting instant and accurate information making the system extremely time (and money) efficient;</li>
                            <li>Allows the user to provide a client with instant detailed advice regarding a property;</li>
                            <li>Is mobile friendly (i.e. use it on site);</li>
                            <li>Will allow any assistant or untrained employee to quickly obtain information;</li>
                            <li>Saves hours a day waiting on hold to Council or town planners to get the same answers; and</li>
                            <li>Avoid spending hours reading through planning schemes.</li>
                        </ul>
                    </div>
                    
                </div>
             </div><!-- tab pane -->
             
             <div id="developers" class="tab-pane fade in"><!-- tab pane -->
                <h1 style="font-size: 37px;">Benefits for Developers</h1>
                <p class="med" style="font-size: 20px;">he PropertEASE online system can provide you with instant answers when looking at a potential development site. </p>
                <p style="font-size: 19px;">A month to month or yearly unlimited subscription can ensure that you get the best value for money to utilize the system.
                    <br>
                    <br>
                </p>
                <h4 style="font-size: 23px;"><strong>PropertEASE can assist Developers in many ways:</strong></h4>
                <div class="medias"><a class="pull-right img-c" href="#"> <img class="media-object" src="<?php echo $mainURL; ?>images/developers-ico.png" alt=""> </a>
                    <div class="media-body">
                        <ul class="green-list">
                            <li>Easy instant answers (using our online resources);</li>
                            <li>A simple overview of a properties development potential to give you the information to make a quick 'yes' or 'no' answer when purchasing;</li>
                            <li>Saves hours a day waiting on hold to Council or town planners to get the same answers;</li>
                            <li>Gives you the upper hand when negotiating with real estate agents; and</li>
                            <li>Is mobile friendly (i.e. use it on site).</li>
                        </ul>
                    </div>
                    
                </div>
             </div><!-- tab pane -->


             <div id="town-planners" class="tab-pane fade in"><!-- tab pane -->
                <h1 style="font-size: 37px;">Benefits for Town Planners</h1>
                <p class="med" style="font-size: 20px;">The PropertEASE online system can provide you with instant answers when discussing a property with a client.</p>
                <p style="font-size: 19px;">A month to month or yearly unlimited subscription can ensure that you get the best value for money to utilize the system.
                    <br>
                    <br>
                </p>
                <h4 style="font-size: 23px;"><strong>PropertEASE has a range of advantages for a town planner including:</strong></h4>
                <div class="medias"><a class="pull-right img-c" href="#"> <img class="media-object" src="<?php echo $mainURL; ?>images/town-planners-ico.png" alt=""> </a>
                    <div class="media-body">
                        <ul class="green-list">
                            <li>Getting instant and accurate information making the system extremely time (and money) efficient;</li>
                            <li>Allows the user to provide a client with instant detailed advice regarding a property;</li>
                            <li>Is mobile friendly (i.e. use it on site) to give instant answers to a client;</li>
                            <li>Will allow any assistant or untrained employee to quickly obtain information;</li>
                            <li>Avoid spending hours reading through planning schemes; and</li>
                            <li>Removes the human element (errors) when finding property limitations.</li>
                        </ul>
                    </div>
                    
                </div>
                <p>One of our town planning clients saves up to 2 hours a day between 3 staff by utilizing the PropertEASE service.</p>
             </div><!-- tab pane -->
             
        
   
             <div id="real-estate-agents" class="tab-pane fade in"><!-- tab pane -->
                <h1 style="font-size: 37px;">Benefits for Real Estate Agents</h1>
                <p class="med" style="font-size: 20px;">The PropertEASE online system can provide you with instant answers when discussing a property with a prospective seller or purchaser.</p>
                <p style="font-size: 19px;">A month to month or yearly unlimited subscription can ensure that you get the best value for money to utilize the system.
                    <br>
                    <br>
                </p>
                <h4 style="font-size: 23px;"><strong>PropertEASE provides:</strong></h4>
                <div class="medias"><a class="pull-right img-c" href="#"> <img class="media-object" src="<?php echo $mainURL; ?>images/real-estate-agents-ico.png" alt=""> </a>
                    <div class="media-body">
                        <ul class="green-list">
                            <li>Easy instant information (using our online resources);</li>
                            <li>A simple overview of a properties development potential to give you an informed sales pitch and the upper hand in negotiations;</li>
                            <li>Ensures that your asking price coincides with a properties potential; I.e. get the best sales price with the correct information;</li>
                            <li>Saves hours a day waiting on hold to Council or town planners to get the same answers; and</li>
                            <li>Is mobile friendly (i.e. use it on site).</li>
                        </ul>
                    </div>
                    
                </div>
             </div><!-- tab pane -->
           
             
             <div id="investors" class="tab-pane fade in"><!-- tab pane -->
                <h1 style="font-size: 37px;">Benefits for Investors</h1>
                <p class="med" style="font-size: 20px;">It can take hours to investigate the development potential of a property, PropertEASE can give you answers instantly.</p>
                <p style="font-size: 19px;">One off reports can be purchased and a month to month or yearly unlimited subscription can ensure that you get the best value for money to utilize the system.
                    <br>
                    <br>
                </p>
                <h4 style="font-size: 23px;"><strong>PropertEASE provides:</strong></h4>
                <div class="medias"><a class="pull-right img-c" href="#"> <img class="media-object" src="<?php echo $mainURL; ?>images/investors-ico.png" alt=""> </a>
                    <div class="media-body">
                        <ul class="green-list">
                            <li>Easy instant information (using our online resources);</li>
<li>A simple overview of a properties development potential to give you the information to make a quick 'yes' or 'no' answer when purchasing;</li>
<li>Gives you the current development potential of a property. Could you subdivide? Could you do units? Could you build a secondary dwelling?</li>
<li>Saves hours a day waiting on hold to Council or town planners to get the same answers;</li>
<li>Gives you the upper hand when negotiating with real estate agents; and</li>
<li>Is mobile friendly (i.e. use it on site).</li>
                        </ul>
                    </div>
                    
                </div>
             </div><!-- tab pane -->

             <div id="builders" class="tab-pane fade in"><!-- tab pane -->
                <h1 style="font-size: 37px;">Benefits for Builders</h1>
                <p class="med" style="font-size: 20px;">The PropertEASE online system can provide you with instant answers when looking at a property for a prospective client.</p>
                <p style="font-size: 19px;">A month to month or yearly unlimited subscription can ensure that you get the best value for money to utilize the system.
                    <br>
                    <br>
                </p>
                <h4 style="font-size: 23px;"><strong>The PropertEASE system is:</strong></h4>
                <div class="medias"><a class="pull-right img-c" href="#"> <img class="media-object" src="<?php echo $mainURL; ?>images/builders-ico.png" alt=""> </a>
                    <div class="media-body">
                        <ul class="green-list">
                            <li>Very easy to use (with our online resources);</li>
                            <li>Gives a quick overview of the development potential of a property before meeting with a client/owner;</li>
                            <li>Saves hours a day waiting on hold to Council or town planners to get the same answers;</li>
                            <li>Assists in engaging discussion regarding town planning matters with clients/owners and</li>
                            <li>Is mobile friendly (i.e. use it on site).</li>
                        </ul>
                    </div>
                    
                </div>
             </div><!-- tab pane -->
            
        </div><!-- tab content -->
        
    </div>
</div>
</div>

<!--  end o f tabs -->  
                <div class="moduletable green-bg our-process">  
 <div class="custom green-bg our-process">  
      <div class="container">  
 <div class="process">  
 <h3>Our Process</h3>  
 <div class="row">  
 <div style="visibility: hidden; animation-delay: 0.1s; animation-name: none;" class="col-md-4 col-sm-4 col-xs-12 wow slideInLeft" data-wow-delay="0.1s">  
 <div class="panel-content">  
 <div class="arrow">arrow</div>  
 <h5 class="med">Step 1</h5>  
 <p><span class="med">Enter</span> the address of <br>your property.</p>  
 <img src="<?php echo $mainURL; ?>images/step1.png" alt=""></div>  
 </div>  
 <div style="visibility: hidden; animation-delay: 0.2s; animation-name: none;" class="col-md-4 col-sm-4 col-xs-12 wow slideInLeft" data-wow-delay="0.2s">  
 <div class="panel-content">  
 <div class="arrow">arrow</div>  
 <h5 class="med">Step 2</h5>  
 <p><span class="med">Select</span> the property <br>attributes.</p>  
 <img src="<?php echo $mainURL; ?>images/step2.png" alt=""></div>  
 </div>  
 <div style="visibility: hidden; animation-delay: 0.5s; animation-name: none;" class="col-md-4 col-sm-4 col-xs-12 last wow slideInLeft" data-wow-delay="0.5s">  
 <div class="panel-content">  
 <h5 class="med">Step 3</h5>  
 <p><span class="med">Review</span> your results on <br>our online app or<br> print and download a <br>report.</p>  
 <img src="<?php echo $mainURL; ?>images/step3.png" alt=""></div>  
 </div>  
 </div>  
 </div>  
 </div></div>  
           </div>  
                <div class="moduletabletestimonial">  
                                    <h3>What our subscribers say</h3>  
                               <div style="border: 0px !important;">  
 <div style="background: none repeat scroll 0% 0% transparent; padding-top: 0px; padding-bottom: 0px;" id="djslider-loader87" class="djslider-loader djslider-loader-default">  
   <div id="djslider87" class="djslider djslider-default" style="height: 280px; width: 1336px; max-width: 1336px; opacity: 1;">  
     <div id="slider-container87" class="slider-container">  
          <ul style="position: relative; left: -2672px; width: 4011px;" id="slider87" class="djslider-in">  
                                    <li style="margin: 0 0px 0px 0 !important; height: 280px; width: 1336px;">  
                                                                                            <img class="dj-image" src="<?php echo $mainURL; ?>images/arch.jpg" alt="Architect" style="width: 100%; height: auto;">  
                                                                                                                              <!-- Slide description area: START -->  
                               <div class="slide-desc" style="bottom: 0%; left: 0%; width: 100%;">  
                                <div class="slide-desc-in">       
                                    <div class="slide-desc-bg slide-desc-bg-default"></div>  
                                    <div class="slide-desc-text slide-desc-text-default">  
                                                                            <div class="slide-text">  
                                                                                                <p>“I use <span style="color: #7ab700;"> Propert<span class="bold">EASE</span> </span>every day, it helps me figure out which properties I could<br>work with and which to move on from. <span class="bold">Such a time saver</span>.”</p>  
 <p class="bold" style="font-size: 21px;">Carl Ryan, DEVELOPER</p>                                                                                     </div>  
                                                                       <div style="clear: both"></div>  
                                    </div>  
                                </div>  
                               </div>  
                               <!-- Slide description area: END -->  
                          </li>  
                             <li style="margin: 0 0px 0px 0 !important; height: 280px; width: 1336px;">  
                                                                                            <img class="dj-image" src="<?php echo $mainURL; ?>images/town.jpg" alt="Town Planner" style="width: 100%; height: auto;">  
                                                                                                                              <!-- Slide description area: START -->  
                               <div class="slide-desc" style="bottom: 0%; left: 0%; width: 100%;">  
                                <div class="slide-desc-in">       
                                    <div class="slide-desc-bg slide-desc-bg-default"></div>  
                                    <div class="slide-desc-text slide-desc-text-default">  
                                                                            <div class="slide-text">  
                                                                                                <p>“I use <span style="color: #7ab700;"> Propert<span class="bold">EASE</span> </span>every day, it helps me figure out which properties I could<br>work with and which to move on from. <span class="bold">Such a time saver</span>.”</p>  
 <p class="bold" style="font-size: 21px;">Carl Ryan, DEVELOPER</p>                                                                                     </div>  
                                                                       <div style="clear: both"></div>  
                                    </div>  
                                </div>  
                               </div>  
                               <!-- Slide description area: END -->  
                          </li>  
                             <li style="margin: 0 0px 0px 0 !important; height: 280px; width: 1336px;">  
                                                                                            <img class="dj-image" src="<?php echo $mainURL; ?>images/dev.jpg" alt="Developer" style="width: 100%; height: auto;">  
                                                                                                                              <!-- Slide description area: START -->  
                               <div class="slide-desc" style="bottom: 0%; left: 0%; width: 100%;">  
                                <div class="slide-desc-in">       
                                    <div class="slide-desc-bg slide-desc-bg-default"></div>  
                                    <div class="slide-desc-text slide-desc-text-default">  
                                                                            <div class="slide-text">  
                                                                                                <p>“I use <span style="color: #7ab700;"> Propert<span class="bold">EASE</span> </span>every day, it helps me figure out which properties I could<br>work with and which to move on from. <span class="bold">Such a time saver</span>.”</p>  
 <p class="bold" style="font-size: 21px;">Carl Ryan, DEVELOPER</p>                                                                                     </div>  
                                                                       <div style="clear: both"></div>  
                                    </div>  
                                </div>  
                               </div>  
                               <!-- Slide description area: END -->  
                          </li>  
                  </ul>  
     </div>  
         <div id="navigation87" class="navigation-container" style="top: 10.714285714286%; margin: 0 5px;">  
                   <img id="prev87" class="prev-button " src="<?php echo $mainURL; ?>images/prev.png" alt="Previous">  
                <img id="next87" class="next-button " src="<?php echo $mainURL; ?>images/next.png" alt="Next">  
                                   </div>  
           </div>  
 </div>  
 </div>  
 <div style="clear: both"></div>          </div>  
                <div class="moduletable green-bg">  
                                    <h3>Get started</h3>  
 <div class="custom green-bg">  
      <div style="visibility: hidden; animation-duration: 1500ms; animation-name: none;" class="container get-started wow pulse animated" data-wow-duration="1500ms">  
 <div class="row ">  
 <div class="col-md-7 col-xs-12 col-sm-6 left-get-started">  
 <div class="block-left-started light">Get your first report for just<span class="font-l"> $59</span>, or <span class="bold" style="font-size: 36px;">subscribe</span> for<span class="font-l"> $79</span> for month one and <span class="bold" style="font-size: 45px;"> save $100</span></div>  
 </div>  
 <div class="col-md-5 col-sm-6 col-xs-12">  
 <div class="price-start">  
 <ul>  
 <li>  
 <p><span class="light">$</span>59</p>  
 <p style="font-size: 20px;">One Report</p>  
 </li>  
 <li>  
 <p class="award"><span class="light">$</span>79</p>  
 <p style="font-size: 20px;"><span class="blk">One Month</span></p>  
 <p class="green">Unlimited Searches</p>  
 </li>  
 </ul>  
 <a href="#"><span class="blk">GET STARTED</span></a></div>  
 </div>  
 </div>  
 </div></div>  
           </div>  
                <div class="moduletable survey-form">  
 <div class="custom survey-form">  
      <div class="container">  
 <div class="heading-contact wow bounceInDown center animated" style="font-size: 42px; line-height: 130%; visibility: hidden; animation-name: none;"><span class="blk"><img style="display: block; margin-left: auto; margin-right: auto;" src="<?php echo $mainURL; ?>images/xucxac.png" alt=""></span></div>  
 <div class="heading-contact wow fadeInDown" style="font-size: 42px; line-height: 130%; text-align: center; visibility: hidden; animation-name: none;"><span class="blk">Complete the Survey </span>and be in the draw to <br><span class="blk" style="color: #fbc012;">WIN</span> a Month's Subscription<span class="blk" style="color: # 7bb801;">Valued at $79</span></div>  
 <p style="text-align: center; font-size: 22px; line-height: 130%;">Drawn each month, winners notified by email.</p>  
 <br>  
 <div style="visibility: hidden; animation-name: none;" class="wow fadeInDown "><!-- START: Modules Anywhere --><script type="text/javascript">  
           if("undefined"==typeof window.jQuery){}else{  
                var gcore_jQuery_bak = window.jQuery;  
                var gcore_$_bak = window.$;  
           }  
           if("undefined"==typeof gcore_jQuery){}else{  
                window.jQuery = gcore_jQuery;  
                window.$ = gcore_$;  
           }  
           </script>  
 <script type="text/javascript">  
 $ = jQuery.noConflict();  
 jQuery(document).ready(function($){ $("#chronoform-Complete_the_Survey").gvalidate(); });  
 jQuery(document).ready(function($){  
                          $("#chronoform-Complete_the_Survey").find(":input[class*=validate]").each(function(){  
                               if($(this).attr("class").indexOf("required") >= 0 || $(this).attr("class").indexOf("group") >= 0){  
                                    if($(this).closest(".gcore-subinput-container").length > 0){  
                                         var required_parent = $(this).closest(".gcore-subinput-container");  
                                    }else if($(this).closest(".gcore-form-row").length > 0){  
                                         var required_parent = $(this).closest(".gcore-form-row");  
                                    }  
                                    if(required_parent.length > 0){  
                                         var required_label = required_parent.find("label");  
                                         if(required_label.length > 0 && !required_label.first().hasClass("required_label")){  
                                              required_label.first().addClass("required_label");  
                                              required_label.first().html(required_label.first().html() + " <i class='fa fa-asterisk' style='color:#ff0000; font-size:9px; vertical-align:top;'></i>");  
                                         }  
                                    }  
                               }  
                          });  
                     });  
 jQuery(document).ready(function($){  
                     $("#chronoform-Complete_the_Survey").find(":input").each(function(){  
                          if($(this).data("tooltip") && $(this).closest(".gcore-input").length > 0 && $(this).closest(".gcore-input").next(".input-tooltip").length < 1){  
                               var $tip = $('<i class="fa fa-exclamation-circle input-tooltip" style="float:left; padding:7px 0px 0px 7px;"></i>').attr("title", $(this).data("tooltip"));  
                               $(this).closest(".gcore-input").after($tip);  
                          }  
                     });  
                     $("#chronoform-Complete_the_Survey .input-tooltip").gtooltip("hover");  
                });  
 jQuery(document).ready(function($){  
                     $("#chronoform-Complete_the_Survey").find(':input[data-load-state="disabled"]').prop("disabled", true);  
                     $("#chronoform-Complete_the_Survey").find(':input[data-load-state="hidden"]').css("display", "none");  
                     $("#chronoform-Complete_the_Survey").find(':input[data-load-state="hidden_parent"]').each(function(){  
                          if($(this).closest(".gcore-subinput-container").length > 0){  
                               $(this).closest(".gcore-subinput-container").css("display", "none");  
                          }else if($(this).closest(".gcore-form-row").length > 0){  
                               $(this).closest(".gcore-form-row").css("display", "none");  
                          }  
                     });  
                });  
 jQuery(document).ready(function($){ $(":input").inputmask(); });</script>  
           <script type="text/javascript">  
           if("undefined"==typeof gcore_jQuery){  
                var gcore_jQuery = window.jQuery;  
                var gcore_$ = window.$;  
           }  
           if("undefined"==typeof gcore_jQuery_bak){}else{  
                window.jQuery = gcore_jQuery_bak;  
                window.$ = gcore_$_bak;  
           }  
           </script>  
           <div style="clear:both;"></div><div class="gbs3"><form action="http://localhost:1000:1000/properteasenew/?chronoform=Complete_the_Survey&amp;event=submit" enctype="multipart/form-data" method="post" name="Complete_the_Survey" id="chronoform-Complete_the_Survey" class="chronoform form-horizontal"><div class="row" id="chronoform-container-8"><div class="col-md-6" id="chronoform-container-12"><div class="form-group gcore-form-row" id="form-row-2"><label for="name" class="control-label gcore-label-top required_label">name <i class="fa fa-asterisk" style="color:#ff0000; font-size:9px; vertical-align:top;"></i></label>  
 <div class="gcore-input-wide gcore-display-table" id="fin-name"><input name="name" id="name" placeholder="What is your name?" maxlength="" size="" class="validate['required'] form-control A" title="" style="" data-inputmask="" data-load-state="" data-tooltip="" type="text"></div></div></div><div class="col-md-6 col-md-12" id="chronoform-container-14"><div class="form-group gcore-form-row" id="form-row-4"><label for="email" class="control-label gcore-label-top required_label">email <i class="fa fa-asterisk" style="color:#ff0000; font-size:9px; vertical-align:top;"></i></label>  
 <div class="gcore-input-wide gcore-display-table" id="fin-email"><input name="email" id="email" placeholder="Email address?" maxlength="" size="" class="validate['required','email'] form-control A" title="" style="" data-inputmask="" data-load-state="" data-tooltip="" type="text"></div></div></div></div><div class="row" id="chronoform-container-16"><div class="col-md-6 col-md-12" id="chronoform-container-17"><div class="form-group gcore-form-row" id="form-row-6"><label for="Postcode" class="control-label gcore-label-top">Postcode</label>  
 <div class="gcore-input-wide gcore-display-table" id="fin-Postcode"><input name="Postcode" id="Postcode" placeholder="Enter your Postcode" maxlength="" size="" class="form-control A" title="" style="" data-inputmask="" data-load-state="" data-tooltip="" type="text"></div></div></div><div class="col-md-6 col-md-12" id="chronoform-container-21"><div class="form-group gcore-form-row" id="form-row-8"><label for="Phone" class="control-label gcore-label-top">Phone</label>  
 <div class="gcore-input-wide gcore-display-table" id="fin-Phone"><input name="Phone" id="Phone" placeholder="Phone?" maxlength="" size="" class="validate['phone'] form-control A" title="" style="" data-inputmask="" data-load-state="" data-tooltip="" type="text"></div></div></div></div><div class="form-group gcore-form-row" id="form-row-9"><label for="subject" class="control-label gcore-label-top required_label">subject <i class="fa fa-asterisk" style="color:#ff0000; font-size:9px; vertical-align:top;"></i></label>  
 <div class="gcore-input-wide gcore-display-table" id="fin-subject"><textarea name="subject" id="subject" placeholder="Why would you use this project? " rows="3" cols="40" class="validate['required'] form-control A" title="" style="" data-wysiwyg="0" data-load-state="" data-tooltip=""></textarea></div></div><div class="form-group gcore-form-row" id="form-row-10"><div class="gcore-input gcore-display-table" id="fin-button23"><input name="button23" id="button23" value="Take the Survey" class="form-control A" style="" data-load-state="" type="submit"></div></div></form><p class="chrono_credits"><a href="http://www.chronoengine.com/" target="_blank">Powered by ChronoForms - ChronoEngine.com</a></p></div><!-- END: Modules Anywhere --></div>  
 </div></div>  
           </div>  
                     </div>  
                     <!-- //HEAD SEARCH -->  
 <div id="t3-mainbody" class="container t3-mainbody">  
      <div class="row">  
           <!-- MAIN CONTENT -->  
           <div id="t3-content" class="t3-content col-xs-12">  
                               <div class="blog-featured" itemscope="" itemtype="http://schema.org/Blog">  
 </div>  
           </div>  
           <!-- //MAIN CONTENT -->  
      </div>  
 </div>   
 <!-- FOOTER -->  
 <footer id="t3-footer" class="wrap t3-footer">  
                <!-- FOOT NAVIGATION -->  
           <div class="container">  
                     <!-- SPOTLIGHT -->  
      <div class="t3-spotlight t3-footnav row">  
                          <div class=" col-lg-6 col-md-6 col-sm-6 col-xs-12">  
                                         <div class="t3-module module footer-menu " id="Mod103"><div class="module-inner"><div class="module-ct"><ul class="nav nav-pills nav-stacked ">  
 <li class="item-140 divider"><span class="separator">Copyright 2015</span>  
 </li><li class="item-141"><a href="http://dev.dusted.com.au/propertease0724/">About PropertEASE</a></li><li class="item-142"><a href="http://dev.dusted.com.au/propertease0724/conditions-of-use.html">Conditions of Use</a></li></ul>  
 </div></div></div>  
                                    </div>  
                          <div class=" col-lg-6 col-md-6 col-sm-6 col-xs-12">  
                                         <div class="t3-module module footer-menu-right " id="Mod130"><div class="module-inner"><div class="module-ct"><ul class="nav nav-pills nav-stacked ">  
 <li class="item-101 current active"><a class="home" href="<?php echo $mainURL; ?>">Home</a></li><li class="item-113"><a href="<?php echo $mainURL; ?>subscribe.html">Plans   </a></li><li class="item-112"><a href="<?php echo $mainURL; ?>sample-reports.html">Sample Reports</a></li><li class="item-169"><a href="<?php echo $mainURL; ?>resources.html">Resources</a></li><li class="item-114"><a class="Connect bold" href="<?php echo $mainURL; ?>faq.html" title="  ">Connect    </a></li><li class="item-233"><a href="<?php echo $mainURL; ?>popup.html">Popup</a></li></ul>  
 </div></div></div>  
                                    </div>  
                </div>  
 <!-- SPOTLIGHT -->          </div>  
           <!-- //FOOT NAVIGATION -->  
 </footer>  
 <script type="text/javascript">  
       jQuery(document).ready(function(){  
            new WOW().init();  
        jQuery('#layerslider').layerSlider({  
                          hoverPrevNext : false,  
                          //responsive       : true,  
                           //responsiveUnder     : 768,  
                          slideDirection :'fade',  
                          loops : 1,  
                          forceLoopNum :true               });  
  })  
 </script>  
 </div>  
      </div></body></html> 