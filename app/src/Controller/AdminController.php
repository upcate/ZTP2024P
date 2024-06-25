<?php

/**
 * AdminController.
 */

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Http\Attribute\IsGranted;

/**
 * Class AdminController.
 */
#[\Symfony\Component\Routing\Attribute\Route('/admin')]
class AdminController extends AbstractController
{
    /**
     * Show admin panel action.
     *
     * @return Response HTTP Response
     */
    #[\Symfony\Component\Routing\Attribute\Route(
        '/panel',
        name: 'admin_panel',
        methods: 'get',
    )
    ]
    #[IsGranted('ROLE_ADMIN')]
    public function panel(): Response
    {
        return $this->render(
            'admin/panel.html.twig'
        );
    }// end panel()
}// end class
