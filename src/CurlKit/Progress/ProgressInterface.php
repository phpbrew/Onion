<?php
namespace CurlKit\Progress;

interface ProgressInterface {
    public function callback($downloadSize, $downloaded, $uploadSize, $uploaded);
}


