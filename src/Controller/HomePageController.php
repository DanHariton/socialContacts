<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class HomePageController
 * @Route("/")
 * @package App\Controller
 */
class HomePageController extends AbstractController
{
    /**
     * @Route("/", name="homepage_index")
     * @return Response
     */
    public function index()
    {
        return $this->render('homepage/index.html.twig');
    }
}