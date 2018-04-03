<?php
/**
 * @file
 * @version 0.1
 * @copyright 2017-2018 CN-Consult GmbH
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
                $arguments = $this->getOptionArguments($option, $optionData["arguments"]);
                $sessionFinished = call_user_func_array(array($option, $option->callback()), $arguments);
                return $sessionFinished;
            }
        }
        else
        {
            if (strlen($_input) == 0) throw new \Exception("Input is empty.");
            else
            {
                $optionParts = explode(" ", $_input);
                $optionName = $optionParts[0];

                if (is_numeric($optionName))
                {
                    if (count($optionParts) == 2) return $this->callOption($optionParts[0] . "," . $optionParts[1]);
                    else throw new \Exception("Invalid coordinates format.");
                }
                else throw new \Exception("Invalid option \"" . $optionName . "\".");
            }
        }
    }

    /**
     * Reads missing arguments and returns the full list of arguments.
     *
     * @param BoardEditorOption $_option The option
     * @param String[] $_arguments The already inputted arguments
     *
     * @return String[] The complete list of arguments
     *
     * @throws \Exception The exception when the user input is invalid
     */
    private function getOptionArguments(BoardEditorOption $_option, array $_arguments): array
    {
        $arguments = array();

        foreach ($_option->arguments() as $argumentName => $argumentType)
        {
            $argumentType = explode("|", $argumentType)[0];

            $argument = current($_arguments);
            if ($argument === false)
            {
                if ($this->canArgumentBeOmitted($argumentType, $arguments)) continue;
                $argument = $this->readArgument($argumentName, $argumentType);
            }

            $this->checkArgument($argument, $argumentType);
            $argument = $this->changeArgumentType($argument, $argumentType);

            $arguments[] = $argument;
            next($_arguments);
        }

        return $arguments;
    }

    /**
     * Checks whether the argument can be omitted.
     *
     * @param String $_argumentType The type of the argument
     * @param String[] $_arguments The list of already read arguments
     *
     * @return Bool True: The argument can be omitted
     *              False: The argument can not be omitted
     */
    private function canArgumentBeOmitted(String $_argumentType, array $_arguments): Bool
    {
        // Split string into option parts
        $argumentTypeParts = explode("|", $_argumentType);

        // Remove the argument type from the argument type parts
        array_shift($argumentTypeParts);

        $conditions = $argumentTypeParts;

        foreach ($conditions as $condition)
        {
            $conditionParts = explode("=", $condition);
            $conditionArgumentId = (int)$conditionParts[0];

            $conditionArgumentParts = explode(",", $conditionParts[1]);

            $conditionArgumentType = $conditionArgumentParts[0];
            $conditionArgumentValue = $conditionArgumentParts[1];
            settype($conditionArgumentValue, $conditionArgumentType);

            if ($_arguments[$conditionArgumentId] === $conditionArgumentValue) return true;
        }

        return false;
    }

    /**
     * Reads an argument from the console.
     *
     * @param String $_argumentName The argument name
     * @param String $_argumentType The argument type
     *
     * @return String The argument that was entered by the user
     */
    public function readArgument(String $_argumentName, String $_argumentType): String
    {
        $argumentName = $_argumentName;
        if ($_argumentType == "Bool") $argumentName .= "(Yes|No)";

        return $this->parentOptionHandler->parentBoardEditor()->readInput($argumentName . ": ");
    }

    /**
     * Checks whether an argument is valid.
     *
     * @param String $_argument The argument
     * @param String $_argumentType The argument type
     *
     * @throws \Exception The exception when the argument is not valid
     */
    public function checkArgument(String $_argument, String $_argumentType)
    {
        if ($_argument === "") throw new \Exception("Arguments may not be empty.");
        elseif ($_argumentType == "int" && ! is_numeric($_argument))
        {
            throw new \Exception("The argument must be a number.");
        }
    }

    /**
     * Changes the type of an argument to $_argumentType.
     *
     * @param String $_argument The argument
     * @param String $_argumentType The desired argument type
     *
     * @return mixed The converted argument
     */
    public function changeArgumentType(String $_argument, String $_argumentType)
    {
        $argument = $_argument;

        if ($_argumentType == "Bool")
        {
            if (stristr($_argument, "yes") || stristr($_argument, "y"))
            {
                $argument = true;
            }
            else $argument = false;
        }

        settype($argument, $_argumentType);

        return $argument;
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
