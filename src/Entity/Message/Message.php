<?php

namespace App\Entity\Message;
use JMS\Serializer\Annotation\ExclusionPolicy;
use JMS\Serializer\Annotation\Expose;
use JMS\Serializer\Annotation\SerializedName;
use Swagger\Annotations as SWG;
use Doctrine\ORM\Mapping as ORM;
use App\Command\Message\GetMessagesCommand;

/**
 * @ExclusionPolicy("all")
 * Class Message
 * @package App\Entity
 * @SWG\Definition(type="object",@SWG\Xml(name="Message"))
 *
 * @ORM\Table(name="message")
 * @ORM\Entity(repositoryClass="App\Repository\Message\MessageRepository")
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
     * @ORM\ManyToOne(targetEntity="App\Entity\Message\MessageRequest", inversedBy="messageRequest")
     * @ORM\JoinColumn(name="id_message", referencedColumnName="id")
     */
    private $messageRequest;

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