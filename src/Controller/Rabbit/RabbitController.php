<?php

namespace App\Controller\Rabbit;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


class RabbitController extends Controller
{

    /**
     * @Route("/rabbit/send-rabbit-message", name="app_rabbit_send_rabbit_message")
     */
    public function sendRabbitMessageAction(){
        $msg = array('user_id' => 1235, 'image_path' => '/');
        //$this->get('old_sound_rabbit_mq.upload_picture_producer')->publish(serialize($msg));
        return new Response(
            '<html><body>send 1 message to Rabbit: '.$msg.'</body></html>'
        );
    }
}
