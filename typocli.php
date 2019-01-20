#!/usr/bin/php -q
<?php

require_once __dir__.'/typocli/Prompter.php';
require_once __dir__.'/typocli/Templater.php';

$prompter = Prompter::getInstance();

switch($prompter->command) {
    case "add_column":
        break;
    case "add_table":
        $addTable = true;
        break;
    default:
        echo "Usage: typocli command [arg1=val1 ...]\n\n";
        echo "Available commands:\n";
        echo "add_table\tCreates a new table and the corresponding TCA definition as well as optionally a Controller and module entry\n";
        echo "add_column\tAdds a column to an existing table updating the TCA definition (call add_table first)\n\n";
        echo "Available parameters:\n";
        echo "ext\t\tthe name of the extension, e.g. mytypopage\n";
        echo "ns\t\tnamespace, e.g. Thorstenhack\\MyTypoPage\n";
        echo "table\t\ttable name, first letter uppercase e.g. MapLocation\n\n";
        exit;
}

if(!is_dir("typo3conf")) {
    echo "cannot find typo3 installation. Make sure your working directory is in the root directory containing the typo3conf folder.";
    exit;
}


$ext = $prompter->promptIfNoArgument('ext', "Enter extension name, without tx (e.g. mytypopage):");
$namespace = $prompter->promptIfNoArgument('ns', "Enter namespace (e.g. Thorstenhack\\MyTypoPage):");
$company = explode("\\", $namespace)[0];
$TableName = $prompter->promptIfNoArgument('table', "Enter table name, first letter uppercase (e.g. MapLocation):");
$tablename = strtolower($TableName);

$params = [
    "namespace" => $namespace,
    "company" => $company,
    "ext" => $ext,
    "TableName" => $TableName,
    "tableName" => strtolower(substr($TableName, 0, 1)).substr($TableName, 1),
    "tablename" => $tablename,
];

$extensionRoot = "typo3conf/ext/".$ext."/";



if(isset($addTable) && $addTable) {
    Templater::save("Model.php", $extensionRoot."Classes/Domain/Model/".$TableName.".php", $params);
    Templater::save("Repository.php", $extensionRoot."Classes/Domain/Repository/".$TableName."Repository.php", $params);
    Templater::save("TCA.php", $extensionRoot."Configuration/TCA/".$TableName.".php", $params);
    Templater::append("ExtTablesAllowStatement.php", $extensionRoot."ext_tables.php", $params);
    Templater::append("ExtTablesSQLStatement.php", $extensionRoot."ext_tables.sql", $params);

    echo "Table successfully created.\n";

    if($prompter->yesNoPrompt("Add module?")) {

        Templater::append("locallang.xlf", $extensionRoot."Resources/Private/Language/locallang_".$tablename.".xlf", $params);

        if($prompter->yesNoPrompt("Use custom editor (instead of plain TCA)?")) {
            Templater::append("ExtTablesCustomModule.php", $extensionRoot."ext_tables.php", $params);
            Templater::save("ControllerCustom.php", $extensionRoot."Classes/Controller/".$TableName."Controller.php", $params);
            $templateRoot = $extensionRoot . "Resources/Private/Templates/" . $TableName;
            mkdir($templateRoot);
            Templater::save("Edit.html", $templateRoot."/Edit.html", $params);
            Templater::save("New.html", $templateRoot."/New.html", $params);
            Templater::save("List.html", $templateRoot."/List.html", $params);
            Templater::save("FormFields.html", $extensionRoot."Resources/Private/Templates/Partials/".$TableName."FormFields.html", $params);
            Templater::saveIfNotExists("FormErrors.html", $extensionRoot."Resources/Private/Templates/Partials/FormErrors.html", $params);
            Templater::saveIfNotExists("Backend.html", $extensionRoot."Resources/Private/Layouts/Backend.html", $params);
            Templater::saveIfNotExists("BackendList.html", $extensionRoot."Resources/Private/Layouts/BackendList.html", $params);
            echo "Custom controller successfully created.\n";

        } else {
            Templater::save("Controller.php", $extensionRoot."Classes/Controller/".$TableName."Controller.php", $params);
            Templater::append("ExtTablesTCAModule.php", $extensionRoot."ext_tables.php", $params);
            echo "Module successfully created.\n";

        }
    }
    echo "Adding columns now.\n";
}

do {

    $params['ColumnName'] = $prompter->prompt("Enter column name (CamelCase):");
    $params['columnName'] = strtolower(substr($params['ColumnName'], 0, 1)).substr($params['ColumnName'], 1);
    $params['column_name'] = Templater::from_camel_case($params['ColumnName']);
    $params['type'] = $prompter->prompt("Enter column type, allowed types: text, textarea, int, float, foreign, file");

    $templateName = "TCAEntry.php";
    $sqlType = "int(11)";
    $sqlDefault = "'0'";

    switch($params['type']) {
        case "text":
            $params['columnType'] = "string";
            $params['defaultValue'] = "''";
            $params['tcaType'] = "input";
            $params['tcaSize'] = 100;
            $params['tcaEval'] = "trim";
            $params['tcaCols'] = 100;
            $params['tcaRows'] = 1;
            $sqlType = "varchar(255)";
            $sqlDefault = "''";
            break;

        case "textarea":
            $params['columnType'] = "string";
            $params['defaultValue'] = "''";
            $params['tcaType'] = "text";
            $params['tcaCols'] = 100;
            $params['tcaRows'] = 10;
            $params['tcaEval'] = "trim";
            $sqlType = "TEXT";
            $sqlDefault = "''";
            break;

        case "int":
            $params['columnType'] = "int";
            $params['defaultValue'] = "0";
            $params['tcaType'] = "input";
            $params['tcaSize'] = 30;
            $params['tcaCols'] = 30;
            $params['tcaRows'] = 1;
            $params['tcaEval'] = "int";
            break;

        case "float":
            $params['columnType'] = "float";
            $params['defaultValue'] = "0.0";
            $params['tcaType'] = "input";
            $params['tcaSize'] = 30;
            $params['tcaEval'] = "double2";
            $params['tcaCols'] = 30;
            $params['tcaRows'] = 1;
            $sqlType = "double(12,5)";
            $sqlDefault = "'0.00'";
            break;

        case "foreign":
            $params['columnType'] = "int";
            $params['defaultValue'] = "0";
            $params['foreignTable'] = strtolower($prompter->prompt("foreign table name (just the classname:"));
            $templateName = "TCAEntryForeign.php";
            $sqlType = "int(11) unsigned";
            break;

        case "file":
            $params['columnType'] = "\\TYPO3\\CMS\\Extbase\\Domain\\Model\\FileReference";
            $params['defaultValue'] = "null";
            $templateName = "TCAEntryFile.php";
            $sqlType = "int(11) unsigned";
            break;

        default: echo "Unknown type, try again.\n"; continue;
    }

    $modelEntry = Templater::get("ModelEntry.php", $params);
    $modelFilename = $extensionRoot . "Classes/Domain/Model/" . $TableName . ".php";
    $content = file_get_contents($modelFilename);
    $content = preg_replace("/}\s*$/", $modelEntry."\n}\n", $content);
    file_put_contents($modelFilename, $content);

    $tcaFilename = $extensionRoot . "Configuration/TCA/" . $TableName . ".php";
    $tcaConf = file_get_contents($tcaFilename);
    $tcaConf = preg_replace("/('1' => array\('showitem' => 'sys_language_uid;)(.*)( --div--)/", "$1 ".$params['column_name'].", $2", $tcaConf);
    $tcaConf = preg_replace("/('showRecordFieldList' => '.*)(',)/U", "$1, ".$params['column_name']."$2", $tcaConf);
    $tcaRecord = Templater::get($templateName, $params);
    $tcaConf = preg_replace("/(\)\s*,)(\s*\)\s*,\s*\)\s*;)/", "$1".$tcaRecord."$2", $tcaConf);
    file_put_contents($tcaFilename, $tcaConf);

    $extTables = file_get_contents($extensionRoot."ext_tables.php");
    $fullTable = "tx_".$params['ext']."_domain_model_".$params['tablename'];
    $extTables = preg_replace("/(allowTableOnStandardPages\('".$fullTable.".*'searchFields'.*)(',)/sU", "$1,".$params['column_name']."$2", $extTables);
    $extTables = preg_replace("/(allowTableOnStandardPages\('".$fullTable.".*'searchFields' => '),/sU", "$1", $extTables);
    file_put_contents($extensionRoot."ext_tables.php", $extTables);

    $extTablesSql = file_get_contents($extensionRoot."ext_tables.sql");
    $sqlStatement = $params['column_name']." ".$sqlType." DEFAULT ".$sqlDefault." NOT NULL,";
    $extTablesSql = preg_replace("/(CREATE TABLE ".$fullTable.".*?pid.*?,\s*)/s", "$1".$sqlStatement."\n\t", $extTablesSql);
    file_put_contents($extensionRoot."ext_tables.sql", $extTablesSql);

    echo "Column ".$params['ColumnName']." created.\n";



} while($prompter->yesNoPrompt("Add another column?"));







/*** adding TCA table:
- Classes/Domain/Model: new File
- Classes/Domain/Repository: new File
- Configuration/TCA: new File
- ext_tables.php: add allowTableOnStandardPages statement
- ext_tables.sql: add CREATE TABLE statement

if(addModule)
    - Classes/Controller: new file with list action
    - add registerModule statement in ext_tables.php

if(custom editor)
    - more sophisticated Classes/Controller
    - check for Resources/Private/Layouts/Backend + BackendList
    - create folder in Resources/Private/Templates
    - create Edit/List/New files
    - create Partials/FormErrors if not present
    - create Partials/FormFields

*** add Column
 - get/set in Classes/Domain/Model
 - add to types/1 in Configuration/TCA
 - add to showRecordFieldList in Configuration/TCA
 - new array element in column in Configuration/TCA
 - add to searchfields in allowTableOnStandardPages statement in ext_tables.php
 - add column in ext_tables.sql


 *
 * $f = fopen("test.txt", "tr+");

// read lines with fgets() until you have reached the right one

$pos = ftell($f);                   // save current position
$trailer = stream_get_contents($f); // read trailing data
fseek($f, $pos);                    // go back
ftruncate($f, $pos);                // truncate the file at current position
fputs($f, "my strings\n");          // add line
fwrite($f, $trailer);               // restore trailing data
*/