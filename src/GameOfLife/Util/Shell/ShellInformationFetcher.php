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
 * Fetches information about the shell in which the script is executed.
 */
class ShellInformationFetcher
{
    // Attributes

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

	/**
	 * The cached number of shell lines
	 *
	 * @var int $cachedNumberOfShellLines
	 */
    static $cachedNumberOfShellLines;

	/**
	 * The cached number of shell columns
	 *
	 * @var int $cachedNumberOfShellColumns
	 */
    static $cachedNumberOfShellColumns;


    // Magic Methods

    /**
     * ShellInformationFetcher constructor.
     */
    public function __construct()
    {
        $this->osInformationFetcher = new OsInformationFetcher();
        $this->shellExecutor = new ShellExecutor();
    }


    // Class Methods

    /**
     * Returns the number of shell lines.
     * The number of shell lines is only fetched once, after that the cached number of shell lines will be returned.
     *
     * @param Bool $_alwaysFetchData If true, the number of shell lines will be fetched even if the cached number of shell lines is set
     *
     * @return int The number of shell lines
     */
    public function getNumberOfShellLines(Bool $_alwaysFetchData = false): int
    {
    	if (! $this::$cachedNumberOfShellLines || $_alwaysFetchData)
	    {
		    /*
		     * 29 lines is the default number of lines in cmd
		     * 50 lines is the default number of lines in PowerShell
		     */
		    $numberOfLines = 29;

		    if ($this->osInformationFetcher->isWindows())
		    {
			    $this->shellExecutor->executeCommand("powershell -command \"&{\$H=get-host;\$H.ui.rawui.windowsize.height;}\"");
			    $numberOfLines = (int)$this->shellExecutor->output()[0];
		    }
		    elseif ($this->osInformationFetcher->isLinux())
		    {
			    /*
				 * Using "stty" instead of "tput" here because "tput" will display a wrong value
				 * when redirecting stderr to stdout
				 */
			    $returnValue = $this->shellExecutor->executeCommand("stty size");
			    if ($returnValue === 0)
			    {
				    $dimensionsString = $this->shellExecutor->output()[0];
				    $dimensions = explode(" ", $dimensionsString);
				    $numberOfLines = (int)$dimensions[0];
			    }
		    }

		    $this::$cachedNumberOfShellLines = $numberOfLines;
	    }

        return $this::$cachedNumberOfShellLines;
    }

    /**
     * Returns the number of shell columns.
     * The number of shell columns is only fetched once, after that the cached number of shell columns will be returned.
     *
     * @param Bool $_alwaysFetchData If true the number of shell columns will be fetched even if the cached number of shell columns is set
     *
     * @return int The number of shell columns
     */
    public function getNumberOfShellColumns(Bool $_alwaysFetchData = false): int
    {
    	if (! $this::$cachedNumberOfShellColumns || $_alwaysFetchData)
	    {
		    // 120 is the default number of columns in cmd and PowerShell
		    $numberOfColumns = 120;

		    if ($this->osInformationFetcher->isWindows())
		    {
			    $this->shellExecutor->executeCommand("powershell -command \"&{\$H=get-host;\$H.ui.rawui.windowsize.width;}\"");
			    $numberOfColumns = (int)$this->shellExecutor->output()[0];
		    }
		    elseif ($this->osInformationFetcher->isLinux())
		    {
			    /*
				 * Using "stty" instead of "tput" here because "tput" will display a wrong value
				 * when redirecting stderr to stdout
				 */
			    $returnValue = $this->shellExecutor->executeCommand("stty size");
			    if ($returnValue === 0)
			    {
				    $dimensionsString = $this->shellExecutor->output()[0];
				    $dimensions = explode(" ", $dimensionsString);
				    $numberOfColumns = (int)$dimensions[1];
			    }
		    }

		    $this::$cachedNumberOfShellColumns = $numberOfColumns;
	    }

        return $this::$cachedNumberOfShellColumns;
    }
}
