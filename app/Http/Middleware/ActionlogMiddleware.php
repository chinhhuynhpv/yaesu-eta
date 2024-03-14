<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Models\TActionLog;


class ActionlogMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        $response = $next($request);
        $this->insertActionLog($request);
        return $response;
    }

    private function insertActionLog(Request $request)
    {
        // 認証していないものをlog取得するとパスワードが推測できるため
        // 認証したユーザーのみアクションログを取得する。
        if (\Auth::user() != null) {
            $log = new TActionlog();
            $route = explode("@",  \Route::getCurrentRoute()->getActionName());
            $log->controller = count($route) > 0? $route[0]: '';
            $log->action = count($route) == 2? $route[1]: '';
            $log->parameter = json_encode(\Request::all());
            $log->ip =  \Request::ip();
            $log->method = \Request::getMethod();
            // auth::user の実装クラスを取得
            $implements_class = get_class(\Auth::user());
            $user_type = str_replace("App\\Models\\", "", $implements_class);
            $log->user_type = $user_type;
            $log->user_id = \Auth::user()->id;
            $log->shop_id = \Auth::user()->shop_id;
            $log->is_admin = \Auth::user()->is_admin;
            $log->save();
        }
    }

}
