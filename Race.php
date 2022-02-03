<?php

include "RaceResult.php";
include "RoundResult.php";

class Race
{

  //assign some variables
  public $cars = [];
  public $totalSpeed = 22;
  public $totalCars = 5;
  public $raceWinner = "";
  public $previousCar = "";
  // Track Stuff
  public $lastTrack;
  public $nextTrack;


  // Round Stuff
  public $round;
  public $roundResult = [];
  public $totalCurved = 0;
  public $totalStraight = 0;

  public $elementsMax = 40;
  public $elementsLow = 36;

  public $firstPlace = 0;
  public $secondPlace = 0;
  public $thirdPlace = 0;
  public $fourthPlace = 0;
  public $fifthPlace = 0;

  public $firstPlaceCar = "";
  public $secondPlaceCar = "";
  public $thirdPlaceCar = "";
  public $fourthPlaceCar = "";
  public $fifthPlaceCar = "";




  public function runRace(): RaceResult
  {
    return new RaceResult();
  }


  //! VALIDATE CARS SPEEDS

  public function checkSpeeds($i)
  {

    // Generate our random speeds
    $curve = rand(4, 18);
    $straight = rand(4, 18);

    $total = $curve + $straight;
    // if total speed is greater than allowable speed, return speed checl
    if ($total > $this->totalSpeed) {

      return $this->checkSpeeds($i);
    } else if ($total != $this->totalSpeed) {

      //if curved speed and straight speed doesnt equal total allowable speed, rerun checks
      return $this->checkSpeeds($i);

      // Otherwise speeds are good, return value.
    } else {
      return ['straight' => $straight, 'curve' => $curve, 'elements' => 0];
    }
  }

  // Create Random Cars
  public function randomCar()
  {
    $i = rand(0, 14);
    $makes = [
      'Subaru WRX STI',
      'Nissan R34 GTR',
      'Mitsubishi Evolution 8',
      'MK4 Toyota Supra',
      'Volkswaggen Golf GTI',
      'Ferrari Spyder',
      'Lamborghini Aventador',
      'Koenigsegg Agera',
      'McLaren 720s',
      'Tesla Roadster',
      'Buggati Veyron',
      'Ferrari 812 Superfast',
      'Dodge Demon',
      'Porsche 911 GT2',
      'Hennessey Venom GT',
    ];
    return $makes[$i];
  }


  //! Generate all car speeds based on rules for total cars


  public function generateCarSpeeds()
  {
    $car = [];
    for ($i = 0; $i < $this->totalCars; $i++) {
      $car = $this->checkSpeeds($i);
      $car['name'] = 'Car [' . $i . ']';
      $car['model'] = $this->randomCar();
      array_push($this->cars, $car);
    }
    return $this->cars;
  }



  //! METHOD TO START THE RACE


  public function startRace()
  {

    //Generate our cars
    $this->generateCarSpeeds();
    // random for straight or curve    true / false respectively
    $track = rand(0, 1);
    if ($track) {
      // print "<h1>Straight Track</h1>";
      $this->straightTrack();
    } else {
      // print "<h1>Curved Track</h1>";
      $this->curvedTrack();
    } // if /else track
    return $this->cars;
  } // start race




  //! Curved track method


  public function curvedTrack()
  {
    //change elements value
    for ($i = 0; $i < $this->totalCars; $i++) {
      //First check to see if the element is empty.
      if (empty($this->cars[$i]['elements'])) {
        // Assign intital value
        $this->cars[$i]['elements'] += $this->cars[$i]['curve'];
      } else {
        // assign value + value
        $this->cars[$i]['elements'] += $this->cars[$i]['curve'];
      }
    }
    //set last track and change round
    $this->lastTrack = 'curve';
    $this->totalCurved++;
    $this->round++;

    return $this->continueRace($this->lastTrack);
  }





  //! Straight track method



  public function straightTrack()
  {
    //change elements value
    for ($i = 0; $i < $this->totalCars; $i++) {

      if (empty($this->cars[$i]['elements'])) {
        // Assign intital value
        $this->cars[$i]['elements'] += $this->cars[$i]['straight'];
      } else {
        // assign value + value
        $this->cars[$i]['elements'] += $this->cars[$i]['straight'];
      }
    }
    //set last track change rounds
    $this->lastTrack = 'straight';
    $this->totalStraight++;
    $this->round++;

    return $this->continueRace($this->lastTrack);
  }


  //! CONTINUE RACE METHOD

  public function continueRace($lastTrack)
  {
    // what is next track
    $this->nextTrack = rand(0, 1);

    // Use last track to find where to go next
    if ($lastTrack == 'curve') {

      // Find out if any elements are close to 40
      for ($i = 0; $i < $this->totalCars; $i++) {
        // is there an element near 40?
        if ($this->cars[$i]['elements'] >= ($this->elementsLow * ($this->round + 1)) && $this->cars[$i]['elements'] >= ($this->elementsMax * ($this->round + 1)) && $this->nextTrack) {
          //assign new value of element max
          $this->cars[$i]['elements'] = ($this->elementsMax * ($this->round + 1));
        } else {
          //assign element + curve value
          $this->cars[$i]['elements'] += $this->cars[$i]['curve'];
        }
      } // For Loop

    } else {

      // Find out if any elements are close to 40 on straight tracks
      for ($i = 0; $i < $this->totalCars; $i++) {
        // is there an element near 40?

        if ($this->cars[$i]['elements'] >= ($this->elementsLow * ($this->round + 1)) && $this->cars[$i]['elements'] <= ($this->elementsMax * ($this->round + 1)) && !$this->nextTrack) {
          //assign new value of element max
          $this->cars[$i]['elements'] = ($this->elementsMax * ($this->round + 1));
        } else {
          //assign element + straight value
          $this->cars[$i]['elements'] += $this->cars[$i]['straight'];
        } // For Loop
      }
    }




    return $this->pitStop();
  }


  //! CARS PITSTOP

  // validate variables
  public function pitStop()
  {

    //Which car has which position?
    // echo "<h4>" . $this->round . "</h4>";
    for ($i = 0; $i < $this->totalCars; $i++) {

      //Can this car be in first place?
      if ($this->cars[$i]['elements'] >= $this->firstPlace) {

        if ($this->cars[$i]['elements'] == $this->firstPlace) {
          // There was a tie
          $name = $this->cars[$i]['model'];
          $this->firstPlace = $this->cars[$i]['elements'];
          $this->firstPlaceCar = $name . " <b> & </b> " . $this->cars[$i]['model'];
        } else {
          // No Tie
          $this->firstPlace = $this->cars[$i]['elements'];
          $this->firstPlaceCar = $this->cars[$i]['model'];
        }

        // Can this car be in second place?
      } elseif ($this->cars[$i]['elements'] >= $this->secondPlace && $this->cars[$i]['elements'] < $this->firstPlace) {

        if ($this->cars[$i]['elements'] == $this->secondPlace) {
          // There was a tie
          $name = $this->cars[$i]['model'];
          $this->secondPlace = $this->cars[$i]['elements'];
          $this->secondPlaceCar = $name . " <b> & </b> " . $this->cars[$i]['model'];
        } else {
          // No Tie
          $this->secondPlace = $this->cars[$i]['elements'];
          $this->secondPlaceCar = $this->cars[$i]['model'];
        }
        //Can this car be in third place?
      } elseif ($this->cars[$i]['elements'] >= $this->thirdPlace  && $this->cars[$i]['elements'] < $this->secondPlace) {

        if ($this->cars[$i]['elements'] == $this->thirdPlace) {
          // There was a tie
          $name = $this->cars[$i]['model'];
          $this->thirdPlace = $this->cars[$i]['elements'];
          $this->thirdPlaceCar = $name . " <b> & </b> " . $this->cars[$i]['model'];
        } else {
          // No Tie
          $this->thirdPlace = $this->cars[$i]['elements'];
          $this->thirdPlaceCar = $this->cars[$i]['model'];
        }
      } elseif ($this->cars[$i]['elements'] >= $this->fourthPlace  && $this->cars[$i]['elements'] < $this->thirdPlace) {

        if ($this->cars[$i]['elements'] == $this->fourthPlace) {
          // There was a tie
          $name = $this->cars[$i]['model'];
          $this->fourthPlace = $this->cars[$i]['elements'];
          $this->fourthPlaceCar = $name . " <b> & </b> " . $this->cars[$i]['model'];
        } else {
          // No Tie
          $this->fourthPlace = $this->cars[$i]['elements'];
          $this->fourthPlaceCar = $this->cars[$i]['model'];
        }
      } elseif ($this->cars[$i]['elements'] >= $this->fifthPlace  && $this->cars[$i]['elements'] < $this->fourthPlace) {

        if ($this->cars[$i]['elements'] == $this->fifthPlace) {
          // There was a tie
          $name = $this->cars[$i]['model'];
          $this->fifthPlace = $this->cars[$i]['elements'];
          $this->fifthPlaceCar = $name . " <b> & </b> " . $this->cars[$i]['model'];
        } else {
          // No Tie
          $this->fifthPlace = $this->cars[$i]['elements'];
          $this->fifthPlaceCar = $this->cars[$i]['model'];
        }
      } // IF ELSE LOGIC

    } // FOR LOOP



    //Push to our RoundResult Class
    $carPositions = [
      '1st ' . $this->firstPlaceCar => $this->firstPlace,
      '2nd ' . $this->secondPlaceCar => $this->secondPlace,
      '3rd ' . $this->thirdPlaceCar => $this->thirdPlace,
      '4th ' . $this->fourthPlaceCar => $this->fourthPlace,
      '5th ' . $this->fifthPlaceCar => $this->fifthPlace,
    ];
    $result = new RoundResult($this->round, $carPositions);
    $result->pushRound();
    // Clear values
    unset($carPositions);





    // Set max rounds so script doesn't break (verified via testing) one car always passes 2000 elements
    if ($this->round < 103) {

      if ($this->nextTrack) {

        // Has a car hit 2000 elements?
        for ($i = 0; $i < $this->totalCars; $i++) {

          if ($this->cars[$i]['elements'] >= 2000 && $this->cars[$i]['elements'] > $this->previousCar) {
            $this->previousCar = $this->cars[$i]['elements'];
            if ($this->previousCar > $this->raceWinner) {
              $this->raceWinner = $this->previousCar;
            } elseif ($this->previousCar == $this->raceWinner) {
              $this->raceWinner = "It's a TIE ";
            }
          }
        }

        if ($this->raceWinner == "") {
          $this->nextTrack = "";

          // Have we hit 1000 elements of straight tracks?
          if ($this->totalStraight >= 40) {
            return $this->curvedTrack();
          } else {
            return $this->straightTrack();
          }
        } else {

          $result->theResults();
        }
      } else {
        // Has a car hit 2000 elements?
        for ($i = 0; $i < $this->totalCars; $i++) {

          if ($this->cars[$i]['elements'] >= 2000 && $this->cars[$i]['elements'] > $this->previousCar) {
            $this->previousCar = $this->cars[$i]['elements'];
            if ($this->previousCar > $this->raceWinner) {
              $this->raceWinner = $this->previousCar;
            } elseif ($this->previousCar == $this->raceWinner) {
              $this->raceWinner = "It's a TIE ";
            }
          }
        } // For Loops

        if ($this->raceWinner == "") {
          $this->nextTrack = "";

          // Have we hit 1000 elements of straight tracks?
          if ($this->totalCurved >= 40) {
            return $this->straightTrack();
          } else {
            return $this->curvedTrack();
          }
        } else {

          $result->theResults();
        }
      } // IF NEXT TRACK


    } else { // We've hit 100 rounds      
      return $this->pitStop();
    }
  }
}
