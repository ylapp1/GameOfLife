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
use Ulrichsg\Getopt;

/**
 * Prints the BoardEditor to the console for UserInput.
 */
class BoardEditorOutput extends ConsoleOutput
{
    /**
     * The symbol that is used to print a living cell
     *
     * @var String $cellAliveSymbol
     */
    protected $cellAliveSymbol = "o";


    /**
     * BoardEditorOutput constructor.
     */
    public function __construct()
    {
        parent::__construct();
        $this->outputTitle = "BOARD EDITOR";
    }

    private $additionalSpace;
    private $highLightX;
    private $highLightY;
    private $isHighLight;
    private $selectionCoordinates;

    /**
     * Initializes the output.
     *
     * @param Getopt $_options User inputted option list
     * @param Board $_board Initial board
     */
    public function startOutput(Getopt $_options, Board $_board)
    {
        parent::startOutput($_options, $_board);
    }

    /**
     * Print the board to the console and highlights the cell at ($_curX | $_curY) if both values are set.
     *
     * @param Board $_board Current board
     * @param int $_gameStep The current game step
     * @param Integer $_highLightX X-Coordinate of the cell that shall be highlighted
     * @param Integer $_highLightY Y-Coordinate of the cell that shall be highlighted
     * @param array $_selectionCoordinates The selection coordinates
     */
    public function outputBoard(Board $_board, int $_gameStep, int $_highLightX = null, int $_highLightY = null, array $_selectionCoordinates = null)
    {
        $this->selectionCoordinates = $_selectionCoordinates;
        $this->additionalSpace = 0;

        if (isset($_highLightX) && isset($_highLightY))
        {
            $this->highLightX = $_highLightX;
            $this->highLightY = $_highLightY;
            $this->isHighLight = true;

            $hasLeftBorder = true;
            if ($_highLightX == 0) $hasLeftBorder = false;
            $hasRightBorder = true;
            if ($_highLightX == $_board->width() - 1) $hasRightBorder = false;

            $this->additionalSpace = $hasLeftBorder + $hasRightBorder;

            // Output the X-Coordinate of the highlighted cell above the board
            $paddingLeft = str_repeat(" ", $_highLightX + $hasLeftBorder);
            $xCoordinateHighLightString = str_pad($paddingLeft . $_highLightX, $_board->width() + $this->additionalSpace);

            $this->shellOutputHelper->printCenteredOutputString($xCoordinateHighLightString . "\n");
        }
        elseif ($_selectionCoordinates && $_selectionCoordinates != array())
        {
            $this->additionalSpace = 2;

            if ($_selectionCoordinates["A"]["x"] == 0) $this->additionalSpace -= 1;
            if ($_selectionCoordinates["B"]["x"] == $_board->width() - 1) $this->additionalSpace -= 1;
        }

        echo $this->getBoardContentString($_board);

        $this->isHighLight = false;
    }

	/**
	 * Returns the string for the top border.
	 *
	 * @param Board $_board The board
	 *
	 * @return String The string for the top border
	 */
    protected function getBorderTopString($_board): String
    {
    	$topBorderString = parent::getBorderTopString($_board);

    	// Add additional space
	    $additionalBorderString = str_repeat($this->borderSymbols["top-bottom"], $this->additionalSpace);
	    $topBorderString = substr_replace($topBorderString, $additionalBorderString, 1, 0);

        $specialSymbolsTop = $this->getSpecialSymbols("╤", "╤", $_board->width(), $_board->height(), $this->selectionCoordinates);
        foreach ($specialSymbolsTop as $index => $specialSymbol)
        {
        	$topBorderString = substr_replace($topBorderString, $specialSymbol, $index, 1);
        }

        return $topBorderString;
    }

	/**
	 * Returns the string for the bottom border.
	 *
	 * @param Board $_board The board
	 *
	 * @return String The string for the bottom border
	 */
    protected function getBorderBottomString($_board): String
    {
	    $bottomBorderString = parent::getBorderTopString($_board);

	    // Add additional space
	    $additionalBorderString = str_repeat($this->borderSymbols["top-bottom"], $this->additionalSpace);
	    $bottomBorderString = substr_replace($bottomBorderString, $additionalBorderString, 1, 0);

	    $specialSymbolsBottom = $this->getSpecialSymbols("╧", "╧", $_board->width(), $_board->height(), $this->selectionCoordinates, true);
	    foreach ($specialSymbolsBottom as $index => $specialSymbol)
	    {
		    $bottomBorderString = substr_replace($bottomBorderString, $specialSymbol, $index, 1);
	    }

	    return $bottomBorderString;
    }

	/**
	 * Returns the output string for the cells of a single row.
	 *
	 * @param Field[] $_fields The fields of the row
	 *
	 * @return String Row output String
	 */
	protected function getRowOutputString (array $_fields): String
	{
		$row = "";
		$specialSymbolsAboveBelow = $this->getSpecialSymbols("┼", "┼", $_board->width(), $_board->height(), $this->selectionCoordinates);
		$_selectionCoordinates = array();

		if ($this->isHighLight)
		{
			if ($y == $this->highLightY && $y > 0 ||
				$y == $this->highLightY + 1 && $y < $_board->height())
			{ // Output lines below and above highlighted cell row

				//$row = $this->getHorizontalLineString($_board->width() + $this->additionalSpace, "╟", "╢", "─", $specialSymbolsAboveBelow);
			}
		}
		elseif ($_selectionCoordinates)
		{
			$borderRowString = $this->getSelectionBorderRowString($y, $_selectionCoordinates, $_board, $_sideBorderSymbol);
			if ($borderRowString) $row = $borderRowString;
		}

		if ($row) $output .= $row . "\n";

		$row = $_sideBorderSymbol . $this->getRowOutputString($_board->fields()[$y], $_board->height(), $_selectionCoordinates) . $_sideBorderSymbol;
		$output .= $row;

		if ($this->isHighLight && $y == $this->highLightY) $output .= " " . $y;
		$output .= "\n";
	}

	protected function getCellSymbol(Field $_field): String
	{
		$cellSymbol = parent::getCellSymbol($_field);
		$boardWidth = "TODO: Get board width";

		if ($this->isHighLight)
		{
			if ($_field->x() == $this->highLightX && $_field->y() == $this->highLightY)
			{
				if ($_field->isAlive()) $cellSymbol = "X";
			}

			if ($_field->x() == $this->highLightX - 1  && $_field->x() >= 0 ||
				$_field->x() == $this->highLightX && $_field->x() < $boardWidth - 1)
			{ // Output lines left and right from highlighted cell X-Coordinate
				$cellSymbol .= "│";
			}
		}

		return $cellSymbol;
	}

	/**
     * Returns the output string for the cells of a single row.
     *
     * @param Field[] $_fields The fields of the row
     * @param int $_boardHeight The board height. This value is not optional, but its default value is null to not break the compatibility to the parent method
     * @param array $_selectionCoordinates The selection coordinates
     *
     * @return String Row output String
     */
    protected function getRowOutputString (array $_fields, int $_boardHeight = null, array $_selectionCoordinates = null): String
    {
        $_cellAliveSymbol = "";
        $_cellDeadSymbol = "";
        $output = "";

        $boardWidth = count($_fields);

        foreach ($_fields as $field)
        {
            // Print cell

            // Print highlight border
            if ($this->isHighLight)
            {

            }
            elseif ($_selectionCoordinates)
            {
                if ($field->x() == $_selectionCoordinates["A"]["x"] - 1 && $field->x() + 1 > 0 ||
                    $field->x() == $_selectionCoordinates["B"]["x"] && $field->x() + 1 < $boardWidth)
                { // If x value is the same like one of the selection coordinates

                    if ($field->y() >= $_selectionCoordinates["A"]["y"] && $field->y() >= 0 &&
                        $field->y() < $_selectionCoordinates["B"]["y"] + 1 && $field->y() < $_boardHeight)
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
            $_y == $_selectionCoordinates["B"]["y"] + 1 && $_y < $_board->height())
        {
            $specialSymbols = array();

            $leftBorderSymbol = $_sideBorderSymbol;
            $rightBorderSymbol = $_sideBorderSymbol;

            $hasLeftBorder = false;
            if ($_selectionCoordinates["A"]["x"] > 0)
            {
                $hasLeftBorder = true;

                if ($_y == $_selectionCoordinates["A"]["y"]) $specialSymbols[$_selectionCoordinates["A"]["x"]] = "┏";
                else $specialSymbols[$_selectionCoordinates["A"]["x"]] = "┗";
            }
            else $leftBorderSymbol = "╟";

            $hasRightBorder = false;
            if ($_selectionCoordinates["B"]["x"] + 1 < $_board->width())
            {
                $hasRightBorder = true;

                if ($_y == $_selectionCoordinates["A"]["y"]) $specialSymbols[$_selectionCoordinates["B"]["x"] + $hasLeftBorder + 1] = "┓";
                else $specialSymbols[$_selectionCoordinates["B"]["x"] + $hasLeftBorder + 1] = "┛";
            }
            else $rightBorderSymbol = "╢";

            $startX = $_selectionCoordinates["A"]["x"] + $hasLeftBorder;
            $endX = $_selectionCoordinates["B"]["x"] + $hasLeftBorder + 1;
            for ($x = $startX; $x <= $endX - $hasRightBorder; $x++)
            {
                $specialSymbols[$x] = "╍";
            }

            /*
            return $this->getHorizontalLineString(
                $_board->width() + $hasLeftBorder + $hasRightBorder,
                    $leftBorderSymbol,
                    $rightBorderSymbol,
                " ",
                $specialSymbols
                );
            */
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
     * @param Bool $_isBottomBorder Indicates whether the border for which the special symbols will be used is a bottom border
     *
     * @return array The special symbols array
     */
    private function getSpecialSymbols(String $_symbolLeft, String $_symbolRight, int $_boardWidth, int $_boardHeight, array $_selectionCoordinates = null, Bool $_isBottomBorder = false): array
    {
        $specialSymbols = array();

        if ($this->isHighLight)
        {
            if ($this->highLightX > 0) $specialSymbols[$this->highLightX] = $_symbolLeft;
            if ($this->highLightX + 1 < $_boardWidth) $specialSymbols[$this->highLightX + 1 + count($specialSymbols)] = $_symbolRight;
        }
        elseif ($_selectionCoordinates)
        {
            if ((! $_isBottomBorder && $_selectionCoordinates["A"]["y"] == 0) ||
                ($_isBottomBorder && $_selectionCoordinates["B"]["y"] == $_boardHeight - 1))
            {
                if ($_selectionCoordinates["A"]["x"] > 0) $specialSymbols[$_selectionCoordinates["A"]["x"]] = $_symbolLeft;
                if ($_selectionCoordinates["B"]["x"] + 1 < $_boardWidth - 1) $specialSymbols[$_selectionCoordinates["B"]["x"] + count($specialSymbols) + 1] = $_symbolRight;
            }
        }

        return $specialSymbols;
    }
}
