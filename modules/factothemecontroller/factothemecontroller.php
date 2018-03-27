<?php
/**
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
*  @author    PrestaShop SA <contact@prestashop.com>
*  @copyright 2007-2015 PrestaShop SA
*  @license   http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
*  International Registered Trademark & Property of PrestaShop SA
*/

	if (!defined('_PS_VERSION_'))
		exit;

	class FactoThemecontroller extends Module
	{
		protected static $cache_products;

	public function __construct()
	{
		$this->name = 'factothemecontroller';
		$this->tab = 'front_office_features';
		$this->version = '2.0.1';
		$this->author = 'Factoriadigital';
		$this->need_instance = 0;

		parent::__construct();

		$this->displayName = $this->l('Customizar plantilla');
		$this->description = $this->l('Theme Customizer');

		$this->default_settings = array(
		'header_nav_bg' => '156285',
		'header_nav_font' => 'ffffff',
		'header_bg' => 'ffffff',
		'header_font_color' => '000000',
		'cart_font_color' => '6FB9BE',
		'search_font_color' => '000000',
		'search_icon_color' => '6FB9BE',
		'menu_bg' => '6FB9BE',
		'menu_font' => 'ffffff',
		'menu_hover_bg' => '156285',
		'menu_hover_font' => 'ffffff',
		'theme_bg' => '6FB9BE',
		'theme_font_color' => 'ffffff',
		'button_bg' => '6FB9BE',
		'button_bghover' => '156285',
		'button_font' => 'ffffff',
		'button_hover_font' => 'ffffff',
		'addcart_button_bg' => '6FB9BE',
		'addcart_button_bghover' => '156285',
		'addcart_button_font' => 'ffffff',
		'addcart_button_hover_font' => 'ffffff',
		'new_lable' => '156285',
		'new_lable_font' => 'ffffff',
		'sale_lable' => '156285',
		'sale_lable_font' => 'ffffff',
		'new_lable_hover' => '000000',
		'new_lable_font_hover' => 'ffffff',
		'sale_lable_hover' => '156285',
		'sale_lable_font_hover' => 'ffffff',
		'price_font' => '156285',
		'footer_bg' => '6FB9BE',
		'footer_font_color' => 'ffffff'
		);
	}

	public function install()
	{
		if (parent::install() && $this->registerHook('displayHeader'))
		{
			$defaults = Configuration::updateValue('header_nav_bg', $this->default_settings['header_nav_bg']);
			$defaults = Configuration::updateValue('header_nav_font', $this->default_settings['header_nav_font']);
			$defaults = Configuration::updateValue('header_bg', $this->default_settings['header_bg']);
			$defaults = Configuration::updateValue('header_font_color', $this->default_settings['header_font_color']);
			$defaults = Configuration::updateValue('cart_font_color', $this->default_settings['cart_font_color']);
			$defaults = Configuration::updateValue('search_font_color', $this->default_settings['search_font_color']);
			$defaults = Configuration::updateValue('search_icon_color', $this->default_settings['search_icon_color']);
			$defaults = Configuration::updateValue('menu_bg', $this->default_settings['menu_bg']);
			$defaults = Configuration::updateValue('menu_font', $this->default_settings['menu_font']);
			$defaults = Configuration::updateValue('menu_hover_bg', $this->default_settings['menu_hover_bg']);
			$defaults = Configuration::updateValue('menu_hover_font', $this->default_settings['menu_hover_font']);
			$defaults = Configuration::updateValue('theme_bg', $this->default_settings['theme_bg']);
			$defaults = Configuration::updateValue('theme_font_color', $this->default_settings['theme_font_color']);
			$defaults = Configuration::updateValue('button_bg', $this->default_settings['button_bg']);
			$defaults = Configuration::updateValue('button_bghover', $this->default_settings['button_bghover']);
			$defaults = Configuration::updateValue('button_font', $this->default_settings['button_font']);
			$defaults = Configuration::updateValue('button_hover_font', $this->default_settings['button_hover_font']);
			$defaults = Configuration::updateValue('addcart_button_bg', $this->default_settings['addcart_button_bg']);
			$defaults = Configuration::updateValue('addcart_button_bghover', $this->default_settings['addcart_button_bghover']);
			$defaults = Configuration::updateValue('addcart_button_font', $this->default_settings['addcart_button_font']);
			$defaults = Configuration::updateValue('addcart_button_hover_font', $this->default_settings['addcart_button_hover_font']);
			$defaults = Configuration::updateValue('new_lable', $this->default_settings['new_lable']);
			$defaults = Configuration::updateValue('new_lable_font', $this->default_settings['new_lable_font']);
			$defaults = Configuration::updateValue('sale_lable', $this->default_settings['sale_lable']);
			$defaults = Configuration::updateValue('sale_lable_font', $this->default_settings['sale_lable_font']);
			$defaults = Configuration::updateValue('new_lable_hover', $this->default_settings['new_lable_hover']);
			$defaults = Configuration::updateValue('new_lable_font_hover', $this->default_settings['new_lable_font_hover']);
			$defaults = Configuration::updateValue('sale_lable_hover', $this->default_settings['sale_lable_hover']);
			$defaults = Configuration::updateValue('sale_lable_font_hover', $this->default_settings['sale_lable_font_hover']);
			$defaults = Configuration::updateValue('price_font', $this->default_settings['price_font']);
			$defaults = Configuration::updateValue('footer_bg', $this->default_settings['footer_bg']);
			$defaults = Configuration::updateValue('footer_font_color', $this->default_settings['footer_font_color']);
			
			return $defaults;
		}
		return false;
	}

	public function hookHeader($params)
	{
			$facto_controller = array(
			'header_nav_bg' => (Configuration::get('header_nav_bg')),
			'header_nav_font' => (Configuration::get('header_nav_font')),
			'header_bg' => (Configuration::get('header_bg')),
			'header_font_color' => (Configuration::get('header_font_color')),
			'cart_font_color' => (Configuration::get('cart_font_color')),
			'search_font_color' => (Configuration::get('search_font_color')),
			'search_icon_color' => (Configuration::get('search_icon_color')),
			'menu_bg' => (Configuration::get('menu_bg')),
			'menu_font' => (Configuration::get('menu_font')),
			'menu_hover_bg' => (Configuration::get('menu_hover_bg')),
			'menu_hover_font' => (Configuration::get('menu_hover_font')),
			'theme_bg' => (Configuration::get('theme_bg')),
			'theme_font_color' => (Configuration::get('theme_font_color')),
			'button_bg' => (Configuration::get('button_bg')),
			'button_bghover' => (Configuration::get('button_bghover')),
			'button_font' => (Configuration::get('button_font')),
			'button_hover_font' => (Configuration::get('button_hover_font')),
			'addcart_button_bg' => (Configuration::get('addcart_button_bg')),
			'addcart_button_bghover' => (Configuration::get('addcart_button_bghover')),
			'addcart_button_font' => (Configuration::get('addcart_button_font')),
			'addcart_button_hover_font' => (Configuration::get('addcart_button_hover_font')),
			'new_lable' => (Configuration::get('new_lable')),
			'new_lable_font' => (Configuration::get('new_lable_font')),
			'sale_lable' => (Configuration::get('sale_lable')),
			'sale_lable_font' => (Configuration::get('sale_lable_font')),
			'new_lable_hover' => (Configuration::get('new_lable_hover')),
			'new_lable_font_hover' => (Configuration::get('new_lable_font_hover')),
			'sale_lable_hover' => (Configuration::get('sale_lable_hover')),
			'sale_lable_font_hover' => (Configuration::get('sale_lable_font_hover')),
			'price_font' => (Configuration::get('price_font')),
			'footer_bg' => (Configuration::get('footer_bg')),
			'footer_font_color' => (Configuration::get('footer_font_color')));

	$this->context->smarty->assign(
			'facto_controller', $facto_controller);

	return $this->display(__FILE__, 'factothemecontroller.tpl');
	}

	public function getContent()
	{
	if (Tools::isSubmit('reset'))
	{
		Configuration::updateValue('header_nav_bg', $this->default_settings['header_nav_bg']);
		Configuration::updateValue('header_nav_font', $this->default_settings['header_nav_font']);
		Configuration::updateValue('header_bg', $this->default_settings['header_bg']);
		Configuration::updateValue('header_font_color', $this->default_settings['header_font_color']);
		Configuration::updateValue('cart_font_color', $this->default_settings['cart_font_color']);
		Configuration::updateValue('search_font_color', $this->default_settings['search_font_color']);
		Configuration::updateValue('search_icon_color', $this->default_settings['search_icon_color']);
		Configuration::updateValue('menu_bg', $this->default_settings['menu_bg']);
		Configuration::updateValue('menu_font', $this->default_settings['menu_font']);
		Configuration::updateValue('menu_hover_bg', $this->default_settings['menu_hover_bg']);
		Configuration::updateValue('menu_hover_font', $this->default_settings['menu_hover_font']);
		Configuration::updateValue('theme_bg', $this->default_settings['theme_bg']);
		Configuration::updateValue('theme_font_color', $this->default_settings['theme_font_color']);
		Configuration::updateValue('button_bg', $this->default_settings['button_bg']);
		Configuration::updateValue('button_bghover', $this->default_settings['button_bghover']);
		Configuration::updateValue('button_font', $this->default_settings['button_font']);
		Configuration::updateValue('button_hover_font', $this->default_settings['button_hover_font']);
		Configuration::updateValue('addcart_button_bg', $this->default_settings['addcart_button_bg']);
		Configuration::updateValue('addcart_button_bghover', $this->default_settings['addcart_button_bghover']);
		Configuration::updateValue('addcart_button_font', $this->default_settings['addcart_button_font']);
		Configuration::updateValue('addcart_button_hover_font', $this->default_settings['addcart_button_hover_font']);
		Configuration::updateValue('new_lable', $this->default_settings['new_lable']);
		Configuration::updateValue('new_lable_font', $this->default_settings['new_lable_font']);
		Configuration::updateValue('sale_lable', $this->default_settings['sale_lable']);
		Configuration::updateValue('sale_lable_font', $this->default_settings['sale_lable_font']);
		Configuration::updateValue('new_lable_hover', $this->default_settings['new_lable_hover']);
		Configuration::updateValue('new_lable_font_hover', $this->default_settings['new_lable_font_hover']);
		Configuration::updateValue('sale_lable_hover', $this->default_settings['sale_lable_hover']);
		Configuration::updateValue('sale_lable_font_hover', $this->default_settings['sale_lable_font_hover']);
		Configuration::updateValue('price_font', $this->default_settings['price_font']);
		Configuration::updateValue('footer_bg', $this->default_settings['footer_bg']);
		Configuration::updateValue('footer_font_color', $this->default_settings['footer_font_color']);
	}

	if (Tools::isSubmit('submit'))
	{
		Configuration::updateValue('header_nav_bg', Tools::getValue('header_nav_bg'));
		Configuration::updateValue('header_nav_font', Tools::getValue('header_nav_font'));
		Configuration::updateValue('header_bg', Tools::getValue('header_bg'));
		Configuration::updateValue('header_font_color', Tools::getValue('header_font_color'));
		Configuration::updateValue('cart_font_color', Tools::getValue('cart_font_color'));
		Configuration::updateValue('search_font_color', Tools::getValue('search_font_color'));
		Configuration::updateValue('search_icon_color', Tools::getValue('search_icon_color'));
		Configuration::updateValue('menu_bg', Tools::getValue('menu_bg'));
		Configuration::updateValue('menu_font', Tools::getValue('menu_font'));
		Configuration::updateValue('menu_hover_bg', Tools::getValue('menu_hover_bg'));
		Configuration::updateValue('menu_hover_font', Tools::getValue('menu_hover_font'));
		Configuration::updateValue('theme_bg', Tools::getValue('theme_bg'));
		Configuration::updateValue('theme_font_color', Tools::getValue('theme_font_color'));
		Configuration::updateValue('price_font', Tools::getValue('price_font'));
		Configuration::updateValue('footer_bg', Tools::getValue('footer_bg'));
		Configuration::updateValue('footer_font_color', Tools::getValue('footer_font_color'));
		Configuration::updateValue('button_bg', Tools::getValue('button_bg'));
		Configuration::updateValue('button_bghover', Tools::getValue('button_bghover'));
		Configuration::updateValue('button_font', Tools::getValue('button_font'));
		Configuration::updateValue('button_hover_font', Tools::getValue('button_hover_font'));
		Configuration::updateValue('addcart_button_bg', Tools::getValue('addcart_button_bg'));
		Configuration::updateValue('addcart_button_bghover', Tools::getValue('addcart_button_bghover'));
		Configuration::updateValue('addcart_button_font', Tools::getValue('addcart_button_font'));
		Configuration::updateValue('addcart_button_hover_font', Tools::getValue('addcart_button_hover_font'));
		Configuration::updateValue('new_lable', Tools::getValue('new_lable'));
		Configuration::updateValue('new_lable_font', Tools::getValue('new_lable_font'));
		Configuration::updateValue('sale_lable', Tools::getValue('sale_lable'));
		Configuration::updateValue('sale_lable_font', Tools::getValue('sale_lable_font'));
		Configuration::updateValue('new_lable_hover', Tools::getValue('new_lable_hover'));
		Configuration::updateValue('new_lable_font_hover', Tools::getValue('new_lable_font_hover'));
		Configuration::updateValue('sale_lable_hover', Tools::getValue('sale_lable_hover'));
		Configuration::updateValue('sale_lable_font_hover', Tools::getValue('sale_lable_font_hover'));
		
	}

	$this->_displayForm();
	return $this->_html;
	}

	private function _displayForm()
	{
		$this->context->controller->addJS(($this->_path).'views/js/pickers.js');
		$this->context->controller->addJS(($this->_path).'views/js/jscolor.js');

		$this->context->controller->addCSS(($this->_path).'views/css/colorpicker.css', 'all');
		$this->context->controller->addCSS(($this->_path).'views/css/style.css', 'all');

		$this->_html .= '
	
	<h2>
	  facto Customizer
	</h2>

	  <style>
		.specialpatterns {border: 2px solid #E1E1E1; text-align:center; cursor: pointer; float: left; height: 45px; margin: 0 3px 3px 0; overflow: hidden; width: 32px;}
		.cell input {
		margin: 5px 0 5px 6px;
		}
		.pattern_group { float:left; clear:both;}
	  </style>
		<form action="'.$_SERVER['REQUEST_URI'].'" method="post">
		
		<fieldset style="margin-top:15px;" class="field-themesettings">
		<script>
			window.addEventListener("load", function() {
			makeTabs(".tabs")
			});
		</script>
		<legend><img src="'.$this->_path.'logo.png" width="16" height="16" alt="" title="" />Configurador de color de la plantilla</legend>
		<div class="tabs">
			<ul>
				<li><a href="#tab-1" class="active">Plantilla</a></li>
				<li><a href="#tab-2">Colores del menú</a></li>
				<li><a href="#tab-3">Colores de botones</a></li>
				<li><a href="#tab-4">Colores de cabecera</a></li>
			</ul>
				<div id="tab-1">
					<div style="clear:both;"></div>
					<br>
						<label>Color por defecto:</label>
						<input type="text" name="theme_bg" id="theme_bg" value="'.Configuration::get('theme_bg').'" class="color" /><br/><br/>

					<div style="clear:both;"></div>
					<br>
						<label>Color de texto :</label>
						<input type="text" name="theme_font_color" id="theme_font_color" value="'.Configuration::get('theme_font_color').'" class="color" /><br/><br/>
					<div style="clear:both;"></div>
					<br>
						<label>Color de precios :</label>
						<input type="text" name="price_font" id="price_font" value="'.Configuration::get('price_font').'" class="color" /><br/><br/>

					<div style="clear:both;"></div>
					<br>
						<label>Color de fondo del footer :</label>
						<input type="text" name="footer_bg" id="footer_bg" value="'.Configuration::get('footer_bg').'" class="color" /><br/><br/>

					<div style="clear:both;"></div>
					<br>
						<label>Color de texto del footer :</label>
						<input type="text" name="footer_font_color" id="footer_font_color" value="'.Configuration::get('footer_font_color').'" class="color" /><br/><br/>

					<div style="clear:both;"></div>
					<br>
						<label>Color de la pastilla Nuevo :</label>
						<input type="text" name="new_lable" id="new_lable" value="'.Configuration::get('new_lable').'" class="color" /><br/><br/>

					<div style="clear:both;"></div>
					<br>
						<label>Color del hover de pastilla Nuevo :</label>
						<input type="text" name="new_lable_hover" id="new_lable_hover" value="'.Configuration::get('new_lable_hover').'" class="color" /><br/><br/>
						<div style="clear:both;"></div>
					<br>
						<label>Color de texto de pastilla Nuevo :</label>
						<input type="text" name="new_lable_font" id="new_lable_font" value="'.Configuration::get('new_lable_font').'" class="color" /><br/><br/>
					<div style="clear:both;"></div>
					<br>
						<label>Color de texto hover de pastilla Nuevo :</label>
						<input type="text" name="new_lable_font_hover" id="new_lable_font_hover" value="'.Configuration::get('new_lable_font_hover').'" class="color" /><br/><br/>
						<div style="clear:both;"></div>
					<br>
						<label>Color de pastilla Rebajas :</label>
						<input type="text" name="sale_lable" id="sale_lable" value="'.Configuration::get('sale_lable').'" class="color" /><br/><br/>

					<div style="clear:both;"></div>
					<br>
						<label>Color del hover de pastilla Rebajas :</label>
						<input type="text" name="sale_lable_hover" id="sale_lable_hover" value="'.Configuration::get('sale_lable_hover').'" class="color" /><br/><br/>
						<div style="clear:both;"></div>
					<br>
						<label>Color de texto de Rebajas :</label>
						<input type="text" name="sale_lable_font" id="sale_lable_font" value="'.Configuration::get('sale_lable_font').'" class="color" /><br/><br/>
					<div style="clear:both;"></div>
					<br>
						<label>Color de texto hover de rebajas :</label>
						<input type="text" name="sale_lable_font_hover" id="sale_lable_font_hover" value="'.Configuration::get('sale_lable_font_hover').'" class="color" /><br/><br/>
						<div style="clear:both;"></div>
				</div>
				<div id="tab-2">
					<div style="clear:both;"></div>
					<br>
						<label>Color de fondo del menú :</label>
						<input type="text" name="menu_bg" id="menu_bg" value="'.Configuration::get('menu_bg').'" class="color" /><br/><br/>

					<div style="clear:both;"></div>
					<br>
						<label>Color de fondo del hover menú :</label>
						<input type="text" name="menu_hover_bg" id="menu_hover_bg" value="'.Configuration::get('menu_hover_bg').'" class="color" /><br/><br/>

					<div style="clear:both;"></div>
					<br>
						<label>Color de texto menú :</label>
						<input type="text" name="menu_font" id="menu_font" value="'.Configuration::get('menu_font').'" class="color" /><br/><br/>

					<div style="clear:both;"></div>
					<br>
						<label>Color de texto hover menú :</label>
						<input type="text" name="menu_hover_font" id="menu_hover_font" value="'.Configuration::get('menu_hover_font').'" class="color" /><br/><br/>

					<div style="clear:both;"></div>
					

				</div>

				<div id="tab-3">
					<div style="clear:both;"></div>
					<br>

						<label>Color de fondo de botones :</label>
						<input type="text" name="button_bg" id="button_bg" value="'.Configuration::get('button_bg').'" class="color" /><br/><br/>

					<div style="clear:both;"></div>
					<br>
					<label>Color hover botones :</label>
						<input type="text" name="button_bghover" id="button_bghover" value="'.Configuration::get('button_bghover').'" class="color" /><br/><br/>
						
						
						<div style="clear:both;"></div>
					<br>
						<label>Color de texto de botones :</label>
						<input type="text" name="button_font" id="button_font" value="'.Configuration::get('button_font').'" class="color" /><br/><br/>

					<div style="clear:both;"></div>
					<br>
						<label>Color de texto hover de botones :</label>
						<input type="text" name="button_hover_font" id="button_hover_font" value="'.Configuration::get('button_hover_font').'" class="color" /><br/><br/>

					<div style="clear:both;"></div>
					<br>
						<label>Color de fondo botón añadir al carrito :</label>
						<input type="text" name="addcart_button_bg" id="addcart_button_bg" value="'.Configuration::get('addcart_button_bg').'" class="color" /><br/><br/>

					<div style="clear:both;"></div>
					<br>
						<label>Hover de color de fondo de añadir al carrito :</label>
						<input type="text" name="addcart_button_bghover" id="addcart_button_bghover" value="'.Configuration::get('addcart_button_bghover').'" class="color" /><br/><br/>

					<div style="clear:both;"></div>
					<br>
						<label>Color de texto añadir al carrito :</label>
						<input type="text" name="addcart_button_font" id="addcart_button_font" value="'.Configuration::get('addcart_button_font').'" class="color" /><br/><br/>

					<div style="clear:both;"></div>
					<br>
						<label>Color de texto hover de añadir al carrito :</label>
						<input type="text" name="addcart_button_hover_font" id="addcart_button_hover_font" value="'.Configuration::get('addcart_button_hover_font').'" class="color" /><br/><br/>

					<div style="clear:both;"></div>
					
				</div>

				<div id="tab-4">

					<div style="clear:both;"></div>
					<br>

						<label>Color de fondo del Top :</label>
						<input type="text" name="header_nav_bg" id="header_nav_bg" value="'.Configuration::get('header_nav_bg').'" class="color" /><br/><br/>

					<div style="clear:both;"></div>
					<br>

						<label>Color de fuente del top :</label>
						<input type="text" name="header_nav_font" id="header_nav_font" value="'.Configuration::get('header_nav_font').'" class="color" /><br/><br/>

					<div style="clear:both;"></div>
					<br>

						<label>Color de fonodo de cabecera :</label>
						<input type="text" name="header_bg" id="header_bg" value="'.Configuration::get('header_bg').'" class="color" /><br/><br/>
					<div style="clear:both;"></div>
					<br>

						<label>Color de texto de cabecera :</label>
						<input type="text" name="header_font_color" id="header_font_color" value="'.Configuration::get('header_font_color').'" class="color" /><br/><br/>
					<div style="clear:both;"></div>
					<br>

						<label>Color de texto del buscador :</label>
						<input type="text" name="search_font_color" id="search_font_color" value="'.Configuration::get('search_font_color').'" class="color" /><br/><br/>
					<div style="clear:both;"></div>
					<br>

						<label>Color de texto del icono de barra de búsqueda :</label>
						<input type="text" name="search_icon_color" id="search_icon_color" value="'.Configuration::get('search_icon_color').'" class="color" /><br/><br/>
					<div style="clear:both;"></div>
					<br>

						<label>Color de texto del carrito :</label>
						<input type="text" name="cart_font_color" id="cart_font_color" value="'.Configuration::get('cart_font_color').'" class="color" /><br/><br/>
					<div style="clear:both;"></div>
					
				</div>


		</div>
		<div class="pattern_group">
				<input type="submit" name="submit" value="Guardar cambios" class="button" />
				<input type="submit" name="reset" value="Volver a valores predeterminados" class="button" />
		</div>
		</fieldset>
	 ';
	}
}
