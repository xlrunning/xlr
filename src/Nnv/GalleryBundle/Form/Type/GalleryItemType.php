<?php

namespace Nnv\GalleryBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Nnv\GalleryBundle\Entity\GalleryItem;

class GalleryItemType extends AbstractType
{
    
    // 上传路径
    private $assetDir;
    
    public function __construct($assetDir)
    {
        $this->assetDir  = $assetDir;
    }
    
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Nnv\GalleryBundle\Entity\GalleryItem'
        ));
    }
    
    public function buildView(FormView $view, FormInterface $form, array $options)
    {
        parent::buildView($view, $form, $options);
        if ($form->getData() instanceof GalleryItem) {
            $obj = $form->getData();
            $view->vars['pic'] = $obj->getPic();
            $view->vars['id']  = $obj->getId();
            $view->vars['meta'] = ['content'=>$obj->getMetaContent(), 'type' => $obj->getMetaType()];
            // meta type
        }
    }
    
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
                //->add('content') // 默认文件名将作为content
                ->add('assetFile', 'file', ['mapped' => false])
                ->add('seq', 'hidden')
        ;
        // $builder->setAttribute('asset', $options['asset']);
        $builder->addEventListener(
            FormEvents::SUBMIT,
            [$this, 'onSubmit']
        );
    }
    
    public function onSubmit(FormEvent $event)
    {
        $galleryItem = $event->getData();
        $file = $event->getForm()->get('assetFile')->getData(); // UploadedFile
        $fileName = uniqid('nnv_') . '.' . $file->getClientOriginalExtension();
        
        $origFileName = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
        $file->move($this->assetDir, $fileName);
        $galleryItem->setItem($fileName);
    }
    
    public function getBlockPrefix()
    {
        return 'nnv_form_gallery_item';
    }
}