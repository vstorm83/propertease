$ = jQuery.noConflict();
var btlOpt = $('.btlOpt');

/*! jQuery Migrate v1.2.1 | (c) 2005, 2013 jQuery Foundation, Inc. and other contributors | jquery.org/license */
jQuery.migrateMute===void 0&&(jQuery.migrateMute=!0),function(e,t,n){function r(n){var r=t.console;i[n]||(i[n]=!0,e.migrateWarnings.push(n),r&&r.warn&&!e.migrateMute&&(r.warn("JQMIGRATE: "+n),e.migrateTrace&&r.trace&&r.trace()))}function a(t,a,i,o){if(Object.defineProperty)try{return Object.defineProperty(t,a,{configurable:!0,enumerable:!0,get:function(){return r(o),i},set:function(e){r(o),i=e}}),n}catch(s){}e._definePropertyBroken=!0,t[a]=i}var i={};e.migrateWarnings=[],!e.migrateMute&&t.console&&t.console.log&&t.console.log("JQMIGRATE: Logging is active"),e.migrateTrace===n&&(e.migrateTrace=!0),e.migrateReset=function(){i={},e.migrateWarnings.length=0},"BackCompat"===document.compatMode&&r("jQuery is not compatible with Quirks Mode");var o=e("<input/>",{size:1}).attr("size")&&e.attrFn,s=e.attr,u=e.attrHooks.value&&e.attrHooks.value.get||function(){return null},c=e.attrHooks.value&&e.attrHooks.value.set||function(){return n},l=/^(?:input|button)$/i,d=/^[238]$/,p=/^(?:autofocus|autoplay|async|checked|controls|defer|disabled|hidden|loop|multiple|open|readonly|required|scoped|selected)$/i,f=/^(?:checked|selected)$/i;a(e,"attrFn",o||{},"jQuery.attrFn is deprecated"),e.attr=function(t,a,i,u){var c=a.toLowerCase(),g=t&&t.nodeType;return u&&(4>s.length&&r("jQuery.fn.attr( props, pass ) is deprecated"),t&&!d.test(g)&&(o?a in o:e.isFunction(e.fn[a])))?e(t)[a](i):("type"===a&&i!==n&&l.test(t.nodeName)&&t.parentNode&&r("Can't change the 'type' of an input or button in IE 6/7/8"),!e.attrHooks[c]&&p.test(c)&&(e.attrHooks[c]={get:function(t,r){var a,i=e.prop(t,r);return i===!0||"boolean"!=typeof i&&(a=t.getAttributeNode(r))&&a.nodeValue!==!1?r.toLowerCase():n},set:function(t,n,r){var a;return n===!1?e.removeAttr(t,r):(a=e.propFix[r]||r,a in t&&(t[a]=!0),t.setAttribute(r,r.toLowerCase())),r}},f.test(c)&&r("jQuery.fn.attr('"+c+"') may use property instead of attribute")),s.call(e,t,a,i))},e.attrHooks.value={get:function(e,t){var n=(e.nodeName||"").toLowerCase();return"button"===n?u.apply(this,arguments):("input"!==n&&"option"!==n&&r("jQuery.fn.attr('value') no longer gets properties"),t in e?e.value:null)},set:function(e,t){var a=(e.nodeName||"").toLowerCase();return"button"===a?c.apply(this,arguments):("input"!==a&&"option"!==a&&r("jQuery.fn.attr('value', val) no longer sets properties"),e.value=t,n)}};var g,h,v=e.fn.init,m=e.parseJSON,y=/^([^<]*)(<[\w\W]+>)([^>]*)$/;e.fn.init=function(t,n,a){var i;return t&&"string"==typeof t&&!e.isPlainObject(n)&&(i=y.exec(e.trim(t)))&&i[0]&&("<"!==t.charAt(0)&&r("$(html) HTML strings must start with '<' character"),i[3]&&r("$(html) HTML text after last tag is ignored"),"#"===i[0].charAt(0)&&(r("HTML string cannot start with a '#' character"),e.error("JQMIGRATE: Invalid selector string (XSS)")),n&&n.context&&(n=n.context),e.parseHTML)?v.call(this,e.parseHTML(i[2],n,!0),n,a):v.apply(this,arguments)},e.fn.init.prototype=e.fn,e.parseJSON=function(e){return e||null===e?m.apply(this,arguments):(r("jQuery.parseJSON requires a valid JSON string"),null)},e.uaMatch=function(e){e=e.toLowerCase();var t=/(chrome)[ \/]([\w.]+)/.exec(e)||/(webkit)[ \/]([\w.]+)/.exec(e)||/(opera)(?:.*version|)[ \/]([\w.]+)/.exec(e)||/(msie) ([\w.]+)/.exec(e)||0>e.indexOf("compatible")&&/(mozilla)(?:.*? rv:([\w.]+)|)/.exec(e)||[];return{browser:t[1]||"",version:t[2]||"0"}},e.browser||(g=e.uaMatch(navigator.userAgent),h={},g.browser&&(h[g.browser]=!0,h.version=g.version),h.chrome?h.webkit=!0:h.webkit&&(h.safari=!0),e.browser=h),a(e,"browser",e.browser,"jQuery.browser is deprecated"),e.sub=function(){function t(e,n){return new t.fn.init(e,n)}e.extend(!0,t,this),t.superclass=this,t.fn=t.prototype=this(),t.fn.constructor=t,t.sub=this.sub,t.fn.init=function(r,a){return a&&a instanceof e&&!(a instanceof t)&&(a=t(a)),e.fn.init.call(this,r,a,n)},t.fn.init.prototype=t.fn;var n=t(document);return r("jQuery.sub() is deprecated"),t},e.ajaxSetup({converters:{"text json":e.parseJSON}});var b=e.fn.data;e.fn.data=function(t){var a,i,o=this[0];return!o||"events"!==t||1!==arguments.length||(a=e.data(o,t),i=e._data(o,t),a!==n&&a!==i||i===n)?b.apply(this,arguments):(r("Use of jQuery.fn.data('events') is deprecated"),i)};var j=/\/(java|ecma)script/i,w=e.fn.andSelf||e.fn.addBack;e.fn.andSelf=function(){return r("jQuery.fn.andSelf() replaced by jQuery.fn.addBack()"),w.apply(this,arguments)},e.clean||(e.clean=function(t,a,i,o){a=a||document,a=!a.nodeType&&a[0]||a,a=a.ownerDocument||a,r("jQuery.clean() is deprecated");var s,u,c,l,d=[];if(e.merge(d,e.buildFragment(t,a).childNodes),i)for(c=function(e){return!e.type||j.test(e.type)?o?o.push(e.parentNode?e.parentNode.removeChild(e):e):i.appendChild(e):n},s=0;null!=(u=d[s]);s++)e.nodeName(u,"script")&&c(u)||(i.appendChild(u),u.getElementsByTagName!==n&&(l=e.grep(e.merge([],u.getElementsByTagName("script")),c),d.splice.apply(d,[s+1,0].concat(l)),s+=l.length));return d});var Q=e.event.add,x=e.event.remove,k=e.event.trigger,N=e.fn.toggle,T=e.fn.live,M=e.fn.die,S="ajaxStart|ajaxStop|ajaxSend|ajaxComplete|ajaxError|ajaxSuccess",C=RegExp("\\b(?:"+S+")\\b"),H=/(?:^|\s)hover(\.\S+|)\b/,A=function(t){return"string"!=typeof t||e.event.special.hover?t:(H.test(t)&&r("'hover' pseudo-event is deprecated, use 'mouseenter mouseleave'"),t&&t.replace(H,"mouseenter$1 mouseleave$1"))};e.event.props&&"attrChange"!==e.event.props[0]&&e.event.props.unshift("attrChange","attrName","relatedNode","srcElement"),e.event.dispatch&&a(e.event,"handle",e.event.dispatch,"jQuery.event.handle is undocumented and deprecated"),e.event.add=function(e,t,n,a,i){e!==document&&C.test(t)&&r("AJAX events should be attached to document: "+t),Q.call(this,e,A(t||""),n,a,i)},e.event.remove=function(e,t,n,r,a){x.call(this,e,A(t)||"",n,r,a)},e.fn.error=function(){var e=Array.prototype.slice.call(arguments,0);return r("jQuery.fn.error() is deprecated"),e.splice(0,0,"error"),arguments.length?this.bind.apply(this,e):(this.triggerHandler.apply(this,e),this)},e.fn.toggle=function(t,n){if(!e.isFunction(t)||!e.isFunction(n))return N.apply(this,arguments);r("jQuery.fn.toggle(handler, handler...) is deprecated");var a=arguments,i=t.guid||e.guid++,o=0,s=function(n){var r=(e._data(this,"lastToggle"+t.guid)||0)%o;return e._data(this,"lastToggle"+t.guid,r+1),n.preventDefault(),a[r].apply(this,arguments)||!1};for(s.guid=i;a.length>o;)a[o++].guid=i;return this.click(s)},e.fn.live=function(t,n,a){return r("jQuery.fn.live() is deprecated"),T?T.apply(this,arguments):(e(this.context).on(t,this.selector,n,a),this)},e.fn.die=function(t,n){return r("jQuery.fn.die() is deprecated"),M?M.apply(this,arguments):(e(this.context).off(t,this.selector||"**",n),this)},e.event.trigger=function(e,t,n,a){return n||C.test(e)||r("Global events are undocumented and deprecated"),k.call(this,e,t,n||document,a)},e.each(S.split("|"),function(t,n){e.event.special[n]={setup:function(){var t=this;return t!==document&&(e.event.add(document,n+"."+e.guid,function(){e.event.trigger(n,null,t,!0)}),e._data(this,n,e.guid++)),!1},teardown:function(){return this!==document&&e.event.remove(document,n+"."+e._data(this,n)),!1}}})}(jQuery,window);
/*
        GNU General Public License version 2 or later; see LICENSE.txt
*/
var JCaption=function(c){var e,b,a=function(f){e=jQuery.noConflict();b=f;e(b).each(function(g,h){d(h)})},d=function(i){var h=e(i),f=h.attr("title"),j=h.attr("width")||i.width,l=h.attr("align")||h.css("float")||i.style.styleFloat||"none",g=e("<p/>",{text:f,"class":b.replace(".","_")}),k=e("<div/>",{"class":b.replace(".","_")+" "+l,css:{"float":l,width:j}});h.before(k);k.append(h);if(f!==""){k.append(g)}};a(c)};

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

!function(t){(void 0==t.browser||void 0==t.browser.msie)&&(t.browser={msie:!1,version:0},(match=navigator.userAgent.match(/MSIE ([0-9]{1,}[\.0-9]{0,})/)||navigator.userAgent.match(/Trident.*rv:([0-9]{1,}[\.0-9]{0,})/))&&(t.browser.msie=!0,t.browser.version=match[1])),t.browser.msie&&t("html").addClass("ie"+Math.floor(t.browser.version)),t(document).ready(function(){window.getComputedStyle||(window.getComputedStyle=function(t){return this.el=t,this.getPropertyValue=function(e){var o=/(\-([a-z]){1})/g;return"float"==e&&(e="styleFloat"),o.test(e)&&(e=e.replace(o,function(){return arguments[2].toUpperCase()})),t.currentStyle[e]?t.currentStyle[e]:null},this});var e="body-data-holder",o="content",n=t("<div>").css("display","none").addClass(e).appendTo(t("body"));try{var r=window.getComputedStyle(n[0],":before");if(r){var a=r.getPropertyValue(o);if(a){var l=a.match(/([\da-z\-]+)/gi),s={};if(l&&l.length)for(var i=0;i<l.length;i++)s[l[i++]]=i<l.length?l[i]:null;t("body").data(s)}}}finally{n.remove()}}),function(){t.support.t3transform=function(){for(var t,e=document.createElement("div").style,o=["t","webkitT","MozT","msT","OT"],n=0,r=o.length;r>n;n++)if(t=o[n]+"ransform",t in e)return t;return!1}()}(),function(){t("html").addClass("ontouchstart"in window?"touch":"no-touch")}(),t(document).ready(function(){!function(){if(window.MooTools&&window.MooTools.More&&Element&&Element.implement){var e=Element.prototype.hide,o=Element.prototype.show,n=Element.prototype.slide;Element.implement({show:function(e){return arguments.callee&&arguments.callee.caller&&-1!==arguments.callee.caller.toString().indexOf("isPropagationStopped")?this:t.isFunction(o)&&o.apply(this,e)},hide:function(){return arguments.callee&&arguments.callee.caller&&-1!==arguments.callee.caller.toString().indexOf("isPropagationStopped")?this:t.isFunction(e)&&e.apply(this,arguments)},slide:function(e){return arguments.callee&&arguments.callee.caller&&-1!==arguments.callee.caller.toString().indexOf("isPropagationStopped")?this:t.isFunction(n)&&n.apply(this,e)}})}}(),t.fn.tooltip.Constructor&&t.fn.tooltip.Constructor.DEFAULTS&&(t.fn.tooltip.Constructor.DEFAULTS.html=!0),t.fn.popover.Constructor&&t.fn.popover.Constructor.DEFAULTS&&(t.fn.popover.Constructor.DEFAULTS.html=!0),t.fn.tooltip.defaults&&(t.fn.tooltip.defaults.html=!0),t.fn.popover.defaults&&(t.fn.popover.defaults.html=!0),function(){window.jomsQuery&&jomsQuery.fn.collapse&&(t('[data-toggle="collapse"]').on("click",function(e){return t(t(this).attr("data-target")).eq(0).collapse("toggle"),e.stopPropagation(),!1}),jomsQuery("html, body").off("touchstart.dropdown.data-api"))}(),function(){t.fn.chosen&&"rtl"==t(document.documentElement).attr("dir")&&t("select").addClass("chzn-rtl")}()}),t(window).load(function(){if(!t(document.documentElement).hasClass("off-canvas-ready")&&(t(".navbar-collapse-fixed-top").length||t(".navbar-collapse-fixed-bottom").length)){var e=t('.btn-navbar[data-toggle="collapse"]');if(!e.length)return;if(e.data("target")){var o=t(e.data("target"));if(!o.length)return;var n=o.closest(".navbar-collapse-fixed-top").length;e.on("click",function(){var r=window.innerHeight||t(window).height();t.support.transition||(o.parent().css("height",!e.hasClass("collapsed")&&e.data("t3-clicked")?"":r),e.data("t3-clicked",1)),o.addClass("animate").css("max-height",r-(n?parseFloat(o.css("top"))||0:parseFloat(o.css("bottom"))||0))}),o.on("shown hidden",function(){o.removeClass("animate")})}}})}(jQuery);

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

!function(t){var e=function(e,i){this.$menu=t(e),this.$menu.length&&(this.options=t.extend({},t.fn.t3menu.defaults,i),this.child_open=[],this.loaded=!1,this.start())};e.prototype={constructor:e,start:function(){if(!this.loaded){this.loaded=!0;var e=this,i=this.options,n=this.$menu;this.$items=n.find("li"),this.$items.each(function(){var n=t(this),s=n.children(".dropdown-menu"),o=n.children("a"),a={$item:n,child:s.length,link:o.length,clickable:!(o.length&&s.length),mega:n.hasClass("mega"),status:"close",timer:null,atimer:null};n.data("t3menu.item",a),s.length&&!i.hover?n.on("click",function(t){t.stopPropagation(),n.hasClass("group")||"close"==a.status&&(t.preventDefault(),e.show(a))}):n.on("click",function(t){t.stopPropagation()}),n.find("a > .caret").on("click tap",function(){a.clickable=!1}),i.hover&&(n.on("mouseover",function(i){if(!n.hasClass("group")){var s=t(i.target);s.data("show-processed")||(s.data("show-processed",!0),setTimeout(function(){s.data("show-processed",!1)},10),e.show(a))}}).on("mouseleave",function(i){if(!n.hasClass("group")){var s=t(i.target);s.data("hide-processed")||(s.data("hide-processed",!0),setTimeout(function(){s.data("hide-processed",!1)},10),e.hide(a,s))}}),o.length&&s.length&&o.on("click",function(){return a.clickable}))}),t(document.body).on("tap hideall.t3menu",function(i){clearTimeout(e.timer),e.timer=setTimeout(t.proxy(e.hide_alls,e),"tap"==i.type?500:e.options.hidedelay)}),n.find(".mega-dropdown-menu").on("hideall.t3menu",function(t){return t.stopPropagation(),t.preventDefault(),!1}),n.find("input, select, textarea, label").on("click tap",function(t){t.stopPropagation()})}},show:function(e){t.inArray(e,this.child_open)<this.child_open.length-1&&this.hide_others(e),t(document.body).trigger("hideall.t3menu",[this]),clearTimeout(this.timer),clearTimeout(e.timer),clearTimeout(e.ftimer),clearTimeout(e.ctimer),"open"==e.status&&e.$item.hasClass("open")&&this.child_open.length||(e.mega?(clearTimeout(e.astimer),clearTimeout(e.atimer),this.position(e.$item),e.astimer=setTimeout(function(){e.$item.addClass("animating")},10),e.atimer=setTimeout(function(){e.$item.removeClass("animating")},this.options.duration+50),e.timer=setTimeout(function(){e.$item.addClass("open")},100)):e.$item.addClass("open"),e.status="open",e.child&&-1==t.inArray(e,this.child_open)&&this.child_open.push(e)),e.ctimer=setTimeout(t.proxy(this.clickable,this,e),300)},hide:function(e,i){if(clearTimeout(this.timer),clearTimeout(e.timer),clearTimeout(e.astimer),clearTimeout(e.atimer),clearTimeout(e.ftimer),!i||!i.is("input",e.$item)){e.mega?(e.$item.addClass("animating"),e.atimer=setTimeout(function(){e.$item.removeClass("animating")},this.options.duration),e.timer=setTimeout(function(){e.$item.removeClass("open")},100)):e.timer=setTimeout(function(){e.$item.removeClass("open")},100),e.status="close";for(var n=this.child_open.length;n--;)this.child_open[n]===e&&this.child_open.splice(n,1);e.ftimer=setTimeout(t.proxy(this.hidden,this,e),this.options.duration),this.timer=setTimeout(t.proxy(this.hide_alls,this),this.options.hidedelay)}},hidden:function(t){"close"==t.status&&(t.clickable=!1)},hide_others:function(e){var i=this;t.each(this.child_open.slice(),function(t,n){(!e||n!=e&&!n.$item.has(e.$item).length)&&i.hide(n)})},hide_alls:function(e,i){if(!e||"tap"==e.type||"hideall"==e.type&&this!=i){var n=this;t.each(this.child_open.slice(),function(t,e){e&&n.hide(e)})}},clickable:function(t){t.clickable=!0},position:function(e){var i=e.children(".mega-dropdown-menu"),n=i.is(":visible");n||i.show();var s=e.offset(),o=e.outerWidth(),a=t(window).width()-this.options.sb_width,r=i.outerWidth(),l=e.data("level");if(n||i.css("display",""),i.css({left:"",right:""}),1==l){var h=e.data("alignsub"),m=0,c=0,d=0;if("justify"==h)return;h||(h="left"),"center"==h?(m=s.left+o/2,t.support.t3transform||(d=-r/2,i.css(this.options.rtl?"right":"left",d+o/2))):m=s.left+("left"==h&&this.options.rtl||"right"==h&&!this.options.rtl?o:0),this.options.rtl?"right"==h?m+r>a&&(c=a-m-r,i.css("left",c),r>a&&i.css("left",c+r-a)):(("center"==h?r/2:r)>m&&(c=m-("center"==h?r/2:r),i.css("right",c+d)),m+("center"==h?r/2:0)-c>a&&i.css("right",m+("center"==h?(r+o)/2:0)+d-a)):"right"==h?r>m&&(c=m-r,i.css("right",c),r>a&&i.css("right",r-a+c)):(m+("center"==h?r/2:r)>a&&(c=a-m-("center"==h?r/2:r),i.css("left",c+d)),0>m-("center"==h?r/2:0)+c&&i.css("left",("center"==h?(r+o)/2:0)+d-m))}else this.options.rtl?e.closest(".mega-dropdown-menu").parent().hasClass("mega-align-right")?s.left+o+r>a&&(e.removeClass("mega-align-right"),s.left-r<0&&i.css("right",s.left+o-r)):s.left-r<0&&(e.removeClass("mega-align-left").addClass("mega-align-right"),s.left+o+r>a&&i.css("left",a-s.left-r)):e.closest(".mega-dropdown-menu").parent().hasClass("mega-align-right")?s.left-r<0&&(e.removeClass("mega-align-right"),s.left+o+r>a&&i.css("left",a-s.left-r)):s.left+o+r>a&&(e.removeClass("mega-align-left").addClass("mega-align-right"),s.left-r<0&&i.css("right",s.left+o-r))}},t.fn.t3menu=function(i){return this.each(function(){var n=t(this),s=n.data("megamenu"),o="object"==typeof i&&i;n.parents("#off-canvas-nav").length||n.parents("#t3-off-canvas").length||(s?"string"==typeof i&&s[i]&&s[i]():n.data("megamenu",s=new e(this,o)))})},t.fn.t3menu.defaults={duration:400,timeout:100,hidedelay:200,hover:!0,sb_width:20},t(document).ready(function(){var e=t(".t3-megamenu").data("duration")||0;e&&t('<style type="text/css">.t3-megamenu.animate .animating > .mega-dropdown-menu,.t3-megamenu.animate.slide .animating > .mega-dropdown-menu > div {transition-duration: '+e+"ms !important;-webkit-transition-duration: "+e+"ms !important;}</style>").appendTo("head");var i=e?100+e:500,n="rtl"==t(document.documentElement).attr("dir"),s=t(document.documentElement).hasClass("mm-hover"),o=function(){var e=t('<div style="width:50px;height:50px;overflow:auto"><div/></div>').appendTo("body"),i=e.children(),n=i.innerWidth()-i.height(100).innerWidth();return e.remove(),n}();t.support.transition||(t(".t3-megamenu").removeClass("animate"),i=100),t("ul.nav").has(".dropdown-menu").t3menu({duration:e,timeout:i,rtl:n,sb_width:o,hover:s}),t(window).load(function(){t("ul.nav").has(".dropdown-menu").t3menu({duration:e,timeout:i,rtl:n,sb_width:o,hover:s})})})}(jQuery);

/*
 * jQuery Easing v1.3 - http://gsgd.co.uk/sandbox/jquery/easing/
 *
 * Uses the built in easing capabilities added In jQuery 1.1
 * to offer multiple easing options
 *
 * TERMS OF USE - jQuery Easing
 * 
 * Open source under the BSD License. 
 * 
 * Copyright Â© 2008 George McGinley Smith
 * All rights reserved.
 * 
 * Redistribution and use in source and binary forms, with or without modification, 
 * are permitted provided that the following conditions are met:
 * 
 * Redistributions of source code must retain the above copyright notice, this list of 
 * conditions and the following disclaimer.
 * Redistributions in binary form must reproduce the above copyright notice, this list 
 * of conditions and the following disclaimer in the documentation and/or other materials 
 * provided with the distribution.
 * 
 * Neither the name of the author nor the names of contributors may be used to endorse 
 * or promote products derived from this software without specific prior written permission.
 * 
 * THIS SOFTWARE IS PROVIDED BY THE COPYRIGHT HOLDERS AND CONTRIBUTORS "AS IS" AND ANY 
 * EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT LIMITED TO, THE IMPLIED WARRANTIES OF
 * MERCHANTABILITY AND FITNESS FOR A PARTICULAR PURPOSE ARE DISCLAIMED. IN NO EVENT SHALL THE
 *  COPYRIGHT OWNER OR CONTRIBUTORS BE LIABLE FOR ANY DIRECT, INDIRECT, INCIDENTAL, SPECIAL,
 *  EXEMPLARY, OR CONSEQUENTIAL DAMAGES (INCLUDING, BUT NOT LIMITED TO, PROCUREMENT OF SUBSTITUTE
 *  GOODS OR SERVICES; LOSS OF USE, DATA, OR PROFITS; OR BUSINESS INTERRUPTION) HOWEVER CAUSED 
 * AND ON ANY THEORY OF LIABILITY, WHETHER IN CONTRACT, STRICT LIABILITY, OR TORT (INCLUDING
 *  NEGLIGENCE OR OTHERWISE) ARISING IN ANY WAY OUT OF THE USE OF THIS SOFTWARE, EVEN IF ADVISED 
 * OF THE POSSIBILITY OF SUCH DAMAGE. 
 *
*/

// t: current time, b: begInnIng value, c: change In value, d: duration
jQuery.easing.jswing=jQuery.easing.swing,jQuery.extend(jQuery.easing,{def:"easeOutQuad",swing:function(n,e,t,u,a){return jQuery.easing[jQuery.easing.def](n,e,t,u,a)},easeInQuad:function(n,e,t,u,a){return u*(e/=a)*e+t},easeOutQuad:function(n,e,t,u,a){return-u*(e/=a)*(e-2)+t},easeInOutQuad:function(n,e,t,u,a){return(e/=a/2)<1?u/2*e*e+t:-u/2*(--e*(e-2)-1)+t},easeInCubic:function(n,e,t,u,a){return u*(e/=a)*e*e+t},easeOutCubic:function(n,e,t,u,a){return u*((e=e/a-1)*e*e+1)+t},easeInOutCubic:function(n,e,t,u,a){return(e/=a/2)<1?u/2*e*e*e+t:u/2*((e-=2)*e*e+2)+t},easeInQuart:function(n,e,t,u,a){return u*(e/=a)*e*e*e+t},easeOutQuart:function(n,e,t,u,a){return-u*((e=e/a-1)*e*e*e-1)+t},easeInOutQuart:function(n,e,t,u,a){return(e/=a/2)<1?u/2*e*e*e*e+t:-u/2*((e-=2)*e*e*e-2)+t},easeInQuint:function(n,e,t,u,a){return u*(e/=a)*e*e*e*e+t},easeOutQuint:function(n,e,t,u,a){return u*((e=e/a-1)*e*e*e*e+1)+t},easeInOutQuint:function(n,e,t,u,a){return(e/=a/2)<1?u/2*e*e*e*e*e+t:u/2*((e-=2)*e*e*e*e+2)+t},easeInSine:function(n,e,t,u,a){return-u*Math.cos(e/a*(Math.PI/2))+u+t},easeOutSine:function(n,e,t,u,a){return u*Math.sin(e/a*(Math.PI/2))+t},easeInOutSine:function(n,e,t,u,a){return-u/2*(Math.cos(Math.PI*e/a)-1)+t},easeInExpo:function(n,e,t,u,a){return 0==e?t:u*Math.pow(2,10*(e/a-1))+t},easeOutExpo:function(n,e,t,u,a){return e==a?t+u:u*(-Math.pow(2,-10*e/a)+1)+t},easeInOutExpo:function(n,e,t,u,a){return 0==e?t:e==a?t+u:(e/=a/2)<1?u/2*Math.pow(2,10*(e-1))+t:u/2*(-Math.pow(2,-10*--e)+2)+t},easeInCirc:function(n,e,t,u,a){return-u*(Math.sqrt(1-(e/=a)*e)-1)+t},easeOutCirc:function(n,e,t,u,a){return u*Math.sqrt(1-(e=e/a-1)*e)+t},easeInOutCirc:function(n,e,t,u,a){return(e/=a/2)<1?-u/2*(Math.sqrt(1-e*e)-1)+t:u/2*(Math.sqrt(1-(e-=2)*e)+1)+t},easeInElastic:function(n,e,t,u,a){var r=1.70158,i=0,s=u;if(0==e)return t;if(1==(e/=a))return t+u;if(i||(i=.3*a),s<Math.abs(u)){s=u;var r=i/4}else var r=i/(2*Math.PI)*Math.asin(u/s);return-(s*Math.pow(2,10*(e-=1))*Math.sin(2*(e*a-r)*Math.PI/i))+t},easeOutElastic:function(n,e,t,u,a){var r=1.70158,i=0,s=u;if(0==e)return t;if(1==(e/=a))return t+u;if(i||(i=.3*a),s<Math.abs(u)){s=u;var r=i/4}else var r=i/(2*Math.PI)*Math.asin(u/s);return s*Math.pow(2,-10*e)*Math.sin(2*(e*a-r)*Math.PI/i)+u+t},easeInOutElastic:function(n,e,t,u,a){var r=1.70158,i=0,s=u;if(0==e)return t;if(2==(e/=a/2))return t+u;if(i||(i=.3*a*1.5),s<Math.abs(u)){s=u;var r=i/4}else var r=i/(2*Math.PI)*Math.asin(u/s);return 1>e?-.5*s*Math.pow(2,10*(e-=1))*Math.sin(2*(e*a-r)*Math.PI/i)+t:s*Math.pow(2,-10*(e-=1))*Math.sin(2*(e*a-r)*Math.PI/i)*.5+u+t},easeInBack:function(n,e,t,u,a,r){return void 0==r&&(r=1.70158),u*(e/=a)*e*((r+1)*e-r)+t},easeOutBack:function(n,e,t,u,a,r){return void 0==r&&(r=1.70158),u*((e=e/a-1)*e*((r+1)*e+r)+1)+t},easeInOutBack:function(n,e,t,u,a,r){return void 0==r&&(r=1.70158),(e/=a/2)<1?u/2*e*e*(((r*=1.525)+1)*e-r)+t:u/2*((e-=2)*e*(((r*=1.525)+1)*e+r)+2)+t},easeInBounce:function(n,e,t,u,a){return u-jQuery.easing.easeOutBounce(n,a-e,0,u,a)+t},easeOutBounce:function(n,e,t,u,a){return(e/=a)<1/2.75?7.5625*u*e*e+t:2/2.75>e?u*(7.5625*(e-=1.5/2.75)*e+.75)+t:2.5/2.75>e?u*(7.5625*(e-=2.25/2.75)*e+.9375)+t:u*(7.5625*(e-=2.625/2.75)*e+.984375)+t},easeInOutBounce:function(n,e,t,u,a){return a/2>e?.5*jQuery.easing.easeInBounce(n,2*e,0,u,a)+t:.5*jQuery.easing.easeOutBounce(n,2*e-a,0,u,a)+.5*u+t}});


/*!
 * jQuery Transit - CSS3 transitions and transformations
 * (c) 2011-2012 Rico Sta. Cruz <rico@ricostacruz.com>
 * MIT Licensed.
 *
 * http://ricostacruz.com/jquery.transit
 * http://github.com/rstacruz/jquery.transit
 */

/*! 
 * modified for LayerSlider
 */

/*! WOW - v1.1.2 - 2015-04-07
* Copyright (c) 2015 Matthieu Aussaguel; Licensed MIT */(function(){var a,b,c,d,e,f=function(a,b){return function(){return a.apply(b,arguments)}},g=[].indexOf||function(a){for(var b=0,c=this.length;c>b;b++)if(b in this&&this[b]===a)return b;return-1};b=function(){function a(){}return a.prototype.extend=function(a,b){var c,d;for(c in b)d=b[c],null==a[c]&&(a[c]=d);return a},a.prototype.isMobile=function(a){return/Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test(a)},a.prototype.createEvent=function(a,b,c,d){var e;return null==b&&(b=!1),null==c&&(c=!1),null==d&&(d=null),null!=document.createEvent?(e=document.createEvent("CustomEvent"),e.initCustomEvent(a,b,c,d)):null!=document.createEventObject?(e=document.createEventObject(),e.eventType=a):e.eventName=a,e},a.prototype.emitEvent=function(a,b){return null!=a.dispatchEvent?a.dispatchEvent(b):b in(null!=a)?a[b]():"on"+b in(null!=a)?a["on"+b]():void 0},a.prototype.addEvent=function(a,b,c){return null!=a.addEventListener?a.addEventListener(b,c,!1):null!=a.attachEvent?a.attachEvent("on"+b,c):a[b]=c},a.prototype.removeEvent=function(a,b,c){return null!=a.removeEventListener?a.removeEventListener(b,c,!1):null!=a.detachEvent?a.detachEvent("on"+b,c):delete a[b]},a.prototype.innerHeight=function(){return"innerHeight"in window?window.innerHeight:document.documentElement.clientHeight},a}(),c=this.WeakMap||this.MozWeakMap||(c=function(){function a(){this.keys=[],this.values=[]}return a.prototype.get=function(a){var b,c,d,e,f;for(f=this.keys,b=d=0,e=f.length;e>d;b=++d)if(c=f[b],c===a)return this.values[b]},a.prototype.set=function(a,b){var c,d,e,f,g;for(g=this.keys,c=e=0,f=g.length;f>e;c=++e)if(d=g[c],d===a)return void(this.values[c]=b);return this.keys.push(a),this.values.push(b)},a}()),a=this.MutationObserver||this.WebkitMutationObserver||this.MozMutationObserver||(a=function(){function a(){"undefined"!=typeof console&&null!==console&&console.warn("MutationObserver is not supported by your browser."),"undefined"!=typeof console&&null!==console&&console.warn("WOW.js cannot detect dom mutations, please call .sync() after loading new content.")}return a.notSupported=!0,a.prototype.observe=function(){},a}()),d=this.getComputedStyle||function(a){return this.getPropertyValue=function(b){var c;return"float"===b&&(b="styleFloat"),e.test(b)&&b.replace(e,function(a,b){return b.toUpperCase()}),(null!=(c=a.currentStyle)?c[b]:void 0)||null},this},e=/(\-([a-z]){1})/g,this.WOW=function(){function e(a){null==a&&(a={}),this.scrollCallback=f(this.scrollCallback,this),this.scrollHandler=f(this.scrollHandler,this),this.resetAnimation=f(this.resetAnimation,this),this.start=f(this.start,this),this.scrolled=!0,this.config=this.util().extend(a,this.defaults),this.animationNameCache=new c,this.wowEvent=this.util().createEvent(this.config.boxClass)}return e.prototype.defaults={boxClass:"wow",animateClass:"animated",offset:0,mobile:!0,live:!0,callback:null},e.prototype.init=function(){var a;return this.element=window.document.documentElement,"interactive"===(a=document.readyState)||"complete"===a?this.start():this.util().addEvent(document,"DOMContentLoaded",this.start),this.finished=[]},e.prototype.start=function(){var b,c,d,e;if(this.stopped=!1,this.boxes=function(){var a,c,d,e;for(d=this.element.querySelectorAll("."+this.config.boxClass),e=[],a=0,c=d.length;c>a;a++)b=d[a],e.push(b);return e}.call(this),this.all=function(){var a,c,d,e;for(d=this.boxes,e=[],a=0,c=d.length;c>a;a++)b=d[a],e.push(b);return e}.call(this),this.boxes.length)if(this.disabled())this.resetStyle();else for(e=this.boxes,c=0,d=e.length;d>c;c++)b=e[c],this.applyStyle(b,!0);return this.disabled()||(this.util().addEvent(window,"scroll",this.scrollHandler),this.util().addEvent(window,"resize",this.scrollHandler),this.interval=setInterval(this.scrollCallback,50)),this.config.live?new a(function(a){return function(b){var c,d,e,f,g;for(g=[],c=0,d=b.length;d>c;c++)f=b[c],g.push(function(){var a,b,c,d;for(c=f.addedNodes||[],d=[],a=0,b=c.length;b>a;a++)e=c[a],d.push(this.doSync(e));return d}.call(a));return g}}(this)).observe(document.body,{childList:!0,subtree:!0}):void 0},e.prototype.stop=function(){return this.stopped=!0,this.util().removeEvent(window,"scroll",this.scrollHandler),this.util().removeEvent(window,"resize",this.scrollHandler),null!=this.interval?clearInterval(this.interval):void 0},e.prototype.sync=function(){return a.notSupported?this.doSync(this.element):void 0},e.prototype.doSync=function(a){var b,c,d,e,f;if(null==a&&(a=this.element),1===a.nodeType){for(a=a.parentNode||a,e=a.querySelectorAll("."+this.config.boxClass),f=[],c=0,d=e.length;d>c;c++)b=e[c],g.call(this.all,b)<0?(this.boxes.push(b),this.all.push(b),this.stopped||this.disabled()?this.resetStyle():this.applyStyle(b,!0),f.push(this.scrolled=!0)):f.push(void 0);return f}},e.prototype.show=function(a){return this.applyStyle(a),a.className=a.className+" "+this.config.animateClass,null!=this.config.callback&&this.config.callback(a),this.util().emitEvent(a,this.wowEvent),this.util().addEvent(a,"animationend",this.resetAnimation),this.util().addEvent(a,"oanimationend",this.resetAnimation),this.util().addEvent(a,"webkitAnimationEnd",this.resetAnimation),this.util().addEvent(a,"MSAnimationEnd",this.resetAnimation),a},e.prototype.applyStyle=function(a,b){var c,d,e;return d=a.getAttribute("data-wow-duration"),c=a.getAttribute("data-wow-delay"),e=a.getAttribute("data-wow-iteration"),this.animate(function(f){return function(){return f.customStyle(a,b,d,c,e)}}(this))},e.prototype.animate=function(){return"requestAnimationFrame"in window?function(a){return window.requestAnimationFrame(a)}:function(a){return a()}}(),e.prototype.resetStyle=function(){var a,b,c,d,e;for(d=this.boxes,e=[],b=0,c=d.length;c>b;b++)a=d[b],e.push(a.style.visibility="visible");return e},e.prototype.resetAnimation=function(a){var b;return a.type.toLowerCase().indexOf("animationend")>=0?(b=a.target||a.srcElement,b.className=b.className.replace(this.config.animateClass,"").trim()):void 0},e.prototype.customStyle=function(a,b,c,d,e){return b&&this.cacheAnimationName(a),a.style.visibility=b?"hidden":"visible",c&&this.vendorSet(a.style,{animationDuration:c}),d&&this.vendorSet(a.style,{animationDelay:d}),e&&this.vendorSet(a.style,{animationIterationCount:e}),this.vendorSet(a.style,{animationName:b?"none":this.cachedAnimationName(a)}),a},e.prototype.vendors=["moz","webkit"],e.prototype.vendorSet=function(a,b){var c,d,e,f;d=[];for(c in b)e=b[c],a[""+c]=e,d.push(function(){var b,d,g,h;for(g=this.vendors,h=[],b=0,d=g.length;d>b;b++)f=g[b],h.push(a[""+f+c.charAt(0).toUpperCase()+c.substr(1)]=e);return h}.call(this));return d},e.prototype.vendorCSS=function(a,b){var c,e,f,g,h,i;for(h=d(a),g=h.getPropertyCSSValue(b),f=this.vendors,c=0,e=f.length;e>c;c++)i=f[c],g=g||h.getPropertyCSSValue("-"+i+"-"+b);return g},e.prototype.animationName=function(a){var b;try{b=this.vendorCSS(a,"animation-name").cssText}catch(c){b=d(a).getPropertyValue("animation-name")}return"none"===b?"":b},e.prototype.cacheAnimationName=function(a){return this.animationNameCache.set(a,this.animationName(a))},e.prototype.cachedAnimationName=function(a){return this.animationNameCache.get(a)},e.prototype.scrollHandler=function(){return this.scrolled=!0},e.prototype.scrollCallback=function(){var a;return!this.scrolled||(this.scrolled=!1,this.boxes=function(){var b,c,d,e;for(d=this.boxes,e=[],b=0,c=d.length;c>b;b++)a=d[b],a&&(this.isVisible(a)?this.show(a):e.push(a));return e}.call(this),this.boxes.length||this.config.live)?void 0:this.stop()},e.prototype.offsetTop=function(a){for(var b;void 0===a.offsetTop;)a=a.parentNode;for(b=a.offsetTop;a=a.offsetParent;)b+=a.offsetTop;return b},e.prototype.isVisible=function(a){var b,c,d,e,f;return c=a.getAttribute("data-wow-offset")||this.config.offset,f=window.pageYOffset,e=f+Math.min(this.element.clientHeight,this.util().innerHeight())-c,d=this.offsetTop(a),b=d+a.clientHeight,e>=d&&b>=f},e.prototype.util=function(){return null!=this._util?this._util:this._util=new b},e.prototype.disabled=function(){return!this.config.mobile&&this.util().isMobile(navigator.userAgent)},e}()}).call(this);

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




/**
 * @version $Id: slider.js 25 2015-06-29 19:45:38Z szymon $
 * @package DJ-ImageSlider
 * @subpackage DJ-ImageSlider Component
 * @copyright Copyright (C) 2012 DJ-Extensions.com, All rights reserved.
 * @license DJ-Extensions.com Proprietary Use License
 * @author url: http://dj-extensions.com
 * @author email contact@dj-extensions.com
 * @developer Szymon Woronowski - szymon.woronowski@design-joomla.eu
 *
 */
!function($){var F={init:function(m){m.data();var n=m.data('djslider');var o=m.data('animation');m.removeAttr('data-djslider');m.removeAttr('data-animation');var q=$('#djslider'+n.id).css('opacity',0);var r=$('#slider'+n.id).css('position','relative');var t=n.css3=='1'?support('transition'):false;var u=r.children('li');var w=n.slide_size;var x=n.visible_slides;var y=w*u.length;var z=u.length-x;var A=0;var B=o.auto=='1'?1:0;var C=0;var D=false;if(n.slider_type==2){u.css('position','absolute');u.css('top',0);u.css('left',0);r.css('width',w);u.css('opacity',0);u.css('visibility','hidden');$(u[0]).css('opacity',1);$(u[0]).css('visibility','visible');if(t)u.css(t,'opacity '+o.duration+'ms '+o.css3transition)}else if(n.slider_type==1){r.css('top',0);r.css('height',y);if(t)r.css(t,'top '+o.duration+'ms '+o.css3transition)}else{r.css(n.direction,0);r.css('width',y);if(t)r.css(t,n.direction+' '+o.duration+'ms '+o.css3transition)}if(n.show_arrows>0){$('#next'+n.id).on('click',function(){if(n.direction=='right')prevSlide();else nextSlide()});$('#prev'+n.id).on('click',function(){if(n.direction=='right')nextSlide();else prevSlide()})}if(n.show_buttons>0){$('#play'+n.id).on('click',function(){changeNavigation();B=1});$('#pause'+n.id).on('click',function(){changeNavigation();B=0})}m.on('mouseenter',function(){C=1}).on('mouseleave',function(){C=0});m.djswipe(function(a,b){if(b.x<100||b.y>30){return}if(a.x=="left"){if(n.direction=='right')prevSlide();else nextSlide()}else if(a.x=="right"){if(n.direction=='right')nextSlide();else prevSlide()}});if($('#cust-navigation'+n.id).length){var E=$('#cust-navigation'+n.id).find('.load-button');E.each(function(a){var b=$(this);b.on('click',function(e){if(!D&&!b.hasClass('load-button-active')){loadSlide(a)}});if(a>z)b.css('display','none')})}function getSize(a){return{'x':a.width(),'y':a.height()}}function responsive(){var c=m.parent();var d=getSize(c).x;var e=parseInt(q.css('max-width'));var f=getSize(q);var g=f.x;if(g>d){g=d}else if(g<=d&&(!e||g<e)){g=(d>e?e:d)}var h=f.x/f.y;var i=g/h;q.css('width',g);q.css('height',i);if(n.slider_type==2){r.css('width',g);u.css('width',g);u.css('height',i)}else if(n.slider_type==1){var j=parseInt($(u[0]).css('margin-bottom'));w=(i+j)/x;y=u.length*w+u.length;r.css('height',y);u.css('width',g);u.css('height',w-j);r.css('top',-w*A)}else{var j=n.direction=='right'?parseInt($(u[0]).css('margin-left')):parseInt($(u[0]).css('margin-right'));var k=Math.ceil(g/(n.slide_size+j));if(k!=x){x=(k>n.visible_slides?n.visible_slides:k);z=u.length-x;if($('#cust-navigation'+n.id).length){var l=$('#cust-navigation'+n.id).find('.load-button');l.each(function(a){var b=$(this);if(a>z)b.css('display','none');else b.css('display','')})}h=(x*w-j)/f.y;i=g/h;q.css('height',i)}w=(g+j)/x;y=u.length*w+u.length;r.css('width',y);u.css('width',w-j);u.css('height',i);r.css(n.direction,-w*A);if(A>z)loadSlide(z)}if(n.show_buttons>0||n.show_arrows>0){button_pos=$('#navigation'+n.id).position().top;if(button_pos<0){m.css('padding-top',-button_pos);m.css('padding-bottom',0)}else{buttons_height=0;if(n.show_arrows>0){buttons_height=getSize($('#next'+n.id)).y;buttons_height=Math.max(buttons_height,getSize($('#prev'+n.id)).y)}if(n.show_buttons>0){buttons_height=Math.max(buttons_height,getSize($('#play'+n.id)).y);buttons_height=Math.max(buttons_height,getSize($('#pause'+n.id)).y)}padding=button_pos+buttons_height-i;if(padding>0){m.css('padding-top',0);m.css('padding-bottom',padding)}else{m.css('padding-top',0);m.css('padding-bottom',0)}}buttons_margin=parseInt($('#navigation'+n.id).css('margin-left'))+parseInt($('#navigation'+n.id).css('margin-right'));if(buttons_margin<0&&window.getSize().x<getSize($('#navigation'+n.id)).x-buttons_margin){$('#navigation'+n.id).css('margin-left',0);$('#navigation'+n.id).css('margin-right',0)}}}function updateActiveButton(c){if($('#cust-navigation'+n.id).length)E.each(function(a){var b=$(this);b.removeClass('load-button-active');if(a==c)b.addClass('load-button-active')})}function nextSlide(){if(A<z)loadSlide(A+1);else loadSlide(0)}function prevSlide(){if(A>0)loadSlide(A-1);else loadSlide(z)}function loadSlide(a){if(A==a)return;if(n.slider_type==2){if(D)return;D=true;prev_slide=A;A=a;makeFade(prev_slide)}else{A=a;if(n.slider_type==1){if(t){r.css('top',-w*A)}else{r.animate({top:-w*A},o.duration,o.transition)}}else{if(t){r.css(n.direction,-w*A)}else{if(n.direction=='right')r.animate({right:-w*A},o.duration,o.transition);else r.animate({left:-w*A},o.duration,o.transition)}}}updateActiveButton(A)}function makeFade(a){$(u[A]).css('visibility','visible');if(t){$(u[A]).css('opacity',1);$(u[a]).css('opacity',0)}else{$(u[A]).animate({opacity:1},o.duration,o.transition);$(u[a]).animate({opacity:0},o.duration,o.transition)}setTimeout(function(){$(u[a]).css('visibility','hidden');D=false},o.duration)}function changeNavigation(){if(B){$('#pause'+n.id).css('display','none');$('#play'+n.id).css('display','block')}else{$('#play'+n.id).css('display','none');$('#pause'+n.id).css('display','block')}}function slidePlay(){setTimeout(function(){if(B&&!C)nextSlide();slidePlay()},o.delay)}function sliderLoaded(){m.css('background','none');q.css('opacity',1);responsive();if(n.show_buttons>0){play_width=getSize($('#play'+n.id)).x;$('#play'+n.id).css('margin-left',-play_width/2);pause_width=getSize($('#pause'+n.id)).x;$('#pause'+n.id).css('margin-left',-pause_width/2);if(B){$('#play'+n.id).css('display','none')}else{$('#pause'+n.id).css('display','none')}}slidePlay()}function support(p){var b=document.body||document.documentElement,s=b.style;if(typeof s=='undefined')return false;if(typeof s[p]=='string')return p;v=['Moz','Webkit','Khtml','O','ms','Icab'],pu=p.charAt(0).toUpperCase()+p.substr(1);for(var i=0;i<v.length;i++){if(typeof s[v[i]+pu]=='string')return('-'+v[i].toLowerCase()+'-'+p)}return false}if(n.preload)setTimeout(sliderLoaded,n.preload);else $(window).load(sliderLoaded);$(window).on('resize',responsive)}};$.fn.djswipe=function(b){var c=false,originalPosition=null,info=null;$el=$(this);function swipeInfo(a){var x=a.originalEvent.touches[0].pageX,y=a.originalEvent.touches[0].pageY,dx,dy;dx=(x>originalPosition.x)?"right":"left";dy=(y>originalPosition.y)?"down":"up";return{direction:{x:dx,y:dy},offset:{x:Math.abs(x-originalPosition.x),y:Math.abs(originalPosition.y-y)}}}$el.on("touchstart",function(a){c=true;originalPosition={x:a.originalEvent.touches[0].pageX,y:a.originalEvent.touches[0].pageY}});$el.on("touchend",function(){c=false;if(info)b(info.direction,info.offset);originalPosition=null;info=null});$el.on("touchmove",function(a){if(!c){return}info=swipeInfo(a)});return true};$(document).ready(function(){$('[data-djslider]').each(function(){F.init($(this))})})}(jQuery);


// default.js
jQuery.noConflict();
if(typeof(BTLJ)=='undefined') var BTLJ = jQuery;
if(typeof(btTimeOut)=='undefined') var btTimeOut;
if(typeof(requireRemove)=='undefined') var requireRemove = true;

//var autoPos = !('ontouchstart' in window); 
BTLJ(document).ready(function() {
	
	BTLJ('#btl-content').appendTo('body');
	BTLJ(".btl-input #jform_profile_aboutme").attr("cols",21);
	BTLJ('.bt-scroll .btl-buttonsubmit').click(function(){		
		setTimeout(function(){
			if(BTLJ("#btl-registration-error").is(':visible')){
				BTLJ('.bt-scroll').data('jsp').scrollToY(0,true);
			}else{
				var position = BTLJ('.bt-scroll').find('.invalid:first').position();
				if(position) BTLJ('.bt-scroll').data('jsp').scrollToY(position.top-15,true);
			}
		},20);
	})
	//SET POSITION
	if(BTLJ('.btl-dropdown').length){
		setFPosition();
		BTLJ(window).resize(function(){
			setFPosition();
		})
	}
	
/*	BTLJ(btlOpt.LOGIN_TAGS).addClass("btl-modal");
	if(btlOpt.REGISTER_TAGS != ''){
		BTLJ(btlOpt.REGISTER_TAGS).addClass("btl-modal");
	}*/
	if(!typeof(btlOpt)=='undefined'){// added this condition by fred: btlOpt type of error 
   
 
		// Login event
		var elements = '#btl-panel-login';
		if (btlOpt.LOGIN_TAGS) elements += ', ' + btlOpt.LOGIN_TAGS;
		if (btlOpt.MOUSE_EVENT =='click'){ 
			BTLJ(elements).click(function (event) {
					showLoginForm();
					event.preventDefault();
			});	
		}else{
			BTLJ(elements).hover(function () {
					showLoginForm();
			},function(){});
		}
	
	

	// Registration/Profile event
		elements = '#btl-panel-registration';
		if (btlOpt.REGISTER_TAGS) elements += ', ' + btlOpt.REGISTER_TAGS;
		if (btlOpt.MOUSE_EVENT =='click'){ 
			BTLJ(elements).click(function (event) {
				showRegistrationForm();
				event.preventDefault();
			});	
			BTLJ("#btl-panel-profile").click(function(event){
				showProfile();
				event.preventDefault();
			});
		}else{
			BTLJ(elements).hover(function () {
					if(!BTLJ("#btl-integrated").length){
						showRegistrationForm();
					}
			},function(){});
			BTLJ("#btl-panel-profile").hover(function () {
					showProfile();
			},function(){});
		}
		
	}; // end of added code
	
	
	BTLJ('#register-link a').click(function (event) {
			if(BTLJ('.btl-modal').length){
				BTLJ.modal.close();setTimeout("showRegistrationForm();",1000);
			}
			else{
				showRegistrationForm();
			}
			event.preventDefault();
	});	
	
	// Close form
	BTLJ(document).click(function(event){
		if(requireRemove && event.which == 1) btTimeOut = setTimeout('BTLJ("#btl-content > div").slideUp();BTLJ(".btl-panel span").removeClass("active");',10);
		requireRemove =true;
	})
	BTLJ(".btl-content-block").click(function(){requireRemove =false;});	
	BTLJ(".btl-panel span").click(function(){requireRemove =false;});	
	
	// Modify iframe
	BTLJ('#btl-iframe').load(function (){
		//edit action form	
		oldAction=BTLJ('#btl-iframe').contents().find('form').attr("action");
		if(oldAction!=null){
			if(oldAction.search("tmpl=component")==-1){
				if(BTLJ('#btl-iframe').contents().find('form').attr("action").indexOf('?')!=-1){	
					BTLJ('#btl-iframe').contents().find('form').attr("action",oldAction+"&tmpl=component");
				}
				else{
					BTLJ('#btl-iframe').contents().find('form').attr("action",oldAction+"?tmpl=component");
				}
			}
		}
	});	
	
	//reload captcha click event
	BTLJ('span#btl-captcha-reload').click(function(){
		BTLJ.ajax({
						type: "post",
						url: btlOpt.BT_AJAX,
						data: 'bttask=reload_captcha',
						success: function(html){
							BTLJ('#recaptcha img').attr('src', html);
						}
					});
	});

});

function setFPosition(){
	/*if(btlOpt.ALIGN == "center"){
		BTLJ("#btl-content > div").each(function(){
			var panelid = "#"+this.id.replace("content","panel");
			var left = BTLJ(panelid).offset().left + BTLJ(panelid).width()/2 - BTLJ(this).width()/2;
			if(left < 0) left = 0;
			BTLJ(this).css('left',left);
		});
	}else{
		if(btlOpt.ALIGN == "right"){
			BTLJ("#btl-content > div").css('right',BTLJ(document).width()-BTLJ('.btl-panel').offset().left-BTLJ('.btl-panel').width());
		}else{
			BTLJ("#btl-content > div").css('left',BTLJ('.btl-panel').offset().left);
		}
	}	
	BTLJ("#btl-content > div").css('top',BTLJ(".btl-panel").offset().top+BTLJ(".btl-panel").height()+2);*/	
}

// SHOW LOGIN FORM
function showLoginForm(){
	BTLJ('.btl-panel span').removeClass("active");
	var el = '#btl-panel-login';
	if (btlOpt.LOGIN_TAGS) el += ', ' + btlOpt.LOGIN_TAGS;
	BTLJ.modal.close();
	var containerWidth = 0;
	var containerHeight = 0;
	containerHeight = 371;
	containerWidth = 357;
	
	if(containerWidth>BTLJ(window).width()){
		containerWidth = BTLJ(window).width()-50;
	}
	if(BTLJ(el).hasClass("btl-modal")){
		BTLJ(el).addClass("active");
		BTLJ("#btl-content > div").slideUp();
		BTLJ("#btl-content-login").modal({
			overlayClose:true,
			persist :true,
			autoPosition:true,
			fixed: BTLJ(window).width()>500,
			onOpen: function (dialog) {
				dialog.overlay.fadeIn();
				dialog.container.show();
				dialog.data.show();		
			},
			onClose: function (dialog) {
				dialog.overlay.fadeOut(function () {
					dialog.container.hide();
					dialog.data.hide();		
					BTLJ.modal.close();
					BTLJ('.btl-panel span').removeClass("active");
				});
			},
			containerCss:{
				height:containerHeight,
				width:containerWidth
			}
		})			 
	}
	else
	{	
		setFPosition();
		BTLJ("#btl-content > div").each(function(){
			if(this.id=="btl-content-login")
			{
				if(BTLJ(this).is(":hidden")){
					BTLJ(el).addClass("active");
					BTLJ(this).slideDown();
					}
				else{
					BTLJ(this).slideUp();
					BTLJ(el).removeClass("active");
				}						
					
			}
			else{
				if(BTLJ(this).is(":visible")){						
					BTLJ(this).slideUp();
					BTLJ('#btl-panel-registration').removeClass("active");
				}
			}
			
		})
	}
}

// SHOW REGISTRATION FORM
function showRegistrationForm(){
	if(BTLJ("#btl-integrated").length){
		window.location.href=BTLJ("#btl-integrated").val();
		return;
	}
	BTLJ('.btl-panel span').removeClass("active");
	BTLJ.modal.close();
	var el = '#btl-panel-registration';
	var containerWidth = 0;
	var containerHeight = 0;
	containerHeight = "auto";
	containerWidth = "auto";
	if(containerWidth>BTLJ(window).width()){
		containerWidth = BTLJ(window).width();
	}
	if(BTLJ(el).hasClass("btl-modal")){
		BTLJ(el).addClass("active");
		BTLJ("#btl-content > div").slideUp();
		BTLJ("#btl-content-registration").modal({
			overlayClose:true,
			persist :true,
			autoPosition:true,
			fixed: BTLJ(window).width()>500,
			onOpen: function (dialog) {
				dialog.overlay.fadeIn();
				dialog.container.show();
				dialog.data.show();		
			},
			onClose: function (dialog) {
				dialog.overlay.fadeOut(function () {
					dialog.container.hide();
					dialog.data.hide();		
					BTLJ.modal.close();
					BTLJ('.btl-panel span').removeClass("active");
				});
			},
			containerCss:{
				height:containerHeight,
				width:containerWidth
			}
		})
	}
	else
	{	
		setFPosition();
		BTLJ("#btl-content > div").each(function(){
			if(this.id=="btl-content-registration")
			{
				if(BTLJ(this).is(":hidden")){
					BTLJ(el).addClass("active");
					BTLJ(this).slideDown();
					}
				else{
					BTLJ(this).slideUp();								
					BTLJ(el).removeClass("active");
					}
			}
			else{
				if(BTLJ(this).is(":visible")){						
					BTLJ(this).slideUp();
					BTLJ('#btl-panel-login').removeClass("active");
				}
			}
			
		})
	}
}

// SHOW PROFILE (LOGGED MODULES)
function showProfile(){
	setFPosition();
	var el = '#btl-panel-profile';
	BTLJ("#btl-content > div").each(function(){
		if(this.id=="btl-content-profile")
		{
			if(BTLJ(this).is(":hidden")){
				BTLJ(el).addClass("active");
				BTLJ(this).slideDown();
				}
			else{
				BTLJ(this).slideUp();	
				BTLJ('.btl-panel span').removeClass("active");
			}				
		}
		else{
			if(BTLJ(this).is(":visible")){						
				BTLJ(this).slideUp();
				BTLJ('.btl-panel span').removeClass("active");	
			}
		}
		
	})
}

// AJAX REGISTRATION
function registerAjax(){
	BTLJ("#btl-registration-error").hide();
	 BTLJ(".btl-error-detail").hide();
	if(BTLJ("#btl-input-name").val()==""){
		BTLJ("#btl-registration-error").html(Joomla.JText._('REQUIRED_NAME')).show();
		BTLJ("#btl-input-name").focus();
		return false;
	}
	if(BTLJ("#btl-input-username1").val()==""){
		BTLJ("#btl-registration-error").html(Joomla.JText._('REQUIRED_USERNAME')).show();
		BTLJ("#btl-input-username1").focus();
		return false;
	}
	if(BTLJ("#btl-input-password1").val()==""){
		BTLJ("#btl-registration-error").html(Joomla.JText._('REQUIRED_PASSWORD')).show();
		BTLJ("#btl-input-password1").focus();
		return false;
	}
	if(BTLJ("#btl-input-password2").val()==""){
		BTLJ("#btl-registration-error").html(Joomla.JText._('REQUIRED_VERIFY_PASSWORD')).show();
		BTLJ("#btl-input-password2").focus();
		return false;
	}
	if(BTLJ("#btl-input-password2").val()!=BTLJ("#btl-input-password1").val()){
		BTLJ("#btl-registration-error").html(Joomla.JText._('PASSWORD_NOT_MATCH')).show();
		BTLJ("#btl-input-password2").focus().select();
		BTLJ("#btl-registration-error").show();
		return false;
	}
	if(BTLJ("#btl-input-email1").val()==""){
		BTLJ("#btl-registration-error").html(Joomla.JText._('REQUIRED_EMAIL')).show();
		BTLJ("#btl-input-email1").focus();
		return false;
	}
	var emailRegExp = /^[_a-zA-Z0-9-]+(\.[_a-zA-Z0-9-]+)*@[a-zA-Z0-9-]+(\.[a-zA-Z0-9-]+)*(\.([a-zA-Z]){2,4})$/;
	if(!emailRegExp.test(BTLJ("#btl-input-email1").val())){		
		BTLJ("#btl-registration-error").html(Joomla.JText._('EMAIL_INVALID')).show();
		BTLJ("#btl-input-email1").focus().select();
		return false;
	}
	if(BTLJ("#btl-input-email2").val()==""){
		BTLJ("#btl-registration-error").html(Joomla.JText._('REQUIRED_VERIFY_EMAIL')).show();
		BTLJ("#btl-input-email2").focus().select();
		return false;
	}
	if(BTLJ("#btl-input-email1").val()!=BTLJ("#btl-input-email2").val()){
		BTLJ("#btl-registration-error").html(Joomla.JText._('EMAIL_NOT_MATCH')).show();;
		BTLJ("#btl-input-email2").focus().select();
		return false;
	}
	if(btlOpt.RECAPTCHA =="recaptcha"){
		if(BTLJ('#recaptcha_response_field').length && BTLJ('#recaptcha_response_field').val()==''){
			BTLJ("#btl-registration-error").html(Joomla.JText._('CAPTCHA_REQUIRED')).show();
			BTLJ('#recaptcha_response_field').focus();
			return false;
		}
	}else if(btlOpt.RECAPTCHA =="2"){
		if(BTLJ('#btl-captcha').length && BTLJ('#btl-captcha').val()==''){
			BTLJ("#btl-registration-error").html(Joomla.JText._('CAPTCHA_REQUIRED')).show();
			BTLJ('#btl-captcha').focus();
			return false;
		}	
	}	
	 
	var token = BTLJ('.btl-buttonsubmit input:last').attr("name");
	var value_token = encodeURIComponent(BTLJ('.btl-buttonsubmit input:last').val()); 
	var datasubmit= "bttask=register&name="+encodeURIComponent(BTLJ("#btl-input-name").val())
			+"&username="+encodeURIComponent(BTLJ("#btl-input-username1").val())
			+"&passwd1=" + encodeURIComponent(BTLJ("#btl-input-password1").val())
			+"&passwd2=" + encodeURIComponent(BTLJ("#btl-input-password2").val())
			+"&email1=" + encodeURIComponent(BTLJ("#btl-input-email1").val())
			+"&email2=" + encodeURIComponent(BTLJ("#btl-input-email2").val())					
			+ "&"+token+"="+value_token;
	if(btlOpt.RECAPTCHA =="recaptcha"){
		datasubmit  += "&recaptcha=yes&recaptcha_response_field="+ encodeURIComponent(BTLJ("#recaptcha_response_field").val())
					+"&recaptcha_challenge_field="+encodeURIComponent(BTLJ("#recaptcha_challenge_field").val());
	}else if(btlOpt.RECAPTCHA =="2"){
		datasubmit  += "&recaptcha=yes&btl_captcha="+ encodeURIComponent(BTLJ("#btl-captcha").val());
	}
	
	BTLJ.ajax({
		   type: "POST",
		   beforeSend:function(){
			   BTLJ("#btl-register-in-process").show();			   
		   },
		   url: btlOpt.BT_AJAX,
		   data: datasubmit,
		   success: function(html){				  
			   //if html contain "Registration failed" is register fail
			  BTLJ("#btl-register-in-process").hide();	
			  if(html.indexOf('$error$')!= -1){
				  BTLJ("#btl-registration-error").html(html.replace('$error$',''));  
				  BTLJ("#btl-registration-error").show();
				  if(btlOpt.RECAPTCHA =="recaptcha"){
					  Recaptcha.reload();
				  }else if(btlOpt.RECAPTCHA =="2"){
					BTLJ.ajax({
						type: "post",
						url: btlOpt.BT_AJAX,
						data: 'bttask=reload_captcha',
						success: function(html){
							BTLJ('#recaptcha img').attr('src', html);
						}
					});
				  }
				  
			   }else{				   
				   BTLJ(".btl-formregistration").children("div").hide();
				   BTLJ("#btl-success").html(html);	
				   BTLJ("#btl-success").show();	
				   setTimeout(function() {window.location.reload();},7000);

			   }
		   },
		   error: function (XMLHttpRequest, textStatus, errorThrown) {
				alert(textStatus + ': Ajax request failed');
		   }
		});
		return false;
}

// AJAX LOGIN
function loginAjax(){
	if(BTLJ("#btl-input-username").val()=="") {
		showLoginError(Joomla.JText._('REQUIRED_USERNAME'));
		return false;
	}
	if(BTLJ("#btl-input-password").val()==""){
		showLoginError(Joomla.JText._('REQUIRED_PASSWORD'));
		return false;
	}
	var token = BTLJ('.btl-buttonsubmit input:last').attr("name");
	var value_token = encodeURIComponent(BTLJ('.btl-buttonsubmit input:last').val()); 
	var datasubmit= "bttask=login&username="+encodeURIComponent(BTLJ("#btl-input-username").val())
	+"&passwd=" + encodeURIComponent(BTLJ("#btl-input-password").val())
	+ "&"+token+"="+value_token
	+"&return="+ encodeURIComponent(BTLJ("#btl-return").val());
	
	if(BTLJ("#btl-checkbox-remember").is(":checked")){
		datasubmit += '&remember=yes';
	}
	
	BTLJ.ajax({
	   type: "POST",
	   beforeSend:function(){
		   BTLJ("#btl-login-in-process").show();
		   BTLJ("#btl-login-in-process").css('height',BTLJ('#btl-content-login').outerHeight()+'px');
		   
	   },
	   url: btlOpt.BT_AJAX,
	   data: datasubmit,
	   success: function (html, textstatus, xhrReq){
		  if(html == "1" || html == 1){
			   window.location.href=btlOpt.BT_RETURN;
		   }else{
			   if(html.indexOf('</head>')==-1){		   
				   showLoginError(Joomla.JText._('E_LOGIN_AUTHENTICATE'));
				}
				else
				{
					if(html.indexOf('btl-panel-profile')==-1){ 
						showLoginError('Another plugin has redirected the page on login, Please check your plugins system');
					}
					else
					{
						window.location.href=btlOpt.BT_RETURN;
					}
				}
		   }
	   },
	   error: function (XMLHttpRequest, textStatus, errorThrown) {
			alert(textStatus + ': Ajax request failed!');
	   }
	});
	return false;
}
function showLoginError(notice,reload){
	BTLJ("#btl-login-in-process").hide();
	BTLJ("#btl-login-error").html(notice);
	BTLJ("#btl-login-error").show();
	if(reload){
		setTimeout(function() {window.location.reload();},5000);
	}
}

/*
 *
 * More info at [www.dropzonejs.com](http://www.dropzonejs.com)
 *
 * Copyright (c) 2012, Matias Meno
 *
 * Permission is hereby granted, free of charge, to any person obtaining a copy
 * of this software and associated documentation files (the "Software"), to deal
 * in the Software without restriction, including without limitation the rights
 * to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 * copies of the Software, and to permit persons to whom the Software is
 * furnished to do so, subject to the following conditions:
 *
 * The above copyright notice and this permission notice shall be included in
 * all copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
 * THE SOFTWARE.
 *
 */
 
(function(){var e,t,i,n,r,o,s,l,a=[].slice,u={}.hasOwnProperty,p=function(e,t){function i(){this.constructor=e}for(var n in t)u.call(t,n)&&(e[n]=t[n]);return i.prototype=t.prototype,e.prototype=new i,e.__super__=t.prototype,e};s=function(){},t=function(){function e(){}return e.prototype.addEventListener=e.prototype.on,e.prototype.on=function(e,t){return this._callbacks=this._callbacks||{},this._callbacks[e]||(this._callbacks[e]=[]),this._callbacks[e].push(t),this},e.prototype.emit=function(){var e,t,i,n,r,o;if(n=arguments[0],e=2<=arguments.length?a.call(arguments,1):[],this._callbacks=this._callbacks||{},i=this._callbacks[n])for(r=0,o=i.length;o>r;r++)t=i[r],t.apply(this,e);return this},e.prototype.removeListener=e.prototype.off,e.prototype.removeAllListeners=e.prototype.off,e.prototype.removeEventListener=e.prototype.off,e.prototype.off=function(e,t){var i,n,r,o,s;if(!this._callbacks||0===arguments.length)return this._callbacks={},this;if(n=this._callbacks[e],!n)return this;if(1===arguments.length)return delete this._callbacks[e],this;for(r=o=0,s=n.length;s>o;r=++o)if(i=n[r],i===t){n.splice(r,1);break}return this},e}(),e=function(e){function i(e,t){var r,o,s;if(this.element=e,this.version=i.version,this.defaultOptions.previewTemplate=this.defaultOptions.previewTemplate.replace(/\n*/g,""),this.clickableElements=[],this.listeners=[],this.files=[],"string"==typeof this.element&&(this.element=document.querySelector(this.element)),!this.element||null==this.element.nodeType)throw new Error("Invalid dropzone element.");if(this.element.dropzone)throw new Error("Dropzone already attached.");if(i.instances.push(this),this.element.dropzone=this,r=null!=(s=i.optionsForElement(this.element))?s:{},this.options=n({},this.defaultOptions,r,null!=t?t:{}),this.options.forceFallback||!i.isBrowserSupported())return this.options.fallback.call(this);if(null==this.options.url&&(this.options.url=this.element.getAttribute("action")),!this.options.url)throw new Error("No URL provided.");if(this.options.acceptedFiles&&this.options.acceptedMimeTypes)throw new Error("You can't provide both 'acceptedFiles' and 'acceptedMimeTypes'. 'acceptedMimeTypes' is deprecated.");this.options.acceptedMimeTypes&&(this.options.acceptedFiles=this.options.acceptedMimeTypes,delete this.options.acceptedMimeTypes),this.options.method=this.options.method.toUpperCase(),(o=this.getExistingFallback())&&o.parentNode&&o.parentNode.removeChild(o),this.options.previewsContainer!==!1&&(this.previewsContainer=this.options.previewsContainer?i.getElement(this.options.previewsContainer,"previewsContainer"):this.element),this.options.clickable&&(this.clickableElements=this.options.clickable===!0?[this.element]:i.getElements(this.options.clickable,"clickable")),this.init()}var n,r;return p(i,e),i.prototype.Emitter=t,i.prototype.events=["drop","dragstart","dragend","dragenter","dragover","dragleave","addedfile","removedfile","thumbnail","error","errormultiple","processing","processingmultiple","uploadprogress","totaluploadprogress","sending","sendingmultiple","success","successmultiple","canceled","canceledmultiple","complete","completemultiple","reset","maxfilesexceeded","maxfilesreached","queuecomplete"],i.prototype.defaultOptions={url:"index.php",method:"post",withCredentials:!1,parallelUploads:2,uploadMultiple:!1,maxFilesize:256,paramName:"osm_avatar",createImageThumbnails:!0,maxThumbnailFilesize:10,thumbnailWidth:120,thumbnailHeight:120,filesizeBase:1e3,maxFiles:null,filesizeBase:1e3,params:{},clickable:!0,ignoreHiddenFiles:!0,acceptedFiles:null,acceptedMimeTypes:null,autoProcessQueue:!0,autoQueue:!0,addRemoveLinks:!1,previewsContainer:null,capture:null,dictDefaultMessage:"Drop your new photo here",dictFallbackMessage:"Your browser does not support drag'n'drop file uploads.",dictFallbackText:"Please use the fallback form below to upload your files like in the olden days.",dictFileTooBig:"File is too big ({{filesize}}MiB). Max filesize: {{maxFilesize}}MiB.",dictInvalidFileType:"You can't upload files of this type.",dictResponseError:"Server responded with {{statusCode}} code.",dictCancelUpload:"Cancel upload",dictCancelUploadConfirmation:"Are you sure you want to cancel this upload?",dictRemoveFile:"Remove file",dictRemoveFileConfirmation:null,dictMaxFilesExceeded:"You can not upload any more files.",accept:function(e,t){return t()},init:function(){return s},forceFallback:!1,fallback:function(){var e,t,n,r,o,s;for(this.element.className=""+this.element.className+" dz-browser-not-supported",s=this.element.getElementsByTagName("div"),r=0,o=s.length;o>r;r++)e=s[r],/(^| )dz-message($| )/.test(e.className)&&(t=e,e.className="dz-message");return t||(t=i.createElement('<div class="dz-message"><span></span></div>'),this.element.appendChild(t)),n=t.getElementsByTagName("span")[0],n&&(n.textContent=this.options.dictFallbackMessage),this.element.appendChild(this.getFallbackForm())},resize:function(e){var t,i,n;return t={srcX:0,srcY:0,srcWidth:e.width,srcHeight:e.height},i=e.width/e.height,t.optWidth=this.options.thumbnailWidth,t.optHeight=this.options.thumbnailHeight,null==t.optWidth&&null==t.optHeight?(t.optWidth=t.srcWidth,t.optHeight=t.srcHeight):null==t.optWidth?t.optWidth=i*t.optHeight:null==t.optHeight&&(t.optHeight=1/i*t.optWidth),n=t.optWidth/t.optHeight,e.height<t.optHeight||e.width<t.optWidth?(t.trgHeight=t.srcHeight,t.trgWidth=t.srcWidth):i>n?(t.srcHeight=e.height,t.srcWidth=t.srcHeight*n):(t.srcWidth=e.width,t.srcHeight=t.srcWidth/n),t.srcX=(e.width-t.srcWidth)/2,t.srcY=(e.height-t.srcHeight)/2,t},drop:function(){return this.element.classList.remove("dz-drag-hover")},dragstart:s,dragend:function(){return this.element.classList.remove("dz-drag-hover")},dragenter:function(){return this.element.classList.add("dz-drag-hover")},dragover:function(){return this.element.classList.add("dz-drag-hover")},dragleave:function(){return this.element.classList.remove("dz-drag-hover")},paste:s,reset:function(){return this.element.classList.remove("dz-started")},addedfile:function(e){var t,n,r,o,s,l,a,u,p,d,c,h,m;if(this.element===this.previewsContainer&&this.element.classList.add("dz-started"),this.previewsContainer){for(e.previewElement=i.createElement(this.options.previewTemplate.trim()),e.previewTemplate=e.previewElement,this.previewsContainer.appendChild(e.previewElement),d=e.previewElement.querySelectorAll("[data-dz-name]"),o=0,a=d.length;a>o;o++)t=d[o],t.textContent=e.name;for(c=e.previewElement.querySelectorAll("[data-dz-size]"),s=0,u=c.length;u>s;s++)t=c[s],t.innerHTML=this.filesize(e.size);for(this.options.addRemoveLinks&&(e._removeLink=i.createElement('<a class="dz-remove" href="javascript:undefined;" data-dz-remove>'+this.options.dictRemoveFile+"</a>"),e.previewElement.appendChild(e._removeLink)),n=function(t){return function(n){return n.preventDefault(),n.stopPropagation(),e.status===i.UPLOADING?i.confirm(t.options.dictCancelUploadConfirmation,function(){return t.removeFile(e)}):t.options.dictRemoveFileConfirmation?i.confirm(t.options.dictRemoveFileConfirmation,function(){return t.removeFile(e)}):t.removeFile(e)}}(this),h=e.previewElement.querySelectorAll("[data-dz-remove]"),m=[],l=0,p=h.length;p>l;l++)r=h[l],m.push(r.addEventListener("click",n));return m}},removedfile:function(e){var t;return e.previewElement&&null!=(t=e.previewElement)&&t.parentNode.removeChild(e.previewElement),this._updateMaxFilesReachedClass()},thumbnail:function(e,t){var i,n,r,o;if(e.previewElement){for(e.previewElement.classList.remove("dz-file-preview"),o=e.previewElement.querySelectorAll("[data-dz-thumbnail]"),n=0,r=o.length;r>n;n++)i=o[n],i.alt=e.name,i.src=t;return setTimeout(function(){return function(){return e.previewElement.classList.add("dz-image-preview")}}(this),1)}},error:function(e,t){var i,n,r,o,s;if(e.previewElement){for(e.previewElement.classList.add("dz-error"),"String"!=typeof t&&t.error&&(t=t.error),o=e.previewElement.querySelectorAll("[data-dz-errormessage]"),s=[],n=0,r=o.length;r>n;n++)i=o[n],s.push(i.textContent=t);return s}},errormultiple:s,processing:function(e){return e.previewElement&&(e.previewElement.classList.add("dz-processing"),e._removeLink)?e._removeLink.textContent=this.options.dictCancelUpload:void 0},processingmultiple:s,uploadprogress:function(e,t){var i,n,r,o,s;if(e.previewElement){for(o=e.previewElement.querySelectorAll("[data-dz-uploadprogress]"),s=[],n=0,r=o.length;r>n;n++)i=o[n],s.push("PROGRESS"===i.nodeName?i.value=t:i.style.width=""+t+"%");return s}},totaluploadprogress:s,sending:s,sendingmultiple:s,success:function(e){return e.previewElement?e.previewElement.classList.add("dz-success"):void 0},successmultiple:s,canceled:function(e){return this.emit("error",e,"Upload canceled.")},canceledmultiple:s,complete:function(e){return e._removeLink&&(e._removeLink.textContent=this.options.dictRemoveFile),e.previewElement?e.previewElement.classList.add("dz-complete"):void 0},completemultiple:s,maxfilesexceeded:s,maxfilesreached:s,queuecomplete:s,previewTemplate:'<div class="dz-preview dz-file-preview">\n  <div class="dz-image"><img data-dz-thumbnail /></div>\n  <div class="dz-details">\n    <div class="dz-size"><span data-dz-size></span></div>\n    <div class="dz-filename"><span data-dz-name></span></div>\n  </div>\n  <div class="dz-progress"><span class="dz-upload" data-dz-uploadprogress></span></div>\n  <div class="dz-error-message"><span data-dz-errormessage></span></div>\n  <div class="dz-success-mark">\n    <svg width="54px" height="54px" viewBox="0 0 54 54" version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" xmlns:sketch="http://www.bohemiancoding.com/sketch/ns">\n      <title>Check</title>\n      <defs></defs>\n      <g id="Page-1" stroke="none" stroke-width="1" fill="none" fill-rule="evenodd" sketch:type="MSPage">\n        <path d="M23.5,31.8431458 L17.5852419,25.9283877 C16.0248253,24.3679711 13.4910294,24.366835 11.9289322,25.9289322 C10.3700136,27.4878508 10.3665912,30.0234455 11.9283877,31.5852419 L20.4147581,40.0716123 C20.5133999,40.1702541 20.6159315,40.2626649 20.7218615,40.3488435 C22.2835669,41.8725651 24.794234,41.8626202 26.3461564,40.3106978 L43.3106978,23.3461564 C44.8771021,21.7797521 44.8758057,19.2483887 43.3137085,17.6862915 C41.7547899,16.1273729 39.2176035,16.1255422 37.6538436,17.6893022 L23.5,31.8431458 Z M27,53 C41.3594035,53 53,41.3594035 53,27 C53,12.6405965 41.3594035,1 27,1 C12.6405965,1 1,12.6405965 1,27 C1,41.3594035 12.6405965,53 27,53 Z" id="Oval-2" stroke-opacity="0.198794158" stroke="#747474" fill-opacity="0.816519475" fill="#FFFFFF" sketch:type="MSShapeGroup"></path>\n      </g>\n    </svg>\n  </div>\n  <div class="dz-error-mark">\n    <svg width="54px" height="54px" viewBox="0 0 54 54" version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" xmlns:sketch="http://www.bohemiancoding.com/sketch/ns">\n      <title>Error</title>\n      <defs></defs>\n      <g id="Page-1" stroke="none" stroke-width="1" fill="none" fill-rule="evenodd" sketch:type="MSPage">\n        <g id="Check-+-Oval-2" sketch:type="MSLayerGroup" stroke="#747474" stroke-opacity="0.198794158" fill="#FFFFFF" fill-opacity="0.816519475">\n          <path d="M32.6568542,29 L38.3106978,23.3461564 C39.8771021,21.7797521 39.8758057,19.2483887 38.3137085,17.6862915 C36.7547899,16.1273729 34.2176035,16.1255422 32.6538436,17.6893022 L27,23.3431458 L21.3461564,17.6893022 C19.7823965,16.1255422 17.2452101,16.1273729 15.6862915,17.6862915 C14.1241943,19.2483887 14.1228979,21.7797521 15.6893022,23.3461564 L21.3431458,29 L15.6893022,34.6538436 C14.1228979,36.2202479 14.1241943,38.7516113 15.6862915,40.3137085 C17.2452101,41.8726271 19.7823965,41.8744578 21.3461564,40.3106978 L27,34.6568542 L32.6538436,40.3106978 C34.2176035,41.8744578 36.7547899,41.8726271 38.3137085,40.3137085 C39.8758057,38.7516113 39.8771021,36.2202479 38.3106978,34.6538436 L32.6568542,29 Z M27,53 C41.3594035,53 53,41.3594035 53,27 C53,12.6405965 41.3594035,1 27,1 C12.6405965,1 1,12.6405965 1,27 C1,41.3594035 12.6405965,53 27,53 Z" id="Oval-2" sketch:type="MSShapeGroup"></path>\n        </g>\n      </g>\n    </svg>\n  </div>\n</div>'},n=function(){var e,t,i,n,r,o,s;for(n=arguments[0],i=2<=arguments.length?a.call(arguments,1):[],o=0,s=i.length;s>o;o++){t=i[o];for(e in t)r=t[e],n[e]=r}return n},i.prototype.getAcceptedFiles=function(){var e,t,i,n,r;for(n=this.files,r=[],t=0,i=n.length;i>t;t++)e=n[t],e.accepted&&r.push(e);return r},i.prototype.getRejectedFiles=function(){var e,t,i,n,r;for(n=this.files,r=[],t=0,i=n.length;i>t;t++)e=n[t],e.accepted||r.push(e);return r},i.prototype.getFilesWithStatus=function(e){var t,i,n,r,o;for(r=this.files,o=[],i=0,n=r.length;n>i;i++)t=r[i],t.status===e&&o.push(t);return o},i.prototype.getQueuedFiles=function(){return this.getFilesWithStatus(i.QUEUED)},i.prototype.getUploadingFiles=function(){return this.getFilesWithStatus(i.UPLOADING)},i.prototype.getActiveFiles=function(){var e,t,n,r,o;for(r=this.files,o=[],t=0,n=r.length;n>t;t++)e=r[t],(e.status===i.UPLOADING||e.status===i.QUEUED)&&o.push(e);return o},i.prototype.init=function(){var e,t,n,r,o,s,l;for("form"===this.element.tagName&&this.element.setAttribute("enctype","multipart/form-data"),this.element.classList.contains("dropzone")&&!this.element.querySelector(".dz-message")&&this.element.appendChild(i.createElement('<div class="dz-default dz-message"><span>'+this.options.dictDefaultMessage+"</span></div>")),this.clickableElements.length&&(n=function(e){return function(){return e.hiddenFileInput&&document.body.removeChild(e.hiddenFileInput),e.hiddenFileInput=document.createElement("input"),e.hiddenFileInput.setAttribute("type","file"),(null==e.options.maxFiles||e.options.maxFiles>1)&&e.hiddenFileInput.setAttribute("multiple","multiple"),e.hiddenFileInput.className="dz-hidden-input",null!=e.options.acceptedFiles&&e.hiddenFileInput.setAttribute("accept",e.options.acceptedFiles),null!=e.options.capture&&e.hiddenFileInput.setAttribute("capture",e.options.capture),e.hiddenFileInput.style.visibility="hidden",e.hiddenFileInput.style.position="absolute",e.hiddenFileInput.style.top="0",e.hiddenFileInput.style.left="0",e.hiddenFileInput.style.height="0",e.hiddenFileInput.style.width="0",document.body.appendChild(e.hiddenFileInput),e.hiddenFileInput.addEventListener("change",function(){var t,i,r,o;if(i=e.hiddenFileInput.files,i.length)for(r=0,o=i.length;o>r;r++)t=i[r],e.addFile(t);return n()})}}(this))(),this.URL=null!=(s=window.URL)?s:window.webkitURL,l=this.events,r=0,o=l.length;o>r;r++)e=l[r],this.on(e,this.options[e]);return this.on("uploadprogress",function(e){return function(){return e.updateTotalUploadProgress()}}(this)),this.on("removedfile",function(e){return function(){return e.updateTotalUploadProgress()}}(this)),this.on("canceled",function(e){return function(t){return e.emit("complete",t)}}(this)),this.on("complete",function(e){return function(){return 0===e.getUploadingFiles().length&&0===e.getQueuedFiles().length?setTimeout(function(){return e.emit("queuecomplete")},0):void 0}}(this)),t=function(e){return e.stopPropagation(),e.preventDefault?e.preventDefault():e.returnValue=!1},this.listeners=[{element:this.element,events:{dragstart:function(e){return function(t){return e.emit("dragstart",t)}}(this),dragenter:function(e){return function(i){return t(i),e.emit("dragenter",i)}}(this),dragover:function(e){return function(i){var n;try{n=i.dataTransfer.effectAllowed}catch(r){}return i.dataTransfer.dropEffect="move"===n||"linkMove"===n?"move":"copy",t(i),e.emit("dragover",i)}}(this),dragleave:function(e){return function(t){return e.emit("dragleave",t)}}(this),drop:function(e){return function(i){return t(i),e.drop(i)}}(this),dragend:function(e){return function(t){return e.emit("dragend",t)}}(this)}}],this.clickableElements.forEach(function(e){return function(t){return e.listeners.push({element:t,events:{click:function(n){return t!==e.element||n.target===e.element||i.elementInside(n.target,e.element.querySelector(".dz-message"))?e.hiddenFileInput.click():void 0}}})}}(this)),this.enable(),this.options.init.call(this)},i.prototype.destroy=function(){var e;return this.disable(),this.removeAllFiles(!0),(null!=(e=this.hiddenFileInput)?e.parentNode:void 0)&&(this.hiddenFileInput.parentNode.removeChild(this.hiddenFileInput),this.hiddenFileInput=null),delete this.element.dropzone,i.instances.splice(i.instances.indexOf(this),1)},i.prototype.updateTotalUploadProgress=function(){var e,t,i,n,r,o,s,l;if(n=0,i=0,e=this.getActiveFiles(),e.length){for(l=this.getActiveFiles(),o=0,s=l.length;s>o;o++)t=l[o],n+=t.upload.bytesSent,i+=t.upload.total;r=100*n/i}else r=100;return this.emit("totaluploadprogress",r,i,n)},i.prototype._getParamName=function(e){return"function"==typeof this.options.paramName?this.options.paramName(e):""+this.options.paramName+(this.options.uploadMultiple?"["+e+"]":"")},i.prototype.getFallbackForm=function(){var e,t,n,r;return(e=this.getExistingFallback())?e:(n='<div class="dz-fallback">',this.options.dictFallbackText&&(n+="<p>"+this.options.dictFallbackText+"</p>"),n+='<input type="file" name="'+this._getParamName(0)+'" '+(this.options.uploadMultiple?'multiple="multiple"':void 0)+' /><input type="submit" value="Upload!"></div>',t=i.createElement(n),"FORM"!==this.element.tagName?(r=i.createElement('<form action="'+this.options.url+'" enctype="multipart/form-data" method="'+this.options.method+'"></form>'),r.appendChild(t)):(this.element.setAttribute("enctype","multipart/form-data"),this.element.setAttribute("method",this.options.method)),null!=r?r:t)},i.prototype.getExistingFallback=function(){var e,t,i,n,r,o;for(t=function(e){var t,i,n;for(i=0,n=e.length;n>i;i++)if(t=e[i],/(^| )fallback($| )/.test(t.className))return t},o=["div","form"],n=0,r=o.length;r>n;n++)if(i=o[n],e=t(this.element.getElementsByTagName(i)))return e},i.prototype.setupEventListeners=function(){var e,t,i,n,r,o,s;for(o=this.listeners,s=[],n=0,r=o.length;r>n;n++)e=o[n],s.push(function(){var n,r;n=e.events,r=[];for(t in n)i=n[t],r.push(e.element.addEventListener(t,i,!1));return r}());return s},i.prototype.removeEventListeners=function(){var e,t,i,n,r,o,s;for(o=this.listeners,s=[],n=0,r=o.length;r>n;n++)e=o[n],s.push(function(){var n,r;n=e.events,r=[];for(t in n)i=n[t],r.push(e.element.removeEventListener(t,i,!1));return r}());return s},i.prototype.disable=function(){var e,t,i,n,r;for(this.clickableElements.forEach(function(e){return e.classList.remove("dz-clickable")}),this.removeEventListeners(),n=this.files,r=[],t=0,i=n.length;i>t;t++)e=n[t],r.push(this.cancelUpload(e));return r},i.prototype.enable=function(){return this.clickableElements.forEach(function(e){return e.classList.add("dz-clickable")}),this.setupEventListeners()},i.prototype.filesize=function(e){var t,i,n,r,o,s,l,a;for(s=["TB","GB","MB","KB","b"],n=r=null,i=l=0,a=s.length;a>l;i=++l)if(o=s[i],t=Math.pow(this.options.filesizeBase,4-i)/10,e>=t){n=e/Math.pow(this.options.filesizeBase,4-i),r=o;break}return n=Math.round(10*n)/10,"<strong>"+n+"</strong> "+r},i.prototype._updateMaxFilesReachedClass=function(){return null!=this.options.maxFiles&&this.getAcceptedFiles().length>=this.options.maxFiles?(this.getAcceptedFiles().length===this.options.maxFiles&&this.emit("maxfilesreached",this.files),this.element.classList.add("dz-max-files-reached")):this.element.classList.remove("dz-max-files-reached")},i.prototype.drop=function(e){var t,i;e.dataTransfer&&(this.emit("drop",e),t=e.dataTransfer.files,t.length&&(i=e.dataTransfer.items,i&&i.length&&null!=i[0].webkitGetAsEntry?this._addFilesFromItems(i):this.handleFiles(t)))},i.prototype.paste=function(e){var t,i;if(null!=(null!=e&&null!=(i=e.clipboardData)?i.items:void 0))return this.emit("paste",e),t=e.clipboardData.items,t.length?this._addFilesFromItems(t):void 0},i.prototype.handleFiles=function(e){var t,i,n,r;for(r=[],i=0,n=e.length;n>i;i++)t=e[i],r.push(this.addFile(t));return r},i.prototype._addFilesFromItems=function(e){var t,i,n,r,o;for(o=[],n=0,r=e.length;r>n;n++)i=e[n],o.push(null!=i.webkitGetAsEntry&&(t=i.webkitGetAsEntry())?t.isFile?this.addFile(i.getAsFile()):t.isDirectory?this._addFilesFromDirectory(t,t.name):void 0:null!=i.getAsFile?null==i.kind||"file"===i.kind?this.addFile(i.getAsFile()):void 0:void 0);return o},i.prototype._addFilesFromDirectory=function(e,t){var i,n;return i=e.createReader(),n=function(e){return function(i){var n,r,o;for(r=0,o=i.length;o>r;r++)n=i[r],n.isFile?n.file(function(i){return e.options.ignoreHiddenFiles&&"."===i.name.substring(0,1)?void 0:(i.fullPath=""+t+"/"+i.name,e.addFile(i))}):n.isDirectory&&e._addFilesFromDirectory(n,""+t+"/"+n.name)}}(this),i.readEntries(n,function(e){return"undefined"!=typeof console&&null!==console&&"function"==typeof console.log?console.log(e):void 0})},i.prototype.accept=function(e,t){return e.size>1024*this.options.maxFilesize*1024?t(this.options.dictFileTooBig.replace("{{filesize}}",Math.round(e.size/1024/10.24)/100).replace("{{maxFilesize}}",this.options.maxFilesize)):i.isValidFile(e,this.options.acceptedFiles)?null!=this.options.maxFiles&&this.getAcceptedFiles().length>=this.options.maxFiles?(t(this.options.dictMaxFilesExceeded.replace("{{maxFiles}}",this.options.maxFiles)),this.emit("maxfilesexceeded",e)):this.options.accept.call(this,e,t):t(this.options.dictInvalidFileType)},i.prototype.addFile=function(e){return e.upload={progress:0,total:e.size,bytesSent:0},this.files.push(e),e.status=i.ADDED,this.emit("addedfile",e),this._enqueueThumbnail(e),this.accept(e,function(t){return function(i){return i?(e.accepted=!1,t._errorProcessing([e],i)):(e.accepted=!0,t.options.autoQueue&&t.enqueueFile(e)),t._updateMaxFilesReachedClass()}}(this))},i.prototype.enqueueFiles=function(e){var t,i,n;for(i=0,n=e.length;n>i;i++)t=e[i],this.enqueueFile(t);return null},i.prototype.enqueueFile=function(e){if(e.status!==i.ADDED||e.accepted!==!0)throw new Error("This file can't be queued because it has already been processed or was rejected.");return e.status=i.QUEUED,this.options.autoProcessQueue?setTimeout(function(e){return function(){return e.processQueue()}}(this),0):void 0},i.prototype._thumbnailQueue=[],i.prototype._processingThumbnail=!1,i.prototype._enqueueThumbnail=function(e){return this.options.createImageThumbnails&&e.type.match(/image.*/)&&e.size<=1024*this.options.maxThumbnailFilesize*1024?(this._thumbnailQueue.push(e),setTimeout(function(e){return function(){return e._processThumbnailQueue()}}(this),0)):void 0},i.prototype._processThumbnailQueue=function(){return this._processingThumbnail||0===this._thumbnailQueue.length?void 0:(this._processingThumbnail=!0,this.createThumbnail(this._thumbnailQueue.shift(),function(e){return function(){return e._processingThumbnail=!1,e._processThumbnailQueue()}}(this)))},i.prototype.removeFile=function(e){return e.status===i.UPLOADING&&this.cancelUpload(e),this.files=l(this.files,e),this.emit("removedfile",e),0===this.files.length?this.emit("reset"):void 0},i.prototype.removeAllFiles=function(e){var t,n,r,o;for(null==e&&(e=!1),o=this.files.slice(),n=0,r=o.length;r>n;n++)t=o[n],(t.status!==i.UPLOADING||e)&&this.removeFile(t);return null},i.prototype.createThumbnail=function(e,t){var i;return i=new FileReader,i.onload=function(n){return function(){return"image/svg+xml"===e.type?(n.emit("thumbnail",e,i.result),void(null!=t&&t())):n.createThumbnailFromUrl(e,i.result,t)}}(this),i.readAsDataURL(e)},i.prototype.createThumbnailFromUrl=function(e,t,i){var n;return n=document.createElement("img"),n.onload=function(t){return function(){var r,s,l,a,u,p,d,c;return e.width=n.width,e.height=n.height,l=t.options.resize.call(t,e),null==l.trgWidth&&(l.trgWidth=l.optWidth),null==l.trgHeight&&(l.trgHeight=l.optHeight),r=document.createElement("canvas"),s=r.getContext("2d"),r.width=l.trgWidth,r.height=l.trgHeight,o(s,n,null!=(u=l.srcX)?u:0,null!=(p=l.srcY)?p:0,l.srcWidth,l.srcHeight,null!=(d=l.trgX)?d:0,null!=(c=l.trgY)?c:0,l.trgWidth,l.trgHeight),a=r.toDataURL("image/png"),t.emit("thumbnail",e,a),null!=i?i():void 0}}(this),null!=i&&(n.onerror=i),n.src=t},i.prototype.processQueue=function(){var e,t,i,n;if(t=this.options.parallelUploads,i=this.getUploadingFiles().length,e=i,!(i>=t)&&(n=this.getQueuedFiles(),n.length>0)){if(this.options.uploadMultiple)return this.processFiles(n.slice(0,t-i));for(;t>e;){if(!n.length)return;this.processFile(n.shift()),e++}}},i.prototype.processFile=function(e){return this.processFiles([e])},i.prototype.processFiles=function(e){var t,n,r;for(n=0,r=e.length;r>n;n++)t=e[n],t.processing=!0,t.status=i.UPLOADING,this.emit("processing",t);return this.options.uploadMultiple&&this.emit("processingmultiple",e),this.uploadFiles(e)},i.prototype._getFilesWithXhr=function(e){var t,i;return i=function(){var i,n,r,o;for(r=this.files,o=[],i=0,n=r.length;n>i;i++)t=r[i],t.xhr===e&&o.push(t);return o}.call(this)},i.prototype.cancelUpload=function(e){var t,n,r,o,s,l,a;if(e.status===i.UPLOADING){for(n=this._getFilesWithXhr(e.xhr),r=0,s=n.length;s>r;r++)t=n[r],t.status=i.CANCELED;for(e.xhr.abort(),o=0,l=n.length;l>o;o++)t=n[o],this.emit("canceled",t);this.options.uploadMultiple&&this.emit("canceledmultiple",n)}else((a=e.status)===i.ADDED||a===i.QUEUED)&&(e.status=i.CANCELED,this.emit("canceled",e),this.options.uploadMultiple&&this.emit("canceledmultiple",[e]));return this.options.autoProcessQueue?this.processQueue():void 0},r=function(){var e,t;return t=arguments[0],e=2<=arguments.length?a.call(arguments,1):[],"function"==typeof t?t.apply(this,e):t},i.prototype.uploadFile=function(e){return this.uploadFiles([e])},i.prototype.uploadFiles=function(e){var t,o,s,l,a,u,p,d,c,h,m,f,g,v,y,F,w,E,b,C,z,k,L,x,T,A,D,S,_,M,U,N,I,R;for(b=new XMLHttpRequest,C=0,x=e.length;x>C;C++)t=e[C],t.xhr=b;f=r(this.options.method,e),w=r(this.options.url,e),b.open(f,w,!0),b.withCredentials=!!this.options.withCredentials,y=null,s=function(i){return function(){var n,r,o;for(o=[],n=0,r=e.length;r>n;n++)t=e[n],o.push(i._errorProcessing(e,y||i.options.dictResponseError.replace("{{statusCode}}",b.status),b));return o}}(this),F=function(i){return function(n){var r,o,s,l,a,u,p,d,c;if(null!=n)for(o=100*n.loaded/n.total,s=0,u=e.length;u>s;s++)t=e[s],t.upload={progress:o,total:n.total,bytesSent:n.loaded};else{for(r=!0,o=100,l=0,p=e.length;p>l;l++)t=e[l],(100!==t.upload.progress||t.upload.bytesSent!==t.upload.total)&&(r=!1),t.upload.progress=o,t.upload.bytesSent=t.upload.total;if(r)return}for(c=[],a=0,d=e.length;d>a;a++)t=e[a],c.push(i.emit("uploadprogress",t,o,t.upload.bytesSent));return c}}(this),b.onload=function(t){return function(n){var r;if(e[0].status!==i.CANCELED&&4===b.readyState){if(y=b.responseText,b.getResponseHeader("content-type")&&~b.getResponseHeader("content-type").indexOf("application/json"))try{y=JSON.parse(y)}catch(o){n=o,y="Invalid JSON response from server."}return F(),200<=(r=b.status)&&300>r?t._finished(e,y,n):s()}}}(this),b.onerror=function(){return function(){return e[0].status!==i.CANCELED?s():void 0}}(this),v=null!=(_=b.upload)?_:b,v.onprogress=F,u={Accept:"application/json","Cache-Control":"no-cache","X-Requested-With":"XMLHttpRequest"},this.options.headers&&n(u,this.options.headers);for(l in u)a=u[l],b.setRequestHeader(l,a);if(o=new FormData,this.options.params){M=this.options.params;for(m in M)E=M[m],o.append(m,E)}for(z=0,T=e.length;T>z;z++)t=e[z],this.emit("sending",t,b,o);if(this.options.uploadMultiple&&this.emit("sendingmultiple",e,b,o),"FORM"===this.element.tagName)for(U=this.element.querySelectorAll("input, textarea, select, button"),k=0,A=U.length;A>k;k++)if(d=U[k],c=d.getAttribute("name"),h=d.getAttribute("type"),"SELECT"===d.tagName&&d.hasAttribute("multiple"))for(N=d.options,L=0,D=N.length;D>L;L++)g=N[L],g.selected&&o.append(c,g.value);else(!h||"checkbox"!==(I=h.toLowerCase())&&"radio"!==I||d.checked)&&o.append(c,d.value);for(p=S=0,R=e.length-1;R>=0?R>=S:S>=R;p=R>=0?++S:--S)o.append(this._getParamName(p),e[p],e[p].name);return b.send(o)},i.prototype._finished=function(e,t,n){var r,o,s;for(o=0,s=e.length;s>o;o++)r=e[o],r.status=i.SUCCESS,this.emit("success",r,t,n),this.emit("complete",r);return this.options.uploadMultiple&&(this.emit("successmultiple",e,t,n),this.emit("completemultiple",e)),this.options.autoProcessQueue?this.processQueue():void 0},i.prototype._errorProcessing=function(e,t,n){var r,o,s;for(o=0,s=e.length;s>o;o++)r=e[o],r.status=i.ERROR,this.emit("error",r,t,n),this.emit("complete",r);return this.options.uploadMultiple&&(this.emit("errormultiple",e,t,n),this.emit("completemultiple",e)),this.options.autoProcessQueue?this.processQueue():void 0},i}(t),e.version="4.0.1",e.options={},e.optionsForElement=function(t){return t.getAttribute("id")?e.options[i(t.getAttribute("id"))]:void 0},e.instances=[],e.forElement=function(e){if("string"==typeof e&&(e=document.querySelector(e)),null==(null!=e?e.dropzone:void 0))throw new Error("No Dropzone found for given element. This is probably because you're trying to access it before Dropzone had the time to initialize. Use the `init` option to setup any additional observers on your Dropzone.");return e.dropzone},e.autoDiscover=!0,e.discover=function(){var t,i,n,r,o,s;for(document.querySelectorAll?n=document.querySelectorAll(".dropzone"):(n=[],t=function(e){var t,i,r,o;for(o=[],i=0,r=e.length;r>i;i++)t=e[i],o.push(/(^| )dropzone($| )/.test(t.className)?n.push(t):void 0);return o},t(document.getElementsByTagName("div")),t(document.getElementsByTagName("form"))),s=[],r=0,o=n.length;o>r;r++)i=n[r],s.push(e.optionsForElement(i)!==!1?new e(i):void 0);return s},e.blacklistedBrowsers=[/opera.*Macintosh.*version\/12/i],e.isBrowserSupported=function(){var t,i,n,r,o;if(t=!0,window.File&&window.FileReader&&window.FileList&&window.Blob&&window.FormData&&document.querySelector)if("classList"in document.createElement("a"))for(o=e.blacklistedBrowsers,n=0,r=o.length;r>n;n++)i=o[n],i.test(navigator.userAgent)&&(t=!1);else t=!1;else t=!1;return t},l=function(e,t){var i,n,r,o;for(o=[],n=0,r=e.length;r>n;n++)i=e[n],i!==t&&o.push(i);return o},i=function(e){return e.replace(/[\-_](\w)/g,function(e){return e.charAt(1).toUpperCase()})},e.createElement=function(e){var t;return t=document.createElement("div"),t.innerHTML=e,t.childNodes[0]},e.elementInside=function(e,t){if(e===t)return!0;for(;e=e.parentNode;)if(e===t)return!0;return!1},e.getElement=function(e,t){var i;if("string"==typeof e?i=document.querySelector(e):null!=e.nodeType&&(i=e),null==i)throw new Error("Invalid `"+t+"` option provided. Please provide a CSS selector or a plain HTML element.");return i},e.getElements=function(e,t){var i,n,r,o,s,l,a,u;if(e instanceof Array){r=[];try{for(o=0,l=e.length;l>o;o++)n=e[o],r.push(this.getElement(n,t))}catch(p){i=p,r=null}}else if("string"==typeof e)for(r=[],u=document.querySelectorAll(e),s=0,a=u.length;a>s;s++)n=u[s],r.push(n);else null!=e.nodeType&&(r=[e]);if(null==r||!r.length)throw new Error("Invalid `"+t+"` option provided. Please provide a CSS selector, a plain HTML element or a list of those.");return r},e.confirm=function(e,t,i){return window.confirm(e)?t():null!=i?i():void 0},e.isValidFile=function(e,t){var i,n,r,o,s;if(!t)return!0;for(t=t.split(","),n=e.type,i=n.replace(/\/.*$/,""),o=0,s=t.length;s>o;o++)if(r=t[o],r=r.trim(),"."===r.charAt(0)){if(-1!==e.name.toLowerCase().indexOf(r.toLowerCase(),e.name.length-r.length))return!0}else if(/\/\*$/.test(r)){if(i===r.replace(/\/.*$/,""))return!0}else if(n===r)return!0;return!1},"undefined"!=typeof jQuery&&null!==jQuery&&(jQuery.fn.dropzone=function(t){return this.each(function(){return new e(this,t)})}),"undefined"!=typeof module&&null!==module?module.exports=e:window.Dropzone=e,e.ADDED="added",e.QUEUED="queued",e.ACCEPTED=e.QUEUED,e.UPLOADING="uploading",e.PROCESSING=e.UPLOADING,e.CANCELED="canceled",e.ERROR="error",e.SUCCESS="success",r=function(e){var t,i,n,r,o,s,l,a,u,p;for(l=e.naturalWidth,s=e.naturalHeight,i=document.createElement("canvas"),i.width=1,i.height=s,n=i.getContext("2d"),n.drawImage(e,0,0),r=n.getImageData(0,0,1,s).data,p=0,o=s,a=s;a>p;)t=r[4*(a-1)+3],0===t?o=a:p=a,a=o+p>>1;return u=a/s,0===u?1:u
},o=function(e,t,i,n,o,s,l,a,u,p){var d;return d=r(t),e.drawImage(t,i,n,o,s,l,a,u,p/d)},n=function(e,t){var i,n,r,o,s,l,a,u,p;if(r=!1,p=!0,n=e.document,u=n.documentElement,i=n.addEventListener?"addEventListener":"attachEvent",a=n.addEventListener?"removeEventListener":"detachEvent",l=n.addEventListener?"":"on",o=function(i){return"readystatechange"!==i.type||"complete"===n.readyState?(("load"===i.type?e:n)[a](l+i.type,o,!1),!r&&(r=!0)?t.call(e,i.type||i):void 0):void 0},s=function(){var e;try{u.doScroll("left")}catch(t){return e=t,void setTimeout(s,50)}return o("poll")},"complete"!==n.readyState){if(n.createEventObject&&u.doScroll){try{p=!e.frameElement}catch(d){}p&&s()}return n[i](l+"DOMContentLoaded",o,!1),n[i](l+"readystatechange",o,!1),e[i](l+"load",o,!1)}},e._autoDiscoverFunction=function(){return e.autoDiscover?e.discover():void 0},n(window,e._autoDiscoverFunction)}).call(this);

/**
 * @version $Id: slider.js 25 2015-06-29 19:45:38Z szymon $
 * @package DJ-ImageSlider
 * @subpackage DJ-ImageSlider Component
 * @copyright Copyright (C) 2012 DJ-Extensions.com, All rights reserved.
 * @license DJ-Extensions.com Proprietary Use License
 * @author url: http://dj-extensions.com
 * @author email contact@dj-extensions.com
 * @developer Szymon Woronowski - szymon.woronowski@design-joomla.eu
 *
 */
!function($){var F={init:function(m){m.data();var n=m.data('djslider');var o=m.data('animation');m.removeAttr('data-djslider');m.removeAttr('data-animation');var q=$('#djslider'+n.id).css('opacity',0);var r=$('#slider'+n.id).css('position','relative');var t=n.css3=='1'?support('transition'):false;var u=r.children('li');var w=n.slide_size;var x=n.visible_slides;var y=w*u.length;var z=u.length-x;var A=0;var B=o.auto=='1'?1:0;var C=0;var D=false;if(n.slider_type==2){u.css('position','absolute');u.css('top',0);u.css('left',0);r.css('width',w);u.css('opacity',0);u.css('visibility','hidden');$(u[0]).css('opacity',1);$(u[0]).css('visibility','visible');if(t)u.css(t,'opacity '+o.duration+'ms '+o.css3transition)}else if(n.slider_type==1){r.css('top',0);r.css('height',y);if(t)r.css(t,'top '+o.duration+'ms '+o.css3transition)}else{r.css(n.direction,0);r.css('width',y);if(t)r.css(t,n.direction+' '+o.duration+'ms '+o.css3transition)}if(n.show_arrows>0){$('#next'+n.id).on('click',function(){if(n.direction=='right')prevSlide();else nextSlide()});$('#prev'+n.id).on('click',function(){if(n.direction=='right')nextSlide();else prevSlide()})}if(n.show_buttons>0){$('#play'+n.id).on('click',function(){changeNavigation();B=1});$('#pause'+n.id).on('click',function(){changeNavigation();B=0})}m.on('mouseenter',function(){C=1}).on('mouseleave',function(){C=0});m.djswipe(function(a,b){if(b.x<100||b.y>30){return}if(a.x=="left"){if(n.direction=='right')prevSlide();else nextSlide()}else if(a.x=="right"){if(n.direction=='right')nextSlide();else prevSlide()}});if($('#cust-navigation'+n.id).length){var E=$('#cust-navigation'+n.id).find('.load-button');E.each(function(a){var b=$(this);b.on('click',function(e){if(!D&&!b.hasClass('load-button-active')){loadSlide(a)}});if(a>z)b.css('display','none')})}function getSize(a){return{'x':a.width(),'y':a.height()}}function responsive(){var c=m.parent();var d=getSize(c).x;var e=parseInt(q.css('max-width'));var f=getSize(q);var g=f.x;if(g>d){g=d}else if(g<=d&&(!e||g<e)){g=(d>e?e:d)}var h=f.x/f.y;var i=g/h;q.css('width',g);q.css('height',i);if(n.slider_type==2){r.css('width',g);u.css('width',g);u.css('height',i)}else if(n.slider_type==1){var j=parseInt($(u[0]).css('margin-bottom'));w=(i+j)/x;y=u.length*w+u.length;r.css('height',y);u.css('width',g);u.css('height',w-j);r.css('top',-w*A)}else{var j=n.direction=='right'?parseInt($(u[0]).css('margin-left')):parseInt($(u[0]).css('margin-right'));var k=Math.ceil(g/(n.slide_size+j));if(k!=x){x=(k>n.visible_slides?n.visible_slides:k);z=u.length-x;if($('#cust-navigation'+n.id).length){var l=$('#cust-navigation'+n.id).find('.load-button');l.each(function(a){var b=$(this);if(a>z)b.css('display','none');else b.css('display','')})}h=(x*w-j)/f.y;i=g/h;q.css('height',i)}w=(g+j)/x;y=u.length*w+u.length;r.css('width',y);u.css('width',w-j);u.css('height',i);r.css(n.direction,-w*A);if(A>z)loadSlide(z)}if(n.show_buttons>0||n.show_arrows>0){button_pos=$('#navigation'+n.id).position().top;if(button_pos<0){m.css('padding-top',-button_pos);m.css('padding-bottom',0)}else{buttons_height=0;if(n.show_arrows>0){buttons_height=getSize($('#next'+n.id)).y;buttons_height=Math.max(buttons_height,getSize($('#prev'+n.id)).y)}if(n.show_buttons>0){buttons_height=Math.max(buttons_height,getSize($('#play'+n.id)).y);buttons_height=Math.max(buttons_height,getSize($('#pause'+n.id)).y)}padding=button_pos+buttons_height-i;if(padding>0){m.css('padding-top',0);m.css('padding-bottom',padding)}else{m.css('padding-top',0);m.css('padding-bottom',0)}}buttons_margin=parseInt($('#navigation'+n.id).css('margin-left'))+parseInt($('#navigation'+n.id).css('margin-right'));if(buttons_margin<0&&window.getSize().x<getSize($('#navigation'+n.id)).x-buttons_margin){$('#navigation'+n.id).css('margin-left',0);$('#navigation'+n.id).css('margin-right',0)}}}function updateActiveButton(c){if($('#cust-navigation'+n.id).length)E.each(function(a){var b=$(this);b.removeClass('load-button-active');if(a==c)b.addClass('load-button-active')})}function nextSlide(){if(A<z)loadSlide(A+1);else loadSlide(0)}function prevSlide(){if(A>0)loadSlide(A-1);else loadSlide(z)}function loadSlide(a){if(A==a)return;if(n.slider_type==2){if(D)return;D=true;prev_slide=A;A=a;makeFade(prev_slide)}else{A=a;if(n.slider_type==1){if(t){r.css('top',-w*A)}else{r.animate({top:-w*A},o.duration,o.transition)}}else{if(t){r.css(n.direction,-w*A)}else{if(n.direction=='right')r.animate({right:-w*A},o.duration,o.transition);else r.animate({left:-w*A},o.duration,o.transition)}}}updateActiveButton(A)}function makeFade(a){$(u[A]).css('visibility','visible');if(t){$(u[A]).css('opacity',1);$(u[a]).css('opacity',0)}else{$(u[A]).animate({opacity:1},o.duration,o.transition);$(u[a]).animate({opacity:0},o.duration,o.transition)}setTimeout(function(){$(u[a]).css('visibility','hidden');D=false},o.duration)}function changeNavigation(){if(B){$('#pause'+n.id).css('display','none');$('#play'+n.id).css('display','block')}else{$('#play'+n.id).css('display','none');$('#pause'+n.id).css('display','block')}}function slidePlay(){setTimeout(function(){if(B&&!C)nextSlide();slidePlay()},o.delay)}function sliderLoaded(){m.css('background','none');q.css('opacity',1);responsive();if(n.show_buttons>0){play_width=getSize($('#play'+n.id)).x;$('#play'+n.id).css('margin-left',-play_width/2);pause_width=getSize($('#pause'+n.id)).x;$('#pause'+n.id).css('margin-left',-pause_width/2);if(B){$('#play'+n.id).css('display','none')}else{$('#pause'+n.id).css('display','none')}}slidePlay()}function support(p){var b=document.body||document.documentElement,s=b.style;if(typeof s=='undefined')return false;if(typeof s[p]=='string')return p;v=['Moz','Webkit','Khtml','O','ms','Icab'],pu=p.charAt(0).toUpperCase()+p.substr(1);for(var i=0;i<v.length;i++){if(typeof s[v[i]+pu]=='string')return('-'+v[i].toLowerCase()+'-'+p)}return false}if(n.preload)setTimeout(sliderLoaded,n.preload);else $(window).load(sliderLoaded);$(window).on('resize',responsive)}};$.fn.djswipe=function(b){var c=false,originalPosition=null,info=null;$el=$(this);function swipeInfo(a){var x=a.originalEvent.touches[0].pageX,y=a.originalEvent.touches[0].pageY,dx,dy;dx=(x>originalPosition.x)?"right":"left";dy=(y>originalPosition.y)?"down":"up";return{direction:{x:dx,y:dy},offset:{x:Math.abs(x-originalPosition.x),y:Math.abs(originalPosition.y-y)}}}$el.on("touchstart",function(a){c=true;originalPosition={x:a.originalEvent.touches[0].pageX,y:a.originalEvent.touches[0].pageY}});$el.on("touchend",function(){c=false;if(info)b(info.direction,info.offset);originalPosition=null;info=null});$el.on("touchmove",function(a){if(!c){return}info=swipeInfo(a)});return true};$(document).ready(function(){$('[data-djslider]').each(function(){F.init($(this))})})}(jQuery);


function writeDynaList(e,t,n,r,i){var s="\n  <select "+e+">",o,u;o=0;for(x in t){if(t[x][0]==n){u="";if(r==n&&i==t[x][1]||o==0&&r!=n)u='selected="selected"';s+='\n     <option value="'+t[x][1]+'" '+u+">"+t[x][2]+"</option>"}o++}s+="\n </select>",document.writeln(s)}function changeDynaList(e,t,n,r,s){var o=document.adminForm[e];for(i in o.options.length)o.options[i]=null;i=0;for(x in t)if(t[x][0]==n){opt=new Option,opt.value=t[x][1],opt.text=t[x][2];if(r==n&&s==opt.value||i==0)opt.selected=!0;o.options[i++]=opt}o.length=i}function radioGetCheckedValue(e){if(!e)return"";var t=e.length,n;if(t==undefined)return e.checked?e.value:"";for(n=0;n<t;n++)if(e[n].checked)return e[n].value;return""}function getSelectedValue(e,t){var n=document[e],r=n[t];return i=r.selectedIndex,i!=null&&i>-1?r.options[i].value:null}function listItemTask(e,t){var n=document.adminForm,r,i,s=n[e];if(s){for(r=0;!0;r++){i=n["cb"+r];if(!i)break;i.checked=!1}s.checked=!0,n.boxchecked.value=1,submitbutton(t)}return!1}function submitbutton(e){Joomla.submitform(e)}function submitform(e){Joomla.submitform(e)}function saveorder(e,t){checkAll_button(e,t)}function checkAll_button(e,t){t||(t="saveorder");var n,r;for(n=0;n<=e;n++){r=document.adminForm["cb"+n];if(!r){alert("You cannot change the order of items, as an item in the list is `Checked Out`");return}r.checked==0&&(r.checked=!0)}submitform(t)}Joomla=window.Joomla||{},Joomla.editors={},Joomla.editors.instances={},Joomla.submitform=function(e,t,n){t||(t=document.getElementById("adminForm")),e&&(t.task.value=e),t.noValidate=!n;var r=document.createElement("input");r.style.display="none",r.type="submit",t.appendChild(r).click(),t.removeChild(r)},Joomla.submitbutton=function(e){Joomla.submitform(e)},Joomla.JText={strings:{},_:function(e,t){return typeof this.strings[e.toUpperCase()]!="undefined"?this.strings[e.toUpperCase()]:t},load:function(e){for(var t in e)this.strings[t.toUpperCase()]=e[t];return this}},Joomla.replaceTokens=function(e){var t=document.getElementsByTagName("input"),n;for(n=0;n<t.length;n++)t[n].type=="hidden"&&t[n].name.length==32&&t[n].value=="1"&&(t[n].name=e)},Joomla.isEmail=function(e){var t=new RegExp("^[\\w-_.]*[\\w-_.]@[\\w].+[\\w]+[\\w]$");return t.test(e)},Joomla.checkAll=function(e,t){t||(t="cb");if(e.form){var r=0,i,s;for(i=0,n=e.form.elements.length;i<n;i++)s=e.form.elements[i],s.type==e.type&&(t&&s.id.indexOf(t)==0||!t)&&(s.checked=e.checked,r+=s.checked==1?1:0);return e.form.boxchecked&&(e.form.boxchecked.value=r),!0}return!1},Joomla.renderMessages=function(e){Joomla.removeMessages();var t=document.getElementById("system-message-container");for(var n in e)if(e.hasOwnProperty(n)){var r=e[n],i=document.createElement("div");i.className="alert alert-"+n;var s=Joomla.JText._(n);if(typeof s!="undefined"){var o=document.createElement("h4");o.className="alert-heading",o.innerHTML=Joomla.JText._(n),i.appendChild(o)}for(var u=r.length-1;u>=0;u--){var a=document.createElement("p");a.innerHTML=r[u],i.appendChild(a)}t.appendChild(i)}},Joomla.removeMessages=function(){var e=document.getElementById("system-message-container");while(e.firstChild)e.removeChild(e.firstChild);e.style.display="none",e.offsetHeight,e.style.display=""},Joomla.isChecked=function(e,t){typeof t=="undefined"&&(t=document.getElementById("adminForm")),e==1?t.boxchecked.value++:t.boxchecked.value--;var r=!0,i,s;for(i=0,n=t.elements.length;i<n;i++){s=t.elements[i];if(s.type=="checkbox"&&s.name!="checkall-toggle"&&s.checked==0){r=!1;break}}t.elements["checkall-toggle"]&&(t.elements["checkall-toggle"].checked=r)},Joomla.popupWindow=function(e,t,n,r,i){var s=(screen.width-n)/2,o,u,a;o=(screen.height-r)/2,u="height="+r+",width="+n+",top="+o+",left="+s+",scrollbars="+i+",resizable",a=window.open(e,t,u),a.window.focus()},Joomla.tableOrdering=function(e,t,n,r){typeof r=="undefined"&&(r=document.getElementById("adminForm")),r.filter_order.value=e,r.filter_order_Dir.value=t,Joomla.submitform(n,r)};


/*
 Input Mask plugin for jquery
 http://github.com/RobinHerbots/jquery.inputmask
 Copyright (c) 2010 - 2013 Robin Herbots
 Licensed under the MIT license (http://www.opensource.org/licenses/mit-license.php)
 Version: 2.4.8
*/
(function(c){void 0===c.fn.inputmask&&(c.inputmask={defaults:{placeholder:"_",optionalmarker:{start:"[",end:"]"},quantifiermarker:{start:"{",end:"}"},groupmarker:{start:"(",end:")"},escapeChar:"\\",mask:null,oncomplete:c.noop,onincomplete:c.noop,oncleared:c.noop,repeat:0,greedy:!0,autoUnmask:!1,clearMaskOnLostFocus:!0,insertMode:!0,clearIncomplete:!1,aliases:{},onKeyUp:c.noop,onKeyDown:c.noop,onUnMask:void 0,showMaskOnFocus:!0,showMaskOnHover:!0,onKeyValidation:c.noop,skipOptionalPartCharacter:" ",
showTooltip:!1,numericInput:!1,isNumeric:!1,radixPoint:"",skipRadixDance:!1,rightAlignNumerics:!0,definitions:{9:{validator:"[0-9]",cardinality:1},a:{validator:"[A-Za-z\u0410-\u044f\u0401\u0451]",cardinality:1},"*":{validator:"[A-Za-z\u0410-\u044f\u0401\u04510-9]",cardinality:1}},keyCode:{ALT:18,BACKSPACE:8,CAPS_LOCK:20,COMMA:188,COMMAND:91,COMMAND_LEFT:91,COMMAND_RIGHT:93,CONTROL:17,DELETE:46,DOWN:40,END:35,ENTER:13,ESCAPE:27,HOME:36,INSERT:45,LEFT:37,MENU:93,NUMPAD_ADD:107,NUMPAD_DECIMAL:110,NUMPAD_DIVIDE:111,
NUMPAD_ENTER:108,NUMPAD_MULTIPLY:106,NUMPAD_SUBTRACT:109,PAGE_DOWN:34,PAGE_UP:33,PERIOD:190,RIGHT:39,SHIFT:16,SPACE:32,TAB:9,UP:38,WINDOWS:91},ignorables:[8,9,13,19,27,33,34,35,36,37,38,39,40,45,46,93,112,113,114,115,116,117,118,119,120,121,122,123],getMaskLength:function(a,c,e,f,b){b=a.length;c||("*"==e?b=f.length+1:1<e&&(b+=a.length*(e-1)));return b}},escapeRegex:function(a){return a.replace(RegExp("(\\/|\\.|\\*|\\+|\\?|\\||\\(|\\)|\\[|\\]|\\{|\\}|\\\\)","gim"),"\\$1")}},c.fn.inputmask=function(a,
d){function e(a){var b=document.createElement("input");a="on"+a;var g=a in b;g||(b.setAttribute(a,"return;"),g="function"==typeof b[a]);return g}function f(a,b){var e=g.aliases[a];return e?(e.alias&&f(e.alias),c.extend(!0,g,e),c.extend(!0,g,b),!0):!1}function b(a){g.numericInput&&(a=a.split("").reverse().join(""));var b=!1,e=0,d=g.greedy,f=g.repeat;"*"==f&&(d=!1);1==a.length&&!1==d&&0!=f&&(g.placeholder="");a=c.map(a.split(""),function(a,c){var d=[];if(a==g.escapeChar)b=!0;else if(a!=g.optionalmarker.start&&
a!=g.optionalmarker.end||b){var f=g.definitions[a];if(f&&!b)for(var h=0;h<f.cardinality;h++)d.push(y(e+h));else d.push(a),b=!1;e+=d.length;return d}});for(var h=a.slice(),s=1;s<f&&d;s++)h=h.concat(a.slice());return{mask:h,repeat:f,greedy:d}}function h(a){g.numericInput&&(a=a.split("").reverse().join(""));var b=!1,e=!1,d=!1;return c.map(a.split(""),function(a,c){var f=[];if(a==g.escapeChar)e=!0;else{if(a!=g.optionalmarker.start||e){if(a!=g.optionalmarker.end||e){var h=g.definitions[a];if(h&&!e){for(var L=
h.prevalidator,k=L?L.length:0,p=1;p<h.cardinality;p++){var l=k>=p?L[p-1]:[],m=l.validator,l=l.cardinality;f.push({fn:m?"string"==typeof m?RegExp(m):new function(){this.test=m}:/./,cardinality:l?l:1,optionality:b,newBlockMarker:!0==b?d:!1,offset:0,casing:h.casing,def:h.definitionSymbol||a});!0==b&&(d=!1)}f.push({fn:h.validator?"string"==typeof h.validator?RegExp(h.validator):new function(){this.test=h.validator}:/./,cardinality:h.cardinality,optionality:b,newBlockMarker:d,offset:0,casing:h.casing,
def:h.definitionSymbol||a})}else f.push({fn:null,cardinality:0,optionality:b,newBlockMarker:d,offset:0,casing:null,def:a}),e=!1;d=!1;return f}b=!1}else b=!0;d=!0}})}function k(){function a(b){var e=b.length;for(i=0;i<e&&b.charAt(i)!=g.optionalmarker.start;i++);var d=[b.substring(0,i)];i<e&&d.push(b.substring(i+1,e));return d}function e(k,s,l){var r=0,x=0,p=s.length;for(i=0;i<p&&!(s.charAt(i)==g.optionalmarker.start&&r++,s.charAt(i)==g.optionalmarker.end&&x++,0<r&&r==x);i++);r=[s.substring(0,i)];i<
p&&r.push(s.substring(i+1,p));x=a(r[0]);1<x.length?(s=k+x[0]+(g.optionalmarker.start+x[1]+g.optionalmarker.end)+(1<r.length?r[1]:""),-1==c.inArray(s,f)&&""!=s&&(f.push(s),p=b(s),d.push({mask:s,_buffer:p.mask,buffer:p.mask.slice(),tests:h(s),lastValidPosition:-1,greedy:p.greedy,repeat:p.repeat,metadata:l})),s=k+x[0]+(1<r.length?r[1]:""),-1==c.inArray(s,f)&&""!=s&&(f.push(s),p=b(s),d.push({mask:s,_buffer:p.mask,buffer:p.mask.slice(),tests:h(s),lastValidPosition:-1,greedy:p.greedy,repeat:p.repeat,metadata:l})),
1<a(x[1]).length&&e(k+x[0],x[1]+r[1],l),1<r.length&&1<a(r[1]).length&&(e(k+x[0]+(g.optionalmarker.start+x[1]+g.optionalmarker.end),r[1],l),e(k+x[0],r[1],l))):(s=k+r,-1==c.inArray(s,f)&&""!=s&&(f.push(s),p=b(s),d.push({mask:s,_buffer:p.mask,buffer:p.mask.slice(),tests:h(s),lastValidPosition:-1,greedy:p.greedy,repeat:p.repeat,metadata:l})))}var d=[],f=[],k=[];c.isFunction(g.mask)&&(g.mask=g.mask.call(this,g));c.isArray(g.mask)?c.each(g.mask,function(a,b){void 0!=b.mask?e("",b.mask.toString(),b):e("",
b.toString())}):e("",g.mask.toString());(function(a){function b(){this.matches=[];this.isQuantifier=this.isOptional=this.isGroup=!1}var e=/(?:[?*+]|\{[0-9]+(?:,[0-9]*)?\})\??|[^.?*+^${[]()|\\]+|./g,d=new b,c,f=[];for(k=[];c=e.exec(a);)switch(c=c[0],c.charAt(0)){case g.optionalmarker.end:case g.groupmarker.end:c=f.pop();0<f.length?f[f.length-1].matches.push(c):(k.push(c),d=c);break;case g.optionalmarker.start:!d.isGroup&&0<d.matches.length&&k.push(d);d=new b;d.isOptional=!0;f.push(d);break;case g.groupmarker.start:!d.isGroup&&
0<d.matches.length&&k.push(d);d=new b;d.isGroup=!0;f.push(d);break;case g.quantifiermarker.start:var h=new b;h.isQuantifier=!0;h.matches.push(c);0<f.length?f[f.length-1].matches.push(h):d.matches.push(h);break;default:if(0<f.length)f[f.length-1].matches.push(c);else{if(d.isGroup||d.isOptional)d=new b;d.matches.push(c)}}0<d.matches.length&&k.push(d);return k})(g.mask);return g.greedy?d:d.sort(function(a,b){return a.mask.length-b.mask.length})}function y(a){return g.placeholder.charAt(a%g.placeholder.length)}
function n(a,b){function d(){return a[b]}function e(){return d().tests}function f(){return d()._buffer}function h(){return d().buffer}function k(f,e,D){function l(a,d,b,e){for(var f=n(a),h=b?1:0,c="",H=d.buffer,S=d.tests[f].cardinality;S>h;S--)c+=F(H,f-(S-1));b&&(c+=b);return null!=d.tests[f].fn?d.tests[f].fn.test(c,H,a,e,g):b==F(d._buffer,a,!0)||b==g.skipOptionalPartCharacter?{refresh:!0,c:F(d._buffer,a,!0),pos:a}:!1}if(D=!0===D){var m=l(f,d(),e,D);!0===m&&(m={pos:f});return m}var s=[],m=!1,u=b,
v=h().slice(),t=d().lastValidPosition;w(f);var y=[];c.each(a,function(a,g){if("object"==typeof g){b=a;var c=f,k=d().lastValidPosition,n;if(k==t){if(1<c-t)for(k=-1==k?0:k;k<c&&(n=l(k,d(),v[k],!0),!1!==n);k++)G(h(),k,v[k],!0),!0===n&&(n={pos:k}),n=n.pos||k,d().lastValidPosition<n&&(d().lastValidPosition=n);if(!r(c)&&!l(c,d(),e,D)){k=q(c)-c;for(n=0;n<k&&!1===l(++c,d(),e,D);n++);y.push(b)}}(d().lastValidPosition>=t||b==u)&&0<=c&&c<p()&&(m=l(c,d(),e,D),!1!==m&&(!0===m&&(m={pos:c}),n=m.pos||c,d().lastValidPosition<
n&&(d().lastValidPosition=n)),s.push({activeMasksetIndex:a,result:m}))}});b=u;return function(d,b){var h=!1;c.each(b,function(a,b){if(h=-1==c.inArray(b.activeMasksetIndex,d)&&!1!==b.result)return!1});if(h)b=c.map(b,function(b,f){if(-1==c.inArray(b.activeMasksetIndex,d))return b;a[b.activeMasksetIndex].lastValidPosition=t});else{var g=-1,k=-1;c.each(b,function(a,b){-1!=c.inArray(b.activeMasksetIndex,d)&&!1!==b.result&(-1==g||g>b.result.pos)&&(g=b.result.pos,k=b.activeMasksetIndex)});b=c.map(b,function(b,
h){if(-1!=c.inArray(b.activeMasksetIndex,d)){if(b.result.pos==g)return b;if(!1!==b.result){for(var H=f;H<g;H++)if(rsltValid=l(H,a[b.activeMasksetIndex],a[k].buffer[H],!0),!1===rsltValid){a[b.activeMasksetIndex].lastValidPosition=g-1;break}else G(a[b.activeMasksetIndex].buffer,H,a[k].buffer[H],!0),a[b.activeMasksetIndex].lastValidPosition=H;rsltValid=l(g,a[b.activeMasksetIndex],e,!0);!1!==rsltValid&&(G(a[b.activeMasksetIndex].buffer,g,e,!0),a[b.activeMasksetIndex].lastValidPosition=g);return b}}})}return b}(y,
s)}function l(){var f=b,e={activeMasksetIndex:0,lastValidPosition:-1,next:-1};c.each(a,function(a,f){"object"==typeof f&&(b=a,d().lastValidPosition>e.lastValidPosition?(e.activeMasksetIndex=a,e.lastValidPosition=d().lastValidPosition,e.next=q(d().lastValidPosition)):d().lastValidPosition==e.lastValidPosition&&(-1==e.next||e.next>q(d().lastValidPosition))&&(e.activeMasksetIndex=a,e.lastValidPosition=d().lastValidPosition,e.next=q(d().lastValidPosition)))});b=-1!=e.lastValidPosition&&a[f].lastValidPosition==
e.lastValidPosition?f:e.activeMasksetIndex;f!=b&&(U(h(),q(e.lastValidPosition),p()),d().writeOutBuffer=!0);u.data("_inputmask").activeMasksetIndex=b}function r(a){a=n(a);a=e()[a];return void 0!=a?a.fn:!1}function n(a){return a%e().length}function p(){return g.getMaskLength(f(),d().greedy,d().repeat,h(),g)}function q(a){var b=p();if(a>=b)return b;for(;++a<b&&!r(a););return a}function w(a){if(0>=a)return 0;for(;0<--a&&!r(a););return a}function G(a,b,d,f){f&&(b=P(a,b));f=e()[n(b)];var h=d;if(void 0!=
h&&void 0!=f)switch(f.casing){case "upper":h=d.toUpperCase();break;case "lower":h=d.toLowerCase()}a[b]=h}function F(a,b,d){d&&(b=P(a,b));return a[b]}function P(a,b){for(var d;void 0==a[b]&&a.length<p();)for(d=0;void 0!==f()[d];)a.push(f()[d++]);return b}function J(a,b,d){a._valueSet(b.join(""));void 0!=d&&v(a,d)}function U(a,b,d,e){for(var h=p();b<d&&b<h;b++)!0===e?r(b)||G(a,b,""):G(a,b,F(f().slice(),b,!0))}function Q(a,b){var d=n(b);G(a,b,F(f(),d))}function M(e,h,g,k,l){k=void 0!=k?k.slice():V(e._valueGet()).split("");
c.each(a,function(a,b){"object"==typeof b&&(b.buffer=b._buffer.slice(),b.lastValidPosition=-1,b.p=-1)});!0!==g&&(b=0);h&&e._valueSet("");p();c.each(k,function(a,b){if(!0===l){var k=d().p,k=-1==k?k:w(k),n=-1==k?a:q(k);-1==c.inArray(b,f().slice(k+1,n))&&c(e).trigger("_keypress",[!0,b.charCodeAt(0),h,g,a])}else c(e).trigger("_keypress",[!0,b.charCodeAt(0),h,g,a])});!0===g&&-1!=d().p&&(d().lastValidPosition=w(d().p))}function W(a){return c.inputmask.escapeRegex.call(this,a)}function V(a){return a.replace(RegExp("("+
W(f().join(""))+")*$"),"")}function X(a){var b=h(),d=b.slice(),f,g;for(g=d.length-1;0<=g;g--)if(f=n(g),e()[f].optionality)if(r(g)&&k(g,b[g],!0))break;else d.pop();else break;J(a,d)}function fa(a,b){if(!e()||!0!==b&&a.hasClass("hasDatepicker"))return a[0]._valueGet();var d=c.map(h(),function(a,b){return r(b)&&k(b,a,!0)?a:null}),d=(A?d.reverse():d).join("");return void 0!=g.onUnMask?g.onUnMask.call(this,h().join(""),d):d}function C(a){!A||"number"!=typeof a||g.greedy&&""==g.placeholder||(a=h().length-
a);return a}function v(a,b,d){var e=a.jquery&&0<a.length?a[0]:a;if("number"==typeof b)b=C(b),d=C(d),c(a).is(":visible")&&(d="number"==typeof d?d:b,e.scrollLeft=e.scrollWidth,!1==g.insertMode&&b==d&&d++,e.setSelectionRange?(e.selectionStart=b,e.selectionEnd=z?b:d):e.createTextRange&&(a=e.createTextRange(),a.collapse(!0),a.moveEnd("character",d),a.moveStart("character",b),a.select()));else{if(!c(a).is(":visible"))return{begin:0,end:0};e.setSelectionRange?(b=e.selectionStart,d=e.selectionEnd):document.selection&&
document.selection.createRange&&(a=document.selection.createRange(),b=0-a.duplicate().moveStart("character",-1E5),d=b+a.text.length);b=C(b);d=C(d);return{begin:b,end:d}}}function O(d){if("*"!=g.repeat){var e=!1,h=0,k=b;c.each(a,function(a,g){if("object"==typeof g){b=a;var c=w(p());if(g.lastValidPosition>=h&&g.lastValidPosition==c){for(var k=!0,l=0;l<=c;l++){var q=r(l),m=n(l);if(q&&(void 0==d[l]||d[l]==y(l))||!q&&d[l]!=f()[m]){k=!1;break}}if(e=e||k)return!1}h=g.lastValidPosition}});b=k;return e}}var A=
!1,K=h().join(""),u,ga;this.unmaskedvalue=function(a,b){A=a.data("_inputmask").isRTL;return fa(a,b)};this.isComplete=function(a){return O(a)};this.mask=function(z){function P(a){a=c._data(a).events;c.each(a,function(a,b){c.each(b,function(a,b){if("inputmask"==b.namespace&&"setvalue"!=b.type&&"_keypress"!=b.type){var d=b.handler;b.handler=function(a){if(this.readOnly||this.disabled)a.preventDefault;else return d.apply(this,arguments)}}})})}function D(a){var b;Object.getOwnPropertyDescriptor&&(b=Object.getOwnPropertyDescriptor(a,
"value"));if(b&&b.get){if(!a._valueGet){var d=b.get,e=b.set;a._valueGet=function(){return A?d.call(this).split("").reverse().join(""):d.call(this)};a._valueSet=function(a){e.call(this,A?a.split("").reverse().join(""):a)};Object.defineProperty(a,"value",{get:function(){var a=c(this),b=c(this).data("_inputmask"),e=b.masksets,f=b.activeMasksetIndex;return b&&b.opts.autoUnmask?a.inputmask("unmaskedvalue"):d.call(this)!=e[f]._buffer.join("")?d.call(this):""},set:function(a){e.call(this,a);c(this).triggerHandler("setvalue.inputmask")}})}}else if(document.__lookupGetter__&&
a.__lookupGetter__("value"))a._valueGet||(d=a.__lookupGetter__("value"),e=a.__lookupSetter__("value"),a._valueGet=function(){return A?d.call(this).split("").reverse().join(""):d.call(this)},a._valueSet=function(a){e.call(this,A?a.split("").reverse().join(""):a)},a.__defineGetter__("value",function(){var a=c(this),b=c(this).data("_inputmask"),e=b.masksets,f=b.activeMasksetIndex;return b&&b.opts.autoUnmask?a.inputmask("unmaskedvalue"):d.call(this)!=e[f]._buffer.join("")?d.call(this):""}),a.__defineSetter__("value",
function(a){e.call(this,a);c(this).triggerHandler("setvalue.inputmask")}));else if(a._valueGet||(a._valueGet=function(){return A?this.value.split("").reverse().join(""):this.value},a._valueSet=function(a){this.value=A?a.split("").reverse().join(""):a}),void 0==c.valHooks.text||!0!=c.valHooks.text.inputmaskpatch)d=c.valHooks.text&&c.valHooks.text.get?c.valHooks.text.get:function(a){return a.value},e=c.valHooks.text&&c.valHooks.text.set?c.valHooks.text.set:function(a,b){a.value=b;return a},jQuery.extend(c.valHooks,
{text:{get:function(a){var b=c(a);if(b.data("_inputmask")){if(b.data("_inputmask").opts.autoUnmask)return b.inputmask("unmaskedvalue");a=d(a);b=b.data("_inputmask");return a!=b.masksets[b.activeMasksetIndex]._buffer.join("")?a:""}return d(a)},set:function(a,b){var d=c(a),f=e(a,b);d.data("_inputmask")&&d.triggerHandler("setvalue.inputmask");return f},inputmaskpatch:!0}})}function Y(a,b,g,c){var l=h();if(!1!==c)for(;!r(a)&&0<=a-1;)a--;for(c=a;c<b&&c<p();c++)if(r(c)){Q(l,c);var m=q(c),L=F(l,m);if(L!=
y(m))if(m<p()&&!1!==k(c,L,!0)&&e()[n(c)].def==e()[n(m)].def)G(l,c,L,!0);else if(r(c))break}else Q(l,c);void 0!=g&&G(l,w(b),g);if(!1==d().greedy){b=V(l.join("")).split("");l.length=b.length;c=0;for(g=l.length;c<g;c++)l[c]=b[c];0==l.length&&(d().buffer=f().slice())}return a}function ba(a,b,g){var c=h();if(F(c,a,!0)!=y(a))for(var l=w(b);l>a&&0<=l;l--)if(r(l)){var m=w(l),q=F(c,m);if(q!=y(m))if(!1!==k(m,q,!0)&&e()[n(l)].def==e()[n(m)].def)G(c,l,q,!0),Q(c,m);else break}else Q(c,l);void 0!=g&&F(c,a)==y(a)&&
G(c,a,g);a=c.length;if(!1==d().greedy){g=V(c.join("")).split("");c.length=g.length;l=0;for(m=c.length;l<m;l++)c[l]=g[l];0==c.length&&(d().buffer=f().slice())}return b-(a-c.length)}function ca(b,e,c){if(g.numericInput||A){switch(e){case g.keyCode.BACKSPACE:e=g.keyCode.DELETE;break;case g.keyCode.DELETE:e=g.keyCode.BACKSPACE}if(A){var f=c.end;c.end=c.begin;c.begin=f}}f=!0;c.begin==c.end?(f=e==g.keyCode.BACKSPACE?c.begin-1:c.begin,g.isNumeric&&""!=g.radixPoint&&h()[f]==g.radixPoint&&(c.begin=h().length-
1==f?c.begin:e==g.keyCode.BACKSPACE?f:q(f),c.end=c.begin),f=!1,e==g.keyCode.BACKSPACE?c.begin--:e==g.keyCode.DELETE&&c.end++):1!=c.end-c.begin||g.insertMode||(f=!1,e==g.keyCode.BACKSPACE&&c.begin--);U(h(),c.begin,c.end);var k=p();if(!1==g.greedy)Y(c.begin,k,void 0,!A&&e==g.keyCode.BACKSPACE&&!f);else{for(var l=c.begin,m=c.begin;m<c.end;m++)if(r(m)||!f)l=Y(c.begin,k,void 0,!A&&e==g.keyCode.BACKSPACE&&!f);f||(c.begin=l)}e=q(-1);U(h(),c.begin,c.end,!0);M(b,!1,void 0==a[1]||e>=c.end,h());d().lastValidPosition<
e?(d().lastValidPosition=-1,d().p=e):d().p=c.begin}function da(a){T=!1;var b=this,e=c(b),k=a.keyCode,m=v(b);k==g.keyCode.BACKSPACE||k==g.keyCode.DELETE||t&&127==k||a.ctrlKey&&88==k?(a.preventDefault(),88==k&&(K=h().join("")),ca(b,k,m),l(),J(b,h(),d().p),b._valueGet()==f().join("")&&e.trigger("cleared"),g.showTooltip&&e.prop("title",d().mask)):k==g.keyCode.END||k==g.keyCode.PAGE_DOWN?setTimeout(function(){var e=q(d().lastValidPosition);g.insertMode||e!=p()||a.shiftKey||e--;v(b,a.shiftKey?m.begin:e,
e)},0):k==g.keyCode.HOME&&!a.shiftKey||k==g.keyCode.PAGE_UP?v(b,0,a.shiftKey?m.begin:0):k==g.keyCode.ESCAPE||90==k&&a.ctrlKey?(M(b,!0,!1,K.split("")),e.click()):k!=g.keyCode.INSERT||a.shiftKey||a.ctrlKey?!1!=g.insertMode||a.shiftKey||(k==g.keyCode.RIGHT?setTimeout(function(){var a=v(b);v(b,a.begin)},0):k==g.keyCode.LEFT&&setTimeout(function(){var a=v(b);v(b,a.begin-1)},0)):(g.insertMode=!g.insertMode,v(b,g.insertMode||m.begin!=p()?m.begin:m.begin-1));e=v(b);!0===g.onKeyDown.call(this,a,h(),g)&&v(b,
e.begin,e.end);Z=-1!=c.inArray(k,g.ignorables)}function ea(e,f,m,n,r,z){if(void 0==m&&T)return!1;T=!0;var u=c(this);e=e||window.event;m=m||e.which||e.charCode||e.keyCode;if((!e.ctrlKey||!e.altKey)&&(e.ctrlKey||e.metaKey||Z)&&!0!==f)return!0;if(m){!0!==f&&46==m&&!1==e.shiftKey&&","==g.radixPoint&&(m=44);var t,N,x=String.fromCharCode(m);f?(m=r?z:d().lastValidPosition+1,t={begin:m,end:m}):t=v(this);z=A?1<t.begin-t.end||1==t.begin-t.end&&g.insertMode:1<t.end-t.begin||1==t.end-t.begin&&g.insertMode;var D=
b;z&&(b=D,c.each(a,function(a,e){"object"==typeof e&&(b=a,d().undoBuffer=h().join(""))}),ca(this,g.keyCode.DELETE,t),g.insertMode||c.each(a,function(a,e){"object"==typeof e&&(b=a,ba(t.begin,p()),d().lastValidPosition=q(d().lastValidPosition))}),b=D);var C=h().join("").indexOf(g.radixPoint);g.isNumeric&&!0!==f&&-1!=C&&(g.greedy&&t.begin<=C?(t.begin=w(t.begin),t.end=t.begin):x==g.radixPoint&&(t.begin=C,t.end=t.begin));var B=t.begin;m=k(B,x,r);!0===r&&(m=[{activeMasksetIndex:b,result:m}]);var E=-1;c.each(m,
function(a,e){b=e.activeMasksetIndex;d().writeOutBuffer=!0;var c=e.result;if(!1!==c){var f=!1,k=h();!0!==c&&(f=c.refresh,B=void 0!=c.pos?c.pos:B,x=void 0!=c.c?c.c:x);if(!0!==f){if(!0==g.insertMode){c=p();for(k=k.slice();F(k,c,!0)!=y(c)&&c>=B;)c=0==c?-1:w(c);c>=B?(ba(B,p(),x),k=d().lastValidPosition,c=q(k),c!=p()&&k>=B&&F(h(),c,!0)!=y(c)&&(d().lastValidPosition=c)):d().writeOutBuffer=!1}else G(k,B,x,!0);if(-1==E||E>q(B))E=q(B)}else!r&&(k=B<p()?B+1:B,-1==E||E>k)&&(E=k);E>d().p&&(d().p=E)}});!0!==r&&
(b=D,l());if(!1!==n&&(c.each(m,function(a,d){if(d.activeMasksetIndex==b)return N=d,!1}),void 0!=N)){var K=this;setTimeout(function(){g.onKeyValidation.call(K,N.result,g)},0);if(d().writeOutBuffer&&!1!==N.result){var I=h();n=f?void 0:g.numericInput?B>C?w(E):x==g.radixPoint?E-1:w(E-1):E;J(this,I,n);!0!==f&&setTimeout(function(){!0===O(I)&&u.trigger("complete")},0)}else z&&(d().buffer=d().undoBuffer.split(""))}g.showTooltip&&u.prop("title",d().mask);e.preventDefault()}}function W(a){var b=c(this),d=
a.keyCode,e=h();m&&d==g.keyCode.BACKSPACE&&ga==this._valueGet()&&da.call(this,a);g.onKeyUp.call(this,a,e,g);d==g.keyCode.TAB&&g.showMaskOnFocus&&(b.hasClass("focus.inputmask")&&0==this._valueGet().length?(e=f().slice(),J(this,e),v(this,0),K=h().join("")):(J(this,e),e.join("")==f().join("")&&-1!=c.inArray(g.radixPoint,e)?(v(this,C(0)),b.click()):v(this,C(0),C(p()))))}u=c(z);if(u.is(":input")){u.data("_inputmask",{masksets:a,activeMasksetIndex:b,opts:g,isRTL:!1});g.showTooltip&&u.prop("title",d().mask);
d().greedy=d().greedy?d().greedy:0==d().repeat;if(null!=u.attr("maxLength")){var I=u.prop("maxLength");-1<I&&c.each(a,function(a,b){"object"==typeof b&&"*"==b.repeat&&(b.repeat=I)});p()>=I&&-1<I&&(I<f().length&&(f().length=I),!1==d().greedy&&(d().repeat=Math.round(I/f().length)),u.prop("maxLength",2*p()))}D(z);var T=!1,Z=!1;g.numericInput&&(g.isNumeric=g.numericInput);("rtl"==z.dir||g.numericInput&&g.rightAlignNumerics||g.isNumeric&&g.rightAlignNumerics)&&u.css("text-align","right");if("rtl"==z.dir||
g.numericInput){z.dir="ltr";u.removeAttr("dir");var $=u.data("_inputmask");$.isRTL=!0;u.data("_inputmask",$);A=!0}u.unbind(".inputmask");u.removeClass("focus.inputmask");u.closest("form").bind("submit",function(){K!=h().join("")&&u.change()}).bind("reset",function(){setTimeout(function(){u.trigger("setvalue")},0)});u.bind("mouseenter.inputmask",function(){!c(this).hasClass("focus.inputmask")&&g.showMaskOnHover&&this._valueGet()!=h().join("")&&J(this,h())}).bind("blur.inputmask",function(){var d=c(this),
e=this._valueGet(),k=h();d.removeClass("focus.inputmask");K!=h().join("")&&d.change();g.clearMaskOnLostFocus&&""!=e&&(e==f().join("")?this._valueSet(""):X(this));!1===O(k)&&(d.trigger("incomplete"),g.clearIncomplete&&(c.each(a,function(a,b){"object"==typeof b&&(b.buffer=b._buffer.slice(),b.lastValidPosition=-1)}),b=0,g.clearMaskOnLostFocus?this._valueSet(""):(k=f().slice(),J(this,k))))}).bind("focus.inputmask",function(){var a=c(this),b=this._valueGet();g.showMaskOnFocus&&!a.hasClass("focus.inputmask")&&
(!g.showMaskOnHover||g.showMaskOnHover&&""==b)&&this._valueGet()!=h().join("")&&J(this,h(),q(d().lastValidPosition));a.addClass("focus.inputmask");K=h().join("")}).bind("mouseleave.inputmask",function(){var a=c(this);g.clearMaskOnLostFocus&&(a.hasClass("focus.inputmask")||this._valueGet()==a.attr("placeholder")||(this._valueGet()==f().join("")||""==this._valueGet()?this._valueSet(""):X(this)))}).bind("click.inputmask",function(){var a=this;setTimeout(function(){var b=v(a),e=h();if(b.begin==b.end){var b=
g.isRTL?C(b.begin):b.begin,f=d().lastValidPosition,e=g.isNumeric?!1===g.skipRadixDance&&""!=g.radixPoint&&-1!=c.inArray(g.radixPoint,e)?g.numericInput?q(c.inArray(g.radixPoint,e)):c.inArray(g.radixPoint,e):q(f):q(f);b<e?r(b)?v(a,b):v(a,q(b)):v(a,e)}},0)}).bind("dblclick.inputmask",function(){var a=this;setTimeout(function(){v(a,0,q(d().lastValidPosition))},0)}).bind(N+".inputmask dragdrop.inputmask drop.inputmask",function(a){var b=this,d=c(b);if("propertychange"==a.type&&b._valueGet().length<=p())return!0;
setTimeout(function(){M(b,!0,!1,void 0,!0);!0===O(h())&&d.trigger("complete");d.click()},0)}).bind("setvalue.inputmask",function(){M(this,!0);K=h().join("");this._valueGet()==f().join("")&&this._valueSet("")}).bind("_keypress.inputmask",ea).bind("complete.inputmask",g.oncomplete).bind("incomplete.inputmask",g.onincomplete).bind("cleared.inputmask",g.oncleared).bind("keyup.inputmask",W);m?u.bind("input.inputmask",function(a){a=c(this);ga=h().join("");M(this,!1,!1);J(this,h());!0===O(h())&&a.trigger("complete");
a.click()}):u.bind("keydown.inputmask",da).bind("keypress.inputmask",ea);M(z,!0,!1);K=h().join("");var aa;try{aa=document.activeElement}catch(fa){}aa===z?(u.addClass("focus.inputmask"),v(z,q(d().lastValidPosition))):g.clearMaskOnLostFocus?h().join("")==f().join("")?z._valueSet(""):X(z):J(z,h());P(z)}};return this}var g=c.extend(!0,{},c.inputmask.defaults,d),w=null!==navigator.userAgent.match(/msie 10/i),t=null!==navigator.userAgent.match(/iphone/i),z=null!==navigator.userAgent.match(/android.*safari.*/i),
m=null!==navigator.userAgent.match(/android.*chrome.*/i),N=e("paste")&&!w?"paste":e("input")?"input":"propertychange",l,q=0;if("string"===typeof a)switch(a){case "mask":return f(g.alias,d),l=k(),0==l.length?this:this.each(function(){n(c.extend(!0,{},l),0).mask(this)});case "unmaskedvalue":return w=c(this),w.data("_inputmask")?(l=w.data("_inputmask").masksets,q=w.data("_inputmask").activeMasksetIndex,g=w.data("_inputmask").opts,n(l,q).unmaskedvalue(w)):w.val();case "remove":return this.each(function(){var a=
c(this);if(a.data("_inputmask")){l=a.data("_inputmask").masksets;q=a.data("_inputmask").activeMasksetIndex;g=a.data("_inputmask").opts;this._valueSet(n(l,q).unmaskedvalue(a,!0));a.removeData("_inputmask");a.unbind(".inputmask");a.removeClass("focus.inputmask");var b;Object.getOwnPropertyDescriptor&&(b=Object.getOwnPropertyDescriptor(this,"value"));b&&b.get?this._valueGet&&Object.defineProperty(this,"value",{get:this._valueGet,set:this._valueSet}):document.__lookupGetter__&&this.__lookupGetter__("value")&&
this._valueGet&&(this.__defineGetter__("value",this._valueGet),this.__defineSetter__("value",this._valueSet));try{delete this._valueGet,delete this._valueSet}catch(d){this._valueSet=this._valueGet=void 0}}});case "getemptymask":return this.data("_inputmask")?(l=this.data("_inputmask").masksets,q=this.data("_inputmask").activeMasksetIndex,l[q]._buffer.join("")):"";case "hasMaskedValue":return this.data("_inputmask")?!this.data("_inputmask").opts.autoUnmask:!1;case "isComplete":return l=this.data("_inputmask").masksets,
q=this.data("_inputmask").activeMasksetIndex,g=this.data("_inputmask").opts,n(l,q).isComplete(this[0]._valueGet().split(""));case "getmetadata":if(this.data("_inputmask"))return l=this.data("_inputmask").masksets,q=this.data("_inputmask").activeMasksetIndex,l[q].metadata;return;default:return f(a,d)||(g.mask=a),l=k(),0==l.length?this:this.each(function(){n(c.extend(!0,{},l),q).mask(this)})}else{if("object"==typeof a)return g=c.extend(!0,{},c.inputmask.defaults,a),f(g.alias,a),l=k(),0==l.length?this:
this.each(function(){n(c.extend(!0,{},l),q).mask(this)});if(void 0==a)return this.each(function(){var a=c(this).attr("data-inputmask");if(a&&""!=a)try{var a=a.replace(RegExp("'","g"),'"'),b=c.parseJSON("{"+a+"}");c.extend(!0,b,d);g=c.extend(!0,{},c.inputmask.defaults,b);f(g.alias,b);g.alias=void 0;c(this).inputmask(g)}catch(e){}})}return this})})(jQuery);
(function(c){c.extend(c.inputmask.defaults.definitions,{A:{validator:"[A-Za-z]",cardinality:1,casing:"upper"},"#":{validator:"[A-Za-z\u0410-\u044f\u0401\u04510-9]",cardinality:1,casing:"upper"}});c.extend(c.inputmask.defaults.aliases,{url:{mask:"ir",placeholder:"",separator:"",defaultPrefix:"http://",regex:{urlpre1:/[fh]/,urlpre2:/(ft|ht)/,urlpre3:/(ftp|htt)/,urlpre4:/(ftp:|http|ftps)/,urlpre5:/(ftp:\/|ftps:|http:|https)/,urlpre6:/(ftp:\/\/|ftps:\/|http:\/|https:)/,urlpre7:/(ftp:\/\/|ftps:\/\/|http:\/\/|https:\/)/,
urlpre8:/(ftp:\/\/|ftps:\/\/|http:\/\/|https:\/\/)/},definitions:{i:{validator:function(a,d,e,c,b){return!0},cardinality:8,prevalidator:function(){for(var a=[],d=0;8>d;d++)a[d]=function(){var a=d;return{validator:function(d,b,c,k,y){if(y.regex["urlpre"+(a+1)]){var n=d;0<a+1-d.length&&(n=b.join("").substring(0,a+1-d.length)+""+n);d=y.regex["urlpre"+(a+1)].test(n);if(!k&&!d){c-=a;for(k=0;k<y.defaultPrefix.length;k++)b[c]=y.defaultPrefix[k],c++;for(k=0;k<n.length-1;k++)b[c]=n[k],c++;return{pos:c}}return d}return!1},
cardinality:a}}();return a}()},r:{validator:".",cardinality:50}},insertMode:!1,autoUnmask:!1},ip:{mask:["[[x]y]z.[[x]y]z.[[x]y]z.x[yz]","[[x]y]z.[[x]y]z.[[x]y]z.[[x]y][z]"],definitions:{x:{validator:"[012]",cardinality:1,definitionSymbol:"i"},y:{validator:function(a,d,e,c,b){a=-1<e-1&&"."!=d[e-1]?d[e-1]+a:"0"+a;return/2[0-5]|[01][0-9]/.test(a)},cardinality:1,definitionSymbol:"i"},z:{validator:function(a,d,e,c,b){-1<e-1&&"."!=d[e-1]?(a=d[e-1]+a,a=-1<e-2&&"."!=d[e-2]?d[e-2]+a:"0"+a):a="00"+a;return/25[0-5]|2[0-4][0-9]|[01][0-9][0-9]/.test(a)},
cardinality:1,definitionSymbol:"i"}}}})})(jQuery);
(function(c){c.extend(c.inputmask.defaults.definitions,{h:{validator:"[01][0-9]|2[0-3]",cardinality:2,prevalidator:[{validator:"[0-2]",cardinality:1}]},s:{validator:"[0-5][0-9]",cardinality:2,prevalidator:[{validator:"[0-5]",cardinality:1}]},d:{validator:"0[1-9]|[12][0-9]|3[01]",cardinality:2,prevalidator:[{validator:"[0-3]",cardinality:1}]},m:{validator:"0[1-9]|1[012]",cardinality:2,prevalidator:[{validator:"[01]",cardinality:1}]},y:{validator:"(19|20)\\d{2}",cardinality:4,prevalidator:[{validator:"[12]",
cardinality:1},{validator:"(19|20)",cardinality:2},{validator:"(19|20)\\d",cardinality:3}]}});c.extend(c.inputmask.defaults.aliases,{"dd/mm/yyyy":{mask:"1/2/y",placeholder:"dd/mm/yyyy",regex:{val1pre:/[0-3]/,val1:/0[1-9]|[12][0-9]|3[01]/,val2pre:function(a){a=c.inputmask.escapeRegex.call(this,a);return RegExp("((0[1-9]|[12][0-9]|3[01])"+a+"[01])")},val2:function(a){a=c.inputmask.escapeRegex.call(this,a);return RegExp("((0[1-9]|[12][0-9])"+a+"(0[1-9]|1[012]))|(30"+a+"(0[13-9]|1[012]))|(31"+a+"(0[13578]|1[02]))")}},
leapday:"29/02/",separator:"/",yearrange:{minyear:1900,maxyear:2099},isInYearRange:function(a,d,e){var c=parseInt(a.concat(d.toString().slice(a.length)));a=parseInt(a.concat(e.toString().slice(a.length)));return(NaN!=c?d<=c&&c<=e:!1)||(NaN!=a?d<=a&&a<=e:!1)},determinebaseyear:function(a,d,e){var c=(new Date).getFullYear();if(a>c)return a;if(d<c){for(var c=d.toString().slice(0,2),b=d.toString().slice(2,4);d<c+e;)c--;d=c+b;return a>d?a:d}return c},onKeyUp:function(a,d,e){d=c(this);a.ctrlKey&&a.keyCode==
e.keyCode.RIGHT&&(a=new Date,d.val(a.getDate().toString()+(a.getMonth()+1).toString()+a.getFullYear().toString()))},definitions:{1:{validator:function(a,d,c,f,b){var h=b.regex.val1.test(a);return f||h||a.charAt(1)!=b.separator&&-1=="-./".indexOf(a.charAt(1))||!(h=b.regex.val1.test("0"+a.charAt(0)))?h:(d[c-1]="0",{pos:c,c:a.charAt(0)})},cardinality:2,prevalidator:[{validator:function(a,d,c,f,b){var h=b.regex.val1pre.test(a);return f||h||!(h=b.regex.val1.test("0"+a))?h:(d[c]="0",c++,{pos:c})},cardinality:1}]},
2:{validator:function(a,d,c,f,b){var h=d.join("").substr(0,3),k=b.regex.val2(b.separator).test(h+a);return f||k||a.charAt(1)!=b.separator&&-1=="-./".indexOf(a.charAt(1))||!(k=b.regex.val2(b.separator).test(h+"0"+a.charAt(0)))?k:(d[c-1]="0",{pos:c,c:a.charAt(0)})},cardinality:2,prevalidator:[{validator:function(a,d,c,f,b){var h=d.join("").substr(0,3),k=b.regex.val2pre(b.separator).test(h+a);return f||k||!(k=b.regex.val2(b.separator).test(h+"0"+a))?k:(d[c]="0",c++,{pos:c})},cardinality:1}]},y:{validator:function(a,
d,c,f,b){if(b.isInYearRange(a,b.yearrange.minyear,b.yearrange.maxyear)){if(d.join("").substr(0,6)!=b.leapday)return!0;a=parseInt(a,10);return 0===a%4?0===a%100?0===a%400?!0:!1:!0:!1}return!1},cardinality:4,prevalidator:[{validator:function(a,d,c,f,b){var h=b.isInYearRange(a,b.yearrange.minyear,b.yearrange.maxyear);if(!f&&!h){f=b.determinebaseyear(b.yearrange.minyear,b.yearrange.maxyear,a+"0").toString().slice(0,1);if(h=b.isInYearRange(f+a,b.yearrange.minyear,b.yearrange.maxyear))return d[c++]=f[0],
{pos:c};f=b.determinebaseyear(b.yearrange.minyear,b.yearrange.maxyear,a+"0").toString().slice(0,2);if(h=b.isInYearRange(f+a,b.yearrange.minyear,b.yearrange.maxyear))return d[c++]=f[0],d[c++]=f[1],{pos:c}}return h},cardinality:1},{validator:function(a,d,c,f,b){var h=b.isInYearRange(a,b.yearrange.minyear,b.yearrange.maxyear);if(!f&&!h){f=b.determinebaseyear(b.yearrange.minyear,b.yearrange.maxyear,a).toString().slice(0,2);if(h=b.isInYearRange(a[0]+f[1]+a[1],b.yearrange.minyear,b.yearrange.maxyear))return d[c++]=
f[1],{pos:c};f=b.determinebaseyear(b.yearrange.minyear,b.yearrange.maxyear,a).toString().slice(0,2);b.isInYearRange(f+a,b.yearrange.minyear,b.yearrange.maxyear)?d.join("").substr(0,6)!=b.leapday?h=!0:(b=parseInt(a,10),h=0===b%4?0===b%100?0===b%400?!0:!1:!0:!1):h=!1;if(h)return d[c-1]=f[0],d[c++]=f[1],d[c++]=a[0],{pos:c}}return h},cardinality:2},{validator:function(a,d,c,f,b){return b.isInYearRange(a,b.yearrange.minyear,b.yearrange.maxyear)},cardinality:3}]}},insertMode:!1,autoUnmask:!1},"mm/dd/yyyy":{placeholder:"mm/dd/yyyy",
alias:"dd/mm/yyyy",regex:{val2pre:function(a){a=c.inputmask.escapeRegex.call(this,a);return RegExp("((0[13-9]|1[012])"+a+"[0-3])|(02"+a+"[0-2])")},val2:function(a){a=c.inputmask.escapeRegex.call(this,a);return RegExp("((0[1-9]|1[012])"+a+"(0[1-9]|[12][0-9]))|((0[13-9]|1[012])"+a+"30)|((0[13578]|1[02])"+a+"31)")},val1pre:/[01]/,val1:/0[1-9]|1[012]/},leapday:"02/29/",onKeyUp:function(a,d,e){d=c(this);a.ctrlKey&&a.keyCode==e.keyCode.RIGHT&&(a=new Date,d.val((a.getMonth()+1).toString()+a.getDate().toString()+
a.getFullYear().toString()))}},"yyyy/mm/dd":{mask:"y/1/2",placeholder:"yyyy/mm/dd",alias:"mm/dd/yyyy",leapday:"/02/29",onKeyUp:function(a,d,e){d=c(this);a.ctrlKey&&a.keyCode==e.keyCode.RIGHT&&(a=new Date,d.val(a.getFullYear().toString()+(a.getMonth()+1).toString()+a.getDate().toString()))},definitions:{2:{validator:function(a,d,c,f,b){var h=d.join("").substr(5,3),k=b.regex.val2(b.separator).test(h+a);if(!(f||k||a.charAt(1)!=b.separator&&-1=="-./".indexOf(a.charAt(1)))&&(k=b.regex.val2(b.separator).test(h+
"0"+a.charAt(0))))return d[c-1]="0",{pos:c,c:a.charAt(0)};if(k){if(d.join("").substr(4,4)+a!=b.leapday)return!0;a=parseInt(d.join("").substr(0,4),10);return 0===a%4?0===a%100?0===a%400?!0:!1:!0:!1}return k},cardinality:2,prevalidator:[{validator:function(a,d,c,f,b){var h=d.join("").substr(5,3),k=b.regex.val2pre(b.separator).test(h+a);return f||k||!(k=b.regex.val2(b.separator).test(h+"0"+a))?k:(d[c]="0",c++,{pos:c})},cardinality:1}]}}},"dd.mm.yyyy":{mask:"1.2.y",placeholder:"dd.mm.yyyy",leapday:"29.02.",
separator:".",alias:"dd/mm/yyyy"},"dd-mm-yyyy":{mask:"1-2-y",placeholder:"dd-mm-yyyy",leapday:"29-02-",separator:"-",alias:"dd/mm/yyyy"},"mm.dd.yyyy":{mask:"1.2.y",placeholder:"mm.dd.yyyy",leapday:"02.29.",separator:".",alias:"mm/dd/yyyy"},"mm-dd-yyyy":{mask:"1-2-y",placeholder:"mm-dd-yyyy",leapday:"02-29-",separator:"-",alias:"mm/dd/yyyy"},"yyyy.mm.dd":{mask:"y.1.2",placeholder:"yyyy.mm.dd",leapday:".02.29",separator:".",alias:"yyyy/mm/dd"},"yyyy-mm-dd":{mask:"y-1-2",placeholder:"yyyy-mm-dd",leapday:"-02-29",
separator:"-",alias:"yyyy/mm/dd"},datetime:{mask:"1/2/y h:s",placeholder:"dd/mm/yyyy hh:mm",alias:"dd/mm/yyyy",regex:{hrspre:/[012]/,hrs24:/2[0-9]|1[3-9]/,hrs:/[01][0-9]|2[0-3]/,ampm:/^[a|p|A|P][m|M]/},timeseparator:":",hourFormat:"24",definitions:{h:{validator:function(a,d,c,f,b){var h=b.regex.hrs.test(a);return f||h||a.charAt(1)!=b.timeseparator&&-1=="-.:".indexOf(a.charAt(1))||!(h=b.regex.hrs.test("0"+a.charAt(0)))?h&&"24"!==b.hourFormat&&b.regex.hrs24.test(a)?(a=parseInt(a,10),d[c+5]=24==a?"a":
"p",d[c+6]="m",a-=12,10>a?(d[c]=a.toString(),d[c-1]="0"):(d[c]=a.toString().charAt(1),d[c-1]=a.toString().charAt(0)),{pos:c,c:d[c]}):h:(d[c-1]="0",d[c]=a.charAt(0),c++,{pos:c})},cardinality:2,prevalidator:[{validator:function(a,d,c,f,b){var h=b.regex.hrspre.test(a);return f||h||!(h=b.regex.hrs.test("0"+a))?h:(d[c]="0",c++,{pos:c})},cardinality:1}]},t:{validator:function(a,c,e,f,b){return b.regex.ampm.test(a+"m")},casing:"lower",cardinality:1}},insertMode:!1,autoUnmask:!1},datetime12:{mask:"1/2/y h:s t\\m",
placeholder:"dd/mm/yyyy hh:mm xm",alias:"datetime",hourFormat:"12"},"hh:mm t":{mask:"h:s t\\m",placeholder:"hh:mm xm",alias:"datetime",hourFormat:"12"},"h:s t":{mask:"h:s t\\m",placeholder:"hh:mm xm",alias:"datetime",hourFormat:"12"},"hh:mm:ss":{mask:"h:s:s",autoUnmask:!1},"hh:mm":{mask:"h:s",autoUnmask:!1},date:{alias:"dd/mm/yyyy"},"mm/yyyy":{mask:"1/y",placeholder:"mm/yyyy",leapday:"donotuse",separator:"/",alias:"mm/dd/yyyy"}})})(jQuery);
(function(c){c.extend(c.inputmask.defaults.aliases,{decimal:{mask:"~",placeholder:"",repeat:"*",greedy:!1,numericInput:!1,isNumeric:!0,digits:"*",groupSeparator:"",radixPoint:".",groupSize:3,autoGroup:!1,allowPlus:!0,allowMinus:!0,integerDigits:"*",defaultValue:"",prefix:"",suffix:"",getMaskLength:function(a,d,e,f,b){var h=a.length;d||("*"==e?h=f.length+1:1<e&&(h+=a.length*(e-1)));a=c.inputmask.escapeRegex.call(this,b.groupSeparator);b=c.inputmask.escapeRegex.call(this,b.radixPoint);f=f.join("");
b=f.replace(RegExp(a,"g"),"").replace(RegExp(b),"");return h+(f.length-b.length)},postFormat:function(a,d,e,f){if(""==f.groupSeparator)return d;var b=a.slice();c.inArray(f.radixPoint,a);e||b.splice(d,0,"?");b=b.join("");if(f.autoGroup||e&&-1!=b.indexOf(f.groupSeparator)){for(var h=c.inputmask.escapeRegex.call(this,f.groupSeparator),b=b.replace(RegExp(h,"g"),""),h=b.split(f.radixPoint),b=h[0],k=RegExp("([-+]?[\\d?]+)([\\d?]{"+f.groupSize+"})");k.test(b);)b=b.replace(k,"$1"+f.groupSeparator+"$2"),b=
b.replace(f.groupSeparator+f.groupSeparator,f.groupSeparator);1<h.length&&(b+=f.radixPoint+h[1])}a.length=b.length;f=0;for(h=b.length;f<h;f++)a[f]=b.charAt(f);b=c.inArray("?",a);e||a.splice(b,1);return e?d:b},regex:{number:function(a){var d=c.inputmask.escapeRegex.call(this,a.groupSeparator),e=c.inputmask.escapeRegex.call(this,a.radixPoint),f=isNaN(a.digits)?a.digits:"{0,"+a.digits+"}";return RegExp("^"+("["+(a.allowPlus?"+":"")+(a.allowMinus?"-":"")+"]?")+"(\\d+|\\d{1,"+a.groupSize+"}(("+d+"\\d{"+
a.groupSize+"})?)+)("+e+"\\d"+f+")?$")}},onKeyDown:function(a,d,e){var f=c(this);if(a.keyCode==e.keyCode.TAB){if(a=c.inArray(e.radixPoint,d),-1!=a){for(var b=f.data("_inputmask").masksets,f=f.data("_inputmask").activeMasksetIndex,h=1;h<=e.digits&&h<e.getMaskLength(b[f]._buffer,b[f].greedy,b[f].repeat,d,e);h++)if(void 0==d[a+h]||""==d[a+h])d[a+h]="0";this._valueSet(d.join(""))}}else if(a.keyCode==e.keyCode.DELETE||a.keyCode==e.keyCode.BACKSPACE)return e.postFormat(d,0,!0,e),this._valueSet(d.join("")),
!0},definitions:{"~":{validator:function(a,d,e,f,b){if(""==a)return!1;if(!f&&1>=e&&"0"===d[0]&&/[\d-]/.test(a)&&1==d.length)return d[0]="",{pos:0};var h=f?d.slice(0,e):d.slice();h.splice(e,0,a);var h=h.join(""),k=c.inputmask.escapeRegex.call(this,b.groupSeparator),h=h.replace(RegExp(k,"g"),""),k=b.regex.number(b).test(h);if(!k&&(h+="0",k=b.regex.number(b).test(h),!k)){k=h.lastIndexOf(b.groupSeparator);for(i=h.length-k;3>=i;i++)h+="0";k=b.regex.number(b).test(h);if(!k&&!f&&a==b.radixPoint&&(k=b.regex.number(b).test("0"+
h+"0")))return d[e]="0",e++,{pos:e}}return!1==k||f||a==b.radixPoint?k:{pos:b.postFormat(d,e,!1,b)}},cardinality:1,prevalidator:null}},insertMode:!0,autoUnmask:!1},integer:{regex:{number:function(a){var d=c.inputmask.escapeRegex.call(this,a.groupSeparator);return RegExp("^"+(a.allowPlus||a.allowMinus?"["+(a.allowPlus?"+":"")+(a.allowMinus?"-":"")+"]?":"")+"(\\d+|\\d{1,"+a.groupSize+"}(("+d+"\\d{"+a.groupSize+"})?)+)$")}},alias:"decimal"}})})(jQuery);
(function(c){c.extend(c.inputmask.defaults.aliases,{Regex:{mask:"r",greedy:!1,repeat:"*",regex:null,regexTokens:null,tokenizer:/\[\^?]?(?:[^\\\]]+|\\[\S\s]?)*]?|\\(?:0(?:[0-3][0-7]{0,2}|[4-7][0-7]?)?|[1-9][0-9]*|x[0-9A-Fa-f]{2}|u[0-9A-Fa-f]{4}|c[A-Za-z]|[\S\s]?)|\((?:\?[:=!]?)?|(?:[?*+]|\{[0-9]+(?:,[0-9]*)?\})\??|[^.?*+^${[()|\\]+|./g,quantifierFilter:/[0-9]+[^,]/,definitions:{r:{validator:function(a,c,e,f,b){function h(){this.matches=[];this.isLiteral=this.isQuantifier=this.isGroup=!1}function k(){var a=
new h,c,d=[];for(b.regexTokens=[];c=b.tokenizer.exec(b.regex);)switch(c=c[0],c.charAt(0)){case "[":case "\\":0<d.length?d[d.length-1].matches.push(c):a.matches.push(c);break;case "(":!a.isGroup&&0<a.matches.length&&b.regexTokens.push(a);a=new h;a.isGroup=!0;d.push(a);break;case ")":c=d.pop();0<d.length?d[d.length-1].matches.push(c):(b.regexTokens.push(c),a=new h);break;case "{":var e=new h;e.isQuantifier=!0;e.matches.push(c);0<d.length?d[d.length-1].matches.push(e):a.matches.push(e);break;default:e=
new h,e.isLiteral=!0,e.matches.push(c),0<d.length?d[d.length-1].matches.push(e):a.matches.push(e)}0<a.matches.length&&b.regexTokens.push(a)}function y(a,c){var d=!1;c&&(n+="(",g++);for(var e=0;e<a.matches.length;e++){var f=a.matches[e];if(!0==f.isGroup)d=y(f,!0);else if(!0==f.isQuantifier){for(var f=f.matches[0],h=b.quantifierFilter.exec(f)[0].replace("}",""),h=n+"{1,"+h+"}",k=0;k<g;k++)h+=")";d=RegExp("^("+h+")$");d=d.test(w);n+=f}else if(!0==f.isLiteral){for(var f=f.matches[0],h=n,R="",k=0;k<g;k++)R+=
")";for(k=0;k<f.length&&!(h=(h+f[k]).replace(/\|$/,""),d=RegExp("^("+h+R+")$"),d=d.test(w));k++);n+=f}else{n+=f;h=n.replace(/\|$/,"");for(k=0;k<g;k++)h+=")";d=RegExp("^("+h+")$");d=d.test(w)}if(d)break}c&&(n+=")",g--);return d}null==b.regexTokens&&k();f=c.slice();var n="";c=!1;var g=0;f.splice(e,0,a);var w=f.join("");for(a=0;a<b.regexTokens.length&&!(h=b.regexTokens[a],c=y(h,h.isGroup));a++);return c},cardinality:1}}}})})(jQuery);
(function(c){c.extend(c.inputmask.defaults.aliases,{phone:{url:"phone-codes/phone-codes.json",mask:function(a){a.definitions={p:{validator:function(){return!1},cardinality:1},"#":{validator:"[0-9]",cardinality:1}};var d=[];c.ajax({url:a.url,async:!1,dataType:"json",success:function(a){d=a}});d.splice(0,0,"+p(ppp)ppp-pppp");return d}}})})(jQuery);