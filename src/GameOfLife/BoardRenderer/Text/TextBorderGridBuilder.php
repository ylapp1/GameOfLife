<?php
/**
 * @file
 * @version 0.1
 * @copyright 2018 CN-Consult GmbH
 * @author Yannick Lapp <yannick.lapp@cn-consult.eu>
 */

namespace BoardRenderer\Text;

use BoardRenderer\Base\BaseBorderGridBuilder;
use BoardRenderer\Base\Border\BaseBorder;
use BoardRenderer\Text\Border\TextBackgroundGridBorder;
use GameOfLife\Board;

/**
 * Fills and returns a border grid for TextBoardRenderer classes.
 */
class TextBorderGridBuilder extends BaseBorderGridBuilder
{
	// Magic Methods

	/**
	 * TextBorderGridBuilder constructor.
	 *
	 * @param Board $_board The board for which this BorderGridBuilder will be used
	 * @param BaseBorder $_mainBorder The main border
	 * @param Bool $_hasBackgroundGrid If true the border grid will contain a background grid
	 */
	public function __construct(Board $_board, BaseBorder $_mainBorder, Bool $_hasBackgroundGrid = false)
	{
		$borderGrid = new TextBorderGrid($_board);
		parent::__construct($_mainBorder, $borderGrid, $_hasBackgroundGrid);
	}


	// Class Methods

	/**
	 * Adds a background grid to a border.
	 *
	 * @param BaseBorder $_parentBorder The parent border of the background grid
	 */
	protected function addBackgroundBorderGrid($_parentBorder)
	{
		$backgroundGridBorder = new TextBackgroundGridBorder($_parentBorder);
		$_parentBorder->addInnerBorder($backgroundGridBorder);
	}
}