<?php
/**
 * @file
 * @version 0.1
 * @copyright 2018 CN-Consult GmbH
 * @author Yannick Lapp <yannick.lapp@cn-consult.eu>
 */

namespace OptionHandler;

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
     * The list of class names whose options will not be loaded
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
    public function linkedOptions(): array
    {
        return $this->linkedOptions;
    }

    /**
     * Returns the option parser.
     *
     * @return OptionParser The option parser
     */
    public function optionParser(): OptionParser
    {
        return $this->optionParser;
    }

    /**
     * Returns the list of class names whose options will not be loaded.
     *
     * @return String[] The list of class names whose options will not be loaded
     */
    public function excludeClasses(): array
    {
        return $this->excludeClasses;
    }

    /**
     * Initializes the command line options.
     *
     * @param Getopt $_options The option list
     */
    public function initializeOptions(Getopt $_options)
    {
        $this->optionLoader->addDefaultOptions($_options);

        $inputClasses = glob(__DIR__ . "/../Inputs/*Input.php");
        $linkedOptions = $this->optionLoader->addClassOptions($_options, $inputClasses, $this->excludeClasses, "Input");
        $this->linkedOptions = array_merge($this->linkedOptions, $linkedOptions);

        $outputClasses = glob(__DIR__ . "/../Outputs/*Output.php");
        $linkedOptions = $this->optionLoader->addClassOptions($_options, $outputClasses, $this->excludeClasses, "Output");
        $this->linkedOptions = array_merge($this->linkedOptions, $linkedOptions);

        $ruleClasses = glob(__DIR__ . "/../Rules/*Rule.php");
        $linkedOptions = $this->optionLoader->addClassOptions($_options, $ruleClasses, $this->excludeClasses, "Rule");
        $this->linkedOptions = array_merge($this->linkedOptions, $linkedOptions);
    }
}
