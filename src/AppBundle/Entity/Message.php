<?php

namespace AppBundle\Entity;
use JMS\Serializer\Annotation\ExclusionPolicy;
use JMS\Serializer\Annotation\Expose;
use JMS\Serializer\Annotation\SerializedName;

/**
 * @ExclusionPolicy("all")
 * Class Message
 * @package AppBundle\Entity
 */
class Message
{
    private $id;
    /**
     * @Expose
     * @SerializedName("textTwitter")
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



    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getText()
    {
        return $this->text;
    }


}