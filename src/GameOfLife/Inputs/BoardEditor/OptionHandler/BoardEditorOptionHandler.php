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
 * Handles option loading and parsing.
 */
class BoardEditorOptionHandler
{
    /**
     * Option list in the format "optionName" => BoardEditorOption $option
     *
     * @var BoardEditorOption[]
     */
    private $options;

    /**
     * The option loader which generates a list of all options.
     *
     * @var BoardEditorOptionLoader  $boardEditorOptionLoader
     */
    private $optionLoader;

    /**
     * The option parser which stores the option list and parses board editor options.
     *
     * @var BoardEditorOptionParser $boardEditorOptionParser
     */
    private $optionParser;

    /**
     * The parent board editor
     *
     * @var BoardEditor  $parentBoardEditor
     */
    private $parentBoardEditor;


    /**
     * BoardEditorOptionHandler constructor.
     *
     * @param BoardEditor $_parentBoardEditor Parent board editor
     */
    public function __construct(BoardEditor $_parentBoardEditor)
    {
        $this->parentBoardEditor = $_parentBoardEditor;
        $this->optionLoader = new BoardEditorOptionLoader($this);
        $this->optionParser = new BoardEditorOptionParser($this);
        $this->options = $this->optionLoader->loadOptions(__DIR__ . "/../Options/");
    }


    /**
     * Returns the option list.
     *
     * @return BoardEditorOption[] Option list
     */
    public function options(): array
    {
        return $this->options;
    }

    /**
     * Sets the option list.
     *
     * @param array $_options New option list
     */
    public function setOptions(array $_options)
    {
        $this->options = $_options;
    }

    /**
     * Returns the option loader.
     *
     * @return BoardEditorOptionLoader Option loader
     */
    public function optionLoader(): BoardEditorOptionLoader
    {
        return $this->optionLoader;
    }

    /**
     * Sets the option loader.
     *
     * @param BoardEditorOptionLoader $_optionLoader Option loader
     */
    public function setOptionLoader(BoardEditorOptionLoader $_optionLoader)
    {
        $this->optionLoader = $_optionLoader;
    }

    /**
     * Returns the option parser.
     *
     * @return BoardEditorOptionParser Option parser
     */
    public function optionParser():BoardEditorOptionParser
    {
        return $this->optionParser;
    }

    /**
     * Sets the option parser.
     *
     * @param BoardEditorOptionParser $_optionParser Option parser
     */
    public function setOptionParser(BoardEditorOptionParser $_optionParser)
    {
        $this->optionParser = $_optionParser;
    }

    /**
     * Returns the parent board editor.
     *
     * @return BoardEditor Parent board editor
     */
    public function parentBoardEditor(): BoardEditor
    {
        return $this->parentBoardEditor;
    }

    /**
     * Sets the parent board editor.
     *
     * @param BoardEditor $_parentBoardEditor Parent board editor
     */
    public function setParentBoardEditor(BoardEditor $_parentBoardEditor)
    {
        $this->parentBoardEditor = $_parentBoardEditor;
    }


    /**
     * Parses the user input and tries to call an option.
     *
     * @param String $_input User input
     *
     * @return bool Indicates whether the board editor session is finished
     */
    public function parseInput(String $_input): bool
    {
        return $this->optionParser->callOption($_input, $this->options);
    }
}