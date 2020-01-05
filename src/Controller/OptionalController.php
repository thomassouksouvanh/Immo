<?php

namespace App\Controller;

use App\Entity\Optional;
use App\Form\OptionalType;
use App\Repository\OptionalRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/optional")
 */
class OptionalController extends AbstractController
{
    /**
     * @Route("/", name="optional_index", methods={"GET"})
     * @param OptionalRepository $optionalRepository
     * @return Response
     */
    public function index(OptionalRepository $optionalRepository): Response
    {
        return $this->render('optional/index.html.twig', [
            'optionals' => $optionalRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="optional_new", methods={"GET","POST"})
     * @param Request $request
     * @return Response
     */
    public function new(Request $request): Response
    {
        $optional = new Optional();
        $form = $this->createForm(OptionalType::class, $optional);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($optional);
            $entityManager->flush();

            return $this->redirectToRoute('optional_index');
        }

        return $this->render('optional/new.html.twig', [
            'optional' => $optional,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="optional_show", methods={"GET"})
     * @param Optional $optional
     * @return Response
     */
    public function show(Optional $optional): Response
    {
        return $this->render('optional/show.html.twig', [
            'optional' => $optional,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="optional_edit", methods={"GET","POST"})
     * @param Request $request
     * @param Optional $optional
     * @return Response
     */
    public function edit(Request $request, Optional $optional): Response
    {
        $form = $this->createForm(OptionalType::class, $optional);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('optional_index');
        }

        return $this->render('optional/edit.html.twig', [
            'optional' => $optional,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="optional_delete", methods={"DELETE"})
     * @param Request $request
     * @param Optional $optional
     * @return Response
     */
    public function delete(Request $request, Optional $optional): Response
    {
        if ($this->isCsrfTokenValid('delete'.$optional->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($optional);
            $entityManager->flush();
        }

        return $this->redirectToRoute('optional_index');
    }
}
