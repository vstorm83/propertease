<?php
/**
 * @package     Joomla.Site
 * @subpackage  com_content
 *
 * @copyright   Copyright (C) 2005 - 2014 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

JHtml::addIncludePath(JPATH_COMPONENT . '/helpers');
?>
<div class="block-price">
<div class="block">
<h3>Single Report</h3>
Just <i style="color: #ff5b95;">1</i> Report<br /> Single User<br /> <span class="through-line">Help Desk Support</span><br />
<p class="pay-month"><span class="light">$</span><span class="blk">59</span> <i>/ month</i></p>
<p class="blk arrow-down">We do it for you.</p>
<p class="plus">Add Concierge <span class="blk">(+$40)</span></p>
</div>
<div class="block standard">
<h3>Standard</h3>
<span class="blk">Unlimited</span> Reports<br /> Single User<br /> Help Desk Support<br />
<p class="pay-month"><span class="light">$</span><span class="blk">179</span> <i>/ month</i></p>
<p class="plus">Up Front Cost <span class="blk">(Save $100)</span></p>
<p class="plus none-border">Annual Prepaid <span class="blk">(Save $358)</span></p>
</div>
<div class="block corporate">
<h3>Corporate</h3>
<span class="blk">Unlimited</span> Reports<br /> Up to <span class="blk">5 Users</span><br /> Help Desk Support<br />
<p class="pay-month"><span class="light">$</span><span class="blk">379</span> <i>/ month</i></p>
<p class="plus">Up Front Cost <span class="blk">(Save $100)</span></p>
<p class="plus none-border">Annual Prepaid <span class="blk">(Save $758)</span></p>
</div>
<a class="sbm-btn" href="#"><span class="blk">Click Here</span> to <span class="blk">Get Started</span></a></div>

<!-- Modal -->
<div class="modal fade" id="registration-form" tabindex="-1" role="dialog" aria-labelledby="registration-form">
  <div class="modal-dialog" role="document">
    <div class="modal-content"></div>
  </div>
</div>
<div class="modal fade" id="login-form-plan" tabindex="-1" role="dialog" aria-labelledby="login-form-plan">
  <div class="modal-dialog" role="document">
    <div class="modal-content"><!-- \html\mod_bt_login --></div>
  </div>
</div>

<div class="popup"><button class="btn btn-primary" type="button" data-toggle="modal" data-target="#login-popup"> Login </button>
<div id="login-popup" class="modal fade" tabindex="-1">
<div class="modal-dialog">
<div class="modal-content">
<div class="modal-header">
<div class="normal-logo"> </div>
<button class="btn btn-register">Don`t have an <b>account?</b></button></div>
<div class="modal-body">
<h3><span class="green blk">Log in</span> to your account</h3>
<form>
<div class="form-group"><input class="form-control" type="text" placeholder=" Enter your Username " /></div>
<div class="form-group"><input class="form-control" type="password" placeholder="Enter your password" /></div>
<div class="form-group">
<div class="radio"><label> <input type="radio" /> Remember me </label><a href="#">Forgot?</a></div>
</div>
<div class="form-group">
<p>By logging in, you agree to our <a href="#">Privacy Policy</a> and <a href="#">Terms of Use.</a></p>
</div>
<div class="form-group last-group"><button class="btn btn-sbm" type="submit">LOGIN</button></div>
</form></div>
</div>
</div>
</div>
</div>
<div class="popup step"><button class="btn btn-primary" type="button" data-toggle="modal" data-target="#plan-step-1"> Step 1</button>
	
	<div id="plan-step-1" class="modal fade" tabindex="-1">
	<div class="modal-dialog">
	<div class="modal-content">
	<div class="modal-header"><a class="back" href="#">back</a>
	<ul class="process">
	<li class="visited"><a href="#">1</a></li>
	<li class="visited"><a href="#">2</a></li>
	<li><a href="#">3</a></li>
	<li><a href="#">4</a></li>
	</ul>
	</div>
	<div class="modal-body">
	<h3>Create a <span class="green blk">free</span> a account</h3>
	<form>
	<div class="form-group"><input class="form-control" type="text" placeholder=" Enter your Username " /></div>
	<div class="form-group"><input class="form-control" type="email" placeholder="Enter your Email" /></div>
	<div class="form-group"><input class="form-control" type="password" placeholder="Enter your password" /></div>
	<div class="form-group"><input class="form-control" type="password" placeholder="Re-Type your Password" /></div>
	<div class="form-group last-group"><button class="btn btn-sbm" type="submit">NEXT STEP</button></div>
	</form></div>
	</div>
	</div>
	</div>

</div>
<div class="popup step"><button class="btn btn-primary" type="button" data-toggle="modal" data-target="#plan-step-2"> Step 2</button>
<div id="plan-step-2" class="modal fade" tabindex="-1">
<div class="modal-dialog">
<div class="modal-content">
<div class="modal-header"><a class="back" href="#">back</a>
<ul class="process">
<li class="visited"><a href="#">1</a></li>
<li class="visited"><a href="#">2</a></li>
<li><a href="#">3</a></li>
<li><a href="#">4</a></li>
</ul>
</div>
<div class="modal-body">
<h3><span class="green">Fill in</span> your details</h3>
<form>
<div class="form-group"><input class="form-control org" type="text" placeholder="Organisation" /></div>
<div class="form-group"><input class="form-control address" type="text" placeholder="What's your Address" /></div>
<div class="form-group"><input class="form-control city" type="text" placeholder="Enter your City" /></div>
<div class="form-group"><input class="form-control post-code" type="text" placeholder="Enter your Postcode" /></div>
<div class="form-group last-group"><button class="btn btn-sbm" type="submit">NEXT STEP</button></div>
</form></div>
</div>
</div>
</div>
</div>
<div class="popup step"><button class="btn btn-primary" type="button" data-toggle="modal" data-target="#plan-step-3"> Step 3</button>
<div id="plan-step-3" class="modal fade" tabindex="-1">
<div class="modal-dialog">
<div class="modal-content">
<div class="modal-header"><a class="back" href="#">back</a>
<ul class="process">
<li class="visited"><a href="#">1</a></li>
<li class="visited"><a href="#">2</a></li>
<li><a href="#">3</a></li>
<li><a href="#">4</a></li>
</ul>
</div>
<div class="modal-body">
<h3><span class="green">Fill in</span> your details</h3>
<form>
<div class="form-group">
<div class="btn-group"><button class="btn btn-default dropdown-toggle" type="button" data-toggle="dropdown"> Organisation</button>
<ul class="dropdown-menu">
<li><a href="#">Dropdown link</a></li>
<li><a href="#">Dropdown link</a></li>
</ul>
</div>
</div>
<div class="form-group"><input class="form-control address" type="text" placeholder="What's your Address" /></div>
<div class="form-group"><input class="form-control city" type="text" placeholder="Enter your City" /></div>
<div class="form-group"><input class="form-control post-code" type="text" placeholder="Enter your Postcode" /></div>
<div class="form-group last-group"><button class="btn btn-sbm" type="submit">NEXT STEP</button></div>
</form></div>
</div>
</div>
</div>
</div>
<div class="popup step"><button class="btn btn-primary" type="button" data-toggle="modal" data-target="#plan-step-4"> Step 4</button>
<div id="plan-step-4" class="modal fade" tabindex="-1">
<div class="modal-dialog">
<div class="modal-content">
<div class="modal-header"><a class="back" href="#">back</a>
<ul class="process">
<li class="visited"><a href="#">1</a></li>
<li class="visited"><a href="#">2</a></li>
<li><a href="#">3</a></li>
<li><a href="#">4</a></li>
</ul>
</div>
<div class="modal-body">
<h3><span class="green blk">Review</span> your Order</h3>
<p><span class="blk">Standard</span> Plan</p>
<p><span class="light">$</span>79</p>
<p class="small">One Month</p>
<p class="award">Unlimited Searches</p>
<a class="pay" href="#">Pay via <span class="ico-paypal">paypal</span></a></div>
</div>
</div>
</div>
</div>