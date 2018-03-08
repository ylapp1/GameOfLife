<?php
/**
 * @file
 * @version 0.1
 * @copyright 2018 CN-Consult GmbH
 * @author Yannick Lapp <yannick.lapp@cn-consult.eu>
 */

use GameOfLife\Board;
use GameOfLife\GameOfLife;
use Input\TemplateInput;
use Input\UserInput;
use Output\ConsoleOutput;
use Output\VideoOutput;
use PHPUnit\Framework\TestCase;
use Rule\Two45Rule;

/**
 * Checks whether the GameOfLife class works as expected.
 */
class GameOfLifeTest extends TestCase
{
    /**
     * Checks whether the initialize() function works as expected.
     *
     * @param array $_optionParserReturnValues The return values of the option parser in the format array("method", "returnValue")
     * @param bool $_expectedReturnValue The expected return value of the initialize() function
     *
     * @dataProvider canBeInitializedProvider()
     * @covers \GameOfLife\GameOfLife::__construct()
     * @covers \GameOfLife\GameOfLife::initialize()
     *
     * @throws ReflectionException
     */
    public function testCanBeInitialized(array $_optionParserReturnValues, Bool $_expectedReturnValue)
    {
        $optionHandlerMock = $this->getMockBuilder(\OptionHandler\OptionHandler::class)
                                  ->getMock();

        $optionParserMock = $this->getMockBuilder(\OptionHandler\OptionParser::class)
                                 ->disableOriginalConstructor()
                                 ->getMock();

        $optionHandlerMock->expects($this->exactly(1))
                          ->method("initializeOptions")
                          ->willReturn(null);

        $optionHandlerMock->expects($this->exactly(count($_optionParserReturnValues)))
                          ->method("optionParser")
                          ->willReturn($optionParserMock);


        foreach ($_optionParserReturnValues as $methodName => $returnValue)
        {
            $optionParserMock->expects($this->exactly(1))
                             ->method($methodName)
                             ->willReturn($returnValue);
        }


        $gameOfLife = new GameOfLife();

        $optionsMock = $this->getMockBuilder(\Ulrichsg\Getopt::class)
                            ->getMock();

        $reflectionClass = new ReflectionClass(\GameOfLife\GameOfLife::class);

        $reflectionProperty = $reflectionClass->getProperty("optionHandler");
        $reflectionProperty->setAccessible(true);
        $reflectionProperty->setValue($gameOfLife, $optionHandlerMock);

        $reflectionProperty = $reflectionClass->getProperty("options");
        $reflectionProperty->setAccessible(true);
        $reflectionProperty->setValue($gameOfLife, $optionsMock);


        $canSimulationBeStarted = $gameOfLife->initialize();
        $this->assertEquals($_expectedReturnValue, $canSimulationBeStarted);
    }

    /**
     * DataProvider for GameOfLifeTest::testCanBeInitialized().
     *
     * @return array Test values in the format array(methods => returnValues, expectedReturnValue)
     */
    public function canBeInitializedProvider()
    {
        return array(
            "Parsed general option = true" => array(
                array(
                    "parseGeneralOptions" => true,
                ),
                false
            ),
            "Parsed general option = false, Board = false" => array(
                array(
                    "parseGeneralOptions" => false,
                    "parseBoardOptions" => false
                ),
                false
            ),
            "Parsed general option = false, Board, Input, Output and Rule valid" => array(
                array(
                    "parseGeneralOptions" => false,
                    "parseBoardOptions" => new Board(1, 2, 3, true),
                    "parseInputOptions" => new UserInput(),
                    "parseOutputOptions" => new VideoOutput(),
                    "parseRuleOptions" => new Two45Rule()
                ),
                true
            )
        );
    }

    /**
     * Checks whether a simulation can be successfully started and stopped.
     *
     * @param int $_amountLoops The amount of loops for which the simulation will be allowed to run
     * @param String $_endLoopMethodName The name of the method that will return true in order to end the loop
     *
     * @dataProvider startSimulationProvider()
     *
     * @throws ReflectionException
     */
    public function testCanStartSimulation(int $_amountLoops, String $_endLoopMethodName)
    {
        $gameOfLife = new GameOfLife();

        $optionsMock = $this->getMockBuilder(\Ulrichsg\Getopt::class)
                            ->getMock();

        $optionsMock->expects($this->exactly(4))
                    ->method("getOption")
                    ->withConsecutive(array("template"), array("templatePosX"), array("templatePosY"), array("template"))
                    ->willReturn("blinker", null, null, "blinker");

        $input = new TemplateInput();
        $output = new ConsoleOutput();
        $board = new Board(5, 5, 200, true);
        $gameLogicMock = $this->getMockBuilder(\GameOfLife\GameLogic::class)
                              ->disableOriginalConstructor()
                              ->getMock();

        $counter = 0;
        $loopCounter = 0;
        $endLoopMethodNames = array("isMaxStepsReached", "isLoopDetected", "isBoardEmpty");

        while ($loopCounter < $_amountLoops)
        {
            foreach ($endLoopMethodNames as $endLoopMethodName)
            {
                $gameLogicMock->expects($this->at($counter))
                              ->method($endLoopMethodName)
                              ->willReturn(false);

                $counter++;
            }

            $gameLogicMock->expects($this->at($counter))
                          ->method("calculateNextBoard")
                          ->willReturn(null);
            $counter++;

            $loopCounter++;
        }

        $endLoopMethodPosition = array_search($_endLoopMethodName, $endLoopMethodNames) + $counter;

        $gameLogicMock->expects($this->at($endLoopMethodPosition))
                      ->method($_endLoopMethodName)
                      ->willReturn(true);


        $reflectionClass = new ReflectionClass(\GameOfLife\GameOfLife::class);

        $reflectionProperty = $reflectionClass->getProperty("options");
        $reflectionProperty->setAccessible(true);
        $reflectionProperty->setValue($gameOfLife, $optionsMock);

        $reflectionProperty = $reflectionClass->getProperty("input");
        $reflectionProperty->setAccessible(true);
        $reflectionProperty->setValue($gameOfLife, $input);

        $reflectionProperty = $reflectionClass->getProperty("output");
        $reflectionProperty->setAccessible(true);
        $reflectionProperty->setValue($gameOfLife, $output);

        $reflectionProperty = $reflectionClass->getProperty("board");
        $reflectionProperty->setAccessible(true);
        $reflectionProperty->setValue($gameOfLife, $board);

        $reflectionProperty = $reflectionClass->getProperty("gameLogic");
        $reflectionProperty->setAccessible(true);
        $reflectionProperty->setValue($gameOfLife, $gameLogicMock);

        // Hide output
        $this->expectOutputRegex("/.*Starting the simulation.*Simulation finished.*/s");
        $gameOfLife->startSimulation();
    }

    /**
     * DataProvider for GameOfLifeTest::testCanStartSimulation().
     *
     * @return array Test values in the format array(amount loops, loopEndFunction)
     */
    public function startSimulationProvider()
    {
        return array(
            "Max steps reached before first step" => array(0, "isMaxStepsReached"),
            "Loop detected reached after 5 steps" => array(5, "isLoopDetected"),
            "Board empty detected after 3 steps" => array(3, "isBoardEmpty")
        );
    }
}
