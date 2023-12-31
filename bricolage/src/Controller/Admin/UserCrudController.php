<?php

namespace App\Controller\Admin;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\QueryBuilder;
use EasyCorp\Bundle\EasyAdminBundle\Collection\FieldCollection;
use EasyCorp\Bundle\EasyAdminBundle\Collection\FilterCollection;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Dto\EntityDto;
use EasyCorp\Bundle\EasyAdminBundle\Dto\SearchDto;
use EasyCorp\Bundle\EasyAdminBundle\Field\ChoiceField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IntegerField;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserCrudController extends AbstractCrudController
{
    public function __construct(
        private UserPasswordHasherInterface $passwordHasher
    ) {}

    public static function getEntityFqcn(): string
    {
        return User::class;
    }

    // public function createIndexQueryBuilder(SearchDto $searchDto, EntityDto $entityDto, FieldCollection $fields, FilterCollection $filters): QueryBuilder
    // {
    //     $userId = $this->getUser()->getId();

    //     return parent::createIndexQueryBuilder($searchDto, $entityDto, $fields, $filters)
    //         ->andWhere('entity.id != :userId')
    //         ->setParameter('userId', $userId);
    // }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setEntityLabelInSingular('Utilisateur')
            ->setEntityLabelInPlural('Utilisateurs');
    }

    public function configureFields(string $pageName): iterable
    {
        yield TextField::new('username');
        yield ChoiceField::new('roles')
            ->allowMultipleChoices()
            ->renderAsBadges([
                'ROLE_ADMIN' => 'success',
                'ROLE_HANDYMAN' => 'warning',
                // 'ROLE_CUSTOMER' => 'danger'
            ])
            ->setChoices([
                'Administrator' => 'ROLE_ADMIN',
                'Handyman' => 'ROLE_HANDYMAN',
                // 'Customer' => 'ROLE_CUSTOMER',
            ]);
        yield TextField::new('lastName');
        yield TextField::new('firstName');
        yield TextField::new('email');
        // yield TextField::new('password')->onlyOnForms()
        //     ->setFormType(PasswordType::class);
        yield TextField::new('phoneNumber');
        yield TextField::new('numStreet');
        yield TextField::new('city');
        yield IntegerField::new('zipCode');
        

    }

    public function persistEntity(EntityManagerInterface $entityManager, $entityInstance): void
    {
        /** @var User $user */
        $user = $entityInstance;

        // Vérifiez si l'entité a une méthode getId avant de l'appeler
        if (method_exists($user, 'getId')) {
            $id = $user->getId();
            // Utilisez $id comme nécessaire
        }

        $plainPassword = $user->getPassword();
        $hashedPassword = $this->passwordHasher->hashPassword($user, $plainPassword);

        $user->setPassword($hashedPassword);

        parent::persistEntity($entityManager, $user);
    }
}
