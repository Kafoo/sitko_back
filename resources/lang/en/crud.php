<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Fails
    |--------------------------------------------------------------------------
    |
    */

    'fail' => [
      'project' => [
        'creation' => "Project could not be created",
        'update' => "Project could not be updated",
        'deletion' => "Project could not be deleted"
      ],
      'events' => [
        'creation' => "Events creation failed",
        'update' => "Events update failed",
        'deletion' => "Events deletion failed"
      ],
      'image' => [
        'creation' => "Image upload failed",
        'update' => "Image update failed",
        'deletion' => "Image deletion failed"
      ]
    ],

    'success' => [
      'project' => [
        'creation' => "Project has been successfully created",
        'update' => "Project has been successfully updated",
        'deletion' => "Project has been successfully delete"
      ]
    ]
];
