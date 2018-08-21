<?php
/**
 * @file
 * @version 0.1
 * @copyright 2018 CN-Consult GmbH
 * @author Yannick Lapp <yannick.lapp@cn-consult.eu>
 */

namespace BoardRenderer\Text\Border;

use BoardRenderer\Base\Border\BaseBorder;
use BoardRenderer\Text\Border\Shapes\TextGridBorderShape;

/**
 * The background grid border for texts.
 */
class TextBackgroundGridBorder extends BaseBorder
{
	public function __construct(BaseBorder $_parentBorder = null)
	{
		parent::__construct($_parentBorder, new TextGridBorderShape($this));
	}
}
