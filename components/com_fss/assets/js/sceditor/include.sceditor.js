jQuery(document).ready(function () {
    setTimeout("init_sceditor()", 500);
});

function init_sceditor() {
    jQuery("textarea.sceditor").each(function () {
        var rows = parseInt(jQuery(this).attr('rows'));
        if (isNaN(rows)) rows = 8;
        if (rows < 8) rows = 8;

        jQuery(this).attr('rows', rows + 8);

            jQuery(this).sceditor({
                plugins: "bbcode",
                style: sceditor_style_root + "jquery.sceditor." + sceditor_style_type + ".css",
                emoticonsRoot: sceditor_emoticons_root,
                toolbarExclude: sceditor_toolbar_exclude
            });
       

        jQuery('div.sceditor-container').addClass('sceditor-container-' + sceditor_style_type);

        jQuery(this).removeClass('sceditor');
    });
}