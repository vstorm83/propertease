<?php
defined('_JEXEC') or die;
$user=JFactory::getUser();
$userstate=(intval($user->id)>0?true:false);
if(isset($_REQUEST['conciergeaddy'])&&$userstate) {
  include(dirname(__FILE__).'/profilef.php');
  if(profilef::profileconcierge()) {
    if(trim($_REQUEST['conciergeaddy'])!='') {
      $to='alex@steffantownplanning.com';
      $subject='Concierge address request from '.$user->name;
      $message=$user->name.' ('.$user->email.') would like a search performed for '.trim($_REQUEST['conciergeaddy']);
      $result=profilef::send_email_plain($to,$subject,$message);
      profilef::profileexpireconciergesearch();
      header("Location: ".JURI::base().'?concr=1');
    } else {
      header("Location: ".JURI::base().'?concr=0');
    }
  } else {
    header("Location: ".JURI::base().'?concr=0');
  }
} else if(intval($_REQUEST['pgmm'])>0&&$userstate) {
  include(dirname(__FILE__).'/profilef.php');
  $searchid=profilef::profilesavesearch();
  header("Location: ".JURI::base().'?sr='.intval($searchid));
} else if($_REQUEST['pto']=='pdf'&&intval($_REQUEST['sr'])>0&&$userstate) {
  include(dirname(__FILE__).'/profilef.php');
  $ht=profilef::profilegetresults($_REQUEST['sr'],true);
  require_once(dirname(__FILE__).'/tcpdf/tcpdf_import.php');
  $pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
  $pdf->SetCreator(PDF_CREATOR);
  $pdf->SetAuthor(PDF_AUTHOR);
  $pdf->SetTitle('PropertEASE PDF Report');
  $pdf->setPrintHeader(false);
  $pdf->setPrintFooter(false);
  $pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);
  $pdf->SetMargins(PDF_MARGIN_LEFT, 15, PDF_MARGIN_RIGHT);
  $pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);
  $pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);
  $pdf->SetFont('helvetica', '', 10);
  $pdf->setFontSubsetting(false);
  $pdf->AddPage();
  $pdf->writeHTML($ht, true, false, true, false, '');
  $pdf->Output('report.pdf', 'D');
  exit();
} else {
  $content='<jdoc:include type="component" />';
  $menu =& JSite::getMenu();
  $ih=false;
  $homeid=0;
  $activeid=0;
  $alias='';
  if($menu) {
    $home=$menu->getDefault();
    $active=$menu->getActive();
    if($home) {
      $homeid=intval($home->id);
    }
    if($active) {
      $alias=$active->alias;
      $activeid=intval($active->id);
    }
  }
  if($activeid>0) {
    if($homeid==$activeid) {
      $ih=true;
    }
  }
  $isprofile=false;
  $isuser=false;
  if(intval($user->id)>0) {
    $isuser=true;
  }
  if($isuser) {
    include(dirname(__FILE__).'/profilef.php');
    if($ih) {
      $content=profilef::profilegetoutput();
      $isprofile=true;
    } else {
      if($alias=='past-searches') {
        $content=profilef::profilegetpastsearchesoutput();
      }
    }
  }
  JHTML::_('behavior.framework', true);
  
  /* The following line gets the application object for things like displaying the site name */
  $app = JFactory::getApplication();
  $tplparams  = $app->getTemplate(true)->params;
  $hasl=($this->countModules('left')?true:false);
  $hasr=($this->countModules('right')?true:false);
?>
 <!DOCTYPE html>  
 <html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en-gb" slick-uniqueid="3" dir="ltr" class="com_content view-article itemid-227 resource j34 mm-hover no-touch <?php if(intval($_GET['sr'])>0){ echo "reports"; } ?>" lang="en-gb"><head>  
  <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">  
  <meta name="HandheldFriendly" content="true">  
  <meta name="apple-mobile-web-app-capable" content="YES">   
  <jdoc:include type="head" />
  <link rel="stylesheet" href="<?php echo $this->baseurl ?>/templates/<?php echo $this->template ?>/css/bootstrap.css" type="text/css">  
  <link rel="stylesheet" href="<?php echo $this->baseurl ?>/templates/<?php echo $this->template ?>/css/font-awesome.css" type="text/css">  
  <link rel="stylesheet" href="<?php echo $this->baseurl ?>/templates/<?php echo $this->template ?>/css/animate.css" type="text/css">  
  <link rel="stylesheet" href="<?php echo $this->baseurl ?>/templates/<?php echo $this->template ?>/css/djimageslider.css" type="text/css">  
  <link href="<?php echo $this->baseurl ?>/templates/<?php echo $this->template ?>/css/bootstrap-gbs3.css" media="screen" rel="stylesheet" type="text/css">  
  <link href="<?php echo $this->baseurl ?>/templates/<?php echo $this->template ?>/css/bootstrap-theme-gbs3.css" media="screen" rel="stylesheet" type="text/css">  
  <link href="<?php echo $this->baseurl ?>/templates/<?php echo $this->template ?>/css/bootstrap-gcore-gbs3.css" media="screen" rel="stylesheet" type="text/css">  
  <link rel="stylesheet" href="<?php echo $this->baseurl ?>/templates/<?php echo $this->template ?>/css/bootstrap-select.min.css" type="text/css">  
  <link rel="stylesheet" href="<?php echo $this->baseurl ?>/templates/<?php echo $this->template ?>/css/main.css" type="text/css">  
  <link rel="stylesheet" href="<?php echo $this->baseurl ?>/templates/<?php echo $this->template ?>/css/style.css" type="text/css" />

<!--[if lt IE 9]>  
 <script src="//html5shim.googlecode.com/svn/trunk/html5.js"></script>  
 <script type="text/javascript" src="/propertease/plugins/system/t3/base-bs3/js/respond.min.js"></script>  
 <![endif]--> 
 <script>
    /*(function(d) {
      var config = {
        kitId: 'uog0btt',
        scriptTimeout: 3000,
        async: true
      },
      h=d.documentElement,t=setTimeout(function(){h.className=h.className.replace(/\bwf-loading\b/g,"")+" wf-inactive";},config.scriptTimeout),tk=d.createElement("script"),f=false,s=d.getElementsByTagName("script")[0],a;h.className+=" wf-loading";tk.src='//use.typekit.net/'+config.kitId+'.js';tk.async=true;tk.onload=tk.onreadystatechange=function(){a=this.readyState;if(f||a&&a!="complete"&&a!="loaded")return;f=true;clearTimeout(t);try{Typekit.load(config)}catch(e){}};s.parentNode.insertBefore(tk,s)
    })(document);*/
  </script>
  <script src="<?php echo $this->baseurl ?>/templates/<?php echo $this->template ?>/js/jquery.js" type="text/javascript"></script>  
   <script src="<?php echo $this->baseurl ?>/templates/<?php echo $this->template ?>/js/bootstrap.js" type="text/javascript"></script> 
    <script src="<?php echo $this->baseurl ?>/templates/<?php echo $this->template ?>/js/mootools-core.js" type="text/javascript"></script>  
  <script src="<?php echo $this->baseurl ?>/templates/<?php echo $this->template ?>/js/mootools-more.js" type="text/javascript"></script>
  <script src="<?php echo $this->baseurl ?>/templates/<?php echo $this->template ?>/js/bootstrap-select.min.js" type="text/javascript"></script>  
  <script src="<?php echo $this->baseurl ?>/templates/<?php echo $this->template ?>/js/plugins.js" type="text/javascript"></script>  
   <script src="<?php echo $this->baseurl ?>/templates/<?php echo $this->template ?>/js/script.js" type="text/javascript"></script>   
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
               <div class="pull-left logo">  
                 <div class="logo-image">  
                   <a href="<?php echo $this->baseurl ?>" title="Propertease">  
                     <img class="logo-img" src="<?php echo $mainURL; ?>images/logo2.png" alt="Propertease">  
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
                     <div class="pull-left search-menu"><a class="page-title"><?php 
                      echo $active->title;
                     ?></a>                       
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
           <div class="top-right col-md-6 ">  
               <?php include_once("element/header_nav.php"); ?>
           </div>  
           <!-- //HEAD SEARCH -->  
           </div></div>  

   </div>  
  </div>  
 <div id="t3-mainbody" class="container t3-mainbody">  
      <div class="row">  
           <!-- MAIN CONTENT -->  
           <div id="t3-content" class="t3-content col-xs-12">  
 <div class="item-pageresource clearfix">  
 <!-- Article -->  
 <?php 
 $option = JRequest::getCmd('option');
  $view = JRequest::getCmd('view');
  if ($option=="com_content" && $view=="article") {
      $ids = explode(':',JRequest::getString('id'));
      $article_id = $ids[0];
      $article =& JTable::getInstance("content");
      $article->load($article_id);
      $heading_title =  $article->get("title");
      $create_date = $article->get("created");
  }
 ?>
 <article itemscope="" itemtype="http://schema.org/Article">  
   <meta itemprop="inLanguage" content="en-GB">  
   <?php if(!(JSite::getMenu()->getActive()->menutype=="usermenu")){ ?>
   <header class="article-header clearfix">  
          <?php  
          if($heading_title<>""){
            echo '<h1 class="article-title" itemprop="name"><i class="sun"></i> '.$heading_title.'</h1>';
          }else{
            if ($menu->getActive() == $menu->getDefault() and $status>0  and intval($_GET['sr'])==0) {
              ?>
                <h1 class="article-title" itemprop="name"><i></i>
                  <input type="text" value="Give this report a name" id="report-name"></h1>
              <?php
            }else{
              ?>
                <h1 class="article-title" itemprop="name"><i class="sun"></i>
                <?php
                $doc = JFactory::getDocument();
                echo $doc->getTitle();
                ?>
                </h1>
              <?php
            }
          }
          ?>      
   </header>  
   <?php }
    if(JSite::getMenu()->getActive()->menutype=="usermenu"){
      ?>
      <div id="submenu">
      <div class="container">
    <ul class="nav nav-tabs">
        <li class="active"><a href="#profile-page" data-toggle="tab"><?php echo JText::_('OSM_EDIT_PROFILE');?></a></li>
        <li><a href="#my-subscriptions-page" data-toggle="tab"><?php echo JText::_('OSM_MY_SUBSCRIPTIONS');?></a></li>
        <li><a href="#subscription-history-page" data-toggle="tab"><?php echo JText::_('OSM_SUBSCRIPTION_HISTORY');?></a></li>
        <li><a href="#upgrade-page" data-toggle="tab">Renew Membership</a></li>	
        <?php 
            if (count($this->plugins)) 
            {
                $count = 0 ;
                foreach ($this->plugins as $plugin) 
                {
                    $title  = $plugin['title'] ;
                    $count++ ;
                ?>
                    <li><a href="#<?php echo 'tab_'.$count;  ?>" data-toggle="tab"><?php echo $title;?></a></li>
                <?php							
                }
            }
        ?>
                                
    </ul>
</div>
      </div>
      <?php
	  #<jdoc:include type="modules" name="usermenu" style="none"/> <!--- this should be dynamic
    }
    ?>

 <!-- Aside -->  
 <aside class="article-aside clearfix">  
   <?php 
    if($create_date<>""){
      ?>
      <dl class="article-info muted">  
           <dt class="article-info-term">Details</dt>  
           <dd data-original-title="Published: <?php echo date('j F Y h:i:s',strtotime($create_date)); ?>" class="published hasTooltip" title="">  
             <i class="icon-calendar"></i>  
             <time datetime="<?php echo $create_date; ?>" itemprop="datePublished">  
               <?php echo date('j F Y',strtotime($create_date)); ?> </time>  
           </dd>  
      </dl>  
      <?php
    }
    if(intval($_GET['sr'])>0){
      ?>
      <dl class="article-info muted">  
           <dt class="article-info-term">Details</dt>  
           <dd data-original-title="Published: <?php echo date('j F Y h:i:s'); ?>" class="published hasTooltip" title="">  
             <i class="icon-calendar"></i>  
             <time datetime="<?php echo date('j F Y h:i:s'); ?>" itemprop="datePublished">  
               <?php echo date('j F Y'); ?> </time>  
           </dd>  
      </dl>  
      <?php
    }
   ?>
   
 </aside> 
 <?php 
$app = JFactory::getApplication();
$menu = $app->getMenu();
if ($menu->getActive() == $menu->getDefault() and intval($_GET['sr'])==0) {
 ?> 
     <div class="step-process">
          <span class="ico-step"></span>
          <ul>
              <li><a href="#"></a></li>
              <li><a href="#"></a></li>
              <li><a href="#"></a></li>
              <li><a href="#"></a></li>
              <li><a href="#"></a></li>
              <li><a href="#"></a></li>
              <li><a href="#"></a></li>
          </ul>
      </div>
  <?php 

   }
  ?>
 <!-- //Aside -->       
 <section class="article-content clearfix" itemprop="articleBody">  
        <?php if($isprofile){
        if(intval($_REQUEST['sr'])>0) {
        ?>
          <div class="row">
            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
              <?php if ($this->getBuffer('message')) : ?>
                <div class="error">
                  <jdoc:include type="message" />
                </div>
              <?php endif; ?>
                <div id="maincontent">
                  <?php /* include_once('home_myreports.php');*/ echo $content; ?>
                </div>  
            </div>
          </div>
        <?php 
        } else {
        ?>
        <?php if ($this->getBuffer('message')) : ?>
                <div class="row-fluid">  
                  <div class="error">
                    <jdoc:include type="message" />
                  </div>
                </div>
        <?php endif; ?>
          <?php include_once('home_create_report.php'); //echo $content; ?>
          <div class="row">
            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
              <div class="row">
                <div class="col-xs-12 col-sm-12 col-md-4 col-lg-5 pull-right">
                  <div id="videoins"></div>
                  <div id="helpins"></div>
                </div>
                <div class="col-xs-12 col-sm-12 col-md-8 col-lg-7">
                
                  <div id="maincontent">
                    <?php echo $content; ?>
                  </div>  
                </div>
                
              </div>  
            </div>
          </div>
          <div class="clr"></div>
        <?php }
        } elseif($hasl&&$hasr){ ?>
          <div class="row">
            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
              <div class="row">
                <div class="col-xs-12 col-sm-12 col-md-4 col-lg-4">
                  <jdoc:include type="modules" name="left" style="xhtml"/>
                </div>
                
                <div class="col-xs-12 col-sm-12 col-md-4 col-lg-4">
                  <?php if ($this->getBuffer('message')) : ?>
                    <div class="error">
                      <jdoc:include type="message" />
                    </div>
                  <?php endif; ?>
                  
                  <div id="maincontent">
                    <?php echo $content; ?>
                  </div>  
                </div>
                
                <div class="col-xs-12 col-sm-12 col-md-4 col-lg-4">
                  <jdoc:include type="modules" name="right" style="xhtml"/>
                </div>
              </div>  
            </div>
          </div>
        
        <?php } elseif(!$hasl&&$hasr){?>
          <div class="row">
            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
              <div class="row">
                <div class="col-xs-12 col-sm-12 col-md-8 col-lg-8">
                <?php if ($this->getBuffer('message')) : ?>
                  <div class="error">
                    <jdoc:include type="message" />
                  </div>
                <?php endif; ?>
                  <div id="maincontent">
                    <?php echo $content; ?>
                  </div>  
                </div>
                
                <div class="col-xs-12 col-sm-12 col-md-4 col-lg-4">
                  <jdoc:include type="modules" name="right" style="xhtml"/>
                </div>
              </div>  
            </div>
          </div>
          <div class="clr"></div>
        <?php } elseif($hasl&&!$hasr){?>
          <div class="row">
            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
              <div class="row">
                <div class="col-xs-12 col-sm-12 col-md-4 col-lg-4">
                  <jdoc:include type="modules" name="left" style="xhtml"/>
                </div>
                <div class="col-xs-12 col-sm-12 col-md-8 col-lg-8">
                  <div id="contentwithleft">
                  <?php if ($this->getBuffer('message')) : ?>
                    <div class="error">
                      <jdoc:include type="message" />
                    </div>
                  <?php endif; ?>
                    <div id="maincontent">
                      <?php echo $content; ?>
                    </div>  
                  </div>  
                </div>
              </div>  
            </div>
          </div>  
        <?php } else { ?>
          <div class="row">
            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
              <?php if ($this->getBuffer('message')) : ?>
                <div class="error">
                  <jdoc:include type="message" />
                </div>
              <?php endif; ?>
                <div id="maincontent">
                  <?php 
                    /* LAYOUT OVERRIDES FOR ARTICLE  */
                     
                     $doc = JFactory::getDocument(); 
                    $page_title = $doc->getTitle();
                    $pagename = strtolower(str_replace(" ","_",$page_title));
                    $override_path = (JPATH_BASE.DIRECTORY_SEPARATOR.'templates'.DIRECTORY_SEPARATOR.$doc->template.DIRECTORY_SEPARATOR.'html'.DIRECTORY_SEPARATOR.'com_content'.DIRECTORY_SEPARATOR.'article'.DIRECTORY_SEPARATOR.$pagename.'.php');
     
                    if(file_exists($override_path))
                    {
                      include_once($override_path);
                    }else{
                       echo $content;
                    }
           
                  ?>
                </div>  
            </div>
          </div>
        <?php } ?>  
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
 <div class="t3-spotlight t3-footnav row">  
   <div class=" col-lg-6 col-md-6 col-sm-6 col-xs-12">  
     <div class="t3-module module footer-menu " id="Mod103">  
       <div class="module-inner">  
         <div class="module-ct">  
           <ul class="nav nav-pills nav-stacked ">  
             <li class="item-140 divider"><span class="separator">Copyright 2015</span>  
             </li>  
             <li class="item-141"><a href="http://dev.dusted.com.au/propertease0724/">About PropertEASE</a>  
             </li>  
             <li class="item-142"><a href="http://dev.dusted.com.au/propertease0724/conditions-of-use.html">Conditions of Use</a>  
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
           <jdoc:include type="modules" name="innertop" style="xhtml"/>  
         </div>  
       </div>  
     </div>  
   </div>  
 </div>  
 <!-- SPOTLIGHT -->          </div>  
 <!-- //FOOT NAVIGATION -->  
 </footer>  
 </div>  
 </body>
 </html>  

<?php } ?>

