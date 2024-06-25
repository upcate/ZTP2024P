<?php

/**
 * 9
 * AdController.
 */

namespace App\Controller;

use App\Entity\Ad;
use App\Form\Type\AdType;
use App\Service\AdServiceInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Contracts\Translation\TranslatorInterface;

/**
 * Class AdController.
 */
#[Route('/ad')]
class AdController extends AbstractController
{
    /**
     * Constructor.
     *
     * @param AdServiceInterface  $adService  Ad Service Interface
     * @param TranslatorInterface $translator Translator
     */
    public function __construct(private readonly AdServiceInterface $adService, private readonly TranslatorInterface $translator)
    {
    }// end __construct()

    /**
     * Index action.
     *
     * @param Request $request HTTP Request
     *
     * @return Response HTTP Response
     */
    #[Route(
        name: 'ad_index',
        methods: 'get'
    )]
    public function index(Request $request): Response
    {
        $filters = $this->getFilters($request);
        $pagination = $this->adService->getPaginatedList(
            $request->query->getInt('page', 1),
            $filters
        );

        if ($this->isGranted('ROLE_ADMIN')) {
            return $this->render('ad/admin.index.html.twig', ['pagination' => $pagination]);
        }

        return $this->render('ad/index.html.twig', ['pagination' => $pagination]);
    }// end index()

    /**
     * Get filters.
     *
     * @param Request $request HTTP Request
     *
     * @return array Array with filters
     */
    public function getFilters(Request $request): array
    {
        return ['adCategory_id' => $request->query->getInt('filters_adCategory_id')];
    }// end getFilters()

    /**
     * Index to accept action.
     *
     * @param Request $request HTTP Request
     *
     * @return Response HTTP Response
     */
    #[Route(
        '/toAccept',
        name: 'accept_index',
        methods: 'get'
    )]
    #[IsGranted('ROLE_ADMIN')]
    public function indexToAccept(Request $request): Response
    {
        $pagination = $this->adService->getPaginatedAcceptList(
            $request->query->getInt('page', 1)
        );

        return $this->render('ad/accept.index.html.twig', ['pagination' => $pagination]);
    }// end indexToAccept()

    /**
     * Accept action.
     *
     * @param Request $request HTTP Request
     * @param Ad      $ad      Ad Entity
     *
     * @return Response HTTP Response
     */
    #[Route(
        '/{id}/accept',
        name: 'ad_accept',
        requirements: ['id' => '[1-9]\d*'],
        methods: 'GET|PUT',
    )]
    #[IsGranted('ROLE_ADMIN')]
    public function acceptAd(Request $request, Ad $ad): Response
    {
        $form = $this->createForm(
            FormType::class,
            $ad,
            [
                'method' => 'PUT',
                'action' => $this->generateUrl('ad_accept', ['id' => $ad->getId()]),
            ]
        );
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->adService->makeVisible($ad);

            $this->addFlash(
                'success',
                $this->translator->trans('message.accepted_successfully')
            );

            return $this->redirectToRoute('accept_index');
        }

        return $this->render(
            '/ad/accept.html.twig',
            [
                'ad' => $ad,
                'form' => $form->createView(),
            ]
        );
    }// end acceptAd()

    /**
     * Show action.
     *
     * @param Ad $ad Ad Entity
     *
     * @return Response HTTP Response
     */
    #[Route(
        '/{id}',
        name: 'ad_show',
        requirements: ['id' => '[1-9]\d*'],
        methods: 'get',
    )]
    public function show(Ad $ad): Response
    {
        if (0 === $ad->getIsVisible()) {
            if (!$this->isGranted('ROLE_ADMIN')) {
                return $this->redirectToRoute('app_login');
            }
        }

        return $this->render(
            'ad/show.html.twig',
            ['ad' => $ad]
        );
    }// end show()

    /**
     * Create action.
     *
     * @param Request $request HTTP Request
     *
     * @return Response HTTP Response
     */
    #[Route(
        '/create',
        name: 'ad_create',
        methods: 'GET|POST'
    )]
    public function create(Request $request): Response
    {
        $ad = new Ad();
        $form = $this->createForm(AdType::class, $ad, ['action' => $this->generateUrl('ad_create')]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            if ($this->isGranted('ROLE_ADMIN')) {
                $this->adService->saveOnCreateAdm($ad);
            } else {
                $this->adService->saveOnCreateUs($ad);
            }

            $this->addFlash(
                'success',
                $this->translator->trans('message.created_successfully')
            );

            return $this->redirectToRoute('ad_index');
        }

        return $this->render(
            'ad/create.html.twig',
            [
                'form' => $form->createView(),
            ]
        );
    }// end create()

    /**
     * Edit action.
     *
     * @param Request $request HTTP Request
     * @param Ad      $ad      Ad Entity
     *
     * @return Response HTTP Response
     */
    #[Route(
        '/{id}/edit',
        name: 'ad_edit',
        requirements: ['id' => '[1-9]\d*'],
        methods: 'GET|PUT'
    )]
    #[IsGranted('ROLE_ADMIN')]
    public function edit(Request $request, Ad $ad): Response
    {
        $form = $this->createForm(
            AdType::class,
            $ad,
            [
                'method' => 'PUT',
                'action' => $this->generateUrl('ad_edit', ['id' => $ad->getId()]),
            ]
        );
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->adService->save($ad);

            $this->addFlash(
                'success',
                $this->translator->trans('message.edited_successfully')
            );

            return $this->redirectToRoute('ad_index');
        }

        return $this->render(
            'ad/edit.html.twig',
            [
                'form' => $form->createView(),
                'ad' => $ad,
            ]
        );
    }// end edit()

    /**
     * Delete action.
     *
     * @param Request $request HTTP Request
     * @param Ad      $ad      Ad Entity
     *
     * @return Response HTTP Response
     */
    #[Route(
        '/{id}/delete',
        name: 'ad_delete',
        requirements: ['id' => '[1-9]\d*'],
        methods: 'GET|DELETE'
    )]
    #[IsGranted('ROLE_ADMIN')]
    public function delete(Request $request, Ad $ad): Response
    {
        $form = $this->createForm(
            FormType::class,
            $ad,
            [
                'method' => 'DELETE',
                'action' => $this->generateUrl('ad_delete', ['id' => $ad->getId()]),
            ]
        );
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->adService->delete($ad);

            $this->addFlash(
                'success',
                $this->translator->trans('message.deleted_successfully')
            );

            return $this->redirectToRoute('ad_index');
        }

        return $this->render(
            'ad/delete.html.twig',
            [
                'form' => $form->createView(),
                'ad' => $ad,
            ]
        );
    }// end delete()

    /**
     * Delete to accept action.
     *
     * @param Request $request HTTP Request
     * @param Ad      $ad      Ad Entity
     *
     * @return Response HTTP Response
     */
    #[Route(
        '/{id}/accept/delete',
        name: 'accept_delete',
        requirements: ['id' => '[1-9]\d*'],
        methods: 'GET|DELETE'
    )]
    #[IsGranted('ROLE_ADMIN')]
    public function deleteToAccept(Request $request, Ad $ad): Response
    {
        $form = $this->createForm(
            FormType::class,
            $ad,
            [
                'method' => 'DELETE',
                'action' => $this->generateUrl('accept_delete', ['id' => $ad->getId()]),
            ]
        );
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->adService->delete($ad);

            $this->addFlash(
                'success',
                $this->translator->trans('message.deleted_successfully')
            );

            return $this->redirectToRoute('accept_index');
        }

        return $this->render(
            'ad/delete.accept.html.twig',
            [
                'form' => $form->createView(),
                'ad' => $ad,
            ]
        );
    }// end deleteToAccept()
}// end class
