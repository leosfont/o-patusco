<?php

namespace App\Services;

use App\Mail\LoginTokenMail;
use App\Repositories\UserRepository;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;

class UserService extends Service
{
    public function __construct(UserRepository $userRepository)
    {
        parent::__construct($userRepository);
    }

    public function create(array $data)
    {
        $data['password'] = Hash::make($data['password'] ?? str(random_int(0, 9)));
        $user = $this->repository->create($data);
        if ($user) {
            $user->assignRole('client');
            $token = $user->createToken('auth_token')->plainTextToken;
            $this->sendLoginToken($user, $token);
        }

        return $user;
    }

    public function sendLoginToken($user, $token)
    {
        Mail::to($user->email)->send(new LoginTokenMail($user, $token));
    }
}
