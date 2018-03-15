<?php
/**
 * @file
 * @version 0.1
 * @copyright 2017-2018 CN-Consult GmbH
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
     * Aliases of this option which can be used to trigger it
     *
     * @var String[] $aliases
     */
    protected $aliases;

    /**
     * Name of the call back class method
     *
     * This function will be called when the option is used
     * Notes:
     *   - The function must return true or false to indicate whether the board editing is finished after using this option
     *   - Any function parameters must not contain type hints because the input values will always be strings.
     *
     * @var String $callback
     */
    protected $callback;

    /**
     * Stores the number of arguments of the callback function
     *
     * @var int $numberOfArguments
     */
    protected $numberOfArguments;

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
        $this->aliases = array();
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
     * Sets the option name.
     *
     * @param String $_name New option name.
     */
    public function setName(String $_name)
    {
        $this->name = $_name;
    }

    /**
     * Returns the aliases of this option.
     *
     * @return array Aliases of this option
     */
    public function aliases(): array
    {
        return $this->aliases;
    }

    /**
     * Sets the aliases of this option.
     *
     * @param array $_aliases Aliases of this option
     */
    public function setAliases(array $_aliases)
    {
        $this->aliases = $_aliases;
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
     * Returns the number of arguments.
     *
     * @return int Number of arguments
     */
    public function numberOfArguments(): int
    {
        return $this->numberOfArguments;
    }

    /**
     * Sets the number of arguments.
     *
     * @param int $_numberOfArguments Number of arguments
     */
    public function setNumberOfArguments(int $_numberOfArguments)
    {
        $this->numberOfArguments = $_numberOfArguments;
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


    /**
     * Checks whether this option has the alias $_alias.
     *
     * @param String $_alias The alias to search for
     *
     * @return bool Indicates whether the alias belongs to this option
     */
    public function hasAlias(String $_alias)
    {
        $alias = strtolower($_alias);
        if (in_array($alias, $this->aliases)) return true;
        else return false;
    }
}
