<?php
namespace App\Traits;


Trait ApiResponseTrait
{
    /**
     * 定義統一例外回應方式
     *
     * @param mixed $message 錯誤訊息
     * @param mixed $status HTTP狀態碼
     * @param mixed|null $code 選填，自定義錯誤編碼
     * @return \Illuminate\Http\Response
     */

     public function errorResponse($message, $status, $code = null)
     {
        $code = $code ?? $status; //$code為null時預設HTTP狀態碼

        return response()->json(
            [
                'message'=>$message,
                'code'=>$code
            ],
            $status
        );
     }
}
