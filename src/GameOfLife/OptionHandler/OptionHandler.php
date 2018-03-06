<?php
/**
 * @file
 * @version 0.1
 * @copyright 2018 CN-Consult GmbH
 * @author Yannick Lapp <yannick.lapp@cn-consult.eu>
 */

namespace GameOfLife\OptionHandler;

use Ulrichsg\Getopt;

/**
 * Handles listing and parsing of command line options.
 */
class OptionHandler
{
    /**
     * The list of option names grouped by the corresponding class name
     *
     * @var array $linkedOptions
     */
    private $linkedOptions;

    /**
     * The option loader
     *
     * @var OptionLoader $optionLoader
     */
    private $optionLoader;

    /**
     * The option parser
     *
     * @var OptionParser $optionParser
     */
    private $optionParser;

    /**
     * The classes whose options will not be loaded
     *
     * @var String[] $excludeClasses
     */
    private $excludeClasses = array(
        // Input classes
        "BaseInput", "ObjectInput",

        // Output classes
        "BaseOutput", "BoardEditorOutput", "ImageOutput",

        // Rule classes
        "BaseRule"
    );


    /**
     * OptionHandler constructor.
     */
    public function __construct()
    {
        $this->linkedOptions = array();
        $this->optionLoader = new OptionLoader();
        $this->optionParser = new OptionParser($this);
    }


    /**
     * Returns the list of linked options.
     *
     * @return array The list of linked options
     */
    public function linkedOptions()
    {
        return $this->linkedOptions;
    }

    /**
     * Returns the list of excluded classes.
     *
     * @return String[] The list of excluded classes
     */
    public function excludeClasses()
    {
        return $this->excludeClasses;
    }

    /**
     * Returns the option parser.
     *
     * @return OptionParser The option parser
     */
    public function optionParser()
    {
        return $this->optionParser;
    }

    /**
     * Initializes the command line options.
     *
     * @param Getopt $_options The option list
     */
    public function initializeOptions(Getopt $_options)
    {
        $this->optionLoader->addDefaultOptions($_options);

        $inputClasses = glob(__DIR__ . "/src/GameOfLife/Inputs/*Input.php");
        $this->optionLoader->addClassOptions($_options, $inputClasses, $this->excludeClasses, "Input");

        $outputClasses = glob(__DIR__ . "/src/GameOfLife/Outputs/*Output.php");
        $this->optionLoader->addClassOptions($_options, $outputClasses, $this->excludeClasses, "Output");

        $ruleClasses = glob(__DIR__ . "/src/GameOfLife/Rules/*Rule.php");
        $this->optionLoader->addClassOptions($_options, $ruleClasses, $this->excludeClasses, "Rule");
    }
}
