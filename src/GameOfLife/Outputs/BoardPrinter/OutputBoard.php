<?php
/**
 * @file
 * @version 0.1
 * @copyright 2018 CN-Consult GmbH
 * @author Yannick Lapp <yannick.lapp@cn-consult.eu>
 */

namespace Output\BoardPrinter;
use GameOfLife\Coordinate;
use Output\BoardPrinter\OutputBoard\OutputBorder;
use Output\BoardPrinter\OutputBoard\SymbolGrid;

/**
 * Stores the row and border output strings of the board.
 */
class OutputBoard
{
    // Attributes

	/**
	 * The cell symbol grid
	 *
	 * @var SymbolGrid $cellSymbolGrid
	 */
	private $cellSymbolGrid;

	/**
	 * The border symbol grid
	 *
	 * @var SymbolGrid $borderSymbolGrid
	 */
	private $borderSymbolGrid;

	/**
	 * The inner and outer borders
	 *
	 * @var OutputBorder[] $borders
	 */
    private $borders;


    // Magic Methods

    /**
     * OutputBoard constructor.
     */
    public function __construct()
    {
        $this->reset();
    }



    // Class Methods

    public function reset()
    {
        $this->cellSymbolGrid->reset();
        $this->borderSymbolGrid->reset();
        $this->borders = array();
    }


    public function addBoardFieldSymbolsRow(array $_boardFieldSymbolsRow)
    {
        $this->boardFieldsSymbolRows[] = $_boardFieldSymbolsRow;
    }

    public function addBorder(OutputBorder $_border)
    {
    	foreach ($this->borders as $border)
	    {
	    	$border->collideWith($_border);
	    }

    	$this->borders[] = $_border;
    }

    /**
     * Adds a horizontal border above a specific coordinate.
     *
     * @param Coordinate $_coordinate The coordinate
     * @param String[] $_borderSymbols The border symbols
     * @param String $_collisionLeftSymbol The collision symbol for collisions at the left edge of the border
     * @param String $_collisionCenterSymbol The collision symbol for collisions in the center of the border
     * @param String $_collisionRightSymbol THe collision symbol for collisions at the right edge of the border
     */
    public function addHorizontalBorderAbove(Coordinate $_coordinate, array $_borderSymbols, String $_collisionLeftSymbol, String $_collisionCenterSymbol, String $_collisionRightSymbol)
    {
        $y = $_coordinate->y() - 1;
        $borderWidth = count($_borderSymbols);

        if (! isset($this->horizontalInnerBorders[$y])) $this->horizontalInnerBorders[$y] = array();

        foreach ($_borderSymbols as $index => $borderSymbol)
        {
            $x = $_coordinate->x() + $index;

            if (isset($this->horizontalInnerBorders[$y][$x]))
            {
                if ($index == 0) $newBorderSymbol = $_collisionLeftSymbol;
                elseif ($index == $borderWidth) $newBorderSymbol = $_collisionRightSymbol;
                else $newBorderSymbol = $_collisionCenterSymbol;
            }
            else $newBorderSymbol = $borderSymbol;

            $this->borderSymbolGrid->setSymbolAt(new Coordinate($x, $y), $newBorderSymbol);
        }
    }

    public function addVerticalBorderLeftFrom(Coordinate $_coordinate, array $_borderSymbols,  String $_collisionTopSymbol, String $_collisionCenterSymbol, String $_collisionBottomSymbol)
    {
        foreach ($_borderSymbols as $index => $borderSymbol)
        {
            $coordinate = clone $_coordinate;
            $coordinate->setY($coordinate->y() + $index);

            $this->addHorizontalBorderAbove($_coordinate, array($borderSymbol), $_collisionTopSymbol, $_collisionCenterSymbol, $_collisionBottomSymbol);
        }
    }

    public function getRowStrings(): array
    {
    	$this->buildBorderGrid();
        $rowStrings = array();

        foreach ($this->borderSymbolGrid->symbolRows() as $y => $symbolRow)
        {
	        $rowString = "";
	        foreach ($this->cellSymbolGrid->symbolRows()[$y] as $x => $borderSymbol)
	        {
	        	if ($borderSymbol == "") continue;
		        $rowString .= $borderSymbol;
	        }

	        $rowStrings[] = $rowString;

        	$rowString = "";
        	foreach ($symbolRow as $x => $cellSymbol)
	        {
	        	$rowString .= $this->borderSymbolGrid->symbolRows()[$y][$x];
	        	$rowString .= $cellSymbol;
	        }

	        $rowStrings[] = $rowString;
        }

        return $rowStrings;
    }

    private function buildBorderGrid()
    {
	    $this->borderSymbolGrid->reset();
	    foreach ($this->borders as $border)
	    {
		    $border->addBorderSymbolsToBorderSymbolGrid($this->borderSymbolGrid);
	    }
    }
}
