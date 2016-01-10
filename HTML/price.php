<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en-gb" slick-uniqueid="3" dir="ltr" class="com_content view-article itemid-230 price j34 mm-hover no-touch" lang="en-gb"><head>
	  <!-- base href="http://dev.dusted.com.au/propertease/price.html" -->
  <meta http-equiv="content-type" content="text/html; charset=UTF-8">
  <meta name="author" content="Super User">
  <meta name="generator" content="Joomla! - Open Source Content Management">
  <title>Price</title>
  
  <link rel="stylesheet" href="css/bootstrap.css" type="text/css">
  <link rel="stylesheet" href="css/main.minified.css" type="text/css">
  <link rel="stylesheet" href="css/font-awesome.css" type="text/css">
  <link rel="stylesheet" href="css/animate.css" type="text/css">
  <link rel="stylesheet" href="css/djimageslider.css" type="text/css">
  <link href="css/bootstrap-gbs3.css" media="screen" rel="stylesheet" type="text/css">
  <link href="css/bootstrap-theme-gbs3.css" media="screen" rel="stylesheet" type="text/css">
  <link href="css/bootstrap-gcore-gbs3.css" media="screen" rel="stylesheet" type="text/css">
  
  <script src="js/jquery.js" type="text/javascript"></script>
  <script src="js/plugins.js" type="text/javascript"></script>
  <script src="js/bootstrap.js" type="text/javascript"></script>
  <script src="js/mootools-core.js" type="text/javascript"></script>
  <script src="js/mootools-more.js" type="text/javascript"></script>
  <script type="text/javascript">
jQuery(window).on('load',  function() {
				new JCaption('img.caption');
			});
jQuery(document).ready(function(){
	jQuery('.hasTooltip').tooltip({"html": true,"container": "body"});
	
	jQuery('.block-price div.block').hover(
       function(){ 
			
			jQuery(".block-price div.block:not(:hover)").addClass( "greyed" )	
		},
       function(){ 
	   		
			jQuery('.block-price div.block').removeClass('greyed')
	   }
	)
	
	jQuery("div.block").click(function(){
		 //var id = this.id;
		 
		 alert(this.id);
		 jQuery("a.sbm-btn").addClass( "has-price-selected" )
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
		@-webkit-viewport   { width: device-width; }
		@-moz-viewport      { width: device-width; }
		@-ms-viewport       { width: device-width; }
		@-o-viewport        { width: device-width; }
		@viewport           { width: device-width; }
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
<script type="text/javascript" src="/propertease/plugins/system/t3/base-bs3/js/respond.min.js"></script>
<![endif]-->

<!-- You can add Google Analytics here or use T3 Injection feature -->
</head>

<body class="page_bg">

<div class="t3-wrapper"> <!-- Need this wrapper for off-canvas menu. Remove if you don't use of-canvas -->

  <div class="head-top">
    <div class="container">
      

<!-- HEADER -->
<div id="t3-header" class=" t3-header">
	<div class="row ">
	<div class="top-left col-md-6 ">
		<!-- LOGO -->
		<div class="pull-left  logo">
			<div class="logo-image">
				<a href="http://dev.dusted.com.au/propertease" title="Propertease">
											<img class="logo-img" src="./images/logo-subpage.png" alt="Propertease">
															<span>Propertease</span>
				</a>
				<small class="site-slogan"></small>
			</div>
		</div>
		<!-- //LOGO -->
				<!-- HEAD SEARCH -->
		<div class="head-search ">
			

<div class="custom">
	<div class="search-bar">
<div class="pull-left search-menu"><a>CONTACT</a></div>
<div class="pull-left"><form><input placeholder="Search Database" type="text"></form></div>
</div></div>

		</div>
		<!-- //HEAD SEARCH -->
				

	</div>
			<!-- HEAD SEARCH -->
		<div class="top-right col-md-6  ">
			<div id="btl">
	<!-- Panel top -->	
	<div style="float: right;" class="btl-panel">
					<!-- Login button -->
						<span id="btl-panel-login-y" class="btn-login" data-toggle="modal" data-target="#login-popup">Log in</span>
						<!-- Registration button -->
						
			
			</div>
	<!-- content dropdown/modal box -->
	
	<div class="clear"></div>
</div>

<script type="text/javascript">
/*<![CDATA[*/
var btlOpt = 
{
	BT_AJAX					:'http://dev.dusted.com.au/propertease/price.html',
	BT_RETURN				:'/propertease/price.html',
	RECAPTCHA				:'none',
	LOGIN_TAGS				:'',
	REGISTER_TAGS			:'',
	EFFECT					:'btl-modal',
	ALIGN					:'right',
	BG_COLOR				:'#6d850a',
	MOUSE_EVENT				:'click',
	TEXT_COLOR				:'#fff'	
}
if(btlOpt.ALIGN == "center"){
	BTLJ(".btl-panel").css('textAlign','center');
}else{
	BTLJ(".btl-panel").css('float',btlOpt.ALIGN);
}
BTLJ("input.btl-buttonsubmit,button.btl-buttonsubmit").css({"color":btlOpt.TEXT_COLOR,"background":btlOpt.BG_COLOR});
//BTLJ("#btl .btl-panel > span").css({"color":btlOpt.TEXT_COLOR,"background-color":btlOpt.BG_COLOR,"border":btlOpt.TEXT_COLOR});
/*]]>*/
</script>

<ul class="nav  nav-pills nav-stacked ">
<li class="item-227"><a href="http://dev.dusted.com.au/propertease/start.html"> Start</a></li><li class="item-228"><a href="http://dev.dusted.com.au/propertease/support.html">support</a></li><li class="item-229">	<a href="http://dev.dusted.com.au/propertease/faq.html">Connect</a></li><li class="item-235"><a href="http://dev.dusted.com.au/propertease/my-app-page.html">My App</a></li></ul>

		</div>
		<!-- //HEAD SEARCH -->
		</div></div>
<!-- //HEADER -->
     <!--  <!?php $this->loadBlock('mainnav') ?> -->
    </div>
  </div>
    

  
<div id="t3-mainbody" class="container t3-mainbody">
	<div class="row">

		<!-- MAIN CONTENT -->
		<div id="t3-content" class="t3-content col-xs-12">
						

<div class="item-pageprice clearfix">


<!-- Article -->
<article itemscope="" itemtype="http://schema.org/Article">
	<meta itemprop="inLanguage" content="en-GB">

	
<header class="article-header clearfix">
	<h1 class="article-title" itemprop="name">
        <i></i>
					Prices			<meta itemprop="url" content="http://dev.dusted.com.au/propertease/price.html">
			</h1>

			</header>

<!-- Aside -->
<aside class="article-aside clearfix">
    	<dl class="article-info  muted">

		
			<dt class="article-info-term">
													Details							</dt>

			
			
			
										<dd data-original-title="Published: " class="published hasTooltip" title="">
				<i class="icon-calendar"></i>
				<time datetime="2015-06-21T14:16:03+00:00" itemprop="datePublished">
					21 June 2015				</time>
			</dd>					
					
			
						</dl>
    
  </aside>  
<!-- //Aside -->	
<section class="article-content clearfix" itemprop="articleBody">
    <div class="block-price">
        <div id="single-pricing" class="block">
            <h3>Single Report</h3> Just <i class="one-report">1</i> Report
            <br> Single User
            <br> <span class="through-line">Help Desk Support</span>
            <br>
            <p class="pay-month"><span class="light">$</span><span class="blk">59</span> <i>/ month</i></p>
            <p class="blk arrow-down">We do it for you.</p>
            <p class="plus">Add Concierge <span class="blk">(+$40)</span></p>
        </div>
        <div id="standard-pricing" class="block standard">
            <h3>Standard</h3>
            <span class="blk">Unlimited</span> Reports
            <br> Single User
            <br> Help Desk Support
            <br>
            <p class="pay-month"><span class="light">$</span><span class="blk">179</span> <i>/ month</i></p>
            <p class="plus">Up Front Cost <span class="blk">(Save $100)</span></p>
            <p class="plus none-border">Annual Prepaid <span class="blk">(Save $358)</span></p>
        </div>
        <div id="corporate-pricing" class="block corporate">
            <h3>Corporate</h3>
            <span class="blk">Unlimited</span> Reports
            <br> Up to <span class="blk">5 Users</span>
            <br> Help Desk Support
            <br>
            <p class="pay-month"><span class="light">$</span><span class="blk">379</span> <i>/ month</i></p>
            <p class="plus">Up Front Cost <span class="blk">(Save $100)</span></p>
            <p class="plus none-border">Annual Prepaid <span class="blk">(Save $758)</span></p>
        </div>
        <a class="sbm-btn" href="#"><span class="blk">Click Here</span> to <span class="blk">Get Started</span></a>
    </div>
</section>

  <!-- footer -->
    <!-- //footer -->

	
	
	
</article>
<!-- //Article -->


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
<div class="t3-spotlight t3-footnav  row">
    <div class=" col-lg-6 col-md-6 col-sm-6 col-xs-12">
        <div class="t3-module module footer-menu " id="Mod103">
            <div class="module-inner">
                <div class="module-ct">
                    <ul class="nav  nav-pills nav-stacked ">
                        <li class="item-140 divider"><span class="separator">Copyright 2015</span>
                        </li>
                        <li class="item-141"><a href="<?php echo $mainURL; ?>/about-propertease.html">About PropertEASE</a>
                        </li>
                        <li class="item-142"><a href="<?php echo $mainURL; ?>/conditions-of-use.html">Conditions of Use</a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
    <div class=" col-lg-6 col-md-6 col-sm-6 col-xs-12">
        <div class="t3-module module footer-menu-right " id="Mod142">
            <div class="module-inner">
                <div class="module-ct">
                    <ul class="nav  nav-pills nav-stacked ">
                        <li class="item-227 current active"><a href="<?php echo $mainURL; ?>/start.html"> Start</a>
                        </li>
                        <li class="item-228"><a href="<?php echo $mainURL; ?>/support.html">support</a>
                        </li>
                        <li class="item-229"> <a href="<?php echo $mainURL; ?>/faq.html">Connect</a>
                        </li>
                        <li class="item-235"><a href="<?php echo $mainURL; ?>/my-app-page.html">My App</a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- SPOTLIGHT -->		</div>
		<!-- //FOOT NAVIGATION -->
	
	

</footer>



</div>
</body></html>