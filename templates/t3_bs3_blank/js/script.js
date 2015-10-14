/** 
 *------------------------------------------------------------------------------
 * @package       T3 Framework for Joomla!
 *------------------------------------------------------------------------------
 * @copyright     Copyright (C) 2004-2013 JoomlArt.com. All Rights Reserved.
 * @license       GNU General Public License version 2 or later; see LICENSE.txt
 * @authors       JoomlArt, JoomlaBamboo, (contribute to this project at github 
 *                & Google group to become co-author)
 * @Google group: https://groups.google.com/forum/#!forum/t3fw
 * @Link:         http://t3-framework.org 
 *------------------------------------------------------------------------------
 */
jQuery(document).ready(function(){
    jQuery('.tacw').find('.content').hide();
    jQuery('.tacw').find('.selected').show();
    jQuery('.tacw-left ul li').click(function(){
        var div = jQuery(this).find('a').attr('href');
        jQuery('.tacw-left ul li').removeClass('selected');
        jQuery(this).addClass('selected');
        jQuery('.tacw').find('.content').hide();
        jQuery('.tacw').find(div).fadeIn();
    });
});
