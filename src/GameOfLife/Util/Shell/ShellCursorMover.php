<?php
/**
 * @file
 * @version 0.1
 * @copyright 2018 CN-Consult GmbH
 * @author Yannick Lapp <yannick.lapp@cn-consult.eu>
 */

namespace Util\Shell;

use Util\OsInformationFetcher;

/**
 * Provides methods to move the console cursor.
 */
class ShellCursorMover
{
	// Attributes

	/**
	 * The cached number of shell lines
	 *
	 * @var int $cachedNumberOfShellLines
	 */
	private $cachedNumberOfShellLines;

	/**
	 * The os information fetcher
	 *
	 * @var OsInformationFetcher $osInformationFetcher
	 */
	private $osInformationFetcher;

	/**
	 * The shell executor
	 *
	 * @var ShellExecutor $shellExecutor
	 */
	private $shellExecutor;


	// Magic Methods

	/**
	 * ShellCursorMover constructor.
	 */
	public function __construct()
	{
		$shellInformationFetcher = new ShellInformationFetcher();
		$this->cachedNumberOfShellLines = $shellInformationFetcher->getNumberOfShellLines();

		$this->osInformationFetcher = new OsInformationFetcher();
		$this->shellExecutor = new ShellExecutor();
	}


	// Class Methods

	/**
	 * Sets the cursor to a specific position relative to the current window dimensions.
	 *
	 * @param int $_x The new X-Position of the cursor
	 * @param int $_y The new Y-Position of the cursor
	 */
	private function setRelativeCursorPosition(int $_x, int $_y)
	{
		if ($this->osInformationFetcher->isLinux()) echo "\e[" . $_y . ";" . $_x . "H";
		elseif ($this->osInformationFetcher->isWindows())
		{
			$this->shellExecutor->executeCommand(__DIR__ . "\\ConsoleHelper.exe setCursor " . $_x . " " . $_y);
		}
	}

	/**
	 * Moves the cursor to the top left corner of the shell window.
	 */
	public function moveCursorToTopLeftCorner()
	{
		$this->setRelativeCursorPosition(0, 1);
	}

	/**
	 * Moves the cursor to the bottom left corner of the shell window.
	 */
	public function moveCursorToBottomLeftCorner()
	{
		$bottomLineNumber = $this->cachedNumberOfShellLines;
		if ($this->osInformationFetcher->isWindows()) $bottomLineNumber -= 1;

		$this->setRelativeCursorPosition(0, $bottomLineNumber);
	}
}
