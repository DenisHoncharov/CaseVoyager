<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Auth0\Laravel\Facade\Auth0;
use Illuminate\Http\Request;

class UserApiController extends Controller
{
    /**
     * This is tmp route if we wanted to use it in the future.
     * (It is no tests for it, if tests exist - remove this comment)
     *
     * @return \Illuminate\Http\JsonResponse
     * @throws \Psr\Container\ContainerExceptionInterface
     * @throws \Psr\Container\NotFoundExceptionInterface
     */
    public function getCurrentUser()
    {
        $user = auth()->id();
        $profile = cache()->get($user);

        if (null === $profile) {
            $endpoint = Auth0::management()->users();
            $profile = $endpoint->get($user);
            $profile = Auth0::json($profile);

            cache()->put($user, $profile, 120);
        }

        $name = $profile['name'] ?? 'Unknown';
        $email = $profile['email'] ?? 'Unknown';

        return response()->json([
            'name' => $name,
            'email' => $email,
        ]);
    }
}
