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
use GameOfLife\Field;
use Output\BorderPrinter\HighLightFieldBorderPrinter;
use Output\BorderPrinter\SelectionAreaBorderPrinter;
use Ulrichsg\Getopt;

/**
 * Prints the BoardEditor to the console for UserInput.
 */
class BoardEditorOutput extends ConsoleOutput
{
	// Attributes

    /**
     * The symbol that is used to print a living cell
     *
     * @var String $cellAliveSymbol
     */
    protected $cellAliveSymbol = "o";

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
     *
     * @var HighLightFieldBorderPrinter|SelectionAreaBorderPrinter $activeBorderPrinter
     */
    private $activeBorderPrinter;



    // Magic Methods

	/**
     * BoardEditorOutput constructor.
     */
    public function __construct()
    {
        parent::__construct();
        $this->outputTitle = "BOARD EDITOR";
        $this->highLightFieldBorderPrinter = new HighLightFieldBorderPrinter();
        $this->selectionAreaBorderPrinter = new SelectionAreaBorderPrinter();
    }


    // Class Methods

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
        $this->activeBorderPrinter = null;
        $this->highLightFieldCoordinate = null;
        $this->selectionArea = null;

        if ($_highLightFieldCoordinate)
        {
            $this->highLightFieldCoordinate = $_highLightFieldCoordinate;
            $this->highLightFieldBorderPrinter->initialize($_board, $this->highLightFieldCoordinate);
            $this->activeBorderPrinter = $this->highLightFieldBorderPrinter;
        }
    	elseif ($_selectionArea)
	    {
		    $this->selectionArea = $_selectionArea;
		    $this->selectionAreaBorderPrinter->initialize($_board, $this->selectionArea);
		    $this->activeBorderPrinter = $this->selectionAreaBorderPrinter;
	    }

        $this->shellOutputHelper->printCenteredOutputString($this->getBoardContentString($_board));
    }


    // Overridden hooks

	/**
	 * Returns the string for the top border.
	 *
	 * @param Board $_board The board
	 *
	 * @return String The string for the top border
	 */
    protected function getBorderTopString($_board): String
    {
        $topBorderString = parent::getBorderTopString($_board);
	    if ($this->activeBorderPrinter)
        {
            $this->activeBorderPrinter->addCollisionBorderToTopOuterBorder($topBorderString);
	    }

	    return $topBorderString;
    }

	/**
	 * Returns the string for the bottom border.
	 *
	 * @param Board $_board The board
	 *
	 * @return String The string for the bottom border
	 */
	protected function getBorderBottomString($_board): String
	{
		$bottomBorderString = parent::getBorderTopString($_board);
		if ($this->activeBorderPrinter)
		{
		    $this->activeBorderPrinter->addCollisionBorderToBottomOuterBorder($bottomBorderString);
		}

		return $bottomBorderString;
	}

	/**
	 * Returns the output string for the cells of a single row.
	 *
	 * @param Field[] $_fields The fields of the row
     * @param int $_y The Y-Coordinate of the row
     *
	 * @return String Row output String
	 */
	protected function getRowOutputString (array $_fields, int $_y): String
	{
		$rowOutputString = parent::getRowOutputString($_fields, $_y);
        if ($this->highLightFieldCoordinate && $_y == $this->highLightFieldCoordinate->y())
        {
            $rowOutputString .= " " . $_y;
        }
        $rowOutputString .= "\n";

        if ($this->activeBorderPrinter)
		{
		    $rowOutputString = $this->activeBorderPrinter->addBordersToRowString($rowOutputString, $_y);
		}

		return $rowOutputString;
	}

    /**
     * Returns the symbol for a cell.
     *
     * @param Field $_field
     *
     * @return String The symbol for the cell
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
