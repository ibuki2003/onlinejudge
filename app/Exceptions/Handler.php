<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Symfony\Component\HttpKernel\Exception\HttpException;

class Handler extends ExceptionHandler
{
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
     * @param  \Exception  $exception
     * @return void
     */
    public function report(Exception $exception)
    {
        parent::report($exception);
    }

    
    protected function renderHttpException(HttpException $e){
        $this->registerErrorViewPaths();
        $message = $e->getMessage();
        switch ($e->getStatusCode()) {
            case 400:
                $name = "Bad Request";
                break;
            case 403:
                $name = "Forbidden";
                break;
            case 404:
                $name = "Not Found";
                break;
            case 500:
                $name = "Internal Server Error";
                $message="";
                break;
            default:
                $name = "Error";
                break;
            
        }

        return response()->view('error', [
            'statuscode' => (string)($e->getStatusCode()),
            'name' => $name,
            'message' => $message
        ],$e->getStatusCode(), $e->getHeaders());
    }
}
