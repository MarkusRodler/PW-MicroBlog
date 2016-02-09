<?php
declare(strict_types = 1);

namespace Dark\PW\MicroBlog;

class UserEmail
{
    /**
     * @var string
     */
    private $email;

    public function __construct(string $email)
    {
        $trimmedEmail = trim($email);

        $this->ensureEmailIsNotEmpty($trimmedEmail);

        $this->email = $trimmedEmail;
    }

    /**
     * @return string
     */
    public function getEmail(): string
    {
        return $this->email;
    }

    /**
     * @param string $email
     * @throws \InvalidArgumentException
     */
    private function ensureEmailIsNotEmpty(string $email)
    {
        if (strlen($email) === 0) {
            throw new \InvalidArgumentException('Empty email is not allowed');
        }
    }
}