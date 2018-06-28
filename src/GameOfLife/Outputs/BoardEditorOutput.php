<?php
/**
 * @file
 * @version 0.1
 * @copyright 2017-2018 CN-Consult GmbH
 * @author Yannick Lapp <yannick.lapp@cn-consult.eu>
 */

namespace Output;

use BoardEditor\SelectionArea;
use GameOfLife\Board;
use GameOfLife\Coordinate;
use GameOfLife\Field;
use Ulrichsg\Getopt;

/**
 * Prints the BoardEditor to the console for UserInput.
 */
class BoardEditorOutput extends ConsoleOutput
{
	// Attributes

    /**
     * The symbol that is used to print a living cell
     *
     * @var String $cellAliveSymbol
     */
    protected $cellAliveSymbol = "o";

	/**
	 * The symbols that are used to print the highlight field borders
	 *
	 * @var String[] $highLightBorderSymbols
	 */
    private $highLightFieldBorderSymbols = array(

    	// Inner borders
    	"corner-inner-border" => "┼",
    	"top-bottom-inner-border" => "─",
    	"left-right-inner-border" => "│",

    	// Outer borders intersections
	    "top-outer-border" => "╤",
	    "bottom-outer-border" => "╧",
	    "left-outer-border" => "╟",
	    "right-outer-border" => "╢"
    );

	/**
	 * The symbols that are used to print the selection area border
	 *
	 * @var String[] $selectionBorderSymbols
	 */
    private $selectionAreaBorderSymbols = array(

	    // Inner borders
	    "top-left-inner-border" => "┏",
	    "top-right-inner-border" => "┓",
	    "bottom-right-inner-border" => "┛",
	    "bottom-left-inner-border" => "┗",
	    "top-bottom-inner-border" => "╍",
	    "left-right-inner-border" => "┋",

	    // Outer borders intersections
	    "top-outer-border" => "╤",
	    "bottom-outer-border" => "╧",
	    "left-outer-border" => "╟",
	    "right-outer-border" => "╢"
    );

	/**
	 * The coordinate of the currently highlighted field
	 *
	 * @var Coordinate $highLightFieldCoordinate
	 */
	private $highLightFieldCoordinate;

	/**
	 * The currently selected area
	 *
	 * @var SelectionArea $selectionArea
	 */
	private $selectionArea;

	/**
	 * The additional width per row because of inner borders (high light field or selection area)
	 *
	 * @var int $additionalWidthPerRow
	 */
	private $additionalWidthPerRow;


	// Magic Methods

	/**
     * BoardEditorOutput constructor.
     */
    public function __construct()
    {
        parent::__construct();
        $this->outputTitle = "BOARD EDITOR";
    }


    // Class Methods

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
     * Prints the board to the console and optionally highlights a high light field or the selection area.
     *
     * @param Board $_board Current board
     * @param int $_gameStep The current game step
     * @param Integer $_highLightX X-Coordinate of the cell that shall be highlighted
     * @param Integer $_highLightY Y-Coordinate of the cell that shall be highlighted
     * @param array $_selectionCoordinates The selection coordinates
     */
    public function outputBoard(Board $_board, int $_gameStep, int $_highLightX = null, int $_highLightY = null, array $_selectionCoordinates = array())
    {
    	if ($_selectionCoordinates)
	    {
		    $selectionCoordinateTopLeft = new Coordinate($_selectionCoordinates["A"]["x"], $_selectionCoordinates["A"]["y"]);
		    $selectionCoordinateBottomRight = new Coordinate($_selectionCoordinates["B"]["x"], $_selectionCoordinates["B"]["y"]);
		    $this->selectionArea = new SelectionArea($selectionCoordinateTopLeft, $selectionCoordinateBottomRight);
	    }
	    else $this->selectionArea = null;

    	if ($_highLightX && $_highLightY)
	    {
	    	$this->highLightFieldCoordinate = new Coordinate($_highLightX, $_highLightY);
	    }
	    else $this->highLightFieldCoordinate = null;

	    $hasInnerLeftBorder = $this->hasInnerLeftBorder();
	    $hasInnerRightBorder = $this->hasInnerRightBorder($_board);

        $this->additionalWidthPerRow = (int)$hasInnerLeftBorder + (int)$hasInnerRightBorder;;

	    // Output the X-Coordinate of the highlighted cell above the board
	    $paddingLeft = str_repeat(" ", $_highLightX + $hasInnerLeftBorder);
	    $xCoordinateHighLightString = str_pad($paddingLeft . $_highLightX, $_board->width() + $this->additionalWidthPerRow);

	    $this->shellOutputHelper->printCenteredOutputString($xCoordinateHighLightString . "\n");

        echo $this->getBoardContentString($_board);
    }

	/**
	 * @return bool
	 */
    private function hasInnerLeftBorder(): Bool
    {
	    $hasInnerLeftBorder = false;
	    if ($this->highLightFieldCoordinate && $this->highLightFieldCoordinate->x() > 0 ||
	        $this->selectionArea && $this->selectionArea->topLeftCornerCoordinate()->x() > 0)
	    {
	    	$hasInnerLeftBorder = true;
	    }

	    return $hasInnerLeftBorder;
    }

	/**
	 * @param Board $_board
	 * @return bool
	 */
    private function hasInnerRightBorder(Board $_board): Bool
    {
	    $hasInnerRightBorder = false;

	    if ($this->highLightFieldCoordinate && $this->highLightFieldCoordinate->x() < $_board->width() - 1 ||
		    $this->selectionArea && $this->selectionArea->bottomRightCornerCoordinate()->x() < $_board->width() - 1)
	    {
		    $hasInnerRightBorder = true;
	    }

	    return $hasInnerRightBorder;
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

	    if ($this->highLightFieldCoordinate || $this->selectionArea)
	    {
		    if ($this->highLightFieldCoordinate)
		    {
			    $specialSymbolLeft = $this->highLightFieldBorderSymbols["top-outer-border"];
			    $specialSymbolRight = $this->highLightFieldBorderSymbols["top-outer-border"];
		    }
		    else
		    {
		    	$specialSymbolLeft = $this->selectionAreaBorderSymbols["top-outer-border"];
		    	$specialSymbolRight = $this->selectionAreaBorderSymbols["top-outer-border"];
		    }

		    $specialSymbolsTop = $this->getSpecialSymbols($specialSymbolLeft, $specialSymbolRight, $_board);
		    $topBorderString = $this->addSpecialSymbolsToBorderString($topBorderString, $specialSymbolsTop, $this->additionalWidthPerRow);
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

		if ($this->highLightFieldCoordinate || $this->selectionArea)
		{
			if ($this->highLightFieldCoordinate)
			{
				$specialSymbolLeft = $this->highLightFieldBorderSymbols["bottom-outer-border"];
				$specialSymbolRight = $this->highLightFieldBorderSymbols["bottom-outer-border"];
			}
			else
			{
				$specialSymbolLeft = $this->selectionAreaBorderSymbols["bottom-outer-border"];
				$specialSymbolRight = $this->selectionAreaBorderSymbols["bottom-outer-border"];
			}

			$specialSymbolsBottom = $this->getSpecialSymbols($specialSymbolLeft, $specialSymbolRight, $_board);
			$bottomBorderString = $this->addSpecialSymbolsToBorderString($bottomBorderString, $specialSymbolsBottom, $this->additionalWidthPerRow);
		}

		return $bottomBorderString;
	}

	/**
	 * Returns the array of special symbols for the line output string.
	 *
	 * @param String $_symbolLeft The symbol for the left side of the highlighted column
	 * @param String $_symbolRight The symbol for the right side of the highlighted column
	 * @param Board $_board The board
	 *
	 * @return array The special symbols array
	 */
	private function getSpecialSymbols(String $_symbolLeft, String $_symbolRight, Board $_board): array
	{
		$specialSymbols = array();

		if ($this->hasInnerLeftBorder())
		{ // Left border exists
			if ($this->highLightFieldCoordinate) $xPosition = $this->highLightFieldCoordinate->x();
			else $xPosition = $this->selectionArea->topLeftCornerCoordinate()->x();

			$specialSymbols[$xPosition] = $_symbolLeft;
		}

		if ($this->hasInnerRightBorder($_board))
		{ // Right border exists
			if ($this->highLightFieldCoordinate) $xPosition = $this->highLightFieldCoordinate->x();
			else $xPosition = $this->selectionArea->bottomRightCornerCoordinate()->x();

			$symbolRightPosition = $xPosition + 1 + count($specialSymbols);
			$specialSymbols[$symbolRightPosition] = $_symbolRight;
		}

		return $specialSymbols;
	}

    private function addSpecialSymbolsToBorderString($_borderString, $_specialSymbols, $_additionalBorderWidth)
    {
	    // Add additional space
	    $additionalBorderString = str_repeat($this->borderSymbols["top-bottom"], $_additionalBorderWidth);
	    $borderString = substr_replace($_borderString, $additionalBorderString, 1, 0);

	    foreach ($_specialSymbols as $index => $specialSymbol)
	    {
		    $borderString = substr_replace($borderString, $specialSymbol, $index, 1);
	    }

	    return $borderString;
    }

	protected function getCellSymbol(Field $_field): String
	{
		$cellSymbol = parent::getCellSymbol($_field);
		$boardWidth = $_field->parentBoard()->width();

		if ($this->highLightFieldCoordinate)
		{
			if ($_field->coordinate()->y() == $this->highLightY && $_field->x() == $this->highLightX)
			{
				if ($_field->isAlive()) $cellSymbol = "X";
				$cellSymbol = $this->addHighLightLinesToCellString($_field, $cellSymbol, );
			}
		}
		elseif ($this->selectionCoordinates)
		{
			if (($_field->y() >= $this->selectionCoordinates["A"]["y"] || $_field->y() < $this->selectionCoordinates["B"]["y"] + 1))
			{
				$this->addHighLightLinesToCellString($_field, $cellSymbol, );
			}
		}

		return $cellSymbol;
	}

	/**
	 * @param Field $_field
	 * @param $_cellSymbol
	 *
	 * @return String The updated cell string
	 */
	private function addHighLightLinesToCellString($_field, $_cellSymbol, $_highLightLineSymbol)
	{
		$cellSymbol = $_cellSymbol;

		if ($_field->x() == $this->highLightX)
		{
			if ($_field->x() >= 0) $cellSymbol = $_highLightLineSymbol . $cellSymbol;
			if ($_field->x() < $_field->parentBoard()->width() - 1) $cellSymbol .= $_highLightLineSymbol;
		}

		return $cellSymbol;
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
		if (! $_fields) return "";

		$rowOutputString = parent::getRowOutputString($_fields);

		// Get rows above and below the row
		$board = $_fields[0]->parentBoard();
		$y = $_fields[0]->y();

		if ($this->isHighLight)
		{
			if ($y == $this->highLightY && ($y > 0 || $y == $this->highLightY + 1))
			{
				$specialSymbolsAboveBelow = $this->getSpecialSymbols( $board->width(), $board->height(), $this->selectionCoordinates);

				$highLightRowString = $this->getHorizontalLineString($board->width(),  );
				$highLightRowString = $this->addSpecialSymbolsToBorderString($highLightRowString, $specialSymbolsAboveBelow, $this->additionalWidthPerRow);
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

                if ($_y == $_selectionCoordinates["A"]["y"]) $specialSymbols[$_selectionCoordinates["A"]["x"]] = "";
                else $specialSymbols[$_selectionCoordinates["A"]["x"]] = ;
            }
            else $leftBorderSymbol = "";

            $hasRightBorder = false;
            if ($_selectionCoordinates["B"]["x"] + 1 < $_board->width())
            {
                $hasRightBorder = true;

                if ($_y == $_selectionCoordinates["A"]["y"]) $specialSymbols[$_selectionCoordinates["B"]["x"] + $hasLeftBorder + 1] = "";
                else $specialSymbols[$_selectionCoordinates["B"]["x"] + $hasLeftBorder + 1] = "";
            }
            else $rightBorderSymbol = "";

            $startX = $_selectionCoordinates["A"]["x"] + $hasLeftBorder;
            $endX = $_selectionCoordinates["B"]["x"] + $hasLeftBorder + 1;
            for ($x = $startX; $x <= $endX - $hasRightBorder; $x++)
            {
                $specialSymbols[$x] = "";
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
}
