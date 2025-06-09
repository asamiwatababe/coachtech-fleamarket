<?php

namespace App\Http\Responses;

use Laravel\Fortify\Contracts\LoginResponse as LoginResponseContract;

class CustomLoginResponse implements LoginResponseContract
{
    public function toResponse($request)
    {
        return redirect(config('fortify.home')); // 常に fortify.php の home にリダイレクト
    }
}
