<?php
/**
 * @file
 * @version 0.1
 * @copyright 2018 CN-Consult GmbH
 * @author Yannick Lapp <yannick.lapp@cn-consult.eu>
 */

namespace Util;

use Util\FileSystem\FileSystemReader;

/**
 * Loads and instantiates classes from a specific directory.
 */
class ClassLoader
{
    // Attributes

    /**
     * The file system reader
     *
     * @var FileSystemReader $fileSystemReader
     */
    private $fileSystemReader;


    // Magic Methods

    /**
     * ClassLoader constructor.
     */
    public function __construct()
    {
        $this->fileSystemReader = new FileSystemReader();
    }


    // Class Methods

    /**
     * Loads all classes from a specific directory and returns a list of instances of the classes.
     *
     * @param String $_directoryPath The path to the directory from which the classes will be loaded
     * @param String $_searchPattern The pattern of the file names that will be loaded
     * @param String[] $_excludedClassNames The list of class names that will not be loaded
     * @param String $_nameSpace The namespace for all of the classes
     *
     * @return mixed[] The instances of the loaded classes
     */
    public function loadClasses(String $_directoryPath, String $_searchPattern, array $_excludedClassNames, String $_nameSpace): array
    {
        try
        {
            $classPaths = $this->fileSystemReader->getFileList($_directoryPath, $_searchPattern);
        }
        catch (\Exception $_exception)
        {
            $classPaths = array();
        }

        $classInstances = array();
        foreach ($classPaths as $classPath)
        {
            $className = basename($classPath, ".php");
            if (! in_array($className, $_excludedClassNames))
            {
                $classIncludePath = $_nameSpace . $className;
                $classInstances[] = new $classIncludePath;
            }
        }

        return $classInstances;
    }
}
