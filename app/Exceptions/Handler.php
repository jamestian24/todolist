<?php

namespace App\Exceptions;

use App\Traits\ApiResponseTrait;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use InvalidArgumentException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Exception\MethodNotAllowedException;
use Throwable;


class Handler extends ExceptionHandler
{
    /**
     * The list of the inputs that are never flashed to the session on validation exceptions.
     *
     * @var array<int, string>
     */
    protected $dontFlash = [
        'current_password',
        'password',
        'password_confirmation',
    ];

    /**
     * Register the exception handling callbacks for the application.
     */
    public function register(): void
    {
        $this->reportable(function (Throwable $e) {
            //
        });
    }

    use ApiResponseTrait;
    public function render($request, Throwable $exception)
    {
        if($request->expectsJson()){
            //1. Model 找不到資源
            if($exception instanceof ModelNotFoundException){
                // 呼叫 errorResponse 方法(特徵撰寫的方法)
                return $this->errorResponse(
                    '找不到資源',
                    Response::HTTP_NOT_FOUND
                );
                // return response()->json(
                //     [
                //         'error' => '找不到資源'
                //     ],
                //     Response::HTTP_NOT_FOUND
                // );
            }
            //2. 網址輸入錯誤(新增判斷)
            if($exception instanceof NotFoundHttpException){
                return $this->errorResponse(
                    '無法找到此網址',
                    Response::HTTP_NOT_FOUND
                );
            }
            if($exception instanceof InvalidArgumentException){
                return $this->errorResponse(
                    '無法找到此ID',
                    Response::HTTP_NOT_FOUND
                );
            }
            //3. 網址不允許該請求動詞(新增判斷)
            if($exception instanceof MethodNotAllowedException){
                return $this->errorResponse(
                    $exception->getMessage(),
                    Response::HTTP_METHOD_NOT_ALLOWED
                );
            }
        }

        //執行父類別render的程式
        return parent::render($request, $exception);
    }
    protected function unauthenticated($request, AuthenticationException $exception)
    {
        if ($request->expectsJson()){
            return $this->errorResponse(
                $exception->getMessage(),
                Response::HTTP_UNAUTHORIZED
            );
        } else{
            return redirect()->guest($exception->redirectTo() ?? route('login'));
        }
    }
}
