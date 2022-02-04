<?php
include('Race.php');


// run a race and print the results
$test = new Race;
$test->startRace();


$results = $test->runRace();
print "<h1>RACE RESULTS</h1>";

print "<pre>";
print_r($results->getRoundResults());
print "</pre>";
