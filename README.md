# GameOfLife

Usage: gameoflife.php [options] [operands]  <br /><br />
Options:  <br />

| Option              | Description             | Type | Possible values |
| ------------------- | ----------------------- | ---- | --------------- |
| --width <arg>       | Set the board width     | Integer | Default: 20 |
| --height <arg>      | Set the board height    | Integer | Default: 10  |
| --maxSteps <arg>    | Set the maximum amount of steps that are calculated before the simulation stops | Integer | Default: 50 |
| --border <arg>      | Set the border type     | String | solid (Default), passthrough |
| --input <arg>       | Fill the board with cells  | String | Blinker, Glider, Random, Spaceship |
| --version           | Print script version  | - | - |
| -h, --help          | Show help  | - | - |
| --blinkerPosX <arg> | X position of the blinker  | Integer | Default: Center |
| --blinkerPosY <arg> | Y position of the blinker  | Integer | Default: Center |
| --gliderPosX <arg>  | X position of the glider   | Integer | Default: Center |
| --gliderPosY <arg>  | Y position of the glider   | Integer | Default: Center |
| --fillPercent <arg> | Percentage of living cells on a random board  | Integer | Default: rand(1,70) |
| --spaceShipPosX <arg> | X position of the spaceship | Integer | Default: Center |
| --spaceShipPosY <arg> | Y position of the spaceship | Integer | Default: Center |