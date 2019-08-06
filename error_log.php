<?php // /find_error_log.php
/**
 * Put this script in the web root or other top-level directory
 *
 * Traverse this directory and all directories of the web tree
 * Show and optionally delete the error log files
 *
 * http://php.net/manual/en/class.recursivedirectoryiterator.php#85805
 */
ob_start();
error_reporting( E_ALL );
ini_set( 'display_errors', TRUE );
ini_set( 'log_errors',     TRUE );


// START IN THE CURRENT DIRECTORY
$path = realpath(getcwd());
$plen = strlen($path);

// THE ERROR LOG FILE NAME - REVERSED BECAUSE IT IS AT THE END OF THE PATH STRING
$error_log = ini_get('error_log');
$error_log = basename($error_log);
$error_log = strrev($error_log);

// IF THERE IS A POST-METHOD REQUEST TO DELETE THIS ERROR LOG
if (!empty($_POST['error_log']))
{
    // MAKE SURE WE ONLY UNLINK THE ERROR LOG FILE
    $test = strrev($_POST['error_log']);
    if (strpos($test, $error_log) === 0)
    {
        @unlink($path . $_POST['error_log']);
        echo '<h3>' . $_POST['error_log'] . ' Discarded</h3>';
    }
}


// COLLECT THE DIRECTORY INFORMATION OBJECTS
$objs = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($path), RecursiveIteratorIterator::SELF_FIRST);


// ITERATE OVER THE OBJECTS
foreach($objs as $name => $obj)
{
    // PROCESS THE ERROR LOG ONLY
    $test = strrev($name);
    if (strpos($test, $error_log) === 0){
        // CREATE A DELETE BUTTON FOR THIS ERROR LOG
        $name = substr($name, $plen);
        $form = <<<EOD
<form method="post" style="margin:0; padding:0; display:inline;!important">
<b>$name</b>
<input type="submit" value="Discard?" />
<input type="hidden" name="error_log" value="$name" />
</form>
EOD;
        echo $form;

        // SHOW THE CONTENTS OF THIS ERROR LOG
        echo '<pre>';
        print_r(file_get_contents($path . $name));
        echo PHP_EOL . '********** EOF **********';
        echo '</pre>' . PHP_EOL;
    }
}


// IF THERE ARE NO ERROR LOG(S)
$out = ob_get_contents();
if (empty($out)) echo '<h3>Good News! No error_log found.</h3>';


// SHOW THE GIT BRANCH
$root = '.git/HEAD';
$text = @file_get_contents($root);
if ($text)
{
    $text = explode(DIRECTORY_SEPARATOR, $text);
    $text = array_slice($text, 2);
    $name = implode(DIRECTORY_SEPARATOR, $text);
    echo PHP_EOL . "On Git branch: $name" . PHP_EOL;
}
else
{
    echo PHP_EOL . "On Git branch: UNKNOWN" . PHP_EOL;
}

echo '<a href="' . $_SERVER['REQUEST_URI'] . '">Run Again</a>?' . PHP_EOL;

// SCRIPT TERMINATION WILL FLUSH THE OUTPUT BUFFER TO THE CLIENT BROWSER
