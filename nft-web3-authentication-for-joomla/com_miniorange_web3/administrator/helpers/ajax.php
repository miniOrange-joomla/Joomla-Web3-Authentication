<?php
define('_JEXEC', 1);
define('JPATH_BASE', dirname(__FILE__));
require_once JPATH_BASE.'/includes/defines.php';
require_once JPATH_BASE.'/includes/framework.php';

require_once JPATH_BASE . '/administrator/components/com_miniorange_web3/helpers/mo-web3-utility.php';
require_once JPATH_BASE . '/administrator/components/com_miniorange_web3/web3/lib/Keccak/Keccak.php';
require_once JPATH_BASE . '/administrator/components/com_miniorange_web3/web3/lib/Elliptic/EC.php';
require_once JPATH_BASE . '/administrator/components/com_miniorange_web3/web3/lib/Elliptic/Curves.php';
require_once JPATH_BASE . '/administrator/components/com_miniorange_web3/web3/lib/Base32/class-base32.php';

$request = $_REQUEST;

$request_type = isset($request['action']) ? $request['action'] : 'NOTHING';

if($request_type == 'type_of_request'){
    type_of_request($request);
}

/**
 * Handles form post
 */
function type_of_request($request)
{
    if ( isset( $request['mo_web3_verify_nonce'] ) && Mo_Web3_Local_Util::mo_verify_nonce(stripslashes($request['mo_web3_verify_nonce']), 'mo_web3_wp_nonce' ))
    {

        Mo_Web3_Local_Util::mo_log("request_type: ".json_encode($request));
        $request_type   = isset($request['request']) ? $request['request'] : '';
        if ($request_type) {
            $address   = isset($request['address']) ? $request['address'] : '';
            $signature = isset($request['signature']) ? $request['signature'] : '';

            switch ( $request_type ) {
                case 'login':
                    Mo_Web3_Local_Util::handle_login_request($address);
                    break;
                case 'auth':
                    Mo_Web3_Local_Util::handle_auth_request($address, $signature);
                    break;            
            }
        }
    }
}
