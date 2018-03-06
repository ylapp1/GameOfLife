<?php
/**
 * @file
 * @version 0.1
 * @copyright 2018 CN-Consult GmbH
 * @author Yannick Lapp <yannick.lapp@cn-consult.eu>
 */

namespace GameOfLife;

use GameOfLife\OptionHandler\OptionHandler;
use Input\BaseInput;
use Output\BaseOutput;
use Ulrichsg\Getopt;

/**
 * Wrapper class for the GameOfLife simulation.
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
        $this->optionHandler->initializeOptions($this->options);
        $this->options->parse();

        $generalOptionUsed = $this->optionHandler->optionParser()->parseGeneralOptions($this->options);
        if ($generalOptionUsed) return false;

        $board = $this->optionHandler->optionParser()->parseBoardOptions($this->options);
        if (! $board) return false;
        else $this->board = $board;

        $this->input = $this->optionHandler->optionParser()->parseInputOptions($this->options);
        $this->output = $this->optionHandler->optionParser()->parseOutputOptions($this->options);

        $rule = $this->optionHandler->optionParser()->parseRuleOptions($this->options);
        $this->gameLogic = new GameLogic($rule);

        return true;
    }

    /**
     * Starts the game loop.
     */
    public function startSimulation()
    {
        $this->input->fillBoard($this->board, $this->options);
        $this->output->startOutput($this->options, $this->board);

        // Game loop
        while (! ($this->gameLogic->isMaxStepsReached($this->board) || $this->gameLogic->isLoopDetected() || $this->gameLogic->isBoardEmpty($this->board)))
        {
            $this->output->outputBoard($this->board);
            $this->gameLogic->calculateNextBoard($this->board);
        }

        $this->output->finishOutput();
    }
}
