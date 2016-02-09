<?php
declare(strict_types = 1);

namespace Dark\PW\MicroBlog;

/**
 * @covers \Dark\PW\MicroBlog\Message
 */
class MessageTest extends \PHPUnit_Framework_TestCase
{

    
    /**
     * @expectedException InvalidArgumentException
     * @expectedExceptionMessage Empty text is not allowed
     */
    public function testTextCanNotBeEmpty()
    {
        new Message('');
    }

    /**
     * @expectedException InvalidArgumentException
     * @expectedExceptionMessage Empty text is not allowed
     */
    public function testTextCanNotContainOnlySpaces()
    {
        new Message('   ');
    }

    public function testTextCanBeRetrieved()
    {
        $message = new Message('Test Message');

        $this->assertSame('Test Message', $message->getText());
    }

    public function testWhitespacesWillBeTrimmed()
    {
        $message = new Message('  Test Message  ');

        $this->assertSame('Test Message', $message->getText());
    }

    public function testTextCanContainUpTo80Character()
    {
        $text = '1234567890123456789012345678901234567890'
            . '1234567890123456789012345678901234567890';
        $message = new Message($text);

        $this->assertSame($text, $message->getText());
    }

    /**
     * @expectedException InvalidArgumentException
     * @expectedExceptionMessage Not more than 80 Character allowed
     */
    public function testTextCanNotContainMoreThan80Character()
    {
        new Message(
            '1234567890123456789012345678901234567890'
            . '1234567890123456789012345678901234567890E'
        );
    }

    public function testMessageContainsCreationDate()
    {
        $message = new Message('test');
        $this->assertTimeEquals(time(), $message->getCreationTime(), 2);
    }
    
    /**
     * @param int $expected
     * @param int $actual
     * @param int $timeTolerance 
     */
    private function assertTimeEquals(int $expected, int $actual, int $timeTolerance)
    {
        $toleranceRange = range($actual, $actual + $timeTolerance);
        $this->assertContains($expected, $toleranceRange);
    }
}