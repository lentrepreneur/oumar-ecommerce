<?php

namespace App\Controller;

use App\Entity\AboutInfo;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class AboutController extends AbstractController
{
    #[Route('/a-propos', name: 'app_about')]
    public function index(EntityManagerInterface $manager): Response
    {
        $aboutInfo = $manager->getRepository(AboutInfo::class)->findOneBy(['id' => 1]);
        return $this->render('about/index.html.twig', [
            'aboutInfo' => $aboutInfo,
        ]);
    }
}
