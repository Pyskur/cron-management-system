<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Models\UsersApiHistories;

class RequestLoggerMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        $logEntry = new UsersApiHistories();
        $user_id = null;

        if ($request->user()) {
            $user_id = $request->user()->id;
        }

        $logEntry->user_id = $user_id;
        $logEntry->ip = $request->ip();
        $logEntry->api_path = $request->fullUrl();
        $logEntry->method = $request->method();

        $logEntry->save();

        $dynamodbClient = \AWS::createClient('DynamoDB');
        $dynamodbClient->putItem([
            'Item' => [
                'id' => [
                    'S' => (string) Str::uuid()
                ],
                'user_id' => [
                    'S' => $user_id ?? ''
                ],
                'ip' => [
                    'S' => $request->ip()
                ],
                'api_path' => [
                    'S' => $request->fullUrl()
                ],
                'method' => [
                    'S' => $request->method()
                ],
            ],
            'TableName' => 'users_api_histories',
        ]);

        return $next($request);
    }
}
