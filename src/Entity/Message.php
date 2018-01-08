<?php

namespace AppBundle\Entity;
use JMS\Serializer\Annotation\ExclusionPolicy;
use JMS\Serializer\Annotation\Expose;
use JMS\Serializer\Annotation\SerializedName;
use Swagger\Annotations as SWG;

/**
 * @ExclusionPolicy("all")
 * Class Message
 * @package AppBundle\Entity
 * @SWG\Definition(type="object",@SWG\Xml(name="Message"))
 */

class Message
{
    /**
     * @Expose
     * @SerializedName("id")
     * Id of Message
     * @var integer
     * @SWG\Property()
     */
    private $id;
    /**
     * @Expose
     * @SerializedName("text")
     * Text of Message
     * @var string
     * @SWG\Property()
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