<?php
/**
 * @file
 * @version 0.1
 * @copyright 2017 CN-Consult GmbH
 * @author Yannick Lapp <yannick.lapp@cn-consult.eu>
 */

use BoardEditor\BoardEditor;
use BoardEditor\BoardEditorOption;
use PHPUnit\Framework\TestCase;

/**
 * Checks whether the Board Editor base option works as expected.
 */
class BoardEditorOptionTest extends TestCase
{
    /**
     * Checks whether the constructor works as expected.
     *
     * @covers \BoardEditor\BoardEditorOption::__construct()
     */
    public function testCanBeConstructed()
    {
        $boardEditor = new BoardEditor("test");
        $option = new BoardEditorOption($boardEditor);

        $this->assertEquals($boardEditor, $option->parentBoardEditor());
    }

    /**
     * Checks whether the getters and setters work as expected.
     *
     * @dataProvider setAttributesProvider
     * @covers \BoardEditor\BoardEditorOption::name()
     * @covers \BoardEditor\BoardEditorOption::setName()
     * @covers \BoardEditor\BoardEditorOption::callback()
     * @covers \BoardEditor\BoardEditorOption::setCallback()
     * @covers \BoardEditor\BoardEditorOption::description()
     * @covers \BoardEditor\BoardEditorOption::setDescription()
     * @covers \BoardEditor\BoardEditorOption::parentBoardEditor()
     * @covers \BoardEditor\BoardEditorOption::setParentBoardEditor()
     *
     * @param String $_name Option name
     * @param String $_callback Function that will be called when the option is used
     * @param String $_description Short description of the option which will be displayed in the option list
     * @param String $_templateDirectory Template directory for the BoardEditor constructor
     */
    public function testCanSetAttributes(String $_name, String $_callback, String $_description, String $_templateDirectory)
    {
        $boardEditor = new BoardEditor("the test");
        $option = new BoardEditorOption($boardEditor);

        $testBoardEditor = new BoardEditor($_templateDirectory);

        $option->setName($_name);
        $option->setCallback($_callback);
        $option->setDescription($_description);
        $option->setParentBoardEditor($testBoardEditor);

        $this->assertEquals($_name, $option->name());
        $this->assertEquals($_callback, $option->callback());
        $this->assertEquals($_description, $option->description());
        $this->assertEquals($testBoardEditor, $option->parentBoardEditor());
    }

    /**
     * DataProvider for BoardEditorOptionTest::testCanSetAttributes
     *
     * @return array Test values
     */
    public function setAttributesProvider()
    {
        return array(
            array("myOption", "openOption", "Opens my option", "Options/Directory"),
            array("myOtherOption", "secondOption", "Opens not my option", "Not/my/option"),
            array("finalOption", "endAllOptions", "Destroys all options that ever existed", "Destroyer/Options/Done")
        );
    }
}