<?php
/**
 * @file
 * @version 0.1
 * @copyright 2018 CN-Consult GmbH
 * @author Yannick Lapp <yannick.lapp@cn-consult.eu>
 */

namespace Output\BoardPrinter\OutputBoard;


class BorderSymbolGrid extends SymbolGrid
{
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
	}
}
