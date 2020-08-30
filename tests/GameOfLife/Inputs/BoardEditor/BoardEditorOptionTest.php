<?php
/**
 * @file
 * @version 0.1
 * @copyright 2017-2018 CN-Consult GmbH
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
     *
     * @throws \Exception
     */
    public function testCanBeConstructed()
    {
        $boardEditor = new BoardEditor("test");
        $option = new BoardEditorOption($boardEditor);

        $this->assertEquals($boardEditor, $option->parentBoardEditor());
        $this->assertEquals(array(), $option->aliases());
    }

    /**
     * Checks whether the getters and setters work as expected.
     *
     * @dataProvider setAttributesProvider
     * @covers \BoardEditor\BoardEditorOption::name()
     * @covers \BoardEditor\BoardEditorOption::setName()
     * @covers \BoardEditor\BoardEditorOption::aliases()
     * @covers \BoardEditor\BoardEditorOption::setAliases()
     * @covers \BoardEditor\BoardEditorOption::callback()
     * @covers \BoardEditor\BoardEditorOption::setCallback()
     * @covers \BoardEditor\BoardEditorOption::description()
     * @covers \BoardEditor\BoardEditorOption::setDescription()
     * @covers \BoardEditor\BoardEditorOption::parentBoardEditor()
     * @covers \BoardEditor\BoardEditorOption::setParentBoardEditor()
     *
     * @param String $_name Option name
     * @param array $_aliases Alias list
     * @param String $_callback Function that will be called when the option is used
     * @param String $_description Short description of the option which will be displayed in the option list
     * @param String $_templateDirectory Template directory for the BoardEditor constructor
     *
     * @throws \Exception
     */
    public function testCanSetAttributes(String $_name, array $_aliases, String $_callback, String $_description, String $_templateDirectory)
    {
        $boardEditor = new BoardEditor("the test");
        $option = new BoardEditorOption($boardEditor);

        $testBoardEditor = new BoardEditor($_templateDirectory);

        $option->setName($_name);
        $option->setAliases($_aliases);
        $option->setCallback($_callback);
        $option->setDescription($_description);
        $option->setParentBoardEditor($testBoardEditor);

        $this->assertEquals($_name, $option->name());
        $this->assertEquals($_aliases, $option->aliases());
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
            array("myOption", array("myO", "MyOpt"), "openOption", "Opens my option", "Options/Directory"),
            array("myOtherOption", array("myOther", "otherOpt"), "secondOption", "Opens not my option", "Not/my/option"),
            array("finalOption", array("finalO", "finalOpt"), "endAllOptions", "Destroys all options that ever existed", "Destroyer/Options/Done")
        );
    }

    /**
     * Checks whether an option can return whether an alias belongs to it.
     *
     * @covers \BoardEditor\BoardEditorOption::hasAlias()
     *
     * @throws \Exception
     */
    public function testCanFindAlias()
    {
        $aliases = array("one", "two", "three");

        $boardEditor =new BoardEditor("hello");
        $option = new BoardEditorOption($boardEditor);

        $option->setAliases($aliases);

        $this->assertTrue($option->hasAlias("one"));
        $this->assertTrue($option->hasAlias("two"));
        $this->assertTrue($option->hasAlias("three"));
        $this->assertFalse($option->hasAlias("random"));
        $this->assertFalse($option->hasAlias("hello"));
        $this->assertFalse($option->hasAlias("myOption"));
    }
}
