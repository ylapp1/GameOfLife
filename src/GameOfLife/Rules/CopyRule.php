<?php
/**
 * @file
 * @version 0.1
 * @copyright 2017-2018 CN-Consult GmbH
 * @author Yannick Lapp <yannick.lapp@cn-consult.eu>
 */

namespace Rule;

use Ulrichsg\Getopt;

/**
 * Rules for a copy world (Rules for 1357/1357).
 */
class CopyRule extends BaseRule
{
    // Magic Methods

    /**
     * Sets the birth/stay alive rules for this rule.
     */
    public function __construct()
    {
        parent::__construct();
        $this->rulesBirth = array(1, 3, 5, 7);
        $this->rulesStayAlive = array(1, 3, 5, 7);
    }


    // Class Methods

    /**
     * Adds rule specific options to a option list.
     *
     * @param Getopt $_options The option list
     */
    public function addOptions(Getopt $_options)
    {
    }
}
