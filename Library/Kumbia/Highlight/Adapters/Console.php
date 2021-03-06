<?php

//"#FE 98 30 "
//"#F27E1E"
//"#00 99 FF "


class ConsoleHighlight {

	/**
	 * Prepara un string antes de ser resaltado
	 *
	 * @param  string $token
	 * @return string
	 */
	public function prepareStringBefore($token){
		$token = str_replace('\\', '\\\\', $token);
		$token = str_replace("\t", "  ", $token);
		return $token;
	}

	/**
	 * Devuelve un token con un coloreado para consola
	 *
	 * @param	string $token
	 * @param	int $type
	 * @return	string
	 */
	public function getToken($token, $type){
		//86400
		switch($type){
			case Highlight::T_RESERVED_WORD:
				return "\\033[38;5;208m".$token.'\\033[0m';
				break;
			case T_VARIABLE:
				return '\\033[38;5;15m'.$token.'\\033[0m';
				break;
			case T_LNUMBER:
				return '\\033[38;5;33m'.$token.'\\033[0m';
				break;
			case T_CONSTANT_ENCAPSED_STRING:
				return '\\033[0;32m'.$token.'\\033[0m';
				break;
			case T_COMMENT:
				return '\\033[38;5;5m'.$token.'\\033[0m';
				break;
			case Highlight::T_OTHER:
				return $token;
				break;
			default:
				return $token;
		}
	}

	/**
	 * Obtiene un número de línea coloreado
	 *
	 * @param	string $number
	 * @return	string
	 */
	public function getLineNumber($number){
		return '\\033[47m\\033[2;30m'.$number.'\\e[0m';
	}

	/**
	 * Prepara una cadena coloreada para su salida a consola
	 *
	 * @param	string $highString
	 * @return	string
	 */
	public function prepareStringAfter($highString){
		$highString = str_replace('$', '\$', $highString);
		$highString = str_replace('"', '\\"', $highString);
		return $highString;
	}

}