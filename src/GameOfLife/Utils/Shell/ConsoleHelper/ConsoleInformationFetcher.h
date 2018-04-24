/**
 * @file
 * @version 0.1
 * @copyright 2018 CN-Consult GmbH
 * @author Yannick Lapp <yannick.lapp@cn-consult.eu>
 */
#include <windows.h>

#ifndef CONSOLEINFORMATIONFETCHER_H
#define CONSOLEINFORMATIONFETCHER_H

class ConsoleInformationFetcher
{
	public:
	  ConsoleInformationFetcher();
	  ConsoleInformationFetcher(HANDLE);
	  COORD getConsoleCursorPosition();
	  int getNumberOfConsoleRows();
	  int getNumberOfConsoleColumns();
	  int getNumberOfBottomDisplayline();

	private:
	  HANDLE stdOutputHandle;
};

#endif
