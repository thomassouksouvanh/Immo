<?php

namespace App\Controller;


use App\Entity\Contact;
use App\Entity\Property;
use App\Entity\PropertySearch;
use App\Form\ContactType;
use App\Form\PropertySearchType;
use App\Notification\NotificationContact;
use App\Repository\PropertyRepository;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class PropertyController extends AbstractController
{
    /**
     * @var PropertyRepository
     */
    private $repository;
    /**
     * @var ObjectManager
     */
    private $entityManagerInterface;

    public function __construct(PropertyRepository $repository,EntityManagerInterface $entityManagerInterface)
    {
        $this->repository=$repository;
        $this->entityManagerInterface=$entityManagerInterface;
    }

    /**
     * @Route("/property/index", name="property_index")
     * @param PropertyRepository $property
     * @param PaginatorInterface $paginator
     * @param Request $request
     * @return Response
     */
    public function index(PropertyRepository $property, PaginatorInterface $paginator, Request $request)
    {
        $search = new PropertySearch();
        $form = $this->createForm(PropertySearchType::class,$search);
        $form->handleRequest($request);

        $properties = $paginator->paginate($this->repository->findAllVisibleQuery($search),
            $request->query->getInt('page', 1),6);

        return $this->render('property/index.html.twig',[
            'current_menu' => $property,
            'properties' => $properties,
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/property/show/{slug}-{id}",name="property_show", requirements={"slug"="[a-z0-9\-]*"})
     * @param Property $property
     * @param string $slug
     * @param Request $request
     * @param NotificationContact $notificationContact
     * @return Response
     */
    public function show(Property $property,string $slug, Request $request,NotificationContact $notificationContact)
    {

        if($property->getSlug() !== $slug)
        {
            return $this->redirectToRoute('property_show',
                [
                    'id'   => $property->getId(),
                    'slug' => $property->getSlug()
                ],301);
        }

        $contact = new Contact();
        $contact->setProperty($property);
        $form = $this->createForm(ContactType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid())
        {
            $notificationContact->notify($contact);
            $this->addFlash('success',
                'Votre email a bien été envoyé, nous vous recontacteront dans les plus bref delai');
            return $this->redirectToRoute('property_show',
                [
                    'id'   => $property->getId(),
                    'slug' => $property->getSlug()
                ]);
        }

        return $this->render('property/show.html.twig',
            [
                'property' => $property,
                'current_menu'=>'properties',
                'form' => $form->createView()
            ]);
    }
}
