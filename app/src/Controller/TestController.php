<?php

/**
 * TestController.
 */

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class TestController.
 */
#[\Symfony\Component\Routing\Attribute\Route('/admin')]
class TestController extends AbstractController
{
    /**
     * Index action.
     *
     * @param Request $request HTTP Request
     *
     * @return Response HTTP Response
     */
    #[\Symfony\Component\Routing\Attribute\Route(
        '/test',
        name: 'admin_test'
    )
    ]
    public function index() : Response
    {
        return $this->render(
            'test/index.html.twig'
        );
    }
}
