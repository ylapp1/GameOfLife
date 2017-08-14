# GameOfLife

Usage: gameoflife.php [options] [operands]  <br /><br />
Options:  <br />

| Option              | Description             | Type | Possible values |
| ------------------- | ----------------------- | ---- | --------------- |
| --width <arg>       | Set the board width     | Integer | Default: 20 |
| --height <arg>      | Set the board height    | Integer | Default: 10  |
| --maxSteps <arg>    | Set the maximum amount of steps that are calculated before the simulation stops | Integer | Default: 50 |
| --border <arg>      | Set the border type     | String | solid (Default), passthrough |
| --input <arg>       | Fill the board with cells | String | random, glider, blinker, spaceship |
| --version           | Print script version  | - | - |
| -h, --help          | Show help  | - | - |
| --posX <arg>        | X position of the object (blinker, glider etc.)  | Integer | Default: Center |
| --posY <arg>        | Y position of the object (blinker, glider, etc.)  | Integer | Default: Center |
| --fillPercent <arg> | Percentage of living cells on a random board  | Integer | Default: rand(1,70) |