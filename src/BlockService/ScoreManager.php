<?php


namespace App\BlockService;


class ScoreManager
{
    private $score = 0;

    private $multiplicator = 1;

    public function getTotalScore()
    {
        return $this->score * $this->multiplicator;
    }
    public function addPoint($point)
    {
        $this->score += $point;
    }
    /**
     * @return mixed
     */
    public function getScore()
    {
        return $this->score;
    }

    /**
     * @param mixed $score
     * @return ScoreManager
     */
    public function setScore($score)
    {
        $this->score = $score;
        return $this;
    }

    public function addMultiplicator($multiplicatorCount)
    {
        $this->multiplicator += $multiplicatorCount;
    }
    /**
     * @return int
     */
    public function getMultiplicator(): int
    {
        return $this->multiplicator;
    }

    /**
     * @param int $multiplicator
     * @return ScoreManager
     */
    public function setMultiplicator(int $multiplicator): ScoreManager
    {
        $this->multiplicator = $multiplicator;
        return $this;
    }



}
