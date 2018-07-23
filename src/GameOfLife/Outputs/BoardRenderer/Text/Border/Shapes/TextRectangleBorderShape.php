<?php
/**
 * @file
 * @version 0.1
 * @copyright 2018 CN-Consult GmbH
 * @author Yannick Lapp <yannick.lapp@cn-consult.eu>
 */

use Output\BoardRenderer\Base\BorderShapes\RectangleBorderShape;


abstract class TextRectangleBorderShape extends RectangleBorderShape
{
	// Attributes

	/**
	 * The symbol for the top left corner of the border
	 *
	 * @var String $borderSymbolTopLeft
	 */
	protected $borderSymbolTopLeft;

	/**
	 * The symbol for the top right corner of the border
	 *
	 * @var String $borderSymbolTopRight
	 */
	protected $borderSymbolTopRight;

	/**
	 * The symbol for the bottom left corner of the border
	 *
	 * @var String $borderSymbolBottomLeft
	 */
	protected $borderSymbolBottomLeft;

	/**
	 * The symbol for the bottom right corner of the border
	 *
	 * @var String $borderSymbolBottomRight
	 */
	protected $borderSymbolBottomRight;

	/**
	 * The symbol for the top and bottom border
	 *
	 * @var String $borderSymbolTopBottom
	 */
	protected $borderSymbolTopBottom;

	/**
	 * The symbol for the left an right border
	 *
	 * @var String $borderSymbolLeftRight
	 */
	protected $borderSymbolLeftRight;


	/**
	 * BaseBorderPrinter constructor.
	 *
	 * @param String $_borderSymbolTopLeft The symbol for the top left corner of the border
	 * @param String $_borderSymbolTopRight The symbol for the top right corner of the border
	 * @param String $_borderSymbolBottomLeft The symbol for the bottom left corner of the border
	 * @param String $_borderSymbolBottomRight The symbol for the bottom right corner of the border
	 * @param String $_borderSymbolTopBottom The symbol for the top and bottom border
	 * @param String $_borderSymbolLeftRight The symbol for the left an right border
	 */
	protected function __construct(String $_borderSymbolTopLeft, String $_borderSymbolTopRight, String $_borderSymbolBottomLeft, String $_borderSymbolBottomRight, String $_borderSymbolTopBottom, String $_borderSymbolLeftRight)
	{
		$this->borderSymbolTopLeft = $_borderSymbolTopLeft;
		$this->borderSymbolTopRight = $_borderSymbolTopRight;
		$this->borderSymbolBottomLeft = $_borderSymbolBottomLeft;
		$this->borderSymbolBottomRight = $_borderSymbolBottomRight;
		$this->borderSymbolTopBottom = $_borderSymbolTopBottom;
		$this->borderSymbolLeftRight = $_borderSymbolLeftRight;

	}
}
