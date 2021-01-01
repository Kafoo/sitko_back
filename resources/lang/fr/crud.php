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
        'creation' => "Erreur lors de la création du projet",
        'update' => "Erreur lors de la modification du projet",
        'deletion' => "Erreur lors de la suppression du projet"
      ],
      'place' => [
        'creation' => "Erreur lors de la création du lieu",
        'update' => "Erreur lors de la modification du lieu",
        'deletion' => "Erreur lors de la suppression du lieu"
      ],
      'events' => [
        'creation' => "Les événements n'ont pas pu être créés",
        'update' => "Les événements n'ont pas pu être modifiés",
        'deletion' => "Les événements n'ont pas pu être supprimés"
      ],
      'image' => [
        'creation' => "L'image n'a pas pu être téléchargée",
        'update' => "L'image n'a pas pu être modifiée",
        'deletion' => "L'image n'a pas pu être supprimée"
      ]
    ],

    'success' => [
      'project' => [
        'creation' => "Projet créé avec succès",
        'update' => "Projet modifié avec succès",
        'deletion' => "Projet supprimé avec succès"
      ],
      'place' => [
        'creation' => "Lieu créé avec succès",
        'update' => "Lieu modifié avec succès",
        'deletion' => "Lieu supprimé avec succès"
      ]
    ]

];
