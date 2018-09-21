<?php
/**
 * @file
 * @version 0.1
 * @copyright 2017-2018 CN-Consult GmbH
 * @author Yannick Lapp <yannick.lapp@cn-consult.eu>
 */

namespace
{
    $loader = require __DIR__ . "/../vendor/autoload.php";
    $loader->addPsr4("BoardEditor\\", __DIR__ . "/../src/GameOfLife/BoardEditor");
    $loader->addPsr4("BoardRenderer\\", __DIR__ . "/../src/GameOfLife/BoardRenderer");
    $loader->addPsr4("Input\\", __DIR__ . "/../src/GameOfLife/Inputs");
    $loader->addPsr4("OptionHandler\\", __DIR__ . "/../src/GameOfLife/OptionHandler");
    $loader->addPsr4("Output\\", __DIR__ . "/../src/GameOfLife/Outputs");
    $loader->addPsr4("Rule\\", __DIR__ . "/../src/GameOfLife/Rules");
    $loader->addPsr4("Simulator\\", __DIR__ . "/../src/GameOfLife/Simulator");
    $loader->addPsr4("TemplateHandler\\", __DIR__ . "/../src/GameOfLife/TemplateHandler");
    $loader->addPsr4("Utils\\", __DIR__ . "/../src/GameOfLife/Utils");

    /**
     * Returns a ReflectionProperty for the class of $_object.
     * Also sets the attribute $_attributeName accessible
     *
     * @param mixed $_object The object
     * @param String $_attributeName The name of the attribute
     *
     * @return ReflectionProperty The reflection property
     *
     * @throws ReflectionException
     */
    function getReflectionProperty($_object, $_attributeName)
    {
        $reflectionClass = new ReflectionClass($_object);

        $reflectionProperty = $reflectionClass->getProperty($_attributeName);
        $reflectionProperty->setAccessible(true);

        return $reflectionProperty;
    }

    /**
     * Sets a private attribute of an object.
     *
     * @param mixed $_object The object
     * @param String $_attributeName The attribute name
     * @param mixed $_value The value
     *
     * @throws ReflectionException
     */
    function setPrivateAttribute($_object, String $_attributeName, $_value)
    {
        $reflectionProperty = getReflectionProperty($_object, $_attributeName);
        $reflectionProperty->setValue($_object, $_value);
    }

    /**
     * Returns a private attribute of an object.
     *
     * @param mixed $_object The object
     * @param String $_attributeName The attribute name
     *
     * @return mixed The attribute value
     *
     * @throws ReflectionException
     */
    function getPrivateAttribute($_object, String $_attributeName)
    {
        $reflectionProperty = getReflectionProperty($_object, $_attributeName);
        return $reflectionProperty->getValue($_object);
    }
}

namespace Utils
{
    /**
     * Overridden exec function for the ShellExecutor unit test.
     * Writes the command to the returnValue.
     *
     * @param String $_command The command
     * @param array $_output The list of output lines
     * @param int $_returnValue The return value
     */
    function exec(String $_command, array &$_output, int &$_returnValue)
    {
        if (stristr($_command, "ffmpeg"))
        {
            // Return 1 when ffmpeg is called in order to not break the VideoOutputTest
            $_returnValue = 1;
        }
        else $_returnValue = $_command;
    }

    /**
     * Overriden system function for the ShellExecutor unit test.
     *
     * @param String $_command The command
     */
    function system(String $_command)
    {
        echo $_command;
    }
}
