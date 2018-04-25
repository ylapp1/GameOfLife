/**
 * @file
 * @version 0.1
 * @copyright 2018 CN-Consult GmbH
 * @author Yannick Lapp <yannick.lapp@cn-consult.eu>
 */
#include "ConsoleInformationFetcher.h"
#include "CursorPositioner.h"
#include <stdio.h>
#include <stdlib.h>

int main(int argc, char *argv[])
{
    HANDLE stdOutputHandle = CreateFile("CONOUT$", GENERIC_READ | GENERIC_WRITE, 0, NULL, OPEN_EXISTING, FILE_ATTRIBUTE_NORMAL, NULL);
    ConsoleInformationFetcher consoleInformationFetcher = ConsoleInformationFetcher(stdOutputHandle);

    /*
     * The arguments in argv are:
     * [0] = Path to the .exe file
     * [1] = selected option
     * [2] ... = option parameters
     */
    if (argc < 2)
    {
        printf("No options passed.\nValid options are: printNumberOfRows, printNumberOfColumns, setCursor <x><y>");
        return 1;
    }

    char* inputOption = argv[1];

    if (strcmp(inputOption, "printNumberOfRows") == 0) printf("%d", consoleInformationFetcher.getNumberOfConsoleRows());
    else if (strcmp(inputOption, "printNumberOfColumns") == 0) printf("%d", consoleInformationFetcher.getNumberOfConsoleColumns());
    else if (strcmp(inputOption, "setCursor") == 0)
    {
        if (argc < 4)
        {
            printf("Error: Not enough arguments.");
            return 1;
        }

        CursorPositioner cursorPositioner = CursorPositioner(stdOutputHandle, consoleInformationFetcher);
        int x, y;

        x = atoi(argv[2]);
        y = atoi(argv[3]);

        cursorPositioner.setRelativeConsoleCursorPosition(x, y);
    }
    else
    {
        printf("Invalid option name entered.");	
        return 1;
    }

    return 0;
}
