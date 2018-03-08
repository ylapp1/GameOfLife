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
     * Checks whether the constructor works as expected.
     *
     * @covers \TemplateHandler\TemplateSaver
     */
    public function testCanBeConstructed()
    {
        $testDirectory = "myPersonalTest";
        $templateSaver = new TemplateSaver($testDirectory);

        $this->assertEquals($testDirectory, $templateSaver->templateDirectory());
    }

    /**
     * Checks whether templates can be saved.
     */
    public function testCanSaveTemplate()
    {
        $fileSystemHandlerMock = $this->getMockBuilder(FileSystemHandler::class)
            ->setMethods(array("createDirectory", "writeFile"))
            ->getMock();

        $testBoard = new Board(2, 3, 2, true);
        $testBoard->setField(1, 1, true);

        $boardString = "..\r\n.X\r\n..";

        $testDirectory = __DIR__ . "/test";
        $templateSaver = new TemplateSaver($testDirectory);

        if ($fileSystemHandlerMock instanceof FileSystemHandler)
        {
            $templateSaver->setFileSystemHandler($fileSystemHandlerMock);

            $fileSystemHandlerMock->expects($this->exactly(2))
                ->method("createDirectory")
                ->with($testDirectory . "/Custom");
            $fileSystemHandlerMock->expects($this->exactly(2))
                ->method("writeFile")
                ->withConsecutive(array($testDirectory . "/Custom", "unitTest.txt", $boardString, true),
                    array($testDirectory . "/Custom", "unitTestSecond.txt", $boardString, false))
                ->willReturn(FileSystemHandler::NO_ERROR, FileSystemHandler::ERROR_FILE_EXISTS);


            // Save successful
            $result = $templateSaver->saveTemplate("unitTest", $testBoard, true);
            $this->assertTrue($result);

            // Save not successful
            $result = $templateSaver->saveTemplate("unitTestSecond", $testBoard, false);
            $this->assertFalse($result);
        }
    }
}
