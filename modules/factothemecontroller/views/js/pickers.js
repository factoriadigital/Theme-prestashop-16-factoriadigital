/*
* 2007-2015 PrestaShop
*
* NOTICE OF LICENSE
*
* This source file is subject to the Academic Free License (AFL 3.0)
* that is bundled with this package in the file LICENSE.txt.
* It is also available through the world-wide-web at this URL:
* http://opensource.org/licenses/afl-3.0.php
* If you did not receive a copy of the license and are unable to
* obtain it through the world-wide-web, please send an email
* to license@prestashop.com so we can send you a copy immediately.
*
* DISCLAIMER
*
* Do not edit or add to this file if you wish to upgrade PrestaShop to newer
* versions in the future. If you wish to customize PrestaShop for your
* needs please refer to http://www.prestashop.com for more information.
*
*  @author PrestaShop SA <contact@prestashop.com>
*  @copyright  2007-2015 PrestaShop SA
*  @license    http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
*  International Registered Trademark & Property of PrestaShop SA
*/

/*$(document).ready(function() {

	$(' #header_nav_bg, #header_nav_font, #header_bg, #header_font_color, #search_font_color,#search_icon_color,
#menu_bg,
#menu_font,
#menu_hover_bg,
#menu_hover_font,
#theme_bg,
#theme_font_color,
#price_font,
#footer_bg,
#footer_font_color,
#new_lable,
#new_lable_font,
#sale_lable,
#sale_lable_font,
#new_lable_hover,
#new_lable_font_hover,
#sale_lable_hover,
#sale_lable_font_hover,
#button_bg,
#button_bghover,
#button_font,
#button_hover_font,
#addcart_button_bg,
#addcart_button_bghover,
#addcart_button_font,
#addcart_button_hover_font').ColorPicker({
		onSubmit: function(hsb, hex, rgb, el) {
			$(el).val(hex);
			$(el).ColorPickerHide();
		},
		onBeforeShow: function () {
			$(this).ColorPickerSetColor(this.bindClass);
		}
	})
	
	
});*/

 function makeTabs(selector) {
 
    tab_lists_anchors = document.querySelectorAll(selector + " li a");
    divs = document.querySelector(selector).getElementsByTagName("div");
    for (var i = 0; i < tab_lists_anchors.length; i++) {
        if (tab_lists_anchors[i].classList.contains('active')) {
            divs[i].style.display = "block";
        }
 
    }
 
    for (i = 0; i < tab_lists_anchors.length; i++) {
 
        document.querySelectorAll(".tabs li a")[i].addEventListener('click', function(e) {
 
            for (i = 0; i < divs.length; i++) {
                divs[i].style.display = "none";
            }
 
            for (i = 0; i < tab_lists_anchors.length; i++) {
                tab_lists_anchors[i].classList.remove("active");
            }
 
            clicked_tab = e.target || e.srcElement;
 
            clicked_tab.classList.add('active');
            div_to_show = clicked_tab.getAttribute('href');
 
            document.querySelector(div_to_show).style.display = "block";
 
        });
    }
 
}