<?php
/**
 * @file
 * @version 0.1
 * @copyright 2018 CN-Consult GmbH
 * @author Yannick Lapp <yannick.lapp@cn-consult.eu>
 */

namespace BoardRenderer\Image\CellImage;

use BoardRenderer\Image\Utils\ImageColor;
use BoardRenderer\Image\Utils\TransparentImageUtils;

/**
 * Base class for cell images that are partially transparent.
 */
abstract class TransparentCellImage extends BaseCellImage
{
	// Attributes

	/**
	 * The transparent image utils
	 *
	 * @var TransparentImageUtils $transparentImageUtils
	 */
	protected $transparentImageUtils;


	// Magic Methods

	/**
	 * TransparentCellImage constructor.
	 *
	 * @param ImageColor $_color The color of the cell image
	 * @param int $_height The height of the image
	 * @param int $_width The width of the image
	 */
	public function __construct(ImageColor $_color, int $_height, int $_width)
	{
		parent::__construct($_color, $_height, $_width);
		$this->transparentImageUtils = new TransparentImageUtils();
	}
}
