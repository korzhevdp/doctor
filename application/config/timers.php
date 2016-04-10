<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

$config['starthour'] = 8;
$config['endhour'] = 21;
$config['discretion'] = 30;
$config['q1'] = 60 / $config['discretion'];
$config['q2'] = ($config['endhour'] - $config['starthour']) * $config['q1'];

/* End of file timers.php */
/* Location: ./application/config/timers.php */