<?php

return [
    'latest_limit' => 5,
    'parameters' => [
        'branch' => env('LN_CIRCLECI_BRANCH'),
    ],
    'project' => env('LN_CIRCLECI_PROJECT'),
    'throttle' => env('LN_CIRCLECI_THROTTLE'),
    'token' => env('LN_CIRCLECI_TOKEN'),
    'user_model' => 'App\Models\User',
    'username' => env('LN_CIRCLECI_USERNAME'),
    'vcs' => env('LN_CIRCLECI_VCS'),
];
