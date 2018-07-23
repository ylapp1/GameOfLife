<?php
/**
 * @file
 * @version 0.1
 * @copyright 2018 CN-Consult GmbH
 * @author Yannick Lapp <yannick.lapp@cn-consult.eu>
 */

namespace Output\BoardRenderer\Base\Border\Shapes;

use Output\BoardRenderer\Base\Border\BaseBorder;
use Output\BoardRenderer\Base\Border\BorderPart\BaseBorderPart;

/**
 * Creates border parts that form a specific shape.
 */
abstract class BaseBorderShape
{
    /**
     * @var BaseBorder $parentBorder
     */
    protected $parentBorder;

    /**
     * BaseBorderShape constructor.
     */
    protected function __construct($_parentBorder)
    {
        $this->parentBorder = $_parentBorder;
    }

    public function parentBorder()
    {
        return $this->parentBorder;
    }

    // Class Methods

    /**
     * Returns all border parts of this border shape.
     *
     * @return BaseBorderPart[] The list of border parts
     */
    abstract public function getBorderParts();
}
