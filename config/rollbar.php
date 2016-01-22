<?php

return [
    // required
    'access_token' => env('ROLLBAR_ACCESS_TOKEN', ''),
    // optional - environment name. any string will do.
    'environment' => env('ROLLBAR_ENV', 'development'),
    // optional - path to directory your code is in. used for linking stack traces.
    'root' => app_path(),
];
