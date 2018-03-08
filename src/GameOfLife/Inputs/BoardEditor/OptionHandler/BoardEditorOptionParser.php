<?php
/**
 * @file
 * @version 0.1
 * @copyright 2017-2018 CN-Consult GmbH
 * @author Yannick Lapp <yannick.lapp@cn-consult.eu>
 */

namespace BoardEditor\OptionHandler;

/**
 * Parses board editor options.
 */
class BoardEditorOptionParser
{
    /**
     * The parent option handler
     *
     * @var BoardEditorOptionHandler Parent option handler
     */
    private $parentOptionHandler;


    /**
     * BoardEditorOptionParser constructor.
     *
     * @param BoardEditorOptionHandler $_parentOptionHandler Parent option handler
     */
    public function __construct(BoardEditorOptionHandler $_parentOptionHandler)
    {
        $this->parentOptionHandler = $_parentOptionHandler;
    }


    /**
     * Returns the parent option handler.
     *
     * @return BoardEditorOptionHandler Parent option handler
     */
    public function parentOptionHandler(): BoardEditorOptionHandler
    {
        return $this->parentOptionHandler;
    }

    /**
     * Sets the parent option handler.
     *
     * @param BoardEditorOptionHandler $_parentOptionHandler Parent option handler
     */
    public function setParentOptionHandler(BoardEditorOptionHandler $_parentOptionHandler)
    {
        $this->parentOptionHandler = $_parentOptionHandler;
    }


    /**
     * Calls an option from the option list.
     *
     * @param String $_input Input string
     *
     * @return bool True: Board Editor session is finished
     *              False: Board Editor session continues
     */
    public function callOption(String $_input): bool
    {
        $optionData = $this->isOption($_input);

        if ($optionData)
        {
            $option = $this->parentOptionHandler->options()[$optionData["name"]];

            if (count($optionData["arguments"]) != $option->numberOfArguments())
            {
                echo "Error: Invalid number of arguments\n";
            }
            else
            {
                $sessionFinished = call_user_func_array(array($option, $option->callback()), $optionData["arguments"]);
                return $sessionFinished;
            }
        }
        else echo "Error: Invalid option or invalid coordinates format\n";

        return false;
    }

    /**
     * Returns whether the input string is one of the registered options.
     *
     * @param String $_input Input string
     *
     * @return array|bool The option data (name, arguments) or false
     */
    private function isOption(String $_input)
    {
        $optionData = $this->splitOption($_input, " ");
        $options = $this->parentOptionHandler->options();

        if (array_key_exists($optionData["name"], $options)) return $optionData;
        elseif (stristr($_input, ","))
        {
            $optionData = $this->splitOption("toggle," . $_input, ",");
            return $optionData;
        }
        else
        {
            // Check whether option name is an alias of any option
            foreach ($options as $optionName => $option)
            {
                if ($option->hasAlias($optionData["name"]))
                {
                    $optionData["name"] = $optionName;
                    return $optionData;
                }
            }

            return false;
        }
    }

    /**
     * Splits an option string into option name and arguments and returns the result.
     *
     * @param String $_input Option string
     * @param String $_delimiter at which the option string will be split
     *
     * @return array Array in the format array(String $optionName, String[] $arguments)
     */
    private function splitOption(String $_input, String $_delimiter): array
    {
        $parts = explode($_delimiter, $_input);

        $optionName = array_shift($parts);
        $arguments = $parts;

        return array("name" => $optionName, "arguments" => $arguments);
    }
}