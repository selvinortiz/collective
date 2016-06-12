<?php

use SelvinOrtiz\Collective\Collective;


/**
 * Class CollectiveTest
 */
class CollectiveTest extends PHPUnit_Framework_TestCase
{
	public function test_first()
	{
		$input    = ['Red', 'Blue', 'Green'];
		$subject  = $this->collect($input);
		$callback = function ($value, $index)
		{
			return ('Blue' == $value || $index == 1);
		};

		$this->assertNull($this->collect([])->first());
		$this->assertEquals($input[0], $subject->first());
		$this->assertEquals($input[1], $subject->first($callback));
	}

	public function test_last()
	{
		$input    = [1, 101, 666];
		$subject  = $this->collect($input);
		$callback = function ($value, $index)
		{
			return (0 == $value || $index == 1);
		};

		$this->assertNull($this->collect([])->last());
		$this->assertEquals($input[2], $subject->last());
		$this->assertEquals($input[1], $subject->last($callback));
	}

	public function test_filter()
	{
		$input    = ['Gill Bates', 'Happy Gilmore', 'John Doe', 'Jane Doe'];
		$subject  = $this->collect($input);
		$callback = function ($value)
		{
			return stripos($value, 'doe') !== false;
		};

		$this->assertEquals([], $this->collect([])->toArray());
		$this->assertEquals($this->collect([]), $this->collect([])->filter($callback));
		$this->assertEquals($this->collect(['John Doe', 'Jane Doe']), $subject->filter($callback));
	}

	public function test_apply()
	{
		$input    = ['Gill Bates', 'Happy Gilmore', 'John Doe', 'Jane Doe'];
		$subject  = $this->collect($input);
		$callback = function ($value)
		{
			return "- {$value}";
		};

		$this->assertEquals([], $this->collect([])->apply($callback)->toArray());
		$this->assertEquals('- Gill Bates', $subject->apply($callback)->first());
		$this->assertEquals('- Jane Doe', $subject->apply($callback)->last());
	}

	public function test_can_be_counted()
	{
		$this->assertEquals(3, count($this->collect([1, 2, 3])));
	}

	public function test_can_be_instantiated()
	{
		$subject = new Collective();

		$this->inspect($subject);

		$this->assertTrue($subject instanceof Collective);
	}

	public function test_can_be_traversed()
	{
		$sum     = 0;
		$subject = $this->collect([1, 2, 3]);

		foreach ($subject as $number)
		{
			$sum += $number;
		}

		$this->assertEquals(6, $sum);
	}

	public function collect(array $input = [])
	{
		return new Collective($input);
	}

	public function inspect($data)
	{
		fwrite(STDERR, print_r($data));
	}
}
