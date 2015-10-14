<?php
defined('_JEXEC') or die;

$config = $this->config;
$decimals      = isset($config->decimals) ? $config->decimals : 2;
$dec_point     = isset($config->dec_point) ? $config->dec_point : '.';
$thousands_sep = isset($config->thousands_sep) ? $config->thousands_sep : ',';

foreach ($this->items as $item) {
	switch($item->id) {
		case 3:
			$single = $item;
			break;
		case 6:
			$concierge = $item;
			break;
		case 1:
			$basic = $item;
			break;
		case 2:
			$corporate = $item;
			break;
		case 4:
			$basicAnual = $item;
			break;
		case 5:
			$corporateAnual = $item;	
	}
}

if (isset($single)) {
?>
<div class="block-price">
	<div class="block single" data-id="<?php echo $single->id?>">
		<h3><?php echo $single->title?></h3>
		<?php echo $single->description?>
		<p class="pay-month">
			<span class="light"><?php echo $config->currency_symbol?></span>
			<span class="blk">
			<?php echo number_format($single->price, $decimals, $dec_point, $thousands_sep)?>
			</span>
		</p>
<?php 
	if (isset($concierge)) {
		$delta = $concierge->price - $single->price;
		if ($delta < 0) {
			$prefix = "Save ";
		} else {
			$prefix = "+";
		}
?>
		<p class="blk arrow-down">We do it for you.</p>
		<p class="plus" data-price="<?php echo number_format($concierge->price,$decimals, $dec_point, $thousands_sep);?>" data-id="<?php echo $concierge->id?>">
			Add Concierge <span class="blk">(<?php echo $prefix.$config->currency_symbol.abs($delta)?>)</span>
		</p>
<?php 
	}
?>
	</div>
<?php 
}
if (isset($basic)) {
?>
	<div class="block standard" data-id="<?php echo $basic->id?>">
		<h3><?php echo $basic->title?></h3>
		<?php echo $basic->description?>
		<p class="pay-month">
			<span class="light"><?php echo $config->currency_symbol?></span>
			<span class="blk"><?php echo number_format($basic->price, $decimals, $dec_point, $thousands_sep)?></span> 
			<i>/ month</i>
		</p>
		<?php 
		if ($basic->trial_amount > 0) {
			$delta = $basic->price - $basic->trial_amount;
		?>
		<p class="plus upFront" data-price="<?php echo number_format($basic->trial_amount,$decimals, $dec_point, $thousands_sep);?>">
			Up Front Cost <span class="blk">(Save <?php echo $config->currency_symbol.$delta?>)</span>
		</p>
		<?php 
		}
		if (isset($basicAnual)) {
			$delta = $basic->price * 12 - $basicAnual->price;
		?>
		<p class="plus none-border yearly" data-price="<?php echo number_format($basicAnual->price,$decimals, $dec_point, $thousands_sep);?>"
			data-id="<?php echo $basicAnual->id?>">
			Annual Prepaid <span class="blk">(Save <?php echo $config->currency_symbol.$delta?>)</span>
		</p>
		<?php
		}
		?>
	</div>
<?php 
}
if (isset($corporate)) {
?>	
	<div class="block corporate" data-id="<?php echo $corporate->id?>">
		<h3><?php echo $corporate->title?></h3>
		<?php echo $corporate->description?>
		<p class="pay-month">
			<span class="light"><?php echo $config->currency_symbol?></span>
			<span class="blk"><?php echo number_format($corporate->price, $decimals, $dec_point, $thousands_sep)?></span> 
			<i>/ month</i>
		</p>
		<?php 
		if ($corporate->trial_amount > 0) {
			$delta = $corporate->price - $corporate->trial_amount;
		?>
		<p class="plus upFront" data-price="<?php echo number_format($corporate->trial_amount, $decimals, $dec_point, $thousands_sep);?>">
			Up Front Cost <span class="blk">(Save <?php echo $config->currency_symbol.$delta?>)</span>
		</p>
		<?php 
		}
		if (isset($corporateAnual)) {
			$delta = $corporate->price * 12 - $corporateAnual->price;
		?>
		<p class="plus none-border yearly" data-price="<?php echo number_format($corporateAnual->price,$decimals, $dec_point, $thousands_sep);?>"
			data-id="<?php echo $corporateAnual->id?>">
			Annual Prepaid <span class="blk">(Save <?php echo $config->currency_symbol.$delta?>)</span>
		</p>
		<?php 
		}
		?>
	</div>
<?php 
}
?>
	<form method="post" class="subscribeForm" action="<?php echo JUri::current() ?>" autocomplete="off">
		<input type="hidden" name="Itemid" value="<?php echo $this->Itemid; ?>" />
		<input type="hidden" class="planid" name="abcd" value="<?php echo JRequest::getInt('abcd', 0);?>" />
	</form>
	<a class="sbm-btn" href="#"><span class="blk">Click Here</span> to <span
		class="blk">Get Started</span></a>
</div>
<?php JRequest::setVar('id', JRequest::getInt('abcd')); ?>
<div class="popup step">
	<?php
		if (JRequest::getVar('id')) {
			require_once __DIR__.'/../../../../../components/com_osmembership/views/register/view.html.php';
			$test = new OSMembershipViewRegister();
			$test->__construct(['base_path'=>$this->base_path, 'layout'=>'popup']);
			$test->display();			
		}
	?>
</div>