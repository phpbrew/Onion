<?php
namespace Onion\Command;

class CompileCommand 
{
    function options($opts)
    {
        $opts->add('autoload:','autoload source file');
        $opts->add('executable:','executable source file');
        $opts->add('src+','external source dir');
        $opts->add('output:','output');
    }

    function brief()
    {
        return 'compile current source into a phar file.';
    }

    function execute($arguments)
    {
        $options = $this->getOptions();
        $executable = $options->executable;
        $src_dirs   = $options->src;
        $output     = $options->output;

    }
}
