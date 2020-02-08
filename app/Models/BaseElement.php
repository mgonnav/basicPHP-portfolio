<?php
namespace App\Models;

class BaseElement implements Printable {
  protected $title;
  public $description;
  public $months;
  public $visible = true;

  public function __construct($title, $description) {
    $this->setTitle($title);
    $this->description = $description;
  }

  public function setTitle($title) {
    if ( !isset($title) ) $this->title = 'N/A';
    else $this->title = $title;
  }
  public function getTitle() {
    return $this->title;
  }

  public function getDurationAsString() {
    $mths = $this->months;
    $years = floor($mths / 12);
    $mths %= 12;

    if ($years && $mths) 
      return "$years year(s) $mths month(s)";
    else if (!$mths)
      return "$years year(s)";
    return "$mths month(s)";
  }

  public function getDescription() {
    return $this->description;
  }
}