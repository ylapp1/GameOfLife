<?php
/**
 * @file
 * @version 0.1
 * @copyright 2018 CN-Consult GmbH
 * @author Yannick Lapp <yannick.lapp@cn-consult.eu>
 */

namespace BoardRenderer\Image;

use BoardRenderer\Base\BaseBorderGridBuilder;
use BoardRenderer\Image\Border\ImageBackgroundGridBorder;
use BoardRenderer\Image\Border\ImageBorder;
use BoardRenderer\Image\Utils\ImageColor;
use GameOfLife\Board;

/**
 * Fills and returns a border grid for ImageBoardRenderer classes.
 */
class ImageBorderGridBuilder extends BaseBorderGridBuilder
{
	// Magic Methods

	/**
	 * ImageBorderGridBuilder constructor.
	 *
	 * @param Board $_board The board for which the border will be rendered
	 * @param ImageBorder $_mainBorder The main border
	 * @param Bool $_hasBackgroundGrid If true the border grid will contain a background grid
	 */
	public function __construct(Board $_board, ImageBorder $_mainBorder, Bool $_hasBackgroundGrid)
	{
		$borderGrid = new ImageBorderGrid($_board, $this->getBorderColors($_mainBorder));
		parent::__construct($_mainBorder, $borderGrid, $_hasBackgroundGrid);
	}


	// Class Methods

	/**
	 * Adds a background grid to a border.
	 *
	 * @param ImageBorder $_parentBorder The parent border of the background grid
	 */
	protected function addBackgroundBorderGrid($_parentBorder)
	{
		$backgroundGridBorder = new ImageBackgroundGridBorder($_parentBorder, $_parentBorder->color());
		$_parentBorder->addInnerBorder($backgroundGridBorder);
	}

	/**
	 * Returns a list of all colors that are used in the main border and its inner borders.
	 *
	 * @param ImageBorder $_mainBorder The main border
	 *
	 * @return ImageColor[] The list of border colors
	 */
	private function getBorderColors(ImageBorder $_mainBorder): array
	{
		/** @var ImageColor[] $borderColors */
		$borderColors = array($_mainBorder->color());

		/** @var ImageBorder $innerBorder */
		foreach ($_mainBorder->getInnerBorders() as $innerBorder)
		{
			$colorExists = false;
			foreach ($borderColors as $borderColor)
			{
				if ($borderColor->equals($innerBorder->color()))
				{
					$colorExists = true;
					break;
				}
			}

			if (! $colorExists) $borderColors[] = $innerBorder->color();
		}

		return $borderColors;
	}
}
