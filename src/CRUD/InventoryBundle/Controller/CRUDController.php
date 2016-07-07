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

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use CRUD\InventoryBundle\Entity\Inventory;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;


/**
 * Show, Add, Edit, Delete vehicle.
 *
 * @package CRUD
 * @author  Test Te <test@symfony.com>
 * @license Corporation 
 * @link    Here/fff
 */
class CRUDController extends Controller
{
    /**
     * boolean $deleteInfo
     *
     */
    private $deleteInfo;

    /**
     * Data record lifetime in seconds (1 day by default)
     */
    const LIFETIME = 86400;

    /**
     * string $allInventoryKey  add in memcache
     *
     */
    protected $allInventoryKey = "website_allInventory";

    /**
    * Show all vehicles 
    *
    * @return string
    */
    public function showAction()
    {
        $inventory = $this->get('memcache.default')->get($this->allInventoryKey);
        if (empty($inventory)) {
            $inventory = $this->getDoctrine()
                ->getRepository('CRUDInventoryBundle:Inventory')
                ->findall();
            $this->get('memcache.default')->set($this->allInventoryKey, $inventory, 0, self::LIFETIME);
        }
                       
        return $this->render(
            'CRUDInventoryBundle:CRUD:Show.html.twig',
            array('inventory' => $inventory, 'deleteInfo' => $this->deleteInfo)
        );
    }

    /**
     * Add all vehicles 
     *
     * @param 
     *
     * @return string
     */
    public function addAction()
    {
        $inventory = new Inventory;
        $form = $this->createFormBuilder($inventory)
            ->add('mainImage', FileType::class, array('label' => 'Add image files'))
            ->add('newUsed', ChoiceType::class, array(
                'choices' => array('New' => 'New', 'Used' => 'Used'),
                'placeholder' => '-- Select Please --',
            )
            )
            ->add('stock', TextType::class, array('attr' => array('maxlength' => 10)))
            ->add('make', ChoiceType::class, array(
                'choices' => $this->tableVallueToArray('Make'),
                'placeholder' => '-- Select Please --',
            )
            )
            ->add('model', ChoiceType::class, array(
                'choices' => $this->tableVallueToArray('Model'),
                'placeholder' => '-- Select Please --',
            )
            )
            ->add('trim', ChoiceType::class, array(
                'choices' => $this->tableVallueToArray('Trim'),
                'placeholder' => '-- Select Please --',
            )
            )
            ->add('price', MoneyType::class, array('divisor' => 100, ))
            ->add('save', SubmitType::class, array('label' => 'Submit', ))
            ->getForm();

        $request = Request::createFromGlobals();
        $form->handleRequest($request);
        $addInfo = '';
        $error = '';

        if ($form->isSubmitted() || $form->isValid()) {
            
            $error = $inventory->uploadImage($request->files->get('mainImage'));
            if (!$error) {
                $entityManager = $this->getDoctrine()->getManager();
                $entityManager->persist($inventory);
                $entityManager->flush();
                $addInfo = true;
                $this->get('memcache.default')->delete($this->allInventoryKey);
            }    
        }

        return $this->render(
            'CRUDInventoryBundle:CRUD:Add.html.twig',
            array(
                'form' => $form->createView(),
                'addInfo' => $addInfo,
                'error' => $error,
            )
        );
    }

/**
    * Edit vehicle 
    *
    * @param Integer $id
    *
    * @return String
    */
    public function editAction($id)
    {
        $entityManager = $this->getDoctrine()->getManager();
        $vehicle = $entityManager->getRepository('CRUDInventoryBundle:Inventory')->find($id);
        if (!$vehicle) {
            throw $this->createNotFoundException(
                'No Vehicle found for id ' . $id
            );
        }

        $imageUrl = $vehicle->getUploadDir() . '/' . $vehicle->getMainImage();

        $form = $this->createFormBuilder($vehicle)
            ->add('newUsed', ChoiceType::class, array(
                'choices' => array('New' => 'New', 'Used' => 'Used'),
                'placeholder' => '-- Select Please --',
            )
            )
            ->add('stock', TextType::class)
            ->add('make', ChoiceType::class, array(
                'choices' => $this->tableVallueToArray('Make'),
                'placeholder' => '-- Select Please --',
            )
            )
            ->add('model', ChoiceType::class, array(
                'choices' => $this->tableVallueToArray('Model'),
                'placeholder' => '-- Select Please --',
            )
            )
            ->add('trim', ChoiceType::class, array(
                'choices' => $this->tableVallueToArray('Trim'),
                'placeholder' => '-- Select Please --',
            )
            )
            ->add('price', MoneyType::class, array('divisor' => 100, ))
            ->add('save', SubmitType::class, array('label' => 'Edit', ))
            ->getForm();

        $request = Request::createFromGlobals();
        $form->handleRequest($request);
        $editInfo = '';
        $error = '';

        if ($form->isSubmitted() || $form->isValid()) {
            if ($request->files->get('mainImage')) {
                $vehicle->removeUploadImage($vehicle->getMainImage());
                $error = $vehicle->uploadImage($request->files->get('mainImage'));
                $imageUrl = $vehicle->getUploadDir() . '/' . $vehicle->getMainImage();
            }
            
            if (!$error) {
                $entityManager->persist($vehicle);
                $entityManager->flush();
                $editInfo = true;
                $this->get('memcache.default')->delete($this->allInventoryKey);
            }    
        }

        return $this->render(
            'CRUDInventoryBundle:CRUD:Edit.html.twig', 
            array(
                'form' => $form->createView(),
                'editInfo' => $editInfo,
                'imageUrl' => $imageUrl,
                'error' => $error,
            )
        );
    }

    /**
    * Delete vehicle
    * 
    * @param integer $id vehicle
    *
    * @return boolean
    */
    public function deleteAction($id) 
    {
        $entityManager = $this->getDoctrine()->getManager();
        $vehicle = $entityManager->getRepository('CRUDInventoryBundle:Inventory')->find($id);
               
        if (!$vehicle) {
           $this->deleteInfo = ''; 
           return $this->redirect($this->generateUrl('crud_inventory_homepage'));
        }
        
        $entityManager->remove($vehicle);
        $entityManager->flush();
        $vehicle->removeUploadImage($vehicle->getMainImage());
        $this->deleteInfo = true; 
        $this->get('memcache.default')->delete($this->allInventoryKey);

        return $this->redirect($this->generateUrl('crud_inventory_homepage'));
    }


    private function tableVallueToArray($table) 
    {
        $resultArray = $this->get('memcache.default')->get("website_allVehicles_by_{$table}");
        if (empty($resultArray)) {
            $inventory = $this->getDoctrine()
                ->getRepository('CRUDInventoryBundle:Inventory')
                ->findall();
            $makeRepository = $this->getDoctrine()->getManager()->getRepository('CRUDInventoryBundle:'.$table);
            $results = $makeRepository->createQueryBuilder($table)
                ->groupBy($table . '.name')
                ->orderBy($table . '.name', 'ASC')
                ->getQuery()
                ->getResult();
            $resultArray = array();
            foreach($results as $make){
                 $resultArray[$make->getName()] = $make->getName(); 
            }
            $this->get('memcache.default')->set("website_allVehicles_by_{$table}", $resultArray, 0, self::LIFETIME);
        } 

        return $resultArray;
    }

}
