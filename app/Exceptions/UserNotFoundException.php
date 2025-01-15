<?php

namespace App\Exceptions;

use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class UserNotFoundException extends NotFoundHttpException
{
    /**
     * Create a new exception instance.
     *
     * @param  string  $message
     * @return void
     */
    public function __construct($message = 'User not found.')
    {
        parent::__construct($message);
    }
    public function render($request)
    {
        return response()->json(['error' => 'User not found.'], 404);
    }
}
