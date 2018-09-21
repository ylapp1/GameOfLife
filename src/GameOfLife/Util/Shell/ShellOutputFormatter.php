<?php
/**
 * @file
 * @version 0.1
 * @copyright 2018 CN-Consult GmbH
 * @author Yannick Lapp <yannick.lapp@cn-consult.eu>
 */

namespace Util\Shell;

/**
 * Provides methods to format shell outputs.
 */
class ShellOutputFormatter
{
	// Attributes

	/**
	 * The cached number of shell columns
	 *
	 * @var int $cachedNumberOfShellColumns
	 */
	private $cachedNumberOfShellColumns;

	/**
	 * The cached number of shell lines
	 *
	 * @var int $cachedNumberOfShellLines
	 */
	private $cachedNumberOfShellLines;

	/**
	 * The shell cursor mover
	 *
	 * @var ShellCursorMover $shellCursorMover
	 */
	private $shellCursorMover;


	// Magic Methods

	/**
	 * ShellOutputFormatter constructor.
	 */
	public function __construct()
	{
		$shellInformationFetcher = new ShellInformationFetcher();
		$this->cachedNumberOfShellColumns = $shellInformationFetcher->getNumberOfShellColumns();
		$this->cachedNumberOfShellLines = $shellInformationFetcher->getNumberOfShellLines();

		$this->shellCursorMover = new ShellCursorMover();
	}


	// Class Methods

	/**
	 * Clears the console screen.
	 * This is achieved by filling the current window with empty lines and moving the cursor back to the top left corner.
	 */
	public function clearScreen()
	{
		echo str_repeat("\n", $this->cachedNumberOfShellLines);
		$this->shellCursorMover->moveCursorToTopLeftCorner();

		/*
		 * A pure php way to clear the screen in Windows would be to add 10000 new lines at the beginning of
		 * the simulation in order to move the scroll bar in cmd to the bottom.
		 * Then with each clear screen call one screen would be filled with empty lines in order to
		 * move the previous board away from the visible output.
		 *
		 * This variant however behaves exactly like the Linux version and does not fill the output buffer with
		 * unnecessary lines.
		 */
	}


	// Centered output strings

	/**
	 * Returns a centered output string for a single row output string relative to the number of shell columns.
	 * The row output string may not contain line breaks.
	 *
	 * @param String $_rowOutputString The row output string
	 *
	 * @return String The centered output row string
	 */
	private function getCenteredOutputString(String $_rowOutputString): String
	{
		$paddingLeft = floor(($this->cachedNumberOfShellColumns - mb_strlen($_rowOutputString)) / 2) + 1;

		$rowOutputString = $_rowOutputString;
		if ($paddingLeft > 0) $rowOutputString = str_repeat(" ", $paddingLeft) . $rowOutputString;

		return $rowOutputString;
	}

	/**
	 * Prints a centered output string.
	 * If the output string contains line breaks, each line will be centered separately.
	 *
	 * @param String $_outputString The output string
	 */
	public function printCenteredOutputString(String $_outputString)
	{
		$lines = explode("\n", $_outputString);
		foreach ($lines as $line)
		{
			if ($line) echo $this->getCenteredOutputString($line) . "\n";
			else echo "\n";
		}
	}
}
