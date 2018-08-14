<?php
/**
 * @file
 * @version 0.1
 * @copyright 2017-2018 CN-Consult GmbH
 * @author Yannick Lapp <yannick.lapp@cn-consult.eu>
 */

$loader = require_once(__DIR__ . "/vendor/autoload.php");
$loader->addPsr4("BoardEditor\\", __DIR__ . "/src/GameOfLife/BoardEditor");
$loader->addPsr4("BoardRenderer\\", __DIR__ . "/src/GameOfLife/Outputs/BoardRenderer");
$loader->addPsr4("GameOfLife\\", __DIR__ . "/src/GameOfLife");
$loader->addPsr4("Input\\", __DIR__ . "/src/GameOfLife/Inputs");
$loader->addPsr4("OptionHandler\\", __DIR__ . "/src/GameOfLife/OptionHandler");
$loader->addPsr4("Output\\", __DIR__ . "/src/GameOfLife/Outputs");
$loader->addPsr4("Rule\\", __DIR__ . "/src/GameOfLife/Rules");
$loader->addPsr4("Simulator\\", __DIR__ . "/src/GameOfLife/Simulator");
$loader->addPsr4("TemplateHandler\\", __DIR__ . "/src/GameOfLife/TemplateHandler");
$loader->addPsr4("Utils\\", __DIR__ . "/src/GameOfLife/Utils");

use Simulator\Simulator;

$simulator = new Simulator();
$canSimulationBeStarted = $simulator->initialize();
if ($canSimulationBeStarted) $simulator->startSimulation();
