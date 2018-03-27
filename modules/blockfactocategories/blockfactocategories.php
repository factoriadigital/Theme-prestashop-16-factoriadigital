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

class BlockFactoCategories extends Module
{
	protected static $cache_products;
	private $html = '';
	private $_postErrors = array();

	public function __construct()
	{
		$this->name = 'blockfactocategories';
		$this->tab = 'front_office_features';
		$this->version = '3.3.1';
		$this->bootstrap = true;
		$this->author = 'Factoriadigital';
		$this->secure_key = Tools::encrypt($this->name);
		$this->need_instance = 0;

		parent::__construct();

		$this->displayName = $this->l('Tabs de categorias en Inicio');
		$this->description = $this->l('Add a block which enables Stylish Category Tab on home page.');
	}

	/**
	 * @see ModuleCore::install()
	 */

	public function install()
	{
		$this->_clearCache('blockfactocategories.tpl');
		$this->_clearCache('blockfactocategories-side.tpl');
		
		$languages = $this->context->controller->getLanguages();
		$id_shop = (int)Shop::getContextShopID();

		$sql_ins = 'INSERT INTO `'._DB_PREFIX_.'factotabsettings` (`id_tab`, `id_lang`, `id_shop`, `name`, `alias_name`, `type`, `position`, `prod_count`) VALUES ';
		foreach ($languages as $lan)
		{
			$lang_id = $lan['id_lang'];
			$sql_ins .= '(1, '.$lang_id.', '.(int)$id_shop.", 'Destacados', 'Destacados', 'special', 1, 8),";
		}
		foreach ($languages as $lan)
		{
			$lang_id = $lan['id_lang'];
			$sql_ins .= '(2, '.$lang_id.', '.(int)$id_shop.", 'Novedades', 'Novedades', 'special', 2, 8),";
		}
		foreach ($languages as $lan)
		{
			$lang_id = $lan['id_lang'];
			$sql_ins .= '(4, '.$lang_id.', '.(int)$id_shop.", 'Ofertas', 'Ofertas', 'special', 4, 8),";
		}
		$loop_counter = 1;
		foreach ($languages as $lan)
		{
			$lang_id = $lan['id_lang'];
			$sql_ins .= '(3, '.$lang_id.', '.(int)$id_shop.", 'Más vendidos', 'Más vendidos', 'special', 3, 8)";

			if (count($languages) > $loop_counter)
				$sql_ins .= ',';
			else
			$sql_ins .= ';';

			$loop_counter++;
		}

		$sql_tab_settings = 'CREATE TABLE IF NOT EXISTS `'._DB_PREFIX_.'factotabsettings` (
								`id_tab` int(10) NOT NULL,
								`id_lang` int(3) NOT NULL,
								`id_shop` int(11) NOT NULL,
								`name` varchar(150) NOT NULL,
								`alias_name` varchar(150) NOT NULL,
								`type` varchar(100) NOT NULL,
								`prod_count` tinyint(3) DEFAULT \'8\',
								`position` tinyint(3) DEFAULT NULL,
								`cat_id` tinyint(3) DEFAULT NULL,
								`status` enum(\'0\',\'1\') NOT NULL DEFAULT \'1\'
							)ENGINE='._MYSQL_ENGINE_.' DEFAULT CHARSET=utf8';
		$sql_prod_settings = 'CREATE TABLE IF NOT EXISTS `'._DB_PREFIX_.'factotab_products` (
							  `tab_id` tinyint(3) NOT NULL,
							  `products` text
							)ENGINE='._MYSQL_ENGINE_.' CHARACTER SET utf8 COLLATE utf8_general_ci;';

	if (!parent::install()
		|| !$this->registerHook('displayCasualtabs')
		|| !$this->registerHook('displayHeader')
		|| !Configuration::updateValue('facto_CATOGORIES_THUMB', 'on')
		|| !(Db::getInstance()->Execute($sql_tab_settings))
		|| !(Db::getInstance()->Execute($sql_prod_settings))
		|| !(Db::getInstance()->Execute($sql_ins))
		|| !Configuration::updateValue('facto_CATOGORIES_ALL', 'on'))
		return false;
	return true;
	}

	public function uninstall()
	{
		$this->_clearCache('blockfactocategories.tpl');
		$this->_clearCache('blockfactocategories-side.tpl');
		$sql_drop_settings = 'DROP TABLE `'._DB_PREFIX_.'factotabsettings`';
		$sql_dropproducts = 'DROP TABLE `'._DB_PREFIX_.'factotab_products`';

	if (!parent::uninstall()
		|| !(Db::getInstance()->Execute($sql_drop_settings))
		|| !(Db::getInstance()->Execute($sql_dropproducts))
		|| !Configuration::deleteByName('facto_CATOGORIES_THUMB')
		|| !Configuration::deleteByName('facto_CATOGORIES_ALL'))
		return false;
	return true;
	}

	public function getContent($cat_id = null, $name = null)
	{
		//$id_lang = (int)Context::getContext()->language->id;
		$languages = $this->context->controller->getLanguages();
		$default_language = (int)Configuration::get('PS_LANG_DEFAULT');
		$id_shop = (int)Shop::getContextShopID();
		$position = (int)Tools::getValue('position');

		$output = '<h2>'.$this->displayName.'</h2>';

		$tabid = Tools::getValue('tabid');
		$sel_product = Tools::getValue('selProduct');
		if (isset($tabid) && $tabid != '')
		{
			if (Tools::isSubmit('submit_'.$tabid))
			{
				$status = Tools::getValue('status_'.$tabid);
				$type = Tools::getValue('type_'.$tabid);

				foreach ($languages as $l)
				{
					$lang_id = $l['id_lang'];
					$aliasname = Tools::getValue('aliasname_'.$tabid.'_'.$lang_id);
					$prod_count = Tools::getValue('prod_count_'.$tabid);

					$sel_prd = 'SELECT COUNT(*) FROM `'._DB_PREFIX_.'factotabsettings` WHERE `id_lang`='.$lang_id.' AND `id_tab`='.$tabid;
					$cnt_value = Db::getInstance()->getValue($sel_prd);
					if ($cnt_value)
						$sql_upd = 'UPDATE `'._DB_PREFIX_.'factotabsettings` 
								SET `alias_name` = "'.$aliasname.'", `status` = "'.$status.'", `prod_count` = "'.$prod_count.'" WHERE `id_tab` = '.$tabid.'	AND `id_lang` = '.$lang_id;
					else
					{
						$sql_sel_prd = 'SELECT  * FROM `'._DB_PREFIX_.'factotabsettings` WHERE `id_tab` = '.$tabid.' AND `id_lang` = '.$default_language;
						$res_sel_prd = Db::getInstance()->ExecuteS($sql_sel_prd);
						foreach ($res_sel_prd as $res_p)
						{
							extract($res_p);
							$sql_upd = 'INSERT INTO `'._DB_PREFIX_.'factotabsettings` (`id_tab`, `id_lang`, `id_shop`, `name`, `alias_name`, `type`, `cat_id`, `position`, `prod_count`)
								VALUES ('.$tabid.', '.(int)$l['id_lang'].', '.(int)$id_shop.', '.trim($name).', '.trim($aliasname).', '.$type.', '.$cat_id.', '.$position.', '.$prod_count.')';
						}
					}
					$this->_clearCache('blockfactocategories.tpl');
					$this->_clearCache('blockfactocategories-side.tpl');

					Db::getInstance()->Execute($sql_upd);
				}

				if ($type == 'custom')
				{
					$sql_sel_tab = 'SELECT `tab_id` FROM `'._DB_PREFIX_.'factotab_products` WHERE `tab_id` = '.$tabid;
					$tab = Db::getInstance()->getValue($sql_sel_tab);
					if ($tab)

						$sql = 'UPDATE `'._DB_PREFIX_.'factotab_products` SET `products` = "'.$sel_product.'" WHERE `tab_id` = '.$tabid;

					else

						$sql = 'INSERT INTO '._DB_PREFIX_.'factotab_products (`tab_id`, `products`) VALUES ("'.$tabid.'", "'.$sel_product.'")';

					Db::getInstance()->Execute($sql);
				}
				$output .= '<div class="bootstrap"><div class="conf confirm updated alert alert-success">'.$this->l('Settings Updated').'</div></div>';
			}
		}

		if (Tools::isSubmit('submit_add'))
		{
			$selected_tab = Tools::getValue('selectedTab');
			$tab_arr = explode(',', $selected_tab);
			$sql_sel = 'SELECT COUNT(DISTINCT(id_tab)) FROM '._DB_PREFIX_.'factotabsettings';
			$count_tab = Db::getInstance()->getValue($sql_sel);
			if (!$count_tab)
				$count_tab = 1;
			else
				$count_tab += 1;
			foreach ($tab_arr as $tab)
			{
				$val_arr = explode('_', $tab);
				$flag_exists = 0;
				if ($val_arr[1] == 'category' || $val_arr[1] == 'special')
				{
					$sql_sel = 'SELECT COUNT(*) FROM `'._DB_PREFIX_.'factotabsettings` WHERE `id_shop` = '.$id_shop.' AND `name` = "'.$val_arr[0].'"';
					$flag_exists = Db::getInstance()->getValue($sql_sel);
				}
				$sql_max_id = 'SELECT max(`id_tab`) FROM '._DB_PREFIX_.'factotabsettings';
				$max_tab_id = Db::getInstance()->getValue($sql_max_id);
				if (!$max_tab_id) $max_tab_id = 1;
				else
				$max_tab_id += 1;
				foreach ($languages as $l)
				{
					if (isset($val_arr[2]))
					{
						$sql_ins = 'INSERT INTO '._DB_PREFIX_.'factotabsettings (`id_tab`, `id_lang`, `id_shop`, `name`, `alias_name`, `type`, `cat_id`, `position`)
								VALUES ('.$max_tab_id.', '.(int)$l['id_lang'].', '.(int)$id_shop.', "'.trim($val_arr[0]).'", "'.trim($val_arr[0]).'", "'.$val_arr[1].'", "'.$val_arr[2].'", '.$count_tab.')';
					}
					else
					{
						$sql_ins = 'INSERT INTO '._DB_PREFIX_.'factotabsettings (`id_tab`, `id_lang`, `id_shop`, `name`, `alias_name`, `type`, `position`)
								VALUES ('.$max_tab_id.', '.(int)$l['id_lang'].', '.(int)$id_shop.', "'.trim($val_arr[0]).'", "'.trim($val_arr[0]).'", "'.$val_arr[1].'", '.$count_tab.')';
					}

					if (($val_arr[1] == 'category' || $val_arr[1] == 'special') && ($flag_exists == 0))

						Db::getInstance()->Execute($sql_ins);

					elseif ($val_arr[1] == 'custom')

						Db::getInstance()->Execute($sql_ins);

				}
			}

			if ($flag_exists)

				$output .= '<div class="bootstrap"><div class="module_error alert alert-danger error">'.$this->l('This section is already added').'</div></div>';

			else

				$output .= '<div class="bootstrap"><div class="conf confirm updated alert alert-success">'.$this->l('New Section Added Sucessfully').'</div></div>';
		}
		else if (Tools::isSubmit('display_all'))
		{
				$disp_val = Tools::getValue('dispAll');
				Configuration::updateValue('facto_CATOGORIES_ALL', "$disp_val");
				$output .= '<div class="conf confirm updated">'.$this->l('Setting Updated').'</div>';
		}

		else if (Tools::isSubmit('display_thumb'))
		{
				$dispthumb_val = Tools::getValue('displaythumb');
				Configuration::updateValue('facto_CATOGORIES_THUMB', "$dispthumb_val");
				$output .= '<div class="conf confirm updated">'.$this->l('Setting Updated').'</div>';
		}
			$output .= '<div class="bootstrap"><div class="conf confirm deleted alert alert-success" style="display:none;">'.$this->l('Deleted Sucessfully').'</div></div>';
			$output .= '<div class="bootstrap"><div class="module_error alert alert-danger" style="display:none;">'.$this->l('This section is already added').'</div></div>';
			$output .= '<div class="bootstrap"><div class="conf confirm posChange alert" style="display:none;">'.$this->l('Position Changed').'</div></div>';

		return $output.$this->displayForm();
	}

	public function getTree($resultParents, $resultIds, $maxDepth, $id_category = null, $currentDepth = 0)
	{
		if (is_null($id_category))
			$id_category = $this->context->shop->getCategory();

		$children = array();

		if (isset($resultParents[$id_category]) && count($resultParents[$id_category]) && ($maxDepth == 0 || $currentDepth < $maxDepth))
			foreach ($resultParents[$id_category] as $subcat)
				$children[] = $this->getTree($resultParents, $resultIds, $maxDepth, $subcat['id_category'], $currentDepth + 1);
		if (!isset($resultIds[$id_category]))
			return false;
		$return = array('id' => $id_category, 'link' => $this->context->link->getCategoryLink($id_category, $resultIds[$id_category]['link_rewrite']),
				'name' => $resultIds[$id_category]['name'], 'desc'=> $resultIds[$id_category]['description'],
				'children' => $children);
		return $return;
	}

	public function displayFlags($languages, $default_language, $ids, $id, $return = false, $use_vars_instead_of_ids = false)
	{
		if (count($languages) == 1)
			return false;
		$output = '
		<div class="displayed_flag">
			<img src="../img/l/'.$default_language.'.jpg" class="pointer" id="language_current_'.$id.'" onclick="toggleLanguageFlags(this);" alt="" />
		</div>
		<div id="languages_'.$id.'" class="language_flags">
			'.$this->l('Choose language:').'<br /><br />';
		foreach ($languages as $language)
			if ($use_vars_instead_of_ids)
				$output .= '<img src="../img/l/'.(int)$language['id_lang'].'.jpg" class="pointer" alt="'.$language['name'].'" title="'.$language['name'].'" onclick="changeLanguage(\''.$id.'\', '.$ids.', '.$language['id_lang'].', \''.$language['iso_code'].'\');" /> ';
			else
			$output .= '<img src="../img/l/'.(int)$language['id_lang'].'.jpg" class="pointer" alt="'.$language['name'].'" title="'.$language['name'].'" onclick="changeLanguage(\''.$id.'\', \''.$ids.'\', '.$language['id_lang'].', \''.$language['iso_code'].'\');" /> ';
		$output .= '</div>';

		if ($return)
			return $output;
		echo $output;
	}

	public function displayForm()
	{
		$html = '';
		$disp_all = Configuration::get('facto_CATOGORIES_ALL');
		$displaythumb = Configuration::get('facto_CATOGORIES_THUMB');
		$id_customer = (int)$this->context->cookie->id_customer;
		$groups = $id_customer ? implode(',', Customer::getGroupsStatic($id_customer)) : Configuration::get('PS_UNIDENTIFIED_GROUP');
		//$id_product = (int)Tools::getValue('id_product', 0);
		//$id_category = (int)Tools::getValue('id_category', 0);
		$id_lang = ($this->context->cookie->id_lang);
		$maxdepth = Configuration::get('BLOCK_CATEG_MAX_DEPTH');

		$id_lang = (int)Context::getContext()->language->id;
		$languages = $this->context->controller->getLanguages();
		//$default_language = (int)Configuration::get('PS_LANG_DEFAULT');
		//$divLangName = 'link_label';
		$id_shop = (int)Shop::getContextShopID();

		$resultIds = array();
		$resultParents = array();
		$res_cat = Db::getInstance()->ExecuteS('

			SELECT c.id_parent, c.id_category, cl.name, cl.description, cl.link_rewrite
			FROM `'._DB_PREFIX_.'category` c
			INNER JOIN `'._DB_PREFIX_.'category_lang` cl ON (c.`id_category` = cl.`id_category` AND cl.`id_lang` = '.(int)$this->context->language->id.Shop::addSqlRestrictionOnLang('cl').')
			INNER JOIN `'._DB_PREFIX_.'category_shop` cs ON (cs.`id_category` = c.`id_category` AND cs.`id_shop` = '.(int)$this->context->shop->id.')
			WHERE (c.`active` = 1 OR c.`id_category` = '.(int)Configuration::get('PS_HOME_CATEGORY').')
			AND c.`id_category` != '.(int)Configuration::get('PS_ROOT_CATEGORY').'
			'.((int)$maxdepth != 0 ? ' AND `level_depth` <= '.(int)$maxdepth : '').'
			AND c.id_category IN (
				SELECT id_category
				FROM `'._DB_PREFIX_.'category_group`
				WHERE `id_group` IN ('.pSQL($groups).')
				)
			ORDER BY `level_depth` ASC, '.(Configuration::get('BLOCK_CATEG_SORT') ? 'cl.`name`' : 'cs.`position`').' '.(Configuration::get('BLOCK_CATEG_SORT_WAY') ? 'DESC' : 'ASC'));

		foreach ($res_cat as &$row)
		{
			$resultParents[$row['id_parent']][] = &$row;
			$resultIds[$row['id_category']] = &$row;
		}

		$blockCategTree = $this->getTree($resultParents, $resultIds, Configuration::get('BLOCK_CATEG_MAX_DEPTH'));

		$html .= '<link type="text/css" rel="stylesheet" href="'._MODULE_DIR_.$this->name.'/views/css/style.css" />
		<script src="'._MODULE_DIR_.$this->name.'/views/js/jqueryUI.js"></script>
		<script>
			$(function() {
				setTimeout(function() {
					$(".confirm, .error").hide("slow");
				}, 2000);
			});

			$(function() {
			$( "#sortable" ).sortable({
				//placeholder: "ui-state-highlight",
				update: function(e, ui) {        
					ui.item.attr("data-currindex", ui.item.index());
					var cat_pos = new Array();
		
					$( "#sortable li" ).each(function(){
						var id = $(this).attr("id");
						var posIndex = parseInt($(this).index());
						$("#position_"+id).val(posIndex);
						cat_pos[posIndex] = id;				
					});					

					var path = $("#path_1").val()+"ajaxPage.php?call_for=changePosition&cat_pos="+cat_pos;
					
					$.ajax({
						url:path,
						success:function(data){										
							$(".confirm").hide("slow");
							$(".posChange").show("slow").delay(2000).fadeOut("slow");
					} });
				}
			});

			$( "#sortable" ).disableSelection();
			});

		</script>

		<form action="'.Tools::safeOutput($_SERVER['REQUEST_URI']).'" method="post" class="defaultForm form-horizontal">

		<div class="admin-wrapper bootstrap">
			<fieldset class="panel">
				<h3><span class="icon-cogs"></span>'.$this->l(' Select New Sections').'</h3>
				<div class="item-field form-group">
						<label class="col-lg-3">
							<select id="rootCat" name="rootCat">
								<option value="Custom section_custom">Custom section</option>
								'.$this->renderCategories($blockCategTree['children'], 0).'
								<option value="Destacados_special">Destacados</option>
								<option value="Novedades_special">Novedades</option>
								<option value="Más vendidos_special">Más Vendidos</option>
								<option value="Ofertas_special">Ofertas</option>
							</select>
						</label>
						<div class="col-lg-7">
							<div class="input-group">
							<input type="hidden" id="selectedTab" name="selectedTab" value="" />
							<button type="submit" name="submit_add" id="submit_add" class="btn btn-default"><i class="icon-plus-sign">'.$this->l('Add Section').'</i></button>
							</div>
						</div>
				</div>

			<div class="item-field form-group">
					<div class="col-lg-3">
					<div class="input-group col-lg-12">
							<span class="switch prestashop-switch">
								<input type="radio" name="dispAll" id="dispAll" value="on" ';

									if ($disp_all == 'on') $html .= 'checked="checked"';
								$html .= '/>
								<label for="dispAll">'.$this->l('"All" Tab Enable').' </label>
							 <input type="radio" name="dispAll" id="dispAll off" value="off" ';
									if ($disp_all == 'off') $html .= 'checked="checked"';
								$html .= '/>
								<label for="dispAll off">'.$this->l('"All" Tab Disable').'</label>
								<a class="slide-button btn"></a>
							</span>
						</div>
						<div class="input-group col-lg-12">	
							'.$this->l('You can enable or disable "ALL" tab on Home page').'
						</div>
						
					</div>
					
					<div class="col-lg-5">
						<input type="submit" name="display_all" id="display_all" value="'.$this->l('Save').'" class="submitTab btn btn-default" />
					</div>
				</div>

				<div class="item-field form-group">
					<div class="col-lg-3">
						<div class="input-group col-lg-12 factobtn">
							<span class="oswitch facto-switch">
								<input type="radio" name="displaythumb" id="displaythumb" value="on" ';
									if ($displaythumb == 'on') $html .= 'checked="checked"';
								$html .= '/>
							<label for="displaythumb">'.$this->l('"Side Thumbnail" Enable').' </label>
							 <input type="radio" name="displaythumb" id="displaythumb off" value="off" ';
									if ($displaythumb == 'off') $html .= 'checked="checked"';
								$html .= '/>
								<label for="displaythumb off">'.$this->l('"Carousal Image" Enable').'</label>
								<a class="slide-button btn"></a>
							</span>
						</div>
						<div class="input-group col-lg-12">	
							'.$this->l('Side thumbnail will work only on Desktop').'
						</div>
					</div>	
						<div class="col-lg-5">
						<input type="submit" name="display_thumb" id="display_thumb" value="'.$this->l('Save').'" class="submitTab btn btn-default" />
					</div>
				</div>
				<div class="mainDiv">	
				<div class="language_colum">
				<div class="heading">
				<h3 class="title"><span class="icon-cogs"></span> '.$this->l('Created Sections').'';
				$html .= '</h3></div></div>';
				$html .= '<div class="sections_column">
						<div class="create_section"	>';
				$html .= '<ul id="sortable" class="tabs">';
				$sql_sel_prod = 'SELECT p.id_product, pl.name
						FROM '._DB_PREFIX_.'product p
						'.Shop::addSqlAssociation('product', 'p').'
						LEFT JOIN '._DB_PREFIX_.'product_lang pl ON (p.id_product = pl.id_product '.Shop::addSqlRestrictionOnLang('pl').')
						WHERE pl.id_lang = '.(int)$id_lang.'
						ORDER BY pl.name';
				$res_all_prod = Db::getInstance()->ExecuteS($sql_sel_prod);
				$prodList = array();
				foreach ($res_all_prod as $prod)
					$prodList[$prod['id_product']] = $prod['name'];
				/* Newly Added Digital facto START*/
				$productObj = new Product();
				$res_all_prod = $productObj->getProducts($id_lang, 0, 0, 'id_product', 'ASC' );
				/* Newly Added Digital facto END*/
				$sql = 'SELECT * FROM `'._DB_PREFIX_.'factotabsettings` WHERE `id_shop` = '.$id_shop.' AND `id_lang` = '.$id_lang.' ORDER BY position';
				$res_sql = Db::getInstance()->ExecuteS($sql);

				foreach ($res_sql as $res)

					$html .= '<li id="'.$res['id_tab'].'" class="section_tab"><div><a href="#div_'.$res['id_tab'].'" class="tab_head">'.$res['alias_name'].'</a> 	<span class="cross btn btn-danger" id="del_'.$res['id_tab'].'"> <i class="icon-remove"></i> </span></div></li>';

				$html .= '</ul>
					</div>
					<div class="tab_container">';
						$i = 0;
						foreach ($res_sql as $res)
						{
						$divLang = 'link_label_'.$res['id_tab'];
							$html .= '<div id="div_'.$res['id_tab'].'"';
							if ($i) $html .= 'style="display:none;"';
							$html .= ' class="prodTabs">
									<div class="form-group"></div>
									<div class="form-group"></div>
									<div class="form-group">
											<label class="control-label col-lg-3">'.$this->l('Section Title').'</label>
										<div class="col-lg-9">
											<div class="form-group">
											<div class="translatable-field lang-1">
										<div class="col-lg-9">';
										foreach ($languages as $language)
										{
												$a_name = '';
											$sql_aname = 'SELECT alias_name FROM '._DB_PREFIX_.'factotabsettings WHERE id_lang = '.(int)$language['id_lang'].' AND id_tab = '.$res['id_tab'];

												$a_name_arr = Db::getInstance()->ExecuteS($sql_aname);
												foreach ($a_name_arr as $alias)

													$a_name = $alias['alias_name'];

												$html .= '
													<div id="link_label_'.$res['id_tab'].'_'.(int)$language['id_lang'].'" style="display: '.($language['id_lang'] == $id_lang ? 'block' : 'none').';">
														<input type="text" name="aliasname_'.$res['id_tab'].'_'.(int)$language['id_lang'].'" id="aliasname_'.$res['id_tab'].'_'.(int)$language['id_lang'].'" value="'.$a_name.'" />
													</div>
												';
										}

											$html .= '</div><div class="col-lg-2"><input type="hidden" name="type_'.$res['id_tab'].'" value="'.$res['type'].'" />
															'.$this->displayFlags($languages, (int)$id_lang, $divLang, $divLang, true).'
												</div></div>
											</div>
										</div>
									</div>

									<div class="form-group" style="display:none">	
										<label class="control-label col-lg-3">'.$this->l('Position').'</label>
										<div class="col-lg-9">
											<div class="form-group">
												<div class="translatable-field lang-1">
													<div class="col-lg-9">
														<select  name="position_'.$res['id_tab'].'" id="position_'.$res['id_tab'].'" class="pos_select">';
													for ($pos = 1;
													$pos <= count($res_sql);
														$pos++)
														{
														$html .= '<option value="'.$pos.'"';
														if ($res['position'] == $pos)
														$html .= 'selected="selected"';
														$html .= '>'.$pos.'</option>';
														}
														$html .= '</select>
													</div>
												</div>
											</div>
										</div>
									</div>

									<div class="form-group">	
											<label class="control-label col-lg-3">
										'.$this->l('Status').'</label>
										<div class="col-lg-9">
											<div class="form-group">
											<div class="translatable-field lang-1">
												<div class="col-lg-9">
											<div class="input-group col-lg-2">
											<span class="switch prestashop-switch">
											<input type="radio" name="status_'.$res['id_tab'].'" id="status_1_'.$res['id_tab'].'"  value="1" ';
											if ($res['status'] == 1)
											$html .= 'checked="checked"';
											$html .= '/>
											<label for="status_1_'.$res['id_tab'].'">'.$this->l('on').'</label>
										    <input type="radio" name="status_'.$res['id_tab'].'" id="status_0_'.$res['id_tab'].'"  value="0"';
											if ($res['status'] == 0)

											$html .= 'checked="checked"';

											$html .= '/>

											<label for="status_0_'.$res['id_tab'].'">'.$this->l('off').'</label><a class="slide-button btn"></a></span>
											</div>
										</div>
											</div>
											</div>
										</div>
									</div>
							<div class="form-group">
										<label class="control-label col-lg-3">'.$this->l('Number of products to be displayed').'</label>

										<div class="col-lg-9">
												<div class="translatable-field lang-1">
													<input type="text" name="prod_count_'.$res['id_tab'].'" id="prod_count_1_'.$res['id_tab'].'"  value="'.$res['prod_count'].'" style="width:100px;"/>
												</div>	
												<div class="translatable-field lang-1">
												'.$this->l('Set the number of products that you would like to display on homepage - default: 8').'
													
												</div>
										</div>
									</div>';

									if ($res['type'] == 'custom')
									{
											$html .= '
									<div class="form-group">	
											<label class="control-label col-lg-3">'.$this->l('Add Products').'</label>
										<div class="col-lg-9">
												<div class="row">
													<div class="col-lg-4">
														<h4 style="margin-top:5px;">Available products</h4>
														<select name="prodList_'.$res['id_tab'].'" id="prodList_'.$res['id_tab'].'" multiple="multiple" style="width:300px; height: 160px;">';
															foreach ($res_all_prod as $prod)

																$html .= '<option value="'.$prod['id_product'].'">'.$prod['name'].'</option>';

												$html .= '
														</select>
													</div>
													<div class="col-lg-4">
													<h4 style="margin-top:5px;">Selected products</h4>
													<select name="selProdList_'.$res['id_tab'].'" id="selProdList_'.$res['id_tab'].'" class="selProdList" multiple="multiple" style="width:300px; height: 160px;">';
													$sql_sel_tab = 'SELECT products FROM '._DB_PREFIX_.'factotab_products WHERE tab_id='.$res['id_tab'];
													$res_prod = Db::getInstance()->getValue($sql_sel_tab);
													if ($res_prod)
													{
														$res_prod_arr = explode(',', $res_prod);
														foreach ($res_prod_arr as $prod_id)

														$html .= '<option value="'.$prod_id.'">'.$prodList[$prod_id].'</option>';

													}
											$html .= '
													</select>
													</div>
												</div>
											<br>
											<div class="row">
												<div class="col-lg-4"><button type="button" name="addProd_'.$res['id_tab'].'" id="addProd_'.$res['id_tab'].'" class="addProd btn btn-success">Add <i class="icon-arrow-right"></i></button></div>
												<div class="col-lg-4"><button type="button" name="remProd_'.$res['id_tab'].'" id="remProd_'.$res['id_tab'].'" class="remProd btn btn-danger"><i class="icon-arrow-left"></i> Remove</button></div>
											</div>
										</div>
									</div>';
									}
										$html .= '
										<div class="form-group">
										<label class="control-label col-lg-3"></label>
											<div class="col-lg-4">
												<button type="submit" name="submit_'.$res['id_tab'].'" id="submit_'.$res['id_tab'].'" value="" class="submitTab btn btn-default"><i class="process-icon-save"></i>'.$this->l('Save').'</button>
											</div>
										</div>	
							</div>
							';
							$i++;
						}
				$html .= '
				<label class="control-label col-lg-3">'.$this->l('No olvide ir a párametros avanzados / Rendimiento. Y Vaciar la caché, después de cualquier cambio').'</label>
				</div>		
					</div>
				</div>
				<input type="hidden" name="tabid" id="tabid"  value=""/>
				<input type="hidden" name="selProduct" id="selProduct"  value=""/>
				<input type="hidden" name="path_1" id="path_1" value="'._MODULE_DIR_.$this->name.'/" />
				<script>
					$(document).ready(function(){
						$("#submit_add").click(function(){ $("#selectedTab").val($("#rootCat").val());	});
						$(".section_tab a").click(function(){
									var curMenu=$(this);
        							if (!$(this).hasClass("active")) { 
	        						$(".section_tab a").removeClass("active");
	        						curMenu.addClass("active");
							}
						});	
						$(".tab_head").click(function(){
								$(".tab_head").removeClass("selectedFix")
								$(this).addClass("selectedFix")								
								$(".prodTabs").hide();	
								var tabId = $(this).attr("href");	$(tabId).show();	
								var tabIdArr = tabId.split("_");
								$("#tabSelect").val(tabIdArr[1]);
								$("#selProdList_"+tabIdArr[1]+" option").prop("selected", "selected");
								$("#selProduct").val($("#selProdList_"+tabIdArr[1]).val());
								return false;
						});							
						$(".submitTab").click(function(){
							var subArr = $(this).attr("id").split("_"); $("#tabid").val(subArr[1]);								
						});
						$(".addProd").click(function(){
							var tabIdArr = $(this).attr("id").split("_"); var tabId = tabIdArr[1];
							var orgProd = "prodList_"+tabId; var selProd = "selProdList_"+tabId;
							var flag=0; var valSel ="";
							$("#"+orgProd+" option:selected").each(function(){
								flag=1; valSel = $(this).val();
								$("#"+selProd+" option").each(function(){ if(valSel == $(this).val()){ flag=0; } });
								if(flag){ var option =$(this).clone(); $("#"+selProd).append(option); }
							});
							$("#selProdList_"+tabId+" option").prop("selected", "selected");
							$("#selProduct").val($("#selProdList_"+tabId).val());
							$("#selProdList_"+tabId+" option").prop("selected", "");
							$("#"+orgProd+" option:selected").prop("selected", "");
						});
						
						$(".remProd").click(function(){	
							var tabIdArr = $(this).attr("id").split("_");
							var selProd = "selProdList_"+tabIdArr[1];
							$("#"+selProd+" option:selected").remove();
							$("#selProdList_"+tabIdArr[1]+" option").prop("selected", "selected");
							$("#selProduct").val($("#selProdList_"+tabIdArr[1]).val());
							$("#selProdList_"+tabIdArr[1]+" option").prop("selected", "");
						});

						$(".cross").click(function(){
							var flag = confirm("Are you sure to delete this section?");
							
							if(flag == true){
								var idArr = $(this).attr("id").split("_");	 var path = $("#path_1").val()+"ajaxPage.php?call_for=delete&id_tab="+idArr[1];
								
							$.ajax({
									url:path,
									success:function(data){	
									$("#"+idArr[1]).remove();
									$("#div_"+idArr[1]).remove();
										$(".confirm").hide("slow");
										$(".deleted").show("slow").delay(3000).fadeOut("slow");
									} });
							}else{ return false; }
						});
						var firstTabHead = $("#sortable").find("li a.tab_head:first");
						if(firstTabHead.hasClass("custom"))
							firstTabHead.click();
					});
				</script>
			</fieldset>
		</div>
		</form>';
		return $html;
	}

	public function hookDisplayHeader($params)
	{
	if ((Configuration::get('facto_CATOGORIES_THUMB')) == 'on');

	$this->context->controller->addCSS($this->_path.'views/css/factocategories.css');

	$this->context->controller->addJS(($this->_path).'views/js/factocategories.js');
	$this->context->controller->addJS(($this->_path).'views/js/jquery.isotope.min.js');
	$this->context->controller->addJS(($this->_path).'views/js/factocustom.js');


	if ((Configuration::get('facto_CATOGORIES_THUMB')) == 'on')
		$this->context->controller->addCSS($this->_path.'views/css/side-gridfive.css');
	else
		$this->context->controller->addCSS($this->_path.'views/css/gridfive.css');
	}

	protected function getCacheId($name = null)
	{
		return parent::getCacheId('factocategories|'.$name);
	}

	public function hookDisplayCasualtabs($params)
	{
		$cat_id = array();
		$prod_count = array();
		$type = array();
		$cat_display = array();
		$id_tab = array();
		$alias_name = array();
		$cat_display_name = array();
		$name = array();
		$lang = $this->context->language->id;
		$id_shop = (int)Shop::getContextShopID();
		$sel_cat = Configuration::get('DISPLAY_facto_CATEGORY');
		$sel_cat_arr = explode('##', $sel_cat);
		$cat_prods = array();
		$all_prods = array();

		$prod_images = array();
		$all_prod_arr = array();
		$all_prod = array();

		$prod_category = '';
		$category = new Category(Context::getContext()->shop->getCategory(), (int)Context::getContext()->language->id);
		//$topSellProd = $featuredProducts = $newProducts = '';

		$sql_sel_tab_id = 'SELECT DISTINCT(id_tab) FROM `'._DB_PREFIX_."factotabsettings` WHERE status = '1' AND id_shop = ".$id_shop.' ORDER BY position';
		$result_tab_id = Db::getInstance()->ExecuteS($sql_sel_tab_id);
		foreach ($result_tab_id as $tab_id_arr)
		{
			$tab_id = $tab_id_arr['id_tab'];
			$sql_sel = 'SELECT count(*) FROM `'._DB_PREFIX_.'factotabsettings` WHERE `id_lang` = '.$lang.' AND `id_tab` = '.$tab_id;
			$resultCount = Db::getInstance()->getValue($sql_sel);
			if ($resultCount > 0)
				$sql_sel = 'SELECT * FROM `'._DB_PREFIX_.'factotabsettings` WHERE `id_lang` = '.$lang.' AND `id_tab` = '.$tab_id;
			else
			{
				$sql_lang = 'SELECT id_lang FROM `'._DB_PREFIX_.'factotabsettings` WHERE `id_tab` = '.$tab_id.' LIMIT 1';
				$id_lang_arr = Db::getInstance()->ExecuteS($sql_lang);
				$id_lang = $id_lang_arr[0]['id_lang'];
				$sql_sel = 'SELECT * FROM `'._DB_PREFIX_.'factotabsettings` WHERE `id_lang` = '.(int)$id_lang.' AND `id_tab` = '.$tab_id;
			}

			$resultant = Db::getInstance()->ExecuteS($sql_sel);
			foreach ($resultant as $result)
			{
				extract($result);
				if ($prod_count == 0) $prod_count = 1000;
				if ($type == 'category')
				{
					$categoryObj = new Category($cat_id);
					$cat_prods = $categoryObj->getProducts(($params['cookie']->id_lang), 0, $prod_count);
					if ($cat_prods)
					{
							$cat_display[$id_tab] = str_replace(' ', '_', Tools::strtolower($alias_name));
							$cat_display_name[$id_tab] = $alias_name;
						foreach ($cat_prods as $prod1)
						{
							$all_prods[$prod1['id_product']] = $prod1;
							//Thumb image coding started ($prod changed to $prod1 on previous line and last line)
							$prod = new Product($prod1['id_product']);
							$images = $prod->getImages((int)$this->context->cookie->id_lang);
							$product_images = array();
							foreach ($images as $k => $image)
								$product_images[(int)$image['id_image']] = $image;

							$prod_images[$prod1['id_product']] = $product_images;
							$all_prod_arr[$prod1['id_product']] = $prod1;
				//Thumb image coding ended
							$prod_category .= str_replace(' ', '_', Tools::strtolower($alias_name)).'::'.$prod1['id_product'].'##';
						}
					}
				}
				elseif ($type == 'special')
				{
					if ($name == 'Más vendidos')
					{
						$bestproducts = ProductSale::getBestSalesLight((int)$params['cookie']->id_lang, 0, $prod_count);
						
						if ($bestproducts)
						{
							$cat_display[$id_tab] = str_replace(' ', '_', Tools::strtolower($alias_name));
							$cat_display_name[$id_tab] = $alias_name;
							foreach ($bestproducts as $prod1)
							{
							$all_prods[$prod1['id_product']] = $prod1;
							//Thumb image coding started ($prod changed to $prod1 on previous line and last line)
							$prod = new Product($prod1['id_product']);
							$images = $prod->getImages((int)$this->context->cookie->id_lang);
							$product_images = array();
							foreach ($images as $k => $image)
								$product_images[(int)$image['id_image']] = $image;

							$prod_images[$prod1['id_product']] = $product_images;
							$all_prod_arr[$prod1['id_product']] = $prod1;
							//Thumb image coding ended
							$prod_category .= str_replace(' ', '_', Tools::strtolower($alias_name)).'::'.$prod1['id_product'].'##';
							}
						}
					}
					else if ($name == 'Novedades')
					{
						$new_products = Product::getNewProducts(($params['cookie']->id_lang), 0, $prod_count);
						if ($new_products)
						{
							$cat_display[$id_tab] = str_replace(' ', '_', Tools::strtolower($alias_name));
							$cat_display_name[$id_tab] = $alias_name;
							foreach ($new_products as $prod1)
							{
							$all_prods[$prod1['id_product']] = $prod1;
							//Thumb image coding started ($prod changed to $prod1 on previous line and last line)
							$prod = new Product($prod1['id_product']);
							$images = $prod->getImages((int)$this->context->cookie->id_lang);
							$product_images = array();
							foreach ($images as $k => $image)
								$product_images[(int)$image['id_image']] = $image;

							$prod_images[$prod1['id_product']] = $product_images;
							$all_prod_arr[$prod1['id_product']] = $prod1;
							//Thumb image coding ended
							$prod_category .= str_replace(' ', '_', Tools::strtolower($alias_name)).'::'.$prod1['id_product'].'##';
							}
						}
					}
					else if ($name == 'Destacados')
					{
						$featured_products = $category->getProducts(($params['cookie']->id_lang), 0, $prod_count);
						if ($featured_products)
						{
							$cat_display[$id_tab] = str_replace(' ', '_', Tools::strtolower($alias_name));
							$cat_display_name[$id_tab] = $alias_name;
							foreach ($featured_products as $prod1)
							{
							$all_prods[$prod1['id_product']] = $prod1;
							//Thumb image coding started ($prod changed to $prod1 on previous line and last line)
							$prod = new Product($prod1['id_product']);
							$images = $prod->getImages((int)$this->context->cookie->id_lang);
							$product_images = array();
							foreach ($images as $k => $image)
								$product_images[(int)$image['id_image']] = $image;

							$prod_images[$prod1['id_product']] = $product_images;
							$all_prod_arr[$prod1['id_product']] = $prod1;
							//Thumb image coding ended
							$prod_category .= str_replace(' ', '_', Tools::strtolower($alias_name)).'::'.$prod1['id_product'].'##';
							}
						}
					}
					else if ($name == 'Ofertas')
					{
						$specialspro = Product::getPricesDrop((int)$params['cookie']->id_lang, 0, $prod_count);
						if ($specialspro)
						{
							$cat_display[$id_tab] = str_replace(' ', '_', Tools::strtolower($alias_name));
							$cat_display_name[$id_tab] = $alias_name;
							foreach ($specialspro as $prod1)
							{
							$all_prods[$prod1['id_product']] = $prod1;
							//Thumb image coding started ($prod changed to $prod1 on previous line and last line)
							$prod = new Product($prod1['id_product']);
							$images = $prod->getImages((int)$this->context->cookie->id_lang);
							$product_images = array();
							foreach ($images as $k => $image)
								$product_images[(int)$image['id_image']] = $image;

							$prod_images[$prod1['id_product']] = $product_images;
							$all_prod_arr[$prod1['id_product']] = $prod1;
							//Thumb image coding ended
							$prod_category .= str_replace(' ', '_', Tools::strtolower($alias_name)).'::'.$prod1['id_product'].'##';
							}
						}
					}
				}
				elseif ($type == 'custom')
				{
					$sql_sel_prod = 'SELECT products FROM `'._DB_PREFIX_.'factotab_products` WHERE `tab_id` = '.$id_tab;
					$resultProd = Db::getInstance()->getValue($sql_sel_prod);

					if ($resultProd)
					{
						$cat_display[$id_tab] = str_replace(' ', '_', $id_tab);
						$cat_display_name[$id_tab] = $alias_name;
						$prod_arr = explode(',', $resultProd);
						$sel_prod_cnt = count($prod_arr);
						if ($sel_prod_cnt > $prod_count)
							$prod_arr = array_slice($prod_arr, 0, $prod_count);
						foreach ($prod_arr as $prod_id)
						{
							//Thumb image coding started ($prod changed to $prod1 on previous line and last line)
							$prod = new Product($prod_id);
							$res = $this->getProductById($prod_id);
							$all_prods[$prod_id] = $res[0];
							$images = $prod->getImages((int)$this->context->cookie->id_lang);
							$product_images = array();
							foreach ($images as $k => $image)
								$product_images[(int)$image['id_image']] = $image;

							$prod_images[$prod_id] = $product_images;
							$all_prod_arr[$prod_id] = $prod_id;
							//Thumb image coding ended
							$prod = new Product($prod_id);
							$cat_id = $prod->id_category_default;
							$category = new Category($cat_id);
							$cat_prods = $category->getProducts($id_lang, 1, '');
							if (!empty($cat_prods))
							{
								foreach ($cat_prods as $cat_prod)
								{
									if ($cat_prod['id_product'] == $prod->id)

										$all_prods[$prod_id] = $cat_prod;

								}
							}
							$prod_category .= str_replace(' ', '_', $id_tab).'::'.$prod_id.'##';
						}

					}
				}
			}
		}

		foreach ($all_prod_arr as $prod1)

		$all_prod[] = $prod1;
	if (!$this->isCached('blockfactocategories-side.tpl', $this->getCacheId()))
		{
			$this->smarty->assign(array(
				'products' => $all_prods,
				'allProd' => $all_prod,
				'prodImages' => $prod_images,
				'add_prod_display' => Configuration::get('PS_ATTRIBUTE_CATEGORY_DISPLAY'),
				'homeSize' => Image::getSize(ImageType::getFormatedName('home')),
				'prodCategory' => $prod_category,
				'dispAll' => Configuration::get('facto_CATOGORIES_ALL'),
				'displaythumb' => Configuration::get('facto_CATOGORIES_THUMB'),
			));
	
			$this->smarty->assign('sel_cat_arr', @$cat_display);
			$this->smarty->assign('selCatNameArr', @$cat_display_name);
		}
		else if (!$this->isCached('blockfactocategories.tpl', $this->getCacheId()))
		{
			$this->smarty->assign(array(
				'products' => $all_prods,
				'allProd' => $all_prod,
				'prodImages' => $prod_images,
				'add_prod_display' => Configuration::get('PS_ATTRIBUTE_CATEGORY_DISPLAY'),
				'homeSize' => Image::getSize(ImageType::getFormatedName('home')),
				'prodCategory' => $prod_category,
				'dispAll' => Configuration::get('facto_CATOGORIES_ALL'),
				'displaythumb' => Configuration::get('facto_CATOGORIES_THUMB'),
			));
	
			$this->smarty->assign('sel_cat_arr', @$cat_display);
			$this->smarty->assign('selCatNameArr', @$cat_display_name);

		}
		if ((Configuration::get('facto_CATOGORIES_THUMB')) == 'on')
			return $this->display(__FILE__, 'blockfactocategories-side.tpl', $this->getCacheId());
		else
			return $this->display(__FILE__, 'blockfactocategories.tpl', $this->getCacheId());

	}

	public function renderCategories($categories, $level)
	{
		$output = '';
		foreach ($categories as $category)
		{
			$output .= "<option value='".$category['name'].'_category_'.$category['id']."'>".$category['name'].'</option>';
			if (count($category['children']) > 0)
			$output .= $this->renderCategories($category['children'], $level + 1);

		}
		return $output;
	}

	public function getProductById($productId, Context $context = null)
	{
		$context = Context::getContext();
		//$id_category = '';
		$id_lang = ($this->context->cookie->id_lang);
		$sql = 'SELECT p.*, product_shop.*, stock.out_of_stock, IFNULL(stock.quantity, 0) as quantity, MAX(product_attribute_shop.id_product_attribute) id_product_attribute, product_attribute_shop.minimal_quantity AS product_attribute_minimal_quantity, pl.description, pl.description_short, pl.available_now,
					pl.available_later, pl.link_rewrite, pl.meta_description, pl.meta_keywords, pl.meta_title, pl.name, MAX(image_shop.id_image) id_image,
					il.legend, m.name AS manufacturer_name, cl.name AS category_default,
					DATEDIFF(product_shop.date_add, DATE_SUB(NOW(),
					INTERVAL '.(Validate::isUnsignedInt(Configuration::get('PS_NB_DAYS_NEW_PRODUCT')) ? Configuration::get('PS_NB_DAYS_NEW_PRODUCT') : 20).'
						DAY)) > 0 AS new, product_shop.price AS orderprice
				FROM '._DB_PREFIX_.'category_product cp
				LEFT JOIN '._DB_PREFIX_.'product p
					ON p.id_product = cp.id_product
				'.Shop::addSqlAssociation('product', 'p').'
				LEFT JOIN '._DB_PREFIX_.'product_attribute pa
				ON (p.id_product = pa.id_product)
				'.Shop::addSqlAssociation('product_attribute', 'pa', false, 'product_attribute_shop.default_on = 1').'
				'.Product::sqlStock('p', 'product_attribute_shop', false, $context->shop).'
				LEFT JOIN '._DB_PREFIX_.'category_lang cl
					ON (product_shop.id_category_default = cl.id_category
					AND cl.id_lang = '.(int)$id_lang.Shop::addSqlRestrictionOnLang('cl').')
				LEFT JOIN '._DB_PREFIX_.'product_lang pl
					ON (p.id_product = pl.id_product
					AND pl.id_lang = '.(int)$id_lang.Shop::addSqlRestrictionOnLang('pl').')
				LEFT JOIN '._DB_PREFIX_.'image i
					ON (i.id_product = p.id_product)'.
				Shop::addSqlAssociation('image', 'i', false, 'image_shop.cover=1').'
				LEFT JOIN '._DB_PREFIX_.'image_lang il
					ON (image_shop.id_image = il.id_image
					AND il.id_lang = '.(int)$id_lang.')
				LEFT JOIN '._DB_PREFIX_.'manufacturer m
					ON m.id_manufacturer = p.id_manufacturer
				WHERE product_shop.id_shop = '.(int)$context->shop->id.'
					AND p.id_product = '.(int)$productId.' GROUP BY product_shop.id_product';

		$rq = Db::getInstance(_PS_USE_SQL_SLAVE_)->executeS($sql);
		return Product::getProductsProperties($id_lang, $rq);
	}
}