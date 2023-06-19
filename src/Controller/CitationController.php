<?php

namespace App\Controller;

use App\service\KaamelottCitations;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CitationController extends AbstractController
{
    #[Route('/index', name: 'app_citation')]
    public function index(KaamelottCitations $kaamelottCitations): Response
    {

        $citation = $kaamelottCitations->fetchKaamelottInfo();


        return $this->render('citation/index.html.twig', [
            "citation"=>$citation
        ]);
    }
}
