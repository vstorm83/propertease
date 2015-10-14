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
	$tplparams	= $app->getTemplate(true)->params;
	$hasl=($this->countModules('left')?true:false);
	$hasr=($this->countModules('right')?true:false);
?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="<?php echo $this->language; ?>" lang="<?php echo $this->language; ?>" dir="<?php echo $this->direction; ?>">
<head>
  <jdoc:include type="head" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="<?php echo $this->baseurl ?>/templates/system/css/system.css" type="text/css" />
  <link rel="stylesheet" href="<?php echo $this->baseurl ?>/templates/<?php echo $this->template ?>/css/bootstrap.css" type="text/css" />
  <link rel="stylesheet" href="<?php echo $this->baseurl ?>/templates/<?php echo $this->template ?>/css/style.css" type="text/css" />
</head>

<body class="page_bg">
	<div class="noo-top-social"><jdoc:include type="modules" name="top-social" style="raw" /></div>
	<div class="row header">
		<div class="container">
			<div class="col-xs-12 col-sm-12 col-md-4 col-lg-4">
				<div id="logo">
					<jdoc:include type="modules" name="logo" style="xhtml"/>
				</div>
			</div>

			<div class="col-xs-12 col-sm-12 col-md-offset-5 col-md-3 col-lg-offset-5 col-lg-3">
				<div id="headercontact">
					<jdoc:include type="modules" name="headercontact" style="xhtml"/>
				</div>
			</div>
		</div>
	</div>	
	
	<div class="slider">
		<jdoc:include type="modules" name="slider" style="xhtml"/>
		<div class="slogin"><div class="container"><div class="slogini"><jdoc:include type="modules" name="login" style="xhtml"/></div></div></div>
	</div>
	<div class="tickerc">
		<jdoc:include type="modules" name="ticker" style="xhtml"/>
	</div>
	
	<div class="row topmenu">
		<div class="container">
			<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
				<div id="topmenu">
					<jdoc:include type="modules" name="<?php if($isuser){ ?>user<?php } else { ?>top<?php } ?>menu" style="xhtml"/>
				</div>
			</div>
		</div>
	</div>
		
	<div class="container">
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
							<?php echo $content; ?>
						</div>	
				</div>
			</div>
		<?php 
		} else {
		?>
			<div class="row">
				<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
					<div class="row">
						<div class="col-xs-12 col-sm-12 col-md-4 col-lg-5 fr">
							<div id="videoins"></div>
							<div id="helpins"></div>
						</div>
						<div class="col-xs-12 col-sm-12 col-md-8 col-lg-7">
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
							<?php echo $content; ?>
						</div>	
				</div>
			</div>
		<?php } ?>	
	</div>
	
	<div class="row contentbottom">
		<div class="container">
			<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
				<div id="contentbottom">
					<jdoc:include type="modules" name="contentbottom" style="xhtml"/>
				</div>
			</div>
		</div>	
	</div>	
	
	<div class="row footertop">
		<div class="container">
			<div class="col-xs-12 col-sm-12 col-md-3 col-lg-3">
				<div id="footermenu1">
					<jdoc:include type="modules" name="footermenu1" style="xhtml"/>
				</div>
			</div>
			<div class="col-xs-12 col-sm-12 col-md-3 col-lg-3">
				<div id="footermenu2">
					<jdoc:include type="modules" name="footermenu2" style="xhtml"/>
				</div>
			</div>
			<div class="col-xs-12 col-sm-12 col-md-3 col-lg-3">
				<div id="footermenu3">
					<jdoc:include type="modules" name="footermenu3" style="xhtml"/>
				</div>
			</div>
			<div class="col-xs-12 col-sm-12 col-md-3 col-lg-3">
				<div id="footermenu4">
					<jdoc:include type="modules" name="footermenu4" style="xhtml"/>
				</div>
			</div>
		</div>	
	</div>		
	
	<div class="row footer">
		<div class="container">
			<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
				<div id="footermenu">
					<jdoc:include type="modules" name="footermenu" style="xhtml"/>
				</div>
			</div>
		</div>
	</div>
<?php } ?>
</body>
</html>