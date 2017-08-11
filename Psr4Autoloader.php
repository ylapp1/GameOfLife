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


    public function register()
    {
        spl_autoload_register(
            function ($_class)
            {
                foreach ($this->prefixes as $prefix=>$baseDirectory)
                {
                    // check whether class uses the namespace prefix
                    $len = strlen($prefix);

                    if (strncmp($prefix, $_class, $len) !== 0)
                    {
                        return;
                    }

                    $relativeClass = substr($_class, $len);

                    $file = $baseDirectory . str_replace('\\', '/', $relativeClass) . '.php';

                    if (file_exists($file))
                    {
                        require_once $file;
                    }
                }
            }
        );
    }


    public function addNamespace($_prefix, $_base_dir)
    {
        // initialize the namespace prefix array
        if (isset($this->prefixes[$_prefix]) === false)
        {
            $this->prefixes[$_prefix] = array();
        }

        $this->prefixes[$_prefix] = $_base_dir;
    }
}