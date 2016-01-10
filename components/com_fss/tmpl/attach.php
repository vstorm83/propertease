<?php
/**
 * @package Freestyle Joomla
 * @author Freestyle Joomla
 * @copyright (C) 2013 Freestyle Joomla
 * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
**/
defined('_JEXEC') or die;
?>
<?php if (!FSSJ3Helper::IsJ3() || FSS_Settings::get('support_attach_use_old_system')): ?>

<?php for ($i = 1; $i < 10 ; $i++): ?>

	<div id="file_<?php echo $i; ?>" <?php if ($i > 1): ?> style='display:none;' <?php endif; ?>>
		<input 
			type="file" 
			size="60" 
			id="filedata_<?php echo $i; ?>" 
			name="filedata_<?php echo $i; ?>" 
			onchange="jQuery('#file_<?php echo $i+1; ?>').show();jQuery('#file_<?php echo $i; ?>_btn').show();" 
			/>
		<a 
			id="file_<?php echo $i; ?>_btn" 
			class="btn btn-mini btn-warning"
			style='display: none' 
			onclick="fss_ClearFileInput(jQuery('#filedata_<?php echo $i; ?>')[0])"
			>&times;</a>
	</div>

<?php endfor; ?>

<?php else: ?>

<?php FSS_Helper::IncludeFileUpload(); ?>

<div style="position: relative;">

	<div class="fileupload-buttonbar">
		<div class="col-lg-7">
			<!-- The fileinput-button span is used to style the file input field as button -->
			<span class="btn fileinput-button pull-left">
				<span><?php echo JText::_('UPLOAD_FILES'); ?></span>
				<input type="file" name="files[]" multiple>
			</span>
		
			<div class="pull-left">&nbsp;</div>
			<div id="dropzone" class="pull-left btn"><?php echo JText::_('DROP_FILES_HERE'); ?></div>

			<!-- The global file processing state -->
			<span class="progress-extended"></span>
			<div class="col-lg-5 fileupload-progress in" style="display: inline-block;">
				<div class="progress-extended"></div>
			</div>
		</div>
		<!-- The global progress state -->
	</div>
</div>

<script>

</script>
<input id="files_delete" type="hidden" name="files_delete" value="" />
		
<table role="presentation" class="table table-striped table-condensed table-valign-middle" style="margin-bottom: 0;" id="attach_files">
	<tbody class="files">		
	</tbody>
</table>
		
<script id="template-upload" type="text/x-tmpl">
{% for (var i=0, file; file=o.files[i]; i++) { %}
    <tr class="template-upload fade">
        <td>
            <div class='name'>{%=file.name%}, <span class='size'>Processing...</span></div>
            <strong class="error text-danger"></strong>
        </td>
		<td width='100'>
            <div class="progress progress-striped active" role="progressbar" aria-valuemin="0" aria-valuemax="100" aria-valuenow="0">
				<div class="bar bar-success" style="width:0%;"></div>
			</div>
		</td>
		<td width='20' style='text-align: right'>
            <button class="btn btn-mini btn-danger cancel">
                &times;
            </button>
        </td>
    </tr>
{% } %}
</script>

<!-- The template to display files available for download -->
<script id="template-download" type="text/x-tmpl">
{% for (var i=0, file; file=o.files[i]; i++) { %}
    <tr class="template-download fade" style='cursor: move'>
		<!--<td width='48'>
            <span class="preview">
				<a href='{%=file.url%}' title='{%=file.url%}'>
					{% if (file.thumbnailUrl) { %}
						<img src="{%=file.thumbnailUrl%}" width='48' height='48'>
					{% } %}
				</a>
            </span>
        </td>-->
        <td>
			<div>
				<input type='hidden' name='new_filename[]' value='{%=file.name%}'>
				<input type='hidden' name='new_fileorder[]' class='order' value=''>
			</div>
			<div style='padding: 3px 6px'>
				<span class="name"><a href='{%=file.url%}' title='{%=file.url%}'>{%=file.name%}</a></span>, 
				<span class='size'>{%=o.formatFileSize(file.size)%}</span>
			</div>
			
            {% if (file.error) { %}
                <div><span class="label label-danger">Error</span> {%=file.error%}</div>
            {% } %}
        </td>
        <td colspan='2' style='text-align: right'>
            <button class="btn btn-mini btn-danger delete" data-type="{%=file.deleteType%}" data-url="{%=file.deleteUrl%}"{% if (file.deleteWithCredentials) { %} data-xhr-fields='{"withCredentials":true}'{% } %}>
                &times;
            </button>
        </td>
    </tr>
{% } %}
</script>

<?php endif; ?>