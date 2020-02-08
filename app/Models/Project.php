<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Project extends Model {
  protected $table = 'projects';

  public function getDurationAsString() {
    $mths = $this->months;
    $years = floor($mths / 12);
    $mths %= 12;

    if ($years && $mths)
      return "Project duration: $years year(s) $mths month(s)";
    else if (!$mths)
      return "Project duration: $years year(s)";
    return "Project duration: $mths month(s)";
  }
}