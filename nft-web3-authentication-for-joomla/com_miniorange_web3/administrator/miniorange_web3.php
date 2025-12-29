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
use Joomla\CMS\Language\Text;
use Joomla\CMS\MVC\Controller\BaseController;
 require_once JPATH_COMPONENT . '/helpers/mo-web3-utility.php';
 require_once JPATH_COMPONENT . '/helpers/mo-web3-customer-setup.php';
 require_once JPATH_COMPONENT . '/helpers/mo_web3_support.php';
 require_once JPATH_COMPONENT . '/helpers/miniorange_web3.php';
 require_once JPATH_COMPONENT . '/helpers/MoWeb3Constants.php';

// Access check.
if (!Factory::getUser()->authorise('core.manage', 'com_miniorange_web3'))
{
	throw new Exception(Text::_('JERROR_ALERTNOAUTHOR'));
}

// Include dependancies
jimport('joomla.application.component.controller');

JLoader::registerPrefix('Miniorange_web3', JPATH_COMPONENT_ADMINISTRATOR);

$controller = BaseController::getInstance('Miniorange_web3');
if(!empty(Factory::getApplication()->input->get('task')))
{
  $controller->execute(Factory::getApplication()->input->get('task'));
}
else
{
    $controller->execute('');   
}
$controller->redirect();
