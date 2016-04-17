<?php

/*
 * This file is Inventory Doctrine Class.
 *
 * (c) Test Te <test@symfony.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace CRUD\InventoryBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * Inventory
 *
 * @ORM\Table(name="inventory")
 * @ORM\Entity(repositoryClass="CRUD\InventoryBundle\Repository\InventoryRepository")
 */
class Inventory
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="newused", type="string", length=4)
     */
    private $newused;

    /**
     * @var string
     *
     * @ORM\Column(name="stock", type="string", length=45)
     */
    private $stock;

    /**
     * @var string
     *
     * @ORM\Column(name="make", type="string", length=45)
     */
    private $make;

    /**
     * @var string
     *
     * @ORM\Column(name="model", type="string", length=45)
     */
    private $model;

    /**
     * @var string
     *
     * @ORM\Column(name="trim", type="string", length=45)
     */
    private $trim;

    /**
     * @var int
     *
     * @ORM\Column(name="price", type="integer")
     */
    private $price;

    

    /**
     * @var string
     *
     * @ORM\Column(name="image2", type="string", length=100)
     */
    private $image2="image2";

    /**
     * @var string
     *
     * @ORM\Column(name="image3", type="string", length=100)
     */
    private $image3="image3";

    /**
     * @Assert\File(maxSize="6000000")
     */
    private $mainimage;


    private $temp;

    private $path;


    /**
     * Sets mainimage.
     *
     * @param UploadedFile $mainimage
     */
    public function setMainimage(UploadedFile $mainimage = null)
    {
        $this->mainimage = $mainimage;
    }

    /**
     * Get mainimage.
     *
     * @return UploadedFile
     */
    public function getMainimage()
    {
        return $this->mainimage;
    }

    /**
     * Get id
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set newused
     *
     * @param string $newused
     *
     * @return Inventory
     */
    public function setNewused($newused)
    {
        $this->newused = $newused;

        return $this;
    }

    /**
     * Get newused
     *
     * @return string
     */
    public function getNewused()
    {
        return $this->newused;
    }

    /**
     * Set stock
     *
     * @param string $stock
     *
     * @return Inventory
     */
    public function setStock($stock)
    {
        $this->stock = $stock;

        return $this;
    }

    /**
     * Get stock
     *
     * @return string
     */
    public function getStock()
    {
        return $this->stock;
    }

    /**
     * Set make
     *
     * @param string $make
     *
     * @return Inventory
     */
    public function setMake($make)
    {
        $this->make = $make;

        return $this;
    }

    /**
     * Get make
     *
     * @return string
     */
    public function getMake()
    {
        return $this->make;
    }

    /**
     * Set model
     *
     * @param string $model
     *
     * @return Inventory
     */
    public function setModel($model)
    {
        $this->model = $model;

        return $this;
    }

    /**
     * Get model
     *
     * @return string
     */
    public function getModel()
    {
        return $this->model;
    }

    /**
     * Set trim
     *
     * @param string $trim
     *
     * @return Inventory
     */
    public function setTrim($trim)
    {
        $this->trim = $trim;

        return $this;
    }

    /**
     * Get trim
     *
     * @return string
     */
    public function getTrim()
    {
        return $this->trim;
    }

    /**
     * Set price
     *
     * @param integer $price
     *
     * @return Inventory
     */
    public function setPrice($price)
    {
        $this->price = $price;

        return $this;
    }

    /**
     * Get price
     *
     * @return int
     */
    public function getPrice()
    {
        return $this->price;
    }

    /**
     * Set image2
     *
     * @param string $image2
     *
     * @return Inventory
     */
    public function setImage2($image2)
    {
        $this->image2 = $image2;

        return $this;
    }

    /**
     * Get image2
     *
     * @return string
     */
    public function getImage2()
    {
        return $this->image2;
    }

    /**
     * Set image3
     *
     * @param string $image3
     *
     * @return Inventory
     */
    public function setImage3($image3)
    {
        $this->image3 = $image3;

        return $this;
    }

    /**
     * Get image3
     *
     * @return string
     */
    public function getImage3()
    {
        return $this->image3;
    }

    /**
     * Show Absolute Path.
     *
     * @return array
     */
    public function getAbsolutePath()
    {
        return null === $this->path
            ? $this->getUploadRootDir()
            : $this->getUploadRootDir().'/'.$this->path;
    }
       
    /**
     * Show Upload root dir.
     *
     * @return array
     */ 
    protected function getUploadRootDir()
    {
        // the absolute directory path where uploaded
        // documents should be saved
        return $_SERVER['DOCUMENT_ROOT'].$this->getUploadDir();
    }

    /**
     * Show Upload Dir.
     *
     * @return array
     */
    public function getUploadDir()
    {
        // get rid of the __DIR__ so it doesn't screw up
        // when displaying uploaded doc/image in the view.
        return '/uploads/images';
    }

    /**
     * Show Upload Dir.
     *
     * @param string $image
     */
    public function uploadImage($image="")
    {
        // the file property can be empty if the field is not required
        if (null === $this->getMainimage()) {
            return;
        }

        if (!empty($image)) {
            $this->setMainimage($image);
        }
        // use the original file name here but you should
        // sanitize it at least to avoid any security issues

        // move takes the target directory and then the
        // target filename to move to
        $this->getMainimage()->move(
            $this->getUploadRootDir(),
            $this->getMainimage()->getClientOriginalName()
        );

        // set the path property to the filename where you've saved the file
        $this->path = $this->getMainimage()->getClientOriginalName();
        
        $this->mainimage =  $this->getMainimage()->getClientOriginalName();
    }

    /**
     * Remove Upload Image.
     *
     * @param string $file
     */
    public function removeUploadImage($file)
    {
        $path = $this->getAbsolutePath();
        if ($path) {
            unlink($path.'/'.$file);
        }
    }
}
