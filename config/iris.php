<?php

return [
    'api_key' => env('IRIS_API_KEY'),
    'is_production' => filter_var(env('IRIS_IS_PRODUCTION', false), FILTER_VALIDATE_BOOLEAN),
];
