<?php
/**
 * @file
 * @version 0.1
 * @copyright 2017 CN-Consult GmbH
 * @author Yannick Lapp <yannick.lapp@cn-consult.eu>
 */

namespace BoardEditor;

/**
 * Base class for other board editor options.
 */
class BoardEditorOption
{
    /**
     * Option name
     *
     * @var String $name
     */
    protected $name;

    /**
     * Name of the call back class method
     *
     * This function will be called when the option is used
     * Notes:
     *   - The function must return true or false to indicate whether the board editing is finished after using this option
     *   - Any function parameters must be defined as optional (you must then manually check in the function whether the
     *     parameter is null)
     *
     * @var String $callback
     */
    protected $callback;

    /**
     * Short description of the option which will be displayed in the option list
     *
     * @var $description
     */
    protected $description;

    /**
     * Board editor to which the option belongs
     *
     * @var BoardEditor
     */
    protected $parentBoardEditor;


    /**
     * BoardEditorOption constructor.
     *
     * @param BoardEditor $_parentBoardEditor The parent board editor
     */
    public function __construct(BoardEditor $_parentBoardEditor)
    {
        $this->parentBoardEditor = $_parentBoardEditor;
    }


    /**
     * Returns the option name.
     *
     * @return String
     */
    public function name(): String
    {
        return $this->name;
    }

    /**
     * Sets the option name
     *
     * @param String $_name New option name.
     */
    public function setName(String $_name)
    {
        $this->name = $_name;
    }

    /**
     * Returns the callback method name.
     *
     * @return String Callback method name
     */
    public function callback(): String
    {
        return $this->callback;
    }

    /**
     * Sets the callback method name.
     *
     * @param String $_callback New callback method name
     */
    public function setCallback(String $_callback)
    {
        $this->callback = $_callback;
    }

    /**
     * Returns the option description.
     *
     * @return String Option description
     */
    public function description(): String
    {
        return $this->description;
    }

    /**
     * Sets the option description.
     *
     * @param String $_description New option description
     */
    public function setDescription(String $_description)
    {
        $this->description = $_description;
    }

    /**
     * Sets the parent board editor.
     *
     * @return BoardEditor Parent board editor
     */
    public function parentBoardEditor(): BoardEditor
    {
        return $this->parentBoardEditor;
    }

    /**
     * Sets the parent board editor
     *
     * @param BoardEditor $_parentBoardEditor Parent board editor
     */
    public function setParentBoardEditor(BoardEditor $_parentBoardEditor)
    {
        $this->parentBoardEditor = $_parentBoardEditor;
    }
}