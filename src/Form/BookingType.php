<?php

namespace App\Form;

use App\Entity\Booking;
use App\Form\DataTransformer\FrenchToDateTimeTransformer;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class BookingType extends ApplicationType
{
    private $transformer;

    public function __construct(FrenchToDateTimeTransformer $transformer)
    {
        $this->transformer = $transformer;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('startDate', DateType::class, $this->getConfiguration("Date d'arrivée", "Date d'arrivée", [
                "widget" => "single_text"
            ]))
            ->add('endDate', DateType::class, $this->getConfiguration("Date de départ", "Date de départ", [
                "widget" => "single_text"
            ]))
            ->add('comment', TextareaType::class, $this->getConfiguration("Commentaire", "Laissez un commentaire concernant la réservation si vous le souhaitez", [
                "required" => false
            ]))
        ;

        //$builder->get('startDate')->addModelTransformer($this->transformer);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Booking::class,
        ]);
    }
}
