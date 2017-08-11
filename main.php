<?php
/**
 * @file
 * @version 0.1
 * @copyright 2017 CN-Consult GmbH
 * @author Yannick Lapp <yannick.lapp@cn-consult.eu>
 */


require_once("Psr4Autoloader.php");


$loader = new Psr4Autoloader();
$loader->addNamespace("CN_Consult\\GameOfLife\\", __DIR__ . "/src/");
$loader->register();


use CN_Consult\GameOfLife\Classes\Board;



$rules = array(
    "Birth" => array (3),
    "Death" => array (0, 1, 4, 5, 6, 7, 8)
);


/*
echo "Geben Sie folgende Werte an, um die Simulation zu starten\n\n";
$sizeX = readline("Spielfeldbreite: ");
$sizeY = readline("Spielfeldhoehe: ");


$board = new Board($sizeX, $sizeY, false, $rules);
*/

$board = new Board(30, 15,false, $rules);
$board->createGliderBoard();

while ($board->checkBoardFinish())
{
    $board->printBoard();
    $board->calculateCells();

    usleep(10000);
}



echo "\n\n\nSimulation beendet: Alle Zellen sind tot oder das Spielfeld ändert sich nicht mehr.\n\n";