<?php

return [
    'difficulty_max' => 8,

    'status_color' => [
        'SB'=>'secondary',
        'WJ'=>'secondary',
        'WR'=>'secondary',
        'AC'=>'success',
        'WA'=>'warning',
        'CE'=>'warning',
        'TLE'=>'warning',
        'OLE'=>'warning',
        'IE'=>'danger',
        'RE'=>'danger',
    ],

    'initial_permission' => env('INITIAL_PERMISSION', 0),

    'help_url' => env('HELP_URL', null),
];
