<?php

namespace App\Controller;

use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route("/api")]
class ApiController extends AbstractController
{
    #[Route("/login/connect")]
    public function getFirstInfo(UserRepository $repository):Response{

        $user = $this->getUser();

        $infos = $repository->findByExampleField($user);


        return $this->json($infos,200,[],["groups"=>"api"]);
    }
}
