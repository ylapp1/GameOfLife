<?php
/**
 * @file
 * @version 0.1
 * @copyright 2018 CN-Consult GmbH
 * @author Yannick Lapp <yannick.lapp@cn-consult.eu>
 */

namespace Output\BoardPrinter\OutputBoard\SymbolGrid;

use Output\BoardPrinter\OutputBoard\OutputBorder\OutputBorder;

/**
 * Container to store border symbols.
 */
class BorderSymbolGrid extends SymbolGrid
{
	/**
	 * The list of borders
	 *
	 * @var OutputBorder[] $borders
	 */
	private $borders;


	public function __construct()
	{
		parent::__construct();
		$this->borders = array();
	}


	public function addBorder(OutputBorder $_border)
	{
		foreach ($this->borders as $border)
		{
			$border->collideWith($_border);
		}

		$this->borders[] = $_border;
	}

	public function resetBorders()
	{
		$this->borders = array();
	}

	public function drawBorders()
	{
		// TODO: Draw the borders
	}

	public function removeEmptyColumns()
	{
		for ($x = 0; $x < $this->width; $x++)
		{
			$columnIsEmpty = true;
			foreach ($this->symbolRows as $symbolRow)
			{
				if ($symbolRow[$x] != "")
				{
					$columnIsEmpty = false;
					break;
				}
			}

			if ($columnIsEmpty)
			{
				foreach ($this->symbolRows as $y => $symbolRow)
				{
					unset($this->symbolRows[$y][$x]);
				}
			}
		}



		// TODO: Remove (Horizontal border)
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



		// TODO: Remove (Vertical border)
		foreach ($_borderSymbols as $index => $borderSymbol)
		{
			$coordinate = clone $_coordinate;
			$coordinate->setY($coordinate->y() + $index);

			$this->addHorizontalBorderAbove($_coordinate, array($borderSymbol), $_collisionTopSymbol, $_collisionCenterSymbol, $_collisionBottomSymbol);
		}



		// Build grid todo
		foreach ($this->borders as $border)
		{
			$border->addBorderSymbolsToBorderSymbolGrid($this->borderSymbolGrid);
		}
	}
}
