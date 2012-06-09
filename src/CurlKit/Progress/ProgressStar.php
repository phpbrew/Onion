<?php
namespace CurlKit;

class ProgressStar
    implements ProgressInterface
{
    public $stars = array('-','\\','|','/');
    public $i = 0;
    public $url;
    public $done = false;

    /* minimum size to render this progress bar */
    public $showSize = 10240;

    public function prettySize($bytes)
    {
        if( $bytes > 1000000 ) {
            return (int)( $bytes / 1000000 ) . 'M';
        }
        elseif( $bytes > 1000 ) {
            return (int)( $bytes / 1000 ) . 'K';
        }
        return (int) ($bytes) . 'B';
    }

    public function callback($downloadSize, $downloaded, $uploadSize, $uploaded)
    {
        /* 4kb */
        if( $downloadSize < $this->showSize ) {
            return;
        }
        if( $this->done ) {
            return;
        }

        // printf("%s % 4d%%", $s , $percent );

        if( $downloadSize != 0 && $downloadSize === $downloaded ) {
            $this->done = true;
            printf("\r\t%-60s                           \n",$this->url);
        } else {
            $percent = ($downloaded > 0 ? (float) ($downloaded / $downloadSize) : 0.0 );
            if( ++$this->i > 3 )
                $this->i = 0;

            /* 8 + 1 + 60 + 1 + 1 + 1 + 6 = */
            printf("\r\tFetching %-60s %s % -3.1f%% %s", $this->url,
                $this->stars[ $this->i ], 
                $percent * 100, $this->prettySize($downloaded) );
        }
    }
}


