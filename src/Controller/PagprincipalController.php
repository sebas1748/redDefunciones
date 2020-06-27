<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class PagprincipalController extends AbstractController
{
    /**
     * @Route("/pagprincipal", name="pagprincipal")
     */
    public function index()
    {
        return $this->render('pagprincipal/index.html.twig', [
            'controller_name' => 'Bienvenido a la Pagina Principal',
        ]);
    }
}
