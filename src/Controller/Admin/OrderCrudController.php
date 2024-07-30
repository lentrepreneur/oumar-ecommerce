<?php

namespace App\Controller\Admin;

use App\Entity\Order;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Context\AdminContext;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\ArrayField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ChoiceField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IntegerField;
use EasyCorp\Bundle\EasyAdminBundle\Field\MoneyField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class OrderCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Order::class;
    }

    public function configureActions(Actions $actions): Actions
    {
        $edit = Action::new('Modifier')->linkToCrudAction('edit');
        $detail = Action::new('Afficher Details')->linkToCrudAction('show');

        return $actions
            ->remove(Crud::PAGE_INDEX, Action::NEW )
            ->remove(Crud::PAGE_DETAIL, Action::EDIT)
            ->remove(Crud::PAGE_DETAIL, Action::DELETE)
            ->remove(Crud::PAGE_INDEX, Action::EDIT)
            ->remove(Crud::PAGE_INDEX, Action::DELETE)
            ->add(Crud::PAGE_INDEX, $detail)
            ->add(Crud::PAGE_INDEX, $edit)
        ;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud->setDefaultSort(['id' => 'DESC'])
            ->setEntityLabelInSingular('une Commande')
            ->setEntityLabelInPlural('Les Commandes')
            ->setPaginatorPageSize(15)
            ->setPageTitle('index', 'List des Commandes')
            ->setPageTitle('edit', 'Modification d\'une commande')
            ->setPageTitle('detail', 'Detail d\'une Commande');
    }

    public function show(AdminContext $context)
    {
        $order = $context->getEntity()->getInstance();

        return $this->render('admin/order-detail.html.twig', [
            'order' => $order,
        ]);
    }


    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id')->hideOnForm(),
            TextField::new('customer.fullName', 'Client')->hideOnForm(),
            MoneyField::new('amount', 'Total')->setCurrency('XAF')->hideOnForm(),
            DateTimeField::new('createdAt', 'Créée le')->hideOnForm(),
            ArrayField::new('orderDetails', 'Produits achetés')->hideOnIndex()->hideOnForm(),
            IntegerField::new('quantity', 'Quantité totale')->setSortable(true)->hideOnForm(),
            ChoiceField::new('status', 'Etat de la commande')->setTemplatePath('admin/order-state.html.twig')->setChoices([
                'Payée' => 1,
                'En attente de paiement' => 2,
                'En cours de préparation' => 3,
                'Annulée' => 0,
            ])
        ];
    }
}
