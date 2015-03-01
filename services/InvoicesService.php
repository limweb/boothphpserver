<?php

/**
 *  README for sample service
 *
 *  This generated sample service contains functions that illustrate typical service operations.
 *  Use these functions as a starting point for creating your own service implementation. Modify the 
 *  function signatures, references to the database, and implementation according to your needs. 
 *  Delete the functions that you do not use.
 *
 *  Save your changes and return to Flash Builder. In Flash Builder Data/Services View, refresh 
 *  the service. Then drag service operations onto user interface components in Design View. For 
 *  example, drag the getAllItems() operation onto a DataGrid.
 *  
 *  This code is for prototyping only.
 *  
 *  Authenticate the user prior to allowing them to call these methods. You can find more 
 *  information at http://www.adobe.com/go/flex_security
 *
 */
class InvoicesService {

	var $username = "root";
	var $password = "";
	var $server = "127.0.0.1";
	var $port = "3306";
	var $databasename = "pos";
	var $tablename = "invoices";

	var $connection;

	/**
	 * The constructor initializes the connection to database. Everytime a request is 
	 * received by Zend AMF, an instance of the service class is created and then the
	 * requested method is invoked.
	 */
	public function __construct() {
	  	$this->connection = mysqli_connect(
	  							$this->server,  
	  							$this->username,  
	  							$this->password, 
	  							$this->databasename,
	  							$this->port
	  						);

		$this->throwExceptionOnError($this->connection);
	}

	/**
	 * Returns all the rows from the table.
	 *
	 * Add authroization or any logical checks for secure access to your data 
	 *
	 * @return array
	 */
	public function getAllInvoices() {

		$stmt = mysqli_prepare($this->connection, "SELECT * FROM $this->tablename");		
		$this->throwExceptionOnError();
		
		mysqli_stmt_execute($stmt);
		$this->throwExceptionOnError();
		
		$rows = array();
		
		mysqli_stmt_bind_result($stmt, $row->id, $row->created_at, $row->updated_at, $row->deleted_at, $row->created_by, $row->updated_by, $row->actived_at, $row->doc_code, $row->doc_ref, $row->customer_id, $row->doc_id, $row->doc_date, $row->tax, $row->contacts, $row->dlv_by, $row->dlvshipid, $row->crday, $row->total, $row->discountref, $row->discountamf, $row->totalafterdiscount, $row->vat, $row->vatamt, $row->totalamt, $row->ref1, $row->ref2);
		
	    while (mysqli_stmt_fetch($stmt)) {
	      $row->created_at = new DateTime($row->created_at);
	      $row->updated_at = new DateTime($row->updated_at);
	      $row->deleted_at = new DateTime($row->deleted_at);
	      $row->actived_at = new DateTime($row->actived_at);
	      $rows[] = $row;
	      $row = new stdClass();
	      mysqli_stmt_bind_result($stmt, $row->id, $row->created_at, $row->updated_at, $row->deleted_at, $row->created_by, $row->updated_by, $row->actived_at, $row->doc_code, $row->doc_ref, $row->customer_id, $row->doc_id, $row->doc_date, $row->tax, $row->contacts, $row->dlv_by, $row->dlvshipid, $row->crday, $row->total, $row->discountref, $row->discountamf, $row->totalafterdiscount, $row->vat, $row->vatamt, $row->totalamt, $row->ref1, $row->ref2);
	    }
		
		mysqli_stmt_free_result($stmt);
	    mysqli_close($this->connection);
	
	    return $rows;
	}

	/**
	 * Returns the item corresponding to the value specified for the primary key.
	 *
	 * Add authorization or any logical checks for secure access to your data 
	 *
	 * 
	 * @return stdClass
	 */
	public function getInvoicesByID($itemID) {
		
		$stmt = mysqli_prepare($this->connection, "SELECT * FROM $this->tablename where id=?");
		$this->throwExceptionOnError();
		
		mysqli_stmt_bind_param($stmt, 'i', $itemID);		
		$this->throwExceptionOnError();
		
		mysqli_stmt_execute($stmt);
		$this->throwExceptionOnError();
		
		mysqli_stmt_bind_result($stmt, $row->id, $row->created_at, $row->updated_at, $row->deleted_at, $row->created_by, $row->updated_by, $row->actived_at, $row->doc_code, $row->doc_ref, $row->customer_id, $row->doc_id, $row->doc_date, $row->tax, $row->contacts, $row->dlv_by, $row->dlvshipid, $row->crday, $row->total, $row->discountref, $row->discountamf, $row->totalafterdiscount, $row->vat, $row->vatamt, $row->totalamt, $row->ref1, $row->ref2);
		
		if(mysqli_stmt_fetch($stmt)) {
	      $row->created_at = new DateTime($row->created_at);
	      $row->updated_at = new DateTime($row->updated_at);
	      $row->deleted_at = new DateTime($row->deleted_at);
	      $row->actived_at = new DateTime($row->actived_at);
	      return $row;
		} else {
	      return null;
		}
	}

	/**
	 * Returns the item corresponding to the value specified for the primary key.
	 *
	 * Add authorization or any logical checks for secure access to your data 
	 *
	 * 
	 * @return stdClass
	 */
	public function createInvoices($item) {

		$stmt = mysqli_prepare($this->connection, "INSERT INTO $this->tablename (created_at, updated_at, deleted_at, created_by, updated_by, actived_at, doc_code, doc_ref, customer_id, doc_id, doc_date, tax, contacts, dlv_by, dlvshipid, crday, total, discountref, discountamf, totalafterdiscount, vat, vatamt, totalamt, ref1, ref2) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
		$this->throwExceptionOnError();

		mysqli_stmt_bind_param($stmt, 'ssssssssiissssiissdddddss', $item->created_at->toString('YYYY-MM-dd HH:mm:ss'), $item->updated_at->toString('YYYY-MM-dd HH:mm:ss'), $item->deleted_at->toString('YYYY-MM-dd HH:mm:ss'), $item->created_by, $item->updated_by, $item->actived_at->toString('YYYY-MM-dd HH:mm:ss'), $item->doc_code, $item->doc_ref, $item->customer_id, $item->doc_id, $item->doc_date, $item->tax, $item->contacts, $item->dlv_by, $item->dlvshipid, $item->crday, $item->total, $item->discountref, $item->discountamf, $item->totalafterdiscount, $item->vat, $item->vatamt, $item->totalamt, $item->ref1, $item->ref2);
		$this->throwExceptionOnError();

		mysqli_stmt_execute($stmt);		
		$this->throwExceptionOnError();

		$autoid = mysqli_stmt_insert_id($stmt);

		mysqli_stmt_free_result($stmt);		
		mysqli_close($this->connection);

		return $autoid;
	}

	/**
	 * Updates the passed item in the table.
	 *
	 * Add authorization or any logical checks for secure access to your data 
	 *
	 * @param stdClass $item
	 * @return void
	 */
	public function updateInvoices($item) {
	
		$stmt = mysqli_prepare($this->connection, "UPDATE $this->tablename SET created_at=?, updated_at=?, deleted_at=?, created_by=?, updated_by=?, actived_at=?, doc_code=?, doc_ref=?, customer_id=?, doc_id=?, doc_date=?, tax=?, contacts=?, dlv_by=?, dlvshipid=?, crday=?, total=?, discountref=?, discountamf=?, totalafterdiscount=?, vat=?, vatamt=?, totalamt=?, ref1=?, ref2=? WHERE id=?");		
		$this->throwExceptionOnError();
		
		mysqli_stmt_bind_param($stmt, 'ssssssssiissssiissdddddssi', $item->created_at->toString('YYYY-MM-dd HH:mm:ss'), $item->updated_at->toString('YYYY-MM-dd HH:mm:ss'), $item->deleted_at->toString('YYYY-MM-dd HH:mm:ss'), $item->created_by, $item->updated_by, $item->actived_at->toString('YYYY-MM-dd HH:mm:ss'), $item->doc_code, $item->doc_ref, $item->customer_id, $item->doc_id, $item->doc_date, $item->tax, $item->contacts, $item->dlv_by, $item->dlvshipid, $item->crday, $item->total, $item->discountref, $item->discountamf, $item->totalafterdiscount, $item->vat, $item->vatamt, $item->totalamt, $item->ref1, $item->ref2, $item->id);		
		$this->throwExceptionOnError();

		mysqli_stmt_execute($stmt);		
		$this->throwExceptionOnError();
		
		mysqli_stmt_free_result($stmt);		
		mysqli_close($this->connection);
	}

	/**
	 * Deletes the item corresponding to the passed primary key value from 
	 * the table.
	 *
	 * Add authorization or any logical checks for secure access to your data 
	 *
	 * 
	 * @return void
	 */
	public function deleteInvoices($itemID) {
				
		$stmt = mysqli_prepare($this->connection, "DELETE FROM $this->tablename WHERE id = ?");
		$this->throwExceptionOnError();
		
		mysqli_stmt_bind_param($stmt, 'i', $itemID);
		mysqli_stmt_execute($stmt);
		$this->throwExceptionOnError();
		
		mysqli_stmt_free_result($stmt);		
		mysqli_close($this->connection);
	}


	/**
	 * Returns the number of rows in the table.
	 *
	 * Add authorization or any logical checks for secure access to your data 
	 *
	 * 
	 */
	public function count() {
		$stmt = mysqli_prepare($this->connection, "SELECT COUNT(*) AS COUNT FROM $this->tablename");
		$this->throwExceptionOnError();

		mysqli_stmt_execute($stmt);
		$this->throwExceptionOnError();
		
		mysqli_stmt_bind_result($stmt, $rec_count);
		$this->throwExceptionOnError();
		
		mysqli_stmt_fetch($stmt);
		$this->throwExceptionOnError();
		
		mysqli_stmt_free_result($stmt);
		mysqli_close($this->connection);
		
		return $rec_count;
	}


	/**
	 * Returns $numItems rows starting from the $startIndex row from the 
	 * table.
	 *
	 * Add authorization or any logical checks for secure access to your data 
	 *
	 * 
	 * 
	 * @return array
	 */
	public function getInvoices_paged($startIndex, $numItems) {
		
		$stmt = mysqli_prepare($this->connection, "SELECT * FROM $this->tablename LIMIT ?, ?");
		$this->throwExceptionOnError();
		
		mysqli_stmt_bind_param($stmt, 'ii', $startIndex, $numItems);
		mysqli_stmt_execute($stmt);
		$this->throwExceptionOnError();
		
		$rows = array();
		
		mysqli_stmt_bind_result($stmt, $row->id, $row->created_at, $row->updated_at, $row->deleted_at, $row->created_by, $row->updated_by, $row->actived_at, $row->doc_code, $row->doc_ref, $row->customer_id, $row->doc_id, $row->doc_date, $row->tax, $row->contacts, $row->dlv_by, $row->dlvshipid, $row->crday, $row->total, $row->discountref, $row->discountamf, $row->totalafterdiscount, $row->vat, $row->vatamt, $row->totalamt, $row->ref1, $row->ref2);
		
	    while (mysqli_stmt_fetch($stmt)) {
	      $row->created_at = new DateTime($row->created_at);
	      $row->updated_at = new DateTime($row->updated_at);
	      $row->deleted_at = new DateTime($row->deleted_at);
	      $row->actived_at = new DateTime($row->actived_at);
	      $rows[] = $row;
	      $row = new stdClass();
	      mysqli_stmt_bind_result($stmt, $row->id, $row->created_at, $row->updated_at, $row->deleted_at, $row->created_by, $row->updated_by, $row->actived_at, $row->doc_code, $row->doc_ref, $row->customer_id, $row->doc_id, $row->doc_date, $row->tax, $row->contacts, $row->dlv_by, $row->dlvshipid, $row->crday, $row->total, $row->discountref, $row->discountamf, $row->totalafterdiscount, $row->vat, $row->vatamt, $row->totalamt, $row->ref1, $row->ref2);
	    }
		
		mysqli_stmt_free_result($stmt);		
		mysqli_close($this->connection);
		
		return $rows;
	}
	
	
	/**
	 * Utility function to throw an exception if an error occurs 
	 * while running a mysql command.
	 */
	private function throwExceptionOnError($link = null) {
		if($link == null) {
			$link = $this->connection;
		}
		if(mysqli_error($link)) {
			$msg = mysqli_errno($link) . ": " . mysqli_error($link);
			throw new Exception('MySQL Error - '. $msg);
		}		
	}
}

?>
