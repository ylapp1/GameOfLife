<?php
/**
 * @file
 * @version 0.1
 * @copyright 2017 CN-Consult GmbH
 * @author Yannick Lapp <yannick.lapp@cn-consult.eu>
 */

use Utils\FileSystemHandler;
use PHPUnit\Framework\TestCase;

/**
 * Checks whether \Utils\FileSystemHandler works as expected
 */
class FileSystemHandlerTest extends TestCase
{
    /** @var FileSystemHandler */
    private $fileSystemHandler;
    /** @var string */
    private $testDirectory = __DIR__ . "/../FileSystemHandlerTest";

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
     * @covers \Utils\FileSystemHandler::createDirectory()
     * @covers \Utils\FileSystemHandler::deleteDirectory()
     *
     * @param string $_directoryName    Test directory name
     * @param array $_subDirectories    Sub directories that shall be created
     */
    public function testCanHandleDirectories(string $_directoryName, array $_subDirectories = array())
    {
        $directoryPath = $this->testDirectory . "/" . $_directoryName;
        $this->assertFalse(file_exists($directoryPath));
        $this->assertEquals(FileSystemHandler::NO_ERROR, $this->fileSystemHandler->createDirectory($directoryPath));
        $this->assertTrue(file_exists($directoryPath));

        foreach ($_subDirectories as $subDirectory)
        {
            $subDirectoryPath = $this->testDirectory . "/" . $_directoryName . "/" . $subDirectory;
            $this->assertFalse(file_exists($subDirectoryPath));
            $this->assertEquals(FileSystemHandler::NO_ERROR, $this->fileSystemHandler->createDirectory($subDirectoryPath));
            $this->assertTrue(file_exists($subDirectoryPath));
        }

        $this->assertEquals(FileSystemHandler::NO_ERROR, $this->fileSystemHandler->deleteDirectory($directoryPath, true));
        $this->assertFalse(file_exists($directoryPath));
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
     * @covers \Utils\FileSystemHandler::createDirectory()
     * @covers \Utils\FileSystemHandler::deleteDirectory()
     */
    public function testCanDetectExistingDirectory()
    {
        $directoryPath = $this->testDirectory . "/atest";
        $this->assertFalse(file_exists($directoryPath));
        $this->assertEquals(FileSystemHandler::NO_ERROR, $this->fileSystemHandler->createDirectory($directoryPath));
        $this->assertTrue(file_exists($directoryPath));

        // try to create the existing directory again
        $this->assertEquals(FileSystemHandler::ERROR_DIRECTORY_EXISTS, $this->fileSystemHandler->createDirectory($directoryPath));
        $this->assertEquals(FileSystemHandler::NO_ERROR, $this->fileSystemHandler->deleteDirectory($directoryPath, true));
        $this->assertFalse(file_exists($directoryPath));
    }

    /**
     * @dataProvider writeFileProvider
     * @covers \Utils\FileSystemHandler::writeFile()
     * @covers \Utils\FileSystemHandler::readFile()
     * @covers \Utils\FileSystemHandler::deleteFile()
     *
     * @param string $_fileName     File name of the test file
     * @param string $_content      Content of the test file
     * @param array $_expectedContent   Expected content that is read by FileSystemHandler::readFile()
     */
    public function testCanHandleFiles(string $_fileName, string $_content, array $_expectedContent)
    {
        $outputPath = $this->testDirectory . "/" . $_fileName;

        $this->assertFalse(file_exists($outputPath));
        $this->assertEquals(FileSystemHandler::NO_ERROR, $this->fileSystemHandler->writeFile($this->testDirectory, $_fileName, $_content));
        $this->assertTrue(file_exists($outputPath));
        $this->assertEquals($_expectedContent, $this->fileSystemHandler->readFile($outputPath));

        $this->assertEquals(FileSystemHandler::NO_ERROR, $this->fileSystemHandler->deleteFile($outputPath));
        $this->assertFalse(file_exists($outputPath));

        $this->assertEquals(FileSystemHandler::ERROR_FILE_NOT_EXISTS, $this->fileSystemHandler->deleteFile("nonExistingFile.notExisting"));
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

    /**
     * @covers \Utils\FileSystemHandler::writeFile()
     * @covers \Utils\FileSystemHandler::readFile()
     * @covers \Utils\FileSystemHandler::deleteFile()
     */
    public function testCanDetectExistingFile()
    {
        $filePath = $this->testDirectory . "/mytest.txt";

        $this->assertFalse(file_exists($filePath));
        $error = $this->fileSystemHandler->writeFile($this->testDirectory, "mytest.txt", "Hello World!");
        $this->assertEquals(FileSystemHandler::NO_ERROR, $error);
        $this->assertTrue(file_exists($filePath));

        $this->assertEquals(array("Hello World!"), $this->fileSystemHandler->readFile($filePath));

        $error = $this->fileSystemHandler->writeFile($this->testDirectory, "mytest.txt", "Hello universe!");
        $this->assertEquals(FileSystemHandler::ERROR_FILE_EXISTS, $error);

        // Check whether content of file was changed
        $error = $this->fileSystemHandler->writeFile($this->testDirectory, "mytest.txt", "Hello universe!", true);
        $this->assertEquals(FileSystemHandler::NO_ERROR, $error);
        $this->assertEquals(array("Hello universe!"), $this->fileSystemHandler->readFile($filePath));

        $this->assertEquals(FileSystemHandler::NO_ERROR, $this->fileSystemHandler->deleteFile($filePath));
        $this->assertFalse(file_exists($filePath));
    }
}
