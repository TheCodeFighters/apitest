<?php

namespace App\Entity;
use JMS\Serializer\Annotation\ExclusionPolicy;
use JMS\Serializer\Annotation\Expose;
use JMS\Serializer\Annotation\SerializedName;
use Swagger\Annotations as SWG;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ExclusionPolicy("all")
 * Class Message
 * @package App\Entity
 * @SWG\Definition(type="object",@SWG\Xml(name="Message"))
 *
 * @ORM\Table(name="message")
 * @ORM\Entity(repositoryClass="App\Repository\MessageRepository")
 */



class Message
{
    /**
     * @Expose
     * @SerializedName("id")
     * Id of Message
     * @var integer
     * @SWG\Property()
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;
    /**
     * @Expose
     * @SerializedName("text")
     * Text of Message
     * @var string
     * @SWG\Property()
     *
     * @ORM\Column(length=500,nullable=false)
     */
    private $text;

    /**
     * Message constructor.
     * @param $id
     * @param $text
     */
    public function __construct(int $id,string $text)
    {
        $this->id = $id;
        $this->text = $text;
    }

}