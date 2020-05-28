<?php

namespace PF;

class BowlingGame
{
    public array $rolls = [];

    /**
     * @param int $points
     * @throws BowlingGameException
     */
    public function roll(int $points): void
    {
        if ($points < 0 || $points > 10) {
            throw new BowlingGameException('Invalid points count');
        }
        $this->rolls[] = $points;
    }

    /**
     * @return int
     * @throws BowlingGameException
     */
    public function getScore(): int
    {
        $score = 0;
        $roll = 0;

        $this->isRollsValid(); //method is added to exit earlier if the mistake is obvious

        for ($frame = 0; $frame < 10; $frame++) {
            if ($this->isStrike($roll)) {
                $score += $this->getPointsForStrike($roll);
                $roll += $frame === 9 ? 3 : 1;
                continue;
            }

            if ($this->isSpare($roll)) {
                $score += $this->getSpareBonus($roll);
                $roll += $frame === 9 ? 1 : 0;
            }

            $score += $this->getNormalScore($roll);
            $roll += 2;
        }

        $this->isGameValid($roll);

        return $score;
    }

    /**
     * @param int $roll
     * @return int
     */
    public function getNormalScore(int $roll): int
    {
        return $this->rolls[$roll] + $this->rolls[$roll + 1];
    }

    /**
     * @param int $roll
     * @return bool
     */
    public function isSpare(int $roll): bool
    {
        return $this->getNormalScore($roll) === 10;
    }

    /**
     * @param int $roll
     * @return int
     */
    public function getSpareBonus(int $roll): int
    {
        return $this->rolls[$roll + 2];
    }

    /**
     * @param int $roll
     * @return bool
     */
    public function isStrike(int $roll): bool
    {
        return $this->rolls[$roll] === 10;
    }

    /**
     * @param int $roll
     * @return int
     */
    public function getPointsForStrike(int $roll): int
    {
        return 10 + $this->rolls[$roll + 1] + $this->rolls[$roll + 2];
    }

    /**
     * @throws BowlingGameException
     */
    public function isRollsValid(): void
    {
        if (count($this->rolls) > 21) {
            throw new BowlingGameException('Too many rolls');
        }

        if (count($this->rolls) < 12) {
            throw new BowlingGameException('Not enough rolls');

        }
    }

    /**
     * @param int $roll
     * @throws BowlingGameException
     */
    public function isGameValid(int $roll): void
    {
        if ($roll !== count($this->rolls)) {
            throw new BowlingGameException('Invalid rolls count');
        }
    }
}