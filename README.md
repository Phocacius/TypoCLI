# TypoCLI
The TypoCLI is a command line interface (written in PHP) to greatly reduce the boilerplate code necessary to write a custom table and/or module to a [Typo3](https://typo3.org) page with [FluidTypo](https://fluidtypo3.org). It targets Typo3 Developers, not editors.

## Usage:
Copy the `typocli.php` file and the `typocli` folder to the root directory of your typo3 installation where you want to add/edit the table/module. Make the `typocli.php` file executable, then execute it in a console.

The CLI works in interactive console mode. Therefore, you just type the start command and follow the instructions. Alternatively, you can specify parameters by adding them after the command in the syntax `arg1=val1[ arg2=val2 ...]`. The available arguments are:

- __ext__: the name of the extension you want to add the table to, e.g. ´mytypopage´
- __ns__: your extension's namespace, e.g. Thorstenhack\MyTypoPage
- __table__: the table's name, first letter uppercase e.g. MapLocation

### Adding tables
```
./typo3cli.php add_table
```
This command allows you to add a database table to your extension. The command will create / alter the following files:

- Classes/Domain/Model: new File
- Classes/Domain/Repository: new File
- Configuration/TCA: new File
- ext_tables.php: add allowTableOnStandardPages statement
- ext_tables.sql: add CREATE TABLE statement

After this, you can already access your table by the List module. If you want to access your table by a separate module, this can also be automatically created if you confirm the prompt that follows the table creation. The following changes will be made:

- Classes/Controller: new file with list action
- add registerModule statement in ext_tables.php

If choosing to use the custom module option, you have another choice: Whether or not to use a custom editor. If choosing a custom editor, you create your own HTML form in order to provide the content for the table, if not, a regular TCA form will be used. The following changes will be made when choosing a custom editor:

- more sophisticated Classes/Controller
- check for Resources/Private/Layouts/Backend + BackendList
- create folder in Resources/Private/Templates
- create Edit/List/New files
- create Partials/FormErrors if not present
- create Partials/FormFields

After that, the add_column command will automatically be run in order to add the columns for the newly created table. You can also abort and add columns at any point in the future though.

### Adding columns
```
./typo3cli.php add_column
```
This command adds a column to an already existing table. You will be asked by the interactive shell for the Column Name (please enter in CamelCase) and the type. The available types are `text`, `textarea`, `int`, `float`, `foreign`, `file`. If adding a `foreign` type you'll also get asked for the table name to be referenced. Please use the class name of the TCA entry for the table in CamelCase.

The command will do the following file changes:
- get/set in Classes/Domain/Model
- add to types/1 in Configuration/TCA
- add to showRecordFieldList in Configuration/TCA
- new array element in column in Configuration/TCA
- add to searchfields in allowTableOnStandardPages statement in ext_tables.php
- add column in ext_tables.sql

## Notes
The script was made for and tested with the now deprecated Typo3 version 7.6. I'd be happy if anyone tests it with newer versions and if necessary create a PR with necessary changes.  

Also, make sure to use source control and commit all changes before using the TypoCLI, so in case something doesn't work afterwards, you can revert the changes done by the script.