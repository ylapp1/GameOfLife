# GameOfLife

Usage: gameoflife.php [options] [operands]  <br /><br />


## Options

| Option              | Description             | Type | Possible values |
| ------------------- | ----------------------- | ---- | --------------- |
| --width <arg>       | Set the board width     | Integer | Default: 20 |
| --height <arg>      | Set the board height    | Integer | Default: 10  |
| --maxSteps <arg>    | Set the maximum amount of steps that are calculated before the simulation stops | Integer | Default: 50 |
| --border <arg>      | Set the border type     | String | solid (Default), passthrough |
| --input <arg>       | Fill the board with cells  | String | Blinker, Glider, Random, Spaceship |
| --output <arg>      | Set the output type  | String | console, png |
| --version           | Print script version  | - | - |
| -h, --help          | Show help  | - | - |


## Input options

| Option              | Description             | Type | Possible values |
| ------------------- | ----------------------- | ---- | --------------- |
| --blinkerPosX <arg> | X position of the blinker  | Integer | Default: Center |
| --blinkerPosY <arg> | Y position of the blinker  | Integer | Default: Center |
| --gliderPosX <arg>  | X position of the glider   | Integer | Default: Center |
| --gliderPosY <arg>  | Y position of the glider   | Integer | Default: Center |
| --spaceShipPosX <arg> | X position of the spaceship | Integer | Default: Center |
| --spaceShipPosY <arg> | Y position of the spaceship | Integer | Default: Center |
| --fillPercent <arg> | Percentage of living cells on a random board  | Integer | Default: rand(1,70) |
| -- template <arg>   | Load board configuration from a txt file | String | glidergun, custom templates|
| -- edit             | Edit a template selected with --template | - | - |


## Output options

| Option              | Description             | Type | Possible values |
| ------------------- | ----------------------- | ---- | --------------- |
| --gifOutputSize <arg> | Size of a cell in pixels for gif outputs | Integer | Default: 100 |
| --gifOutputCellColor <arg> | Color of a cell for gif outputs | String | R,G,B or color names (e.g. "blue", "red", "green") |
| --gifOutputBackgroundColor <arg> | Background color for gif outputs | String | R,G,B or color names (e.g. "blue", "red", "green") |
| --gifOutputFrameTime <arg> | Time for which each frame of a gif is displayed (in milliseconds * 10) | Integer | Default: 20 |
| --pngOutputSize <arg> | Size of a cell in pixels for PNG outputs | Integer | Default:100 |
| --pngOutputCellColor <arg> | Color of a cell for PNG outputs | String | R,G,B or color names (e.g. "blue", "red", "green") |
| --pngOutputBackgroundColor <arg> | Color of the background for PNG outputs | String | R,G,B or color names (e.g. "blue", "red", "green") |