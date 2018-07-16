<?php
/**
 * @file
 * @version 0.1
 * @copyright 2018 CN-Consult GmbH
 * @author Yannick Lapp <yannick.lapp@cn-consult.eu>
 */

namespace Output\BoardPrinter\OutputBoard;
use GameOfLife\Coordinate;


/**
 * Class that stores a two dimensional list of symbols for cell and border symbols.
 */
class SymbolGrid
{
	/**
	 * The list of symbol rows
	 *
	 * @var String[][]
	 */
	protected $symbolRows;

	/**
	 * The width of the symbol grid
	 *
	 * @var int $width
	 */
	protected $width;

	/**
	 * The height of the symbol grid
	 *
	 * @var int $height
	 */
	protected $height;


	/**
	 * SymbolGrid constructor.
	 *
	 * @param int $_width The width of the symbol grid
	 * @param int $_height The height of the symbol grid
	 */
	public function __construct(int $_width, int $_height)
	{
		$this->width = $_width;
		$this->height = $_height;
		$this->initializeEmptyGrid();
	}


	public function symbolRows()
	{
		return $this->symbolRows;
	}


	protected function initializeEmptyGrid()
	{
		$this->symbolRows = array();
		for ($y = 0; $y < $this->height; $y++)
		{
			$this->symbolRows[$y] = array();
			for ($x = 0; $x < $this->width; $x++)
			{
				$this->symbolRows[$y][$x] = " ";
			}
		}
	}

	public function setSymbolAt(Coordinate $_position, String $_symbol)
	{
		$this->symbolRows[$_position->y()][$_position->x()] = $_symbol;
	}

	public function addSymbolRowAt(int $_y, array $_symbolRow)
	{
		if ($_y < 0 || $_y > $this->height - 1)
		{
			throw new \Exception("Symbol Row insert position exceeds symbol grid height.");
		}
		if (count($_symbolRow) > $this->width - 1)
		{
			throw new \Exception("Symbol Row exceeds symbol grid width.");
		}

		$this->symbolRows[$_y] = $_symbolRow;
	}

	public function reset()
	{
		$this->initializeEmptyGrid();
	}
}
