<?php
/**
 * @file
 * @version 0.1
 * @copyright 2017 CN-Consult GmbH
 * @author Yannick Lapp <yannick.lapp@cn-consult.eu>
 */

/**
 * Class Psr4Autoloader
 *
 * Automatically require class files in registered namespaces
 *
 * Usage:
 * - Create a new instance
 * - Use addNamespace($_prefix, $_baseDir) to add the projects namespaces to the list
 * - Use register() to register all of the previously added namespaces
 */
class Psr4Autoloader
{
    private $prefixes = array();

    /**
     * Registers all prefixes in the objects list of namespaces
     */
    public function register()
    {
        spl_autoload_register(
            function ($_class)
            {
                foreach ($this->prefixes as $prefix=>$baseDirectory)
                {
                    // check whether class uses one of the namespace prefixes
                    $len = strlen($prefix);

                    if (strncmp($prefix, $_class, $len) === 0)
                    {
                        $relativeClass = substr($_class, $len);
                        $file = $baseDirectory . str_replace('\\', '/', $relativeClass) . '.php';

                        if (file_exists($file)) require_once $file;
                    }
                }
            }
        );
    }

    /**
     * Adds a namespace to the objects list of namespaces
     *
     * @param string $_prefix      namespace prefix
     * @param string $_baseDir     filepath prefix
     */
    public function addNamespace($_prefix, $_baseDir)
    {
        if (isset($this->prefixes[$_prefix]) === false) $this->prefixes[$_prefix] = $_baseDir;
    }
}