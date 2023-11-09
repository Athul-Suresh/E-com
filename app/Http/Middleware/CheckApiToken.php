<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Auth\AuthenticationException;
use Symfony\Component\HttpFoundation\Response;

class CheckApiToken
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */

        public function handle(Request $request, Closure $next)
        {
            // return response()->json(['status'=>false,'message' => $request->header('Authorization')], 401);

            if ($request->header('Authorization')) {
                $token = str_replace('Bearer ', '', $request->header('Authorization'));
                $request->headers->set('Authorization', 'Bearer ' . $token);
                try {
                    $user = Auth::guard('api')->authenticate();
                    $request->setUserResolver(function () use ($user) {
                        return $user;
                    });
                } catch (AuthenticationException $e) {
                    return response()->json(['status'=>false,'message' => 'Unauthorized','err'=>$e->getMessage()], 401);
                }
            } else {
                return response()->json(['message' => 'Missing Authorization header','status'=>false], 404);
            }

            return $next($request);
        }

        // protected function checkTokenScopes($user)
        // {
        //         // Get the token instance
        //     // $accessToken = $user->token();

        //     // // Get the scopes associated with the token
        //     // $tokenScopes = $accessToken->scopes;

        //     // // Define the required scopes for the specific route or action
        //     // $requiredScopes = ['scope1', 'scope2'];

        //     // // Check if the token has the required scopes
        //     // foreach ($requiredScopes as $scope) {
        //     //     if (!$tokenScopes->contains($scope)) {
        //     //         // Throw an exception or handle the missing scope accordingly
        //     //         throw new AuthorizationException("Insufficient scope: {$scope}");
        //     //     }
        //     // }
        // }


}
