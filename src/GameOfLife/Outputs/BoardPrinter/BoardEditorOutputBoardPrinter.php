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
use Output\BoardPrinter\OutputBoard\BorderPartBuilder\InnerBorderPartBuilder\HighLightFieldBorderPartBuilder;
use Output\BoardPrinter\OutputBoard\BorderPartBuilder\InnerBorderPartBuilder\SelectionAreaBorderPartBuilder;
use Output\BoardPrinter\OutputBoard\BorderPartBuilder\OuterBorderPartBuilder\BoardOuterBorderPartBuilder;

/**
 * BoardPrinter for the BoardEditorOutput.
 */
class BoardEditorOutputBoardPrinter extends BaseBoardPrinter
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
	 * @var HighLightFieldBorderPartBuilder $highLightFieldBorderPrinter
	 */
	private $highLightFieldBorderPrinter;

	/**
	 * The selection area border printer
	 *
	 * @var SelectionAreaBorderPartBuilder $selectionAreaBorderPrinter
	 */
	private $selectionAreaBorderPrinter;


    // Magic Methods

    /**
     * BoardEditorOutputBoardPrinter constructor.
     *
     * @param Board $_board The board to which this board printer belongs
     */
    public function __construct(Board $_board)
    {
        parent::__construct("o", " ", new BoardOuterBorderPartBuilder($_board));
        $this->highLightFieldBorderPrinter = new HighLightFieldBorderPartBuilder();
        $this->selectionAreaBorderPrinter = new SelectionAreaBorderPartBuilder();
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
        $this->highLightFieldCoordinate = $_highLightFieldCoordinate;
        $this->selectionArea = $_selectionArea;
        $this->border->resetInnerBorders();

        if ($_highLightFieldCoordinate)
        {
            $this->highLightFieldBorderPrinter->initialize($_board, $this->highLightFieldCoordinate);
            $this->border->addInnerBorder($this->highLightFieldBorderPrinter);
        }
        elseif ($_selectionArea)
        {
            $this->selectionAreaBorderPrinter->initialize($_board, $this->selectionArea);
            $this->border->addInnerBorder($this->selectionAreaBorderPrinter);
        }

	    return parent::getBoardContentString($_board);
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
