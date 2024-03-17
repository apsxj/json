<?php

/**
 * json Install Script
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

// Declare required arrays with required keys so we don't go down a rabbit hole checking for isset()

$package = array(
  'dependencies' => array(),
  'devDependencies' => array(),
  'directories' => array(),
  'config' => array(
    'prefix' => ''
  )
);

$lock = array(
  'dependencies' => array(),
  'devDependencies' => array(),
  'directories' => array()
);

$root_dir = dirname(__DIR__) . DIRECTORY_SEPARATOR;

$apsxj_prefix = dirname($root_dir, 3);

$lib_dir = $root_dir . 'lib' . DIRECTORY_SEPARATOR;

// Read package.json into $package

$package_path = $root_dir . 'package.json';

if (file_exists($package_path)) {
  $package = json_decode(file_get_contents($package_path), true);
} else {
  die('Installing a package without a package.json file is not currently supported.');
}

// If package.lock exists, read into $lock

$lock_path = $root_dir . 'package.lock';

if (file_exists($lock_path)) {
  $lock = json_decode(file_get_contents($lock_path), true);
}

// Install dependencies

foreach ($package['dependencies'] as $dep_key => $dep_value) {

  $local_path_exists = false;
  $error_message = false;

  // First check if the local path is already in our lock file
  if (isset($lock['dependencies'][$dep_key])) {
    $locked_path = $lock['dependencies'][$dep_key];

    // We have a path in the lock file, check if it exists
    if (file_exists($locked_path)) {
      // path to dependency exists, check if a symbolic link already is set in lib 
      $local_path_exists = true;
    } else {
      // @TODO: Enable user to provide a different path
      $error_message = 'Locked dependency at ' . $locked_path
        . ' does not exist. Please clone the repo to this path and try again.';
      // @TODO: Try to clone the repo to the locked path and checkout the specified branch
    }
  } else {
    // Not in package.lock (or no previous installation) we'll need to calculate the path
    $repoAndBranch = explode("#", $dep_value);

    $repo = $repoAndBranch[0];
    $userAndName = explode('/', $repo);

    $user = $userAndName[0];
    $name = $userAndName[1];
    $branch = $repoAndBranch[1];

    $locked_path = $apsxj_prefix . '/' . $user . '/' . $name . '/' . $branch;

    if (file_exists($locked_path)) {
      $local_path_exists = true;
    } else {
      // @TODO: Enable user to provide a different path. For now, require path structure to be consistent
      $error_message = "Dependency '"
        . $name
        . "' must be cloned. Please run\ngit clone git@github.com:"
        . $user . '/' . $name . '.git '
        . $locked_path
        . "\ncd " . $locked_path . "\ngit checkout " . $branch;
    }
  }

  if ($local_path_exists) {
    $link = $lib_dir . $dep_key;

    if (file_exists($link)) {
      exec('rm ' . $link);
    }

    // Make sure lib directory exists
    if (!file_exists($lib_dir)) {
      mkdir($lib_dir, 0777, true);
    }

    // Create or re-create the link
    exec('ln -s ' . $locked_path . ' ' . $link);

    $lock['dependencies'][$dep_key] = $locked_path;
  } else {
    die($error_message);
    // @TODO: Attempt to perform the clone/checkout operation
  }
}

// Install devDependencies

foreach ($package['devDependencies'] as $dep_key => $dep_value) {

  $local_path_exists = false;
  $error_message = false;

  // First check if the local path is already in our lock file
  if (isset($lock['devDependencies'][$dep_key])) {
    $locked_path = $lock['devDependencies'][$dep_key];

    // We have a path in the lock file, check if it exists
    if (file_exists($locked_path)) {
      // path to dependency exists, check if a symbolic link already is set in lib 
      $local_path_exists = true;
    } else {
      // @TODO: Enable user to provide a different path
      $error_message = 'Locked devDependency at ' . $locked_path
        . ' does not exist. Please clone the repo to this path and try again.';
      // @TODO: Try to clone the repo to the locked path and checkout the specified branch
    }
  } else {
    // Not in package.lock (or no previous installation) we'll need to calculate the path
    $repoAndBranch = explode("#", $dep_value);

    $repo = $repoAndBranch[0];
    $userAndName = explode('/', $repo);

    $user = $userAndName[0];
    $name = $userAndName[1];
    $branch = $repoAndBranch[1];

    $locked_path = $apsxj_prefix . '/' . $user . '/' . $name . '/' . $branch;

    if (file_exists($locked_path)) {
      $local_path_exists = true;
    } else {
      // @TODO: Enable user to provide a different path. For now, let's require path structure to be consistent
      $error_message = "devDependency '"
        . $name
        . "' must be cloned. Please run\ngit clone git@github.com:"
        . $user . '/' . $name . '.git '
        . $locked_path
        . "\ncd " . $locked_path . "\ngit checkout " . $branch;
    }
  }

  if ($local_path_exists) {
    $link = $lib_dir . $dep_key;

    if (file_exists($link)) {
      exec('rm ' . $link);
    }

    // Make sure lib directory exists
    if (!file_exists($lib_dir)) {
      mkdir($lib_dir, 0777, true);
    }

    // Create or re-create the link
    exec('ln -s ' . $locked_path . ' ' . $link);

    $lock['devDependencies'][$dep_key] = $locked_path;
  } else {
    die($error_message);
    // @TODO: Attempt to perform the clone/checkout operation
  }
}

// Iterate over directories.

foreach ($package['directories'] as $dir_key => $dir_value) {

  $absolute_path = str_replace('./', $root_dir, $dir_value);

  if (!file_exists($absolute_path)) {
    mkdir($absolute_path, 0777, true);
  }

  $const_name = strtoupper($dir_key);

  $code = "if (!defined('" . $package['config']['prefix'] . "_" . $const_name . "')) {
  define('" . $package['config']['prefix'] . "_" . $const_name . "', " . $package['config']['prefix'] . "_ROOT . '" . $dir_key . "' . DIRECTORY_SEPARATOR);
}";

  file_put_contents($root_dir . 'etc' . DIRECTORY_SEPARATOR . 'config.php', PHP_EOL . $code . PHP_EOL, FILE_APPEND);
}

// Write new package.lock

$json = json_encode($lock, JSON_UNESCAPED_SLASHES);

// If any of the nested objects are empty, php will incorrectly encode these as arrays instead of objects
$json = str_replace(
  [
    '"dependencies":[]',
    '"devDependencies":[]',
    '"directories":[]'
  ],
  [
    '"dependencies":{}',
    '"devDependencies":{}',
    '"directories":{}'
  ],
  $json
);

file_put_contents($root_dir . 'package.lock', $json);

$build_path = __DIR__ . DIRECTORY_SEPARATOR . 'build.php';

if (file_exists($build_path)) {
  exec('php ' . $build_path);
}

$test_path = __DIR__ . DIRECTORY_SEPARATOR . 'test.php';

if (file_exists($test_path)) {
  exec('php ' . $test_path);
}
