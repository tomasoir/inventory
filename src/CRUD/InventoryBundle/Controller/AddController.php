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
 * Add new vehicles.
 *
 * @package Add
 * @author  Test Te <test@symfony.com>
 * @license Corporation 
 * @link    Here/fff
 */

class AddController extends Controller
{
    /**
    * Show all vehicles 
    *
    * @param request $request
    *
    * @return array
    */
    public function showAction(Request $request)
    {
        
        $inventory = new inventory();
        
        $form = $this->createFormBuilder($inventory)
            ->add('mainimage', FileType::class, array('label' => 'Add image files'))
            ->add('newused', TextType::class)
            ->add('stock', TextType::class)
            ->add('make', TextType::class)
            ->add('model', TextType::class)
            ->add('trim', TextType::class)
            ->add('price', IntegerType::class)
            ->add('save', SubmitType::class, array('label' => 'Submit',))
            ->getForm();
        
        $form->handleRequest($request);
        $addInfo="";
        if ($form->isSubmitted()) {
            $inventory->uploadImage();
            $em = $this->getDoctrine()->getManager();
            $em->persist($inventory);
            $em->flush();
            $addInfo="New vehicle added";
        }
    
        return $this->render(
            'CRUDInventoryBundle:Add:New.html.twig', 
            array(
            'form' => $form->createView(),
            'addInfo' => $addInfo,
            )
        );
    }
}   