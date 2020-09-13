<?php
namespace AppBundle\Constants;

class Mode {
    const COMP_RES00                = 0x00000001;
    const COMP_RES01                = 0x00000002;
    const COMP_RES02                = 0x00000004;
    const COMP_RES03                = 0x00000008;

    const COMP_RES04                = 0x00000010;
    const COMP_RES05                = 0x00000020;
    const COMP_RES06                = 0x00000040;
    const COMP_RES07                = 0x00000080;

    const COMP_RES08                = 0x00000100;
    const COMP_RES09                = 0x00000200;
    const COMP_RES0A                = 0x00000400;
    const COMP_RES0B                = 0x00000800;

    const COMP_RES0C                = 0x00001000;
    const COMP_RES0D                = 0x00002000;
    const COMP_RES0E                = 0x00004000;
    const COMP_RES0F                = 0x00008000;

    const COMP_RES10                = 0x00010000;
    const COMP_RES11                = 0x00020000;
    const COMP_RES12                = 0x00040000;
    const COMP_RES13                = 0x00080000;

    const COMP_RES14                = 0x00100000;
    const COMP_RES15                = 0x00200000;
    const COMP_RES16                = 0x00400000;
    const COMP_RES17                = 0x00800000;

    const COMP_RES18                = 0x01000000;
    const COMP_RES19                = 0x02000000;
    const COMP_RES1A                = 0x04000000;
    const COMP_RES1B                = 0x08000000;

    const COMP_RES1C                = 0x10000000;
    const COMP_RES1D                = 0x20000000;
    const COMP_RES1E                = 0x40000000;
    const COMP_DEBUG                = 0x80000000;

    
    /**
     * isEnabled
     *
     * @param integer $mode
     * @param integer $flag0
     * @param integer $flagn
     */
    public static function isEnabled($mode) {
        for ($i = 1; $i < func_num_args(); $i++)
        {
            if ($mode & func_get_arg($i))
            {
                return (true);
            }
        }

        return (false);
    }


    /**
     * allEnabled
     *
     * @param integer $mode
     * @param integer $flag0
     * @param integer $flagn
     */
    public static function allEnabled($mode) {
        $all = true;

        for ($i = 1; $i < func_num_args(); $i++)
        {
            if (! ($mode & func_get_arg($i)))
            {
                $all = false;
            }
        }

        return ($all);
    }
}