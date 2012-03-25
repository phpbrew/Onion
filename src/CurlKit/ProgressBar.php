<?php
namespace CurlKit;
use Exception;

class ProgressBar {

    function callback($downloadSize, $downloaded, $uploadSize, $uploaded)
    {
        // print progress bar
        $percent = ($downloaded > 0 ? (float) ($downloaded / $downloadSize) : 0.0 );
        $terminalWidth = 70;
        $sharps = (int) $terminalWidth * $percent;

        # echo "\n" . $sharps. "\n";
        echo "\r" . 
            str_repeat( '#' , $sharps ) . 
            str_repeat( ' ' , $terminalWidth - $sharps ) . 
            sprintf( ' %4d B %5d%%' , $downloaded , $percent * 100 );
    }

}

?>
