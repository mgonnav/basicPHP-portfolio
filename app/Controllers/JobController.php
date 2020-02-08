<?php
namespace App\Controllers;

use App\Models\Job;
use Respect\Validation\Validator;

class JobController extends BaseController {
  public function getAddJob() {
    return $this->renderHTML('addJob.twig');
  }

  public function postSaveJob($request) {
    $postData = $request->getParsedBody();

    $jobValidator = Validator::key('title', Validator::stringType()->notEmpty())
                ->key('description', Validator::stringType()->notEmpty())
                ->key('visible', Validator::boolVal())
                ->key('months', Validator::numeric());

    try {
      $jobValidator->assert($postData);

      $job = new Job();
      $job->title = $postData['title'];
      $job->description = $postData['description'];
      $job->visible = $postData['visible'];
      $job->months = $postData['months'];
      $job->save();

      $responseMessage = 'Saved';
    }
    catch (\Exception $e) {
      $responseMessage = $e->getMessage();
    }

    return $this->renderHTML('addJob.twig', [
      'responseMessage' => $responseMessage
    ]);
  }
}