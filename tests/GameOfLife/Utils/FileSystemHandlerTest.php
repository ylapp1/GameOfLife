<?php
/**
 * @file
 * @version 0.1
 * @copyright 2017-2018 CN-Consult GmbH
 * @author Yannick Lapp <yannick.lapp@cn-consult.eu>
 */

use Utils\FileSystem\FileSystemReader;
use Utils\FileSystem\FileSystemWriter;
use PHPUnit\Framework\TestCase;

/**
 * Checks whether \Utils\FileSystemHandler works as expected.
 */
class FileSystemHandlerTest extends TestCase
{
    /** @var FileSystemWriter */
    private $fileSystemHandler;
    /** @var string */
    private $testDirectory;

    private $directorySeparator;

    protected function setUp()
    {
        if (stristr(PHP_OS, "win")) $this->directorySeparator = "\\";
        else $this->directorySeparator = "/";

        $this->testDirectory = __DIR__ . $this->directorySeparator . ".." . $this->directorySeparator . "FileSystemHandlerTest";

        $this->fileSystemHandler = new FileSystemWriter();
        mkdir($this->testDirectory);
    }

    protected function tearDown()
    {
        rmdir($this->testDirectory);
        unset($this->fileSystemHandler);
    }

    /**
     * @dataProvider createDirectoryProvider()
     * @covers \Utils\FileSystem\FileSystemWriter::createDirectory()
     * @covers \Utils\FileSystem\FileSystemWriter::deleteDirectory()
     *
     * @param string $_directoryName    Test directory name
     * @param array $_subDirectories    Sub directories that shall be created
     *
     * @throws \Exception
     */
    public function testCanHandleDirectories(string $_directoryName, array $_subDirectories = array())
    {
        $directoryPath = $this->testDirectory . "/" . $_directoryName;
        $this->assertFalse(file_exists($directoryPath));

        $exceptionOccurred = false;
        try
        {
            $this->fileSystemHandler->createDirectory($directoryPath);
        }
        catch (\Exception $_exception)
        {
            $exceptionOccurred = true;
        }
        $this->assertFalse($exceptionOccurred);
        $this->assertTrue(file_exists($directoryPath));

        foreach ($_subDirectories as $subDirectory)
        {
            $subDirectoryPath = $this->testDirectory . "/" . $_directoryName . "/" . $subDirectory;
            $this->assertFalse(file_exists($subDirectoryPath));

            $exceptionOccurred = false;
            try
            {
                $this->fileSystemHandler->createDirectory($subDirectoryPath);
            }
            catch (\Exception $_exception)
            {
                $exceptionOccurred = true;
            }
            $this->assertFalse($exceptionOccurred);
            $this->assertTrue(file_exists($subDirectoryPath));
        }

        $exceptionOccurred = false;
        try
        {
            $this->fileSystemHandler->deleteDirectory($directoryPath, true);
        }
        catch (\Exception $_exception)
        {
            $exceptionOccurred = true;
        }
        $this->assertFalse($exceptionOccurred);
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
     * @covers \Utils\FileSystem\FileSystemWriter::createDirectory()
     * @covers \Utils\FileSystem\FileSystemWriter::deleteDirectory()
     */
    public function testCanDetectExistingDirectory()
    {
        $directoryPath = $this->testDirectory . $this->directorySeparator . "atest";
        $this->assertFalse(file_exists($directoryPath));

        $exceptionOccurred = false;
        try
        {
            $this->fileSystemHandler->createDirectory($directoryPath);
        }
        catch (\Exception $_exception)
        {
            $exceptionOccurred = true;
        }
        $this->assertFalse($exceptionOccurred);
        $this->assertTrue(file_exists($directoryPath));

        // try to create the existing directory again
        $exceptionOccurred = false;
        try
        {
            $this->fileSystemHandler->createDirectory($directoryPath);
        }
        catch (\Exception $_exception)
        {
            $exceptionOccurred = true;
            $this->assertEquals("The directory \"" . $directoryPath . "\" already exists.", $_exception->getMessage());
        }
        $this->assertTrue($exceptionOccurred);

        $exceptionOccurred = false;
        try
        {
            $this->fileSystemHandler->deleteDirectory($directoryPath, true);
        }
        catch (\Exception $_exception)
        {
            $exceptionOccurred = true;
        }
        $this->assertFalse($exceptionOccurred);
        $this->assertFalse(file_exists($directoryPath));
    }

    /**
     * @dataProvider writeFileProvider
     * @covers \Utils\FileSystem\FileSystemWriter::writeFile()
     * @covers \Utils\FileSystem\FileSystemReader::readFile()
     * @covers \Utils\FileSystem\FileSystemWriter::deleteFile()
     *
     * @param string $_fileName         File name of the test file
     * @param string $_content          Content of the test file
     * @param array $_expectedContent   Expected content that is read by FileSystemHandler::readFile()
     */
    public function testCanHandleFiles(string $_fileName, string $_content, array $_expectedContent)
    {
        $outputPath = $this->testDirectory . $this->directorySeparator . $_fileName;
        $this->assertFalse(file_exists($outputPath));
        $fileSystemReader = new FileSystemReader();

        $exceptionOccurred  = false;
        try
        {
            $this->fileSystemHandler->writeFile($this->testDirectory . "/" . $_fileName, $_content);
        }
        catch (\Exception $_exception)
        {
            $exceptionOccurred = true;
        }
        $this->assertFalse($exceptionOccurred);
        $this->assertTrue(file_exists($outputPath));

        $exceptionOccurred = false;
        try
        {
            $fileContent = $fileSystemReader->readFile($outputPath);
        }
        catch (\Exception $_exception)
        {
            $exceptionOccurred = true;
            $fileContent = array();
        }
        $this->assertFalse($exceptionOccurred);
        $this->assertEquals($_expectedContent, $fileContent);

        $exceptionOccurred = false;
        try
        {
            $this->fileSystemHandler->deleteFile($outputPath);
        }
        catch (\Exception $_exception)
        {
            $exceptionOccurred = true;
        }
        $this->assertFalse($exceptionOccurred);
        $this->assertFalse(file_exists($outputPath));

        $exceptionOccurred = false;
        try
        {
            $this->fileSystemHandler->deleteFile("nonExistingFile.notExisting");
        }
        catch (\Exception $_exception)
        {
            $exceptionOccurred = true;
            $this->assertEquals("The file \"nonExistingFile.notExisting\" does not exist.", $_exception->getMessage());
        }
        $this->assertTrue($exceptionOccurred);
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
     * @covers \Utils\FileSystem\FileSystemWriter::writeFile()
     * @covers \Utils\FileSystem\FileSystemReader::readFile()
     * @covers \Utils\FileSystem\FileSystemWriter::deleteFile()
     */
    public function testCanDetectExistingFile()
    {
        $filePath = $this->testDirectory . $this->directorySeparator . "mytest.txt";
        $fileSystemReader = new FileSystemReader();


        $this->assertFalse(file_exists($filePath));

        // Check whether file can be created with content
        $exceptionOccurred = false;
        try
        {
            $this->fileSystemHandler->writeFile($this->testDirectory . "/mytest.txt", "Hello World!");
        }
        catch (\Exception $_exception)
        {
            $exceptionOccurred = true;
        }
        $this->assertFalse($exceptionOccurred);
        $this->assertTrue(file_exists($filePath));

        // Check whether file content can be read
        $exceptionOccurred = false;
        try
        {
            $fileContent = $fileSystemReader->readFile($filePath);
        }
        catch (\Exception $_exception)
        {
            $exceptionOccurred = true;
            $fileContent = array();
        }
        $this->assertFalse($exceptionOccurred);
        $this->assertEquals(array("Hello World!"), $fileContent);

        // Check whether trying to rewrite the file fails
        $exceptionOccurred = false;
        try
        {
            $this->fileSystemHandler->writeFile($this->testDirectory . "/mytest.txt", "Hello universe!");
        }
        catch (\Exception $_exception)
        {
            $this->assertEquals("The file already exists.", $_exception->getMessage());
            $exceptionOccurred = true;
        }
        $this->assertTrue($exceptionOccurred);

        // Check whether content of file changes
        $exceptionOccurred = false;
        try
        {
            $this->fileSystemHandler->writeFile($this->testDirectory . "/mytest.txt", "Hello universe!", false, true);
        }
        catch (\Exception $_exception)
        {
            $exceptionOccurred = true;
        }
        $this->assertFalse($exceptionOccurred);

        $exceptionOccurred = false;
        try
        {
            $fileContent = $fileSystemReader->readFile($filePath);
        }
        catch (\Exception $_exception)
        {
            $exceptionOccurred = true;
            $fileContent = array();
        }
        $this->assertFalse($exceptionOccurred);
        $this->assertEquals(array("Hello universe!"), $fileContent);

        $exceptionOccurred = false;
        try
        {
            $this->fileSystemHandler->deleteFile($filePath);
        }
        catch (\Exception $_exception)
        {
            $exceptionOccurred = true;
        }
        $this->assertFalse($exceptionOccurred);
        $this->assertFalse(file_exists($filePath));
    }

    /**
     * @covers \Utils\FileSystem\FileSystemReader::getFileList()
     */
    public function testCanGetFileList()
    {
        $fileSystemReader = new FileSystemReader();

        $fileNames = array("Hello", "myFile", "myPersonalFile", "myPersonalTest", "thisIsAFile", "ThisIsMyFile");
        sort($fileNames);

        foreach ($fileNames as $index => $fileName)
        {
            touch($this->testDirectory . $this->directorySeparator . $fileName);
        }

        $exceptionOccurred = false;
        try
        {
            $files = $fileSystemReader->getFileList($this->testDirectory);
        }
        catch (\Exception $_exception)
        {
            $exceptionOccurred = true;
            $files = array();
        }
        $this->assertFalse($exceptionOccurred);


        foreach ($files as $index => $file)
        {
            $fileName = $this->testDirectory . $this->directorySeparator . $fileNames[$index];
            $this->assertEquals($this->testDirectory . $this->directorySeparator . $fileNames[$index], $file);
            unlink($fileName);
        }
    }

    /**
     * Checks whether a file can be found recursive.
     *
     * @param String $_directories
     * @param String $_filePath
     * @param String $_searchFilename
     * @param Bool $_expectsFilePath Indicates whether the FileSystemHadresult will be a filepath or false
     *
     * @dataProvider findFileRecursiveProvider()
     *
     * @covers \Utils\FileSystem\FileSystemReader::findFileRecursive()
     */
    public function testCanFindFileRecursive(String $_directories, String $_filePath, String $_searchFilename, Bool $_expectsFilePath = true)
    {
        $testDirectory = __DIR__ . $this->directorySeparator . "test";
        $testFilePath = $testDirectory . $this->directorySeparator  . $_filePath;

        $fileSystemReader = new FileSystemReader();

        mkdir($testDirectory . $this->directorySeparator . $_directories, 0777, true);
        touch($testFilePath);

        $exceptionOccurred = false;
        try
        {
            $filePath = $fileSystemReader->findFileRecursive($testDirectory, $_searchFilename);
        }
        catch (\Exception $_exception)
        {
            $exceptionOccurred = true;
            $filePath = "";
        }
        $this->assertFalse($exceptionOccurred);

        if ($_expectsFilePath) $this->assertEquals($testFilePath, $filePath);
        else $this->assertEmpty($filePath);

        // Delete the test file
        unlink($testFilePath);

        // Delete the test sub directories
        $directories = explode($this->directorySeparator, $_directories);

        for ($i = count($directories); $i > 0; $i--)
        {
            $removeDirectory = "";

            for ($j = 0; $j < $i; $j++)
            {
                $removeDirectory .= $this->directorySeparator . $directories[$j];
            }

            rmdir($testDirectory . $removeDirectory);
        }

        // Delete the test directory
        rmdir($testDirectory);
    }

    /**
     * DataProvider for FileSystemHandlerTest::testCanFindFileRecursive().
     *
     * @return array Test values in the format array(testDirectoryStructure, filePath, searchFileName, expectsFilePath)
     */
    public function findFileRecursiveProvider()
    {
        if (stristr(PHP_OS, "win")) $directorySeparator = "\\";
        else $directorySeparator = "/";

        return array(
            "Existing file in second sub folder" => array(
                "testing"  . $directorySeparator . "directory" . $directorySeparator . "mytest",
                "testing" . $directorySeparator . "directory" . $directorySeparator . "test.txt",
                "test.txt"
            ),
            "Existing file in first sub folder" => array(
                "testing" . $directorySeparator . "directory" . $directorySeparator . "mytest",
                "testing" . $directorySeparator . "second.txt",
                "second.txt"
            ),
            "Existing file in third sub folder" => array(
                "testing" . $directorySeparator . "directory" . $directorySeparator . "mytest",
                "testing" . $directorySeparator . "directory" . $directorySeparator . "mytest" . $directorySeparator . "thisIsMyTest.txt",
                "thisIsMyTest.txt"
            ),
            "Not existing file" => array(
                "testing" . $directorySeparator . "directory" . $directorySeparator . "mytest",
                "testing" . $directorySeparator . "directory" . $directorySeparator . "mytest" . $directorySeparator . "test.txt",
                "nottest.txt",
                false
            )
        );
    }
}
