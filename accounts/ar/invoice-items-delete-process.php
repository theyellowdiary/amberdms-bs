<?php
/*
	accounts/ar/invoice-items-delete-process.php

	access: accounts_invoices_write

	Allows a user to delete an item from an invoice
*/

// includes
require("../../include/config.php");
require("../../include/amberphplib/main.php");

// custom includes
require("../../include/accounts/inc_invoices.php");
require("../../include/accounts/inc_invoices_items.php");



if (user_permissions_get('accounts_ar_write'))
{
	/*
		Let the invoices functions do all the work for us
	*/

	$returnpage_error	= "accounts/ar/invoice-items.php";
	$returnpage_success	= "accounts/ar/invoice-items.php";

	invoice_form_items_delete_process("ar", $returnpage_error, $returnpage_success);
}
else
{
	// user does not have perms to view this page/isn't logged on
	error_render_noperms();
	header("Location: ../index.php?page=message.php");
	exit(0);
}


?>
