app.config([
	'$stateProvider',
	'$urlRouterProvider',
	function($stateProvider, $urlRouterProvider) {
		$stateProvider.
			state('/', {
				url: '/',
				templateUrl: 'js/ng/app/index/partials/index.html',
				controller: 'index_ctrl'
			})
			.state('/journal_entry', {
				url: '/journal_entry',
				templateUrl: 'js/ng/app/journal/views/index.html',
				controller: 'journal_ctrl'
			})
			.state('/ledger_report', {
				url: '/ledger_report',
				templateUrl: 'js/ng/app/ledger_report/views/index.html',
				controller: 'ledger_report_ctrl'
			})
			.state('/account_type', {
				url: '/account_type',
				templateUrl: 'js/ng/app/account_type/views/index.html',
				controller: 'account_type_ctrl'
			})
			// customer route
			.state('/customer_list', {
				url: '/customer_list',
				templateUrl: 'js/ng/app/customer_list/views/index.html',
				controller: 'customer_list_ctrl'
			})
			.state('/customer_type', {
				url: '/customer_type',
				templateUrl: 'js/ng/app/customer_type/views/index.html',
				controller: 'customer_type_ctrl'
			})
			.state('/create_invoice', {
				url: '/create_invoice',
				templateUrl: 'js/ng/app/create_invoice/views/index.html',
				controller: 'create_invoice_ctrl'
			})
			.state('/received_payment', {
				url: '/received_payment',
				templateUrl: 'js/ng/app/received_payment/views/index.html',
				controller: 'received_payment_ctrl'
			})
			.state('/service', {
				url: '/service',
				templateUrl: 'js/ng/app/service/views/index.html',
				controller: 'service_ctrl'
			})
			// user route
			.state('/user', {
				url: '/user',
				templateUrl: 'js/ng/app/user/views/index.html',
				controller: 'user_ctrl'
			})
			// staff route
			.state('/staff_list', {
				url: '/staff_list',
				templateUrl: 'js/ng/app/staff_list/views/index.html',
				controller: 'staff_list_ctrl'
			})
			// customer_birthday
			.state('/customer_birthday', {
				url: '/customer_birthday',
				templateUrl: 'js/ng/app/customer_birthday/views/index.html',
				controller: 'customer_birthday_ctrl'
			})
			// doctor route
			.state('/doctor_type', {
				url: '/doctor_type',
				templateUrl: 'js/ng/app/doctor_type/views/index.html',
				controller: 'doctor_type_ctrl'
			})
			.state('/doctor_list', {
				url: '/doctor_list',
				templateUrl: 'js/ng/app/doctor_list/views/index.html',
				controller: 'doctor_list_ctrl'
			})
			.state('/doctor_expense', {
				url: '/doctor_expense',
				templateUrl: 'js/ng/app/doctor_expense/views/index.html',
				controller: 'doctor_expense_ctrl'
			})
			// product route
			.state('/product_type', {
				url: '/product_type',
				templateUrl: 'js/ng/app/products_type/views/index.html',
				controller: 'products_type_ctrl'
			})
			.state('/product_list', {
				url: '/product_list',
				templateUrl: 'js/ng/app/products_list/views/index.html',
				controller: 'products_list_ctrl'
			})
			.state('/stock_out', {
				url: '/stock_out',
				templateUrl: 'js/ng/app/stock_out/views/index.html',
				controller: 'stock_out_ctrl'
			})
			// vendor route
			.state('/vendor_type', {
				url: '/vendor_type',
				templateUrl: 'js/ng/app/vendor_type/views/index.html',
				controller: 'vendor_type_ctrl'
			})
			.state('/vendor_list', {
				url: '/vendor_list',
				templateUrl: 'js/ng/app/vendor_list/views/index.html',
				controller: 'vendor_list_ctrl'
			})
			.state('/vendor_balance', {
				url: '/vendor_balance',
				templateUrl: 'js/ng/app/report_vendor_balance/views/index.html',
				controller: 'report_vendor_balance_ctrl'
			})
			// purchase route
			.state('/purchase', {
				url: '/purchase',
				templateUrl: 'js/ng/app/purchase/views/index.html',
				controller: 'purchase_ctrl'
			})
			.state('/pay_bill', {
				url: '/pay_bill',
				templateUrl: 'js/ng/app/pay_bill/views/index.html',
				controller: 'pay_bill_ctrl'
			})
			// report purchase route
			.state('/report_purchase', {
				url: '/report_purchase',
				templateUrl: 'js/ng/app/report_purchase/views/index.html',
				controller: 'report_purchase_ctrl'
			})
			// report_purchase_summary route
			.state('/report_purchase_summary', {
				url: '/report_purchase_summary',
				templateUrl: 'js/ng/app/report_purchase_summary/views/index.html',
				controller: 'report_purchase_summary_ctrl'
			})
			.state('/account_payable', {
				url: '/account_payable',
				templateUrl: 'js/ng/app/report_pay_bill/views/index.html',
				controller: 'report_pay_bill_ctrl'
			})
			.state('/account_payable_summary', {
				url: '/account_payable_summary',
				templateUrl: 'js/ng/app/report_payment_summary/views/index.html',
				controller: 'report_pay_bill_summary_ctrl'
			})
			// report invoice rote
			.state('/report_invoice', {
				url: '/report_invoice',
				templateUrl: 'js/ng/app/report_invoice/views/index.html',
				controller: 'report_invoice_ctrl'
			})
			// staff payroll rote
			.state('/staff_payroll', {
				url: '/staff_payroll',
				templateUrl: 'js/ng/app/pay_roll/views/index.html',
				controller: 'pay_roll_ctrl'
			})
			// report_staff rote
			.state('/staff_report', {
				url: '/staff_report',
				templateUrl: 'js/ng/app/report_staff/views/index.html',
				controller: 'report_staff_ctrl'
			})
			// report_receive_payment rote
			.state('/account_receivable', {
				url: '/account_receivable',
				templateUrl: 'js/ng/app/report_receive_payment/views/index.html',
				controller: 'report_receive_payment_ctrl'
			})
			// report_receive_payment rote
			.state('/account_receivable_summary', {
				url: '/account_receivable_summary',
				templateUrl: 'js/ng/app/report_account_receivable_summary/views/index.html',
				controller: 'report_account_receivable_summary_ctrl'
			})
			// report stock rote
			.state('/stock_report', {
				url: '/stock_report',
				templateUrl: 'js/ng/app/report_stock/views/index.html',
				controller: 'report_stock_ctrl'
			})
			// report_sale summary rote
			.state('/report_sale_summary', {
				url: '/report_sale_summary',
				templateUrl: 'js/ng/app/report_sale_summary/views/index.html',
				controller: 'report_sale_summary_ctrl'
			})
			// report sale service rote
			.state('/report_sale', {
				url: '/report_sale',
				templateUrl: 'js/ng/app/report_sale/views/index.html',
				controller: 'report_sale_ctrl'
			})
			// setting company profile rote
			.state('/setting', {
				url: '/setting',
				templateUrl: 'js/ng/app/setting/views/index.html',
				controller: 'setting_ctrl'
			})
			// report customer rote
			.state('/customer_report', {
				url: '/customer_report',
				templateUrl: 'js/ng/app/report_customer/views/index.html',
				controller: 'report_customer_ctrl'
			})
			// report doctor rote
			.state('/doctor_report', {
				url: '/doctor_report',
				templateUrl: 'js/ng/app/report_doctor/views/index.html',
				controller: 'report_doctor_ctrl'
			})
			// appointment route
			.state('/appointment', {
				url: '/appointment',
				templateUrl: 'js/ng/app/appointment/views/index.html',
				controller: 'appointment_ctrl'
			})
			// appointment route
			.state('/appointment_report', {
				url: '/appointment_report',
				templateUrl: 'js/ng/app/appointment_report/views/index.html',
				controller: 'appointment_report_ctrl'
			})
			// report payroll route
			.state('/payroll_report', {
				url: '/payroll_report',
				templateUrl: 'js/ng/app/report_payroll/views/index.html',
				controller: 'report_payroll_ctrl'
			})
			// chart account route
			.state('/balance_sheet', {
				url: '/balance_sheet',
				templateUrl: 'js/ng/app/report_balance_sheet/views/index.html',
				controller: 'report_balance_sheet_ctrl'
			})
			// chart account route
			.state('/chart_account', {
				url: '/chart_account',
				templateUrl: 'js/ng/app/chart_account/views/index.html',
				controller: 'chart_account_ctrl'
			})
			.state('/journal_report', {
				url: '/journal_report',
				templateUrl: 'js/ng/app/report_journal/views/index.html',
				controller: 'journal_report_ctrl'
			})
			.state('/cash_flow', {
				url: '/cash_flow',
				templateUrl: 'js/ng/app/report_cash_flow/views/index.html',
				controller: 'cash_flow_report_ctrl'
			})
			.state('/income_statement', {
				url: '/income_statement',
				templateUrl: 'js/ng/app/report_income_statement/views/index.html',
				controller: 'income_statement_report_ctrl'
			})
			.state('/customer_balance', {
				url: '/customer_balance',
				templateUrl: 'js/ng/app/report_customer_balance/views/index.html',
				controller: 'report_customer_balance_ctrl'
			})
			.state('/daily_case_report', {
				url: '/daily_case_report',
				templateUrl: 'js/ng/app/report_daily_case/views/index.html',
				controller: 'report_daily_case_ctrl'
			})
		;
		$urlRouterProvider.otherwise('/');
	}
]);