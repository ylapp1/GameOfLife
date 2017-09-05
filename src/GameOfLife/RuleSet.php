<?php
/**
 * @file
 * @version 0.1
 * @copyright 2017 CN-Consult GmbH
 * @author Yannick Lapp <yannick.lapp@cn-consult.eu>
 */

namespace GameOfLife;

/**
 * Class RuleSet
 *
 * Stores the birth/death rules for the game of life
 */
class RuleSet
{
    private $birth = array();
    private $death = array();

    /**
     * RuleSet constructor.
     *
     * @param array $_birth     Amount of living neighbours which will rebirth a dead cell
     * @param array $_death     Amount of living neighbours which will kill a living cell
     */
    public function __construct($_birth, $_death)
    {
        $this->birth = $_birth;
        $this->death = $_death;
    }

    /**
     * Returns the birth rules
     *
     * @return array    Birth rules
     */
    public function birth()
    {
        return $this->birth;
    }

    /**
     * Sets the birth rules
     *
     * @param array $_birth   Amount of living neighbours which will rebirth a dead cell
     */
    public function setBirth($_birth)
    {
        $this->birth = $_birth;
    }

    /**
     * Returns the death rules
     *
     * @return array    Death rules
     */
    public function death()
    {
        return $this->death;
    }

    /**
     * Sets the death rules
     *
     * @param array $_death  Amount of living neighbours which will kill a living cell
     */
    public function setDeath($_death)
    {
        $this->death = $_death;
    }
}