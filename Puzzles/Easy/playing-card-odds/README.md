# CodinGame PHP template

## Installation
Be sure to have installed PHP and composer.

In root directory, run in terminal `composer install`.

## How to get one file for challenge.

To run "compilation" of files in src directory, run following command : `composer run watch --timeout=999999`

The `--timeout` argument permit to not kill the process after 300 seconds (default).

It will start the watcher on src directory. When you make any change in one of file in src/, "compiler" is executed and a /dist/compiled.php is updated / created.

You can use the extension CodinGame Sync or CG Local on this file to automatically update / upload you're code in CodinGame.

## How to code

### Game

`Game` is the main class used for the challenge.

`start` is the main method where you can put the default challenge code.

Be free to develop with PSR-4 and create all classes as you need in different files in src directory.

### Tests

To execute tests, run `composer run tests` and will execute all tests in `tests` directory.

Feel free to create tests/bootstrap.php as needed.