<?php





class ConfigContainerTest extends PHPUnit_Framework_TestCase 
{
    function test()
    {
        $config = new \Onion\ConfigContainer(array( 
            'key' => array( 
                'subkey' => array( 'hash' => 1 ),
            )
        ));
        ok( $config );
        $val = $config->get('key.subkey.hash');
        is( 1, $val );

        $val = $config->{'key.subkey.hash'};
        is( 1, $val );
    }
}
