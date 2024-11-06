<?php

namespace App\Geo;

readonly class IP
{
    /** @var object{
     *     isp: string,
     *     organization: string
     * } $traits
     */
    public object $traits;
    public function __construct()
    {
        /** @var false|array{
         *     city: array{
         *         names: array{
         *             en: string
         *         }
         *     },
         *     continent: array{
         *         code: string,
         *         names: array{
         *             en: string
         *         }
         *     },
         *     country: array{
         *         iso_code: string,
         *         names: array{
         *              en: string
         *         }
         *     },
         *     traits: array{
         *          organization: string,
         *          isp: string
         *     }
         * } $geo
         */
        $geo = @file_get_contents("https://api.findip.net/" . request()->ip() . "/?token=76e13cdf895e411ca235d5d9e072f05b");
        if (!$geo) return;
        else $geo = json_decode($geo);
        $_data = $geo->traits;
        if (empty($_data->isp)) {
            $_data->isp = '';
        }
        if (empty($_data->organization)) {
            $_data->organization = '';
        }
        $this->traits = $_data;
    }
}
