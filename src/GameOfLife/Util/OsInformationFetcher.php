<?php
/**
 * @file
 * @version 0.1
 * @copyright 2018 CN-Consult GmbH
 * @author Yannick Lapp <yannick.lapp@cn-consult.eu>
 */

namespace Util;

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
    private $osLinux = 0;

    /**
     * Id of the operating system type "Windows"
     *
     * @var int osWindows
     */
    private $osWindows = 1;

    /**
     * Id of the operating system type "Unknown" (everything that is neither Linux nor Windows)
     *
     * @var int osUnknown
     */
    private $osUnknown = 2;

    /**
     * The cached os type (Because the os type will not change during run time)
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
        $operatingSystemName = php_uname("s");

        // Detect the os type
        if (stristr($operatingSystemName, "linux")) $this->osType = $this->osLinux;
        elseif (stristr($operatingSystemName, "win")) $this->osType = $this->osWindows;
        else $this->osType = $this->osUnknown;
    }


    // Class Methods

    /**
     * Returns whether the current operating system is Linux.
     *
     * @return Bool True if the current operating system is Linux, false otherwise
     */
    public function isLinux(): Bool
    {
        return $this->osType == $this->osLinux;
    }

    /**
     * Returns whether the current operating system is Windows.
     *
     * @return Bool True if the current operating system is Windows, false otherwise
     */
    public function isWindows(): Bool
    {
        return $this->osType == $this->osWindows;
    }
}
