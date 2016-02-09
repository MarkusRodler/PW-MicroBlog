<?php
declare(strict_types = 1);

namespace Dark\PW\MicroBlog;

/**
 * @covers \Dark\PW\MicroBlog\UserEmail
 */
class UserEmailTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @expectedException InvalidArgumentException
     * @expectedExceptionMessage Empty email is not allowed
     */
    public function testEmailCanNotBeEmpty()
    {
        new UserEmail('');
    }

    /**
     * @expectedException InvalidArgumentException
     * @expectedExceptionMessage Empty email is not allowed
     */
    public function testEmailCanNotContainOnlySpaces()
    {
        new UserEmail('   ');
    }

    public function testEmailCanBeRetrieved()
    {
        $userEmail = new UserEmail('mail@mail.de');

        $this->assertSame('mail@mail.de', $userEmail->getEmail());
    }

    public function testWhitespacesWillBeTrimmed()
    {
        $userEmail = new UserEmail('  mail@mail.de  ');

        $this->assertSame('mail@mail.de', $userEmail->getEmail());
    }
}