<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Functions
 *
 * @author rodolfoalmeida
 */
class Tokem_Functions {
    
    public function removeAcentos($str){
		$map = array(
		    'á' => 'a',
		    'à' => 'a',
		    'ã' => 'a',
		    'â' => 'a',
		    'é' => 'e',
		    'ê' => 'e',
		    'í' => 'i',
		    'ó' => 'o',
		    'ô' => 'o',
		    'õ' => 'o',
		    'ú' => 'u',
		    'ü' => 'u',
		    'ç' => 'c',
		    'Á' => 'A',
		    'À' => 'A',
		    'Ã' => 'A',
		    'Â' => 'A',
		    'É' => 'E',
		    'Ê' => 'E',
		    'Í' => 'I',
		    'Ó' => 'O',
		    'Ô' => 'O',
		    'Õ' => 'O',
		    'Ú' => 'U',
		    'Ü' => 'U',
		    'Ç' => 'C',
		    '~' => '-',
		    '_' => '-',
		    '`' => '-',
		    '<' => '-',
		    '>' => '-',
		    '?' => '-',
		    '+' => '-',
		    '[' => '-',
		    ']' => '',
		    '{' => '-',
		    '}' => '-',
		    '(' => '-',
		    ')' => '-',
		    '|' => '-',
		    '%' => '-',
		    '$' => '-',
                );
                
		$string =strtr($str, $map);

		return $string;
        
    }
}
