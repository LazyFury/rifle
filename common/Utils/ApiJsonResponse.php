<?php

namespace Common\Utils;

class ApiJsonResponse
{

    // ErrCode
    /**
     * 错误码
     * @var array
     */
    const ErrCode = [
        'OK' => 200,
        'BadRequest' => 400,
        'Unauthorized' => 401,
        'Forbidden' => 403,
        'NotFound' => 404,
        'MethodNotAllowed' => 405,
        'InternalServerError' => 500,
        "NeedBindPhone" => 1001,
    ];

    // ErrCode Message
    const ErrCodeMessage = [
        200 => "Ok.",
        400 => "Bad Request.",
        401 => "Unauthorized.",
        403 => "Forbidden.",
        404 => "Not Found.",
        405 => "Method Not Allowed.",
        500 => "Internal Server Error.",
        1001 => "Need Bind Phone.",
    ];

    // response
    public static function response($data, $code = 200, $message = null, $http_status = 200)
    {

        $system_message = trans(self::ErrCodeMessage[$code] ?? 'Unknown Error');
        $message = $message == null ? $system_message : $message;
        return response()->json([
            'code' => $code,
            'message' => $message,
            'data' => $data,
        ], $http_status);
    }

    // success
    public static function success($data, $message = null, $code = self::ErrCode['OK'], $http_status = 200)
    {
        return self::response($data, code: $code, message: $message, http_status: $http_status);
    }

    // error
    public static function error(string $message = null, int $code = self::ErrCode['BadRequest'], $data = null, int $http_status = 400, )
    {
        return self::response($data, code: $code, message: $message, http_status: $http_status);
    }

    // unauthenticated
    public static function unauthenticated($extra=null)
    {
        $code = self::ErrCode['Unauthorized'];
        return self::error(null, $code, data:$extra, http_status: 401);
    }

    // forbidden
    public static function forbidden()
    {
        $code = self::ErrCode['Forbidden'];
        return self::error(null, $code, http_status: 403);
    }
}
