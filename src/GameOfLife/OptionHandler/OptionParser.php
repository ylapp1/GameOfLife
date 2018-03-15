<?php
/**
 * @file
 * @version 0.1
 * @copyright 2018 CN-Consult GmbH
 * @author Yannick Lapp <yannick.lapp@cn-consult.eu>
 */

namespace OptionHandler;

use GameOfLife\Board;
use Input\BaseInput;
use Input\RandomInput;
use Input\TemplateInput;
use Output\BaseOutput;
use Output\ConsoleOutput;
use Rule\BaseRule;
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
     * @return BaseInput The input object
     */
    public function parseInputOptions(Getopt $_options): BaseInput
    {
        $input = $this->parseOptions($_options, "input", "Input", "Input");
        if ($input) return $input;
        else return new TemplateInput();
    }

    /**
     * Parses the output options and returns the Output object.
     *
     * @param Getopt $_options The option list
     *
     * @return BaseOutput The output object
     */
    public function parseOutputOptions(Getopt $_options): BaseOutput
    {
        $output = $this->parseOptions($_options, "output", "Output", "Output");
        if ($output) return $output;
        else return new ConsoleOutput();
    }

    /**
     * Parses the rule options and returns the Rule object.
     *
     * @param Getopt $_options The option list
     *
     * @return BaseRule The rule object
     */
    public function parseRuleOptions(Getopt $_options): BaseRule
    {
        $rule = $this->parseOptions($_options, "rules", "Rule", "Rule");
        if ($rule) return $rule;
        else return new ConwayRule();
    }

    /**
     * Parses the input, output and rule options.
     *
     * @param Getopt $_options The options list
     * @param String $_optionName The option name (input, output or rule)
     * @param String $_classNameSpace The class name space
     * @param String $_classSuffix The class suffix
     *
     * @return BaseInput|BaseOutput|BaseRule|Bool The class instance or false if no class was found from the options
     */
    private function parseOptions(Getopt $_options, String $_optionName, String $_classNameSpace, String $_classSuffix)
    {
        if ($_options->getOption($_optionName) !== null)
        {
            $className = ucfirst(strtolower($_options->getOption($_optionName))) . $_classSuffix;
            $classPath = $_classNameSpace . "\\" . $className;

            if (class_exists($classPath) &&
                ! in_array($className, $this->parentOptionHandler->excludeClasses()))
            {
                return new $classPath;
            }
        }

        // check whether any linked option (input/output/rule specific option) is set
        foreach ($this->parentOptionHandler->linkedOptions() as $option => $className)
        {
            // if input/output/rule specific option is set return new instance of the class which the input refers to
            if (stristr($className, $_classSuffix) && $_options->getOption($option) !== null)
            {
                return new $className;
            }
        }

        return false;
    }
}
