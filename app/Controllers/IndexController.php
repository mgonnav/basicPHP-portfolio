<?php
namespace App\Controllers;

use App\Models\{ Job, Project };

class IndexController extends BaseController {
  public function indexAction() {
    $jobs = Job::all();
    $projects = Project::all();

    $name = 'Mateo Gonzales Navarrete';
    $limitMonths = 60;

    foreach ($jobs as $job)
      $job->duration = $job->getDurationAsString();
    foreach ($projects as $project)
      $project->duration = $project->getDurationAsString();

    return $this->renderHTML('index.twig', [
      'name' => $name,
      'jobs' => $jobs,
      'projects' => $projects
    ]);
  }
}