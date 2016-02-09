<?php
declare(strict_types = 1);

namespace Dark\PW\MicroBlog;

/**
 * @covers \Dark\PW\MicroBlog\UserNickname
 */
class UserNicknameTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @expectedException InvalidArgumentException
     * @expectedExceptionMessage Empty name is not allowed
     */
    public function testNameCanNotBeEmpty()
    {
        new UserNickname('');
    }

    /**
     * @expectedException InvalidArgumentException
     * @expectedExceptionMessage Empty name is not allowed
     */
    public function testNameCanNotContainOnlySpaces()
    {
        new UserNickname('   ');
    }

    public function testNameCanBeRetrieved()
    {
        $nickname = new UserNickname('Test Name');

        $this->assertSame('Test Name', $nickname->getName());
    }

    public function testWhitespacesWillBeTrimmed()
    {
        $nickname = new UserNickname('  Test Name  ');

        $this->assertSame('Test Name', $nickname->getName());
    }
}