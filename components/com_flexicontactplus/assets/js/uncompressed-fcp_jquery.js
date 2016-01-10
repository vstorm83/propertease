// ********************************************************************
// Product      : FlexiContactPlus
// Date         : 24 November 2014
// Copyright    : Les Arbres Design 2012-2014
// Contact      : http://extensions.lesarbresdesign.info
// Licence      : GNU General Public License
// *********************************************************************
//
// JQuery Javascript for FlexiContactPlus
//
function fcp_setup()
{
// if the browser supports the File and FormData API's, we can process file attachments
// if so, patch jQuery to support an Ajax progress callback function

    if ((window.File) && (window.FormData))
        window.fcp_file_attachments = true;         // we can do file attachments
    else
        window.fcp_file_attachments = false;        // we can't do file attachments

// if File and FormData are supported, add a progress callback to jQuery Ajax
// (but not for IE)

    if (window.fcp_file_attachments)
        {
        var msie = false;
        if (navigator.userAgent.indexOf('MSIE') !== -1 || navigator.appVersion.indexOf('Trident/') > 0)
            msie = true;
        if (('onprogress' in jQuery.ajaxSettings.xhr()) && (!msie))  // does the browser support onprogress?
            {
            var oldXHR = jQuery.ajaxSettings.xhr;
            jQuery.ajaxSettings.xhr = function()
                {
                var xhr = oldXHR();
                if (xhr instanceof window.XMLHttpRequest)
                    xhr.addEventListener('progress', this.progress, false);
                if (xhr.upload)
                    xhr.upload.addEventListener('progress', this.progress, false);
                return xhr;
                }
            }
        }

// if the browser does not support file uploads, and there are file upload fields, show the warning message

    if ((!window.fcp_file_attachments) && (typeof fcp_config !== 'undefined'))
        document.getElementById('fcp_smsg').innerHTML = fcp_config.noup;    // display a message by the send button
        
// add an "onchange" event handler to every input and textarea in the fcp form

    var elements = jQuery('input, select, textarea', '#fcp_form');
    for (var i = 0; i < elements.length; i++)
        {
        if (elements[i].type == 'radio')                                // skip the types we don't need
            continue;
        if (elements[i].type == 'submit')
            continue;
        if (elements[i].type == 'hidden')
            continue;
        if ((elements[i].type == 'file') && (!window.fcp_file_attachments))
            {
            elements[i].disabled = true;                                // disable file fields if we can't process them
            continue;
            }
        if (elements[i].className.indexOf('date') > -1)                 // hasClass() doesn't work in IE8
            {
            jQuery(elements[i].id).on('blur', fcp_onchange_handler);    // date picker fields don't fire onchange so we use onblur
            continue;
            }
        jQuery(elements[i]).on('change', fcp_onchange_handler);         // for all other fields we use onchange
        }

// add an "onclick" event handler to the Send button

    jQuery('#fcp_send_button').on('click', fcp_onsubmit_handler);

// initialise Bootstrap Tooltips the way we want them
// we'd like to use "placement":"auto right" but it currently doesn't work properly

    jQuery('.hasTooltip').tooltip({"html":true, "container":"body"});
}

//-------------------------------------------------------------------------------------
// The "onchange" event handler that gets called when the user changes an input field
// "this" is the html element the user changed
//
function fcp_onchange_handler(event)
{
    var request = new Object;
    var value = jQuery(this).val();                    // returns an empty string if no value (this.value returns 'undefined')
    request['task'] = "validate_field";
    request[this.name] = value;
    if (this.type == 'file')
        {
        if (!window.File)                              // if the File API is not available we shouldn't be here
            return;
        if (value != '')
            {
            var f = this.files;                        // an array of files, but we only allow one to be selected, so index [0]
            var fieldnum = this.name.substring(5, 8);  // get the 3 digit field number
            request['filesize'+fieldnum] = f[0].size;  // filesizeNNN where NNN is the field number
            }
        }
    else
        {
        var field_value = this.value;
        if (this.type == 'checkbox')
            {
            if (this.checked == true)
                field_value = 1;
            else
                field_value = 0;
            }
        request[this.name] = field_value;
        }
    fcp_send_request(request);
}

//-------------------------------------------------------------------------------------
// The "onclick" event handler that gets called when the user clicks the Send button
// Sends all the form data to the server including attachment filenames and sizes but not the actual files
//
function fcp_onsubmit_handler(e)
{
    e.preventDefault();                                                 // prevent the browser sending the form
    jQuery('#fcp_send_button').attr('disabled','disabled');
    jQuery('#fcp_spinner').empty().addClass('fcp_spinner');

    var request = '&task=send1';
    
// create post data entries for any file fields so that the server can validate them
// (the server will request the actual files only if all files and fields on the whole form are valid)

    var fileElements = jQuery('input[type=file]', '#fcp_form');
    for (var i = 0; i < fileElements.length; i++)
        {
        var fileName = jQuery(fileElements[i]).val();
        request += '&'+fileElements[i].name+"="+fileName;
        if (fileName != '')
            {
            var f = fileElements[i].files;
            var fieldnum = fileElements[i].name.substring(5,8);
            request += '&filesize'+fieldnum+'='+f[0].size;
            }
        }

    request += '&'+jQuery('#fcp_form').serialize();     // serialize() does not include file fields
    fcp_send_request(request);
}

//-------------------------------------------------------------------------------------
// Upload the entire form, complete with attachment files
//
function fcp_upload()
{
    if (!window.FormData)           // if FormData is not supported we should not have got here
        return;
    var form_data = new FormData(document.getElementById('fcp_form'));      // gets all the fields, including the files
    form_data.append("task", "send2");
    
// we need to add the extra post data fields  

    var fileElements = jQuery('input[type=file]', '#fcp_form');
    for (var i = 0; i < fileElements.length; i++)
        {
        var fileName = jQuery(fileElements[i]).val();
        form_data.append(fileElements[i].name, fileName);
        if (fileName != '')
            {
            var f = fileElements[i].files;
            var fieldnum = fileElements[i].name.substring(5, 8);
            form_data.append('filesize'+fieldnum, f[0].size);
            }
        }
        
// send a FormData object to the server

    var config_id = document.getElementById('config_id').value;
    var url = 'index.php?option=com_flexicontactplus&format=raw&tmpl=component&config_id='+config_id;

    jQuery.ajax({
        url: url,
        data: form_data,
        dataType: "json",
        contentType: false,             // otherwise jQuery will set the Content-Type incorrectly
        processData: false,             // prevent jQuery from transforming the data into a string.
        type: "POST",
        progress: function(e) {fcp_progress(e);},
        success: function(responseText, status, xhr) {fcp_handle_response(responseText);},
        error: function(xhr, status, error) {fcp_handle_response('Failed: '+status+' '+xhr.responseText);}
        });        
}

//-------------------------------------------------------------------------------------
// Send a normal string request to the server
//
function fcp_send_request(request_data)
{
    var config_id = document.getElementById('config_id').value;
    var url = 'index.php?option=com_flexicontactplus&format=raw&tmpl=component&config_id='+config_id;
    
    jQuery.ajax({
        url: url,
        data: request_data,
        dataType: "json",
        type: "POST",
        success: function(responseText, status, xhr) {fcp_handle_response(responseText);},
        error: function(xhr, status, error) {fcp_handle_response('Failed: '+status+' '+xhr.responseText);}
        });        
}

//-------------------------------------------------------------------------------------
// Handle a validation response from the server
// - this should be a JSON OBJECT containing a list of element_id's and messages
//   {"field001":"ERROR", "fcp_err001":"Invalid email address"}
//
function fcp_handle_response(response)
{
    jQuery('#fcp_spinner').removeClass('fcp_spinner');
    
    if (document.getElementById('agreement_check'))			// if the agreement check box exists ...
    	{
    	if (document.getElementById('agreement_check').checked==false)
	    	document.getElementById('fcp_send_button').disabled=true;
	    else
	    	document.getElementById('fcp_send_button').disabled=false;
	    }
	else
	   	document.getElementById('fcp_send_button').disabled=false;
    
    if (typeof response !== 'object')
        {
        document.getElementById('fcp_spinner').innerHTML = response;
        return;
        }

// loop through the responses

    var error_count = 0;
    var len = response.length;
    for (var i=0; i<len; ++i)           // [{...},{...},{...}]
    for (var command in response[i])    // {"command":"value","command":"value","element_id":"text"}
        {
        var command_value = response[i][command];
            
        if (command == 'redirect')
            {
            window.location = command_value;
            return;
            }
            
        if (command == 'reloadcaptcha')
            {
            if (typeof Recaptcha != 'undefined')
                Recaptcha.reload();
            continue;
            }
            
        if (command == 'f_error')                                                   // add the error class to an input field 
            {
            jQuery('#'+command_value).removeClass('fcp_error_field');
            jQuery('#'+command_value).addClass(' fcp_error_field');
            continue;
            }

        if (command == 'f_valid')                                                   // remove the error class from an input field
            {
            jQuery('#'+command_value).removeClass('fcp_error_field');
            continue;
            }
        
        if (command == 'e_error')                                                   // add the error class to an error message element
            {
            jQuery('#'+command_value).removeClass('fcp_error_msg');                 // in case it's already there
            jQuery('#'+command_value).addClass('fcp_error_msg');
            error_count ++;
            continue;
            }
        
        if (command == 'e_valid')                                                   // remove the error class to an error message element
            {
            jQuery('#'+command_value).removeClass('fcp_error_msg');
            jQuery('#'+command_value).empty();
            continue;
            }
        
        if (command == 'hide')                                                      // hide an element
            {
            jQuery('#'+command_value).hide();
            continue;
            }
        
        if (command == 'send_files')                                                // send attachment files
            {
            fcp_upload();
            return;
            }
            
        // if it's none of the above, the command is an element id and the value is the content for it

            jQuery('#'+command).html(command_value);
        }
        
// if there were any errors, re-build the tooltips

    if (error_count > 0)
        jQuery('.hasTooltip').tooltip({"html":true, "container":"body"});

// if file uploads are not supported, show the warning

    if ((!window.fcp_file_attachments) && (typeof fcp_config !== 'undefined'))
        document.getElementById('fcp_smsg').innerHTML = fcp_config.noup;    // display a message by the send button
}

//-------------------------------------------------------------------------------------
// Show upload progress
//
function fcp_progress(e)
{
    if (!e.lengthComputable)        // check browser support
        return;
    jQuery('#fcp_spinner').removeClass('fcp_spinner');
    jQuery('#fcp_spinner').removeClass('fcp_error_msg');
    jQuery('#fcp_spinner').addClass('fcp_percent');
    var percent = Math.round((e.loaded / e.total) * 100);
    document.getElementById('fcp_spinner').innerHTML = percent+"%";
}

//-------------------------------------------------------------------------------------
// Highlight images for image captcha
//
function fcp_image_select(pictureID)
{
    var images = document.getElementsByTagName('img');
    for (var i = 0; i < images.length; i++)
        if (images[i].className == 'fcp_active')
            images[i].className = 'fcp_inactive';
    document.getElementById(pictureID).className = 'fcp_active';
    document.fcp_form.picselected.value = pictureID;
}

//-------------------------------------------------------------------------------------
// Only allow numbers to be entered into a field
//
function numbersOnly(e)
{
    var unicode = e.charCode ? e.charCode : e.keyCode;
    if (unicode != 8)
        {
        if (unicode < 48 || unicode > 57) 
            return false;
        }
}

