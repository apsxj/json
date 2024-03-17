<?php

/**
 * json Configuration File
 *
 * @category   Utilities
 * @package    json
 * @link       https://apsxj.com
 * @author     Roderic Linguri <apsxj@mail.com>
 * @copyright  2023 APSXJ * All Rights Reserved
 * @license    http://www.apache.org/licenses/ Apache License
 * @version    0.1.1
 * @since      0.1.1
 */

// Defines the absolute path to this package's root directory
if (!defined('JSON_ROOT')) {
  define('JSON_ROOT', dirname(__DIR__) . DIRECTORY_SEPARATOR);
}

// Define the path to where the lock file should go or where it should be
if (!defined('JSON_LOCK_PATH')) {
  define('JSON_LOCK_PATH', JSON_ROOT . 'package.lock');
}

if (!defined('JSON_SRC')) {
  define('JSON_SRC', JSON_ROOT . 'src' . DIRECTORY_SEPARATOR);
}
