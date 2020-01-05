<?php

namespace App\Controller;

use App\Repository\PropertyRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    /**
     * @Route("/", name="home_index")
     * @param $repository
     * @return Response
     */
    public function index(PropertyRepository $repository):Response
    {
        $properties = $repository->findLatest();
        return $this->render('Pages/home.html.twig',['properties' => $properties]);
    }
}