<?php

use SelvinOrtiz\Collective\Collective;

/**
 * Class CollectiveTest
 */
class CollectiveTest extends PHPUnit_Framework_TestCase
{
    public function test_get()
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

        $this->assertEquals('Angry', $this->make($input)->get('user.mood'));
        $this->assertEquals('Matt', $this->make($input)->get('user.family.brother'));
    }

    public function test_set()
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

        $actual = $this->make($input)->set('user.name', 'Brad')->set('user.family.brother', 'Matt')->toArray();

        $this->assertEquals($expect, $actual);
    }

    public function test_first()
    {
        $input    = ['Brad', 'Brandon', 'Matt'];
        $items    = $this->make($input);
        $callback = function ($value, $index) {
            return ('Brandon' == $value || $index == 1);
        };

        $this->assertNull($this->make()->first());
        $this->assertEquals($input[0], $items->first());
        $this->assertEquals($input[1], $items->first($callback));
    }

    public function test_last()
    {
        $input    = [128, 256, 512];
        $items    = $this->make($input);
        $callback = function ($value, $index) {
            return (256 == $value || $index == 1);
        };

        $this->assertNull($this->make([])->last());
        $this->assertEquals($input[2], $items->last());
        $this->assertEquals($input[1], $items->last($callback));
    }

    public function test_map()
    {
        $input    = ['Brad', 'Brandon', 'Matt'];
        $items    = $this->make($input);
        $callback = function ($value) {

            return "- {$value}";
        };

        $this->assertEquals([], $this->make([])->map($callback)->toArray());
        $this->assertEquals('- Brad', $items->map($callback)->first());
        $this->assertEquals('- Matt', $items->map($callback)->last());
    }

    public function test_filter()
    {
        $input    = ['Brad', 'Brandon', 'Matt'];
        $items    = $this->make($input);
        $callback = function ($value) {
            return stripos($value, 'Bra') !== false;
        };

        $this->assertEquals([], $this->make([])->toArray());
        $this->assertEquals($this->make([]), $this->make([])->filter($callback));
        $this->assertEquals($this->make(['Brad', 'Brandon']), $items->filter($callback));
    }

    public function test_reduce()
    {
        $input    = [
            ['name' => 'Brad', 'salary' => 100000, 'type' => 'yearly'],
            ['name' => 'Brandon', 'salary' => 250000, 'type' => 'yearly']
        ];
        $expect   = 350000;
        $callback = function ($value, $carry) {
            return $carry + $value['salary'];
        };

        $this->assertEquals($expect, $this->make($input)->reduce($callback));
    }

    public function test_reverse()
    {
        $this->assertEquals([512, 256, 128], $this->make([128, 256, 512])->reverse()->toArray());
    }

    public function test_keys()
    {
        $input  = ['user', 'name' => 'Brad', 'email' => 'brad@domain.com'];
        $expect = [0, 'name', 'email'];

        $this->assertEquals($expect, $this->make($input)->keys()->toArray());
    }

    public function test_values()
    {
        $input  = ['user', 'name' => 'Brad', 'email' => 'brad@mrangrypants.io'];
        $expect = ['user', 'Brad', 'brad@mrangrypants.io'];

        $this->assertEquals($expect, $this->make($input)->values()->toArray());
    }

    public function test_then()
    {
        $input = [128, 256, 512, 'Brad', 'Brandon', 'Matt'];
        $items = $this->make($input);

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
        )->values()->toArray());
    }

    public function test_can_be_counted()
    {
        $input = [128, 256, 512];

        $this->assertEquals(3, count($this->make($input)));
    }

    public function test_can_be_instantiated()
    {
        $this->assertTrue((new Collective()) instanceof Collective);
    }

    public function test_can_be_traversed()
    {
        $sum   = 0;
        $input = [128, 256, 512];
        $items = $this->make($input);

        foreach ($items as $number) {
            $sum += $number;
        }

        $this->assertEquals(896, $sum);
    }

    /**
     * @param array $input
     *
     * @return Collective
     */
    public function make(array $input = [])
    {
        return new Collective($input);
    }

    public function inspect($data)
    {
        fwrite(STDERR, print_r($data));
    }
}
