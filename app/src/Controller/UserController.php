<?php

/**
 * UserController.
 */

namespace App\Controller;

use App\Entity\User;
use App\Form\Type\UserType;
use App\Service\UserServiceInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\Translation\TranslatorInterface;

/**
 * Class UserController.
 */
#[\Symfony\Component\Routing\Attribute\Route('/admin')]
class UserController extends AbstractController
{
    /**
     * Constructor.
     *
     * @param UserServiceInterface $userService User Interface
     * @param TranslatorInterface  $translator  Translator Interface
     */
    public function __construct(
        /**
         * UserServiceInterface.
         */
        private readonly UserServiceInterface $userService,
        /**
         * TranslatorInterface.
         */
        private readonly TranslatorInterface $translator
    )
    {
    }

    /**
     * Edit action.
     *
     * @param Request $request HTTP Request
     * @param User    $user    User Entity
     *
     * @return Response HTTP Response
     */
    #[\Symfony\Component\Routing\Attribute\Route(
        '/{id}/edit',
        name: 'user_edit',
        requirements: ['id' => '[1-9]\d*'],
        methods: 'GET|PUT',
    )]
    #[IsGranted('ROLE_ADMIN')]
    public function edit(Request $request, User $user): Response
    {
        $form = $this->createForm(UserType::class, $user, [
            'method' => 'PUT',
            'action' => $this->generateUrl('user_edit', ['id' => $user->getId()]),
        ]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->userService->save($user);

            $this->addFlash(
                'success',
                $this->translator->trans('message.changed_successfully')
            );

            return $this->redirectToRoute('admin_panel');
        }

        return $this->render(
            'admin/edit.html.twig',
            [
              'form' => $form->createView(),
              'user' => $user,
            ]
        );
    }
}
