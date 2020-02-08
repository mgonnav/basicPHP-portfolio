<?php
namespace App\Controllers;

use App\Models\User;
use Respect\Validation\Validator;
use Zend\Diactoros\Response\RedirectResponse;

class AuthController extends BaseController {
  public function getLogin() {
    return $this->renderHTML('login.twig');
  }

  public function postLogin($request) {
    $responseMessage = null;
    $postData = $request->getParsedBody();

    $userValidator = Validator::key('email', Validator::email())
                ->key('password', Validator::stringType()->length(8, null));

    try {
      $userValidator->assert($postData);

      $user = User::where('email', $postData['email'])->first();
      if ($user) {
        if ( \password_verify($postData['password'], $user->password) ) {
          $_SESSION['userId'] = $user->id;
          return new RedirectResponse('/admin');
        }
        else
          $responseMessage = 'Bad credentials.';
      }
      else
        $responseMessage = 'Bad credentials.';
    }
    catch (\Exception $e) {
      $responseMessage = 'Fill in the required fields!';
    }

    return $this->renderHTML('login.twig', [
      'responseMessage' => $responseMessage
    ]);
  }

  public function getLogout() {
    unset( $_SESSION['userId'] );
    return $this->renderHTML('login.twig');
  }
}
