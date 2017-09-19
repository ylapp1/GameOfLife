<?php
/**
 * @file
 * @version 0.1
 * @copyright 2017 CN-Consult GmbH
 * @author Yannick Lapp <yannick.lapp@cn-consult.eu>
 */

use GameOfLife\FileSystemHandler;
use PHPUnit\Framework\TestCase;

/**
 * Class FileSystemHandlerTest
 */
class FileSystemHandlerTest extends TestCase
{
    /** @var FileSystemHandler */
    private $fileSystemHandler;
    /** @var string */
    private $testDirectory = __DIR__ . "/../unitTest";

    protected function setUp()
    {
        $this->fileSystemHandler = new FileSystemHandler();
        mkdir($this->testDirectory);
    }

    protected function tearDown()
    {
        rmdir($this->testDirectory);
        unset($this->fileSystemHandler);
    }

    /**
     * @dataProvider createDirectoryProvider()
     * @covers \GameOfLife\FileSystemHandler::createDirectory()
     * @covers \GameOfLife\FileSystemHandler::deleteDirectory()
     *
     * @param string $_directoryName    Test directory name
     * @param array $_subDirectories    Sub directories that shall be created
     */
    public function testCanHandleDirectories(string $_directoryName, array $_subDirectories = array())
    {
        $directoryPath = $this->testDirectory . "/" . $_directoryName;
        $this->assertEquals(false, file_exists($directoryPath));
        $this->assertEquals(true, $this->fileSystemHandler->createDirectory($directoryPath));
        $this->assertEquals(true, file_exists($directoryPath));

        foreach ($_subDirectories as $subDirectory)
        {
            $subDirectoryPath = $this->testDirectory . "/" . $_directoryName . "/" . $subDirectory;
            $this->assertEquals(false, file_exists($subDirectoryPath));
            $this->assertEquals(true, $this->fileSystemHandler->createDirectory($subDirectoryPath));
            $this->assertEquals(true, file_exists($subDirectoryPath));
        }

        $this->assertEquals(true, $this->fileSystemHandler->deleteDirectory($directoryPath, true));
        $this->assertEquals(false, file_exists($directoryPath));
    }

    public function createDirectoryProvider()
    {
        return [
            ["mytest"],
            ["atest", ["1", "2", "3", "43"]],
            ["myyyy", ["ertert", "kuhkuihfauisdhf", "dshfuasdfuhadklshfasdkjfhadkshf", "ksjfhkasjdhfkasdjhf"]],
            ["lastDirectory"]
        ];
    }

    /**
     * @covers \GameOfLife\FileSystemHandler::createDirectory()
     * @covers \GameOfLife\FileSystemHandler::deleteDirectory()
     */
    public function testCanDetectExistingDirectory()
    {
        $directoryPath = $this->testDirectory . "/atest";
        $this->assertEquals(false, file_exists($directoryPath));
        $this->assertEquals(true, $this->fileSystemHandler->createDirectory($directoryPath));
        $this->assertEquals(true, file_exists($directoryPath));

        // try to create the existing directory again
        $this->assertEquals(false, $this->fileSystemHandler->createDirectory($directoryPath));
        $this->assertEquals(true, $this->fileSystemHandler->deleteDirectory($directoryPath, true));
        $this->assertEquals(false, file_exists($directoryPath));
    }

    /**
     * @dataProvider writeFileProvider
     * @covers \GameOfLife\FileSystemHandler::writeFile()
     * @covers \GameOfLife\FileSystemHandler::readFile()
     * @covers \GameOfLife\FileSystemHandler::deleteFile()
     *
     * @param string $_fileName     File name of the test file
     * @param string $_content      Content of the test file
     * @param array $_expectedContent   Expected content that is read by FileSystemHandler::readFile()
     */
    public function testCanHandleFiles(string $_fileName, string $_content, array $_expectedContent)
    {
        $outputPath = $this->testDirectory . "/" . $_fileName;

        $this->assertEquals(false, file_exists($outputPath));
        $this->assertEquals(true, $this->fileSystemHandler->writeFile($this->testDirectory, $_fileName, $_content));
        $this->assertEquals(true, file_exists($outputPath));
        $this->assertEquals($_expectedContent, $this->fileSystemHandler->readFile($outputPath));

        $this->fileSystemHandler->deleteFile($outputPath);
        $this->assertEquals(false, file_exists($outputPath));
    }

    public function writeFileProvider()
    {
        return [
            "Single line of text" => ["mytest.txt", "Hello World!", ["Hello World!"]],
            "Two lines with line break" => ["asecondtest.txt", "My name is \n ...", ["My name is ", " ..."]],
            "Empty line after line break" => ["emptyline.txt", "test\n", ["test"]],
            "Empty line before line break" => ["emptylinefirst.txt", "\ntest", ["test"]],
            "Three lines" => ["threelines.txt", "testst\nteststst\ntserse", ["testst", "teststst", "tserse"]]
        ];
    }


    public function testCanDetectExistingFile()
    {
        $filePath = $this->testDirectory . "/mytest.txt";

        $this->assertEquals(false, file_exists($filePath));
        $this->fileSystemHandler->writeFile($this->testDirectory, "mytest.txt", "Hello World!");
        $this->assertEquals(true, file_exists($filePath));

        $fileContent = array("Hello World!");
        $this->assertEquals($fileContent, $this->fileSystemHandler->readFile($filePath));

        $error = $this->fileSystemHandler->writeFile($this->testDirectory, "mytest.txt", "Hello universe!");
        $this->assertEquals(\GameOfLife\FileSystemHandler::ERROR_FILE_EXISTS, $error);

        // Check whether content of file was changed
        $this->assertEquals($fileContent, $this->fileSystemHandler->readFile($filePath));

        $this->fileSystemHandler->writeFile($this->testDirectory, "mytest.txt", "Hello universe!", true);
        $this->assertEquals(array("Hello universe!"), $this->fileSystemHandler->readFile($filePath));

        $this->fileSystemHandler->deleteFile($filePath);
        $this->assertEquals(false, file_exists($filePath));
    }
}
