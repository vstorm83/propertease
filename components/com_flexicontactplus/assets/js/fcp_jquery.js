function fcp_setup()
{
if((window.File)&&(window.FormData))
window.fcp_file_attachments=true;
else
window.fcp_file_attachments=false;
if(window.fcp_file_attachments)
{
var msie=false;
if(navigator.userAgent.indexOf('MSIE')!==-1||navigator.appVersion.indexOf('Trident/')>0)
msie=true;
if(('onprogress'in jQuery.ajaxSettings.xhr())&&(!msie))
{
var oldXHR=jQuery.ajaxSettings.xhr;
jQuery.ajaxSettings.xhr=function()
{
var xhr=oldXHR();
if(xhr instanceof window.XMLHttpRequest)
xhr.addEventListener('progress',this.progress,false);
if(xhr.upload)
xhr.upload.addEventListener('progress',this.progress,false);
return xhr;
}
}
}
if((!window.fcp_file_attachments)&&(typeof fcp_config!=='undefined'))
document.getElementById('fcp_smsg').innerHTML=fcp_config.noup;
var elements=jQuery('input, select, textarea','#fcp_form');
for(var i=0;i<elements.length;i++)
{
if(elements[i].type=='radio')
continue;
if(elements[i].type=='submit')
continue;
if(elements[i].type=='hidden')
continue;
if((elements[i].type=='file')&&(!window.fcp_file_attachments))
{
elements[i].disabled=true;
continue;
}
if(elements[i].className.indexOf('date')>-1)
{
jQuery(elements[i].id).on('blur',fcp_onchange_handler);
continue;
}
jQuery(elements[i]).on('change',fcp_onchange_handler);
}
jQuery('#fcp_send_button').on('click',fcp_onsubmit_handler);
jQuery('.hasTooltip').tooltip({"html":true,"container":"body"});
}
function fcp_onchange_handler(event)
{
var request=new Object;
var value=jQuery(this).val();
request['task']="validate_field";
request[this.name]=value;
if(this.type=='file')
{
if(!window.File)
return;
if(value!='')
{
var f=this.files;
var fieldnum=this.name.substring(5,8);
request['filesize'+fieldnum]=f[0].size;
}
}
else
{
var field_value=this.value;
if(this.type=='checkbox')
{
if(this.checked==true)
field_value=1;
else
field_value=0;
}
request[this.name]=field_value;
}
fcp_send_request(request);
}
function fcp_onsubmit_handler(e)
{
e.preventDefault();
jQuery('#fcp_send_button').attr('disabled','disabled');
jQuery('#fcp_spinner').empty().addClass('fcp_spinner');
var request='&task=send1';
var fileElements=jQuery('input[type=file]','#fcp_form');
for(var i=0;i<fileElements.length;i++)
{
var fileName=jQuery(fileElements[i]).val();
request+='&'+fileElements[i].name+"="+fileName;
if(fileName!='')
{
var f=fileElements[i].files;
var fieldnum=fileElements[i].name.substring(5,8);
request+='&filesize'+fieldnum+'='+f[0].size;
}
}
request+='&'+jQuery('#fcp_form').serialize();
fcp_send_request(request);
}
function fcp_upload()
{
if(!window.FormData)
return;
var form_data=new FormData(document.getElementById('fcp_form'));
form_data.append("task","send2");
var fileElements=jQuery('input[type=file]','#fcp_form');
for(var i=0;i<fileElements.length;i++)
{
var fileName=jQuery(fileElements[i]).val();
form_data.append(fileElements[i].name,fileName);
if(fileName!='')
{
var f=fileElements[i].files;
var fieldnum=fileElements[i].name.substring(5,8);
form_data.append('filesize'+fieldnum,f[0].size);
}
}
var config_id=document.getElementById('config_id').value;
var url='index.php?option=com_flexicontactplus&format=raw&tmpl=component&config_id='+config_id;
jQuery.ajax({
url:url,
data:form_data,
dataType:"json",
contentType:false,
processData:false,
type:"POST",
progress:function(e){fcp_progress(e);},
success:function(responseText,status,xhr){fcp_handle_response(responseText);},
error:function(xhr,status,error){fcp_handle_response('Failed: '+status+' '+xhr.responseText);}
});
}
function fcp_send_request(request_data)
{
var config_id=document.getElementById('config_id').value;
var url='index.php?option=com_flexicontactplus&format=raw&tmpl=component&config_id='+config_id;
jQuery.ajax({
url:url,
data:request_data,
dataType:"json",
type:"POST",
success:function(responseText,status,xhr){fcp_handle_response(responseText);},
error:function(xhr,status,error){fcp_handle_response('Failed: '+status+' '+xhr.responseText);}
});
}
function fcp_handle_response(response)
{
jQuery('#fcp_spinner').removeClass('fcp_spinner');
if(document.getElementById('agreement_check'))
{
if(document.getElementById('agreement_check').checked==false)
document.getElementById('fcp_send_button').disabled=true;
else
document.getElementById('fcp_send_button').disabled=false;
}
else
document.getElementById('fcp_send_button').disabled=false;
if(typeof response!=='object')
{
document.getElementById('fcp_spinner').innerHTML=response;
return;
}
var error_count=0;
var len=response.length;
for(var i=0;i<len;++i)
for(var command in response[i])
{
var command_value=response[i][command];
if(command=='redirect')
{
window.location=command_value;
return;
}
if(command=='reloadcaptcha')
{
if(typeof Recaptcha!='undefined')
Recaptcha.reload();
continue;
}
if(command=='f_error')
{
jQuery('#'+command_value).removeClass('fcp_error_field');
jQuery('#'+command_value).addClass(' fcp_error_field');
continue;
}
if(command=='f_valid')
{
jQuery('#'+command_value).removeClass('fcp_error_field');
continue;
}
if(command=='e_error')
{
jQuery('#'+command_value).removeClass('fcp_error_msg');
jQuery('#'+command_value).addClass('fcp_error_msg');
error_count++;
continue;
}
if(command=='e_valid')
{
jQuery('#'+command_value).removeClass('fcp_error_msg');
jQuery('#'+command_value).empty();
continue;
}
if(command=='hide')
{
jQuery('#'+command_value).hide();
continue;
}
if(command=='send_files')
{
fcp_upload();
return;
}
jQuery('#'+command).html(command_value);
}
if(error_count>0)
jQuery('.hasTooltip').tooltip({"html":true,"container":"body"});
if((!window.fcp_file_attachments)&&(typeof fcp_config!=='undefined'))
document.getElementById('fcp_smsg').innerHTML=fcp_config.noup;
}
function fcp_progress(e)
{
if(!e.lengthComputable)
return;
jQuery('#fcp_spinner').removeClass('fcp_spinner');
jQuery('#fcp_spinner').removeClass('fcp_error_msg');
jQuery('#fcp_spinner').addClass('fcp_percent');
var percent=Math.round((e.loaded/e.total)*100);
document.getElementById('fcp_spinner').innerHTML=percent+"%";
}
function fcp_image_select(pictureID)
{
var images=document.getElementsByTagName('img');
for(var i=0;i<images.length;i++)
if(images[i].className=='fcp_active')
images[i].className='fcp_inactive';
document.getElementById(pictureID).className='fcp_active';
document.fcp_form.picselected.value=pictureID;
}
function numbersOnly(e)
{
var unicode=e.charCode?e.charCode:e.keyCode;
if(unicode!=8)
{
if(unicode<48||unicode>57)
return false;
}
}
