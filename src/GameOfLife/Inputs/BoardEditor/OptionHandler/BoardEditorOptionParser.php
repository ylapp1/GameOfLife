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
     *
     * @throws \Exception The exception when the option or its arguments are invalid
     */
    public function callOption(String $_input): bool
    {
        $optionData = $this->isOption($_input);

        if ($optionData)
        {
            $option = $this->parentOptionHandler->options()[$optionData["name"]];
            $numberOfArguments = count($optionData["arguments"]);

            if ($numberOfArguments > $option->getNumberOfArguments())
            {
                throw new \Exception("Invalid number of arguments (Expected " . $option->getNumberOfArguments() . ", Got " . $numberOfArguments . ").");
            }
            else
            {
                // Convert the arguments to the argument types
                $arguments = array();

                foreach ($option->arguments() as $argumentName => $argumentType)
                {
                    $argument = current($optionData["arguments"]);
                    if (! $argument)
                    {
                        $argument = $this->parentOptionHandler->parentBoardEditor()->readInput($argumentName . ": ");
                        if (! $argument) throw new \Exception("Arguments may not be empty.");
                    }

                    settype($argument, $argumentType);
                    $arguments[] = $argument;

                    next($optionData["arguments"]);
                }

                $sessionFinished = call_user_func_array(array($option, $option->callback()), $arguments);
                return $sessionFinished;
            }
        }
        else
        {
            if (strlen($_input) == 0) throw new \Exception("Input is empty.");
            else
            {
                $optionName = explode(" ", $_input)[0];
                if (is_numeric($optionName)) throw new \Exception("Invalid coordinates format.");
                else throw new \Exception("Invalid option \"" . $optionName . "\".");
            }
        }
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

        if (stristr($_input, ","))
        {
            $optionData = $this->splitOption("toggle," . $_input, ",");
            return $optionData;
        }
        else
        {
            foreach ($options as $optionName => $option)
            {
                if (strtolower($option->name()) == strtolower($optionData["name"]) ||
                    $option->hasAlias($optionData["name"]))
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
