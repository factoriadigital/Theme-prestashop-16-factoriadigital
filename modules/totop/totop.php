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

class ToTop extends Module
{
public function __construct()
{
	$this->name = 'totop';
	$this->tab = 'advertising_marketing';
	$this->version = '2.0.0';
	$this->author = 'Factoriadigital';

	parent::__construct();
	
	$this->displayName = $this->l('Ir al top');
	$this->description = $this->l('');
}

public function install()
{
	if (!parent::install() OR !$this->registerHook('footer'))
		return false;
	return true;
}

public function hookFooter($params)
{
	$this->smarty->assign('this_path', $this->_path);
	return $this->display(__FILE__, 'totop.tpl');
}
	
}
