<?php


class RoundResult
{
  /**
   * @var int
   */
  public $step;

  /**
   * @var array
   */
  public $carsPosition;
  public $rounds = [];

  public function __construct(int $step, array $carsPosition)
  {
    $this->step = $step;
    $this->carsPosition = $carsPosition;
  }

  public function pushRound()
  {
    $result = $this->carsPosition;
    array_push($this->rounds, $result);
    return $this->theResults();
  }

  public function theResults()
  {
    // $result = new RaceResult();
    RaceResult::endRace($this->rounds);
  }
}
