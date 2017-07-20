<?php

namespace Nnv\GalleryBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Nnv\GalleryBundle\Entity\Gallery;
use Nnv\GalleryBundle\Entity\GalleryItem;
// use Symfony\Component\HttpFoundation\FileBag;
// use Symfony\Component\HttpFoundation\File\UploadedFile;
use Nnv\GalleryBundle\Form\Type\GalleryType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;

/**
 * @todo 管理员身份
 * 
 * @Route("/gallery")
 */
class GalleryController extends Controller
{
    /**
     * manage
     * 
     * @Route("/manage/{id}", name="nnv_gallery_manage")
     * @ParamConverter("gallery", class="NnvGalleryBundle:Gallery")
     * @Template()
     */
    public function manageAction(Request $req, Gallery $gallery)
    {   
        $form = $this->createForm(GalleryType::class, $gallery);
        $form->handleRequest($req);
        
        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($gallery);
            $em->flush();
            // return $this->redirect($this->generateUrl('', ['id' => $id]));
        }
        
        return [
            'gallery' => $gallery,
            'form' => $form->createView()
        ];
    }
    
    /**
     * 
     * @Route("/create", name="nnv_gallery_create", defaults={"_format": "json"})
     * @Template()
     */
    public function createAction(Request $req)
    {
        $em = $this->getDoctrine()->getManager();
        $gallery = new Gallery();
        $em->persist($gallery);
        $em->flush();
        
        return $this->json(['ok' => true, 'id' => $gallery->getId()]);
    }
    
    /**
     * @Route("/item-create", name="nnv_gallery_item_create", defaults={"_format": "json"})
     * @Template()
     */
    public function itemCreateAction(Request $req)
    {
        $em = $this->getDoctrine()->getManager();
        $gallery = $em->getRepository('NnvGalleryBundle:Gallery')
                ->find($req->get('gallery'));
        if (!$gallery) {
            return $this->json(['ok' => false, 'err' => 'gallery.inexist']);
        }
        
        $galleryItem = new GalleryItem;
        $metaType = $req->get('meta_type');
        if (!$metaType || !$this->get('nnv_gallery.helper')->checkType($metaType)) {
            $metaType = GalleryItem::META_TYPE_LINK;
        }
        
        // @todo if type is of entity, check existence
        
        $galleryItem->setGallery($gallery)
                ->setMetaType($metaType)
                ->setMetaContent($req->get('meta_content', ''))
                ->setSeq($req->get('seq'));
        $file = $req->files->get('file');
        $rootDir = $this->container->getParameter('kernel.root_dir');
        $fileName = uniqid('nnv_') . '.' . $file->getClientOriginalExtension();
        $file->move("$rootDir/../web/uploads/gallery/", $fileName);
        list($width, $height, $type, $attr) = getimagesize("$rootDir/../web/uploads/gallery/$fileName");
        $galleryItem->setPic($fileName)
                ->setWidth($width)
                ->setHeight($height);
        $em->persist($galleryItem);
        $em->flush();
        
        $picPath = $this->get('assets.packages')->getUrl('uploads/gallery/' . $galleryItem->getPic());
        return $this->json([
            'ok' => true,
            'img' => $picPath,
            'id' => $galleryItem->getId()
        ]);
    }
    
    /**
     * @Route("/item-del", name="nnv_gallery_item_del", defaults={"_format": "json"})
     * @Template()
     */
    public function itemDelAction(Request $req)
    {
        $em = $this->getDoctrine()->getManager();
        $galleryItem = $em->getRepository('NnvGalleryBundle:GalleryItem')
                ->find($req->get('id'));
        $em->remove($galleryItem);
        $em->flush();
        return $this->json(['ok' => true]);
    }
    
    /**
     * 设置图片信息
     * 
     * @Route("/item-update", name="nnv_gallery_item_update", defaults={"_format": "json"})
     * @Template()
     */
    public function itemUpdateAction(Request $req)
    {
        $em = $this->getDoctrine()->getManager();
        $galleryItem = $em->getRepository('NnvGalleryBundle:GalleryItem')
                ->find($req->get('pk'));
        $name = $req->get('name');
        if ($name == 'meta') {
            $val = $req->get('value');
            if (isset($val['content'])) {
                $galleryItem->setMetaContent($val['content']);
            }
            if (isset($val['type']) && $this->get('nnv_gallery.helper')->checkType($val['type'])) {
                $galleryItem->setMetaType($val['type']);
            }
        }
        $em->flush();
        return $this->json([
            'ok' => true,
            'id' => $galleryItem->getId()]
        );
    }
    
    /**
     * 设置图片顺序
     * 
     * @Route("/seq", name="nnv_gallery_seq", defaults={"_format": "json"})
     * @Template()
     */
    public function seqAction(Request $req)
    {
        $em = $this->getDoctrine()->getManager();
        $gallery = $em->getRepository('NnvGalleryBundle:Gallery')
                ->find($req->get('gallery'));
        $seqs = $req->get('seqs');
        $items = $gallery->getItems();
        foreach ($items as $itemId=>$item) {
            $item->setSeq($seqs[$itemId]);
        }
        $em->flush();
        return $this->json([
            'ok' => true,
            'id' => $gallery->getId()]
        );
    }
    
    /**
     * 删除其中所有图片
     * 
     * @Route("/clear/{id}", name="nnv_gallery_clear", defaults={"_format": "json"})
     */
    public function clearAction($id)
    {
        $em = $this->getDoctrine()->getManager();
        $gallery = $em->getRepository('NnvGalleryBundle:Gallery')->find($id);
        if ($gallery) {
            $items = $gallery->getItems();
            foreach ($items as $item) {
                $em->remove($item);
            }
        }
        $em->flush();
        return $this->json([
            'ok' => true,
            'id' => $id
        ]);
    }
}
