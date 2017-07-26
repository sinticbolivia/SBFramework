<?php
class SB_BarcodeEAN13
{
	public static function Build($country, $company, $product)
	{
		$fl 	= strlen(trim($country) . trim($company));
		$code 	= trim($country) . trim($company) . str_pad($product, 12 - $fl, '0');
		$weightflag = true;
		$sum = 0;
		// Weight for a digit in the checksum is 3, 1, 3.. starting from the last digit.
		// loop backwards to make the loop length-agnostic. The same basic functionality
		// will work for codes of different lengths.
		for ($i = strlen($code) - 1; $i >= 0; $i--)
		{
			$sum += (int)$code[$i] * ($weightflag ? 3 : 1);
			$weightflag = !$weightflag;
		}
		$code .= (10 - ($sum % 10)) % 10;
		return $code;
	}
}
//die(SB_BarcodeEAN13::Build('123', '45678', '9041'));