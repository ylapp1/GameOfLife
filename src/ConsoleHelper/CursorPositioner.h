/**
 * @file
 * @version 0.1
 * @copyright 2018 CN-Consult GmbH
 * @author Yannick Lapp <yannick.lapp@cn-consult.eu>
 */
#include "ConsoleInformationFetcher.h"
#include <windows.h>

#ifndef CURSORPOSITIONER_H
#define CURSORPOSITIONER_H

class CursorPositioner
{
    public:
        // Constructor
        CursorPositioner();
        CursorPositioner(HANDLE, ConsoleInformationFetcher);

        // Methods
        void setRelativeConsoleCursorPosition(int, int);

    private:
        // Attributes
        ConsoleInformationFetcher consoleInformationFetcher;
        HANDLE stdOutputHandle;

        // Methods
        void setAbsoluteConsoleCursorPosition(int, int);
};

#endif
