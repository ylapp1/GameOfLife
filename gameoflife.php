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
$loader->addNamespace("Ulrichsg", __DIR__ . "/src/Ulrichsg/");
$loader->register();


use CN_Consult\GameOfLife\Classes\Board;
use CN_Consult\GameOfLife\Classes\RuleSet;
use Ulrichsg\Getopt;



// Create command line options
$options = new Getopt(
    array(
        // board options
        array(null, "width", Getopt::REQUIRED_ARGUMENT, "Set the board width (Default: 20)"),
        array(null, "height", Getopt::REQUIRED_ARGUMENT, "Set the board height (Default: 10)"),
        array(null, "maxSteps", Getopt::REQUIRED_ARGUMENT, "Set the maximum amount of steps that are calculated before the simulation stops (Default: 50)"),
        array(null, "border", Getopt::REQUIRED_ARGUMENT, "Set the border type (solid|passthrough) (Default: solid)"),

        // start of board options
        array(null, "startRandom", Getopt::NO_ARGUMENT, "Fill the board with random cells and start the simulation"),
        array(null, "startGlider", Getopt::NO_ARGUMENT, "Place one glider on the board and start the simulation"),
        array(null, "startBlinker", Getopt::NO_ARGUMENT, "Place one blinker on the board and start the simulation"),

        // other
        array(null, "version", Getopt::NO_ARGUMENT, "Print script version"),
        array("h", "help", Getopt::NO_ARGUMENT)
    )
);


// parse options
$options->parse();



if ($options->getOption("version"))
{
    // Show game version
    echo "Game of life version 0.1\n";
    return;
}
elseif ($options->getOption("help"))
{
    // Show help screen
    echo "\n";
    $options->showHelp();
    return;
}
else
{
    // Start the simulation
    $width = $options->getOption("width");
    $height = $options->getOption("height");
    $maxSteps = $options->getOption("maxSteps");
    $border = $options->getOption("border");


    if ($width == null) $width = 20;
    if ($height == null) $height = 10;
    if ($maxSteps == null) $maxSteps = 50;


    $hasBorder = true;
    if ($border == null or $border == "solid") $hasBorder = true;
    elseif ($border == "passthrough") $hasBorder = false;
    else
    {
        echo "Error: Invalid border type specified";
        return;
    }


    $rulesConway = new RuleSet(array(3), array(0, 1, 4, 5, 6, 7, 8));

    // initialize new board
    $board = new Board($width, $height, $maxSteps, $hasBorder, $rulesConway);



    if ($options->getOption("startRandom"))
    {
        $board->initializeRandomBoard();
    }
    elseif ($options->getOption("startGlider"))
    {
        $board->initializeGliderBoard();
    }
    elseif ($options->getOption("startBlinker"))
    {
        $board->initializeBlinkBoard();
    }
    else
    {
        echo "Error: You have to specify which kind of board you want to initialize.\n";
        return;
    }



    $curStep = 0;

    while ($board->isFinished($curStep) != true)
    {
        $curStep++;

        echo "\n\nGame Step: " . $curStep;

        $board->printBoard();
        $board->calculateStep();

        usleep(10000);
    }
}