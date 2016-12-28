<?php
namespace Bean\Bundle\LocationBundle\Admin;

use Bean\Bundle\LocationBundle\Model\Geolocation;
use Bean\Bundle\LocationBundle\Form\Type\GoogleMapType;

use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\CoreBundle\Form\Type\BooleanType;
use Sonata\CoreBundle\Model\Metadata;
use Sonata\MediaBundle\Form\Type\MediaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class GeolocationAdmin extends AbstractAdmin
{

    public function toString($object)
    {
        return $object->getName();
    }

    private $stringService;

    public function setStringService($service)
    {
        $this->stringService = $service;
    }

    /**
     * {@inheritdoc}
     */
    protected function configureDatagridFilters(DatagridMapper $datagridMapper)
    {
        $options = array(
            'choices' => array(),
        );

        $datagridMapper
            ->add('name')
            ->add('address');


    }

    protected function configureListFields(ListMapper $listMapper)
    {
        $listMapper
            ->addIdentifier('name', 'text');
    }

    /**
     * @param Construction $object
     * @return Metadata
     */
    function getObjectMetadata($object)
    {
        return new Metadata($object->getName());
    }

    protected function configureFormFields(FormMapper $formMapper)
    {
        $formMapper
            ->with('form.group_general')
            /**
             * array(
             * 'admin_code' => 'sonata.media.admin.media',
             * 'link_parameters' => array(
             * 'context' => 'images',
             * 'provider' => 'sonata.media.provider.image'
             * )
             * )
             */
            ->add('name', TextType::class, ['required' => false])
//			->add('country',HiddenType::class,['required' => false, 'attr' => array(
//        'class'=>'country-hidden-data'
//    )])
//            ->add('address', HiddenType::class)
            ->add('geolocation', GoogleMapType::class)
            //->add('geoLat', TextType::class)
            //->add('geoLong', TextType::class)
            ->end();
    }

    /**
     * @param Geolocation $object
     */
    public function prePersist($object)
    {
        if (empty($object->getName())) {
            $object->setName($object->getAddress());
        }
    }

}