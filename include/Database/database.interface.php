<?php
interface SB_IDatabase
{
	function Query($query);
	function Open();
	function Close();
}