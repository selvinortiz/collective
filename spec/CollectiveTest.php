<?php

use SelvinOrtiz\Collective\Collective;
use SelvinOrtiz\Collective\Helpers\Dot;


/**
 * Class CollectiveTest
 */
class CollectiveTest extends PHPUnit_Framework_TestCase
{
    public function test_helper_dot_get()
    {
        $input = [
            'user' => [
                'name'   => 'Brad',
                'mood'   => 'Angry',
                'family' => [
                    'brother' => 'Matt'
                ]
            ]
        ];

        $this->assertEquals(3, count(Dot::get($input, 'user')));
        $this->assertEquals('Matt', Dot::get($input, 'user.family.brother'));
    }

    public function test_helper_dot_set()
    {
        $input  = [];
        $expect = [
            'user' => [
                'name'   => 'Brad',
                'family' => [
                    'brother' => 'Matt'
                ]
            ]
        ];

        Dot::set($input, 'user.name', 'Brad');
        Dot::set($input, 'user.family.brother', 'Matt');

        $this->assertEquals($expect, $input);

    }

    public function test_first()
    {
        $input    = ['Brad', 'Brandon', 'Matt'];
        $items    = $this->collect($input);
        $callback = function ($value, $index) {
            return ('Brandon' == $value || $index == 1);
        };

        $this->assertNull($this->collect([])->first());
        $this->assertEquals($input[0], $items->first());
        $this->assertEquals($input[1], $items->first($callback));
    }

    public function test_last()
    {
        $input    = [256, 512, 1024];
        $items    = $this->collect($input);
        $callback = function ($value, $index) {
            return (512 == $value || $index == 1);
        };

        $this->assertNull($this->collect([])->last());
        $this->assertEquals($input[2], $items->last());
        $this->assertEquals($input[1], $items->last($callback));
    }

    public function test_filter()
    {
        $input    = ['Brad', 'Brandon', 'Matt'];
        $items    = $this->collect($input);
        $callback = function ($value) {
            return stripos($value, 'Bra') !== false;
        };

        $this->assertEquals([], $this->collect([])->toArray());
        $this->assertEquals($this->collect([]), $this->collect([])->filter($callback));
        $this->assertEquals($this->collect(['Brad', 'Brandon']), $items->filter($callback));
    }

    public function test_map()
    {
        $input    = ['Brad', 'Brandon', 'Matt'];
        $items    = $this->collect($input);
        $callback = function ($value) {

            return "- {$value}";
        };

        $this->assertEquals([], $this->collect([])->map($callback)->toArray());
        $this->assertEquals('- Brad', $items->map($callback)->first());
        $this->assertEquals('- Matt', $items->map($callback)->last());
    }

    public function test_then()
    {
        $input = [256, 512, 1024, 'Brad', 'Brandon', 'Matt'];
        $items = $this->collect($input);

        $this->assertEquals(
            ['Brad', 'Matt'], $items->then(
            function ($items) {
                return $items->filter(
                    function ($value) {
                        return is_string($value);
                    }
                );
            }
        )->then(
            function ($items) {
                return $items->filter(
                    function ($value) {
                        return strlen($value) == 4;
                    }
                );
            }
        )->resetKeys()->toArray());
    }

    public function test_can_be_counted()
    {
        $input = [256, 512, 1024];

        $this->assertEquals(3, count($this->collect($input)));
    }

    public function test_can_be_instantiated()
    {
        $items = new Collective();

        $this->assertTrue($items instanceof Collective);
    }

    public function test_can_be_traversed()
    {
        $sum   = 0;
        $input = [256, 512, 1024];
        $items = $this->collect($input);

        foreach ($items as $number) {
            $sum += $number;
        }

        $this->assertEquals(1792, $sum);
    }

    /**
     * @param array $input
     *
     * @return Collective
     */
    public function collect(array $input = [])
    {
        return new Collective($input);
    }

    public function inspect($data)
    {
        fwrite(STDERR, print_r($data));
    }
}
