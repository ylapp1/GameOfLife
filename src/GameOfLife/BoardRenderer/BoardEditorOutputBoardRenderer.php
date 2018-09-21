<?php
/**
 * @file
 * @version 0.1
 * @copyright 2018 CN-Consult GmbH
 * @author Yannick Lapp <yannick.lapp@cn-consult.eu>
 */

namespace BoardRenderer;

use BoardRenderer\Base\Border\BaseBorder;
use BoardRenderer\Text\BoardEditorOutputBoardFieldRenderer;
use BoardRenderer\Text\Border\SelectionAreaBorder;
use BoardRenderer\Text\Border\TextBoardOuterBorder;
use BoardRenderer\Text\Border\TextHighLightFieldBorder;
use BoardRenderer\Text\TextBorderGridBuilder;
use BoardRenderer\Text\TextCanvas;
use GameOfLife\Board;
use GameOfLife\Coordinate;
use GameOfLife\Rectangle;

/**
 * BoardRenderer for the BoardEditorOutput.
 */
class BoardEditorOutputBoardRenderer extends BaseBoardRenderer
{
	// Attributes

	/**
	 * The board field renderer
	 *
	 * @var BoardEditorOutputBoardFieldRenderer $boardFieldRenderer
	 */
	protected $boardFieldRenderer;

	/**
	 * The initial border
	 *
	 * @var BaseBorder $initialBorder
	 */
	private $initialBorder;


    // Magic Methods

    /**
     * BoardEditorOutputBoardPrinter constructor.
     *
     * @param Board $_board The board to which this board printer belongs
     */
    public function __construct(Board $_board)
    {
    	$border = new TextBoardOuterBorder($_board);

	    parent::__construct(
		    $border,
		    new TextBorderGridBuilder($_board, $border, false),
		    new BoardEditorOutputBoardFieldRenderer("o", " ", "x", " "),
		    new TextCanvas(false)
	    );

	    $this->initialBorder = clone $this->border;
    }


    // Getters and Setters

	/**
	 * Returns the border of this board renderer.
	 *
	 * @return BaseBorder The border of this board renderer
	 */
	public function border()
	{
		return $this->border;
	}


    // Class Methods

	/**
	 * Returns the board output string for one board.
	 *
	 * @param Board $_board The board
	 * @param Coordinate $_highLightFieldCoordinate The coordinate of the currently highlighted field
	 * @param Rectangle $_selectionAreaRectangle The currently selected area rectangle
	 *
	 * @return String The board output string
	 */
	public function renderBoard(Board $_board, Coordinate $_highLightFieldCoordinate = null, Rectangle $_selectionAreaRectangle = null): String
	{
		$this->border->setInnerBorders($this->initialBorder->innerBorders());
		$this->boardFieldRenderer->reset();

		if ($_highLightFieldCoordinate)
		{
			$this->boardFieldRenderer->setHighLightFieldCoordinate($_highLightFieldCoordinate);

			$highLightFieldBorder = new TextHighLightFieldBorder($this->border, $_highLightFieldCoordinate);
			$this->border->addInnerBorder($highLightFieldBorder);
		}
		elseif ($_selectionAreaRectangle)
		{
			$selectionAreaBorder = new SelectionAreaBorder($this->border, $_selectionAreaRectangle);
			$this->border->addInnerBorder($selectionAreaBorder);
		}

		return parent::renderBoard($_board);
	}
}
