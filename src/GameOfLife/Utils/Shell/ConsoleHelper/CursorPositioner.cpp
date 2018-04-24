/**
 * @file
 * @version 0.1
 * @copyright 2018 CN-Consult GmbH
 * @author Yannick Lapp <yannick.lapp@cn-consult.eu>
 */
#include "CursorPositioner.h"
#include "ConsoleInformationFetcher.h"
#include <stdio.h>
#include <windows.h>

/**
 * CursorPositioner default constructor.
 */
CursorPositioner::CursorPositioner()
{
	this->stdOutputHandle = GetStdHandle(STD_OUTPUT_HANDLE);
	this->consoleInformationFetcher = ConsoleInformationFetcher(this->stdOutputHandle);
}

/**
 * CursorPositioner constructor.
 *
 * @param _stdOutputHandle The handle to the console screen buffer
 */
CursorPositioner::CursorPositioner(HANDLE _stdOutputHandle, ConsoleInformationFetcher _consoleInformationFetcher)
{
	this->stdOutputHandle = _stdOutputHandle;
	this->consoleInformationFetcher = _consoleInformationFetcher;
}

/**
 * Sets the cursor position to x|y inside the current window dimensions.
 *
 * @param int _x The relative X-Coordinate of the cursor position
 * @param int _y The relative Y-Coordinate of the cursor position
 */
void CursorPositioner::setRelativeConsoleCursorPosition(int _x, int _y)
{
	// Check X-Coordinate
	if (_x < 0)
	{
		printf("Error: The x value exceeds the left console border (x = %d).\n", _x);
	}
	else if (_x > this->consoleInformationFetcher.getNumberOfConsoleColumns())
	{
		printf("Error: The x value exceeds the right console border (x = %d).\n", _x);
	}
	
	// Check Y-Coordinate
	else if (_y < 0)
	{
	    printf("Error: The y value exceeds the top console border (y = %d).\n", _y);
	}
	else if (_y > this->consoleInformationFetcher.getNumberOfConsoleRows())
	{
	    printf("Error: The y value exceeds the bottom console border (y = %d).\n", _y);
	}
	
	
	else
	{
		int numberOfConsoleRows = consoleInformationFetcher.getNumberOfConsoleRows();
		int absoluteY = consoleInformationFetcher.getNumberOfBottomDisplayline() - numberOfConsoleRows + _y;
		
		this->setAbsoluteConsoleCursorPosition(_x, absoluteY);
	}
}

/**
 * Sets the cursor position to x|y in all of the output rows.
 *
 * @param int _x The new x-Coordinate of the console cursor
 * @param int _y The new y-Coordinate of the console cursor
 */
void CursorPositioner::setAbsoluteConsoleCursorPosition(int _x, int _y)
{	
    COORD coord = { (SHORT)_x, (SHORT)_y };
    SetConsoleCursorPosition(this->stdOutputHandle, coord);
}
