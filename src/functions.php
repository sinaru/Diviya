<?php
function loadClass($class)
{
	global $CORE_CLASSES;
    if(isset($CORE_CLASSES[$class]))
    {   
        require_once FRAMEWORK_PATH.$CORE_CLASSES[$class];
        return true;
    }
	
    global $CLASS_PATHS;
    foreach ($CLASS_PATHS as $path)
    {
        $fileName = $path. $class . '.php';
        if (file_exists($fileName))
        {
            require_once $fileName;
            return true;
        }
    }

    return false;
}

function __autoload($class_name)
{
    if(!loadClass($class_name))
        die("Unable to load class $class_name");
}

function addClassPath($path)
{
    global $CLASS_PATHS;
        $CLASS_PATHS[] = $path;
}