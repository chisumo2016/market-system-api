<?php

namespace App\Exceptions;

use App\Traits\ApiResponser;
use Exception;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\QueryException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;


class Handler extends ExceptionHandler
{
    use ApiResponser;
    /**
     * A list of the exception types that are not reported.
     *
     * @var array
     */
    protected $dontReport = [
        //


    ];

    /**
     * A list of the inputs that are never flashed for validation exceptions.
     *
     * @var array
     */
    protected $dontFlash = [
        'password',
        'password_confirmation',
    ];

    /**
     * Report or log an exception.
     *
     * This is a great spot to send exceptions to Sentry, Bugsnag, etc.
     *
     * @param  \Exception  $exception
     * @return void
     */
    public function report(Exception $exception)
    {
        parent::report($exception);
    }

    /**
     * Render an exception into an HTTP response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Exception  $exception
     * @return \Illuminate\Http\Response
     */
    public function render($request, Exception $exception)
    {

            if ($exception instanceof  ValidationException){
                return $this->convertValidationExceptionToResponse($exception, $request);
            }

            if($exception instanceof  ModelNotFoundException){ // Handle the Model
                $modelName = strtolower(class_basename($exception->getModel()));
                return $this->errorResponse("Does not exists any {$modelName} with the specified identificator", 404);
            }

        if($exception instanceof  AuthorizationException){ // Handle the Authorization
            return $this->errorResponse($exception->getMessage(), 403);
        }

        if($exception instanceof  NotFoundHttPException){ // Handle the Authorization
            return $this->errorResponse("The specified URL cannot be found ", 404);
        }

        if($exception instanceof  MethodNotAllowedHttpException){ // Handle the Method
            return $this->errorResponse("The specified method for the request is invalid ", 405);
        }

        if($exception instanceof  HttpException){ // Handle the HttpException
            return $this->errorResponse($exception->getMessage(), $exception->getStatusCode());
        }

        if($exception instanceof  QueryException){ // Handle the HttpException
           $errorCode =  $exception -> errorInfo[1];

           if ($errorCode == 1451) {
               return $this->errorResponse('Cannot remove this resource permanetly  . It is related  with other resource ', 409);
           }
        }



    }

     // Handling AuthenticationException
    protected function unauthenticated($request, AuthenticationException $exception)
    {
        return $this->errorResponse('Unauthenticated .', 401);

        /*return $request->expectsJson()
            ? response()->json(['message' => 'Unauthenticated.'], 401)
            : redirect()->guest(route('authentication.index'));*/


    }


/**
* Create a Symfony response for the given exception.
*
* @param  \Exception  $e
* @return mixed
*/
    protected function convertValidationExceptionToResponse(validationException $e, $request)
    {
        $errors = $e->validator->errors()->getMessages();
         return $this->errorResponse($errors, 422);
         //return response()->json($errors, 422);
    }


}
