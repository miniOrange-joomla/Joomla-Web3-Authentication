<?php
/**
 * Created by PhpStorm.
 * User: miniorange
 * Date: 07-10-2018
 * Time: 02:53
 */
defined('_JEXEC') or die;
/*
 * @package    miniOrange
 * @subpackage Plugins
 * @license    GNU/GPLv3
 * @copyright  Copyright 2015 miniOrange. All Rights Reserved.
*/

include "BasicEnum.php";

class mo_configure_settings extends BasicEnum{
    const enable_web3_user_login = "enable_web3_user_login";
}

class mo_proxy extends BasicEnum{
    const proxy_host_name = "proxy_host_name";
    const port_number = "port_number";
    const username ="username";
    const password = "password";
}

