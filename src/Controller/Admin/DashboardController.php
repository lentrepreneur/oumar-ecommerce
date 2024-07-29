<?php

namespace App\Controller\Admin;

use App\Entity\AboutInfo;
use App\Entity\Contact;
use App\Entity\HeaderPromo;
use App\Entity\Order;
use App\Entity\Product;
use App\Entity\SiteInformation;
use App\Entity\SocialMedia;
use App\Entity\TopBarPromo;
use App\Entity\User;
use EasyCorp\Bundle\EasyAdminBundle\Config\Dashboard;
use EasyCorp\Bundle\EasyAdminBundle\Config\MenuItem;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractDashboardController;
use EasyCorp\Bundle\EasyAdminBundle\Router\AdminUrlGenerator;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DashboardController extends AbstractDashboardController
{
    #[Route('/admin', name: 'admin')]
    public function index(): Response
    {
        //return parent::index();

        // Option 1. You can make your dashboard redirect to some common page of your backend
        //
        $adminUrlGenerator = $this->container->get(AdminUrlGenerator::class);
        return $this->redirect($adminUrlGenerator->setController(ProductCrudController::class)->generateUrl());

        // Option 2. You can make your dashboard redirect to different pages depending on the user
        //
        // if ('jane' === $this->getUser()->getUsername()) {
        //     return $this->redirect('...');
        // }

        // Option 3. You can render some custom template to display a proper dashboard with widgets, etc.
        // (tip: it's easier if your template extends from @EasyAdmin/page/content.html.twig)
        //
        // return $this->render('some/path/my-dashboard.html.twig');
    }

    public function configureDashboard(): Dashboard
    {
        return Dashboard::new()
            ->setTitle('Al Amin Design');
    }

    public function configureMenuItems(): iterable
    {
        yield MenuItem::linkToDashboard('Dashboard', 'fa fa-home');

        yield MenuItem::section('Boutique');
        yield MenuItem::linkToCrud('Gestion des Produits', 'fas fa-list', Product::class);
        yield MenuItem::linkToCrud('Gestion des Commandes', 'fas fa-list', Order::class);
        yield MenuItem::linkToCrud('Gestion des utilisateurs', 'fas fa-list', User::class);

        yield MenuItem::section('Promotions');
        yield MenuItem::linkToCrud('Promotion Defilante', 'fas fa-list', TopBarPromo::class)
            ->setController(TopBarPromoCrudController::class);
        yield MenuItem::linkToCrud('Promotion Tete de page', 'fas fa-list', HeaderPromo::class)
            ->setController(HeaderPromoCrudController::class);

        yield MenuItem::section('Contacts');
        yield MenuItem::linkToCrud('Messages', 'fas fa-list', Contact::class)
            ->setController(ContactCrudController::class)->setAction('index');

        yield MenuItem::section('Parametre du site');
        yield MenuItem::linkToCrud('Informations du site', 'fas fa-list', SiteInformation::class)->setController(SiteInformationCrudController::class);
        yield MenuItem::linkToCrud('Reseaux Sociaux', 'fas fa-list', SocialMedia::class)->setController(SocialMediaCrudController::class);
        yield MenuItem::linkToCrud('A propos du site', 'fas fa-list', AboutInfo::class)->setController(AboutInfoCrudController::class);
    }
}
