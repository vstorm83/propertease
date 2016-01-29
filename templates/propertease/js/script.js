 //<![CDATA[  
if (navigator.userAgent.match(/IEMobile\/10\.0/)) {  
    var msViewportStyle = document.createElement("style");  
    msViewportStyle.appendChild(  
         document.createTextNode("@-ms-viewport{width:auto!important}")  
    );  
    document.getElementsByTagName("head")[0].appendChild(msViewportStyle);  
}  
//]]> 
jQuery(window).on('load', function() {  
                     new JCaption('img.caption');  
                });  
 jQuery(document).ready(function(){  
      jQuery('.hasTooltip').tooltip({"html": true,"container": "body"});  
 }); 
 
jQuery(document).ready(function($){
	Joomla.JText.load({"REQUIRED_FILL_ALL":"Please enter data in all fields.","E_LOGIN_AUTHENTICATE":"Username and password do not match or you do not have an account yet.","REQUIRED_NAME":"Please enter your name!","REQUIRED_USERNAME":"Please enter your username!","REQUIRED_PASSWORD":"Please enter your password!","REQUIRED_VERIFY_PASSWORD":"Please re-enter your password!","PASSWORD_NOT_MATCH":"Password does not match the verify password!","REQUIRED_EMAIL":"Please enter your email!","EMAIL_INVALID":"Please enter a valid email!","REQUIRED_VERIFY_EMAIL":"Please re-enter your email!","EMAIL_NOT_MATCH":"Email does not match the verify email!","CAPTCHA_REQUIRED":"Please enter captcha key"});  

	jQuery(document).off('click.login').on('click.login', '#btl-panel-login-y', function() {
    jQuery('#login-popup').modal('show');
  });
	
	//move expired panel hide ip panel
	$('.reportCreator').before($('.expiredPanel'));	  
	window.setTimeout(function() {
	}, 1000);	
	window.setTimeout(function() {
	  $('.ipPanel').fadeOut(1000);
	}, 5000);	
	
	//terms and conditions
	$(document).off('click.verticalTab').on('click.verticalTab', '.vertical-tab .tab-item a', function(e) {
	  var $item = $(e.target);
	  if (!$item.hasClass('active')) {
	    $item.closest('.tab-item').find('.active').removeClass('active');
	    $item.addClass('active');
	    var $tab = $item.closest('.vertical-tab');
	    $tab.find('.current').removeClass('current').hide();
	    $tab.find($item.attr('href')).addClass('current').show();
	  }
	  return false;
	});
	
	$(document).off('click.ticket').on('click.ticket', '.btn-live-chat', function() {
	  var $loginBtn = $('#btl-panel-login-y');
	  if ($loginBtn.length) {
	    $loginBtn.click();
	    window.setTimeout(function() {
	      $('#btl-content-login-y input[name=return]').val('./?option=com_fss&view=ticket');
	    }, 1000);
	  } else {
	    window.location.href = 'submit-a-ticket.html?view=ticket&layout=support&tickets=1';
	  }
	});
	
	//profile page
	$('.avatar').click(function() {
	  $('#avatarModal').modal('show');
	});
	
	/* PRICE PAGE related code */
	$('.selectpicker').selectpicker();
	$('#report-name').focus(function(){
		var e = $(this);
		if(e.val()=="Name of the Report"){e.val(''); }
	}).focusout(function(){
		var e = $(this);
		if(e.val()==""){e.val('Name of the Report');/* $('.step-process li').removeClass('active');*/ }
		else{
			$('.step-process ul li:nth-child(1)').addClass('active');
			if ($.trim(e.val())) {
			  $('#refw input').val($.trim(e.val()));			  
			}
		}
	});

	$('.scheme-n').change(function(){
		$('.step-process ul li:nth-child(4)').addClass('active');
		$('#collapsethree').addClass('filled-step');
	});
	$('#getpdf').click(function(){
		$('#downloadpdf').trigger('click');
	});
	$('#maincontent').on('click', '.downloadreport', function(e) {
	  var $download = $(e.target);
	  var $form = $('#downloadpdf').closest('form');
	  $form.find('[name="sr"]').val($download.attr('id'));
	  $form.submit();
	});
	
	var changeBlockColor = function($block) {
		$('.block-price .block').addClass('greyed');
		$block.removeClass('greyed');
		$('.block-price  .sbm-btn').addClass('btn-green');
	}
	
	//FOR PLANS PAGE
	$('.block-price .block').click(function(){
		changeBlockColor($(this));
		$('.subscribeForm .planid').val($(this).data('id'));
	});
	
	$('.block-price .single .plus').click(function() {
	  var $block = $(this).closest('.block');
//	  $block.find('.active').removeClass('active');
//	  $(this).addClass('active');
		changeBlockColor($block);
		$block.find('.pay-month .blk').text($(this).data('price'));
		$('.subscribeForm .planid').val($(this).data('id'));
		return false;
	});
	
	$('.block-price .upFront').click(function() {
		if ($(this).closest('.standard').length) {
			$('.block-price .standard .pay-month .blk').text($(this).data('price'));
			$('.block-price .corporate .pay-month .blk').text($('.block-price .corporate .upFront').data('price'));
		} else {
			$('.block-price .standard .pay-month .blk').text($('.block-price .standard .upFront').data('price'));
			$('.block-price .corporate .pay-month .blk').text($(this).data('price'));
		}		
		$('.standard .pay-month i').text('*for the first month');
		$('.corporate .pay-month i').text('**for the first month');
	});
	
	$('.block-price .yearly').click(function() {
		$block = $(this).closest('.block');
		changeBlockColor($block);
		
		if ($(this).closest('.standard').length) {
			$('.block-price .standard .pay-month .blk').text($(this).data('price'));		
			$('.block-price .corporate .pay-month .blk').text($('.block-price .corporate .yearly').data('price'));			
		} else {
			$('.block-price .standard .pay-month .blk').text($('.block-price .standard .yearly').data('price'));
			$('.block-price .corporate .pay-month .blk').text($(this).data('price'));
		}
		$('.pay-month i').text('/ year');
		$('.subscribeForm .planid').val($(this).data('id'));
		return false;
	});
	
	//prepare step2, step3
	//wait for buding the 'state' input 
	window.setTimeout(function() {
		$('.temporary input, .temporary select').each(function(idx, input) {
			var $input = $(input);
			var label = $input.closest('.control-group').find('label').text().replace('*', '');
			var $div = $('<div class="form-group"></div>');
			$div.append($input);
			if (idx < 3) {
				$('.step2Details').append($div);
			} else {
				$('.step3Details').append($div);			
			}
			$input.addClass("form-control").attr('placeholder', label);
		});
		$('.temporary').remove();
	}, 2000);

	//start subcription flow
	$('.block-price  .sbm-btn').click(function(e) {
		if($('.block-price .greyed').length==0) {
			return false;
		}
		$('.subscribeForm').submit();		
		return false;
	});

	var hash = window.location.hash;
	var planid = $('.planid').val(); 	
	if (planid) {
		var $block = $('*[data-id="' + planid + '"]');
		if ($block.length) {
			$block.click();
			
			if ($('.register').val() != '0') {
			  $('.register').val(0);
			  $('#plan-step-1').modal('show');
			} else if ($('#login-popup').length) {
				$('#login-popup').modal('show');
			} else if ($('#plan-step-2').length) {
				$('#plan-step-2').modal('show');
			} else {
				alert('login first');
			}			
		}
	}
	
	//auto display registration dialog if coming from other pages  
  if (hash =="#register-modal" && $('.block-price .greyed').length==0 && window.location.href.match('plans.html')) {
    var $block = $('.block').first();
    if ($block.length) {
      $block.click();      
      $('.register').val(1);
      $('.subscribeForm').submit();        
    }
  }
	
	//show registration form
	$('.btn-register').click(function(event){
//	  if ($('#plan-step-1').length) {
//	    $('.modal').modal('hide');
//	    $('#plan-step-1').modal('show');
//	    return false;	    
//	  }
	  window.location = 'subscribe-mail.html';
	});	
	
	$('#plan-step-1 .btn').click(function() {
	  if (OSM.jQuery('#os_form').validationEngine('validate')) {
	    $('.modal').modal('hide');
	    $('#plan-step-2').modal('show');	    
	  }
		return false;
	});
	
	$('#plan-step-1 .back').click(function() {
		$('.modal').modal('hide');
		$('#login-popup').modal('show');
	});
	
	$('#plan-step-2 .btn').click(function() {
	  if (OSM.jQuery('#os_form').validationEngine('validate')) { 
	    $('.modal').modal('hide');
	    $('#plan-step-3').modal('show');	    
	  }
		return false;
	});
	
	$('#plan-step-2 .back').click(function() {
		$('.modal').modal('hide');
		$('#plan-step-1').modal('show');
	});
	
	$('#plan-step-3 .btn').click(function() {
	  if (OSM.jQuery('#os_form').validationEngine('validate')) {
	    $('.modal').modal('hide');
	    $('#plan-step-4').modal('show');	    
	  }
		return false;
	});
	
	$('#plan-step-3 .back').click(function() {
		$('.modal').modal('hide');
		$('#plan-step-2').modal('show');
	});
	
	$('#plan-step-4 .btn').click(function() {
		$('.step4 .pay').click(function() {
			
		});
	});
	
	$('#plan-step-4 .back').click(function() {
		$('.modal').modal('hide');
		$('#plan-step-3').modal('show');
	});
	
	$('.pay').click(function() {
		$(this).closest('form').submit();
	});

	/* end of sPRICE PAGE related code */
//END OF DOCUMENT READY	
});