<?php

/**
 * @file
 * This file bootstraps the app.
 */

namespace AKlump\AspectRatio;

use AKlump\LoftLib\Bash\Bash;
use AKlump\LoftLib\Bash\Color;
use AKlump\LoftLib\Storage\FilePath;

// Check for aspect_ratio installed as composer global package.
if (file_exists(__DIR__ . '/../../../autoload.php')) {
  require_once __DIR__ . '/../../../autoload.php';
}
else {
  require_once ROOT . '/vendor/autoload.php';
}

$cli = new Bash($argv);

// Display the version.
if ($cli->hasFlag('v')) {
  $data = FilePath::create(ROOT . '/composer.json')->load()->getJson();
  $credits = sprintf("Aspect Ratio by In the Loft Studios ~ Version %s", $data->version);
  echo Color::wrap('cyan', $credits) . PHP_EOL;
  exit(0);
}

$args = $cli->getArgs();
if (empty($args[1])) {
  print Color::wrap('red', 'Argument(s) must be width height in one of these formats: "WIDTH HEIGHT", "WIDTHxHEIGHT" or "WIDTH,HEIGHT"') . PHP_EOL;
  exit(1);
}

$width = $cli->getArg(1);
$height = $cli->getArg(2);
if (strpos($width, ':') !== FALSE) {
  list($width, $height) = explode(':', $width);
}
if (strpos($width, 'x') !== FALSE) {
  list($width, $height) = explode('x', $width);
}
if (strpos($width, ',') !== FALSE) {
  list($width, $height) = explode(',', $width);
}
if ($cli->hasFlag('i')) {
  list($width, $height) = [$height, $width];
}

$height = is_null($height) ? 1 : $height;

$aspect_ratio = new AspectRatio($width, $height, $cli->getParam('precision'), $cli->getParam('nearby'));
if ($cli->hasParam('width')) {
  $aspect_ratio->setTargetWidth($cli->getParam('width'));
}
