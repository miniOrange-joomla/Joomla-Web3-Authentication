<?php
/**
 * @version    CVS: 1.0.0
 * @package    Com_miniorange_web3
 * @author     miniOrange Security Software Pvt. Ltd. <info@xecurify.com>
 * @copyright  Copyright 2023 miniOrange. All Rights Reserved.
 * @license    GNU/GPLv3
 */

// No direct access
defined('_JEXEC') or die;
use Joomla\CMS\MVC\Controller\FormController;
use Joomla\CMS\Factory;
use Joomla\CMS\Language\Text;

jimport('joomla.application.component.controllerform');
jimport('joomla.filesystem.file');


/**
 * Myaccount controller class.
 *
 * @since  1.6
 */
class Miniorange_web3ControllerMyaccount extends FormController
{
    function __construct()
    {
        $this->view_list = 'myaccounts';
        parent::__construct();
    }

    function requestForTrialPlan()
    {
        $post = Factory::getApplication()->input->post->getArray();
        if ((!isset($post['email'])) || (!isset($post['plan'])) || (!isset($post['description']))) {
            $this->setRedirect('index.php?option=com_miniorange_web3&tab=config_settings');
            return;
        }
        $email = $post['email'];
        $plan = $post['plan'];
        $description = trim($post['description']);
        $demo = 'Trial';

        $customer = new Mo_web3_Local_Customer();
        $response = json_decode($customer->request_for_trial($email, $plan, $demo, $description));

        if ($response->status != 'ERROR')
        {
            $msg = ($demo == 'Demo') 
            ? Text::_('COM_MINIORANGE_WEB3_DEMO_REQUEST_RECEIVED') 
            : Text::_('COM_MINIORANGE_WEB3_TRIAL_REQUEST_RECEIVED');
            $this->setRedirect('index.php?option=com_miniorange_web3&tab=config_settings', $msg);
        }
        else {
            $this->setRedirect('index.php?option=com_miniorange_web3&tab=config_settings',Text::_('COM_MINIORANGE_WEB3_SERVER_BUSY'),'error');     
             return;
        }

    }


    function importexport()
    {
        $customer = new Mo_Web3_Local_Util();
        $customerResult = $customer->_load_db_values('#__miniorange_web3_config_settings');

        if (empty($customerResult['enable_web3_user_login'])) {
            $this->setRedirect('index.php?option=com_miniorange_web3&tab=config_settings', Text::_('COM_MINIORANGE_WEB3_CONFIGURE_WALLET_METHOD'), 'error');
            return;
        }
        $post = Factory::getApplication()->input->post->getArray();
        require_once JPATH_SITE . DIRECTORY_SEPARATOR . 'administrator' . DIRECTORY_SEPARATOR . 'components' . DIRECTORY_SEPARATOR . 'com_miniorange_web3' . DIRECTORY_SEPARATOR . 'helpers' . DIRECTORY_SEPARATOR . 'export.php';

        define("Tab_Class_Names", serialize(array(
            "configure_settings" => 'mo_configure_settings',
            "proxy" => 'mo_proxy'
        )));

        $tab_class_name = unserialize(Tab_Class_Names);
        $configuration_array = array();
        foreach ($tab_class_name as $key => $value) {
            $configuration_array[$key] = $this->mo_get_configuration_array($value);
        }

        if ($configuration_array) {
            header("Content-Disposition: attachment; filename=miniorange-web3-config.json");
            echo(json_encode($configuration_array, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES));
            exit;
        }
        if(isset($post) && $post['test_configuration']=='true')
        {
            $this->setRedirect('index.php?morequest=acs', Text::_('COM_MINIORANGE_WEB3_DOWNLOAD_SUCCESS'));
            return;
        }
        $this->setRedirect('index.php?option=com_miniorange_web3&tab=config_settings', Text::_('COM_MINIORANGE_WEB3_DOWNLOAD_SUCCESS'));
        return;
    }

    function mo_get_configuration_array($class_name)
    {
        if ($class_name == 'mo_configure_settings') {
            $customerResult = Mo_Web3_Local_Util::_get_values_from_table('#__miniorange_web3_config_settings');
        }
        if ($class_name == 'mo_proxy') {
            $customerResult = Mo_Web3_Local_Util::_get_values_from_table('#__miniorange_web3_proxy_setup');
        }

        $class_object = call_user_func($class_name . '::getConstants');
        $mo_array = array();

        foreach ($class_object as $key => $value) {
            if ($mo_option_exists = $customerResult[$value])
                $mo_array[$key] = $mo_option_exists;
        }
        return $mo_array;
    }

    function saveAdminMail()
    {
        $post=	Factory::getApplication()->input->post->getArray();
        $db = Factory::getDbo();
        $query = $db->getQuery(true);
        $fields = array(
            $db->quoteName('email') . ' = '.$db->quote($post['admin_email']),

        );

        $conditions = array(
            $db->quoteName('id') . ' = 1'
        );

        $query->update($db->quoteName('#__miniorange_web3_customer_details'))->set($fields)->where($conditions);
        $db->setQuery($query);
        $result = $db->execute();
        $this->setRedirect('index.php?option=com_miniorange_web3&tab=config_settings', Text::_('COM_MINIORANGE_WEB3_EMAIL_CHANGED'));
        return;
    }

    function saveConfig()
    {
        $dest_file = JPATH_ROOT . "/ajax.php";
        if (!file_exists($dest_file)) {
            Mo_Web3_Local_Util::create_ajax_file($dest_file);
        }

        $post = Factory::getApplication()->input->post->getArray();

        if (count($post) == 0) {
            $this->setRedirect('index.php?option=com_miniorange_web3&tab=config_settings');
            return;
        }

        $wallet_array = $post;

        $web3_user_login_array = array();
        foreach ($wallet_array as $key => $value)
        {
            if($key != 'option1')
            {
                $web3_user_login_array[$key] = $value;
            }
        }

        $database_name = '#__miniorange_web3_config_settings';
        $updatefieldsarray = array(
                'enable_web3_user_login'        => json_encode($web3_user_login_array),
        );

        $result = new Mo_Web3_Local_Util();
        $result->generic_update_query($database_name, $updatefieldsarray);

        //Save web3 configuration
        $message = Text::_('COM_MINIORANGE_WEB3_CONFIG_SAVED');
        $this->setRedirect('index.php?option=com_miniorange_web3&tab=config_settings', $message);
    }

    function contactUs()
    {
        $post = Factory::getApplication()->input->post->getArray();
        if (count($post) == 0) {
            $this->setRedirect('index.php?option=com_miniorange_web3&tab=overview');
            return;
        }
        if (isset($post['query_phone']) && $post['query_phone'] != NULL) {
            $pgone_num_validate = preg_match("/^\+?[0-9]+$/", $post['query_phone']);
            if (!$pgone_num_validate) {
                $this->setRedirect('index.php?option=com_miniorange_web3&tab=overview', Text::_('COM_MINIORANGE_WEB3_INVALID_PHONE'), 'error');
                return;
            }
        }

        if (Mo_Web3_Local_Util::check_empty_or_null($post['query_email'])) {
            $msg='Please submit your query with email.';
            $type='error';
        } else if (Mo_Web3_Local_Util::check_empty_or_null(trim($post['mo_saml_query_support'] || trim($post['mo_saml_query_support'])))) {
            $msg='Query cannot be empty.';
            $type='error';
        } else {
            $query = $post['mo_saml_query_support'];
            $email = $post['query_email'];
            $phone = $post['query_phone'];
            $contact_us = new Mo_web3_Local_Customer();
            $submited = json_decode($contact_us->submit_contact_us($email, $phone, $query), true);
            if (json_last_error() == JSON_ERROR_NONE) {
                if (is_array($submited) && array_key_exists('status', $submited) && $submited['status'] == 'ERROR') {
                    $msg=$submited['message'];
                    $type='error';
                } else {
                    if ($submited == false) {
                        $msg = Text::_('COM_MINIORANGE_WEB3_QUERY_FAILED');
                        $type='error';
                    } else {
                        $msg = Text::_('COM_MINIORANGE_WEB3_QUERY_SUCCESS');
                        $type='success';
                    }
                }
            }
        }

        $this->setRedirect('index.php?option=com_miniorange_web3&tab=overview', $msg,$type);
    }


    function callContactUs()
    {
        $post = Factory::getApplication()->input->post->getArray();
        if (count($post) == 0) {
            $this->setRedirect('index.php?option=com_miniorange_web3&tab=overview');
            return;
        }
        $query_email = $post['mo_sp_setup_call_email'];
        $query = $post['mo_sp_setup_call_issue'];
        $description = $post['mo_sp_setup_call_desc'];
        $callDate = $post['mo_sp_setup_call_date'];
        $timeZone = $post['mo_sp_setup_call_timezone'];
        if ($this->checkEmptyOrNull($timeZone) || $this->checkEmptyOrNull($callDate) || $this->checkEmptyOrNull($query_email) || $this->checkEmptyOrNull($query) || $this->checkEmptyOrNull($description)) {
            $this->setRedirect('index.php?option=com_miniorange_web3&tab=overview', Text::_('COM_MINIORANGE_WEB3_MANDATORY_FIELDS'), 'error');
            return;
        } else {
            $contact_us = new Mo_web3_Local_Customer();
            $submited = json_decode($contact_us->request_for_setupCall($query_email, $query, $description, $callDate, $timeZone), true);
            if (json_last_error() == JSON_ERROR_NONE) {
                if (is_array($submited) && array_key_exists('status', $submited) && $submited['status'] == 'ERROR') {
                    $this->setRedirect('index.php?option=com_miniorange_web3&tab=overview', $submited['message'], 'error');
                } else {
                    if ($submited == false) {
                        $this->setRedirect('index.php?option=com_miniorange_web3&tab=overview', Text::_('COM_MINIORANGE_WEB3_QUERY_FAILED'), 'error');
                    } else {
                        $this->setRedirect('index.php?option=com_miniorange_web3&tab=overview', Text::_('COM_MINIORANGE_WEB3_QUERY_SUCCESS'));
                    }
                }
            }

        }
    }

    function checkEmptyOrNull($value)
    {
        if (!isset($value) || empty($value)) {
            return true;
        }
        return false;
    }
}
