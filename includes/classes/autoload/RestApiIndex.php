<?php

class RestApiIndex extends RestApi {

	public function get(){
		$products_query = tep_db_query("select count(id) as total from products where status = 1");
		$products = tep_db_fetch_array($products_query);

		$customer_query = tep_db_query("select count(id) as total from tab_customer where status = 1");
		$customer = tep_db_fetch_array($customer_query);

		$sum_balance_customer = tep_db_query("
			SELECT
				SUM(balance) as total
			FROM
				invoice
			WHERE
				status = 1
		");
		$total_balance_cus = tep_db_fetch_array($sum_balance_customer);

		$sum_balance_vendor = tep_db_query("
			SELECT
				SUM(remain) as total
			FROM
				purchase_master
			WHERE
				status = 1
		");
		$total_balance_vendor = tep_db_fetch_array($sum_balance_vendor);

		return array( data =>
			array(
				product_total => $products['total'],
				customer_total => $customer['total'],
				customer_balance => $total_balance_cus['total'],
				vendor_balance => $total_balance_vendor['total']
			)
		);
	}
}
