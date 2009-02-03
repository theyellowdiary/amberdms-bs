<?php
/*
	vendors/add.php
	
	access: vendors_write

	Form to add a new vendor to the database.
*/


class page_output
{
	var $obj_form;	// page form


	function check_permissions()
	{
		return user_permissions_get('vendors_write');
	}

	function check_requirements()
	{
		// nothing todo
		return 1;
	}


	function execute()
	{
		/*
			Define form structure
		*/
		$this->obj_form = New form_input;
		$this->obj_form->formname = "vendor_add";
		$this->obj_form->language = $_SESSION["user"]["lang"];

		$this->obj_form->action = "vendors/edit-process.php";
		$this->obj_form->method = "post";
		

		// general
		$structure = NULL;
		$structure["fieldname"] 	= "code_vendor";
		$structure["type"]		= "input";
		$this->obj_form->add_input($structure);
		
		$structure = NULL;
		$structure["fieldname"] 	= "name_vendor";
		$structure["type"]		= "input";
		$structure["options"]["req"]	= "yes";
		$this->obj_form->add_input($structure);
		
		$structure = NULL;
		$structure["fieldname"] = "name_contact";
		$structure["type"]	= "input";
		$this->obj_form->add_input($structure);

		$structure = NULL;
		$structure["fieldname"] = "name_contact";
		$structure["type"]	= "input";
		$this->obj_form->add_input($structure);

		$structure = NULL;
		$structure["fieldname"] = "contact_email";
		$structure["type"]	= "input";
		$this->obj_form->add_input($structure);

		$structure = NULL;
		$structure["fieldname"] = "contact_phone";
		$structure["type"]	= "input";
		$this->obj_form->add_input($structure);

		$structure = NULL;
		$structure["fieldname"] = "contact_fax";
		$structure["type"]	= "input";
		$this->obj_form->add_input($structure);

		$structure = NULL;
		$structure["fieldname"] 	= "date_start";
		$structure["type"]		= "date";
		$structure["defaultvalue"]	= date("Y-m-d");
		$structure["options"]["req"]	= "yes";
		$this->obj_form->add_input($structure);

		$structure = NULL;
		$structure["fieldname"] = "date_end";
		$structure["type"]	= "date";
		$this->obj_form->add_input($structure);

		$this->obj_form->subforms["vendor_view"]		= array("code_vendor", "name_vendor", "name_contact", "contact_phone", "contact_fax", "contact_email", "date_start", "date_end");



		// taxes
		$structure = NULL;
		$structure["fieldname"] = "tax_number";
		$structure["type"]	= "input";
		$this->obj_form->add_input($structure);

		$structure = NULL;
		$structure = form_helper_prepare_dropdownfromdb("tax_default", "SELECT id, name_tax as label FROM account_taxes");
		$this->obj_form->add_input($structure);

		$this->obj_form->subforms["vendor_taxes"] = array("tax_number", "tax_default");



		// list all the taxes so the user can enable or disable the taxes
		$sql_tax_obj		= New sql_query;
		$sql_tax_obj->string	= "SELECT id, name_tax, description FROM account_taxes ORDER BY name_tax";
		$sql_tax_obj->execute();

		if ($sql_tax_obj->num_rows())
		{
			// user note
			$structure = NULL;
			$structure["fieldname"] 		= "tax_message";
			$structure["type"]			= "message";
			$structure["defaultvalue"]		= "<p>Select all the taxes below which apply to this vendor. Any taxes not selected, will not be added to invoices for this customer.</p>";
			$this->obj_form->add_input($structure);
				
			$this->obj_form->subforms["vendor_taxes"][] = "tax_message";


			// run through all the taxes
			$sql_tax_obj->fetch_array();

			foreach ($sql_tax_obj->data as $data_tax)
			{
				// define tax checkbox
				$structure = NULL;
				$structure["fieldname"] 		= "tax_". $data_tax["id"];
				$structure["type"]			= "checkbox";
				$structure["options"]["label"]		= $data_tax["name_tax"] ." -- ". $data_tax["description"];
				$structure["options"]["no_fieldname"]	= "enable";

				// add to form
				$this->obj_form->add_input($structure);
				$this->obj_form->subforms["vendor_taxes"][] = "tax_". $data_tax["id"];
			}
		}




		// billing address
		$structure = NULL;
		$structure["fieldname"] = "address1_street";
		$structure["type"]	= "textarea";
		$this->obj_form->add_input($structure);
		
		$structure = NULL;
		$structure["fieldname"] = "address1_city";
		$structure["type"]	= "input";
		$this->obj_form->add_input($structure);
		
		$structure = NULL;
		$structure["fieldname"] = "address1_state";
		$structure["type"]	= "input";
		$this->obj_form->add_input($structure);
		
		$structure = NULL;
		$structure["fieldname"] = "address1_country";
		$structure["type"]	= "input";
		$this->obj_form->add_input($structure);

		$structure = NULL;
		$structure["fieldname"] = "address1_zipcode";
		$structure["type"]	= "input";
		$this->obj_form->add_input($structure);
		
		$this->obj_form->subforms["address_billing"]	= array("address1_street", "address1_city", "address1_state", "address1_country", "address1_zipcode");



		// shipping address
		$structure = NULL;
		$structure["fieldname"] = "address2_street";
		$structure["type"]	= "textarea";
		$this->obj_form->add_input($structure);
		
		$structure = NULL;
		$structure["fieldname"] = "address2_city";
		$structure["type"]	= "input";
		$this->obj_form->add_input($structure);
		
		$structure = NULL;
		$structure["fieldname"] = "address2_state";
		$structure["type"]	= "input";
		$this->obj_form->add_input($structure);
		
		$structure = NULL;
		$structure["fieldname"] = "address2_country";
		$structure["type"]	= "input";
		$this->obj_form->add_input($structure);

		$structure = NULL;
		$structure["fieldname"] = "address2_zipcode";
		$structure["type"]	= "input";
		$this->obj_form->add_input($structure);
	
		$this->obj_form->subforms["address_shipping"]	= array("address2_street", "address2_city", "address2_state", "address2_country", "address2_zipcode");


		// submit button
		$structure = NULL;
		$structure["fieldname"] 	= "submit";
		$structure["type"]		= "submit";
		$structure["defaultvalue"]	= "Create Vendor";
		$this->obj_form->add_input($structure);
		

		// define subforms
		$this->obj_form->subforms["submit"]		= array("submit");
	
		// load any data returned due to errors
		$this->obj_form->load_data_error();
	}
		

	
	function render_html()
	{
		// Title + Summary
		print "<h3>ADD VENDOR RECORD</h3><br>";
		print "<p>This page allows you to add a new vendor.</p>";
		
		// display the form
		$this->obj_form->render_form();
	}

} // end of class page_output


?>
