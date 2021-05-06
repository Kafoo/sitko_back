<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Verify
    |--------------------------------------------------------------------------
    |
    */

    'verify_subject' => 'Vérification de votre adresse e-mail',
    'verify_hello' => 'Salut !',
    'verify_line1' => 'Vous pouvez cliquer sur le button ci-dessous pour valider votre e-mail sur Sitko',
    'verify_action' => 'Je vérifie mon adresse e-mail',
    'verify_line2' => 'Si vous n\'avez pas créé de compte sur Sitko, vous pouvez ignorer cet email',
    'verify_salutation' => 'A bientôt',
    'verify_footer' => 'Si vous rencontrez des difficultés avec le bouton ":actionText", vous pouvez copier/coller ce lien dans votre navigateur :',


    /*
    |--------------------------------------------------------------------------
    | Link
    |--------------------------------------------------------------------------
    |
    */

    'link_request' => [
        'subject' => 'Nouvelle demande de lien !',
        'toUser' => '<strong>:requesting</strong> aimerait se connecter à vous.',
        'toPlace' => '<strong>:requesting</strong> aimerait se connecter à "<strong>:requested</strong>"'
    ],
    'link_confirmation' => [
        'subject' => 'Lien confirmé !',
        'toUser' => '<strong>:requested</strong> a accepté votre demande de lien.',
        'toPlace' => '<strong>:requested</strong> a accepté la demande de lien de "<strong>:requesting</strong>"'
    ],
    'goto' => [
        'place' => 'Page du lieu ":place"',
        'user' => 'Profil de :user',
    ]
    

];
