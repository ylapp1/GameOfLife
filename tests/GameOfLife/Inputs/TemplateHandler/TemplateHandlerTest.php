<?php
/**
 * @file
 * @version 0.1
 * @copyright 2017 CN-Consult GmbH
 * @author Yannick Lapp <yannick.lapp@cn-consult.eu>
 */

use Input\TemplateHandler\TemplateHandler;
use Utils\FileSystemHandler;
use PHPUnit\Framework\TestCase;

/**
 * Checks whether \Input\TemplateHandler\TemplateHandler works as expected.
 */
class TemplateHandlerTest extends TestCase
{
    /**
     * Checks whether the constructor sets the attributes as expected.
     *
     * @covers \Input\TemplateHandler\TemplateHandler::__construct()
     */
    public function testCanBeConstructed()
    {
        $templateHandler = new TemplateHandler("test");

        $this->assertEquals(new FileSystemHandler(), $templateHandler->fileSystemHandler());
        $this->assertEquals("test", $templateHandler->templateDirectory());
    }

    /**
     * Checks whether the getters/setters work as expected.
     *
     * @covers \Input\TemplateHandler\TemplateHandler::setFileSystemHandler()
     * @covers \Input\TemplateHandler\TemplateHandler::fileSystemHandler()
     * @covers \Input\TemplateHandler\TemplateHandler::setTemplateDirectory()
     * @covers \Input\TemplateHandler\TemplateHandler::templateDirectory()
     */
    public function testCanSetAttributes()
    {
        $templateHandler = new TemplateHandler("nottest");

        $templateHandler->setFileSystemHandler(new FileSystemHandler());
        $templateHandler->setTemplateDirectory("mytest");

        $this->assertEquals(new FileSystemHandler(), $templateHandler->fileSystemHandler());
        $this->assertEquals("mytest", $templateHandler->templateDirectory());
    }
}