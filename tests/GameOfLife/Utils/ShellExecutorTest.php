<?php
/**
 * @file
 * @version 0.1
 * @copyright 2018 CN-Consult GmbH
 * @author Yannick Lapp <yannick.lapp@cn-consult.eu>
 */

// Must add this test to the same namespace like ShellExecutor in order to be able to override exec
namespace Utils;

/**
 * Overridden exec function for this unit test.
 * Writes the command to the returnValue.
 *
 * @param String $_command The command
 * @param array $_output The list of output lines
 * @param int $_returnValue The return value
 */
function exec(String $_command, array &$_output, int &$_returnValue)
{
    $_returnValue = $_command;
}


use PHPUnit\Framework\TestCase;

/**
 * Checks whether the ShellExecutor class works as expected.
 */
class ShellExecutorTest extends TestCase
{
    /**
     * Checks whether a command can be successfully executed.
     *
     * @covers \Utils\ShellExecutor::__construct()
     * @covers \Utils\ShellExecutor::executeCommand()
     */
    public function testCanExecuteCommand()
    {
        $shellExecutor = new ShellExecutor("win");

        $returnValue = $shellExecutor->executeCommand("hello");
        $this->assertEquals("hello", $returnValue);
    }

    /**
     * Checks whether the output can be redirected.
     *
     * @param String $_osName The os name
     * @param String $_command The command
     * @param String $_expectedCommand The expected command as generated by the ShellExecutor
     *
     * @dataProvider redirectOutputProvider()
     *
     * @covers \Utils\ShellExecutor::__construct()
     * @covers \Utils\ShellExecutor::executeCommand()
     * @covers \Utils\ShellExecutor::getOutputHideRedirect()
     */
    public function testCanRedirectOutput(String $_osName, String $_command, String $_expectedCommand)
    {
        $shellExecutor = new ShellExecutor($_osName);
        $returnValue = $shellExecutor->executeCommand($_command, true);
        $this->assertEquals($_expectedCommand, $returnValue);
    }

    /**
     * DataProvider for ShellExecutorTest::testCanRedirectOutput().
     *
     * @return array The test values in the format array(osName, command, expectedCommand)
     */
    public function redirectOutputProvider()
    {
        return array(
            "Windows" => array("win", "hello", "hello 2>NUL"),
            "Linux" => array("linux", "hello", "hello 2>/dev/null"),
            "Other" => array("other", "hello", "hello 2>output.txt")
        );
    }
}
