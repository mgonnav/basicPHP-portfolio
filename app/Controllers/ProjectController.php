<?php
namespace App\Controllers;

use App\Models\Project;
use Respect\Validation\Validator;

class ProjectController extends BaseController {
  public function getAddProject() {
    return $this->renderHTML('addProject.twig');
  }

  public function postSaveProject($request) {
    $postData = $request->getParsedBody();

    $projectValidator = Validator::key('title', Validator::stringType()->notEmpty())
                ->key('description', Validator::stringType()->notEmpty())
                ->key('months', Validator::numeric());

    try {
      $projectValidator->assert($postData);

      $files = $request->getUploadedFiles();
      $logo = $files['logo'];

      $filename = null;
      if ($logo->getError() == UPLOAD_ERR_OK) {
        $filename = $logo->getClientFilename();
        $logo->moveTo("uploads/$filename");
      }

      $project = new Project();
      $project->title = $postData['title'];
      $project->description = $postData['description'];
      $project->months = $postData['months'];
      $project->logo = $filename;
      $project->save();

      $responseMessage = 'Saved';
    }
    catch (\Exception $e) {
      $responseMessage = $e->getMessage();
    }

    return $this->renderHTML('addProject.twig', [
      'responseMessage' => $responseMessage
    ]);
  }
}