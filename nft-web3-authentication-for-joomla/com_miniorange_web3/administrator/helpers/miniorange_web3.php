<?php

/*
* @package    miniOrange
* @subpackage Plugins
* @license    GNU/GPLv3
* @copyright  Copyright 2023 miniOrange. All Rights Reserved.
*/
// No direct access
defined('_JEXEC') or die;
use Joomla\CMS\Factory;
use Joomla\CMS\Object\CMSObject;
use Joomla\CMS\Language\Text;

/**
 * Miniorange_web3 helper.
 *
 * @since  1.6
 */
class Miniorange_web3HelpersMiniorange_web3
{
	/**
	 * Configure the Linkbar.
	 *
	 * @param   string  $vName  string
	 *
	 * @return void
	 */
	public static function addSubmenu($vName = '')
	{
		return [
			[
				'title' => Text::_('COM_MINIORANGE_WEB3_TITLE_MYACCOUNTS'),
				'link' => 'index.php?option=com_miniorange_web3&view=myaccounts',
				'active' => ($vName == 'myaccounts')
			]
		];
	}
	

	/**
	 * Gets a list of the actions that can be performed.
	 *
	 * @return    CMSObject
	 *
	 * @since    1.6
	 */
	public static function getActions()
	{
		$user   = Factory::getUser();
		$result = new CMSObject;

		$assetName = 'com_miniorange_web3';

		$actions = array(
			'core.admin', 'core.manage', 'core.create', 'core.edit', 'core.edit.own', 'core.edit.state', 'core.delete'
		);

		foreach ($actions as $action)
		{
			$result->set($action, $user->authorise($action, $assetName));
		}

		return $result;
	}
}
