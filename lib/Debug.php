<?php

namespace SirAymane\ecommerce\lib;


class Debug {
    /**
     * Function that configures variables related to error management
     */
    static function iniset(): void {
        ini_set('display_errors', 1);
        ini_set('display_startup_errors', 1);   
        ini_set('error_reporting', E_ALL);        
    }

    /**
     * Display each element of array concatenated with an 
     * @param array data to be shown
     */
    static function display(array $data): void {
        foreach ($data as $value) {
            echo $value . PHP_EOL;
        }
    }

    /**
     * Function that encapsulates print_r adding EOL at the beggining
     * and at the end of $data
     * @param array data to be shown
     */
    static function printr(array $data): void {
        echo PHP_EOL;
        print_r($data);
        echo PHP_EOL;
    }

    /**
     * Function that encapsulates var_dump adding EOL at the beggining
     * and at the end of $data
     * @param array data to be shown
     */
    static function vardump(array $data): void {
        echo PHP_EOL;
        var_dump($data);
        echo PHP_EOL;
    }

     /**
     * Function that prints a message adding EOL at the beggining
     * and at the end of $data
     * @param String message to be shown
     */
    static function message(String $msg): void{
        echo PHP_EOL;
        print_r($msg);
        echo PHP_EOL;
    }

    /**
     * Compares $asis parameter with $tobe parameter.
     * It they are equals retunr OK, if not return KO
     * @param Object $asis value
     * @param Object $tobe value
     * @return String OK or KO
     */
    static function assert(mixed $asis, mixed $tobe): string {
        if ($asis===$tobe) {
            return 'OK';
        } else {
            return 'KO';
        }
    }

}
