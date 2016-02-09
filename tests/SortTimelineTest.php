<?php
declare(strict_types = 1);

namespace Dark\PW\MicroBlog;

/**
 * @covers \Dark\PW\MicroBlog\SortTimeline
 */
class TimelineTest extends \PHPUnit_Framework_TestCase
{
    
    public function testTimelineWillBeSortedDescendingByCreationTime()
    {
        $message1 = new Message('Welt!');
        sleep(1);
        $message2 = new Message('PHP');
        sleep(1);
        $message3 = new Message('schöne');
        sleep(1);
        $message4 = new Message('Hallo');
        $nickname = new UserNickname('nick');
        $email = new UserEmail('mail@mail.de');
        $user = new User($nickname, $email);
        $nickname2 = new UserNickname('nick2');
        $email2 = new UserEmail('mail2@mail.de');
        $user2 = new User($nickname2, $email2);

        $user2->post($message4);
        $user->post($message3);
        $user->post($message2);
        $user2->post($message1);
        $user->follow($user2);

        $timeline = $user->getTimeline();
        $sortTimeline = new SortTimeline();
        $sortedTimeline = $sortTimeline->sortByCreationTimeDesc($timeline);
        
        $expectedTimeline = [
            $message1,
            $message2,
            $message3,
            $message4
        ];
        $this->assertSame($expectedTimeline, $sortedTimeline);
        
    }
}