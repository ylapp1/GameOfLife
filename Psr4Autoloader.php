<?php
/**
 * @file
 * @version 0.1
 * @copyright 2017 CN-Consult GmbH
 * @author Yannick Lapp <yannick.lapp@cn-consult.eu>
 */

/**
 * Class Psr4Autoloader
 */
class Psr4Autoloader
{
    private $prefixes = array();


    /**
     * register all prefixes that are saved in the object
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
     * add a namespace to the list
     *
     * @param string $_prefix      namespace prefix
     * @param string $_baseDir     filepath prefix
     */
    public function addNamespace($_prefix, $_baseDir)
    {
        if (isset($this->prefixes[$_prefix]) === false)
        {
            $this->prefixes[$_prefix] = $_baseDir;
        }
    }
}