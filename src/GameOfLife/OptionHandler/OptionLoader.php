<?php
/**
 * @file
 * @version 0.1
 * @copyright 2018 CN-Consult GmbH
 * @author Yannick Lapp <yannick.lapp@cn-consult.eu>
 */

namespace OptionHandler;

use Input\BaseInput;
use Output\BaseOutput;
use Rule\BaseRule;
use Ulrichsg\Getopt;

/**
 * Loads all options and returns the Getopt object.
 */
class OptionLoader
{
    /**
     * Adds the default options to the option list.
     *
     * @param Getopt $_options The option list
     */
    public function addDefaultOptions(Getopt $_options)
    {
        $_options->addOptions(
            array(
                // board options
                array(null, "width", Getopt::REQUIRED_ARGUMENT, "Set the board width (Default: 20)"),
                array(null, "height", Getopt::REQUIRED_ARGUMENT, "Set the board height (Default: 10)"),
                array(null, "maxSteps", Getopt::REQUIRED_ARGUMENT, "Set the maximum amount of steps that are calculated before the simulation stops (Default: 50)"),
                array(null, "border", Getopt::REQUIRED_ARGUMENT, "Set the border type (solid|passthrough) (Default: solid)"),
                array(null, "input", Getopt::REQUIRED_ARGUMENT, "Fill the board with cells (valid arguments: Blinker, Glider, Random, Spaceship)"),
                array(null, "output", Getopt::REQUIRED_ARGUMENT, "Set the output type (valid arguments: console, png)"),
                array(null, "rules", Getopt::REQUIRED_ARGUMENT, "Set the rules for the simulation (valid arguments: Conway, Copy, Two45) (Default: Conway)"),
                array(null, "antiRules", Getopt::NO_ARGUMENT, "Converts the selected rules to anti rules"),

                // other options
                array(null, "version", Getopt::NO_ARGUMENT, "Print script version"),
                array("h", "help", Getopt::NO_ARGUMENT)
            )
        );
    }

    /**
     * Adds the class specific options from Inputs, Outputs and Rules to the option list.
     *
     * @param Getopt $_options The option list
     * @param String[] $_classPaths The paths to the classes
     * @param String[] $_excludeClasses The classes whose options will not be added to the option list
     * @param String $_nameSpace The name space of the classes
     *
     * @return array List of which options refer to which class
     */
    public function addClassOptions(Getopt $_options, array $_classPaths, array $_excludeClasses, String $_nameSpace): array
    {
        $linkedOptions = array();

        foreach ($_classPaths as $classPath)
        {
            $className = basename($classPath, ".php");

            if (! in_array($className, $_excludeClasses))
            {
                $classIncludePath = $_nameSpace . "\\" . $className;

                // get options before class adds its options
                $previousOptions = $_options->getOptionList();

                /**
                 * @var BaseInput|BaseOutput|BaseRule $classInstance
                 */
                $classInstance = new $classIncludePath;
                $classInstance->addOptions($_options);

                // get options after the class added its options
                $newOptions = $_options->getOptionList();

                foreach ($this->getOptionsDiff($previousOptions, $newOptions) as $option)
                {
                    $optionName = $option[1];
                    $linkedOptions[$optionName] = $classIncludePath;
                }
            }
        }

        return $linkedOptions;
    }

    /**
     * Returns the options that are contained in $_newOptions and not in $_previousOptions.
     *
     * @param array $_previousOptions The previous options list
     * @param array $_newOptions The new options list
     *
     * @return array The options that are only contained in $_newOptions
     */
    private function getOptionsDiff(array $_previousOptions, array $_newOptions): array
    {
        $optionsDiff = array();

        // Cannot use array_diff because it doesn't work with multidimensional arrays
        foreach ($_newOptions as $newOption)
        {
            $isNewOption = true;

            foreach ($_previousOptions as $previousOption)
            {
                if ($previousOption == $newOption)
                {
                    $isNewOption = false;
                    break;
                }
            }

            if ($isNewOption) $optionsDiff[] = $newOption;
        }

        return $optionsDiff;
    }
}
