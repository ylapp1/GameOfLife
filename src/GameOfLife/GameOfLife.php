<?php
/**
 * @file
 * @version 0.1
 * @copyright 2018 CN-Consult GmbH
 * @author Yannick Lapp <yannick.lapp@cn-consult.eu>
 */

namespace GameOfLife;

use OptionHandler\OptionHandler;
use Input\BaseInput;
use Output\BaseOutput;
use Ulrichsg\Getopt;

/**
 * Wrapper class for the Game Of Life simulation.
 */
class GameOfLife
{
    /**
     * The option list
     *
     * @var Getopt $options
     */
    private $options;

    /**
     * The option handler
     *
     * @var OptionHandler The option handler
     */
    private $optionHandler;

    /**
     * The board that is used for the simulation.
     *
     * @var Board $board
     */
    private $board;

    /**
     * The input
     *
     * @var BaseInput $input
     */
    private $input;

    /**
     * The output
     *
     * @var BaseOutput $output
     */
    private $output;

    /**
     * The game logic
     *
     * @var GameLogic $gameLogic
     */
    private $gameLogic;


    /**
     * GameOfLife constructor.
     */
    public function __construct()
    {
        $this->options = new Getopt();
        $this->optionHandler = new OptionHandler();
    }


    /**
     * Parses the options and returns whether the simulation shall be started or not.
     *
     * @return Bool Indicates whether the simulation shall be started or not
     */
    public function initialize(): Bool
    {
        try
        {
            $this->optionHandler->initializeOptions($this->options);
        }
        catch (\Exception $_exception)
        {
            echo "\nError while initializing the options: " . $_exception->getMessage() . "\n\n";
            return false;
        }
        $this->options->parse();

        $generalOptionUsed = $this->optionHandler->optionParser()->parseGeneralOptions($this->options);
        if ($generalOptionUsed) return false;

        try
        {
            $this->board = $this->optionHandler->optionParser()->parseBoardOptions($this->options);
        }
        catch (\Exception $_exception)
        {
            echo "\nError while parsing the board options: " . $_exception->getMessage() . "\n\n";
            return false;
        }

        try
        {
            $this->input = $this->optionHandler->optionParser()->parseInputOptions($this->options);
        }
        catch (\Exception $_exception)
        {
            echo "\nError while parsing the input options: " . $_exception->getMessage() . "\n\n";
            return false;
        }

        try
        {
            $this->output = $this->optionHandler->optionParser()->parseOutputOptions($this->options);
        }
        catch (\Exception $_exception)
        {
            echo "\nError while parsing the output options: " . $_exception->getMessage() . "\n\n";
            return false;
        }

        try
        {
            $rule = $this->optionHandler->optionParser()->parseRuleOptions($this->options);
        }
        catch (\Exception $_exception)
        {
            echo "\nError while parsing the rule options: " . $_exception->getMessage() . "\n\n";
            return false;
        }

        try
        {
            $rule->initialize($this->options);
        }
        catch (\Exception $_exception)
        {
            echo "\nError while initializing the rule: " . $_exception->getMessage() . "\n\n";
            return false;
        }

        $this->gameLogic = new GameLogic($rule);

        return true;
    }

    /**
     * Starts the game loop.
     */
    public function startSimulation()
    {
        try
        {
            $this->input->fillBoard($this->board, $this->options);
        }
        catch(\Exception $_exception)
        {
            echo "\nError while filling the board: " . $_exception->getMessage() . "\n\n";
            return;
        }
        $this->output->startOutput($this->options, $this->board);

        // Game loop
        while (! ($this->gameLogic->isMaxStepsReached($this->board) || $this->gameLogic->isLoopDetected() || $this->gameLogic->isBoardEmpty($this->board)))
        {
            $this->output->outputBoard($this->board);
            $this->gameLogic->calculateNextBoard($this->board);
        }

        if ($this->gameLogic->isMaxStepsReached($this->board)) $simulationEndReason = "Max steps reached";
        elseif ($this->gameLogic->isLoopDetected()) $simulationEndReason = "Loop detected";
        elseif ($this->gameLogic->isBoardEmpty($this->board)) $simulationEndReason = "All cells are dead";
        else $simulationEndReason = "All cells are dead, a repeating pattern was detected or maxSteps was reached";

        try
        {
            $this->output->finishOutput($simulationEndReason);
        }
        catch (\Exception $_exception)
        {
            echo "\nError while finishing the simulation: " . $_exception->getMessage() ."\n\n";
        }
    }
}
