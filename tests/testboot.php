<?php
/**
 * @file
 * @version 0.1
 * @copyright 2017 CN-Consult GmbH
 * @author Yannick Lapp <yannick.lapp@cn-consult.eu>
 */

//require_once __DIR__ . '/../vendor/composer/autoload_real.php';
//$loader =  ComposerAutoloaderInitef2f69864c5695a57d28bbd4a98f5b03::getLoader();
$loader = require_once __DIR__ . "/../vendor/autoload.php";

$loader->addPsr4("GameOfLife\\", __DIR__ . "/../src/GameOfLife");
$loader->addPsr4("Input\\", __DIR__ . "/../src/GameOfLife/Inputs");
$loader->addPsr4("Output\\", __DIR__ . "/../src/GameOfLife/Outputs");
$loader->addPsr4("Ulrichsg\\", __DIR__ . "/../src/Ulrichsg");