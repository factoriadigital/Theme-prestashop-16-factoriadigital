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
* @author    PrestaShop SA <contact@prestashop.com>
* @copyright 2007-2015 PrestaShop SA
* @license   http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
* International Registered Trademark & Property of PrestaShop SA
*/

if (!defined('_CAN_LOAD_FILES_'))
	exit;
	
class BlockFeaturedFooter extends Module
{
	private $_html = '';
	private $_postErrors = array();

	public function __construct()
	{
		$this->name = 'blockfeaturedfooter';
		$this->tab = 'front_office_features';
		$this->version = '2.0.0';
		$this->author = 'Factoriadigital';
		parent::__construct();

		$this->displayName = $this->l('Productos destacados en footer');
		$this->description = $this->l('Footer Featured product in Homepage.');
	}
	
	public function install()
	{
		if (!parent::install() ||
				!$this->registerHook('header') || !$this->registerHook('footer'))
			return false;
		return true;
	}
		
	public function hookFooter($params)
	{
		$category = new Category(Context::getContext()->shop->getCategory(), (int)Context::getContext()->language->id);

		$featured_products = $category->getProducts((int)($params['cookie']->id_lang), 1, 3);
		
		$this->smarty->assign(array(
			'featured_products' => $featured_products,
			'add_prod_display' => Configuration::get('PS_ATTRIBUTE_CATEGORY_DISPLAY'),
			'homeSize' => Image::getSize(ImageType::getFormatedName('home'))
		));
		return $this->display(__FILE__, 'blockfeaturedfooter.tpl');
	}
}


