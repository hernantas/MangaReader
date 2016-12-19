<?php
    namespace Hook;

    class Message
    {
        public function view($data)
        {
            $msg = page()->message;
            return ($msg->count()>0 ? ['msg'=>$msg->getAsArray()] : []);
        }
    }

?>
