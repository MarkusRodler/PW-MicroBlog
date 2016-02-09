<?php
declare(strict_types = 1);

namespace Dark\PW\MicroBlog;

class SortTimeline
{
    
    public function sortByCreationTimeDesc(\SplObjectStorage $timeline): array
    {
        $timelineArray = iterator_to_array($timeline);
        usort($timelineArray, function (Message $a, Message $b): int {
            return $a->getCreationTime() <=> $b->getCreationTime();
        });
        return $timelineArray;
    }
}