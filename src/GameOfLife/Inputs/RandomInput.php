<?php
/**
 * @file
 * @version 0.1
 * @copyright 2017-2018 CN-Consult GmbH
 * @author Yannick Lapp <yannick.lapp@cn-consult.eu>
 */

namespace Input;

use Simulator\Board;
use Ulrichsg\Getopt;

/**
 * Fills the board with random set cells.
 */
class RandomInput extends BaseInput
{
    /**
     * Adds RandomInputs specific options to the option list.
     *
     * @param Getopt $_options Option list to which the objects options are added
     */
    public function addOptions(Getopt $_options)
    {
        $_options->addOptions(
            array(
                array(null, "fillPercent", Getopt::REQUIRED_ARGUMENT, "RandomInput - Percentage of living cells on a random board\n")
            )
        );
    }

    /**
     * Fills the board with random cells until a specific percentage of the field is filled.
     *
     * @param Board $_board The Board which will be filled
     * @param Getopt $_options The option list
     *
     * @throws \Exception The exception when the fill percentage is invalid
     */
    public function fillBoard(Board $_board, Getopt $_options)
    {
        if ($_options->getOption("fillPercent") !== null) $fillPercent = (float)$_options->getOption("fillPercent");
        else $fillPercent = (float)(rand(15000, 70000) / 1000);

        if ($fillPercent > 100)
        {
            throw new \Exception("There can't be more living cells than 100% of the fields.");
        }
        elseif ($fillPercent < 0)
        {
            throw new \Exception("There can't be less living cells than 0% of the fields.");
        }

        // Fill the board with random set cells
        $numberOfSetCells = 0;
        $numberOfFields = $_board->width() * $_board->height();

        while (($numberOfSetCells / $numberOfFields) * 100 < $fillPercent)
        {
            $x = rand(0, $_board->width() - 1);
            $y = rand(0, $_board->height() - 1);

            if ($_board->getFieldState($x, $y) == false)
            {
                $_board->setFieldState($x, $y, true);
                $numberOfSetCells++;
            }
        }
    }
}
