<?php
/**
 * @file
 * @version 0.1
 * @copyright 2017 CN-Consult GmbH
 * @author Yannick Lapp <yannick.lapp@cn-consult.eu>
 */

namespace BoardEditor\OptionHandler;

use BoardEditor\BoardEditor;
use BoardEditor\BoardEditorOption;


/**
 * Loads the board editor options and returns a list of options.
 */
class BoardEditorOptionLoader
{
    /**
     * The parent option handler
     *
     * @var BoardEditorOptionHandler $parentOptionHandler
     */
    private $parentOptionHandler;


    /**
     * BoardEditorOptionLoader constructor.
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
     * Loads all options from the options folder.
     *
     * @param String $_optionsDirectory Directory in which the board editor options are stored
     *
     * @return BoardEditorOption[] array in the format ("optionName" => "optionObject")
     */
    public function loadOptions(String $_optionsDirectory): array
    {
        $options = array();

        // Load each option from the options folder
        $classes = glob($_optionsDirectory);

        foreach ($classes as $class)
        {
            $className = basename($class, ".php");
            $classPath = "BoardEditor\\Options\\" . $className;

            $instance = new $classPath($this->parentOptionHandler->parentBoardEditor());

            if ($instance instanceof BoardEditorOption) $options[$instance->name()] = $instance;
        }

        return $options;
    }
}