<?php
/**
 * @file
 * @version 0.1
 * @copyright 2017-2018 CN-Consult GmbH
 * @author Yannick Lapp <yannick.lapp@cn-consult.eu>
 */

namespace Output;

use BoardEditor\SelectionArea;
use GameOfLife\Board;
use GameOfLife\Coordinate;
use Output\BoardPrinter\BoardEditorOutputBoardPrinter;
use Ulrichsg\Getopt;

/**
 * Prints the BoardEditor to the console for UserInput.
 */
class BoardEditorOutput extends BaseOutput
{
	// Attributes

    /**
     * The board printer
     *
     * @var BoardEditorOutputBoardPrinter $boardPrinter
     */
    private $boardPrinter;


    // Magic Methods

	/**
     * BoardEditorOutput constructor.
     */
    public function __construct()
    {
        parent::__construct("BOARD EDITOR");
        $this->boardPrinter = new BoardEditorOutputBoardPrinter();
    }


    // Class Methods

    /**
     * Adds output specific options to the option list.
     *
     * @param Getopt $_options The option list
     */
    public function addOptions(Getopt $_options)
    {
    }

    /**
     * Initializes the output.
     *
     * @param Getopt $_options User inputted option list
     * @param Board $_board Initial board
     */
    public function startOutput(Getopt $_options, Board $_board)
    {
        parent::startOutput($_options, $_board);
    }

    /**
     * Prints the board to the console and optionally highlights a high light field or the selection area.
     *
     * @param Board $_board Current board
     * @param int $_gameStep The current game step
     * @param Coordinate $_highLightFieldCoordinate The coordinate of the cell that will be highglighted
     * @param SelectionArea $_selectionArea The selection area
     */
    public function outputBoard(Board $_board, int $_gameStep, Coordinate $_highLightFieldCoordinate = null, SelectionArea $_selectionArea = null)
    {
        $boardContentString = $this->boardPrinter->getBoardContentString($_board, $_highLightFieldCoordinate, $_selectionArea);
        $this->shellOutputHelper->printCenteredOutputString($boardContentString);
    }
}
