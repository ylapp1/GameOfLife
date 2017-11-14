<?php
/**
 * @file
 * @version 0.1
 * @copyright 2017 CN-Consult GmbH
 * @author Yannick Lapp <yannick.lapp@cn-consult.eu>
 */

namespace GameOfLife;

/**
 * Stores the birth/death rules for the game of life.
 */
class RuleSet
{
    private $birth = array();
    private $death = array();


    // Magic Methods

    /**
     * RuleSet constructor.
     *
     * @param array $_birth     Amount of living neighbours which will rebirth a dead cell
     * @param array $_death     Amount of living neighbours which will kill a living cell
     */
    public function __construct(array $_birth, array $_death)
    {
        $this->birth = $_birth;
        $this->death = $_death;
    }


    // Getters and Setters

    /**
     * Returns the birth rules.
     *
     * @return array    Amount of living neighbours which will rebirth a dead cell
     */
    public function birth(): array
    {
        return $this->birth;
    }

    /**
     * Sets the birth rules.
     *
     * @param array $_birth   Amount of living neighbours which will rebirth a dead cell
     */
    public function setBirth(array $_birth)
    {
        $this->birth = $_birth;
    }

    /**
     * Returns the death rules.
     *
     * @return array    Amount of living neighbours which will kill a living cell
     */
    public function death(): array
    {
        return $this->death;
    }

    /**
     * Sets the death rules.
     *
     * @param array $_death  Amount of living neighbours which will kill a living cell
     */
    public function setDeath(array $_death)
    {
        $this->death = $_death;
    }
}