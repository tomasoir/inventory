<?php
namespace CRUD\InventoryBundle\Form\Type;


use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\Validator\Constraints\Collection;

use Symfony\Component\Validator\Constraints\Regex;

use Symfony\Component\Form\Extension\Core\Type\TextType;


class StockType extends AbstractType
{
    


    public function getDefaultOptions(array $options)
    {
        $notBlankMsg = array('message' => 'Dieses Feld darf nicht leer sein.');

        $collectionConstraint = new Collection(array(            
            'make'         => new Regex(array(
                'pattern'   => '/^[0-9 -\+#\(\)\/]$/',
                'match'     => true,
                'message'   => 'Telefon ist nicht gültig.'
            ))
        ));        

        return array(
            'validation_constraint' => $collectionConstraint,
            'show_legend'           => false,
            'render_fieldset'       => false
        );
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'choices' => array(
                'm' => 'Male',
                'f' => 'Female',
            )
        ));
    }


    public function getName()
    {
        return 'stock';
    }

    public function getParent()
    {
        return 'text';
    }
}