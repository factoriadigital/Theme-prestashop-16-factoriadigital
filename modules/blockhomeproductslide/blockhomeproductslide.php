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
	
class BlockHomeProductSlide extends Module
{
	protected static $cache_products;
	protected static $cache_specials;
	
	private $_html = '';
	private $_postErrors = array();

	public function __construct()
	{
		$this->name = 'blockhomeproductslide';
		$this->tab = 'front_office_features';
		$this->version = '2.0.2';
		$this->author = 'Factoriadigital';
		$this->need_instance = 0;
		
		$this->bootstrap = true;
		parent::__construct();

		$this->displayName = $this->l('Slider de productos destacados');
		$this->description = $this->l('Add top sellers product, new product, Featured product in Homepage.');
	}
	
	public function install()
	{
		$this->_clearCache('blockhomeproductslide.tpl');
		$this->_clearCache('blockhomeproductslide-side.tpl');
		Configuration::updateValue('HOME_FEATURED_THUMB', true);
		
	
		if (!parent::install()
			|| !$this->registerHook('header') 
			|| !$this->registerHook('slideTab')
			|| !$this->registerHook('displayheader')
			|| !$this->registerHook('displayHomeTab'))
			return false;
		return true;
	}
	
	public function uninstall()
	{
		$this->_clearCache('blockhomeproductslide.tpl');
		$this->_clearCache('blockhomeproductslide-side.tpl');

		return parent::uninstall();
	}
	
	public function getContent()
	{
		$output = '';
		$errors = array();
				
		if (Tools::isSubmit('submitBlockHomeproductslide'))
		{
		
			$featuredthumb = Tools::getValue('HOME_FEATURED_THUMB');
			if (!Validate::isBool($featuredthumb))
				$errors[] = $this->l('Invalid value for the "thumbnail image" flag.');
			if (isset($errors) && count($errors))
				$output = $this->displayError(implode('<br />', $errors));
			else
			{
				Configuration::updateValue('HOME_FEATURED_THUMB', (bool)$featuredthumb);
				Tools::clearCache(Context::getContext()->smarty, $this->getTemplatePath('blockhomeproductslide.tpl'));
				Tools::clearCache(Context::getContext()->smarty, $this->getTemplatePath('blockhomeproductslide-side.tpl'));
				$output = $this->displayConfirmation($this->l('Your settings have been updated.'));
			}
		}
		return $output.$this->renderForm();
	}

	public function hookDisplayHeader($params)
	{
		if (isset($this->context->controller->php_self) && $this->context->controller->php_self == 'index')
		$this->context->controller->addCSS($this->_path.'views/css/blockhomeproductslide.css');
		$this->context->controller->addCSS($this->_path.'views/css/homeproductslide.css');
		
		$this->context->controller->addJS($this->_path.'views/js/blockhomeproductslide.js');
		$this->context->controller->addJS(($this->_path).'views/js/redfoxhomefeatured.js');
		$this->context->controller->addJS(($this->_path).'views/js/homeproductslide.carousel.js');
		$this->context->controller->addJS(($this->_path).'views/js/jquery.introLoader.js');

	}
	
	public function hookSlideTab($params)
	{
		if (Configuration::get('PS_CATALOG_MODE'))
			return;

	
		$category = new Category(Context::getContext()->shop->getCategory(), (int)Context::getContext()->language->id);
		$nb = (int)(Configuration::get('HOME_FEATURED_NBR'));
		$products = $category->getProducts((int)Context::getContext()->language->id, 1, ($nb ? $nb : 1000));
		
		$prodImages = array();
		$allProdArr = array();
		$allProd = array();
		
		$bestsellers = ProductSale::getBestSalesLight((int)$params['cookie']->id_lang, 0, 100);
		if ($bestsellers)
		{
		foreach($bestsellers as $prod1) {
              $prod = new Product($prod1['id_product']);
			$images = $prod->getImages((int)$this->context->cookie->id_lang);
			$product_images = array();
			foreach ($images as $k => $image)
				$product_images[(int)$image['id_image']] = $image;

			$prodImages[$prod1['id_product']] = $product_images;
			$allProdArr[$prod1['id_product']] = $prod1;
		}
		}
		$new_products = Product::getNewProducts((int)($params['cookie']->id_lang), 0, 100);
		if ($new_products)
		{
		foreach($new_products as $prod1) {
               $prod = new Product($prod1['id_product']);
			$images = $prod->getImages((int)$this->context->cookie->id_lang);
			$product_images = array();
			foreach ($images as $k => $image)
				$product_images[(int)$image['id_image']] = $image;

			$prodImages[$prod1['id_product']] = $product_images;
			$allProdArr[$prod1['id_product']] = $prod1;
		}
		}
		$featured_products = $category->getProducts((int)($params['cookie']->id_lang), 1, 100);
		if ($featured_products)
		{
		
		foreach($featured_products as $prod1) {
               $prod = new Product($prod1['id_product']);
			$images = $prod->getImages((int)$this->context->cookie->id_lang);
			$product_images = array();
			foreach ($images as $k => $image)
				$product_images[(int)$image['id_image']] = $image;

			$prodImages[$prod1['id_product']] = $product_images;
			$allProdArr[$prod1['id_product']] = $prod1;
		}
		}
		$specials_pro = Product::getPricesDrop((int)$params['cookie']->id_lang, 0, 100);
		if ($specials_pro)
		{
		
		foreach($specials_pro as $prod1) {
               $prod = new Product($prod1['id_product']);
			$images = $prod->getImages((int)$this->context->cookie->id_lang);
			$product_images = array();
			foreach ($images as $k => $image)
				$product_images[(int)$image['id_image']] = $image;

			$prodImages[$prod1['id_product']] = $product_images;
			$allProdArr[$prod1['id_product']] = $prod1;
		}
	}
		if (!$this->isCached('blockhomeproductslide.tpl', $this->getCacheId()))
		{
			$this->smarty->assign(array(
				'allProd' => $allProd,
				'prodImages' => $prodImages,
				'products' => $products,
				'new_products' => $new_products,
				'featured_products' => $featured_products,
				'bestsellers' => $bestsellers,
				'specials' => $specials_pro,
				'add_prod_display' => Configuration::get('PS_ATTRIBUTE_CATEGORY_DISPLAY'),
				'homeSize' => Image::getSize(ImageType::getFormatedName('home'))
			));
		}
		else if (!$this->isCached('blockhomeproductslide-side.tpl', $this->getCacheId()))
		{
			$this->smarty->assign(array(
				'allProd' => $allProd,
				'prodImages' => $prodImages,
				'products' => $products,
				'new_products' => $new_products,
				'featured_products' => $featured_products,
				'bestsellers' => $bestsellers,
				'specials' => $specials_pro,
				'add_prod_display' => Configuration::get('PS_ATTRIBUTE_CATEGORY_DISPLAY'),
				'homeSize' => Image::getSize(ImageType::getFormatedName('home'))
			));
		}
		
		if ((Configuration::get('HOME_FEATURED_THUMB')) == false)
			return $this->display(__FILE__, 'blockhomeproductslide.tpl', $this->getCacheId());
		else
			return $this->display(__FILE__, 'blockhomeproductslide-side.tpl', $this->getCacheId());
		}
	
	public function getCacheId($name = null)
	{
		if ($name === null)
		$name = 'blockhomeproductslide';
		return parent::getCacheId($name.'|'.date('Ymd'));
	}
	
	public function renderForm()
	{
		$fields_form = array(
			'form' => array(
				'legend' => array(
					'title' => $this->l('Settings'),
					'icon' => 'icon-cogs'
				),
				'input' => array(
					array(
						'type' => 'switch',
						'label' => $this->l('Thumbnail image for Display products'),
						'name' => 'HOME_FEATURED_THUMB',
						'class' => 'fixed-width-xs',
						'desc' => $this->l('Enable if you wish the products to be displayed with Thumnail image (default: Yes).'),
						'values' => array(
							array(
								'id' => 'active_off',
								'value' => 1,
								'label' => $this->l('No')
							),
							array(
								'id' => 'active_on',
								'value' => 0,
								'label' => $this->l('Yes')
							)
						),
					),
				),
				'submit' => array(
					'title' => $this->l('Save'),
				)
			),
		);
		
		$helper = new HelperForm();
		$helper->show_toolbar = false;
		$helper->table = $this->table;
		$lang = new Language((int)Configuration::get('PS_LANG_DEFAULT'));
		$helper->default_form_language = $lang->id;
		$helper->allow_employee_form_lang = Configuration::get('PS_BO_ALLOW_EMPLOYEE_FORM_LANG') ? Configuration::get('PS_BO_ALLOW_EMPLOYEE_FORM_LANG') : 0;
		$this->fields_form = array();
		$helper->id = (int)Tools::getValue('id_carrier');
		$helper->identifier = $this->identifier;
		$helper->submit_action = 'submitBlockHomeproductslide';
		$helper->currentIndex = $this->context->link->getAdminLink('AdminModules', false).'&configure='.$this->name.'&tab_module='.$this->tab.'&module_name='.$this->name;
		$helper->token = Tools::getAdminTokenLite('AdminModules');
		$helper->tpl_vars = array(
			'fields_value' => $this->getConfigFieldsValues(),
			'languages' => $this->context->controller->getLanguages(),
			'id_language' => $this->context->language->id
		);

		return $helper->generateForm(array($fields_form));
	}
	
	public function getConfigFieldsValues()
	{
		return array(
			'HOME_FEATURED_THUMB' => Tools::getValue('HOME_FEATURED_THUMB', (bool)Configuration::get('HOME_FEATURED_THUMB')),
		);
	}

}


