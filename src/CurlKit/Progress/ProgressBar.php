<?php
namespace CurlKit\Progress;
use Exception;
use CurlKit\Progress\ProgressInterface;

class ProgressBar
    implements ProgressInterface
{

    function callback($downloadSize, $downloaded, $uploadSize, $uploaded)
    {
        if( $downloadSize < $this->showSize ) {
            return;
        }
        if( $this->done ) {
            return;
        }


        // print progress bar
        $percent = ($downloaded > 0 ? (float) ($downloaded / $downloadSize) : 0.0 );
        $terminalWidth = 70;
        $sharps = (int) $terminalWidth * $percent;

        # echo "\n" . $sharps. "\n";
        echo "\r" . 
            str_repeat( '#' , $sharps ) . 
            str_repeat( ' ' , $terminalWidth - $sharps ) . 
            sprintf( ' %4d B %5d%%' , $downloaded , $percent * 100 );

        if( $downloadSize != 0 && $downloadSize === $downloaded ) {
            echo "\n";
        }
    }
}

