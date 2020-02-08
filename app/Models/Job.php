<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Job extends Model {
  protected $table = 'jobs';

  public function getDurationAsString() {
    $mths = $this->months;
    $years = floor($mths / 12);
    $mths %= 12;

    if ($years && $mths) 
      return "Job duration: $years year(s) $mths month(s)";
    else if (!$mths)
      return "Job duration: $years year(s)";
    return "Job duration: $mths month(s)";
  }
}