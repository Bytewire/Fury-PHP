<?php
echo 'got here';
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

/*
$jobs = $pheanstalk->statsTube('test');

print_r($jobs);

echo '<Br><BR><pre>';

$stats = $pheanstalk->stats();

print_r($stats);

echo '</pre>';


$watch = $pheanstalk->watch('test');

print_r($watch);
*/

class Worker {
  
  private $path;

  public function __construct($path) {
    $this->setBasePath($path);
    $this->log('starting');
    $this->pheanstalk = new Pheanstalk('87.124.86.12:11300');
  }
   
  public function __destruct() {
    $this->log('ending');
  }
  
  private function setBasePath($path) {
    $this->path = $path;
  }

  public function run() {
    $this->log('starting to run');
    $cnt = 0;
    $done_jobs = array();

    while(1) {
      $job = $this->pheanstalk->watch('test')->ignore('default')->reserve();
      $job_encoded = json_decode($job->getData(), false);
      $done_jobs[] = $job_encoded;
      $this->log('job:'.print_r($job_encoded, 1));
      $this->pheanstalk->delete($job);
      $cnt++;

      $memory = memory_get_usage();

      $this->log('memory:' . $memory);

      if($memory > 1000000) {
        $this->log('exiting run due to memory limit');
        exit;
      }

      usleep(10);
    }
  }
  
  private function log($txt) {
    file_put_contents('/Applications/MAMP/htdocs/boardwalk/logs/worker.txt', $txt . "\n", FILE_APPEND);
  }
}

$worker = new Worker(dirname($argv[0]));
$worker->run();