<?php
namespace Bean\Bundle\LocationBundle\Form\Type;

use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilder;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\OptionsResolver\Options;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\Form\Extension\Core\Type\FormType;

class GoogleMapType extends AbstractType
{

    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add($options['address_name'], $options['type'], array_merge($options['options'], $options['address_options']))
            ->add($options['lat_name'], HiddenType::class, array_merge($options['options'], $options['lat_options']))
            ->add($options['long_name'], HiddenType::class, array_merge($options['options'], $options['long_options']))
            ->add($options['country_name'], HiddenType::class, $options['options'])
            ->add($options['first_division_name'], HiddenType::class, $options['options'])
            ->add($options['second_division_name'], HiddenType::class, $options['options'])
            ->add($options['third_division_name'], HiddenType::class, $options['options'])
            ->add($options['fourth_division_name'], HiddenType::class, $options['options'])
            ->add($options['fifth_division_name'], HiddenType::class, $options['options'])
            ->add($options['street_name'], HiddenType::class, $options['options'])
            ->add($options['number_name'], HiddenType::class, $options['options'])
            ->add($options['place_id_name'], HiddenType::class, $options['options'])
            ->add($options['locality_name'], HiddenType::class, $options['options']);
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'show_map' => true,
            'type' => TextType::class,  // or 'text' the types to render the lat and long fields as
            'options' => array(), // the options for both the fields
            'address_options' => array(
                                'label'=>false,
                                ),
            'lat_options' => array(),   // the options for just the lat field
            'long_options' => array(),    // the options for just the lng field
            'lat_name' => 'latitude',   // the name of the lat field
            'long_name' => 'longitude',   // the name of the long field
            'error_bubbling' => false,
            'map_width' => '100%',     // the width of the map
            'map_height' => '400px',     // the height of the map
            'default_lat' => 51.5,    // the starting position on the map
            'default_long' => -0.1245, // the starting position on the map
            'include_jquery' => false,   // jquery needs to be included above the field (ie not at the bottom of the page)
            'include_gmaps_js' => true,     // is this the best place to include the google maps javascript?
            'country_name' => 'country',
            'first_division_name' => 'firstDivision',
            'second_division_name' => 'secondDivision',
            'third_division_name' => 'thirdDivision',
            'fourth_division_name' => 'fourthDivision',
            'fifth_division_name' => 'fifthDivision',
            'street_name' => 'street',
            'number_name' => 'number',
            'address_name' => 'address',
            'place_id_name' => 'placeId',
            'locality_name' => 'locality'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function buildView(FormView $view, FormInterface $form, array $options)
    {
        $view->vars['show_map'] = $options['show_map'];
        $view->vars['lat_name'] = $options['lat_name'];
        $view->vars['long_name'] = $options['long_name'];
        $view->vars['map_width'] = $options['map_width'];
        $view->vars['map_height'] = $options['map_height'];
        $view->vars['default_lat'] = $options['default_lat'];
        $view->vars['default_long'] = $options['default_long'];
        $view->vars['include_jquery'] = $options['include_jquery'];
        $view->vars['include_gmaps_js'] = $options['include_gmaps_js'];

        $view->vars['country_name'] = $options['country_name'];
        $view->vars['first_division_name'] = $options['first_division_name'];
        $view->vars['second_division_name'] = $options['second_division_name'];
        $view->vars['third_division_name'] = $options['third_division_name'];
        $view->vars['fourth_division_name'] = $options['fourth_division_name'];
        $view->vars['fifth_division_name'] = $options['fifth_division_name'];
        $view->vars['street_name'] = $options['street_name'];
        $view->vars['number_name'] = $options['number_name'];
        $view->vars['address_name'] = $options['address_name'];
        $view->vars['place_id_name'] = $options['place_id_name'];
        $view->vars['locality_name'] = $options['locality_name'];

    }


    public function getParent()
    {
        return FormType::class;
    }

    public function getBlockPrefix()
    {
        return 'bean_google_maps';
    }
}
