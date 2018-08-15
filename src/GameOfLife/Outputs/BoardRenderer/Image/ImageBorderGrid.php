<?php
/**
 * @file
 * @version 0.1
 * @copyright 2018 CN-Consult GmbH
 * @author Yannick Lapp <yannick.lapp@cn-consult.eu>
 */

namespace BoardRenderer\Image;

use BoardRenderer\Base\BaseBorderGrid;
use BoardRenderer\Base\Border\BaseBorder;
use BoardRenderer\Base\Border\BorderPart\BaseBorderPart;
use BoardRenderer\Base\Border\BorderPart\BorderPartThickness;
use BoardRenderer\Image\Border\BorderPart\ImageBorderPart;
use BoardRenderer\Image\Border\BorderPart\Shapes\ImageHorizontalBorderPartShape;
use BoardRenderer\Image\Border\BorderPart\Shapes\ImageVerticalBorderPartShape;
use BoardRenderer\Image\Border\ImageBorder;
use GameOfLife\Board;
use GameOfLife\Coordinate;
use Output\Helpers\ImageColor;

/**
 * Rendered border grid for the image board renderer.
 */
class ImageBorderGrid extends BaseBorderGrid
{
	private $board;
	private $fieldSize;
	private $backgroundColor;

	public function __construct(Board $_board, BaseBorder $_border, int $_fieldSize, ImageColor $_backgroundColor)
	{
		parent::__construct($_board, $_border);
		$this->board = $_board;
		$this->fieldSize = $_fieldSize;
		$this->backgroundColor = $_backgroundColor;
	}

	/**
	 * Returns a horizontal border part for the background grid.
	 *
	 * @param Coordinate $_startsAt The start position
	 * @param Coordinate $_endsAt The end position
	 * @param ImageBorder $_parentBorder The main border
	 *
	 * @return BaseBorderPart The horizontal border part
	 */
	protected function getHorizontalBackgroundGridBorderPart(Coordinate $_startsAt, Coordinate $_endsAt, $_parentBorder)
	{
		return new ImageBorderPart(
			$_parentBorder,
			$_startsAt,
			$_endsAt,
			new ImageHorizontalBorderPartShape(),
			new BorderPartThickness(1, 1)
		);
	}

	/**
	 * Returns a vertical border part for the background grid.
	 *
	 * @param Coordinate $_startsAt The start position
	 * @param Coordinate $_endsAt The end position
	 * @param ImageBorder $_parentBorder The main border
	 *
	 * @return BaseBorderPart The vertical border part
	 */
	protected function getVerticalBackgroundGridBorderPart(Coordinate $_startsAt, Coordinate $_endsAt, $_parentBorder)
	{
		return new ImageBorderPart(
			$_parentBorder,
			$_startsAt,
			$_endsAt,
			new ImageVerticalBorderPartShape(),
			new BorderPartThickness(1, 1)
		);
	}

	/**
	 * Creates and returns the rendered border grid.
	 *
	 * @return resource The rendered border grid
	 */
	public function renderBorderGrid()
	{
		$this->renderBorderParts();

		// Create the background image
		$image = $this->initializeImage();

		// Render the border parts
		foreach ($this->renderedBorderParts as $renderedBorderPart)
		{
			$startX = $renderedBorderPart->parentBorderPart()->startsAt()->x();
			$startY = $renderedBorderPart->parentBorderPart()->startsAt()->y();

			// TODO: Fix this, all borders are shifted away from their original start position
			$imageStartX = $startX * $this->fieldSize + $this->getTotalBorderWidthUntilColumn($startX) - $renderedBorderPart->parentBorderPart()->thickness()->width();
			$imageStartY = $startY * $this->fieldSize + $this->getTotalBorderHeightUntilRow($startY) - $renderedBorderPart->parentBorderPart()->thickness()->height();

			$rawRenderedBorderPart = $renderedBorderPart->rawRenderedBorderPart();
			imagecopy($image, $rawRenderedBorderPart, $imageStartX, $imageStartY, 0, 0, imagesx($rawRenderedBorderPart), imagesy($rawRenderedBorderPart));
		}

		return $image;
	}

	/**
	 * Initializes the background image of the border grid.
	 *
	 * @return resource The background image
	 */
	private function initializeImage()
	{
		$imageWidth = $this->board->width() * $this->fieldSize + $this->getTotalBorderWidthUntilColumn($this->getHighestColumnId());
		$imageHeight = $this->board->height() * $this->fieldSize + $this->getTotalBorderHeightUntilRow($this->getHighestRowId());

		$image = imagecreate($imageWidth, $imageHeight);
		imagefill($image, 0, 0, $this->backgroundColor->getColor($image));

		return $image;
	}
}
