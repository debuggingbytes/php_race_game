<?php

class RaceResult
{
  /**
   * @var array of StepResult
   */
  private $roundResults = [];
  private static $results = [];

  public function getRoundResults(): array
  {
    $this->roundResults = self::$results;
    return $this->roundResults;
  }


  public static function endRace($result)
  {
    return array_push(self::$results, $result);
  }
}
