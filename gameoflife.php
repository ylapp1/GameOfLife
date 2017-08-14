<?php
/**
 * @file
 * @version 0.1
 * @copyright 2017 CN-Consult GmbH
 * @author Yannick Lapp <yannick.lapp@cn-consult.eu>
 */


require_once("Psr4Autoloader.php");

$loader = new Psr4Autoloader();
$loader->addNamespace("GameOfLife\\", __DIR__ . "/src/Classes/");
$loader->addNamespace("Ulrichsg", __DIR__ . "/src/Ulrichsg/");
$loader->addnameSpace("Input", __DIR__ . "/src/Classes/Inputs");
$loader->register();


use GameOfLife\Board;
use GameOfLife\RuleSet;
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
        array(null, "input", Getopt::REQUIRED_ARGUMENT, "Fill the board with cells"),

        // other
        array(null, "version", Getopt::NO_ARGUMENT, "Print script version"),
        array("h", "help", Getopt::NO_ARGUMENT)
    )
);



// save input names for error message
$inputNames = array();


// find every input class
foreach (glob(__DIR__ . "/src/Classes/Inputs/*Input.php") as $inputClass)
{
    // initialize each class
    $inputNames[] = basename($inputClass, ".php");
    $className = "Input\\" . basename($inputClass, ".php");


    $input = new $className;
    $input->addOptions($options);
}



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
elseif ($options->getOption("input"))
{
    // Fetch game options
    $width = $options->getOption("width");
    $height = $options->getOption("height");
    $maxSteps = $options->getOption("maxSteps");
    $border = $options->getOption("border");


    if ($width == null) $width = 20;
    if ($height == null) $height = 10;
    if ($maxSteps == null) $maxSteps = 50;


    if ($border == null or $border == "solid") $hasBorder = true;
    elseif ($border == "passthrough") $hasBorder = false;
    else
    {
        echo "Error: Invalid border type specified";
        return;
    }


    // define rules for conways game of life
    $rulesConway = new RuleSet(array(3), array(0, 1, 4, 5, 6, 7, 8));

    // initialize new board
    $board = new Board($width, $height, $maxSteps, $hasBorder, $rulesConway);


    // try to load specified input
    $className = "Input\\". $options->getOption("input") . "Input";

    if (class_exists($className) == false)
    {
        echo "Error: Invalid input type specified.\n";
        echo "Allowed options are:\n\n";

        foreach ($inputNames as $inputname)
        {
            echo $inputname . "\n";
        }
        return;
    }
    else
    {
        $input = new $className;

        $input->fillBoard($board, $options);


        // Game loop
        $curStep = 0;

        while ($board->isFinished($curStep) == false)
        {
            $curStep++;

            echo "\n\nGame Step: " . $curStep;

            $board->printBoard();
            $board->calculateStep();

            usleep(10000);
        }
    }
}