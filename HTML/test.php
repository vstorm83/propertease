<?php
//$mainURL = 'http://'.$_SERVER['HTTP_HOST'].'';
$mainURL = 'http://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'].'';
?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en-gb" slick-uniqueid="3" dir="ltr" class="com_content view-article itemid-227 resource j34 mm-hover no-touch" lang="en-gb"><head>

  <meta http-equiv="content-type" content="text/html; charset=UTF-8">
  <meta name="author" content="Super User">
  <meta name="generator" content="Joomla! - Open Source Content Management">
  <title> Start</title>
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

</head>

<body class="page_bg">

    <div class="t3-wrapper">
        <!-- Need this wrapper for off-canvas menu. Remove if you don't use of-canvas -->

        <div class="head-top">
            <div class="container">


                <!-- HEADER -->
                <div id="t3-header" class=" t3-header">
                    <div class="row ">
                        <div class="top-left col-md-6 ">
                            <!-- LOGO -->
                            <div class="pull-left  logo">
                                <div class="logo-image">
                                    <a href="<?php echo $mainURL; ?>" title="Propertease">
                                        <img class="logo-img" src="<?php echo $mainURL; ?>/images/logo.png" alt="Propertease">
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
                                        <div class="pull-left search-menu"><a>CONTACT</a>
                                        </div>
                                        <div class="pull-left">
                                            <form>
                                                <input placeholder="Search Database" type="text">
                                            </form>
                                        </div>
                                    </div>
                                </div>

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
	BT_AJAX					:'<?php echo $mainURL; ?>/start.html',
	BT_RETURN				:'/propertease/start.html',
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
						

<div class="item-pageresource clearfix">


<!-- Article -->
<article itemscope="" itemtype="http://schema.org/Article">
    <meta itemprop="inLanguage" content="en-GB">


    <header class="article-header clearfix">
        <h1 class="article-title" itemprop="name">
        <i></i>
					Need help?			<meta itemprop="url" content="<?php echo $mainURL; ?>/resources.html">
			</h1>

    </header>

<!-- Aside -->
<aside class="article-aside clearfix">
    <dl class="article-info  muted">
		<dt class="article-info-term">Details</dt>
        <dd data-original-title="Published: " class="published hasTooltip" title="">
            <i class="icon-calendar"></i>
            <time datetime="2015-03-10T02:27:03+00:00" itemprop="datePublished">
                10 March 2015 </time>
        </dd>
	</dl>
</aside>
<!-- //Aside -->	
<section class="article-content clearfix" itemprop="articleBody">
    <div class="row">
        <div class="col-md-6">
            <h3>Frequently Asked Questions <i class="light">and Answers</i></h3>
            <div id="accordion" class="panel-group">
                <div class="panel panel-default">
                    <div id="headingOne" class="panel-heading">
                        <h4 class="panel-title"><a aria-expanded="false" class="collapsed" href="#collapseOne" data-parent="#accordion" data-toggle="collapse"> Q: What information do I get? </a></h4>
                    </div>
                    <div style="height: 0px;" aria-expanded="false" id="collapseOne" class="panel-collapse collapse">
                        <div class="panel-body">
                            <p class="txt">A:You can review a sample report here. Our system will give general information based on the zoning, overlays and neighbourhood plan covering a property. It will give the minimum lot size (and frontage) allowable for a subdivision, minimum lot size (and frontage) required for a unit development, maximum height, maximum site cover, maximum Gross Floor Area, maximum density and general information regarding small lot house provisions, secondary dwelling potential and multiple unit dwelling boundary setbacks. The system does not provide site-specific information.</p>
                            <ul class="list">
                                <li>Was this article helpful?<a class="btn">Yes</a><a class="btn">No</a>
                                </li>
                                <li>Have a <i>Live Chat</i> with us.<a class="btn-live-chat">Live Chat</a>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
                <div class="panel panel-default">
                    <div id="headingTwo" class="panel-heading">
                        <h4 class="panel-title"><a aria-expanded="false" class="collapsed" href="#collapseTwo" data-parent="#accordion" data-toggle="collapse">How do I find the zoning/overlay/neighbourhood plan information about my property? </a></h4>
                    </div>
                    <div aria-expanded="false" id="collapseTwo" class="panel-collapse collapse" style="height: 0px;">
                        <div class="panel-body">
                            <p class="txt">Anim pariatur cliche reprehenderit, enim eiusmod high life accusamus terry richardson ad squid. 3 wolf moon officia aute, non cupidatat skateboard dolor brunch. Food truck quinoa nesciunt laborum eiusmod. Brunch 3 wolf moon tempor, sunt aliqua put a bird on it squid single-origin coffee nulla assumenda shoreditch et. Nihil anim keffiyeh helvetica, craft beer labore wes anderson cred nesciunt sapiente ea proident. Ad vegan excepteur butcher vice lomo. Leggings occaecat craft beer farm-to-table, raw denim aesthetic synth nesciunt you probably haven't heard of them accusamus labore sustainable VHS.</p>
                        </div>
                    </div>
                </div>
                <div class="panel panel-default">
                    <div id="headingThree" class="panel-heading">
                        <h4 class="panel-title"><a aria-expanded="true" class="" href="#collapseThree" data-parent="#accordion" data-toggle="collapse">How often can I use my subscription? </a></h4>
                    </div>
                    <div aria-expanded="true" id="collapseThree" class="panel-collapse collapse in" style="">
                        <div class="panel-body">
                            <p class="txt">Anim pariatur cliche reprehenderit, enim eiusmod high life accusamus terry richardson ad squid. 3 wolf moon officia aute, non cupidatat skateboard dolor brunch. Food truck quinoa nesciunt laborum eiusmod. Brunch 3 wolf moon tempor, sunt aliqua put a bird on it squid single-origin coffee nulla assumenda shoreditch et. Nihil anim keffiyeh helvetica, craft beer labore wes anderson cred nesciunt sapiente ea proident. Ad vegan excepteur butcher vice lomo. Leggings occaecat craft beer farm-to-table, raw denim aesthetic synth nesciunt you probably haven't heard of them accusamus labore sustainable VHS.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-1">
            <div class="item-gr">or</div>
        </div>
        <div class="col-md-5">
            <div class="ytb-help">
                <h3>Youtube help <i class="light">videos</i></h3>
                <ul>
                    <li><a href="https://www.youtube.com/watch?v=R6JzrAmXxdY"> Brisbane City Council <i>Visit Link</i> </a>
                    </li>
                    <li><a href="https://www.youtube.com/watch?v=-W8urg63FA8" target="_blank">Logan City Council<i>Visit Link</i></a>
                    </li>
                    <li><a href="https://www.youtube.com/watch?v=9ee-G9mGVL8" target="_blank">Gold Coast City Council<i>Visit Link</i></a>
                    </li>
                    <li><a href="https://www.youtube.com/watch?v=6BCl8PhORFE" target="_blank">Redland City Council<i>Visit Link</i></a>
                    </li>
                    <li><a href="https://www.youtube.com/watch?v=CRK-2-c44kQ" target="_blank">Ipswich City Council<i>Visit Link</i></a>
                    </li>
                    <li><a href="https://www.youtube.com/watch?v=SWZll-0LO1g" target="_blank">Moreton Bay Regional Council<i>Visit Link</i></a>
                    </li>
                    <li><a href="https://www.youtube.com/watch?v=wq00Amv33pY" target="_blank">Sunshine Coast Regional Council<i>Visit Link</i></a>
                    </li>
                    <li><a href="https://www.youtube.com/watch?v=dIxJPKE_S08" target="_blank">Toowoomba Regional CouncilV<i>Visit Link</i></a>
                    </li>
                    <li><a href="https://www.youtube.com/watch?v=yw5BpfxVAow" target="_blank">Fraser Coast Regional Council<i>Visit Link</i></a>
                    </li>
                </ul>
            </div>
        </div>
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