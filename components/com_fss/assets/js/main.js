
jQuery(document).ready(function () {
    // test if original jQuery library is loaded
    try {
        if (jQuery.fn.fss_jquery_ok()) {
        }
    } catch (e) {
        if (typeof (fss_no_warn) == "undefined") {
            jQuery('.fss_main').first().prepend("<div class='alert alert-error'><h4>Freestyle Support Portal Error: jQuery issue detected</h4>Multiple copies of the jQuery library are being loaded on your site. This will prevent much of the component from working correctly. Please install the jQuery Easy Plugin to ensure that onle a single instance of the jQuery library is loaded to allow this component to work correctly.</div>");
        }
    }

    jQuery('.fss_main .hide').hide().removeClass('hide');

    init_elements();

    jQuery('#fss_modal_container').appendTo(document.body);

    // fix hide event in bootstrap when mootools is loaded 
    if (typeof (MooTools) != "undefined") {
        (function ($) {
            $$('[data-toggle=collapse]').each(function (e) {
                if ($$(e.get('data-target')).length > 0) {
                    $$(e.get('data-target'))[0].hide = null;
                }
            });
        })(MooTools);
    }

    setTimeout("fix_joomla_art_mess();", 500);

    jQuery('.help-inline').each(function () {
        if (jQuery(this).text().trim() == "")
            jQuery(this).hide();
    })
});

function fix_joomla_art_mess() {
    jQuery('.fss_main .nav.nav-tabs a').unbind('click');
}

function init_elements() {

    jQuery('.fss_main .vert-center').each(function () {
        var elem_height = jQuery(this).outerHeight(true);
        var parent_height = jQuery(this).parent().height();
        var offset = parseInt((parent_height - elem_height) / 2);
        jQuery(this).css('top', offset + 'px');
    });

    jQuery('.fss_main .show_modal').click(function (ev) {
        ev.preventDefault();
        var url = jQuery(this).attr('href');

        jQuery('#fss_modal').html(jQuery('#fss_modal_base').html());
        jQuery('#fss_modal').css('width', '560px');
        jQuery('#fss_modal').css('margin-left', '-280px');
        //jQuery('#fss_modal').css('margin-top', '-250px');
        jQuery('#fss_modal').modal("show");
        jQuery('#fss_modal').load(url);
    });

    jQuery('.fss_main .show_modal_iframe').click(function (ev) {
        ev.preventDefault();
        var url = jQuery(this).attr('href');
        var width = jQuery(this).attr('data_modal_width');
        if (typeof (width) != "number")
            width = 0;
        if (width < 1)
            width = 820;

        if (jQuery(window).width() < 766)
        {
            width = jQuery(window).width();
        }

        var offset = parseInt(width / 2);

        jQuery('#fss_modal').addClass('iframe');
        jQuery('#fss_modal').html("<iframe src='" + url + "' seamless='seamless'>");
        jQuery('#fss_modal').modal("show");
        jQuery('#fss_modal').css('width', width + 'px');
        jQuery('#fss_modal').css('margin-left', '-' + offset + 'px');
        //jQuery('#fss_modal').css('margin-top', '-250px');
    });

    jQuery('.fss_main .show_modal_image').click(function (ev) {
        ev.preventDefault();
        var url = jQuery(this).attr('href');

        if (!url)
            url = jQuery(this).attr('src');
        
        jQuery('#fss_modal').addClass('iframe');

        var is_inline = false;
        if (url.substring(0, 10) == "data:image")
        {
            is_inline = true;
        }

        if (is_inline) {
            var html = "<img id='modal_image_image' src='" + url + "'>";
            html += "<div class='modal_image_close'>&times;</div>";
        } else {
            var html = "<img id='modal_image_wait' src='" + jQuery('#fss_base_url').text() + "/components/com_fss/assets/images/ajax-loader.gif' style='padding:84px;'>";
            html += "<img id='modal_image_image' src='" + url + "' style='display: none'>";
            html += "<div class='modal_image_close'>&times;</div>";
        }

        jQuery('#fss_modal').html(html);
        jQuery('#fss_modal').modal("show");
        jQuery('#fss_modal').css('width', '200px');
        jQuery('#fss_modal').css('margin-left', '-100px');
        //jQuery('#fss_modal').css('margin-top', '-250px');

        jQuery('.modal_image_close').click(function () {
            jQuery('#fss_modal').modal("hide");
        });

        if (is_inline) {
            fss_resize_modal(jQuery('#modal_image_image').naturalWidth(), jQuery('#modal_image_image').naturalHeight());
        } else {
            jQuery('#modal_image_image').load(function () {
                fss_resize_modal(this.width, this.height);
            });
        }

    });

    jQuery('.fss_main .select-color').change(function (ev) {
        select_update_color(this);
    });

    jQuery('.fss_main .select-color').each(function () {
        select_update_color_init(this);
        select_update_color(this);
    });

    if (jQuery.fn.fss_tooltip)
        jQuery('.fss_main .fssTip').fss_tooltip();

    jQuery('.fss_main .dropdown-toggle').dropdown();
}

function fss_resize_modal(width, height)
{
    var max_width = parseInt(jQuery(window).width() * 0.9);
    var max_height = parseInt(jQuery(window).height() * 0.9);

    if (width > max_width) {
        var scale = max_width / width;
        width = parseInt(scale * width);
        height = parseInt(scale * height);
    }

    if (height > max_height) {
        var scale = max_height / height;
        width = parseInt(scale * width);
        height = parseInt(scale * height);
    }

    var w_offset = parseInt(width / 2);
    var h_offset = parseInt(height / 2);
    jQuery('#modal_image_wait').hide();
    jQuery('#modal_image_image').show();

    if (jQuery.isFunction(jQuery('#fss_modal').animate)) {
        jQuery('#fss_modal').animate({ width: width + 'px', marginLeft: '-' + w_offset + 'px', marginTop: '-' + h_offset + 'px' }, 500);
    } else {
        jQuery('#fss_modal').css('width', width + 'px');
        jQuery('#fss_modal').css('margin-left', '-' + w_offset + 'px');
        jQuery('#fss_modal').css('margin-top', '-' + h_offset + 'px');
    }
}

function select_update_color_init(el) {
    var sel_el = jQuery(el);
    var value = sel_el.val();
    // change color of dropdown

    basecol = sel_el.css('color');
    
    sel_el.css('color', sel_el.css('color'));

    sel_el.find('option').each(function () {
        var active = false;
        if (value == jQuery(this).attr('value')) {
            sel_el.val(value + 1);
            active = true;
            jQuery(this).removeAttr('selected');
        }

        var color = jQuery(this).css('color');

        if (color == "rgb(255, 255, 255)") // hack for IE
            color = basecol;

        jQuery(this).attr('dropdown-color', color);
        jQuery(this).css('color', color);
        if (active)
            jQuery(this).attr('selected', 'selected');
    });


    sel_el.find('optgroup').each(function () {
        jQuery(this).css('color', jQuery(this).css('color'));
    });
    sel_el.val(value);
}

function select_update_color(el) {
    jQuery(el).css('color', '');
    jQuery(el).find('option').each(function () {
        if (jQuery(this).attr('value') == jQuery(el).val()) {
            jQuery(el).css('color', jQuery(this).attr('dropdown-color'));
        }
    });
}

function fss_modal_show(url, iframe, width) {
    if (!width)
        width = 820;
    var offset = parseInt(width / 2);

    jQuery('#fss_modal').css('width', width + 'px');
    jQuery('#fss_modal').css('margin-left', '-' + offset + 'px');

    if (iframe) {
        jQuery('#fss_modal').addClass('iframe');
        jQuery('#fss_modal').html("<iframe src='" + url + "' scrolling='no' seamless='seamless'>");
        jQuery('#fss_modal').modal("show");
    } else {
        jQuery('#fss_modal').removeClass('iframe');
        jQuery('#fss_modal').html(jQuery('#fss_modal_base').html());
        jQuery('#fss_modal').modal("show");
        jQuery('#fss_modal').load(url);
    }
}

function fss_modal_hide() {
    try {
        jQuery('#fss_modal').modal("hide");
    } catch (e) {
    }
    try {
        jQuery.modal.close();
    } catch (e)
    {
    }
}

(function (jQuery) {
    function _outerSetter(direction, args) {

        var $el = jQuery(this),
            $sec_el = jQuery(args[0]),
            dir = (direction == 'Height') ? ['Top', 'Bottom'] : ['Left', 'Right'],
            style_attrs = ['padding', 'border'],
            style_data = {};
        // If we are detecting margins
        if (args[1]) {
            style_attrs.push('margin');
        }
        jQuery(style_attrs).each(function () {
            var $style_attrs = this;
            jQuery(dir).each(function () {
                var prop = $style_attrs + this + (($style_attrs == 'border') ? 'Width' : '');
                style_data[prop] = parseFloat($sec_el.css(prop));
            });
        });
        $el[direction.toLowerCase()]($sec_el[direction.toLowerCase()]());
        $el.css(style_data);
        return $el['outer' + direction](args[1]);

    }
    jQuery(['Height', 'Width']).each(function () {
        var old_method = jQuery.fn['outer' + this];
        var direction = this;
        jQuery.fn['outer' + this] = function () {
            if (typeof arguments[0] === 'string') {
                return _outerSetter.call(this, direction, arguments);
            }
            return old_method.apply(this, arguments);
        }
    });
})(jQuery);

(function (jQuery) {
    var
    props = ['Width', 'Height'],
    prop;

    while (prop = props.pop()) {
        (function (natural, prop) {
            jQuery.fn[natural] = (natural in new Image()) ?
            function () {
                return this[0][natural];
            } :
            function () {
                var
                node = this[0],
                img,
                value;

                if (node.tagName.toLowerCase() === 'img') {
                    img = new Image();
                    img.src = node.src,
                    value = img[prop];
                }
                return value;
            };
        }('natural' + prop, prop.toLowerCase()));
    }
}(jQuery));

function fss_ClearFileInput(oldInput) {
    var newInput = document.createElement("input");

    newInput.type = "file";
    newInput.id = oldInput.id;
    newInput.name = oldInput.name;
    newInput.className = oldInput.className;
    newInput.style.cssText = oldInput.style.cssText;
    jQuery(newInput).attr('size', jQuery(oldInput).attr('size'));
    // copy any other relevant attributes

    var par = jQuery(oldInput).parent();
    oldInput.remove();

    //jQuery(newInput).prependTo(par);
    jQuery(par).prepend(newInput);
}

jQuery.fn.fss_jquery_ok = function () {
    return true;
};

jQuery(document).bind('dragover', function (e) {
    var dropZone = jQuery('#dropzone'),
        timeout = window.dropZoneTimeout;
    if (!timeout) {
        dropZone.addClass('in');
    } else {
        clearTimeout(timeout);
    }
    var found = false,
        node = e.target;
    do {
        if (node === dropZone[0]) {
            found = true;
            break;
        }
        node = node.parentNode;
    } while (node != null);
    if (found) {
        dropZone.addClass('hover');
    } else {
        dropZone.removeClass('hover');
    }
    window.dropZoneTimeout = setTimeout(function () {
        window.dropZoneTimeout = null;
        dropZone.removeClass('in hover');
    }, 100);
});