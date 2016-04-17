<?php

/*
 * This file is part of the Symfony package.
 *
 * (c) Test Te <test@symfony.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace CRUD\InventoryBundle\Controller;

use CRUD\InventoryBundle\Entity\Inventory;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\FileType;

/**
 * Edit vehicle.
 *
 * @package Edit
 * @author  Test Te <test@symfony.com>
 * @license Corporation 
 * @link    Here/fff
 */
class EditController extends Controller
{
    /**
    * Edit vehicle 
    *
    * @param request $request
    *
    * @return array
    */
    public function editAction(Request $request)
    {
        $id=$request->query->get('id');
        $em = $this->getDoctrine()->getManager();
        $vehicle = $em->getRepository('CRUDInventoryBundle:Inventory')->find($id);
        if (!$vehicle) {
            throw $this->createNotFoundException(
                'No Vehicle found for id ' . $id
            );
        }

   
        $image_url=$vehicle->getUploadDir().'/'.$vehicle->getMainimage();

        $form = $this->createFormBuilder($vehicle)
            ->add('newused', TextType::class)
            ->add('stock', TextType::class)
            ->add('make', TextType::class)
            ->add('model', TextType::class)
            ->add('trim', TextType::class)
            ->add('price', IntegerType::class)
            ->add('save', SubmitType::class, array('label' => 'Edit',))
            ->getForm();
     
        $form->handleRequest($request);
        $editInfo="";
        if ($form->isSubmitted()) {
            if ($request->files->get('mainimage')) {
                $vehicle->removeUploadImage($vehicle->getMainimage());
                $vehicle->uploadImage($request->files->get('mainimage'));
                $image_url=$vehicle->getUploadDir().'/'.$vehicle->getMainimage();
            }

            $em = $this->getDoctrine()->getManager();
            $em->persist($vehicle);
            $em->flush();
            $editInfo="vehicle updated";
        }

        return $this->render(
            'CRUDInventoryBundle:Edit:Edit.html.twig', 
            array(
                'form' => $form->createView(),
                'editInfo' => $editInfo,
                'image_url' => $image_url,
            )
        );
    }
}