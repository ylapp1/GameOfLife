[![Build Status](https://travis-ci.org/ylapp1/GameOfLife.svg?branch=feature%2Fphase-6)](https://travis-ci.org/ylapp1/GameOfLife)

# GameOfLife

Usage: gameoflife.php [options] [operands]  <br /><br />


## Options

| Option              | Description                                     | Type    | Possible values                                                  |
| ------------------- | ----------------------------------------------- | ------- | ---------------------------------------------------------------- |
| --width <arg>       | Set the board width                             | Integer | Default: 20                                                      |
| --height <arg>      | Set the board height                            | Integer | Default: 10                                                      |
| --maxSteps <arg>    | Set the maximum amount of calculated game steps | Integer | Default: 50                                                      |
| --border <arg>      | Set the border type                             | String  | solid (Default), passthrough                                     |
| --input <arg>       | Fill the board with cells                       | String  | Blinker, Glider, Random (Default), Spaceship                     |
| --output <arg>      | Set the output type                             | String  | console, png                                                     |
| --rules <arg>       | Set the rules                                   | String  | BlinkingStains, Conway (Default), Custom, Copy, Labyrinth, Two45 |
| --version           | Print script version                            | -       | -                                                                |
| -h, --help          | Show help                                       | -       | -                                                                |


## Input options

| Option                | Description                                       | Type    | Possible values             |
| --------------------- | ------------------------------------------------- | ------- | --------------------------- |
| --fillPercent <arg>   | Percentage of living cells on a random board      | Integer | Default: rand(1,70)         |
| --blinkerPosX <arg>   | X position of the blinker                         | Integer | Default: Center             |
| --blinkerPosY <arg>   | Y position of the blinker                         | Integer | Default: Center             |
| --gliderPosX <arg>    | X position of the glider                          | Integer | Default: Center             |
| --gliderPosY <arg>    | Y position of the glider                          | Integer | Default: Center             |
| --glidergunPosX <arg> | X position of the glidergun                       | Integer | Default: Center             |
| --glidergunPosY <arg> | Y position of the glidergun                       | Integer | Default: Center             |
| --pacmanPosX <arg>    | X position of the pacman                          | Integer | Default: Center             |
| --pacmanPosY <arg>    | Y position of the pacman                          | Integer | Default: Center             |
| --spaceShipPosX <arg> | X position of the spaceship                       | Integer | Default: Center             |
| --spaceShipPosY <arg> | Y position of the spaceship                       | Integer | Default: Center             |
| --template <arg>      | Load board configuration from a txt file          | String  | glidergun, custom templates |
| --list-templates      | Display a list of all templates                   | -       | -                           |
| --templatePosX <arg>  | X-Position of the top left corner of the template | Integer | Default: Center             |
| --templatePosY <arg>  | Y-Position of the top left corner of the template | Integer | Default: Center             |
| --invertTemplate      | Inverts the loaded template                       | -       | -                           |
| --edit                | Edit a template selected with --template          | -       | -                           |


## Output options

| Option                             | Description                                                                      | Type    | Possible values                                    |
| ---------------------------------- | -------------------------------------------------------------------------------- | ------- | -------------------------------------------------- |
| --consoleOutputSleepTime <arg>     | The time for which the program will sleep between each game step in milliseconds | Integer | Default: 50                                       |
| --gifOutputSize <arg>              | Size of a cell in pixels for gif outputs                                         | Integer | Default: 100                                       |
| --gifOutputCellColor <arg>         | Color of a cell for gif outputs                                                  | String  | R,G,B or color names (e.g. "blue", "red", "green") |
| --gifOutputBackgroundColor <arg>   | Background color for gif outputs                                                 | String  | R,G,B or color names (e.g. "blue", "red", "green") |
| --gifOutputGridColor <arg>         | Grid color for gif outputs                                                       | String  | R,G,B or color names (e.g. "blue", "red", "green") |
| --gifOutputFrameTime <arg>         | Frame time of gif (in milliseconds * 10)                                         | Integer | Default: 20                                        |
| --jpgOutputSize <arg>              | Size of a cell in pixels                                                         | Integer | Default: 100                                       |
| --jpgOutputCellColor <arg>         | Color of a cell                                                                  | String  | R,G,B or color names (e.g. "blue", "red", "green") |
| --jpgOutputBackgroundColor <arg>   | Background color                                                                 | String  | R,G,B or color names (e.g. "blue", "red", "green") |
| --jpgOutputGridColor <arg>         | Grid color                                                                       | String  | R,G,B or color names (e.g. "blue", "red", "green") |
| --pngOutputSize <arg>              | Size of a cell in pixels for PNG outputs                                         | Integer | Default: 100                                       |
| --pngOutputCellColor <arg>         | Color of a cell for PNG outputs                                                  | String  | R,G,B or color names (e.g. "blue", "red", "green") |
| --pngOutputBackgroundColor <arg>   | Color of the background for PNG outputs                                          | String  | R,G,B or color names (e.g. "blue", "red", "green") |
| --pngOutputGridColor <arg>         | Color of the grid for PNG outputs                                                | String  | R,G,B or color names (e.g. "blue", "red", "green") |
| --videoOutputSize <arg>            | Size of a cell in pixels for video outputs                                       | Integer | Default: 100                                       |
| --videoOutputCellColor <arg>       | Color of a cell for video outputs                                                | String  | R,G,B or color names (e.g. "blue", "red", "green") |
| --videoOutputBackgroundColor <arg> | Background color for video outputs                                               | String  | R,G,B or color names (e.g. "blue", "red", "green") |
| --videoOutputGridColor <arg>       | Grid color for video outputs                                                     | String  | R,G,B or color names (e.g. "blue", "red", "green") |
| --videoOutputFPS <arg>             | Frames per second of videos                                                      | Integer | Default: 1                                         |
| --videoOutputAddSound              | Add sound to the video                                                           | -       | -                                                  |


## Rule options
| Option                 | Description                                                            | Type   | Possible values                                                    |
| -----------------------| ---------------------------------------------------------------------- | ------ | ------------------------------------------------------------------ |
| --antiRules            | Converts the selected rules to anti rules                              | -      | -                                                                  |
| --rulesString <arg>    | Rule string in the format <stayAlive>/<birth>                          | String | <numericString>/<numericString> or <numericString>G<numericString> |
| --rulesBirth <arg>     | The amounts of cells which will rebirth a dead cell as a single string | String | numeric string                                                     |
| --rulesStayAlive <arg> | The amounts of cells which will keep a living cell alive               | String | numeric string                                                     |


You have to download ffmpeg and extract it to "GameOfLife/Tools/" in order to use the video output in Windows.
You have to install ffmpeg with your package manager to use the video output in Linux.
