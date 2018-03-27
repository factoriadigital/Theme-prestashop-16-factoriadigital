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

<div id="gear-right">
	<i class="icon-cogs icon-2x icon-light"></i>
</div>
<form action="" method="post">
	<div id="tool_customization">
		<p>
			{l s='The customization tool allows you to make color changes in your theme.' mod='blockredfoxthemeconfigurator'}<br /><br />
			<span>
				{l s='Only you can see this tool, because as you are currently connected to your back-office as an admin; your visitors will not see it.' mod='blockredfoxthemeconfigurator'}
			</span>
		</p>
		<div class="list-tools">
			<p id="theme-title">
			  {l s='Theme color' mod='blockredfoxthemeconfigurator'} 
			  <i class="icon-caret-down pull-right"></i> 
			</p>
		</div>
		{if isset($themes)}
			<ul id="color-box">
				{foreach $themes as $theme}
					<li class="{$theme|escape:'htmlall':'UTF-8'}">
						<div class="color-theme1 color1"></div>
						<div class="color-theme2 color2"> </div>
					</li>
				{/foreach}
			</ul>
		{/if}
		
		<div class="btn-tools">
			<button type="button" class="btn btn-1" id="reset" name="resetLiveConfigurator">{l s='Reset' mod='blockredfoxthemeconfigurator'}</button>
			<button type="submit" class="btn btn-2" name="submitLiveConfigurator">{l s='Save' mod='blockredfoxthemeconfigurator'}</button>
		</div>
		<div id="block-advertisement">
			<a href="{$advertisement_url|escape:'htmlall':'UTF-8'}" onclick="return !window.open(this.href)">
				<img src="{$advertisement_image|escape:'htmlall':'UTF-8'}" alt="{$advertisement_text|escape:'htmlall':'UTF-8'}" />
			</a>
		</div>
	</div>
</form>
