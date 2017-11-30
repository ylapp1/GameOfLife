<?php
/**
 * @file
 * @version 0.1
 * @copyright 2017 CN-Consult GmbH
 * @author Tim Schreindl <tim.schreindl@cn-consult.eu>
 * @author Yannick Lapp <yannick.lapp@cn-consult.eu>
 */

namespace Input;

use GameOfLife\Board;
use BoardEditor\BoardEditor;
use Ulrichsg\Getopt;

/**
 * Launches a Board Editor.
 */
class UserInput extends BaseInput
{
    /**
     * The board editor
     *
     * @var BoardEditor $boardEditor
     */
    private $boardEditor;

    /**
     * Base directory of templates
     *
     * @var String $templateDirectory
     */
    private $templateDirectory = __DIR__ . "/../../../Input/Templates/";


    /**
     * UserInput constructor.
     */
    public function __construct()
    {
        $this->boardEditor = new BoardEditor($this->templateDirectory, null);
    }



    /**
     * Returns the board editor
     *
     * @return BoardEditor Board editor
     */
    public function boardEditor(): BoardEditor
    {
        return $this->boardEditor;
    }

    /**
     * Sets the board editor
     *
     * @param BoardEditor $_boardEditor New board editor
     */
    public function setBoardEditor(BoardEditor $_boardEditor)
    {
        $this->boardEditor = $_boardEditor;
    }

    /**
     * Returns the template directory in which UserInput will create the folder Custom where it saves custom templates.
     *
     * @return String Template directory
     */
    public function templateDirectory(): String
    {
        return $this->templateDirectory;
    }

    /**
     * Sets the template directory.
     *
     * @param string $_templateDirectory Template directory
     */
    public function setTemplateDirectory(String $_templateDirectory)
    {
        $this->templateDirectory = $_templateDirectory;
    }

    /**
     * Adds UserInputs specific options to the option list.
     *
     * @param Getopt $_options Option list to which the objects options are added
     */
    public function addOptions(Getopt $_options)
    {
        $_options->addOptions(
            array(
                array(null, "edit", Getopt::NO_ARGUMENT, "Edit a template"))
        );
    }

    /**
     * Launches a new board editor session.
     *
     * @param Board $_board The board which will be filled
     * @param Getopt $_options Options (edit, template)
     */
    public function fillBoard(Board $_board, Getopt $_options)
    {
        if ($_options->getOption("edit") !== null)
        {
            $fileInput = new TemplateInput($this->templateDirectory);
            $fileInput->fillBoard($_board, $_options);
        }

        $this->boardEditor->setBoard($_board);
        $this->boardEditor->launch();
    }
}