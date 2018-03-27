{*
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
*}

<style>


/* Header */

header .nav { background-color: #{$facto_controller.header_nav_bg|escape:'intval'} !important;}
header .nav, .shop-phone i, .shop-phone strong, nav #contact-link a, nav .header_user_info a, #languages-block-top div.current, #languages-block-top div.current:after { color: #{$facto_controller.header_nav_font|escape:'intval'} !important;}

.header-container { background-color: #{$facto_controller.header_bg|escape:'intval'} !important;}

.header-compare, a.header-wishlists, .cart_txt { color: #{$facto_controller.header_font_color|escape:'intval'} !important;}
#search_block_top .btn.button-search, .price_circle { background-color: #{$facto_controller.search_icon_color|escape:'intval'} !important;}
#search_block_top #search_query_top { color: #{$facto_controller.search_font_color|escape:'intval'} !important;}

.shopping_cart span.totalprice { color: #{$facto_controller.cart_font_color|escape:'intval'} !important;}


.menu-container { background-color: #{$facto_controller.menu_bg|escape:'intval'} !important;}
.sf-menu > li.sfHover, .sf-menu > li > a:hover { background-color: #{$facto_controller.menu_hover_bg|escape:'intval'} !important;}
ul.submenu-container { border-color: #{$facto_controller.menu_hover_bg|escape:'intval'} !important;}
.sf-menu > li > ul > li > a, .sf-menu li li li a:hover { color: #{$facto_controller.menu_hover_bg|escape:'intval'} !important;}

.sf-menu > li > a { color: #{$facto_controller.menu_font|escape:'intval'} !important;}
.sf-menu > li.sfHover, .sf-menu > li > a:hover { color: #{$facto_controller.menu_hover_font|escape:'intval'} !important;}

/* Theme Configure */
.other-pro .nav-tabs li.active > a, #casual_tabs ul.casual_title li.selected, #categories_block_left li span.grower,
.cartbottom_btn .add_to_compare:hover, .cartbottom_btn .addToWishlist:hover, .quick-view-wrapper .quick-view:hover, .other-pro .nav-tabs li a:hover, .other-pro .navslider a:hover, .ac_results li:hover, .ac_results li:focus, .ac_results li.ac_over, ul.step li.step_current, .table > thead > tr > th, .camera_bar_cont span { background-color: #{$facto_controller.theme_bg|escape:'intval'} !important;}

.breadcrumb, .cart_block .quantity-formated, #header .cart_block .price, #header .cart_block .priceco .price,
#layered_block_left .layered_subtitle, .breadcrumb a:hover, .header-wishlists:before, .header-compare:before { color: #{$facto_controller.theme_bg|escape:'intval'} !important;}
#layer_cart, #header .cart_block, #languages-block-top ul { border-color: #{$facto_controller.theme_bg|escape:'intval'} !important;}

/* Button Color */
.button.button-medium, .button.button-small, a.exclusive, span.exclusive, a.button, a#wishlist_button_nopop, a.lnk_more, .cat_desc .lnk_more,
.cart_block .cart-buttons a, .button.exclusive-medium, .cart_navigation .button-exclusive { background-color: #{$facto_controller.button_bg|escape:'intval'} !important;}

.cart_block .cart-buttons a#button_order_cart, .button.button-medium, .button.button-small, a.exclusive, span.exclusive, a.button, a#wishlist_button_nopop, a.lnk_more, .cat_desc .lnk_more,
.cart_block .cart-buttons a, .cart_navigation .button-exclusive, .button.exclusive-medium span, #layer_cart .layer_cart_cart .button-container span.exclusive-medium i,
a#wishlist_button_nopop:after { color: #{$facto_controller.button_font|escape:'intval'} !important;}

.button.button-medium:hover, .button.button-small:hover, a.exclusive:hover, span.exclusive:hover, a.button:hover, a#wishlist_button_nopop:hover, a.lnk_more:hover, .cat_desc .lnk_more:hover,
.cart_block .cart-buttons a:hover, .button.exclusive-medium:hover, .cart_navigation .button-exclusive:hover { background-color: #{$facto_controller.button_bghover|escape:'intval'} !important;}

a.exclusive:hover, span.exclusive:hover, a.button:hover, #add_to_cart input.exclusive:hover, a#wishlist_button_nopop:hover, a.lnk_more:hover, .button.button-medium:hover,
.cart_block .cart-buttons a#button_order_cart:hover span, .button.exclusive-medium:hover span, #layer_cart .layer_cart_cart .button-container span.exclusive-medium:hover i,
.cart_navigation .button-exclusive:hover, .cart_navigation .button-exclusive:focus, .cart_navigation .button-exclusive:active, a#wishlist_button_nopop:hover:after { color: #{$facto_controller.button_hover_font|escape:'intval'} !important;}

.button.ajax_add_to_cart_button:after, .button.ajax_add_to_cart_button, .box-info-product .exclusive { background-color: #{$facto_controller.addcart_button_bg|escape:'intval'} !important;}

.button.ajax_add_to_cart_button:hover:after, .button.ajax_add_to_cart_button:hover,  .box-info-product .exclusive:hover { background-color: #{$facto_controller.addcart_button_bghover|escape:'intval'} !important;}

.button.ajax_add_to_cart_button:after, .button.ajax_add_to_cart_button, .box-info-product .exclusive span, .box-info-product .exclusive:after { color: #{$facto_controller.addcart_button_font|escape:'intval'} !important;}

.button.ajax_add_to_cart_button:hover:after, .button.ajax_add_to_cart_button:hover, .box-info-product .exclusive:hover span, .box-info-product .exclusive:hover:after { color: #{$facto_controller.addcart_button_hover_font|escape:'intval'} !important;}

/* Theme Configure */
article.ajax_block_product span.new, .new-box span.new-label { background-color: #{$facto_controller.new_lable|escape:'intval'} !important;}
article.ajax_block_product span.new, .new-box span.new-label { color: #{$facto_controller.new_lable_font|escape:'intval'} !important;}

article.ajax_block_product:hover span.new, li.ajax_block_product:hover .new-box span.new-label { background-color: #{$facto_controller.new_lable_hover|escape:'intval'} !important;}
article.ajax_block_product:hover span.new, li.ajax_block_product:hover .new-box span.new-label  { color: #{$facto_controller.new_lable_font_hover|escape:'intval'} !important;}


article.ajax_block_product .sale, .sale-box span.sale-label { background-color: #{$facto_controller.sale_lable|escape:'intval'} !important;}
article.ajax_block_product:hover .sale, li.ajax_block_product:hover .sale-box span.sale-label { background-color: #{$facto_controller.sale_lable_hover|escape:'intval'} !important;}
article.ajax_block_product .sale, .sale-box span.sale-label { color: #{$facto_controller.sale_lable_font|escape:'intval'} !important;}
article.ajax_block_product:hover .sale, li.ajax_block_product:hover .sale-box span.sale-label { color: #{$facto_controller.sale_lable_font_hover|escape:'intval'} !important;}

.price.product-price, .our_price_display .price, .price, .price-box .price, .products_list_price .price { color: #{$facto_controller.price_font|escape:'intval'} !important;}

.footer-container { background-color: #{$facto_controller.footer_bg|escape:'intval'} !important;}
.footer-container #footer h4, .footer-container #footer a, .footer-container #footer #block_contact_infos > div ul li > span, .footer-container #footer #block_contact_infos > div ul li > span a { color: #{$facto_controller.footer_font_color|escape:'intval'} !important;}
</style>