<?php

/**
 * MainController.
 */

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class MainController.
 */
class MainController extends AbstractController
{
    /**
     * Index action.
     *
     * @return Response HTTP Response
     */
    #[\Symfony\Component\Routing\Attribute\Route(
        '/main',
        name: 'main_index',
        methods: 'get'
    )
    ]
    public function index(): Response
    {
        return $this->render(
            'main/index.html.twig'
        );
    }// end index()
}// end class
