<?php
/**
 * @file
 * @version 0.1
 * @copyright 2017-2018 CN-Consult GmbH
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
    private $additionalSpace;
    private $highLightX;
    private $highLightY;
    private $isHighLight;

    /**
     * Print the board to the console and highlights the cell at ($_curX | $_curY) if both values are set.
     *
     * @param Board $_board Current board
     * @param Integer $_highLightX X-Coordinate of the cell that shall be highlighted
     * @param Integer $_highLightY Y-Coordinate of the cell that shall be highlighted
     * @param array $_selectionCoordinates The selection coordinates
     */
    public function outputBoard(Board $_board, int $_highLightX = null, int $_highLightY = null, array $_selectionCoordinates = null)
    {
        $this->additionalSpace = 0;

        if (isset($_highLightX) && isset($_highLightY))
        {
            $this->highLightX = $_highLightX;
            $this->highLightY = $_highLightY;
            $this->isHighLight = true;

            $this->additionalSpace = 2;

            if ($_highLightX == 0) $this->additionalSpace -= 1;
            if ($_highLightX == $_board->width() - 1) $this->additionalSpace -= 1;

            // Output the X-Coordinate of the highlighted cell above the board
            echo "\n" . str_pad("", $_highLightX + $this->additionalSpace, " ") . $_highLightX . "\n";
        }
        elseif ($_selectionCoordinates)
        {
            $this->additionalSpace = 2;

            if ($_selectionCoordinates["A"]["x"] == 0) $this->additionalSpace -= 1;
            if ($_selectionCoordinates["B"]["x"] == $_board->width() - 1) $this->additionalSpace -= 1;
        }

        echo $this->getBoardContentString($_board, "║", "o", " ", $_selectionCoordinates);

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
     * @param array $_selectionCoordinates The selection coordinates
     *
     * @return String Board output string
     */
    protected function getBoardContentString(Board $_board, String $_sideBorderSymbol, String $_cellAliveSymbol, String $_cellDeadSymbol, array $_selectionCoordinates = null): String
    {
        $specialSymbolsTop = $this->getSpecialSymbols("╤", "╤", $_board->width(), $_board->height(), $_selectionCoordinates);
        $specialSymbolsAboveBelow = $this->getSpecialSymbols("┼", "┼", $_board->width(), $_board->height(), $_selectionCoordinates);
        $specialSymbolsBottom = $this->getSpecialSymbols("╧", "╧", $_board->width(), $_board->height(), $_selectionCoordinates);

        $output = $this->getHorizontalLineString($_board->width() + $this->additionalSpace, "╔", "╗", "═", $specialSymbolsTop) . "\n";

        for ($y = 0; $y < $_board->height(); $y++)
        {
            if ($this->isHighLight)
            {
                if ($y == $this->highLightY && $y > 0 ||
                    $y == $this->highLightY + 1 && $y < $_board->height())
                { // Output lines below and above highlighted cell row

                    $output .= $this->getHorizontalLineString($_board->width() + $this->additionalSpace, "╟", "╢", "─", $specialSymbolsAboveBelow) . "\n";
                }
            }
            elseif ($_selectionCoordinates)
            {
                $borderRowString = $this->getSelectionBorderRowString($y, $_selectionCoordinates, $_board, $_sideBorderSymbol);
                if ($borderRowString) $output .= $borderRowString;
            }

            $output .= $_sideBorderSymbol;
            $output .= $this->getRowOutputString($_board->fields()[$y], $_cellAliveSymbol, $_cellDeadSymbol, $_board->height(), $_selectionCoordinates);
            $output .= $_sideBorderSymbol;

            if ($this->isHighLight && $y == $this->highLightY) $output .= " " . $y;
            $output .= "\n";
        }

        $output .= $this->getHorizontalLineString($_board->width() + $this->additionalSpace, "╚", "╝", "═", $specialSymbolsBottom) . "\n";

        return $output;
    }

    /**
     * Returns the output string for the cells of a single row.
     *
     * @param Field[] $_fields The fields of the row
     * @param String $_cellAliveSymbol The symbol for living cells
     * @param String $_cellDeadSymbol The symbol for dead cells
     * @param int $_boardHeight The board height. This value is not optional, but its default value is null to not break the compatibility to the parent method
     * @param array $_selectionCoordinates The selection coordinates
     *
     * @return String Row output String
     */
    protected function getRowOutputString (array $_fields, String $_cellAliveSymbol, String $_cellDeadSymbol, int $_boardHeight = null, array $_selectionCoordinates = null): String
    {
        $output = "";

        $boardWidth = count($_fields) - 1;

        foreach ($_fields as $field)
        {
            // Print cell
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


            // Print highlight border
            if ($this->isHighLight)
            {
                if ($field->x() == $this->highLightX - 1  && $field->x() >= 0 ||
                    $field->x() == $this->highLightX && $field->x() < $boardWidth)
                { // Output lines left and right from highlighted cell X-Coordinate
                    $output .= "│";
                }
            }
            elseif ($_selectionCoordinates)
            {
                $hasLeftBorder = false;
                if ($_selectionCoordinates["A"]["x"] > 0) $hasLeftBorder = true;

                if ($field->x() == $_selectionCoordinates["A"]["x"] - $hasLeftBorder && $field->x() > 0 ||
                    $field->x() == $_selectionCoordinates["B"]["x"] - 1 - $hasLeftBorder && $field->x() < $boardWidth)
                { // If x value is the same like one of the selection coordinates

                    if ($field->y() >= $_selectionCoordinates["A"]["y"] && $field->y() >= 0 &&
                        $field->y() < $_selectionCoordinates["B"]["y"] && $field->y() < $_boardHeight)
                    {
                        $output .= "┋";
                    }
                    else $output .= " ";
                }
            }
        }

        return $output;
    }

    /**
     * Returns the top or bottom border of the selection area or null.
     *
     * @param int $_y
     * @param array $_selectionCoordinates
     * @param Board $_board
     * @param String $_sideBorderSymbol
     *
     * @return String|Bool The border row string or false
     */
    private function getSelectionBorderRowString(int $_y, array $_selectionCoordinates, Board $_board, String $_sideBorderSymbol)
    {
        if ($_y == $_selectionCoordinates["A"]["y"] && $_y > 0 ||
            $_y == $_selectionCoordinates["B"]["y"] && $_y < $_board->height())
        {
            $leftBorderSymbol = $_sideBorderSymbol;
            $rightBorderSymbol = $_sideBorderSymbol;

            $specialSymbols = array();
            for ($x = $_selectionCoordinates["A"]["x"]; $x <= $_selectionCoordinates["B"]["x"]; $x++)
            {
                $specialSymbols[$x] = "╍";
            }

            $numberOfBorderSymbols = 0;
            if ($_selectionCoordinates["A"]["x"] > 0)
            {
                $numberOfBorderSymbols++;
                if ($_y == $_selectionCoordinates["A"]["y"]) $specialSymbols[$_selectionCoordinates["A"]["x"]] = "┏";
                else $specialSymbols[$_selectionCoordinates["A"]["x"]] = "┗";
            }
            else $leftBorderSymbol = "╟";

            if ($_selectionCoordinates["B"]["x"] < $_board->width())
            {
                $numberOfBorderSymbols++;
                if ($_y == $_selectionCoordinates["A"]["y"]) $specialSymbols[$_selectionCoordinates["B"]["x"]] = "┓";
                else $specialSymbols[$_selectionCoordinates["B"]["x"]] = "┛";
            }
            else $rightBorderSymbol = "╢";

            return $this->getHorizontalLineString(
                $_board->width() + $numberOfBorderSymbols,
                    $leftBorderSymbol,
                    $rightBorderSymbol,
                " ",
                $specialSymbols
                ) . "\n";
        }
        else return false;
    }

    /**
     * Returns the array of special symbols for the line output string.
     *
     * @param String $_symbolLeft The symbol for the left side of the highlighted column
     * @param String $_symbolRight The symbol for the right side of the highlighted column
     * @param int $_boardWidth The board width
     * @param int $_boardHeight The board height
     * @param array $_selectionCoordinates The selection coordinates
     *
     * @return array The special symbols array
     */
    private function getSpecialSymbols(String $_symbolLeft, String $_symbolRight, int $_boardWidth, int $_boardHeight, array $_selectionCoordinates = null): array
    {
        $specialSymbols = array();

        if ($this->isHighLight)
        {
            if ($this->highLightX > 0) $specialSymbols[$this->highLightX] = $_symbolLeft;
            if ($this->highLightX + 1 < $_boardWidth) $specialSymbols[$this->highLightX + 1 + count($specialSymbols)] = $_symbolRight;
        }
        elseif ($_selectionCoordinates)
        {
            if ($_selectionCoordinates["A"]["y"] == 0 || $_selectionCoordinates["B"]["y"] == $_boardHeight)
            {
                if ($_selectionCoordinates["A"]["x"] > 0) $specialSymbols[$_selectionCoordinates["A"]["x"]] = $_symbolLeft;
                if ($_selectionCoordinates["B"]["x"] < $_boardWidth) $specialSymbols[$_selectionCoordinates["B"]["x"]] = $_symbolRight;
            }
        }

        return $specialSymbols;
    }
}
