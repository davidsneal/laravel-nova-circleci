<?php

return [
    'parameters' => [
        'branch' => env('LN_CIRCLECI_BRANCH'),
    ],
    'project' => env('LN_CIRCLECI_PROJECT'),
    'token' => env('LN_CIRCLECI_TOKEN'),
    'username' => env('LN_CIRCLECI_USERNAME'),
    'vcs' => env('LN_CIRCLECI_VCS'),
];
