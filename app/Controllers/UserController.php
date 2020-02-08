<?php
namespace App\Controllers;

use App\Models\User;
use Respect\Validation\Validator;

class UserController extends BaseController {
  public function getAddUser() {
    return $this->renderHTML('addUser.twig');
  }

  public function postSaveUser($request) {
    $postData = $request->getParsedBody();

    $userValidator = Validator::key('username', Validator::stringType()->length(1, 20)->noWhitespace())
                ->key('email', Validator::email())
                ->key('password', Validator::stringType()->length(8, null));
    
    try {
      $userValidator->assert($postData);

      $user = new User();
      $user->username = $postData['username'];
      $user->email = $postData['email'];
      $user->password = password_hash($postData['password'], PASSWORD_DEFAULT);
      $user->save();

      $responseMessage = 'Saved';
    }
    catch (\Exception $e) {
      $responseMessage = $e->getMessage();
    }

    return $this->renderHTML('addUser.twig', [
      'responseMessage' => $responseMessage
    ]);
  }
}
