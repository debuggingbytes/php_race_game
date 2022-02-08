<?php
include('Race.php');


// run a race and print the results
$test = new Race;
$test->startRace();
print "<h1>CAR STATISTICS</h1>";

print "<pre>";
print_r($test->cars);
print "</pre>";



$results = $test->runRace();
print "<h1>RACE RESULTS</h1>";

print "<pre>";
print_r($results->getRoundResults());
print "</pre>";
