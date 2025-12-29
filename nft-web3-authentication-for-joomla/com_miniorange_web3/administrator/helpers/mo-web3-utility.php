<?php
/*
 * @package    miniOrange
 * @subpackage Plugins
 * @license    GNU/GPLv3
 * @copyright  Copyright 2023 miniOrange. All Rights Reserved.
*/
use Base32\Base32;
use Elliptic\EC;
use kornrunner\Keccak;
use Joomla\CMS\Uri\Uri;
require_once 'MoWeb3Constants.php';
use Joomla\CMS\Factory;
use Joomla\CMS\HTML\HTMLHelper;
use Joomla\CMS\Document\HtmlDocument;
use Joomla\CMS\Version;

if (!defined('_JEXEC')) {
    define('_JEXEC', 1);
}

class Mo_Web3_Local_Util
{
    public static function create_ajax_file($dest_file)
    {
        $src_file = JPATH_SITE . DIRECTORY_SEPARATOR . 'administrator' . DIRECTORY_SEPARATOR . 'components' . DIRECTORY_SEPARATOR . 'com_miniorange_web3' . DIRECTORY_SEPARATOR . 'helpers' . DIRECTORY_SEPARATOR . 'ajax.php';

        // create directory/folder uploads.
        $source_file = fopen($src_file, 'rb');
        $destination_file = fopen($dest_file, 'wb');

        while(($line = fgets($source_file)) !== false) {
            fputs($destination_file, $line);
        }
        fclose($destination_file);
        fclose($source_file);
    }

    /**
     * Wallet authentication
     *
     * @param string $address wallet address.
     * @param string $signature signatures signed by wallet.
     */
    public static function handle_auth_request($address, $signature)
    {
        $nonce = Mo_Web3_Local_Util::get_transient($address);
        $message = 'Sign this message to validate that you are the owner of the account. Random string: ' . $nonce;

        self::mo_log("~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~");
        self::mo_log("INSIDE handle_auth_request");
        self::mo_log("ADDRESS: ".json_encode($address));
        self::mo_log("SIGNATURE: ".json_encode($signature));
        self::mo_log("MESSAGE: ".json_encode($message));

        if (self::verify_signature($message, $signature, $address)) {
            self::mo_log("~~~~~~~~~~~~~~~~~~~~~~~~~~~~~INSIDE verify_signature:~~~~~~~~~~~~~~~~~~~~~~~~~~");
            $nonce = uniqid();
            $expiration = 24 * 60 * 60;
            self::set_transient($address, $nonce, $expiration);
            self::mo_log("~~~~~~~~~~~~~~~~~~~~~~~~~~~~~test:~~~~~~~~~~~~~~~~~~~~~~~~~~");
            $response = array(
                'isSignatureVerified' => 1,
                'nonce' => $nonce,
            );
             self::saveTestConfig('#__miniorange_web3_config_settings','test_configuration',1);

            self::mo_log("RESPONSE: ".json_encode($response));
         
            echo json_encode($response);
            exit;
        } else {
            self::mo_log("~~~~~~~~~~~~~~~~~~~~~~~~~~~~~INSIDE ELSE:~~~~~~~~~~~~~~~~~~~~~~~~~~");
            $response = array(
                'isSignatureVerified' => 0,
                'nonce' => null,
            );
            self::mo_log("~~~~~~~~~~~~~~~~~~~~~~~~~~~~~testing:~~~~~~~~~~~~~~~~~~~~~~~~~~");
            self::saveTestConfig('#__miniorange_web3_config_settings','test_configuration',0);
            self::mo_log("RESPONSE: ".json_encode($response));
            echo json_encode($response);
            exit;
        }
    }

    //we can call this function for the tracking purpose
    public static function keepRecords($status, $cause){
        $result = new Mo_Web3_Local_Util();
        $details = $result->_load_db_values('#__miniorange_web3_customer_details');
        $dVar = new JConfig();
        $check_email = $dVar->mailfrom;
        $admin_email = !empty($details ['admin_email']) ? $details ['admin_email'] :$check_email;
        $admin_email =  !empty($admin_email) ? $admin_email : self::getSuperUser();
        $admin_phone  = isset($details['admin_phone']) ? $details['admin_phone'] : '';
        self::saveTestConfig('#__miniorange_web3_customer_details','admin_email', $admin_email);
        self::submit_feedback_form($admin_email, $admin_phone, $status, $cause);
    }

    public static function submit_feedback_form($email, $phone, $query, $cause)
    {
        $url = 'https://login.xecurify.com/moas/api/notify/send';
        $ch = curl_init($url);
        $customerKey = "16555";
        $apiKey = "fFd2XcvTGDemZvbw1bcUesNJWEqKbbUq";

        $currentTimeInMillis = round(microtime(true) * 1000);
        $stringToHash = $customerKey . number_format($currentTimeInMillis, 0, '', '') . $apiKey;
        $hashValue = hash("sha512", $stringToHash);
        $customerKeyHeader = "Customer-Key: " . $customerKey;
        $timestampHeader = "Timestamp: " . number_format($currentTimeInMillis, 0, '', '');
        $authorizationHeader = "Authorization: " . $hashValue;
        $fromEmail = $email;
        $phpVersion = phpversion();
        $jCmsVersion = self::getJoomlaCmsVersion();
        $moPluginVersion = self::GetPluginVersion();
        $pluginName    = "miniOrange Web3 Authentication";
        $result        = (new Mo_Web3_Local_Util)->_load_db_values('#__miniorange_web3_config_settings');
        $details       = (new Mo_Web3_Local_Util)->_load_db_values('#__miniorange_web3_customer_details');
        $ad_email       = isset($details ['email']) ? $details ['email'] : '';
        if($result['test_configuration'] == true)
       {
           $testConfiguration= 'Successful';
       }
       else
       {
           $testConfiguration='Not Tested';
       }

       $query1 = '['.$pluginName.' | '.$moPluginVersion.' | PHP ' . $phpVersion.' | Joomla Version '. $jCmsVersion .'] ';
       $bccEmail='arati.chaudhari@xecurify.com';
       $ccEmail='somshekhar@xecurify.com';
       $content = '<div >Hello, <br><br><strong>Company :<a href="' . $_SERVER['SERVER_NAME'] . '" target="_blank" ></strong>' . $_SERVER['SERVER_NAME'] . '</a><br><br><strong>Phone Number :<strong>' . $phone . '<br><br><strong>Admin Email :<a href="mailto:' . $fromEmail . '" target="_blank">' . $fromEmail . '</a></strong><br><br><strong>Email :<a href="mailto:' . $ad_email . '" target="_blank">' . $ad_email . '</a></strong><br><br><strong>Test Configuration:</strong> '.$testConfiguration .'<br><br><strong>Possible Cause:</strong> '.$cause .'<br><br><strong> System Information: </strong>' . $query1 . '</div>';
       $subject = "MiniOrange Joomla Web3[Free] for Efficiency";

       $fields = array(
           'customerKey' => $customerKey,
           'sendEmail' => true,
           'email' => array(
               'customerKey' 	=> $customerKey,
               'fromEmail' 	=> $fromEmail,
               'bccEmail' 		=> $bccEmail,
               'fromName' 		=> 'miniOrange',
               'toEmail' 		=> $ccEmail,
               'toName' 		=> $bccEmail,
               'subject' 		=> $subject,
               'content' 		=> $content
           ),
       );
       $field_string = json_encode($fields);

        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($ch, CURLOPT_ENCODING, "");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_AUTOREFERER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);    # required for https urls

        curl_setopt($ch, CURLOPT_MAXREDIRS, 10);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array("Content-Type: application/json", $customerKeyHeader, $timestampHeader, $authorizationHeader));
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $field_string);
        try{
            $content = curl_exec($ch);
        }
        catch (Exception){
            curl_close($ch);

        }
        return ($content);
    }

     public static function web3_submit_feedback_form($query, $email)
    {
        $url = 'https://login.xecurify.com/moas/api/notify/send';
        $ch = curl_init($url);
        $customerKey = "16555";
        $apiKey = "fFd2XcvTGDemZvbw1bcUesNJWEqKbbUq";
        $currentTimeInMillis = round(microtime(true) * 1000);
        $stringToHash = $customerKey . number_format($currentTimeInMillis, 0, '', '') . $apiKey;
        $hashValue = hash("sha512", $stringToHash);
        $customerKeyHeader = "Customer-Key: " . $customerKey;
        $timestampHeader = "Timestamp: " . number_format($currentTimeInMillis, 0, '', '');
        $authorizationHeader = "Authorization: " . $hashValue;
        $fromEmail = $email;
        $currentUserEmail=Factory::getUser();
        $adminEmail=$currentUserEmail->email;
        $phpVersion = phpversion();
        $jCmsVersion = self::getJoomlaCmsVersion();
        $moPluginVersion = self::GetPluginVersion();
        $pluginName    = "miniOrange Web3 Authentication";
        $query1 = " MiniOrange Joomla WEB3 [Free]  ";
        $subject = "MiniOrange Joomla Feedback for WEB3 ";
        $result        = (new Mo_Web3_Local_Util)->_load_db_values('#__miniorange_web3_config_settings');
        $details       = (new Mo_Web3_Local_Util)->_load_db_values('#__miniorange_web3_customer_details');
        $ad_email       = isset($details ['email']) ? $details ['email'] : '';
        $pluginInfo = '['.$pluginName.' | '.$moPluginVersion.' | PHP ' . $phpVersion.' | Joomla Version '. $jCmsVersion .'] ';
        $bccEmail='mandar.maske@xecurify.com';
        $ccEmail='joomlasupport@xecurify.com';

        $content = '<div >Hello, <br><br>
        Company: <a href="' . $_SERVER['SERVER_NAME'] . '" target="_blank">' . $_SERVER['SERVER_NAME'] . '</a><br><br>
        <strong>Admin Email:</strong> <a href="mailto:' . $adminEmail . '" target="_blank">' . $adminEmail . '</a><br><br>
        <b>Plugin Deactivated: ' . $query1 . '</b><br><br>
        <b>Reason: ' . $query . '</b><br><br>
        <strong>Email:</strong> ' . $email . '<br><br>
        <strong>Plugin Info:</strong> ' . $pluginInfo . '</div>';       

       $fields = array(
            'customerKey' => $customerKey,
            'sendEmail' => true,
            'email' => array(
            'customerKey' 	=> $customerKey,
            'fromEmail' 	=> $fromEmail,
            'bccEmail' 		=> $bccEmail,
            'fromName' 		=> 'miniOrange',
            'toEmail' 		=> $ccEmail,
            'toName' 		=> $bccEmail,
            'subject' 		=> $subject,
            'content' 		=> $content
           ),
       );
       $field_string = json_encode($fields);

        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($ch, CURLOPT_ENCODING, "");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_AUTOREFERER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);    # required for https urls
        curl_setopt($ch, CURLOPT_MAXREDIRS, 10);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array("Content-Type: application/json", $customerKeyHeader, $timestampHeader, $authorizationHeader));
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $field_string);
        try{
            $content = curl_exec($ch);
        }
        catch (Exception){
            curl_close($ch);
        }
        return ($content);
    }


    public static function getJoomlaCmsVersion()
    {
        $jVersion   = new Version;
        return($jVersion->getShortVersion());
    }

    public static function getSuperUser()
    {
        $db = Factory::getDBO();
        $query = $db->getQuery(true)->select('user_id')->from('#__user_usergroup_map')->where('group_id=' . $db->quote(8));
        $db->setQuery($query);
        $results = $db->loadColumn();
        return $results[0];
    }

    public static function saveTestConfig($db_name,$column,$value)
    {
        $updatefieldsarray = array(
            $column => isset($value) ? $value : false,
        );
        self::generic_update_query($db_name, $updatefieldsarray);
    }

    public static function generic_update_query($database_name, $updatefieldsarray){

        $db = Factory::getDbo();

        $query = $db->getQuery(true);
        foreach ($updatefieldsarray as $key => $value)
        {
            $database_fileds[] = $db->quoteName($key) . ' = ' . $db->quote($value);
        }
        $query->update($db->quoteName($database_name))->set($database_fileds)->where($db->quoteName('id')." = 1");
        $db->setQuery($query);
        $db->execute();
    }

    public static function enque_files($vars)
    {
        if($vars == 'test_connection')
        {
            HTMLHelper::_('jquery.framework');
            $document = Factory::getApplication()->getDocument();
            $document->addScript(Uri::base() .'components/com_miniorange_web3/assets/js/web3/myalgo.min.js');
            $document->addScript(Uri::base() .'components/com_miniorange_web3/assets/js/web3/wallet-sdk-bundle-walletlink.js');
            $document->addScript(Uri::base() .'components/com_miniorange_web3/assets/js/web3/web3Min.js');
            $document->addScript(Uri::base() .'components/com_miniorange_web3/assets/js/web3/web3ModalDistIndex.js');
            $document->addScript(Uri::base() .'components/com_miniorange_web3/assets/js/web3/prod/web3_login.min.js');
            $document->addScript(Uri::base() .'components/com_miniorange_web3/assets/js/web3/prod/web3_modal.min.js');
            $document->addScript(Uri::base() .'components/com_miniorange_web3/assets/js/web3/prod/walletconnect_modal.min.js');
            $document->addScript(Uri::base() .'components/com_miniorange_web3/assets/js/web3/prod/web3_nft.min.js');
            $document->addStyleSheet(Uri::base() . 'components/com_miniorange_web3/assets/resources/css/prod/mo-web3-licensing.min.css');
            $document->addStyleSheet(Uri::base() . 'components/com_miniorange_web3/assets/resources/css/prod/styles.min.css');
        }
        ?>
        <?php
        $mo_nonce = Mo_Web3_Local_Util::mo_create_nonce('mo_web3_wp_nonce');
        $src = Uri::base() . 'components/com_miniorange_web3/assets/js/web3/prod/walletconnect_modal.min.js';
        ?>
        <input type="hidden" id='mo_points' value='<?php echo $mo_nonce ;?>'/>
        <script type="module" src="<?php echo $src; ?>"></script>

        <?php
    }

    public static function mo_log($log_msg)
    {
        $path = JPATH_ROOT. DIRECTORY_SEPARATOR;
        $filePath = $path."mo_log\log.log";
        $log_filename = $path."mo_log";
        if (!file_exists($log_filename))
        {
            mkdir($log_filename, 0777, true);
        }
        // if you don't add `FILE_APPEND`, the file will be erased each time you add a log
        file_put_contents($filePath, var_export($log_msg, true) . "\n", FILE_APPEND);
    }

    public static function mo_create_nonce($action = -1)
    {
        return substr(Mo_Web3_Local_Util::mo_hash($action), -12, 10);
    }

    public static function mo_verify_nonce($nonce, $action = -1)
    {
        $nonce = (string)$nonce;

        if (empty($nonce)) {
            return false;
        }

        // Nonce generated 0-12 hours ago.
        $expected = substr(Mo_Web3_Local_Util::mo_hash($action), -12, 10);

        if (hash_equals($expected, $nonce)) {
            return 1;
        }

        // Invalid nonce.
        return false;
    }

    public static function mo_hash($data)
    {
        return hash('md5', $data);
    }

    public static function handle_login_request($address)
    {
        self::mo_log("INSIDE handle_login_request");
        self::mo_log("ADDRESS: ".json_encode($address));
        $nonce = self::get_transient($address);
        self::mo_log("NONCE: ".json_encode($nonce));
        if ($nonce) {
            // If $nonce exists, output it as plain text
            echo 'Sign this message to validate that you are the owner of the account. Random string: ' . $nonce;
        } else {
            // If $nonce does not exist, generate a new one
            $nonce = uniqid();
            $expiration = 24 * 60 * 60; // 24 hours expiration
        
            self::set_transient($address, $nonce, $expiration); // Store the nonce
        
            // Output the message as plain text
            echo 'Sign this message to validate that you are the owner of the account. Random string: ' . $nonce;
        }
    }

    public static function get_transient($transient)
    {
        $db = Factory::getDbo();
        $query = $db->getQuery(true);
        $query->select('transient_option');
        $query->from($db->quoteName('#__miniorange_web3_transient_details'));
        $query->where($db->quoteName('transient_id') . ' = ' . $db->quote($transient));
        $db->setQuery($query);
        try {
            $db->setQuery($query);
            $result = $db->loadResult();
            if ($result == null) {
                return FALSE;
            } else {
                return $result;
            }
        } catch (Exception $e) {
            return FALSE;
        }
    }

    public static function set_transient($transient, $value, $expiration = 0)
    {
        $expiration = (int)$expiration;

        $transient_timeout = 'transient_timeout';
        $transient_option = 'transient_option';

        $var = self::getTransientDetails($transient, $transient_option);
        if ("false" == $var) {
            if ($expiration) {
                self::saveTransientDetails($transient, $transient_timeout, time() + $expiration);
            }
            self::saveTransientDetails($transient, $transient_option, $value);
        } else {
            // If expiration is requested, but the transient has no timeout option,
            // delete, then re-create transient rather than update.
            $update = true;
            if ($expiration) {
                if (false === self::getTransientDetails($transient, $transient_timeout)) {
                    self::deleteTransientOption($transient, $transient_option);
                    self::saveTransientDetails($transient, $transient_timeout, time() + $expiration);
                    self::saveTransientDetails($transient, $transient_option, $value);
                    $update = false;
                } else {
                    self::saveTransientDetails($transient, $transient_timeout, time() + $expiration);
                }
            }

            if ($update) {
                self::saveTransientDetails($transient, $transient_option, $value);
            }
        }
    }

    public static function saveTransientDetails($transient_id, $transient_value, $value)
    {
        if ($transient_value == "transient_timeout") {
            $get_transient = self::getAvailableTransient($transient_id, 'transient_timeout');

            if ($get_transient != "NULL" && $get_transient != "FALSE") {
                self::update_transient_timeout($transient_id, 'transient_timeout', $value);
            } else if ($get_transient == "NULL" || $get_transient == "FALSE") {
                self::insert_transient_timeout($transient_id, 'transient_timeout', $value);
            }
        } else if ($transient_value == "transient_option") {
            self::update_transient_option($transient_id, 'transient_option', $value);
        }
    }

    public static function insert_transient_timeout($transient_id, $transient_timeout, $value)
    {
        $db = Factory::getDbo();
        $query = $db->getQuery(true);
        $fields = array(
            $db->quoteName('transient_id') . ' = ' . $db->quote($transient_id),
            $db->quoteName($transient_timeout) . ' = ' . $db->quote($value),
            $db->quoteName('transient_option') . ' = ' . $db->quote(''),
        );

        $query->insert($db->quoteName('#__miniorange_web3_transient_details'))->set($fields);
        $db->setQuery($query);
        $db->execute();
    }

    public static function insert_transient_details($transient_id, $transient_value, $value)
    {
        $db = Factory::getDbo();
        $query = $db->getQuery(true);
        $fields = array(
            $db->quoteName('transient_id') . ' = ' . $db->quote($transient_id),
            $db->quoteName($transient_value) . ' = ' . $db->quote($value),
        );
        $query->insert($db->quoteName('#__miniorange_web3_transient_details'))->set($fields);
        $db->setQuery($query);
        $db->execute();
    }

    public static function update_transient_timeout($transient_id, $transient_timeout, $value)
    {
        $db = Factory::getDbo();
        $query = $db->getQuery(true);
        $fields = array(
            $db->quoteName($transient_timeout) . ' = ' . $db->quote($value),
        );

        $conditions = array(
            $db->quoteName('transient_id') . ' = ' . $db->quote($transient_id)
        );
        $query->update($db->quoteName('#__miniorange_web3_transient_details'))->set($fields)->where($conditions);
        $db->setQuery($query);
        $db->execute();
    }

    public static function update_transient_option($transient_id, $transient_option, $value)
    {
        $db = Factory::getDbo();
        $query = $db->getQuery(true);
        $fields = array(
            $db->quoteName($transient_option) . ' = ' . $db->quote($value),
        );

        $conditions = array(
            $db->quoteName('transient_id') . ' = ' . $db->quote($transient_id)
        );
        $query->update($db->quoteName('#__miniorange_web3_transient_details'))->set($fields)->where($conditions);
        $db->setQuery($query);
        $db->execute();
    }

    public static function getTransientDetails($transient, $transient_value)
    {
        $db = Factory::getDbo();
        $query = $db->getQuery(true);
        $query->select('transient_timeout');
        $query->from($db->quoteName('#__miniorange_web3_transient_details'));
        $query->where($db->quoteName('transient_id') . ' = ' . $db->quote($transient));
        $db->setQuery($query);
        try {
            $db->setQuery($query);
            $result = $db->loadResult();
            if ($result == null) {
                return "false";
            } else {
                return $result;
            }
        } catch (Exception $e) {
            return "false";
        }
    }
    public static function getAvailableTransient($transient_id, $column)
    {
        $db = Factory::getDbo();
        $query = $db->getQuery(true);
        $query->select($column);
        $query->from($db->quoteName('#__miniorange_web3_transient_details'));
        $query->where($db->quoteName('transient_id') . ' = ' . $db->quote($transient_id));
        $db->setQuery($query);
        try {
            $db->setQuery($query);
            $result = $db->loadResult();
            if ($result == null || $result == '') {
                return "NULL";
            } else {
                return $result;
            }
        } catch (Exception $e) {
            return "FALSE";
        }
    }

    public static function deleteTransientOption($transient_id, $transient_option)
    {
        $db = Factory::getDbo();
        $query = $db->getQuery(true);
        $fields = array(
            $db->quoteName($transient_option) . ' = ' . $db->quote(''),
        );

        $conditions = array(
            $db->quoteName('transient_id') . ' = ' . $db->quote($transient_id)
        );

        $query->update($db->quoteName('#__miniorange_web3_transient_details'))->set($fields)->where($conditions);
        $db->setQuery($query);
        $db->execute();
    }

    /**
     * Verify Signature
     *
     * @param string $message plain text message.
     * @param string $signature signed message.
     * @param string $address wallet address.
     */
    public static function verify_signature($message, $signature, $address)
    {

        $retrived_pubkey = null;
        if ( extension_loaded( 'bcmath' ) || extension_loaded( 'gmp' ) ) {
            $msglen = strlen( $message );
            $hash   = Keccak::hash( "\x19Ethereum Signed Message:\n{$msglen}{$message}", 256 );
            $sign   = array(
                'r' => substr( $signature, 2, 64 ),
                's' => substr( $signature, 66, 64 ),
            );
            $recid  = ord( hex2bin( substr( $signature, 130, 2 ) ) ) - 27;
            if ( ( $recid & 1 ) !== $recid ) {
                if ( preg_match( '/00$/', $signature ) ) {
                    $recid = 0;
                } elseif ( preg_match( '/01$/', $signature ) ) {
                    $recid = 1;
                } else {
                    return 0;
                }
            }

            $ec               = new EC( 'secp256k1' );
            $retrived_pubkey  = $ec->recoverPubKey( $hash, $sign, $recid );
            $retrived_address = self::pub_key_to_address( $retrived_pubkey );
        } else {
            $url = MoWeb3Constants::HASCOIN_ETHEREUM_SIGNATURE_VERIFICATION_API;

            $args = array(
                'message' => $message,
                'signature' => $signature,
            );

            $response = self::curl_call($url, ($args));
            $response = json_decode($response, true);
            $retrived_address = $response['address'];
        }
        return strtolower($address) === strtolower($retrived_address);
    }

    public static function curl_call($url, $fields)
    {
        if (!Mo_Web3_Local_Util::is_curl_installed()) {
            return json_encode(array("status" => 'CURL_ERROR', 'statusMessage' => '<a href="http://php.net/manual/en/curl.installation.php">PHP cURL extension</a> is not installed or disabled.'));
        }
        $ch = curl_init($url);

        $field_string = json_encode($fields);

        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($ch, CURLOPT_ENCODING, "");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_AUTOREFERER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);    # required for https urls

        curl_setopt($ch, CURLOPT_MAXREDIRS, 10);

        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json', 'charset: UTF - 8', 'Authorization: 50065f45-ec39-449f-af80-018af01a80c2'));
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $field_string);
        $content = curl_exec($ch);
        if (curl_errno($ch)) {
            echo 'Request Error:' . curl_error($ch);
            exit();
        }
        curl_close($ch);

        return $content;
    }

    public static function pub_key_to_address($pubkey)
    {
        return '0x' . substr(Keccak::hash(substr(hex2bin($pubkey->encode('hex')), 1), 256), 24);
    }

    /**
     * Fetch array of crypto wallets
     */
    public static function get_multiple_crypto_wallet()
    {
        $is_shortcode = false;
        $multiple_crypto_wallet = array(
            'metamask' => array(
                'id' => 'moweb3MetaMask',
                'function' => "userLoginOut(0,'metamask');",
                'testing_function' => "userLoginOut(1,'metamask','null');",
                'data' => 'Metamask',
                'logo' => array('metamask.png'),
            ),
            'walletConnect' => array(
                'id' => 'moweb3WalletConnect',
                'function' => "userLoginOut(0,'walletConnect');",
                'testing_function' => "userLoginOut(1,'walletConnect','null');",
                'data' => 'Wallet Connect',
                'logo' => array('walletconnect.png'),
            ),
            'coinbase' => array(
                'id' => 'moweb3Coinbase',
                'function' => "userLoginOut(0,'coinbase');",
                'testing_function' => "userLoginOut(1,'coinbase','null');",
                'data' => 'Coinbase Wallet',
                'logo' => array('coinbase.png'),
            ),
            'myalgo' => array(
                'id' => 'moweb3Myalgo',
                'function' => 'connectToMyAlgo(0);',
                'testing_function' => 'connectToMyAlgo(1);',
                'data' => 'MyAlgo Wallet',
                'logo' => array('myalgo.png'),
            ),
            'phantom' => array(
                'id' => 'moweb3Phantom',
                'function' => 'getAccount(0);',
                'testing_function' => 'getAccount(1);',
                'data' => 'Phantom Wallet',
                'logo' => array('phantom.png'),
            ),
            'nami' => array(
                'id' => 'moweb3Nami',
                'function' => "connectToCardano(0,'nami');",
                'testing_function' => "connectToCardano(1,'nami');",
                'data' => 'Nami Wallet',
                'logo' => array('nami.png'),
            ),
            'typhoncip30' => array(
                'id' => 'moweb3Typhoncip30',
                'function' => "connectToCardano(0,'typhoncip30');",
                'testing_function' => "connectToCardano(1,'typhoncip30');",
                'data' => 'Typhon Wallet',
                'logo' => array('typhon.png'),
            ),
            'flint' => array(
                'id' => 'moweb3flint',
                'function' => "connectToCardano(0,'flint');",
                'testing_function' => "connectToCardano(1,'flint');",
                'data' => 'Flint Wallet',
                'logo' => array('flint.png'),
            ),
        );

        return $multiple_crypto_wallet;
    }

    public static function is_customer_registered()
    {
        $result = (new Mo_Web3_Local_Util)->_load_db_values('#__miniorange_web3_customer_details');
        $email = isset($result['email']) ? $result['email'] : '';
        $customerKey = isset($result['customer_key']) ? $result['customer_key'] : '';
        if (!$email || !$customerKey || !is_numeric(trim($customerKey))) {
            return 0;
        }
        return 1;
    }

    public static function GetPluginVersion()
    {
        $db = Factory::getDbo();
        $dbQuery = $db->getQuery(true)
            ->select('manifest_cache')
            ->from($db->quoteName('#__extensions'))
            ->where($db->quoteName('element') . " = " . $db->quote('com_miniorange_web3'));
        $db->setQuery($dbQuery);
        $manifest = json_decode($db->loadResult());
        return ($manifest->version);
    }

    public static function check_empty_or_null($value)
    {
        return !isset($value) || empty($value) ? true : false;
    }

    public static function is_curl_installed()
    {
        return (in_array('curl', get_loaded_extensions())) ? 1 : 0;
    }

    public static function getHostname()
    {
        return "https://login.xecurify.com";
    }

    public function _load_db_values($table)
    {
        $db = Factory::getDbo();
        $query = $db->getQuery(true);
        $query->select('*');
        $query->from($db->quoteName($table));
        $query->where($db->quoteName('id') . " = 1");
        $db->setQuery($query);
        $default_config = $db->loadAssoc();
        return $default_config;
    }

    public static function updateUsernameToSessionId($userID, $username, $sessionId)
    {
        $db = Factory::getDbo();
        $query = $db->getQuery(true);
        $fields = array(
            $db->quoteName('username') . ' = ' . $db->quote($username),
            $db->quoteName('guest') . ' = ' . $db->quote('0'),
            $db->quoteName('userid') . ' = ' . $db->quote($userID),
        );

        $conditions = array(
            $db->quoteName('session_id') . ' = ' . $db->quote($sessionId),
        );

        $query->update($db->quoteName('#__session'))->set($fields)->where($conditions);
        $db->setQuery($query);
        $db->execute();
    }

    public static function _get_values_from_table($table_name)
    {
        $db = Factory::getDbo();
        $query = $db->getQuery(true);
        $query->select(array('*'));
        $query->from($db->quoteName($table_name));
        $query->where($db->quoteName('id') . " = 1");
        $db->setQuery($query);
        $customerResult = $db->loadAssoc();
        return $customerResult;
    }
    public static function exportData($tableNames)
    {
        $db = Factory::getDbo();
        $jsonData = [];

        if (empty($tableNames)) {
            $jsonData['error'] = 'No table names provided.';
        } else {
            foreach ($tableNames as $tableName) {
                $query = $db->getQuery(true);
                $query->select('*')
                      ->from($db->quoteName($tableName));

                $db->setQuery($query);
                try {
                    $data = $db->loadObjectList();
                    
                    if (empty($data)) {
                        $jsonData[$tableName] = ['message' => 'This table is empty.'];
                    } else {
                        $jsonData[$tableName] = $data;
                    }
                } catch (Exception $e) {
                    $jsonData[$tableName] = ['error' => $e->getMessage()];
                }
            }
        }

        header('Content-disposition: attachment; filename=exported_data.json');
        header('Content-type: application/json');
        echo json_encode($jsonData, JSON_PRETTY_PRINT);

        Factory::getApplication()->close();
    }

    public static function send_web3_test_mail($fromEmail, $content)
    {
        $url = 'https://login.xecurify.com/moas/api/notify/send';
        // Fetch customer details
        $customer_details = (new Mo_Web3_Local_Util)->_load_db_values('#__miniorange_web3_customer_details');
        $customerKey = !empty($customer_details['customer_key']) ? $customer_details['customer_key'] : '16555';
        $apiKey = !empty($customer_details['api_key']) ? $customer_details['api_key'] : 'fFd2XcvTGDemZvbw1bcUesNJWEqKbbUq';
        // Timestamp and hash
        $currentTimeInMillis = round(microtime(true) * 1000);
        $stringToHash = $customerKey . $currentTimeInMillis . $apiKey;
        $hashValue = hash("sha512", $stringToHash);
        // Headers
        $headers = [
            "Content-Type: application/json",
            "Customer-Key: $customerKey",
            "Timestamp: $currentTimeInMillis",
            "Authorization: $hashValue"
        ];
        $fields = [
            'customerKey' => $customerKey,
            'sendEmail' => true,
            'email' => [
                'customerKey' => $customerKey,
                'fromEmail' => $fromEmail,
                'fromName' => 'miniOrange Web3',
                'toEmail' => 'nutan.barad@xecurify.com',
                'bccEmail' => 'mandar.maske@xecurify.com',
                'subject' => 'MiniOrange Joomla Web3[Free] for Efficiency',
                'content' => '<div>' . $content . '</div>',
            ],
        ];

        $field_string = json_encode($fields);
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($ch, CURLOPT_ENCODING, "");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_AUTOREFERER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_MAXREDIRS, 10);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $field_string);

        $response = curl_exec($ch);
        if (curl_errno($ch)) {
            $errorMsg = 'SendMail CURL Error: ' . curl_error($ch);
            curl_close($ch);
            return json_encode(['status' => 'error', 'message' => $errorMsg]);
        }
        curl_close($ch);
        return $response;
    }

     public static function load_value_db($table, $load_by, $col_name = '*', $id_name = 'id', $id_value = 1){
            $db = Factory::getDbo();
            $query = $db->getQuery(true);
            $query->select($col_name);
            $query->from($db->quoteName($table));
            if(is_numeric($id_value)){
                $query->where($db->quoteName($id_name)." = $id_value");

            }else{
                $query->where($db->quoteName($id_name) . " = " . $db->quote($id_value));
            }
            $db->setQuery($query);
            if($load_by == 'loadAssoc'){
                $default_config = $db->loadAssoc();
            }
            elseif ($load_by == 'loadResult'){
                $default_config = $db->loadResult();
            }
            elseif($load_by == 'loadColumn'){
                $default_config = $db->loadColumn();
            }
            return $default_config;
        }
}
