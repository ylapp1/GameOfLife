<?php
/**
 * @file
 * @version 0.1
 * @copyright 2017 CN-Consult GmbH
 * @author Yannick Lapp <yannick.lapp@cn-consult.eu>
 */

namespace BoardEditor\OptionHandler;

use BoardEditor\BoardEditorOption;

/**
 * Parses board editor options.
 */
class BoardEditorOptionParser
{
    /**
     * Calls an option from the option list.
     *
     * @param String $_input Input string
     * @param BoardEditorOption[] $_optionList The option list
     *
     * @return bool True: Board Editor session is finished
     *              False: Board Editor session continues
     */
    public function callOption(String $_input, array $_optionList): bool
    {
        $optionData = $this->isOption($_input, $_optionList);

        if ($optionData)
        {
            $option = $_optionList[$optionData["name"]];

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
     * @param BoardEditorOption[] $_optionList The option list
     *
     * @return array|bool The option data (name, arguments) or false
     */
    private function isOption(String $_input, array $_optionList)
    {
        $optionData = $this->splitOption($_input, " ");

        if (array_key_exists($optionData["name"], $_optionList)) return $optionData;
        elseif (stristr($_input, ","))
        {
            $optionData = $this->splitOption("toggle," . $_input, ",");
            return $optionData;
        }
        else return false;
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