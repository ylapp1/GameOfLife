<?php
/**
 * @file
 * @version 0.1
 * @copyright 2018 CN-Consult GmbH
 * @author Yannick Lapp <yannick.lapp@cn-consult.eu>
 */

namespace GameOfLife\OptionHandler;

use GameOfLife\Board;
use Input\BaseInput;
use Input\RandomInput;
use Output\BaseOutput;
use Output\ConsoleOutput;
use Rule\ConwayRule;
use Ulrichsg\Getopt;

/**
 * Parses the options.
 * Can only be used after $options->parse() was called.
 */
class OptionParser
{
    /**
     * The parent option handler
     *
     * @var OptionHandler $parentOptionHandler
     */
    private $parentOptionHandler;


    /**
     * OptionParser constructor.
     *
     * @param OptionHandler $_parentOptionHandler The parent option handler
     */
    public function __construct(OptionHandler $_parentOptionHandler)
    {
        $this->parentOptionHandler = $_parentOptionHandler;
    }


    /**
     * Parses the general options which will prevent the simulation from starting.
     *
     * @param Getopt $_options The option list
     *
     * @return Bool True: A general option was used
     *              False: No general option was used
     */
    public function parseGeneralOptions(Getopt $_options): Bool
    {
        if ($_options->getOption("version") !== null)
        {
            echo "Game of life version 0.1\n";
            return true;
        }
        elseif ($_options->getOption("help") !== null)
        {
            echo "\n";
            $_options->showHelp();
            return true;
        }

        return false;
    }

    /**
     * Parses the board options and returns the Board object or false if an error occurred.
     *
     * @param Getopt $_options The option list
     *
     * @return Board|bool The board or false
     */
    public function parseBoardOptions(Getopt $_options)
    {
        // Fetch board options
        if ($_options->getOption("width") !== null) $width = (int)$_options->getOption("width");
        else $width = 20;

        if ($_options->getOption("height") !== null) $height = (int)$_options->getOption("height");
        else $height = 10;

        if ($_options->getOption("maxSteps") !== null) $maxSteps = (int)$_options->getOption("maxSteps");
        else $maxSteps = 50;

        if ($_options->getOption("border") !== null)
        {
            $borderType = $_options->getOption("border");
            if ($borderType == "solid") $hasBorder = true;
            elseif ($borderType == "passthrough") $hasBorder = false;
            else
            {
                echo "Error: Invalid border type specified";
                return false;
            }
        }
        else $hasBorder = true;

        return new Board($width, $height, $maxSteps, $hasBorder);
    }

    /**
     * Parses the input options and returns the Input object.
     *
     * @param Getopt $_options The option list
     *
     * @return BaseInput $input The input object
     */
    public function parseInputOptions(Getopt $_options)
    {
        $input = new RandomInput();

        if ($_options->getOption("input") !== null)
        {
            $className = strtolower($_options->getOption("input")) . "Input";
            $classPath = "Input\\" . ucfirst($className);

            if (class_exists($classPath) &&
                ! in_array($className, $this->parentOptionHandler->excludeClasses()))
            {
                $input = new $classPath;
            }
        }

        // check whether any linked option (input specific option) is set
        foreach ($this->parentOptionHandler->linkedOptions() as $option => $className)
        {
            // if input specific option is set initialize new input of the class which the input refers to
            if (stristr($className, "Input") && $_options->getOption($option) !== null)
            {
                $input = new $className;
            }
        }

        return $input;
    }

    /**
     * Parses the output options and returns the Output object.
     *
     * @param Getopt $_options The option list
     *
     * @return BaseOutput The output object
     */
    public function parseOutputOptions(Getopt $_options)
    {
        $output = new ConsoleOutput();

        if ($_options->getOption("output") !== null)
        {
            $className = strtolower($_options->getOption("output")) . "Output";
            $classPath = "Output\\" . ucfirst($className);

            if (class_exists($classPath) &&
                ! in_array($className, $this->parentOptionHandler->excludeClasses()))
            {
                $output = new $classPath;
            }
        }

        foreach ($this->parentOptionHandler->linkedOptions() as $option => $className)
        {
            // if input specific option is set initialize new input of the class which the input refers to
            if (stristr($className, "Output") && $_options->getOption($option) !== null)
            {
                $output = new $className;
            }
        }

        return $output;
    }

    /**
     * Parses the rule options and returns the Rule object.
     *
     * @param Getopt $_options The option list
     *
     * @return ConwayRule
     */
    public function parseRuleOptions(Getopt $_options)
    {
        $rule = new ConwayRule();

        if ($_options->getOption("rules") !== null)
        {
            $className = strtolower($_options->getOption("rules")) . "Rule";
            $classPath = "Rule\\" . ucfirst($className);

            if (class_exists($classPath) &&
                ! in_array($className, $this->parentOptionHandler->excludeClasses()))
            {
                $rule = new $classPath;
            }
        }

        foreach ($this->parentOptionHandler->linkedOptions() as $option => $className)
        {
            // if input specific option is set initialize new input of the class which the input refers to
            if (stristr($className, "Rule") && $_options->getOption($option) !== null)
            {
                $rule = new $className;
            }
        }

        return $rule;
    }
}
