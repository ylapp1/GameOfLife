<?php
/**
 * @file
 * @version 0.1
 * @copyright 2017-2018 CN-Consult GmbH
 * @author Yannick Lapp <yannick.lapp@cn-consult.eu>
 */

use GameOfLife\Board;
use TemplateHandler\TemplateSaver;
use Utils\FileSystem\FileSystemWriter;
use PHPUnit\Framework\TestCase;

/**
 * Checks whether the template saver works as expected.
 */
class TemplateSaverTest extends TestCase
{
    /**
     * Checks whether templates can be saved.
     *
     * @throws \Exception
     */
    public function testCanSaveTemplate()
    {
        $fileSystemHandlerMock = $this->getMockBuilder(FileSystemWriter::class)
            ->setMethods(array("writeFile"))
            ->getMock();

        $testBoard = new Board(2, 3, 2, true);
        $testBoard->setField(1, 1, true);

        $boardString = "..\r\n.X\r\n..";

        $testDirectory = __DIR__ . "/test";
        $templateSaver = new TemplateSaver($testDirectory);

        if ($fileSystemHandlerMock instanceof FileSystemWriter)
        {
            setPrivateAttribute($templateSaver, "fileSystemWriter", $fileSystemHandlerMock);

            // Save successful
            $fileSystemHandlerMock->expects($this->exactly(2))
                                  ->method("writeFile")
                                  ->withConsecutive(
                                      array($testDirectory . "/Custom/unitTest.txt", $boardString, false, true),
                                      array($testDirectory . "/Custom/unitTestSecond.txt", $boardString, false, false)
                                  )
                                  ->willReturnOnConsecutiveCalls(
                                      $this->returnValue(null),
                                      $this->throwException(new \Exception("File exists."))
                                  );

            $templateSaver->saveCustomTemplate("unitTest", $testBoard, true);

            // Save not successful
            $exceptionOccurred = false;
            try
            {
                $templateSaver->saveCustomTemplate("unitTestSecond", $testBoard, false);
            }
            catch (\Exception $_exception)
            {
                $exceptionOccurred = true;
                $this->assertEquals("File exists.", $_exception->getMessage());
            }
            $this->assertTrue($exceptionOccurred);
        }
    }
}
