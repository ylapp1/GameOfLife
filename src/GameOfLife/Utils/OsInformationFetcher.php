<?php
/**
 * @file
 * @version 0.1
 * @copyright 2018 CN-Consult GmbH
 * @author Yannick Lapp <yannick.lapp@cn-consult.eu>
 */

namespace Utils;

/**
 * Fetches information about the operating system on that php currently runs.
 */
class OsInformationFetcher
{
    // Attributes

    /**
     * Id of the operating system type "Linux"
     *
     * @var int osLinux
     */
    const osLinux = 0;

    /**
     * Id of the operating system type "Windows"
     *
     * @var int osWindows
     */
    const osWindows = 1;

    /**
     * Id of the operating system type "Unknown" (everything that is neither Linux nor Windows)
     *
     * @var int osUnknown
     */
    const osUnknown = 2;

    /**
     * The cached os type (The os type will not change during run time)
     *
     * @var int $osType
     */
    private $osType;


    // Magic Methods

    /**
     * OsInformationFetcher constructor.
     */
    public function __construct()
    {
        // Detect the os type
        if (stristr(PHP_OS, "linux")) $this->osType = $this::osLinux;
        elseif (stristr(PHP_OS, "win")) $this->osType = $this::osWindows;
        else $this->osType = $this::osUnknown;
    }


    // Class Methods

    /**
     * Returns the id of the operating system on that php currently runs.
     *
     * @return int The id of the operating system on that php currently runs
     */
    public function getOsType(): int
    {
        return $this->osType;
    }

    /**
     * Returns whether the current operating system is Linux.
     *
     * @return Bool True: The current operating system is Linux
     *              False: The current operating system is not Linux
     */
    public function isLinux(): Bool
    {
        return $this->osType == $this::osLinux;
    }

    /**
     * Returns whether the current operating system is Windows.
     *
     * @return Bool True: The current operating system is Windows
     *              False: The current operating system is not Windows
     */
    public function isWindows(): Bool
    {
        return $this->osType == $this::osWindows;
    }
}
