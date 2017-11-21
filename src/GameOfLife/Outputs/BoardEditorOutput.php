<?php
/**
 * @file
 * @version 0.1
 * @copyright 2017 CN-Consult GmbH
 * @author Yannick Lapp <yannick.lapp@cn-consult.eu>
 */

namespace Output;

use GameOfLife\Board;
use GameOfLife\Field;

/**
 * Prints the BoardEditor to the console for UserInput.
 */
class BoardEditorOutput extends ConsoleOutput
{
    private $bonusDashes;
    private $highLightX;
    private $highLightY;
    private $isHighLight;

    /**
     * Print the board to the console and highlights the cell at ($_curX | $_curY) if both values are set.
     *
     * @param Board $_board Current board
     * @param Integer $_highLightX X-Coordinate of the cell that shall be highlighted
     * @param Integer $_highLightY Y-Coordinate of the cell that shall be highlighted
     */
    public function outputBoard(Board $_board, int $_highLightX = null, int $_highLightY = null)
    {
        if (isset($_highLightX) && isset($_highLightY))
        {
            $this->highLightX = $_highLightX;
            $this->highLightY = $_highLightY;
            $this->isHighLight = true;

            $this->bonusDashes = 2;

            if ($_highLightX == 0) $this->bonusDashes -= 1;
            if ($_highLightX == $_board->width() - 1) $this->bonusDashes -= 1;

            // Output the X-Coordinate of the highlighted cell above the board
            echo "\n" . str_pad("", $_highLightX + $this->bonusDashes, " ") . $_highLightX . "\n";
        }
        else
        {
            $this->bonusDashes = 0;
        }

        echo $this->getBoardContentString($_board, "║", "o", " ");

        $this->isHighLight = false;
        unset($this->highLightX);
        unset($this->highLightY);
    }

    /**
     * Returns the board output string.
     *
     * @param Board $_board The board
     * @param String $_sideBorderSymbol Symbol for left and right border
     * @param String $_cellAliveSymbol Symbol for a living cell
     * @param String $_cellDeadSymbol Symbol for a dead cell
     *
     * @return String Board output string
     */
    protected function getBoardContentString(Board $_board, String $_sideBorderSymbol, String $_cellAliveSymbol, String $_cellDeadSymbol): String
    {
        $output =  $this->getHorizontalLineString($_board->width() + $this->bonusDashes, "╔", "╗", "═") . "\n";

        for ($y = 0; $y < $_board->height(); $y++)
        {
            if ($this->isHighLight)
            {
                if ($y == $this->highLightY && $y > 0 ||
                    $y == $this->highLightY + 1 && $y < $_board->height())
                { // Output lines below and above highlighted cell row

                    $output .= $this->getHorizontalLineString($_board->width() + $this->bonusDashes, "║", "║", "═") . "\n";
                }
            }

            $output .= $_sideBorderSymbol;
            $output .= $this->getRowOutputString($_board->fields()[$y], $_cellAliveSymbol, $_cellDeadSymbol);
            $output .= $_sideBorderSymbol;

            if ($this->isHighLight)
            {
                if ($y == $this->highLightY) $output .= " " . $y;

            }
            $output .= "\n";
        }

        $output .= $this->getHorizontalLineString($_board->width() + $this->bonusDashes, "╚", "╝", "═") . "\n";

        return $output;
    }

    /**
     * Returns the output string for the cells of a single row.
     *
     * @param Field[] $_fields The fields of the row
     * @param String $_cellAliveSymbol The symbol for living cells
     * @param String $_cellDeadSymbol The symbol for dead cells
     *
     * @return String Row output String
     */
    protected function getRowOutputString (array $_fields, String $_cellAliveSymbol, String $_cellDeadSymbol): String
    {
        $output = "";

        foreach ($_fields as $field)
        {
            if ($field->isAlive())
            {
                if ($this->isHighLight)
                {
                    if ($field->x() == $this->highLightX && $field->y() == $this->highLightY) $output .= "X";
                    else $output .= $_cellAliveSymbol;
                }
                else $output .= $_cellAliveSymbol;
            }
            else $output .= $_cellDeadSymbol;

            if ($this->isHighLight)
            {
                if ($field->x() == $this->highLightX - 1  && $field->x() >= 0 ||
                    $field->x() == $this->highLightX && $field->x() < count($_fields) - 1)
                { // Output lines left and right from highlighted cell X-Coordinate
                    $output .= "║";
                }
            }
        }

        return $output;
    }
}