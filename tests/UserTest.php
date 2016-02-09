<?php
declare(strict_types = 1);

namespace Dark\PW\MicroBlog;

/**
 * @covers \Dark\PW\MicroBlog\User
 * @uses \Dark\PW\MicroBlog\UserNickname
 * @uses \Dark\PW\MicroBlog\UserEmail
 * @uses \Dark\PW\MicroBlog\Message
 */
class UserTest extends \PHPUnit_Framework_TestCase
{

    public function testUserWithValidNicknameAndValidEmailCanBeCreated()
    {
        $nickname = new UserNickname('nick');
        $email = new UserEmail('mail@mail.de');
        
        $user = new User($nickname, $email);

        $this->assertSame('nick', $user->getNickname());
        $this->assertSame('mail@mail.de', $user->getEmail());
    }

    public function testUserCanPostNewMessage()
    {
        $nickname = new UserNickname('nick');
        $email = new UserEmail('mail@mail.de');
        $user = new User($nickname, $email);
        $message = new Message('test');

        $user->post($message);

        $this->assertCount(1, $user->getPostings());
        $this->assertContains($message, $user->getPostings());
    }

    public function testUserCanFollowOtherUser()
    {
        $nickname = new UserNickname('nick');
        $email = new UserEmail('mail@mail.de');
        $user = new User($nickname, $email);
        $nickname2 = new UserNickname('nick2');
        $email2 = new UserEmail('mail2@mail.de');
        $user2 = new User($nickname2, $email2);

        $user->follow($user2);
        
        $this->assertCount(1, $user->getFriends());
        $this->assertContains($user2, $user->getFriends());
    }

    /**
     * @expectedException InvalidArgumentException
     * @expectedExceptionMessage User can not follow himself
     */
    public function testUserCanNotFollowHimself()
    {
        $nickname = new UserNickname('nick');
        $email = new UserEmail('mail@mail.de');
        $user = new User($nickname, $email);
        
        $user->follow($user);
    }

    public function testUserCanUnfollowOtherUser()
    {
        $nickname = new UserNickname('nick');
        $email = new UserEmail('mail@mail.de');
        $user = new User($nickname, $email);        
        $nickname2 = new UserNickname('nick');
        $email2 = new UserEmail('mail@mail.de');
        $user2 = new User($nickname2, $email2);
        $user->follow($user2);
        
        $user->unfollow($user2);
        
        $this->assertCount(0, $user->getFriends());
        $this->assertNotContains($user2, $user->getFriends());
    }

    public function testTimelineContainsAllPostingsFromHim()
    {
        $message = new Message('test');
        $nickname = new UserNickname('nick');
        $email = new UserEmail('mail@mail.de');
        $user = new User($nickname, $email);
     
        $user->post($message);

        $this->assertCount(1, $user->getTimeline());
        $this->assertContains($message, $user->getTimeline());
    }

    public function testTimelineContainsAllPostingsFromHimAndHisFriends()
    {
        $message1 = new Message('hallo');
        $message2 = new Message('welt');
        $nickname = new UserNickname('nick');
        $email = new UserEmail('mail@mail.de');
        $user = new User($nickname, $email);
        $nickname2 = new UserNickname('nick2');
        $email2 = new UserEmail('mail2@mail.de');
        $user2 = new User($nickname2, $email2);

        $user->post($message1);
        $user2->post($message2);
        $user->follow($user2);

        $this->assertCount(2, $user->getTimeline());
        $this->assertContains($message1, $user->getTimeline());
        $this->assertContains($message2, $user->getTimeline());
    }
    
    public function testCircleOfFriendsContainsUserHimself()
    {
        $nickname = new UserNickname('nick');
        $email = new UserEmail('mail@mail.de');
        $user = new User($nickname, $email);
        
        $this->assertCount(1, $user->getCircleOfFriends());
        $this->assertContains($user, $user->getCircleOfFriends());
    }
    
    public function testCircleOfFriendsContainsFriends()
    {
        $nickname = new UserNickname('nick');
        $email = new UserEmail('mail@mail.de');
        $user = new User($nickname, $email);
        $nickname2 = new UserNickname('nick');
        $email2 = new UserEmail('mail@mail.de');
        $user2 = new User($nickname2, $email2);        
        $nickname3 = new UserNickname('nick');
        $email3 = new UserEmail('mail@mail.de');
        $user3 = new User($nickname3, $email3);
        
        $user->follow($user2);
        $user->follow($user3);
                
        $this->assertCount(3, $user->getCircleOfFriends());
        $this->assertContains($user2, $user->getCircleOfFriends());
        $this->assertContains($user2, $user->getCircleOfFriends());
        $this->assertContains($user3, $user->getCircleOfFriends());
    }
    
    public function testCanBlockOtherUser()
    {
        $nickname = new UserNickname('nick');
        $email = new UserEmail('mail@mail.de');
        $user = new User($nickname, $email);
        $nickname2 = new UserNickname('nick');
        $email2 = new UserEmail('mail@mail.de');
        $user2 = new User($nickname2, $email2);    
        
        $user->block($user2);
        
        $this->assertCount(1, $user->getBlockedUsers());
        $this->assertContains($user2, $user->getBlockedUsers());
    }
    
    /**
     * @expectedException \Dark\PW\MicroBlog\CanNotFollowBlockedUserException
     * @expectedExceptionMessage User can not follow blocked User
     */
    public function testCanNotFollowBlockedUser()
    {
        $nickname = new UserNickname('nick');
        $email = new UserEmail('mail@mail.de');
        $user = new User($nickname, $email);
        $nickname2 = new UserNickname('nick');
        $email2 = new UserEmail('mail@mail.de');
        $user2 = new User($nickname2, $email2);    
        
        $user->block($user2);
        $user->follow($user2);
    }
    
    public function testBlockedUserCanNotFollowUser()
    {
        $nickname = new UserNickname('nick');
        $email = new UserEmail('mail@mail.de');
        $user = new User($nickname, $email);
        $nickname2 = new UserNickname('nick');
        $email2 = new UserEmail('mail@mail.de');
        $user2 = new User($nickname2, $email2);    
        
        $user->follow($user2);
        $this->assertCount(1, $user->getFriends());
        $this->assertContains($user2, $user->getFriends());
        $user2->block($user);
        $this->assertCount(0, $user->getFriends());
        $this->assertNotContains($user2, $user->getFriends());
    }
}