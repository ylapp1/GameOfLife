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
        $specialSymbolsTop = $this->getSpecialSymbols("╤", "╤", $_board->width());
        $specialSymbolsAboveBelow = $this->getSpecialSymbols("┼", "┼", $_board->width());
        $specialSymbolsBottom = $this->getSpecialSymbols("╧", "╧", $_board->width());

        $output = $this->getHorizontalLineString($_board->width(), "╔", "╗", "═", $specialSymbolsTop) . "\n";

        for ($y = 0; $y < $_board->height(); $y++)
        {
            if ($this->isHighLight)
            {
                if ($y == $this->highLightY && $y > 0 ||
                    $y == $this->highLightY + 1 && $y < $_board->height())
                { // Output lines below and above highlighted cell row

                    $output .= $this->getHorizontalLineString($_board->width(), "╟", "╢", "─", $specialSymbolsAboveBelow) . "\n";
                }
            }
            elseif ($_selectionCoordinates)
            {
                $borderRowString = $this->getSelectionBorder($y, $_selectionCoordinates, $_board, $_sideBorderSymbol);
                if ($borderRowString) $output .= $borderRowString;
            }

            $output .= $_sideBorderSymbol;
            $output .= $this->getRowOutputString($_board->fields()[$y], $_cellAliveSymbol, $_cellDeadSymbol, $_selectionCoordinates);
            $output .= $_sideBorderSymbol;

            if ($this->isHighLight && $y == $this->highLightY) $output .= " " . $y;
            $output .= "\n";
        }

        $output .= $this->getHorizontalLineString($_board->width(), "╚", "╝", "═", $specialSymbolsBottom) . "\n";

        return $output;
    }

    /**
     * Returns the output string for the cells of a single row.
     *
     * @param Field[] $_fields The fields of the row
     * @param String $_cellAliveSymbol The symbol for living cells
     * @param String $_cellDeadSymbol The symbol for dead cells
     * @param array $_selectionCoordinates The selection coordinates
     *
     * @return String Row output String
     */
    protected function getRowOutputString (array $_fields, String $_cellAliveSymbol, String $_cellDeadSymbol, array $_selectionCoordinates = null): String
    {
        $output = "";

        $boardWidth = count($_fields) - 1;

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
                    $field->x() == $this->highLightX && $field->x() < $boardWidth)
                { // Output lines left and right from highlighted cell X-Coordinate
                    $output .= "│";
                }
            }
            elseif ($_selectionCoordinates)
                {
                    if ($field->x() == $_selectionCoordinates["A"]["x"] && $field->x() > 0 ||
                        $field->x() == $_selectionCoordinates["B"]["x"] && $field->x() < $boardWidth)
                    { // If x value is the same like one of the selection coordinates

                        if ($field->y() >= $_selectionCoordinates["A"]["y"] &&
                            $field->y() < $_selectionCoordinates["B"]["y"])
                        {
                            $output .= "┋";
                        }
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
    private function getSelectionBorder(int $_y, array $_selectionCoordinates, Board $_board, String $_sideBorderSymbol)
    {
        if ($_y == $_selectionCoordinates["A"]["y"])
        { // Upper selection border

            return $this->getSelectionBorderRowString(
                $_selectionCoordinates["A"]["x"],
                $_selectionCoordinates["B"]["x"],
                $_board->width(),
                $_sideBorderSymbol,
                "┏",
                "┓");
        }
        elseif ($_y == $_selectionCoordinates["B"]["y"])
        { // Bottom border

            return $this->getSelectionBorderRowString(
                $_selectionCoordinates["A"]["x"],
                $_selectionCoordinates["B"]["x"],
                $_board->width(),
                $_sideBorderSymbol,
                "┗",
                "┛");
        }

        return false;
    }

    /**
     * Returns the string for a line including the bottom or top border of the current selection.
     *
     * @param int $_startX
     * @param int $_endX
     * @param int $_boardWidth
     * @param String $_sideBorderSymbol
     * @param String $_leftEdgeSymbol
     * @param String $_rightEdgeSymbol
     * @return String
     */
    private function getSelectionBorderRowString(int $_startX, int $_endX, int $_boardWidth, String $_sideBorderSymbol, String $_leftEdgeSymbol, String $_rightEdgeSymbol)
    {
        // Border of the game of life box
        $line = $_sideBorderSymbol;

        // Empty space
        for ($x = 0; $x < $_startX; $x++)
        {
            $line .= " ";
        }

        if ($_startX == 0) $line .= "╍";
        else $line .= $_leftEdgeSymbol;

        // Dotted border
        for ($x = $_startX; $x < $_endX; $x++)
        {
            $line .= "╍";
        }

        $line .= $_rightEdgeSymbol;

        // Empty space
        for ($x = $_endX; $x < $_boardWidth; $x++)
        {
            $line .= " ";
        }

        if ($_endX == $_boardWidth) $line .= "╍";
        else $line .= $_sideBorderSymbol;

        $line .= "\n";

        return $line;
    }

    /**
     * Returns the array of special symbols for the line output string.
     *
     * @param String $_symbolLeft The symbol for the left side of the highlighted column
     * @param String $_symbolRight The symbol for the right side of the highlighted column
     * @param int $_boardWidth The board width
     *
     * @return array The special symbols array
     */
    private function getSpecialSymbols(String $_symbolLeft, String $_symbolRight, int $_boardWidth): array
    {
        $specialSymbols = array();

        if ($this->isHighLight)
        {
            if ($this->highLightX > 0) $specialSymbols[$this->highLightX] = $_symbolLeft;
            if ($this->highLightX + 1 < $_boardWidth) $specialSymbols[$this->highLightX + 1] = $_symbolRight;
        }

        return $specialSymbols;
    }
}
