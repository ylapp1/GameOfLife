<?php
/**
 * @file
 * @version 0.1
 * @copyright 2017-2018 CN-Consult GmbH
 * @author Yannick Lapp <yannick.lapp@cn-consult.eu>
 */

use GameOfLife\Board;
use TemplateHandler\TemplateLoader;
use PHPUnit\Framework\TestCase;
use Utils\FileSystemHandler;

/**
 * Checks whether \TemplateHandler\TemplateLoader works as expected.
 */
class TemplateLoaderTest extends TestCase
{
    /**
     * File system handler
     *
     * @var FileSystemHandler $fileSystemHandler
     */
    private $fileSystemHandler;

    /**
     * Test template directory
     *
     * @var String $templateDirectory
     */
    private $templateDirectory;

    /**
     * Test template loader
     *
     * @var TemplateLoader $templateLoader
     */
    private $templateLoader;


    public function setUp()
    {
        $this->fileSystemHandler = new FileSystemHandler();
        $this->templateDirectory = __DIR__ . "/../../InputTemplates";
        $this->templateLoader = new TemplateLoader($this->templateDirectory);
    }

    public function tearDown()
    {
        unset($this->fileSystemHandler);
        unset($this->templateLoader);
    }

    /**
     * Checks whether the constructor works as expected.
     *
     * @dataProvider constructionProvider()
     *
     * @param String $_templateDirectory Template directory
     */
    public function testCanBeConstructed(String $_templateDirectory)
    {
        $templateLoader = new TemplateLoader($_templateDirectory);

        $this->assertEquals($_templateDirectory, $templateLoader->defaultTemplatesDirectory());
    }

    /**
     * DataProvider for TemplateLoaderTest::testCanBeConstructed()
     *
     * @return array Test values
     */
    public function constructionProvider()
    {
        return array(
            array("test"),
            array("my/test"),
            array("my/long/test/that/is/good")
        );
    }

    /**
     * Checks whether templates are correctly loaded.
     */
    public function testCanLoadTemplate()
    {
        $board = new Board(5, 5, 1, true);

        // Official template
        $template = $this->templateLoader->loadTemplate("unittest");

        $this->assertEquals(2, $template->width());
        $this->assertEquals(2, $template->height());
        $this->assertTrue($template->getField(0, 0)->isAlive());
        $this->assertFalse($template->getField(1, 0)->isAlive());
        $this->assertFalse($template->getField(0, 1)->isAlive());
        $this->assertFalse($template->getField(1, 1)->isAlive());

        // Custom template
        $this->fileSystemHandler->writeFile($this->templateDirectory . "/Custom", "unittest2.txt", ".X\r\n..");
        $template = $this->templateLoader->loadTemplate("unittest2");

        $this->assertEquals(2, $template->width());
        $this->assertEquals(2, $template->height());
        $this->assertFalse($template->getField(0, 0)->isAlive());
        $this->assertTrue($template->getField(1, 0)->isAlive());
        $this->assertFalse($template->getField(0, 1)->isAlive());
        $this->assertFalse($template->getField(1, 1)->isAlive());

        $this->fileSystemHandler->deleteFile($this->templateDirectory . "/Custom/unittest2.txt");
        $this->fileSystemHandler->deleteDirectory($this->templateDirectory . "/Custom");


        // Non existent template
        $template = $this->templateLoader->loadTemplate("invalidTemplate");

        $this->assertfalse($template);
    }
}
