<?php
/**
 * @file
 * @version 0.1
 * @copyright 2018 CN-Consult GmbH
 * @author Yannick Lapp <yannick.lapp@cn-consult.eu>
 */

namespace Output\BoardPrinter;
use GameOfLife\Coordinate;

/**
 * Stores the row and border output strings of the board.
 */
class OutputBoard
{
    // Attributes

    /**
     * The board field symbols that represent the fields of the board
     *
     * @var String[][] $boardFieldsSymbols
     */
    private $boardFieldsSymbolRows;

    /**
     * The horizontal borders between each of the board field rows
     *
     * @var String[][] $horizontalInnerBorders
     */
    private $horizontalInnerBorders;

    /**
     * The vertical borders between each of the board field columns
     *
     * @var String[][] $verticalInnerBorders
     */
    private $verticalInnerBorders;


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
        $this->boardFieldsSymbolRows = array();
        $this->horizontalInnerBorders = array();
        $this->verticalInnerBorders = array();
    }


    public function addBoardFieldSymbolsRow(array $_boardFieldSymbolsRow)
    {
        $this->boardFieldsSymbolRows[] = $_boardFieldSymbolsRow;
    }

    public function addOuterBorderTop(array $_borderSymbols)
    {
        $this->addHorizontalBorderAbove(new Coordinate(0, 0), $_borderSymbols, "", "", "");
    }

    public function addOuterBorderBottom(array $_borderSymbols)
    {
        $this->addHorizontalBorderAbove(new Coordinate(0,   count($this->getRowStrings())), $_borderSymbols, "", "", "");
    }

    public function addOuterBorderLeft(array $_borderSymbols)
    {
        $this->addVerticalBorderLeftFrom(new Coordinate(0, 0), $_borderSymbols, "", "", "");
    }

    public function addOuterBorderRight(array $_borderSymbols)
    {
        $this->addVerticalBorderLeftFrom(new Coordinate(0, count($this->getRowStrings())), $_borderSymbols, "", "", "");
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
            $this->horizontalInnerBorders[$y][$x] = $newBorderSymbol;
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
        $rowStrings = array();

        $rowIds = array_merge(
            array_keys($this->boardFieldsSymbolRows),
            array_keys($this->horizontalInnerBorders),
            array_keys($this->verticalInnerBorders)
        );
        $rowIds = array_unique($rowIds);
        natsort($rowIds);

        $lowestRowId = array_shift($rowIds);
        $highestRowId = array_pop($rowIds);

        for ($y = $lowestRowId; $y <= $highestRowId; $y++)
        {
            if (isset($this->boardFieldsSymbolRows[$y]))
            {
                $boardFieldsSymbolRow = $this->boardFieldsSymbolRows[$y];
                $rowStrings[] = implode("", $boardFieldsSymbolRow);
            }

            if (isset($this->horizontalInnerBorders[$y]))
            {
                $horizontalInnerBorderString = "";
                $previousIndex = 0;
                foreach ($this->horizontalInnerBorders[$y] as $index => $borderSymbol)
                {
                    $horizontalInnerBorderString .= str_repeat(" ", $index - $previousIndex - 1) . $borderSymbol;
                    $previousIndex = $index;
                }

                $rowStrings[] = $horizontalInnerBorderString;
            }
        }

        return $rowStrings;
    }
}
