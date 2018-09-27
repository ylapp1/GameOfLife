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
	 * The shell information fetcher
	 *
	 * @var ShellInformationFetcher $shellInformationFetcher
	 */
	private $shellInformationFetcher;

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
		$this->shellInformationFetcher = new ShellInformationFetcher();
		$this->shellCursorMover = new ShellCursorMover();
	}


	// Class Methods

	/**
	 * Clears the console screen.
	 * This is achieved by filling the current window with empty lines and moving the cursor back to the top left corner.
	 *
	 * Another way to clear the screen would be to add 10000 new lines at the beginning of the simulation in order to
	 * move the scroll bar of the terminal to the bottom. Then with each clear screen call one screen would be
	 * filled with empty lines in order to move the previous board away from the visible output.
	 */
	public function clearScreen()
	{
		echo str_repeat("\n", $this->shellInformationFetcher->getNumberOfShellLines());
		$this->shellCursorMover->moveCursorToTopLeftCorner();
	}


	// Centered output strings

	/**
	 * Returns a centered output string for a single row output string relative to the number of shell columns.
	 * The row output string may not contain line breaks.
	 *
	 * @param String $_rowOutputString The row output string
	 *
	 * @return String The centered row output string
	 */
	private function getCenteredOutputLine(String $_rowOutputString): String
	{
		$paddingLeft = floor(($this->shellInformationFetcher->getNumberOfShellColumns() - mb_strlen($_rowOutputString)) / 2) + 1;

		if ($paddingLeft > 0) $rowOutputString = str_repeat(" ", $paddingLeft) . $_rowOutputString;
		else $rowOutputString = $_rowOutputString;

		return $rowOutputString;
	}

	/**
	 * Creates and returns a centered output string.
	 * If the output string contains line breaks, each line will be centered separately.
	 *
	 * @param String $_outputString The output string
	 *
	 * @return String The centered output string
	 */
	public function getCenteredOutputString(String $_outputString): String
	{
		$lines = explode("\n", $_outputString);

		$centeredOutputString = "";
		foreach ($lines as $line)
		{
			if ($line) $centeredOutputString .= $this->getCenteredOutputLine($line);
			$centeredOutputString .= "\n";
		}

		return $centeredOutputString;
	}
}
