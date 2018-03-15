<?php
/**
 * @file
 * @version 0.1
 * @copyright 2017-2018 CN-Consult GmbH
 * @author Yannick Lapp <yannick.lapp@cn-consult.eu>
 */

use GameOfLife\Board;
use TemplateHandler\TemplateSaver;
use Utils\FileSystemHandler;
use PHPUnit\Framework\TestCase;

/**
 * Checks whether the template saver works as expected.
 */
class TemplateSaverTest extends TestCase
{
    /**
     * Checks whether templates can be saved.
     *
     * @throws ReflectionException
     */
    public function testCanSaveTemplate()
    {
        $fileSystemHandlerMock = $this->getMockBuilder(FileSystemHandler::class)
            ->setMethods(array("writeFile"))
            ->getMock();

        $testBoard = new Board(2, 3, 2, true);
        $testBoard->setField(1, 1, true);

        $boardString = "..\r\n.X\r\n..";

        $testDirectory = __DIR__ . "/test";
        $templateSaver = new TemplateSaver($testDirectory);

        if ($fileSystemHandlerMock instanceof FileSystemHandler)
        {
            $reflectionClass = new ReflectionClass(\TemplateHandler\TemplateSaver::class);
            $reflectionProperty = $reflectionClass->getProperty("fileSystemHandler");
            $reflectionProperty->setAccessible(true);
            $reflectionProperty->setValue($templateSaver, $fileSystemHandlerMock);

            $fileSystemHandlerMock->expects($this->exactly(2))
                ->method("writeFile")
                ->withConsecutive(array($testDirectory . "/Custom", "unitTest.txt", $boardString, true),
                    array($testDirectory . "/Custom", "unitTestSecond.txt", $boardString, false))
                ->willReturn(FileSystemHandler::NO_ERROR, FileSystemHandler::ERROR_FILE_EXISTS);


            // Save successful
            $result = $templateSaver->saveCustomTemplate("unitTest", $testBoard, true);
            $this->assertTrue($result);

            // Save not successful
            $result = $templateSaver->saveCustomTemplate("unitTestSecond", $testBoard, false);
            $this->assertFalse($result);
        }
    }
}
