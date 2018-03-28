<?php
/**
 * @file
 * @version 0.1
 * @copyright 2017-2018 CN-Consult GmbH
 * @author Yannick Lapp <yannick.lapp@cn-consult.eu>
 */

use GameOfLife\Board;
use GameOfLife\Field;
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
     * Checks whether templates are correctly loaded.
     *
     * @throws \Exception
     */
    public function testCanLoadTemplate()
    {
        $board = new Board(5, 5, 1, true);

        // Official template
        /** @var Field[][] $templateFields */
        $templateFields = $this->templateLoader->loadTemplate("unittest");

        $templateHeight = count($templateFields);
        $templateWidth = count($templateFields[0]);

        $this->assertEquals(2, $templateWidth);
        $this->assertEquals(2, $templateHeight);
        $this->assertTrue($templateFields[0][0]->isAlive());
        $this->assertFalse($templateFields[1][0]->isAlive());
        $this->assertFalse($templateFields[0][1]->isAlive());
        $this->assertFalse($templateFields[1][1]->isAlive());

        // Custom template
        $this->fileSystemHandler->writeFile($this->templateDirectory . "/Custom/unittest2.txt", ".X\r\n..");

        /** @var Field[][] $templateFields */
        $templateFields = $this->templateLoader->loadTemplate("unittest2");

        $templateHeight = count($templateFields);
        $templateWidth = count($templateFields[0]);

        $this->assertEquals(2, $templateWidth);
        $this->assertEquals(2, $templateHeight);
        $this->assertFalse($templateFields[0][0]->isAlive());
        $this->assertTrue($templateFields[0][1]->isAlive());
        $this->assertFalse($templateFields[1][0]->isAlive());
        $this->assertFalse($templateFields[1][1]->isAlive());

        $this->fileSystemHandler->deleteFile($this->templateDirectory . "/Custom/unittest2.txt");

        try
        {
            $this->fileSystemHandler->deleteDirectory($this->templateDirectory . "/Custom");
        }
        catch (\Exception $_exception)
        {
            // Ignore exception
        }


        // Non existent template
        $exceptionOccurred = false;
        try
        {
            $this->templateLoader->loadTemplate("invalidTemplate");
        }
        catch (\Exception $_exception)
        {
            $exceptionOccurred = true;
            $this->assertEquals("The template file could not be found.", $_exception->getMessage());
        }
        $this->assertTrue($exceptionOccurred);
    }
}
