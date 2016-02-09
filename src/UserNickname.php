<?php
declare(strict_types = 1);

namespace Dark\PW\MicroBlog;

class UserNickname
{
    /**
     * @var string
     */
    private $name;

    public function __construct(string $name)
    {
        $trimmedName = trim($name);

        $this->ensureNameIsNotEmpty($trimmedName);

        $this->name = $trimmedName;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @param string $name
     * @throws \InvalidArgumentException
     */
    private function ensureNameIsNotEmpty(string $name)
    {
        if (strlen($name) === 0) {
            throw new \InvalidArgumentException('Empty name is not allowed');
        }
    }
}