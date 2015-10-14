<?php
//$mainURL = 'http://'.$_SERVER['HTTP_HOST'].'';
$mainURL = '.';
?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en-gb" slick-uniqueid="3" dir="ltr" class="com_content view-article itemid-114  Connect         j34 mm-hover no-touch" lang="en-gb"><head>
	  <!-- base href="http://dev.dusted.com.au/propertease/faq.html" -->
  <meta http-equiv="content-type" content="text/html; charset=UTF-8">
  <meta name="author" content="Super User">
  <meta name="generator" content="Joomla! - Open Source Content Management">
  <title>Connect        </title>
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
  <script src="js/gtooltip.js" type="text/javascript"></script>
  <script src="js/gvalidation.js" type="text/javascript"></script>
<script type="text/javascript">
jQuery(document).ready(function($) {});
jQuery(document).ready(function($) {
    $("#chronoform-contact").gvalidate();
});
jQuery(document).ready(function($) {
    $("#chronoform-contact").find(":input[class*=validate]").each(function() {
        if ($(this).attr("class").indexOf("required") >= 0 || $(this).attr("class").indexOf("group") >= 0) {
            if ($(this).closest(".gcore-subinput-container").length > 0) {
                var required_parent = $(this).closest(".gcore-subinput-container");
            } else if ($(this).closest(".gcore-form-row").length > 0) {
                var required_parent = $(this).closest(".gcore-form-row");
            }
            if (required_parent.length > 0) {
                var required_label = required_parent.find("label");
                if (required_label.length > 0 && !required_label.first().hasClass("required_label")) {
                    required_label.first().addClass("required_label");
                    required_label.first().html(required_label.first().html() + " <i class='fa fa-asterisk' style='color:#ff0000; font-size:9px; vertical-align:top;'></i>");
                }
            }
        }
    });
});
jQuery(document).ready(function($) {
    $("#chronoform-contact").find(":input").each(function() {
        if ($(this).data("tooltip") && $(this).closest(".gcore-input").length > 0 && $(this).closest(".gcore-input").next(".input-tooltip").length < 1) {
            var $tip = $('<i class="fa fa-exclamation-circle input-tooltip" style="float:left; padding:7px 0px 0px 7px;"></i>').attr("title", $(this).data("tooltip"));
            $(this).closest(".gcore-input").after($tip);
        }
    });
    $("#chronoform-contact .input-tooltip").gtooltip("hover");
});
jQuery(document).ready(function($) {
    $("#chronoform-contact").find(':input[data-load-state="disabled"]').prop("disabled", true);
    $("#chronoform-contact").find(':input[data-load-state="hidden"]').css("display", "none");
    $("#chronoform-contact").find(':input[data-load-state="hidden_parent"]').each(function() {
        if ($(this).closest(".gcore-subinput-container").length > 0) {
            $(this).closest(".gcore-subinput-container").css("display", "none");
        } else if ($(this).closest(".gcore-form-row").length > 0) {
            $(this).closest(".gcore-form-row").css("display", "none");
        }
    });
});
jQuery(document).ready(function($) {
    $(":input").inputmask();
});
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
				<a href="" title="Propertease">
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
	BT_AJAX					:'<?php echo $mainURL; ?>',
	BT_RETURN				:'/propertease/faq.html',
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
						

<div class="item-page Connect         clearfix">


<!-- Article -->
<article itemscope="" itemtype="http://schema.org/Article">
	<meta itemprop="inLanguage" content="en-GB">

	
<header class="article-header clearfix">
	<h1 class="article-title" itemprop="name">
        <i></i>
					connect			<meta itemprop="url" content="<?php echo $mainURL; ?>/faq.html">
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
    <div class="col-md-6 col-xs-12">
        <h3 style="font-size: 28px;">Support and General Enquiries</h3>
        <div class="map">
            <br>
            <br>&nbsp;</div>
        <span class="med">Propertease Pty Ltd, </span>
        <br> Po Box 153, Red Hill Qld 4059
        <br> Phone 0738448296
        <br> <span id="cloak48496"><a href="mailto:info@rodemic.com">info@rodemic.com</a></span>
        <script type="text/javascript">
            //<!--
            document.getElementById('cloak48496').innerHTML = '';
            var prefix = '&#109;a' + 'i&#108;' + '&#116;o';
            var path = 'hr' + 'ef' + '=';
            var addy48496 = '&#105;nf&#111;' + '&#64;';
            addy48496 = addy48496 + 'r&#111;d&#101;m&#105;c' + '&#46;' + 'c&#111;m';
            document.getElementById('cloak48496').innerHTML += '<a ' + path + '\'' + prefix + ':' + addy48496 + '\'>' + addy48496 + '<\/a>';
            //-->
        </script>
        <br> <a>www.rodemic.com </a></div>
    <div class="col-md-6 col-xs-12">
        <p>For other enquiries, you may email us <span class="bld">using this form.</span></p><!-- START: Modules Anywhere -->
		



		<div style="clear:both;"></div>
<div class="gbs3">
    <form action="./faq.html?chronoform=contact+form&amp;event=submit" enctype="multipart/form-data" method="post" name="contact form" id="chronoform-contact" class="chronoform form-horizontal">
        <div class="form-group gcore-form-row" id="form-row-2">
            <label for="name" class="control-label gcore-label-top">name</label>
            <div class="gcore-input-wide gcore-display-table" id="fin-name">
            
                <input name="name" id="name" placeholder="name" maxlength="" size="" class="validate['required'] form-control A" title="" style="" data-inputmask="" data-load-state="" data-tooltip="" type="text">
            </div>
        </div>
        <div class="form-group gcore-form-row" id="form-row-4">
            <label for="email" class="control-label gcore-label-top">email</label>
            <div class="gcore-input-wide gcore-display-table" id="fin-email">
                <input name="email" id="email" placeholder="email" maxlength="" size="" class="validate['required','email'] form-control A" title="" style="" data-inputmask="" data-load-state="" data-tooltip="" type="text">
            </div>
        </div>
        <div class="form-group gcore-form-row" id="form-row-5">
            <label for="Message" class="control-label gcore-label-top">Message</label>
            <div class="gcore-input-wide gcore-display-table" id="fin-Message">
                <textarea name="Message" id="Message" placeholder="Message" rows="3" cols="40" class="validate['required'] form-control A" title="" style="" data-wysiwyg="0" data-load-state="" data-tooltip=""></textarea>
            </div>
        </div>
        <div class="form-group gcore-form-row" id="form-row-6">
            <div class="gcore-input gcore-display-table" id="fin-button4">
                <input name="button4" id="button4" value="Submit" class="form-control A" style="" data-load-state="" type="submit">
            </div>
        </div>
    </form>
    
    </p>
</div>
<!-- END: Modules Anywhere -->
</div>
</div>

</section>
	
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