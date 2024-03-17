<?php

/**
 * json Autoloader
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

if (!function_exists('load_json')) {

  function load_json()
  {
    require_once(__DIR__ . DIRECTORY_SEPARATOR . 'etc' . DIRECTORY_SEPARATOR . 'config.php');

    // Checks the environment and if it is DEV, load devDependencies

    if (defined('ENV')) {
      if (ENV == 'DEV') {
        if (file_exists(JSON_LOCK_PATH)) {
          $lock = json_decode(file_get_contents(JSON_LOCK_PATH), true);

          foreach ($lock['devDependencies'] as $name => $path) {
            require_once($path . DIRECTORY_SEPARATOR . 'autoload.php');
          }
        } else {
          die('The package json has not been installed. Please run ./bin/install.php from the command line');
        }
      }
    }

    // Loads regular dependencies

    if (file_exists(JSON_LOCK_PATH)) {
      $lock = json_decode(file_get_contents(JSON_LOCK_PATH), true);

      foreach ($lock['dependencies'] as $name => $path) {
        require_once($path . DIRECTORY_SEPARATOR . 'autoload.php');
      }
    } else {
      die('The package json has not been installed. Please run ./bin/install.php from the command line');
    }


    // Make sure to load abstract base classes first
    require_once(JSON_SRC . 'abc.php');

    // Load the remaining src files

    $di = new DirectoryIterator(JSON_SRC);

    foreach ($di as $item) {
      $fn = $item->getFilename();

      if ($fn != 'abc.php' && substr($fn, 0, 1) != '.') {
        require_once(JSON_SRC . $fn);
      }
    }
  }

  load_json();
}
