<?php
/**
 * @file
 * @version 0.1
 * @copyright 2017-2018 CN-Consult GmbH
 * @author Yannick Lapp <yannick.lapp@cn-consult.eu>
 */

use TemplateHandler\TemplateHandler;
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
     * @covers \TemplateHandler\TemplateHandler::__construct()
     */
    public function testCanBeConstructed()
    {
        $templateHandler = new TemplateHandler("test");

        $this->assertEquals(new FileSystemHandler(), $templateHandler->fileSystemHandler());
        $this->assertEquals("test", $templateHandler->defaultTemplatesDirectory());
    }

    /**
     * Checks whether the getters/setters work as expected.
     *
     * @covers \TemplateHandler\TemplateHandler::setFileSystemHandler()
     * @covers \TemplateHandler\TemplateHandler::fileSystemHandler()
     * @covers \TemplateHandler\TemplateHandler::setDefaultTemplatesDirectory()
     * @covers \TemplateHandler\TemplateHandler::defaultTemplatesDirectory()
     * @covers \TemplateHandler\TemplateHandler::setCustomTemplatesDirectory()
     * @covers \TemplateHandler\TemplateHandler::customTemplatesDirectory()
     */
    public function testCanSetAttributes()
    {
        $templateHandler = new TemplateHandler("nottest");

        $templateHandler->setFileSystemHandler(new FileSystemHandler());
        $templateHandler->setDefaultTemplatesDirectory("mytest");
        $templateHandler->setCustomTemplatesDirectory("hello");

        $this->assertEquals(new FileSystemHandler(), $templateHandler->fileSystemHandler());
        $this->assertEquals("mytest", $templateHandler->defaultTemplatesDirectory());
        $this->assertEquals("hello", $templateHandler->customTemplatesDirectory());
    }
}
