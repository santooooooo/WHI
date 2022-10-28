<?php

namespace Tests\Unit;

use PHPUnit\Framework\TestCase;

class ExampleTest extends TestCase
{
    /**
     * A basic test example.
     *
     * @return void
     */
    public function test_example()
    {
	    for($i=10;$i>0;$i--) {
		    $all = array_map('intval', str_split($i));
		    if(array_search(0, $all) !== false) {
			    $result = array_product($all);
			    var_dump($result);
		    }
	    }

        $this->assertTrue($trackCount);
    }
}
