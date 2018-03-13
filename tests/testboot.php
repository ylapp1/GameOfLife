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
    $loader->addPsr4("BoardEditor\\", __DIR__ . "/../src/GameOfLife/Inputs/BoardEditor");
    $loader->addPsr4("GameOfLife\\", __DIR__ . "/../src/GameOfLife");
    $loader->addPsr4("GIFEncoder\\", __DIR__ . "/../src/GIFEncoder");
    $loader->addPsr4("Input\\", __DIR__ . "/../src/GameOfLife/Inputs");
    $loader->addPsr4("OptionHandler\\", __DIR__ . "/../src/GameOfLife/OptionHandler");
    $loader->addPsr4("Output\\", __DIR__ . "/../src/GameOfLife/Outputs");
    $loader->addPsr4("Rule\\", __DIR__ . "/../src/GameOfLife/Rules");
    $loader->addPsr4("TemplateHandler\\", __DIR__ . "/../src/GameOfLife/Inputs/TemplateHandler");
    $loader->addPsr4("Ulrichsg\\", __DIR__ . "/../src/Ulrichsg");
    $loader->addPsr4("Utils\\", __DIR__ . "/../src/GameOfLife/Utils");
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
