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
*  @version  Release: $Revision$
*  @license    http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
*  International Registered Trademark & Property of PrestaShop SA
*}

{if $page_name =='index'}
{if isset($simplesliders_slides)}
    <!-- Module Simplesliders -->
<div class="fluid_container">
{if isset($simplesliders_slides.0) && isset($simplesliders_slides.0.sizes.1)}{capture name='height'}{$simplesliders_slides.0.sizes.1}{/capture}{/if}
        	 <div class="camera_wrap" id="camera_wrap"{if isset($smarty.capture.height) && $smarty.capture.height} style="height:{$smarty.capture.height}px;"{/if}>
			
{foreach from=$simplesliders_slides item=slide}
	{if $slide.active}
	
	
		 <div data-src="{$smarty.const._MODULE_DIR_}simplesliders/views/img/{$slide.image|escape:'htmlall':'UTF-8'}" data-link="{$slide.url|escape:'htmlall':'UTF-8'}" data-target="_blank" {if isset($smarty.capture.height) && $smarty.capture.height} style="height:{$smarty.capture.height}px;"{/if}>			
      
			 
		<img src="{$link->getMediaLink("`$smarty.const._MODULE_DIR_`simplesliders/views/img/`$slide.image|escape:'htmlall':'UTF-8'`")}" {if isset($slide.size) && $slide.size} {$slide.size}{else} width="100%" height="100%"{/if} alt=""/>
	
    	</div>
    
	{/if}
{/foreach}
				
				
</div>
</div>
{/if}
    <!-- /Module simplesliders -->
{/if}