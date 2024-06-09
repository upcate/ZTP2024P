<?php

/**
 * AdminController.
 */

namespace App\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class AdminController.
 */
#[\Symfony\Component\Routing\Attribute\Route('/admin')]
class AdminController extends AbstractController
{
    /**
     * Show admin panel action.
     *
     * @param Request $request HTTP Request
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
    public function panel() : Response
    {
        return $this->render(
            'admin/panel.html.twig'
        );
    }
}
