<?php

namespace App\Form;

use App\Entity\Comment;
use App\Entity\Blog;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\CallbackTransformer;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CommentType extends AbstractType
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('content', TextareaType::class, [
                'label' => 'Your message :',
                'attr' => [
                    'class' => 'form-control textarea-container',
                ],
            ])
            ->add('blog', HiddenType::class)
            ->add('send', SubmitType::class, [
                'label' => 'Send',
                'attr' => [
                    'class' => 'btn btn-success mt-2',
                ],
            ]);

        $builder->get('blog')->addModelTransformer(new CallbackTransformer(
            function ($blog) {
                return $blog instanceof Blog ? $blog->getId() : null;
            },
            function ($blogId) {
                if ($blogId) {
                    return $this->entityManager->getRepository(Blog::class)->find($blogId);
                }

                return null;
            }
        ));
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Comment::class,
            'csrf_token_id' => 'comment-add'
        ]);
    }
}
