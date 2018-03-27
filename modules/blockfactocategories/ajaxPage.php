<?php
/**
* 2007-2015 PrestaShop
*
* NOTICE OF LICENSE
*
* This source file is subject to the Open Software License (OSL 3.0)
* that is bundled with this package in the file LICENSE.txt.
* It is also available through the world-wide-web at this URL:
* http://opensource.org/licenses/osl-3.0.php
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
*  @license   http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
*  International Registered Trademark & Property of PrestaShop SA
*/

include(dirname(__FILE__).'/../../config/config.inc.php');

		$cat_pos_arr = array();
		$call_for = array();
	extract($_REQUEST);
	if ($call_for == 'delete')
		deleteNode();

	if ($call_for == 'changePosition')

		changePosition();


	function deleteNode()
	{
		$id_tab = array();
		extract($_REQUEST);
		$sql_del = 'DELETE FROM `'._DB_PREFIX_.'factotabsettings` WHERE `id_tab` = '.$id_tab;
		Db::getInstance()->Execute($sql_del);
		$sql_del = 'DELETE FROM `'._DB_PREFIX_.'factotab_products` WHERE `tab_id` = '.$id_tab;
		Db::getInstance()->Execute($sql_del);
	}

	function changePosition()
	{
		$cat_pos = array();
		extract($_REQUEST);
		$cat_pos_arr = explode(',', $cat_pos);
		print_r($cat_pos_arr);
		$i = 1;
		foreach ($cat_pos_arr as $cat_pos)
		{
			$sql_upd = 'UPDATE `'._DB_PREFIX_.'factotabsettings` SET `position`='.$i.' WHERE `id_tab` = '.$cat_pos;
			Db::getInstance()->Execute($sql_upd);
			$i += 1;
		}
	}
?>