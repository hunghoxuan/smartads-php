<?php
/**
 * Braintree PHP Library
 * Creates class_aliases for old class names replaced by PSR-4 Namespaces
 */

namespace php\braintree\lib;

use Braintree_Exception;

//require_once(__DIR__ . DIRECTORY_SEPARATOR . 'autoload.php');

//if (version_compare(PHP_VERSION, '5.4.0', '<')) {
//    throw new Braintree_Exception('PHP version >= 5.4.0 required');
//}

class Braintree {

    const MODE = 'sandbox'; // sandbox / live
    const EXPRESS_CHECKOUT_ACCESS_TOKEN = 'access_token$sandbox$rw57r5bgxjf3sq9x$9afd13f2865ac6b0a6d73e513660b659';

    public function init() {

    }

    public static function requireDependencies() {
        $requiredExtensions = ['xmlwriter', 'openssl', 'dom', 'hash', 'curl'];
        foreach ($requiredExtensions AS $ext) {
            if (!extension_loaded($ext)) {
                throw new Braintree_Exception('The Braintree library requires the ' . $ext . ' extension.');
            }
        }
    }
}

Braintree::requireDependencies();
