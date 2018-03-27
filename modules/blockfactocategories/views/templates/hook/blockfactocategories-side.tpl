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
*  @author PrestaShop SA <contact@prestashop.com> *  @copyright  2007-2015 PrestaShop SA
*  @license    http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
*  International Registered Trademark & Property of PrestaShop SA
*}

{if $page_name =='index' && $page_name !='pagenotfound'}
{if $selCatNameArr|@count != 0}

<div id="casual_tabs" class="container">
  <section id="content">
    <div class="category-selector">
      <ul data-option-key="filter" class="casual_title casual-list-filter">
        {if $dispAll == "on"}
        <li> <span><a href="" data-categories="*">{l s='All' mod='blockfactocategories'}</a></span></li>
        {foreach from=$sel_cat_arr item=cat key=catname}
        <li><span><a href="" data-categories="{$cat|escape:'html':'UTF-8'}">{$selCatNameArr.$catname|escape:'html':'UTF-8'}</a></span></li>
        {/foreach}
        {else}   
        {foreach from=$sel_cat_arr item=cat key=catname}
        <li><span><a href="" data-categories="{$cat|escape:'html':'UTF-8'}">{$selCatNameArr.$catname|escape:'html':'UTF-8'}</a></span></li>
        {/foreach}
        {/if}
      </ul>
    </div>
    <div class="filter-container">
    <section class="casual_products" id="portfolio-items" class="clearfix">
    {foreach from=$products item=product name=factoCat}
    
    {assign var="prod_id" value=$product.id_product}
    <article class="ajax_block_product isotope-item {if $dispAll == "off"}isotope-hidden{/if}" id="product_{$product.id_product}">
      <div class="product_isotope">
        <div class="productlisting">
          <div class="border_inside">
            <div class="prod_img">
              <div class="prothumb">
                <ul class="thumbs_list">
                  {if isset($prodImages.$prod_id)}
                  
                  {foreach from=$prodImages.$prod_id item=image name=thumbnails}
                  
                  {assign var=imageIds value="`$prod_id`-`$image.id_image`"}
                  <input type="hidden" value="{$link->getImageLink($product.link_rewrite, $imageIds, 'home_default')|escape:'html':'UTF-8'}" id="hidden_{$image.id_image}" />
                  <li id="thumbnail_{$image.id_image|intval}" style="float:left;" class="thumbsimg"> <img id="thumb_{$image.id_image}_{$product.id_product}" src="{$link->getImageLink($product.link_rewrite, $imageIds, 'small_default')|escape:'html':'UTF-8'}" alt="{$product.name|escape:html:'UTF-8'}" class="thumb_image"/> </li>
                  {/foreach}
                  {/if}
                </ul>
              </div>
              <input type="hidden" id="prodImage_{$product.id_product|intval}" value="{$link->getImageLink($product.link_rewrite, $product.id_image, 'home_default')|escape:'html':'UTF-8'}" />
              <a href="{$product.link|escape:'html':'UTF-8'}" title="{$product.name|escape:html:'UTF-8'}" class="prodtimage product_image hoverproimg"> <img src="{$link->getImageLink($product.link_rewrite, $product.id_image, 'home_default')|escape:'html':'UTF-8'}" height="{$homeSize.height}" width="{$homeSize.width}" alt="{$product.name|escape:html:'UTF-8'}" id="productImage_{$product.id_product}"/> </a>
              <div class="cartbottom_btn"> {if isset($quick_view) && $quick_view}
                <div class="quick-view-wrapper"> <a class="quick-view" href="{$product.link|escape:'html':'UTF-8'}" rel="{$product.link|escape:'html':'UTF-8'}"> <i class="icon-eye-open"></i> </a> </div>
                {/if}
                
                {hook h='displayProductListFunctionalButtons' product=$product}
                {if isset($comparator_max_item) && $comparator_max_item}
                <div class="compare"> <a class="add_to_compare" href="{$product.link|escape:'html':'UTF-8'}" data-id-product="{$product.id_product}" title="{l s='Add to compare' mod='blockfactocategories'}"><i class="icon-signal"></i></a> </div>
                {/if} </div>
            </div>
            {if isset($product.new) && $product.new == 1}<span class="new">{l s='New' mod='blockfactocategories'}</span>{/if}
            
            {if isset($product.on_sale) && $product.on_sale && isset($product.show_price) && $product.show_price && !$PS_CATALOG_MODE} <span class="sale"> {l s='Sale!' mod='blockfactocategories'} </span> {/if}
            <h3><a href="{$product.link}" title="{$product.name|truncate:50:'...'|escape:'htmlall':'UTF-8'}">{$product.name|truncate:25:'...'|escape:'htmlall':'UTF-8'}</a></h3>
            {if (!$PS_CATALOG_MODE AND ((isset($product.show_price) && $product.show_price) || (isset($product.available_for_order) && $product.available_for_order)))}
            <div class="products_list_price"> {if isset($product.show_price) && $product.show_price && !isset($restricted_country_mode)}
              <p class="price_container"><span class="price"> {if !$priceDisplay}{convertPrice price=$product.price}{else}{convertPrice price=$product.price_tax_exc}{/if} </span> {if isset($product.specific_prices) && $product.specific_prices} <span class="old-price product-price"> {displayWtPrice p=$product.price_without_reduction} </span> {if isset($product.specific_prices.reduction) && $product.specific_prices.reduction && $product.specific_prices.reduction_type == 'percentage'} <span class="price-percent-reduction">-{$product.specific_prices.reduction * 100}%</span> {/if} </p>
              {/if}
              {/if} </div>
            {/if}
            {hook h='displayProductListReviews' product=$product}
            <div class="cart_btn"> {if ($product.id_product_attribute == 0 || (isset($add_prod_display) && ($add_prod_display == 1))) && $product.available_for_order && !isset($restricted_country_mode) && $product.minimal_quantity <= 1 && $product.customizable != 2 && !$PS_CATALOG_MODE}
              {if ($product.allow_oosp || $product.quantity > 0)}
              {if isset($static_token)} <a class="button ajax_add_to_cart_button btn btn-default" href="{$link->getPageLink('cart')|escape:'html'}?qty=1&amp;id_product={$product.id_product}&amp;token={$static_token}&amp;add" rel="nofollow" title="{l s='Add to cart' mod='blockfactocategories'}" data-id-product="{$product.id_product|intval}"> <span>{l s='Add to cart' mod='blockfactocategories'}</span> </a> {else} <a class="button ajax_add_to_cart_button btn btn-default" href="{$link->getPageLink('cart',false, NULL, 'add=1&amp;id_product={$product.id_product|intval}', false)|escape:'html':'UTF-8'}" rel="nofollow" title="{l s='Add to cart' mod='blockfactocategories'}" data-id-product="{$product.id_product|intval}"> <span>{l s='Add to cart' mod='blockfactocategories'}</span> </a> {/if}						
              {else} <a itemprop="url" class="button ajax_view_button" href="{$product.link|escape:'html':'UTF-8'}" title="{l s='View'}"> <span>{l s='View' mod='blockfactocategories'}</span> </a> {/if}
              {/if} </div>
          </div>
        </div>
      </div>
    </article>
    {/foreach} </section>
</div>
</section>
<input type="hidden" id="prodCategory" value="{$prodCategory|escape:'html':'UTF-8'}" />
</div>

{if $dispAll == "off"} 
<script type="text/javascript">
	$(document).ready(function(){
		var selIsotopeItem = $(".category-selector li.selected a");
	var selCatItems = selIsotopeItem.attr("data-categories");
	$("#portfolio-items").find("article."+selCatItems).removeClass("isotope-hidden");
	});
    </script> 
{/if}

{/if}{/if}