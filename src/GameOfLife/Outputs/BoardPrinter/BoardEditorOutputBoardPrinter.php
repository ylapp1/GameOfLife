<?php
/**
 * @file
 * @version 0.1
 * @copyright 2018 CN-Consult GmbH
 * @author Yannick Lapp <yannick.lapp@cn-consult.eu>
 */

namespace Output\BoardPrinter;

use BoardEditor\SelectionArea;
use GameOfLife\Board;
use GameOfLife\Coordinate;
use GameOfLife\Field;
use Output\BoardPrinter\BorderPrinter\HighLightFieldBorderPrinter;
use Output\BoardPrinter\BorderPrinter\SelectionAreaBorderPrinter;

/**
 * BoardPrinter for the BoardEditorOutput.
 */
class BoardEditorOutputBoardPrinter extends ConsoleOutputBoardPrinter
{
	// Attributes

    /**
     * The coordinate of the currently highlighted field
     *
     * @var Coordinate $highLightFieldCoordinate
     */
    private $highLightFieldCoordinate;

    /**
     * The currently selected area
     *
     * @var SelectionArea $selectionArea
     */
    private $selectionArea;

	/**
	 * The high light field border printer
	 *
	 * @var HighLightFieldBorderPrinter $highLightFieldBorderPrinter
	 */
	private $highLightFieldBorderPrinter;

	/**
	 * The selection area border printer
	 *
	 * @var SelectionAreaBorderPrinter $selectionAreaBorderPrinter
	 */
	private $selectionAreaBorderPrinter;

    /**
     * The reference to the currently active border printer
     * There can only be one active inner border printer at a time.
     * The priority order of the border printers is:
     * 1. High light field
     * 2. Selection area
     *
     * @var HighLightFieldBorderPrinter|SelectionAreaBorderPrinter $activeInnerBorderPrinter
     */
    private $activeInnerBorderPrinter;


    // Magic Methods

    /**
     * BoardEditorOutputBoardPrinter constructor.
     */
    public function __construct()
    {
        parent::__construct();
        $this->highLightFieldBorderPrinter = new HighLightFieldBorderPrinter();
        $this->selectionAreaBorderPrinter = new SelectionAreaBorderPrinter();
        $this->cellAliveSymbol = "o";
    }


    // Class Methods

	/**
	 * Returns the board output string for one board.
	 *
	 * @param Board $_board The board
	 * @param Coordinate $_highLightFieldCoordinate The coordinate of the currently highlighted field
	 * @param SelectionArea $_selectionArea The currently selected area
	 *
	 * @return String The board output string
	 */
    public function getBoardContentString(Board $_board, Coordinate $_highLightFieldCoordinate = null, SelectionArea $_selectionArea = null): String
    {
        $this->activeInnerBorderPrinter = null;
        $this->highLightFieldCoordinate = $_highLightFieldCoordinate;
        $this->selectionArea = $_selectionArea;

        if ($_highLightFieldCoordinate)
        {
            $this->highLightFieldBorderPrinter->initialize($_board, $this->highLightFieldCoordinate);
            $this->activeInnerBorderPrinter = $this->highLightFieldBorderPrinter;
        }
        elseif ($_selectionArea)
        {
            $this->selectionAreaBorderPrinter->initialize($_board, $this->selectionArea);
            $this->activeInnerBorderPrinter = $this->selectionAreaBorderPrinter;
        }

        return parent::getBoardContentString($_board);
    }

	/**
	 * Returns the string for the top border.
	 *
	 * @param Board $_board The board
	 *
	 * @return String The string for the top border
	 */
	protected function getBorderTopString(Board $_board): String
    {
        $borderTopString = parent::getBorderTopString($_board);
        if ($this->activeInnerBorderPrinter)
        {
        	$borderTopString = $this->activeInnerBorderPrinter->addCollisionBorderToTopOuterBorder($borderTopString);
        }

        return $borderTopString;
    }

	/**
	 * Returns the string for the bottom border.
	 *
	 * @param Board $_board The board
	 *
	 * @return String The string for the bottom border
	 */
	protected function getBorderBottomString(Board $_board): String
    {
        $borderBottomString = parent::getBorderBottomString($_board);
        if ($this->activeInnerBorderPrinter)
        {
        	$borderBottomString = $this->activeInnerBorderPrinter->addCollisionBorderToBottomOuterBorder($borderBottomString);
        }

        return $borderBottomString;
    }

	/**
	 * Returns the output string for the cells of a single row.
	 *
	 * @param Field[] $_fields The fields of the row
	 * @param int $_y The Y-Coordinate of the row
	 *
	 * @return String The output string for the cells of the row
	 */
    protected function getRowOutputString (array $_fields, int $_y): String
    {
        $rowOutputString = parent::getRowOutputString($_fields, $_y);
        if ($this->activeInnerBorderPrinter)
        {
            $rowOutputString = $this->activeInnerBorderPrinter->addBordersToRowString($rowOutputString, $_y);
        }

        return $rowOutputString;
    }

	/**
	 * Returns the symbol for a cell in a field.
	 *
	 * @param Field $_field The field
	 *
	 * @return String The symbol for the cell in the field
	 */
    protected function getCellSymbol(Field $_field): String
    {
        $cellSymbol = parent::getCellSymbol($_field);

        if ($this->highLightFieldCoordinate)
        {
            if ($_field->coordinate()->y() == $this->highLightFieldCoordinate->y() &&
                $_field->coordinate()->x() == $this->highLightFieldCoordinate->x())
            {
                if ($_field->isAlive()) $cellSymbol = "X";
            }
        }

        return $cellSymbol;
    }
}
