<?php
require_once('pheanstalk/pheanstalk_init.php');
$pheanstalk = new Pheanstalk('87.124.86.12:11300');

for($i=0; $i<4; $i++) {
  $job = new stdClass();
  $job->envelope_id = rand();
  $job->date = date('Y-m-d H:i:s');
  
  $array = array(
  	"function" => "process_detective",
  	"rowid" => 1
  );
  
  $job_data = json_encode($job);
  $pheanstalk->useTube('test')->put($job_data);
  echo "pushed: " . $job_data . "\n";
}