<?php
/**
 * @file
 * @version 0.1
 * @copyright 2017-2018 CN-Consult GmbH
 * @author Tim Schreindl <tim.schreindl@cn-consult.eu>
 * @author Yannick Lapp <yannick.lapp@cn-consult.eu>
 */

namespace Input;

use BoardEditor\BoardEditor;
use GameOfLife\Board;
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
     * The base directory for default and custom templates
     *
     * @var String $templatesBaseDirectory
     */
    private $templatesBaseDirectory;


    /**
     * UserInput constructor.
     *
     * @throws \Exception The exception from the BoardEditor constructor
     */
    public function __construct()
    {
        $this->templatesBaseDirectory = __DIR__ . "/../../../Input/Templates";
        $this->boardEditor = new BoardEditor($this->templatesBaseDirectory, null);
    }


    /**
     * Adds UserInputs specific options to the option list.
     *
     * @param Getopt $_options The option list to which the objects options are added
     */
    public function addOptions(Getopt $_options)
    {
        $_options->addOptions(
            array(
                array(null, "edit", Getopt::NO_ARGUMENT, "UserInput - Edit a template\n"))
        );
    }

    /**
     * Launches a new board editor session.
     *
     * @param Board $_board The board that will be filled
     * @param Getopt $_options The option list
     *
     * @throws \Exception The exceptions of the TemplateInput and the board editor launch method
     */
    public function fillBoard(Board $_board, Getopt $_options)
    {
        if ($_options->getOption("edit") !== null)
        {
            $templateInput = new TemplateInput($this->templatesBaseDirectory);
            $templateInput->fillBoard($_board, $_options);
        }

        $this->boardEditor->setBoard($_board);
        $this->boardEditor->launch();
    }
}
