/****
 * Payment method class
 * @param id
 * @param name
 * @param title
 * @param creditCard
 * @param cardType
 * @param cardCvv
 * @param cardHolderName
 * @return
 */
function PaymentMethod(name, creditCard, cardType, cardCvv, cardHolderName) {
    this.name = name ;
    this.creditCard = creditCard ;
    this.cardType = cardType ;
    this.cardCvv = cardCvv ;
    this.cardHolderName = cardHolderName ;
}
/***
 * Get name of the payment method
 * @return string
 */
PaymentMethod.prototype.getName = function() {
    return this.name ;
}
/***
 * This is creditcard payment method or not
 * @return int
 */
PaymentMethod.prototype.getCreditCard = function() {
    return this.creditCard ;
}
/****
 * Show creditcard type or not
 * @return string
 */
PaymentMethod.prototype.getCardType = function() {
    return this.cardType ;
}
/***
 * Check to see whether card cvv code is required
 * @return string
 */
PaymentMethod.prototype.getCardCvv = function() {
    return this.cardCvv ;
}
/***
 * Check to see whether this payment method require entering card holder name
 * @return
 */
PaymentMethod.prototype.getCardHolderName = function() {
    return this.cardHolderName ;
}
/***
 * Payment method class, hold all the payment methods
 */
function PaymentMethods() {
    this.length = 0 ;
    this.methods = new Array();
}
/***
 * Add a payment method to array
 * @param paymentMethod
 * @return
 */
PaymentMethods.prototype.Add = function(paymentMethod) {
    this.methods[this.length] = paymentMethod ;
    this.length = this.length + 1 ;
}
/***
 * Find a payment method based on it's name
 * @param name
 * @return {@link PaymentMethod}
 */
PaymentMethods.prototype.Find = function(name) {
    for (var i = 0 ; i < this.length ; i++) {
        if (this.methods[i].name == name) {
            return this.methods[i] ;
        }
    }
    return null ;
}
OSM.jQuery(function($){
    /**
     * JD validate form
     */
    OSMVALIDATEFORM = (function(formId){
        $(formId).validationEngine('attach', {
            onValidationComplete: function(form, status){
                if (status == true) {
                    form.on('submit', function(e) {
                        e.preventDefault();
                    });
                    return true;
                }
                return false;
            }
        });
    })
    /***
     * Process event when someone change a payment method
     */
    changePaymentMethod = (function(){
        updatePaymentMethod();
        if (document.os_form.show_payment_fee.value == 1)
        {
            // Re-calculate subscription fee in case there is payment fee associated with payment method
            calculateSubscriptionFee();
        }
    });


    /***
     * Process event when someone change a payment method (no recalculate fee)
     */
    updatePaymentMethod = (function(){
        var form = document.os_form;
        if($('input:radio[name^=payment_method]').length)
        {
            var paymentMethod = $('input:radio[name^=payment_method]:checked').val();
        }
        else
        {
            var paymentMethod = $('input[name^=payment_method]').val();
        }
        method = methods.Find(paymentMethod);
        if (!method)
        {
            return;
        }
        if (method.getCreditCard())
        {
            $('#tr_card_number').show();
            $('#tr_exp_date').show();
            $('#tr_cvv_code').show();
            if (method.getCardType())
            {
                $('#tr_card_type').show();
            }
            else
            {
                $('#tr_card_type').hide();
            }
            if (method.getCardHolderName())
            {
                $('#tr_card_holder_name').show();
            }
            else
            {
                $('#tr_card_holder_name').show();
            }
        }
        else
        {
            $('#tr_card_number').hide();
            $('#tr_exp_date').hide();
            $('#tr_cvv_code').hide();
            $('#tr_card_type').hide();
            $('#tr_card_holder_name').hide();
        }
        if (paymentMethod == 'os_ideal')
        {
            $('#tr_bank_list').show();
        }
        else
        {
            $('#tr_bank_list').hide();
        }

        if (paymentMethod == 'os_echeck')
        {
            $('#tr_bank_rounting_number').show();
            $('#tr_bank_account_number').show();
            $('#tr_bank_account_type').show();
            $('#tr_bank_name').show();
            $('#tr_bank_account_holder').show();
        }
        else
        {
            if ($('#tr_bank_rounting_number').length)
            {
                $('#tr_bank_rounting_number').hide();
                $('#tr_bank_account_number').hide();
                $('#tr_bank_account_type').hide();
                $('#tr_bank_name').hide();
                $('#tr_bank_account_holder').hide();
            }
        }
    });
    /**
     * calculate subcription free
     */
    calculateSubscriptionFee = (function(){
        $('#btn-submit').attr('disabled', 'disabled');
        $('#ajax-loading-animation').show();
        if($('input:radio[name^=payment_method]').length)
        {
            var paymentMethod = $('input:radio[name^=payment_method]:checked').val();
        }
        else
        {
            var paymentMethod = $('input[name^=payment_method]').val();
        }
        $.ajax({
            type: 'POST',
            url: siteUrl + 'index.php?option=com_osmembership&task=calculate_subscription_fee&payment_method=' + paymentMethod,
            data: $('#os_form input[name=\'plan_id\'], #os_form input[name=\'coupon_code\'], #os_form select[name=\'country\'], #os_form input.taxable[type=\'text\'], #os_form input[name=\'coupon_code\'], #os_form input[name=\'act\'], #os_form input[name=\'renew_option_id\'], #os_form input[name=\'upgrade_option_id\'], #os_form .payment-calculation input[type=\'text\'], #os_form .payment-calculation input[type=\'checkbox\']:checked, #os_form .payment-calculation input[type=\'radio\']:checked, #os_form .payment-calculation select'),
            dataType: 'json',
            success: function(msg, textStatus, xhr) {
                $('#btn-submit').removeAttr('disabled');
                $('#ajax-loading-animation').hide();
                if ($('#amount'))
                {
                    $('#amount').val(msg.amount);
                }
                if ($('#discount_amount'))
                {
                    $('#discount_amount').val(msg.discount_amount);
                }
                if ($('#tax_amount'))
                {
                    $('#tax_amount').val(msg.tax_amount);
                }
                if ($('#payment_processing_fee'))
                {
                    $('#payment_processing_fee').val(msg.payment_processing_fee);
                }
                if ($('#gross_amount'))
                {
                    $('#gross_amount').val(msg.gross_amount);
                }

                if ($('#trial_amount'))
                {
                    $('#trial_amount').val(msg.trial_amount);
                }
                if ($('#trial_discount_amount'))
                {
                    $('#trial_discount_amount').val(msg.trial_discount_amount);
                }
                if ($('#trial_tax_amount'))
                {
                    $('#trial_tax_amount').val(msg.trial_tax_amount);
                }
                if ($('#trial_payment_processing_fee'))
                {
                    $('#trial_payment_processing_fee').val(msg.trial_payment_processing_fee);
                }
                if ($('#trial_gross_amount'))
                {
                    $('#trial_gross_amount').val(msg.trial_gross_amount);
                }

                if ($('#regular_amount'))
                {
                    $('#regular_amount').val(msg.regular_amount);
                }
                if ($('#regular_discount_amount'))
                {
                    $('#regular_discount_amount').val(msg.regular_discount_amount);
                }
                if ($('#regular_tax_amount'))
                {
                    $('#regular_tax_amount').val(msg.regular_tax_amount);
                }
                if ($('#regular_payment_processing_fee'))
                {
                    $('#regular_payment_processing_fee').val(msg.regular_payment_processing_fee);
                }
                if ($('#regular_gross_amount'))
                {
                    $('#regular_gross_amount').val(msg.regular_gross_amount);
                }
                if ($('#vat_country_code'))
                {
                    $('#vat_country_code').text(msg.country_code);
                }

                // Show or Hide the VAT Number field depend on country
                var vatNumberField = $('input[name^=vat_number_field]').val();
                if (vatNumberField)
                {
                    if (msg.show_vat_number_field == 1)
                    {
                        $('#field_' + vatNumberField).show();
                    }
                    else
                    {
                        $('#field_' + vatNumberField).hide();
                    }
                }

                if (($('#gross_amount').val() != undefined && msg.gross_amount == 0) || ($('#regular_gross_amount').val() != undefined && msg.regular_gross_amount == 0))
                {
                    $('.payment_information').css('display', 'none');
                }
                else
                {
                    $('.payment_information').css('display', '');
                    updatePaymentMethod();
                }
                if (msg.coupon_valid == 1)
                {
                    $('#coupon_validate_msg').hide();
                }
                else
                {
                    $('#coupon_validate_msg').show();
                }

                if (msg.vatnumber_valid == 1)
                {
                    $('#vatnumber_validate_msg').hide();
                }
                else
                {
                    $('#vatnumber_validate_msg').show();
                }
            },
            error: function(jqXHR, textStatus, errorThrown) {
                alert(textStatus);
            }
        });
    });

    /**
     * function build state field
     */
    buildStateField = (function(stateFieldId, countryFieldId, defaultState){
        if($('#' + stateFieldId).length && $('#' + stateFieldId).is('select'))
        {
            //set state
            if ($('#' + countryFieldId).length)
            {
                var countryName = $('#' + countryFieldId).val();
            }
            else
            {
                var countryName = '';
            }
            $.ajax({
                type: 'POST',
                url: siteUrl + 'index.php?option=com_osmembership&task=get_states&country_name='+ countryName+'&field_name='+stateFieldId + '&state_name=' + defaultState,
                success: function(data) {
                    $('#field_' + stateFieldId + ' .controls').html(data);
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    alert(textStatus);
                }
            });
            //Bind onchange event to the country
            if ($('#' + countryFieldId).length)
            {
                $('#' + countryFieldId).change(function(){
                    $.ajax({
                        type: 'POST',
                        url: siteUrl + 'index.php?option=com_osmembership&task=get_states&country_name='+ $(this).val()+'&field_name=' + stateFieldId + '&state_name=' + defaultState,
                        success: function(data) {
                            $('#field_' + stateFieldId + ' .controls').html(data);
                            //$('.wait').remove();
                        },
                        error: function(jqXHR, textStatus, errorThrown) {
                            alert(textStatus);
                        }
                    });
                    var countryBaseTax = paymentMethod = $('input[name^=country_base_tax]').val();
                    if (countryBaseTax)
                    {
                        calculateSubscriptionFee();
                    }
                });
            }
        }//end check exits state
        else
        {
            if ($('#' + countryFieldId).length)
            {
                $('#' + countryFieldId).change(function(){
                    var countryBaseTax = paymentMethod = $('input[name^=country_base_tax]').val();
                    if (countryBaseTax)
                    {
                        calculateSubscriptionFee();
                    }
                });
            }
        }
    });
})
