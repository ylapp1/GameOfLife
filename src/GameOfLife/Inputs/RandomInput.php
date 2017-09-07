<?php
/**
 * @file
 * @version 0.1
 * @copyright 2017 CN-Consult GmbH
 * @author Yannick Lapp <yannick.lapp@cn-consult.eu>
 */

namespace Input;

use GameOfLife\Board;
use Ulrichsg\Getopt;

/**
 * Class RandomInput
 */
class RandomInput extends BaseInput
{
    /**
     * Adds RandomInputs specific options to the option list
     *
     * @param Getopt $_options  Option list to which the objects options are added
     */
    public function addOptions($_options)
    {
        $_options->addOptions(
            array(
                array(null, "fillPercent", Getopt::REQUIRED_ARGUMENT, "Percentage of living cells on a random board"))
        );
    }

    /**
     * Fills the board with random cells until a specific percentage of the field is filled
     *
     * @param Board $_board     The Board
     * @param Getopt $_options  Options (fillPercent)
     */
    public function fillBoard($_board, $_options)
    {
        // fetch options
        $fillPercent = $_options->getOption("fillPercent");

        // fill with default value if option is not set
        if (! isset($fillPercent)) $fillPercent = rand(15, 70);
        elseif ($fillPercent > 100)
        {
            echo "Error: There can't be more living cells than 100% of the fields.\n";
            return;
        }

        // Fill random cells
        $amountSetCells = 0;
        $amountFields = $_board->width() * $_board->height();

        while (($amountSetCells / $amountFields) * 100 < $fillPercent)
        {
            $x = rand(0, $_board->width() - 1);
            $y = rand(0, $_board->height() - 1);

            if ($_board->getField($x, $y) == false)
            {
                $_board->setField($x, $y, true);
                $amountSetCells++;
            }
        }
    }
}