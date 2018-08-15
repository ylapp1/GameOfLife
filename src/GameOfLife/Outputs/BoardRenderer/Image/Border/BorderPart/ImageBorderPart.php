<?php
/**
 * @file
 * @version 0.1
 * @copyright 2018 CN-Consult GmbH
 * @author Yannick Lapp <yannick.lapp@cn-consult.eu>
 */

namespace BoardRenderer\Image\Border\BorderPart;

use BoardRenderer\Base\Border\BorderPart\BaseBorderPart;
use BoardRenderer\Base\Border\BorderPart\BorderPartThickness;
use BoardRenderer\Base\Border\BorderPart\Shapes\BaseBorderPartShape;
use BoardRenderer\Image\Border\ImageBorder;
use GameOfLife\Coordinate;

/**
 * The image border part.
 */
class ImageBorderPart extends BaseBorderPart
{
	// Attributes

	/**
	 * The parent border
	 *
	 * @var ImageBorder $parentBorder
	 */
	protected $parentBorder;


	// Magic Methods

	/**
	 * ImageBorderPart constructor.
	 *
	 * @param ImageBorder $_parentBorder The parent border
	 * @param Coordinate $_startsAt The start coordinate
	 * @param Coordinate $_endsAt The end coordinate
	 * @param BaseBorderPartShape $_shape The border part shape
	 * @param BorderPartThickness $_thickness The thickness of the border
	 */
	public function __construct(ImageBorder $_parentBorder, Coordinate $_startsAt, Coordinate $_endsAt, BaseBorderPartShape $_shape, BorderPartThickness $_thickness)
	{
		parent::__construct($_parentBorder, $_startsAt, $_endsAt, $_shape, $_thickness);
	}


	// Class Methods

	/**
	 * Returns the parent border.
	 *
	 * @return ImageBorder The parent border
	 */
	public function parentBorder()
	{
		return $this->parentBorder;
	}
}
