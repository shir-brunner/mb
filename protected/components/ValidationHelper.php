<?php

class ValidationHelper
{
    public static function validateId($id)
    {
        $IDnum = strval($id);

        if(! ctype_digit($IDnum))
            return false;
        if((strlen($IDnum)>9) || (strlen($IDnum)<5))
            return false;

        while(strlen($IDnum<9))
        {
            $IDnum = '0' . $IDnum;
        }

        $mone = 0;
        for($i=0; $i<9; $i++)
        {
            $char = mb_substr($IDnum, $i, 1);
            $incNum = intval($char);
            $incNum*=($i%2)+1;
            if($incNum > 9)
                $incNum-=9;
            $mone+= $incNum;
        }

        if($mone%10==0)
            return true;
        else
            return false;
    }

    public static function validateEmail($email)
    {
        return filter_var($email, FILTER_VALIDATE_EMAIL);
    }
}