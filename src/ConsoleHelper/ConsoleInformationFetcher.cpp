/**
 * @file
 * @version 0.1
 * @copyright 2018 CN-Consult GmbH
 * @author Yannick Lapp <yannick.lapp@cn-consult.eu>
 */
#include "ConsoleInformationFetcher.h"
#include <windows.h>

/**
 * ConsoleInformationFetcher default constructor.
 */
ConsoleInformationFetcher::ConsoleInformationFetcher()
{
    this->stdOutputHandle = CreateFile("CONOUT$", GENERIC_READ | GENERIC_WRITE, 0, NULL, OPEN_EXISTING, FILE_ATTRIBUTE_NORMAL, NULL);
}

/**
 * ConsoleInformationFetcher constructor.
 *
 * @param HANDLE _stdOutputHandle The handle to the console screen buffer
 */
ConsoleInformationFetcher::ConsoleInformationFetcher(HANDLE _stdOutputHandle)
{
    this->stdOutputHandle = _stdOutputHandle;
}

/**
 * Returns the number of console rows.
 *
 * @return int The number of console rows
 */
int ConsoleInformationFetcher::getNumberOfConsoleRows()
{
    CONSOLE_SCREEN_BUFFER_INFO csbi;
    int numberOfRows;

    GetConsoleScreenBufferInfo(this->stdOutputHandle, &csbi);
    numberOfRows = csbi.srWindow.Bottom - csbi.srWindow.Top + 1;

    return numberOfRows;
}

/**
 * Returns the number of console columns.
 *
 * @return int The number of console columns
 */
int ConsoleInformationFetcher::getNumberOfConsoleColumns()
{
    CONSOLE_SCREEN_BUFFER_INFO csbi;
    int numberOfColumns;

    GetConsoleScreenBufferInfo(this->stdOutputHandle, &csbi);
    numberOfColumns = csbi.srWindow.Right - csbi.srWindow.Left + 1;

    return numberOfColumns;
}

/**
 * Returns the number of the output row at the bottom of the current window.
 *
 * @return int The number of the output row at the bottom of the current window
 */
int ConsoleInformationFetcher::getNumberOfBottomDisplayRow()
{
    CONSOLE_SCREEN_BUFFER_INFO csbi;
    GetConsoleScreenBufferInfo(this->stdOutputHandle, &csbi);

    return csbi.srWindow.Bottom;
}
