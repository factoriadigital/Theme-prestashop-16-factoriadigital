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

<section class="footer-block col-xs-12 col-omi-xs5 featuredfooter blocks">
    	<h4>{l s='Featured Products' mod='blockfeaturedfooter'}</h4>
      <ul class="toggle-footer">
      	
        {if $featured_products}
        {foreach from=$featured_products item=product name=products}
        <li class="ajax_block_product {if $smarty.foreach.products.first}first_item{elseif $smarty.foreach.products.last}last_item{/if}">
        
         
        
            <div class="fproducts-box"> <a href="{$product.link}" title="{$product.name|escape:html:'UTF-8'}" class="footer-product-image">
              <div class="hover-box"> <img src="{$link->getImageLink($product.link_rewrite, $product.id_image, 'small_default')|escape:'html'}" height="{$smallSize.height}" width="{$smallSize.width}" alt="{$product.name|escape:html:'UTF-8'}" /></div>
              </a>  </div>
              <h5><a href="{$product.link|escape:'html'}" title="{$product.name|truncate:32:'...'|escape:'html':'UTF-8'}">{$product.name|truncate:24:'...'|escape:'html':'UTF-8'}</a></h5>
             
              <div class="fproducts_price"> {if $product.show_price AND !isset($restricted_country_mode) AND !$PS_CATALOG_MODE}
                <div class="fprice_container"><span class="price">{if !$priceDisplay}{convertPrice price=$product.price}{else}{convertPrice price=$product.price_tax_exc}{/if}</span></div>
                {else}
                <div style="height:21px;"></div>
                {/if} </div>
                           
      
         
          
        </li>
        {/foreach}
        {/if}
         
      </ul>
</section>
