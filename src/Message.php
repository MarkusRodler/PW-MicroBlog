<?php
declare(strict_types = 1);

namespace Dark\PW\MicroBlog;

class Message
{
    /**
     * @var string
     */
    private $text;
    
    /**
     * @var int
     */
    private $creationTime = 0;

    public function __construct(string $text)
    {
        $trimmedText = trim($text);

        $this->ensureTextIsNotEmpty($trimmedText);
        $this->ensureTextCanNotContainMoreThan80Character($trimmedText);

        $this->text = $trimmedText;
        $this->creationTime = time();
    }

    /**
     * @return string
     */
    public function getText(): string
    {
        return $this->text;
    }

    /**
     * 
     * @return int
     */
    public function getCreationTime(): int
    {
        return $this->creationTime;
    }

    /**
     * @param string $text
     * @throws \InvalidArgumentException
     */
    private function ensureTextIsNotEmpty(string $text)
    {
        if (strlen($text) === 0) {
            throw new \InvalidArgumentException('Empty text is not allowed');
        }
    }

    /**
     * @param string $text
     * @throws \InvalidArgumentException
     */
    private function ensureTextCanNotContainMoreThan80Character(string $text)
    {
        if (strlen($text) > 80) {
            throw new \InvalidArgumentException('Not more than 80 Character allowed');
        }
    }
}