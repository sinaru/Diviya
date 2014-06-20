<?php
/**
 * This functions in this file is supposed to run from command line. 
 * 
 * --funcion initiate_diviya_classes--
 * This function updates the array returned by "diviya_classes.php" by 
 * modifying the file content. To run this function 
 * type "php console_scripts.php initiate_diviya_classes"
 */

define('br', "\n");
define ('CLASS_LIST_FILE_NAME','class_list.php'); // File name of the file that should return the framework class list.
define ('FRAMEWORK_FOLDER',dirname(__FILE__)."/../");

function initiate_diviya_classes() {
    $frameworkFolderList = array();

    $handle = opendir(dirname(__FILE__)."/../");
    if ($handle) {
        while (false != ($entry = readdir($handle))) {
            
            if (is_dir("../".$entry) && $entry != ".." && $entry != "." && $entry != "scripts")
                $frameworkFolderList[$entry] = array();
        }
    }
    
    foreach ($frameworkFolderList as $folder => $value) {
        $handle = opendir(dirname(__FILE__).DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR.$folder);
         if($handle)
         {
             while(false != ($entry = readdir($handle)))
             {
                 if(!(is_dir($entry) || $entry == ".." || $entry =="."))
                 {
                     $frameworkFolderList[$folder][] = $entry;
                 }
             }
         }
    } 
    
    $diviyaClassesFileContent = "<?php".br;
    $diviyaClassesFileContent.="// This file is auto generated".br;
    $diviyaClassesFileContent.= "return array(".br;
    
    foreach ($frameworkFolderList as $folder => $filenames) {
        for ($index = 0; $index < count($filenames); $index++) {
            $fileClass = basename($filenames[$index],".php");
            $diviyaClassesFileContent.= "   '$fileClass'=>'/$folder/$filenames[$index]',".br;
        }
    }
    
    $diviyaClassesFileContent.=");".br;
    
    $diviyaClassesFileHandle = fopen(CLASS_LIST_FILE_NAME, 'w');
    if($diviyaClassesFileHandle == false)
    {
        echo "Unable to open file: '".CLASS_LIST_FILE_NAME."'";
        return 1;
    }
    
    fwrite($diviyaClassesFileHandle, $diviyaClassesFileContent);
    fclose($diviyaClassesFileHandle);
    echo "`function initiate_diviya_classes()` executed successfully".br;
}
$var = $argv[1];
$var();
