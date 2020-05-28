<?php

use PF\BowlingGame;
use PF\BowlingGameException;
use PHPUnit\Framework\TestCase;

class BowlingGameTest extends TestCase
{
    public function testGetScore_withAllZeroes_getZero()
    {
        $game = new BowlingGame();

        for ($i = 0; $i < 20; $i++) {
            $game->roll(0);
        }

        $score = $game->getScore();

        self::assertEquals(0, $score);
    }

    public function testGetScore_withAllOnes_get20asScore()
    {
        $game = new BowlingGame();

        for ($i = 0; $i < 20; $i++) {
            $game->roll(1);
        }

        $score = $game->getScore();

        self::assertEquals(20, $score);
    }

    public function testGetScore_withASpare_returnScoreWithSpareBonus()
    {
        $game = new BowlingGame();

        $game->roll(2);
        $game->roll(8);
        $game->roll(5);

        //2+8+5 (spare bonus) +5 +17

        for ($i = 0; $i < 17; $i++) {
            $game->roll(1);
        }

        $score = $game->getScore();

        self::assertEquals(37, $score);
    }

    public function testGetScore_withAStrike_addStrikeBonus()
    {
        $game = new BowlingGame();

        $game->roll(10);
        $game->roll(5);
        $game->roll(3);

        // 10 + 5 + (bonus) + 3 (bonus) + 5 + 16

        for ($i = 0; $i < 16; $i++) {
            $game->roll(1);
        }

        $score = $game->getScore();

        self::assertEquals(42, $score);
    }

    public function testGetScore_withPerfectGame_returns300()
    {
        $game = new BowlingGame();

        for ($i = 0; $i < 12; $i++) {
            $game->roll(10);
        }

        $score = $game->getScore();

        self::assertEquals(300, $score);
    }

    public function testGetScore_withTooMuchRolls_throwsException()
    {
        $game = new BowlingGame();

        for ($i = 0; $i < 24; $i++) {
            $game->roll(1);
        }

        self::expectException(BowlingGameException::class);

        $game->getScore();
    }

    public function testGetScore_withNotEnoughRolls_throwsException()
    {
        $game = new BowlingGame();

        for ($i = 0; $i < 12; $i++) {
            $game->roll(1);
        }

        self::expectException(BowlingGameException::class);

        $game->getScore();
    }

    public function testGetScore_withIllegalQuantity_throwsException()
    {
        $game = new BowlingGame();

        for ($i = 0; $i < 15; $i++) {
            $game->roll(10);
        }

        self::expectException(BowlingGameException::class);

        $game->getScore();
    }

    public function testGetScore_withIllegalLastRoll_throwsException()
    {
        $game = new BowlingGame();

        for ($i = 0; $i < 20; $i++) {
            $game->roll(4);
        }

        $game->roll(10);

        self::expectException(BowlingGameException::class);

        $game->getScore();
    }

    public function testRoll_withNegative_throwsException()
    {
        $game = new BowlingGame();

        self::expectException(BowlingGameException::class);

        $game->roll(-1);
    }

    public function testRoll_withExtraPoints_throwsException()
    {
        $game = new BowlingGame();

        self::expectException(BowlingGameException::class);

        $game->roll(11);
    }
}
