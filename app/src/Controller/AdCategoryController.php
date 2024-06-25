<?php

/**
 * AdCategory Controller.
 */

namespace App\Controller;

use App\Entity\AdCategory;
use App\Form\Type\AdCategoryType;
use App\Service\AdCategoryServiceInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Contracts\Translation\TranslatorInterface;

/**
 * Class AdCategoryController.
 */
#[Route('/adCategory')]
class AdCategoryController extends AbstractController
{
    /**
     * Constructor.
     *
     * @param AdCategoryServiceInterface $adCategoryService Ad Category service interface
     * @param TranslatorInterface        $translator        Translator interface
     */
    public function __construct(private readonly AdCategoryServiceInterface $adCategoryService, private readonly TranslatorInterface $translator)
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
        name: 'adCategory_index',
        methods: 'get'
    )]
    public function index(Request $request): Response
    {
        $pagination = $this->adCategoryService->getPaginatedList(
            $request->query->getInt('page', 1)
        );
        if ($this->isGranted('ROLE_ADMIN')) {
            return $this->render('adCategory/admin.index.html.twig', ['pagination' => $pagination]);
        }

        return $this->render('adCategory/index.html.twig', ['pagination' => $pagination]);
    }// end index()

    /**
     * Show action.
     *
     * @param AdCategory $adCategory AdCategory Entity
     *
     * @return Response HTTP Response
     */
    #[Route(
        '/{id}',
        name: 'adCategory_show',
        requirements: ['id' => '[1-9]\d*'],
        methods: 'get'
    )]
    #[IsGranted('ROLE_ADMIN')]
    public function show(AdCategory $adCategory): Response
    {
        return $this->render(
            'adCategory/show.html.twig',
            ['adCategory' => $adCategory]
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
        name: 'adCategory_create',
        methods: 'get|post'
    )]
    #[IsGranted('ROLE_ADMIN')]
    public function create(Request $request): Response
    {
        $adCategory = new AdCategory();
        $form = $this->createForm(AdCategoryType::class, $adCategory);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->adCategoryService->save($adCategory);

            $this->addFlash(
                'success',
                $this->translator->trans('message.created_successfully')
            );

            return $this->redirectToRoute('adCategory_index');
        }

        return $this->render(
            'adCategory/create.html.twig',
            ['form' => $form->createView()]
        );
    }// end create()

    /**
     * Edit action.
     *
     * @param Request    $request    HTTP Request
     * @param AdCategory $adCategory AdCategory Entity
     *
     * @return Response HTTP Response
     */
    #[Route(
        '/admin/{id}/edit',
        name: 'adCategory_edit',
        requirements: ['id' => '[1-9]\d*'],
        methods: 'get|put'
    )]
    #[IsGranted('ROLE_ADMIN')]
    public function edit(Request $request, AdCategory $adCategory): Response
    {
        $form = $this->createForm(
            AdCategoryType::class,
            $adCategory,
            [
                'method' => 'PUT',
                'action' => $this->generateUrl('adCategory_edit', ['id' => $adCategory->getId()]),
            ]
        );
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->adCategoryService->save($adCategory);

            $this->addFlash(
                'success',
                $this->translator->trans('message.edited_successfully')
            );

            return $this->redirectToRoute('adCategory_index');
        }

        return $this->render(
            'adCategory/edit.html.twig',
            [
                'form' => $form->createView(),
                'adCategory' => $adCategory,
            ]
        );
    }// end edit()

    /**
     * Delete action.
     *
     * @param Request    $request    HTTP Request
     * @param AdCategory $adCategory AdCategory Entity
     *
     * @return Response HTTP Response
     */
    #[Route(
        '/{id}/delete',
        name: 'adCategory_delete',
        requirements: ['id' => '[1-9]\d*'],
        methods: 'GET|DELETE'
    )]
    #[IsGranted('ROLE_ADMIN')]
    public function delete(Request $request, AdCategory $adCategory): Response
    {
        if (!$this->adCategoryService->canBeDeleted($adCategory)) {
            $this->addFlash(
                'warning',
                $this->translator->trans('message.category_contains_ads')
            );

            return $this->redirectToRoute('adCategory_index');
        }

        $form = $this->createForm(
            FormType::class,
            $adCategory,
            [
                'method' => 'DELETE',
                'action' => $this->generateUrl('adCategory_delete', ['id' => $adCategory->getId()]),
            ]
        );
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->adCategoryService->delete($adCategory);

            $this->addFlash(
                'success',
                $this->translator->trans('message.deleted_successfully')
            );

            return $this->redirectToRoute('adCategory_index');
        }

        return $this->render(
            'adCategory/delete.html.twig',
            [
                'form' => $form->createView(),
                'adCategory' => $adCategory,
            ]
        );
    }// end delete()
}// end class
