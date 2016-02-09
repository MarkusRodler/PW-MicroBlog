<?php
declare(strict_types = 1);

namespace Dark\PW\MicroBlog;

class User
{
    /**
     * @var UserNickname
     */
    private $nickname;

    /**
     * @var UserEmail
     */
    private $email;

    /**
     * @var \SplObjectStorage
     */
    private $messages;

    /**
     * @var \SplObjectStorage
     */
    private $friends;

    /**
     * @var \SplObjectStorage
     */
    private $blockedUsers;

    public function __construct(UserNickname $nickname, UserEmail $email)
    {
        $this->nickname = $nickname;
        $this->email = $email;
        $this->friends = new \SplObjectStorage();
        $this->blockedUsers = new \SplObjectStorage();
        $this->messages = new \SplObjectStorage();
    }

    public function getNickname(): string
    {
        return $this->nickname->getName();
    }

    public function getEmail(): string
    {
        return $this->email->getEmail();
    }

    public function post(Message $message)
    {
        $this->messages->attach($message);
    }

    public function getPostings(): \SplObjectStorage
    {
        return $this->messages;
    }

    public function follow(User $user)
    {
        $this->ensureUserCanNotFollowHimself($user);
        $this->ensureUserCanNotFollowBlockedUser($user);
        $this->friends->attach($user);
    }

    public function unfollow(User $user)
    {
        $this->friends->detach($user);
    }

    public function getFriends(): \SplObjectStorage
    {
        return $this->friends;
    }

    public function getCircleOfFriends(): \SplObjectStorage
    {
        $storage = new \SplObjectStorage();
        $storage->addAll($this->friends);
        $storage->attach($this);
        return $storage;
    }
    
    public function getBlockedUsers(): \SplObjectStorage
    {
        return $this->blockedUsers;
    }
    
    public function block(User $user)
    {
        $this->blockedUsers->attach($user);
        $user->unfollow($this);
    }

    /**
     * @param User $user
     * @throws \InvalidArgumentException
     */
    private function ensureUserCanNotFollowHimself(User $user)
    {
        if ($this === $user) {
            throw new \InvalidArgumentException('User can not follow himself');
        }
    }

    /**
     * @param User $user
     * @throws \Dark\PW\MicroBlog\CanNotFollowBlockedUserException
     */
    private function ensureUserCanNotFollowBlockedUser(User $user)
    {
        if ($this->blockedUsers->contains($user)) {
            throw new \Dark\PW\MicroBlog\CanNotFollowBlockedUserException('User can not follow blocked User');
        }
    }

    public function getOwnTimeline(): \SplObjectStorage
    {
        return $this->messages;
    }

    public function getTimeline(): \SplObjectStorage
    {
        $timelines = new \SplObjectStorage();
        $timelines->addAll($this->getOwnTimeline());
        foreach ($this->getFriends() as $friend) {
            $timelines->addAll($friend->getOwnTimeline());
        }
        return $timelines;
    }
}