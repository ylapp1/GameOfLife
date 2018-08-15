<?php
/**
 * @file
 * @version 0.1
 * @copyright 2018 CN-Consult GmbH
 * @author Yannick Lapp <yannick.lapp@cn-consult.eu>
 */

namespace BoardRenderer\Image\Border\BorderPart;

use BoardRenderer\Base\Border\BorderPart\BaseBorderPart;
use BoardRenderer\Base\Border\BorderPart\Shapes\BaseBorderPartShape;
use BoardRenderer\Image\Border\ImageBorder;
use GameOfLife\Coordinate;

/**
 * The image border part.
 */
class ImageBorderPart extends BaseBorderPart
{
	/**
	 * @var ImageBorder $parentBorder
	 */
	protected $parentBorder;


	// Magic Methods

	public function __construct(ImageBorder $_parentBorder, Coordinate $_startsAt, Coordinate $_endsAt, BaseBorderPartShape $_shape)
	{
		parent::__construct($_parentBorder, $_startsAt, $_endsAt, $_shape);
	}


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
