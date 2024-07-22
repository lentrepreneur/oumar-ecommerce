<?php

namespace App\Twig;

use App\Entity\SiteInformation;
use App\Entity\SocialMedia;
use App\Entity\TopBarPromo;
use Doctrine\ORM\EntityManagerInterface;
use Twig\Extension\AbstractExtension;
use Twig\Extension\GlobalsInterface;

class Globals extends AbstractExtension implements GlobalsInterface
{

    public function __construct(private EntityManagerInterface $entityManager)
    {

    }

    public function getGlobals(): array
    {
        $informations = $this->entityManager->getRepository(SiteInformation::class)->findOneBy(['id' => 1]);
        $socialMedia = $this->entityManager->getRepository(SocialMedia::class)->findAll();
        $topPromo = $this->entityManager->getRepository(TopBarPromo::class)->findAll();

        return [
            'informations' => $informations,
            'socialMedia' => $socialMedia,
            'topPromo' => $topPromo
        ];
    }
}