<?php
    namespace Hook;

    class Message
    {
        public function view($data)
        {
            $msg = page()->load->library('Message');
            return ($msg->count()>0 ? ['msg'=>$msg->getAsArray()] : []);
        }
    }

?>
