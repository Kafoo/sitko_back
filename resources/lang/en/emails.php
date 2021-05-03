<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Verify
    |--------------------------------------------------------------------------
    |
    */

    'verify_subject' => 'Verify Email Address',
    'verify_hello' => 'Hello!',
    'verify_line1' => 'Please click the button below to verify your email address.',
    'verify_action' => 'Verify Email Address',
    'verify_line2' => 'If you did not create an account, no further action is required.',
    'verify_salutation' => 'Regards',
    'verify_footer' => 'If you’re having trouble clicking the ":actionText" button, copy and paste the URL into your web browser:',

    /*
    |--------------------------------------------------------------------------
    | Link
    |--------------------------------------------------------------------------
    |
    */

    'link_request' => [
        'subject' => 'New link request !',
        'toUser' => '<strong>:requesting</strong> would like to connect.',
        'toPlace' => '<strong>:requesting</strong> would like to connect to "<strong>:requested</strong>"'
    ],
    'link_confirmation' => [
        'subject' => 'Link confirmed !',
        'toUser' => '<strong>:requested</strong> confirmed your link request.',
        'toPlace' => '<strong>:requested</strong> confirmed the link request from "<strong>:requesting</strong>"'
    ],
    'goto' => [
        'place' => '":place" page',
        'user' => ':user profile',
    ]
];
