=================================================================
= Xpayment														=
= Modular Gateway and Modular Modules for Payment Transactions	=
= Sponsored by Chronolabs Co-op									=
=================================================================

Xpayment is a module that allows you to write customised payment gateways, as well as having modular hooking stratum in the 
module with a second plugin section for modules and responses to invoices.

Complete with Gateway plugins you can easily customise this module for your payment gateway in a few files. There is many 
benefits to using xpayment as the standard for intergrating payment into your modules. It has a modular plugin system for 
gateway invoice responses so your module will know when an items has been paid for.

This is the new standard for gateway and payment solutions with XOOPS.

We have a translation project underway this archive from time to time will be patched to include more 
languages - currently comes with the 1.31 in other languages. - I ask that all that have translated the module for 
other languages please make the new constants marked at the base of the language file marked for version 1.33.

There are no known bugs and the module has been declare stable in its SDLC - in this version; 1.34 this is the final version 
that will be released which is compatible with XOOPS 2.4 Series, later versions will have the module class wrapper so they 
will not run on XOOPS 2.4.

Features Include:

    Invoice discount coupons
    Control Panel Toolbar
    Control Panel Invoice Filters
    Centralised Language Constant's
    Automated Tax based on IP Location - IPDB
    Fraud Testing on Transactions
    Invoice ID Protection
    .htaccess SEO
    Fee Compensation
    Security Deposit
    Secure JSON Payment Button
    Email Permissions
    Gateway Permissions
    Diverse Modular Plugin
    Plugin Gateway systems
    Modular Plugin and Action hook
    Easy Form Post from any module
    Itemised invoicing
    Multicurrency
    Tax Itemisation
    Recursive Billing with Cron



Payment Gateways Included:

    Paypal
    Zombaio
    CCBill
    2Checkout.com

Downloads & Demo:
	
	Download: http://bin.chronolabs.coop/xoops2.5_xpayment_1.38.zip (6.13Mbs)
	Sourceforge: http://sourceforge.net/projects/chronolabs/files/XOOPS/Modules/xpayment/xoops2.5_xpayment_1.38.zip/download (6.13Mbs)
	Demo: http://xoops.demo.chronolabs.coop
	
Module Plugins:

	The modular plugin now has more than 3 functions the following example is included with the module if you where writting one 
	use this for the basis of it in the /modules/xpayment/plugin/ folder this is example.php, it is only currently sending the mail
	which is why you include a call to the xpayment plugin which contain the mailing routines.
	
	This is one of the main changes in this version, the gateway system has only changed for further language support, but are
	functionally the same.
	
	Code Example of a Plugin:
	
		<?php 
		// for /modules/xpayment/plugin/example.php  
		// This will only send the xpayment 1.28 email notifications. When the plugin 
		// 'example' is specified in the shopping cart form.  
		
		    function PaidExampleHook($invoice) {  
		        include_once $GLOBALS['xoops']->path('/modules/xpayment/plugin/xpayment.php');          
		        return PaidXpaymentHook($invoice);  
		    }  
		      
		    function UnpaidExampleHook($invoice) {  
		        include_once $GLOBALS['xoops']->path('/modules/xpayment/plugin/xpayment.php');  
		        return UnpaidXpaymentHook($invoice);          
		    }  
		      
		    function CancelExampleHook($invoice) {  
		        include_once $GLOBALS['xoops']->path('/modules/xpayment/plugin/xpayment.php');  
		        return CancelXpaymentHook($invoice);  
		    }  
		
		    function NonePaidExampleHook($invoice) {  
		        include_once $GLOBALS['xoops']->path('/modules/xpayment/plugin/xpayment.php');          
		        return NonePaidXpaymentHook($invoice);  
		    }  
		      
		    function NoneUnpaidExampleHook($invoice) {  
		        include_once $GLOBALS['xoops']->path('/modules/xpayment/plugin/xpayment.php');  
		        return NoneUnpaidXpaymentHook($invoice);          
		    }  
		      
		    function NoneCancelExampleHook($invoice) {  
		        include_once $GLOBALS['xoops']->path('/modules/xpayment/plugin/xpayment.php');  
		        return NoneCancelXpaymentHook($invoice);  
		    }  
		
		    function PendingPaidExampleHook($invoice) {  
		        include_once $GLOBALS['xoops']->path('/modules/xpayment/plugin/xpayment.php');          
		        return PendingPaidXpaymentHook($invoice);  
		    }  
		      
		    function PendingUnpaidExampleHook($invoice) {  
		        include_once $GLOBALS['xoops']->path('/modules/xpayment/plugin/xpayment.php');  
		        return PendingUnpaidXpaymentHook($invoice);          
		    }  
		      
		    function PendingCancelExampleHook($invoice) {  
		        include_once $GLOBALS['xoops']->path('/modules/xpayment/plugin/xpayment.php');  
		        return PendingCancelXpaymentHook($invoice);  
		    }  
		
		    function NoticePaidExampleHook($invoice) {  
		        include_once $GLOBALS['xoops']->path('/modules/xpayment/plugin/xpayment.php');          
		        return NoticePaidXpaymentHook($invoice);  
		    }  
		      
		    function NoticeUnpaidExampleHook($invoice) {  
		        include_once $GLOBALS['xoops']->path('/modules/xpayment/plugin/xpayment.php');  
		        return NoticeUnpaidXpaymentHook($invoice);          
		    }  
		      
		    function NoticeCancelExampleHook($invoice) {  
		        include_once $GLOBALS['xoops']->path('/modules/xpayment/plugin/xpayment.php');  
		        return NoticeCancelXpaymentHook($invoice);  
		    }  
		
		    function CollectPaidExampleHook($invoice) {  
		        include_once $GLOBALS['xoops']->path('/modules/xpayment/plugin/xpayment.php');          
		        return CollectPaidXpaymentHook($invoice);  
		    }  
		      
		    function CollectUnpaidExampleHook($invoice) {  
		        include_once $GLOBALS['xoops']->path('/modules/xpayment/plugin/xpayment.php');  
		        return CollectUnpaidXpaymentHook($invoice);          
		    }  
		      
		    function CollectCancelExampleHook($invoice) {  
		        include_once $GLOBALS['xoops']->path('/modules/xpayment/plugin/xpayment.php');  
		        return CollectCancelXpaymentHook($invoice);  
		    }  
		
		    function FraudPaidExampleHook($invoice) {  
		        include_once $GLOBALS['xoops']->path('/modules/xpayment/plugin/xpayment.php');          
		        return FraudPaidXpaymentHook($invoice);  
		    }  
		      
		    function FraudUnpaidExampleHook($invoice) {  
		        include_once $GLOBALS['xoops']->path('/modules/xpayment/plugin/xpayment.php');  
		        return FraudUnpaidXpaymentHook($invoice);          
		    }  
		      
		    function FraudCancelExampleHook($invoice) {  
		        include_once $GLOBALS['xoops']->path('/modules/xpayment/plugin/xpayment.php');  
		        return FraudCancelXpaymentHook($invoice);  
		    }  
		
		    function SettledPaidExampleHook($invoice) {  
		        include_once $GLOBALS['xoops']->path('/modules/xpayment/plugin/xpayment.php');          
		        return SettledPaidXpaymentHook($invoice);  
		    }  
		      
		    function SettledUnpaidExampleHook($invoice) {  
		        include_once $GLOBALS['xoops']->path('/modules/xpayment/plugin/xpayment.php');  
		        return SettledUnpaidXpaymentHook($invoice);          
		    }  
		      
		    function SettledCancelExampleHook($invoice) {  
		        include_once $GLOBALS['xoops']->path('/modules/xpayment/plugin/xpayment.php');  
		        return SettledCancelXpaymentHook($invoice);  
		    }  
		
		    // Individual Items Hooks - Real Time Transactional email and functions 
		    function PurchasedPaidExampleItemHook($invoice, $item) {  
		        include_once $GLOBALS['xoops']->path('/modules/xpayment/plugin/xpayment.php');          
		        return PurchasedPaidXpaymentItemHook($invoice, $item);  
		    }  
		      
		    function PurchasedUnpaidExampleItemHook($invoice, $item) {  
		        include_once $GLOBALS['xoops']->path('/modules/xpayment/plugin/xpayment.php');  
		        return PurchasedUnpaidXpaymentItemHook($invoice, $item);          
		    }  
		      
		    function PurchasedCancelExampleItemHook($invoice, $item) {  
		        include_once $GLOBALS['xoops']->path('/modules/xpayment/plugin/xpayment.php');  
		        return PurchasedCancelXpaymentItemHook($invoice, $item);  
		    }  
		
		    function RefundedPaidExampleItemHook($invoice, $item) {  
		        include_once $GLOBALS['xoops']->path('/modules/xpayment/plugin/xpayment.php');          
		        return RefundedPaidXpaymentItemHook($invoice, $item);  
		    }  
		      
		    function RefundedUnpaidExampleItemHook($invoice, $item) {  
		        include_once $GLOBALS['xoops']->path('/modules/xpayment/plugin/xpayment.php');  
		        return RefundedUnpaidXpaymentItemHook($invoice, $item);          
		    }  
		      
		    function RefundedCancelExampleItemHook($invoice, $item) {  
		        include_once $GLOBALS['xoops']->path('/modules/xpayment/plugin/xpayment.php');  
		        return RefundedCancelXpaymentItemHook($invoice, $item);  
		    }  
		
		    function UndelievedPaidExampleItemHook($invoice, $item) {  
		        include_once $GLOBALS['xoops']->path('/modules/xpayment/plugin/xpayment.php');          
		        return UndelievedPaidXpaymentItemHook($invoice, $item);  
		    }  
		      
		    function UndelievedUnpaidExampleItemHook($invoice, $item) {  
		        include_once $GLOBALS['xoops']->path('/modules/xpayment/plugin/xpayment.php');  
		        return UndelievedUnpaidXpaymentItemHook($invoice, $item);          
		    }  
		      
		    function UndelievedCancelExampleItemHook($invoice, $item) {  
		        include_once $GLOBALS['xoops']->path('/modules/xpayment/plugin/xpayment.php');  
		        return UndelievedCancelXpaymentItemHook($invoice, $item);  
		    }  
		
		    function DamagedPaidExampleItemHook($invoice, $item) {  
		        include_once $GLOBALS['xoops']->path('/modules/xpayment/plugin/xpayment.php');          
		        return DamagedPaidXpaymentItemHook($invoice, $item);  
		    }  
		      
		    function DamagedUnpaidExampleItemHook($invoice, $item) {  
		        include_once $GLOBALS['xoops']->path('/modules/xpayment/plugin/xpayment.php');  
		        return DamagedUnpaidXpaymentItemHook($invoice, $item);          
		    }  
		      
		    function DamagedCancelExampleItemHook($invoice, $item) {  
		        include_once $GLOBALS['xoops']->path('/modules/xpayment/plugin/xpayment.php');  
		        return DamagedCancelXpaymentItemHook($invoice, $item);  
		    }  
		
		    function ExpressPaidExampleItemHook($invoice, $item) {  
		        include_once $GLOBALS['xoops']->path('/modules/xpayment/plugin/xpayment.php');          
		        return ExpressPaidXpaymentItemHook($invoice, $item);  
		    }  
		      
		    function ExpressUnpaidExampleItemHook($invoice, $item) {  
		        include_once $GLOBALS['xoops']->path('/modules/xpayment/plugin/xpayment.php');  
		        return ExpressUnpaidXpaymentItemHook($invoice, $item);          
		    }  
		      
		    function ExpressCancelExampleItemHook($invoice, $item) {  
		        include_once $GLOBALS['xoops']->path('/modules/xpayment/plugin/xpayment.php');  
		        return ExpressCancelXpaymentItemHook($invoice, $item);  
		    }  
		
		    // Transaction Hooks - Real Time Transactional email and functions 
		    function PaymentPaidExampleTransactionHook($invoice, $transaction) {  
		        include_once $GLOBALS['xoops']->path('/modules/xpayment/plugin/xpayment.php');          
		        return PaymentPaidXpaymentTransactionHook($invoice, $transaction);  
		    }  
		      
		    function PaymentUnpaidExampleTransactionHook($invoice, $transaction) {  
		        include_once $GLOBALS['xoops']->path('/modules/xpayment/plugin/xpayment.php');  
		        return PaymentUnpaidXpaymentTransactionHook($invoice, $transaction);         
		    }  
		      
		    function PaymentCancelExampleTransactionHook($invoice, $transaction) {  
		        include_once $GLOBALS['xoops']->path('/modules/xpayment/plugin/xpayment.php');  
		        return PaymentCancelXpaymentTransactionHook($invoice, $transaction);  
		    }  
		
		    function RefundPaidExampleTransactionHook($invoice, $transaction) {  
		        include_once $GLOBALS['xoops']->path('/modules/xpayment/plugin/xpayment.php');          
		        return RefundPaidXpaymentTransactionHook($invoice, $transaction);  
		    }  
		      
		    function RefundUnpaidExampleTransactionHook($invoice, $transaction) {  
		        include_once $GLOBALS['xoops']->path('/modules/xpayment/plugin/xpayment.php');  
		        return RefundUnpaidXpaymentTransactionHook($invoice, $transaction);         
		    }  
		      
		    function RefundCancelExampleTransactionHook($invoice, $transaction) {  
		        include_once $GLOBALS['xoops']->path('/modules/xpayment/plugin/xpayment.php');  
		        return RefundCancelXpaymentTransactionHook($invoice, $transaction);  
		    }  
		
		    function PendingPaidExampleTransactionHook($invoice, $transaction) {  
		        include_once $GLOBALS['xoops']->path('/modules/xpayment/plugin/xpayment.php');          
		        return PendingPaidXpaymentTransactionHook($invoice, $transaction);  
		    }  
		      
		    function PendingUnpaidExampleTransactionHook($invoice, $transaction) {  
		        include_once $GLOBALS['xoops']->path('/modules/xpayment/plugin/xpayment.php');  
		        return PendingUnpaidXpaymentTransactionHook($invoice, $transaction);         
		    }  
		      
		    function PendingCancelExampleTransactionHook($invoice, $transaction) {  
		        include_once $GLOBALS['xoops']->path('/modules/xpayment/plugin/xpayment.php');  
		        return PendingCancelXpaymentTransactionHook($invoice, $transaction);  
		    }  
		
		    function NoticePaidExampleTransactionHook($invoice, $transaction) {  
		        include_once $GLOBALS['xoops']->path('/modules/xpayment/plugin/xpayment.php');          
		        return NoticePaidXpaymentTransactionHook($invoice, $transaction);  
		    }  
		      
		    function NoticeUnpaidExampleTransactionHook($invoice, $transaction) {  
		        include_once $GLOBALS['xoops']->path('/modules/xpayment/plugin/xpayment.php');  
		        return NoticeUnpaidXpaymentTransactionHook($invoice, $transaction);         
		    }  
		      
		    function NoticeCancelExampleTransactionHook($invoice, $transaction) {  
		        include_once $GLOBALS['xoops']->path('/modules/xpayment/plugin/xpayment.php');  
		        return NoticeCancelXpaymentTransactionHook($invoice, $transaction);  
		    }  
		
		    function OtherPaidExampleTransactionHook($invoice, $transaction) {  
		        include_once $GLOBALS['xoops']->path('/modules/xpayment/plugin/xpayment.php');          
		        return OtherPaidXpaymentTransactionHook($invoice, $transaction);  
		    }  
		      
		    function OtherUnpaidExampleTransactionHook($invoice, $transaction) {  
		        include_once $GLOBALS['xoops']->path('/modules/xpayment/plugin/xpayment.php');  
		        return OtherUnpaidXpaymentTransactionHook($invoice);          
		    }  
		      
		    function OtherCancelExampleTransactionHook($invoice, $transaction) {  
		        include_once $GLOBALS['xoops']->path('/modules/xpayment/plugin/xpayment.php');  
		        return OtherCancelXpaymentTransactionHook($invoice);  
		    }  
		?>

Root Mail functions 

	Root mail functions are called from the xpayment_invoice table, the function consist of the mode field, filename and
	enscapulation. For ModeFilenameHook($invoice).
	
	CREATE TABLE `xpayment_invoice` (
	`iid` INT(15) UNSIGNED NOT NULL AUTO_INCREMENT,
	`mode` ENUM('PAID','UNPAID','CANCEL') NOT NULL DEFAULT 'UNPAID',
	`plugin` VARCHAR(128) NOT NULL,
	`return` VARCHAR(1000) NOT NULL,
	`cancel` VARCHAR(1000) NOT NULL,
	`ipn` VARCHAR(1000) NOT NULL,
	`invoicenumber` VARCHAR(64) DEFAULT NULL,
	`drawfor` VARCHAR(255) DEFAULT NULL,
	`drawto` VARCHAR(255) NOT NULL,
	`drawto_email` VARCHAR(255) NOT NULL,
	`paid` DECIMAL(15,2) DEFAULT '0.00',
	`amount` DECIMAL(15,2) DEFAULT '0.00',
	`grand` DECIMAL(15,2) DEFAULT '0.00',
	`shipping` DECIMAL(15,2) DEFAULT '0.00',
	`handling` DECIMAL(15,2) DEFAULT '0.00',
	`weight` DECIMAL(15,2) DEFAULT '0.00',
	`weight_unit` ENUM('lbs','kgs') DEFAULT 'kgs',
	`tax` DECIMAL(15,2) DEFAULT '0.00',
	`currency` VARCHAR(3) DEFAULT 'AUD',
	`items` INT(12) DEFAULT '0',
	`key` VARCHAR(255) DEFAULT NULL,
	`transactionid` VARCHAR(255) DEFAULT NULL,
	`gateway` VARCHAR(128) DEFAULT NULL,
	`created` INT(13) DEFAULT '0',
	`updated` INT(13) DEFAULT '0',
	`actioned` INT(13) DEFAULT '0',
	`reoccurrence` INT(8) DEFAULT '0',
	`reoccurrence_period_days` INT(8) DEFAULT '0',
	`reoccurrences` INT(8) DEFAULT '0',
	`occurrence` INT(13) DEFAULT '0',
	`previous` INT(13) DEFAULT '0',
	`occurrence_amount` DECIMAL(15,2) DEFAULT '0.00',
	`occurrence_grand` DECIMAL(15,2) DEFAULT '0.00',
	`occurrence_shipping` DECIMAL(15,2) DEFAULT '0.00',
	`occurrence_handling` DECIMAL(15,2) DEFAULT '0.00',
	`occurrence_tax` DECIMAL(15,2) DEFAULT '0.00',
	`occurrence_weight` DECIMAL(15,6) DEFAULT '0.000000',
	`remittion` ENUM('NONE','PENDING','NOTICE','COLLECT','FRAUD','SETTLED') NOT NULL DEFAULT 'NONE',
	`remittion_settled` DECIMAL(15,2) DEFAULT '0.00',
	`donation` TINYINT(2) DEFAULT '0',
	`comment` VARCHAR(5000) DEFAULT NULL,
	`user_ip` VARCHAR(128) DEFAULT NULL,
	`user_netaddy` VARCHAR(255) DEFAULT NULL,
	`user_uid` INT(13) DEFAULT '0',
	`user_uids` VARCHAR(1000) DEFAULT NULL,
	`broker_uids` VARCHAR(1000) DEFAULT NULL,
	`accounts_uids` VARCHAR(1000) DEFAULT NULL,
	`officer_uids` VARCHAR(1000) DEFAULT NULL,
	`remitted` INT(13) DEFAULT '0',
	`due` INT(13) DEFAULT '0',
	`collect` INT(13) DEFAULT '0',
	`wait` INT(13) DEFAULT '0',
	`offline` INT(13) DEFAULT '0',
	PRIMARY KEY (`iid`),
	KEY `SEARCH` (`iid`,`mode`,`currency`,`items`,`remittion`)
	) ENGINE=INNODB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8
	
	function PaidXpaymentHook($invoice)
	Called when Enumerator on an invoice is set Paid, this is the ROOT mail function (Bold in Email Permissions)
	Template: xpayment_invoice_paid.tpl
	Subject: _XPY_EMAIL_PAID_SUBJECT
	
	function UnpaidXpaymentHook($invoice)
	Called when Enumerator on an invoice is set to Unpaid, this is the ROOT mail function. (Bold in Email Permissions)
	Template: xpayment_invoice_unpaid.tpl
	Subject: _XPY_EMAIL_UNPAID_SUBJECT
	
	function CancelXpaymentHook($invoice)
	Called when Enumerator on an invoice is set to Cancelled, this is the ROOT mail function. (Bold in Email Permissions)
	Template: xpayment_invoice_cancelled.tpl
	Subject: _XPY_EMAIL_CANCELLED_SUBJECT
	
Remittance Enumerator Invoice Plugin Functions

	Remittion mail functions are called from the xpayment_invoice table, the function consist of the remittion then mode 
	field, filename and enscapulation. For RemittionModeFilenameHook($invoice).
	
	CREATE TABLE `xpayment_invoice` (
	`iid` INT(15) UNSIGNED NOT NULL AUTO_INCREMENT,
	`mode` ENUM('PAID','UNPAID','CANCEL') NOT NULL DEFAULT 'UNPAID',
	`plugin` VARCHAR(128) NOT NULL,
	`return` VARCHAR(1000) NOT NULL,
	`cancel` VARCHAR(1000) NOT NULL,
	`ipn` VARCHAR(1000) NOT NULL,
	`invoicenumber` VARCHAR(64) DEFAULT NULL,
	`drawfor` VARCHAR(255) DEFAULT NULL,
	`drawto` VARCHAR(255) NOT NULL,
	`drawto_email` VARCHAR(255) NOT NULL,
	`paid` DECIMAL(15,2) DEFAULT '0.00',
	`amount` DECIMAL(15,2) DEFAULT '0.00',
	`grand` DECIMAL(15,2) DEFAULT '0.00',
	`shipping` DECIMAL(15,2) DEFAULT '0.00',
	`handling` DECIMAL(15,2) DEFAULT '0.00',
	`weight` DECIMAL(15,2) DEFAULT '0.00',
	`weight_unit` ENUM('lbs','kgs') DEFAULT 'kgs',
	`tax` DECIMAL(15,2) DEFAULT '0.00',
	`currency` VARCHAR(3) DEFAULT 'AUD',
	`items` INT(12) DEFAULT '0',
	`key` VARCHAR(255) DEFAULT NULL,
	`transactionid` VARCHAR(255) DEFAULT NULL,
	`gateway` VARCHAR(128) DEFAULT NULL,
	`created` INT(13) DEFAULT '0',
	`updated` INT(13) DEFAULT '0',
	`actioned` INT(13) DEFAULT '0',
	`reoccurrence` INT(8) DEFAULT '0',
	`reoccurrence_period_days` INT(8) DEFAULT '0',
	`reoccurrences` INT(8) DEFAULT '0',
	`occurrence` INT(13) DEFAULT '0',
	`previous` INT(13) DEFAULT '0',
	`occurrence_amount` DECIMAL(15,2) DEFAULT '0.00',
	`occurrence_grand` DECIMAL(15,2) DEFAULT '0.00',
	`occurrence_shipping` DECIMAL(15,2) DEFAULT '0.00',
	`occurrence_handling` DECIMAL(15,2) DEFAULT '0.00',
	`occurrence_tax` DECIMAL(15,2) DEFAULT '0.00',
	`occurrence_weight` DECIMAL(15,6) DEFAULT '0.000000',
	`remittion` ENUM('NONE','PENDING','NOTICE','COLLECT','FRAUD','SETTLED') NOT NULL DEFAULT 'NONE',
	`remittion_settled` DECIMAL(15,2) DEFAULT '0.00',
	`donation` TINYINT(2) DEFAULT '0',
	`comment` VARCHAR(5000) DEFAULT NULL,
	`user_ip` VARCHAR(128) DEFAULT NULL,
	`user_netaddy` VARCHAR(255) DEFAULT NULL,
	`user_uid` INT(13) DEFAULT '0',
	`user_uids` VARCHAR(1000) DEFAULT NULL,
	`broker_uids` VARCHAR(1000) DEFAULT NULL,
	`accounts_uids` VARCHAR(1000) DEFAULT NULL,
	`officer_uids` VARCHAR(1000) DEFAULT NULL,
	`remitted` INT(13) DEFAULT '0',
	`due` INT(13) DEFAULT '0',
	`collect` INT(13) DEFAULT '0',
	`wait` INT(13) DEFAULT '0',
	`offline` INT(13) DEFAULT '0',
	PRIMARY KEY (`iid`),
	KEY `SEARCH` (`iid`,`mode`,`currency`,`items`,`remittion`)
	) ENGINE=INNODB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8
	
	function NonePaidXpaymentHook($invoice)
	Called when Remittence enumerator is set to None on an invoice and the Invoice is marked Paid.
	Template: xpayment_invoice_paid_none.tpl
	Subject: _XPY_EMAIL_PAID_NONE_SUBJECT
	
	function NoneUnpaidXpaymentHook($invoice)
	Called when Remittence enumerator is set to None on an invoice and the Invoice is marked Unpaid – see cron.
	Template: xpayment_invoice_unpaid_none.tpl
	Subject: _XPY_EMAIL_UNPAID_NONE_SUBJECT
	
	function NoneCancelXpaymentHook($invoice)
	Called when Remittence enumerator is set to None on an invoice and the Invoice is marked Cancelled.
	Template: xpayment_invoice_cancelled_none.tpl
	Subject: _XPY_EMAIL_CANCELLED_NONE_SUBJECT
	
	function PendingPaidXpaymentHook($invoice)
	Called when Remittence enumerator is set to Pending on an invoice and the Invoice is marked Paid.
	Template: xpayment_invoice_paid_pending.tpl
	Subject: _XPY_EMAIL_PAID_PENDING_SUBJECT
	
	function PendingUnpaidXpaymentHook($invoice)
	Called when Remittence enumerator is set to Pending on an invoice and the Invoice is marked Unpaid.
	Template: xpayment_invoice_unpaid_pending.tpl
	Subject: _XPY_EMAIL_UNPAID_PENDING_SUBJECT
	
	function PendingCancelXpaymentHook($invoice)
	Called when Remittence enumerator is set to Pending on an invoice and the Invoice is marked Cancelled.
	Template: xpayment_invoice_cancelled_pending.tpl
	Subject: _XPY_EMAIL_CANCELLED_PENDING_SUBJECT
	
	function NoticePaidXpaymentHook($invoice)
	Called when Remittence enumerator is set to Notice normally when it is over due on an invoice and the Invoice is marked Paid.
	Template: xpayment_invoice_paid_notice.tpl
	Subject: _XPY_EMAIL_PAID_NOTICE_SUBJECT
	
	function NoticeUnpaidXpaymentHook($invoice)
	Called when Remittence enumerator is set to Notice normally when it is over due on an invoice and the Invoice is marked Unpaid.
	Template: xpayment_invoice_unpaid_notice.tpl
	Subject: _XPY_EMAIL_UNPAID_NOTICE_SUBJECT
	
	function NoticeCancelXpaymentHook($invoice)
	Called when Remittence enumerator is set to Notice normally when it is over due on an invoice and the Invoice is marked Cancelled.
	Template: xpayment_invoice_cancelled_notice.tpl
	Subject: _XPY_EMAIL_CANCELLED_NOTICE_SUBJECT
	
	function CollectPaidXpaymentHook($invoice)
	Called when Remittence enumerator is set to Collect normally when it is overdue and past collection date by cron on an invoice and the Invoice is marked Paid.
	Template: xpayment_invoice_paid_collect.tpl
	Subject: _XPY_EMAIL_PAID_COLLECT_SUBJECT
	
	function CollectUnpaidXpaymentHook($invoice)
	Called when Remittence enumerator is set to Collect normally when it is overdue and past collection date by cron on an invoice and the Invoice is marked Unpaid.
	Template: xpayment_invoice_unpaid_collect.tpl
	Subject: _XPY_EMAIL_UNPAID_COLLECT_SUBJECT
	
	function CollectCancelXpaymentHook($invoice)
	Called when Remittence enumerator is set to Collect normally when it is overdue and past collection on an invoice and the Invoice is marked Cancelled.
	Template: xpayment_invoice_cancelled_collect.tpl
	Subject: _XPY_EMAIL_CANCELLED_COLLECT_SUBJECT
	
	function FraudPaidXpaymentHook($invoice)
	Called when Remittence enumerator is set to Fraud by payment gateway normally on an invoice and the Invoice is marked Paid.
	Template: xpayment_invoice_paid_fraud.tpl
	Subject: _XPY_EMAIL_PAID_FRAUD_SUBJECT
	
	function FraudUnpaidXpaymentHook($invoice)
	Called when Remittence enumerator is set to Fraud by payment gateway normally on an invoice and the Invoice is marked Unpaid.
	Template: xpayment_invoice_unpaid_fraud.tpl
	Subject: _XPY_EMAIL_UNPAID_FRAUD_SUBJECT
	
	function FraudCancelXpaymentHook($invoice)
	Called when Remittence enumerator is set to Fraud by payment gateway normally on an invoice and the Invoice is marked Cancelled.
	Template: xpayment_invoice_cancelled_fraud.tpl
	Subject: _XPY_EMAIL_CANCELLED_FRAUD_SUBJECT
	
	function SettledPaidXpaymentHook($invoice)
	Called when Remittence enumerator is set to Settled by payment gateway normally on an invoice and the Invoice is marked Paid.
	Template: xpayment_invoice_paid_settled.tpl
	Subject: _XPY_EMAIL_PAID_SETTLED_SUBJECT
	
	function SettledUnpaidXpaymentHook($invoice)
	Called when Remittence enumerator is set to Settled by payment gateway normally on an invoice and the Invoice is marked Unpaid.
	Template: xpayment_invoice_unpaid_settled.tpl
	Subject: _XPY_EMAIL_UNPAID_SETTLED_SUBJECT
	
	function SettledCancelXpaymentHook($invoice)
	Called when Remittence enumerator is set to Settled by payment gateway normally on an invoice and the Invoice is marked Cancelled.
	Template: xpayment_invoice_cancelled_settled.tpl
	Subject: _XPY_EMAIL_CANCELLED_SETTLED_SUBJECT
	
Warehouse/PLC Logic/Pick & Pack Scripts - Item by Item - Invoice Plugin Functions

	Warehouse functions are called from the xpayment_invoice as well as the xpayment_invoice_item table, the function consist
	of the xpayment_invoice_items .mode enumerator then the xpayment_invoice .mode field, filename and enscapulation. For 
	ModeModeFilenameItemHook($item, $invoice).
	
	CREATE TABLE `xpayment_invoice_items` (
	`iiid` INT(26) UNSIGNED NOT NULL AUTO_INCREMENT,
	`iid` INT(15) UNSIGNED NOT NULL,
	`cat` VARCHAR(255) DEFAULT NULL,
	2 `name` VARCHAR(255) DEFAULT NULL,
	`amount` DECIMAL(19,4) DEFAULT '0.0000',
	`quantity` INT(6) DEFAULT '0',
	`shipping` DECIMAL(15,2) DEFAULT '0.00',
	`handling` DECIMAL(15,2) DEFAULT '0.00',
	`weight` DECIMAL(15,6) DEFAULT '0.000000',
	`tax` DECIMAL(15,2) DEFAULT '0.00',
	`description` VARCHAR(5000) DEFAULT NULL,
	`mode` ENUM('PURCHASED','REFUNDED','UNDELIEVED','DAMAGED','EXPRESS') NOT NULL DEFAULT 'PURCHASED',
	`created` INT(13) DEFAULT '0',
	`updated` INT(13) DEFAULT '0',
	`actioned` INT(13) DEFAULT '0',
	PRIMARY KEY (`iiid`),
	KEY `SEARCH` (`iid`,`cat`(12),`name`(12))
	) ENGINE=INNODB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8
	
	CREATE TABLE `xpayment_invoice` (
	`iid` INT(15) UNSIGNED NOT NULL AUTO_INCREMENT,
	`mode` ENUM('PAID','UNPAID','CANCEL') NOT NULL DEFAULT 'UNPAID',
	`plugin` VARCHAR(128) NOT NULL,
	`return` VARCHAR(1000) NOT NULL,
	`cancel` VARCHAR(1000) NOT NULL,
	`ipn` VARCHAR(1000) NOT NULL,
	`invoicenumber` VARCHAR(64) DEFAULT NULL,
	`drawfor` VARCHAR(255) DEFAULT NULL,
	`drawto` VARCHAR(255) NOT NULL,
	`drawto_email` VARCHAR(255) NOT NULL,
	`paid` DECIMAL(15,2) DEFAULT '0.00',
	`amount` DECIMAL(15,2) DEFAULT '0.00',
	`grand` DECIMAL(15,2) DEFAULT '0.00',
	`shipping` DECIMAL(15,2) DEFAULT '0.00',
	`handling` DECIMAL(15,2) DEFAULT '0.00',
	`weight` DECIMAL(15,2) DEFAULT '0.00',
	`weight_unit` ENUM('lbs','kgs') DEFAULT 'kgs',
	`tax` DECIMAL(15,2) DEFAULT '0.00',
	`currency` VARCHAR(3) DEFAULT 'AUD',
	`items` INT(12) DEFAULT '0',
	`key` VARCHAR(255) DEFAULT NULL,
	`transactionid` VARCHAR(255) DEFAULT NULL,
	`gateway` VARCHAR(128) DEFAULT NULL,
	`created` INT(13) DEFAULT '0',
	`updated` INT(13) DEFAULT '0',
	`actioned` INT(13) DEFAULT '0',
	`reoccurrence` INT(8) DEFAULT '0',
	`reoccurrence_period_days` INT(8) DEFAULT '0',
	`reoccurrences` INT(8) DEFAULT '0',
	`occurrence` INT(13) DEFAULT '0',
	`previous` INT(13) DEFAULT '0',
	`occurrence_amount` DECIMAL(15,2) DEFAULT '0.00',
	`occurrence_grand` DECIMAL(15,2) DEFAULT '0.00',
	`occurrence_shipping` DECIMAL(15,2) DEFAULT '0.00',
	`occurrence_handling` DECIMAL(15,2) DEFAULT '0.00',
	`occurrence_tax` DECIMAL(15,2) DEFAULT '0.00',
	`occurrence_weight` DECIMAL(15,6) DEFAULT '0.000000',
	`remittion` ENUM('NONE','PENDING','NOTICE','COLLECT','FRAUD','SETTLED') NOT NULL DEFAULT 'NONE',
	`remittion_settled` DECIMAL(15,2) DEFAULT '0.00',
	`donation` TINYINT(2) DEFAULT '0',
	`comment` VARCHAR(5000) DEFAULT NULL,
	`user_ip` VARCHAR(128) DEFAULT NULL,
	`user_netaddy` VARCHAR(255) DEFAULT NULL,
	`user_uid` INT(13) DEFAULT '0',
	`user_uids` VARCHAR(1000) DEFAULT NULL,
	`broker_uids` VARCHAR(1000) DEFAULT NULL,
	`accounts_uids` VARCHAR(1000) DEFAULT NULL,
	`officer_uids` VARCHAR(1000) DEFAULT NULL,
	`remitted` INT(13) DEFAULT '0',
	`due` INT(13) DEFAULT '0',
	`collect` INT(13) DEFAULT '0',
	`wait` INT(13) DEFAULT '0',
	`offline` INT(13) DEFAULT '0',
	PRIMARY KEY (`iid`),
	KEY `SEARCH` (`iid`,`mode`,`currency`,`items`,`remittion`)
	) ENGINE=INNODB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8
	
	function PurchasedPaidXpaymentItemHook($item, $invoice)
	Called when Item enumerator is set to Purchased by payment gateway or invoice handler normally on an invoice and the Invoice is marked Paid.
	Template: xpayment_invoice_paid_purchased.tpl
	Subject: _XPY_EMAIL_PAID_PURCHASED_SUBJECT
	
	function PurchasedUnpaidXpaymentItemHook($item, $invoice)
	Called when Item enumerator is set to Purchased by payment gateway or invoice handler normally on an invoice and the Invoice is marked Unpaid.
	Template: xpayment_invoice_unpaid_purchased.tpl
	Subject: _XPY_EMAIL_UNPAID_PURCHASED_SUBJECT
	
	function PurchasedCancelXpaymentItemHook($item, $invoice)
	Called when Item enumerator is set to Purchased by payment gateway or invoice handler normally on an invoice and the Invoice is marked Cancelled.
	Template: xpayment_invoice_cancelled_purchased.tp
	Subject: _XPY_EMAIL_CANCELLED_PURCHASED_SUBJECT
	
	function RefundedPaidXpaymentItemHook($item, $invoice)
	Called when Item enumerator is set to Refunded by payment gateway or invoice handler normally on an invoice and the Invoice is marked Paid.
	Template: xpayment_invoice_paid_refunded.tpl
	Subject: _XPY_EMAIL_PAID_REFUNDED_SUBJECT
	
	function RefundedUnpaidXpaymentItemHook($item, $invoice)
	Called when Item enumerator is set to Refunded by payment gateway or invoice handler normally on an invoice and the Invoice is marked Unpaid.
	Template: xpayment_invoice_unpaid_refunded.tpl
	Subject: _XPY_EMAIL_UNPAID_REFUNDED_SUBJECT
	
	function RefundedCancelXpaymentItemHook($item, $invoice)
	Called when Item enumerator is set to Refunded by payment gateway or invoice handler normally on an invoice and the Invoice is marked Cancelled.
	Template: xpayment_invoice_cancelled_refunded.tpl
	Subject: _XPY_EMAIL_CANCELLED_REFUNDED_SUBJECT
	
	function UndelievedPaidXpaymentItemHook($items, $invoice)
	Called when Item enumerator is set to Undelievered by payment gateway or invoice handler normally on an invoice and the Invoice is marked Paid.
	Template: xpayment_invoice_paid_undelieved.tpl
	Subject: _XPY_EMAIL_PAID_UNDELIEVED_SUBJECT
	
	function UndelievedUnpaidXpaymentItemHook($item, $invoice)
	Called when Item enumerator is set to Undelievered by payment gateway or invoice handler normally on an invoice and the Invoice is marked Unpaid.
	Template: xpayment_invoice_unpaid_undelieved.tpl
	Subject: _XPY_EMAIL_UNPAID_UNDELIEVED_SUBJECT
	
	function UndelievedCancelXpaymentItemHook($item, $invoice)
	Called when Item enumerator is set to Undelievered by payment gateway or invoice handler normally on an invoice and the Invoice is marked Cancel.
	Template: xpayment_invoice_cancelled_undelieved.tpl
	Subject: _XPY_EMAIL_CANCELLED_UNDELIEVED_SUBJECT
	
	function DamagedPaidXpaymentItemHook($item, $invoice)
	Called when Item enumerator is set to Damaged by payment gateway or invoice handler normally on an invoice and the Invoice is marked Paid.
	Template: xpayment_invoice_paid_damaged.tpl
	Subject: _XPY_EMAIL_PAID_DAMAGED_SUBJECT
	
	function DamagedUnpaidXpaymentItemHook($item, $invoice)
	Called when Item enumerator is set to Damaged by payment gateway or invoice handler normally on an invoice and the Invoice is marked Unpaid.
	Template: xpayment_invoice_unpaid_damaged.tpl
	Subject: _XPY_EMAIL_UNPAID_DAMAGED_SUBJECT
	
	function DamagedCancelXpaymentItemHook($item, $invoice)
	Called when Item enumerator is set to Damaged by payment gateway or invoice handler normally on an invoice and the Invoice is marked Cancelled.
	Template: xpayment_invoice_cancelled_damaged.tpl
	Subject: _XPY_EMAIL_CANCELLED_DAMAGED_SUBJECT
	
	function ExpressPaidXpaymentItemHook($item, $invoice)
	Called when Item enumerator is set to Express by payment gateway or invoice handler normally on an invoice and the Invoice is marked Paid.
	Template: xpayment_invoice_paid_express.tpl
	Subject: _XPY_EMAIL_PAID_EXPRESS_SUBJECT
	
	function ExpressUnpaidXpaymentItemHook($item, $invoice)
	Called when Item enumerator is set to Express by payment gateway or invoice handler normally on an invoice and the Invoice is marked Unpaid.
	Template: xpayment_invoice_unpaid_express.tpl
	Subject: _XPY_EMAIL_UNPAID_EXPRESS_SUBJECT
	
	function ExpressCancelXpaymentItemHook($item, $invoice)
	Called when Item enumerator is set to Express by payment gateway or invoice handler normally on an invoice and the Invoice is marked Cancelled.
	Template: xpayment_invoice_cancelled_express.tpl
	Subject: _XPY_EMAIL_CANCELLED_EXPRESS_SUBJECT
	
Transaction Plugin Functions

	Transaction functions are called from the xpayment_invoice as well as the xpayment_invoice_transactions table, the 
	function consist of the xpayment_invoice_transactions.mode enumerator then the xpayment_invoice .mode field, filename 
	and enscapulation. For ModeModeFilenameTransactionHook($transaction, $invoice).
	
	CREATE TABLE `xpayment_invoice_transactions` (
	`tiid` INT(28) UNSIGNED NOT NULL AUTO_INCREMENT,
	`iid` INT(23) UNSIGNED NOT NULL,
	`transactionid` VARCHAR(255) DEFAULT NULL,
	`email` VARCHAR(255) DEFAULT NULL,
	`invoice` VARCHAR(255) DEFAULT NULL,
	`custom` VARCHAR(255) DEFAULT NULL,
	`status` VARCHAR(255) DEFAULT NULL,
	`date` INT(13) DEFAULT '0',
	`gross` DECIMAL(15,2) DEFAULT '0.00',
	`fee` DECIMAL(15,2) DEFAULT '0.00',
	`settle` DECIMAL(15,2) DEFAULT '0.00',
	`exchangerate` VARCHAR(128) DEFAULT NULL,
	`firstname` VARCHAR(255) DEFAULT NULL,
	`lastname` VARCHAR(255) DEFAULT NULL,
	`street` VARCHAR(255) DEFAULT NULL,
	`city` VARCHAR(255) DEFAULT NULL,
	`state` VARCHAR(255) DEFAULT NULL,
	`postcode` VARCHAR(255) DEFAULT NULL,
	`country` VARCHAR(255) DEFAULT NULL,
	`address_status` VARCHAR(255) DEFAULT NULL,
	`payer_email` VARCHAR(255) DEFAULT NULL,
	`payer_status` VARCHAR(255) DEFAULT NULL,
	`gateway` VARCHAR(128) DEFAULT NULL,
	`plugin` VARCHAR(128) DEFAULT NULL,
	`mode` ENUM('PAYMENT','REFUND','PENDING','NOTICE','OTHER') NOT NULL DEFAULT 'PAYMENT',
	PRIMARY KEY (`tiid`)
	) ENGINE=INNODB DEFAULT CHARSET=utf8
	
	CREATE TABLE `xpayment_invoice` (
	`iid` INT(15) UNSIGNED NOT NULL AUTO_INCREMENT,
	`mode` ENUM('PAID','UNPAID','CANCEL') NOT NULL DEFAULT 'UNPAID',
	`plugin` VARCHAR(128) NOT NULL,
	`return` VARCHAR(1000) NOT NULL,
	`cancel` VARCHAR(1000) NOT NULL,
	`ipn` VARCHAR(1000) NOT NULL,
	`invoicenumber` VARCHAR(64) DEFAULT NULL,
	`drawfor` VARCHAR(255) DEFAULT NULL,
	`drawto` VARCHAR(255) NOT NULL,
	`drawto_email` VARCHAR(255) NOT NULL,
	`paid` DECIMAL(15,2) DEFAULT '0.00',
	`amount` DECIMAL(15,2) DEFAULT '0.00',
	`grand` DECIMAL(15,2) DEFAULT '0.00',
	`shipping` DECIMAL(15,2) DEFAULT '0.00',
	`handling` DECIMAL(15,2) DEFAULT '0.00',
	`weight` DECIMAL(15,2) DEFAULT '0.00',
	`weight_unit` ENUM('lbs','kgs') DEFAULT 'kgs',
	`tax` DECIMAL(15,2) DEFAULT '0.00',
	`currency` VARCHAR(3) DEFAULT 'AUD',
	`items` INT(12) DEFAULT '0',
	`key` VARCHAR(255) DEFAULT NULL,
	`transactionid` VARCHAR(255) DEFAULT NULL,
	`gateway` VARCHAR(128) DEFAULT NULL,
	`created` INT(13) DEFAULT '0',
	`updated` INT(13) DEFAULT '0',
	`actioned` INT(13) DEFAULT '0',
	`reoccurrence` INT(8) DEFAULT '0',
	`reoccurrence_period_days` INT(8) DEFAULT '0',
	`reoccurrences` INT(8) DEFAULT '0',
	`occurrence` INT(13) DEFAULT '0',
	`previous` INT(13) DEFAULT '0',
	`occurrence_amount` DECIMAL(15,2) DEFAULT '0.00',
	`occurrence_grand` DECIMAL(15,2) DEFAULT '0.00',
	`occurrence_shipping` DECIMAL(15,2) DEFAULT '0.00',
	`occurrence_handling` DECIMAL(15,2) DEFAULT '0.00',
	`occurrence_tax` DECIMAL(15,2) DEFAULT '0.00',
	`occurrence_weight` DECIMAL(15,6) DEFAULT '0.000000',
	`remittion` ENUM('NONE','PENDING','NOTICE','COLLECT','FRAUD','SETTLED') NOT NULL DEFAULT 'NONE',
	`remittion_settled` DECIMAL(15,2) DEFAULT '0.00',
	`donation` TINYINT(2) DEFAULT '0',
	`comment` VARCHAR(5000) DEFAULT NULL,
	`user_ip` VARCHAR(128) DEFAULT NULL,
	`user_netaddy` VARCHAR(255) DEFAULT NULL,
	`user_uid` INT(13) DEFAULT '0',
	`user_uids` VARCHAR(1000) DEFAULT NULL,
	`broker_uids` VARCHAR(1000) DEFAULT NULL,
	`accounts_uids` VARCHAR(1000) DEFAULT NULL,
	`officer_uids` VARCHAR(1000) DEFAULT NULL,
	`remitted` INT(13) DEFAULT '0',
	`due` INT(13) DEFAULT '0',
	`collect` INT(13) DEFAULT '0',
	`wait` INT(13) DEFAULT '0',
	`offline` INT(13) DEFAULT '0',
	PRIMARY KEY (`iid`),
	KEY `SEARCH` (`iid`,`mode`,`currency`,`items`,`remittion`)
	) ENGINE=INNODB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8
	
	function PaymentPaidXpaymentTransactionHook($transaction, $invoice)
	Called when transaction enumerator is set to Payment by payment gateway normally on an invoice and the Invoice is marked Paid.
	Template: xpayment_invoice_transaction_paid_payment.tpl
	Subject: _XPY_EMAIL_PAID_TRANSACTION_PAYMENT_SUBJECT
	
	function PaymentUnpaidXpaymentTransactionHook($transaction, $invoice)
	Called when transaction enumerator is set to Payment by payment gateway normally on an invoice and the Invoice is marked Unpaid.
	Template: xpayment_invoice_transaction_unpaid_payment.tpl
	Subject: _XPY_EMAIL_UNPAID_TRANSACTION_PAYMENT_SUBJECT
	
	function PaymentCancelXpaymentTransactionHook($transaction, $invoice)
	Called when transaction enumerator is set to Payment by payment gateway normally on an invoice and the Invoice is marked Cancelled.
	Template: xpayment_invoice_transaction_cancelled_payment.tpl
	Subject: _XPY_EMAIL_CANCELLED_TRANSACTION_PAYMENT_SUBJECT
	
	function RefundPaidXpaymentTransactionHook($transaction, $invoice)
	Called when transaction enumerator is set to Refund by payment gateway normally on an invoice and the Invoice is marked Paid.
	Template: xpayment_invoice_transaction_paid_refund.tpl
	Subject: _XPY_EMAIL_PAID_TRANSACTION_REFUND_SUBJECT
	
	function RefundUnpaidXpaymentTransactionHook($transaction, $invoice)
	Called when transaction enumerator is set to Refund by payment gateway normally on an invoice and the Invoice is marked Unpaid.
	Template: xpayment_invoice_transaction_unpaid_refund.tpl
	Subject: _XPY_EMAIL_UNPAID_TRANSACTION_REFUND_SUBJECT
	
	function RefundCancelXpaymentTransactionHook($transaction, $invoice)
	Called when transaction enumerator is set to Refund by payment gateway normally on an invoice and the Invoice is marked Cancelled.
	Template: xpayment_invoice_transaction_cancelled_refund.tpl
	Subject: _XPY_EMAIL_CANCELLED_TRANSACTION_REFUND_SUBJECT
	
	function PendingPaidXpaymentTransactionHook($transaction, $invoice)
	Called when transaction enumerator is set to Pending by payment gateway normally on an invoice and the Invoice is marked Paid.
	Template: xpayment_invoice_transaction_paid_pending.tpl
	Subject: _XPY_EMAIL_PAID_TRANSACTION_PENDING_SUBJECT
	
	function PendingUnpaidXpaymentTransactionHook($transaction, $invoice)
	Called when transaction enumerator is set to Pending by payment gateway normally on an invoice and the Invoice is marked Unpaid.
	Template: xpayment_invoice_transaction_unpaid_pending.tpl
	Subject: _XPY_EMAIL_UNPAID_TRANSACTION_PENDING_SUBJECT
	
	function PendingCancelXpaymentTransactionHook($transaction, $invoice)
	Called when transaction enumerator is set to Pending by payment gateway normally on an invoice and the Invoice is marked Cancelled.
	Template: xpayment_invoice_transaction_cancelled_pending.tpl
	Subject: _XPY_EMAIL_CANCELLED_TRANSACTION_PENDING_SUBJECT
	
	function NoticePaidXpaymentTransactionHook($transaction, $invoice)
	Called when transaction enumerator is set to Notice by payment gateway normally on an invoice and the Invoice is marked Paid. (For System Messages)
	Template: xpayment_invoice_transaction_paid_notice.tpl
	Subject: _XPY_EMAIL_PAID_TRANSACTION_NOTICE_SUBJECT
	
	function NoticeUnpaidXpaymentTransactionHook($transaction, $invoice)
	Called when transaction enumerator is set to Notice by payment gateway normally on an invoice and the Invoice is marked Unpaid. (For System Messages)
	Template: xpayment_invoice_transaction_unpaid_notice.tpl
	Subject: _XPY_EMAIL_UNPAID_TRANSACTION_NOTICE_SUBJECT
	
	function NoticeCancelXpaymentTransactionHook($transaction, $invoice)
	Called when transaction enumerator is set to Notice by payment gateway normally on an invoice and the Invoice is marked Cancelled. (For System Messages)
	Template: xpayment_invoice_transaction_cancelled_notice.tpl
	Subject: _XPY_EMAIL_CANCELLED_TRANSACTION_NOTICE_SUBJECT
	
	function OtherPaidXpaymentTransactionHook($transaction, $invoice)
	Called when transaction enumerator is set to Other by payment gateway normally on an invoice and the Invoice is marked Paid. (For Other Purposes, generic message)
	Template: xpayment_invoice_transaction_paid_other.tpl
	Subject: _XPY_EMAIL_PAID_TRANSACTION_OTHER_SUBJECT
	
	function OtherUnpaidXpaymentTransactionHook($transaction, $invoice)
	Called when transaction enumerator is set to Other by payment gateway normally on an invoice and the Invoice is marked Unpaid. (For Other Purposes, generic message)
	Template: xpayment_invoice_transaction_unpaid_other.tpl
	Subject: _XPY_EMAIL_UNPAID_TRANSACTION_OTHER_SUBJECT
	
	function OtherCancelXpaymentTransactionHook($transaction, $invoice)
	Called when transaction enumerator is set to Other by payment gateway normally on an invoice and the Invoice is marked Cancel. (For Other Purposes, generic message)
	Template: xpayment_invoice_transaction_cancelled_other.tpl
	Subject: _XPY_EMAIL_CANCELLED_TRANSACTION_OTHER_SUBJECT
	
Standard Email Tags - Invoice

	{SITEURL} = XOOPS_URL
	{SITENAME} = $GLOBALS['xoopsConfig']['sitename']
	{INVOICENUMBER} = $invoice->getVar('invoicenumber')
	{CURRENCY} = $invoice->getVar('currency')
	{DRAWTO} = $invoice->getVar('drawto')
	{DRAWTO_EMAIL} = $invoice->getVar('drawto_email')
	{DRAWFOR} = $invoice->getVar('drawfor')
	{GRAND} = $invoice->getVar('grand')
	{AMOUNT} = $invoice->getVar('amount')
	{SHIPPING} = $invoice->getVar('shipping')
	{HANDLING} = $invoice->getVar('handling')
	{TAX} = $invoice->getVar('tax')
	{WEIGHT} = $invoice->getVar('weight')
	{WEIGHTUNIT} = $invoice->getVar('weight_unit')
	{ITEMS} = $invoice->getVar('items')
	{SETTLEFOR} = $invoice->getVar('remittion_settled')
	{INVURL} = $invoice->getURL()
	{PDFURL} = $invoice->getPDFURL()
	{PAID} = $invoice_transactions_handler->sumOfGross($invoice->getVar('iid')))+$gross;
	{LEFT} = $invoice->getVar('grand') - ($invoice_transactions_handler->sumOfGross($invoice->getVar('iid'))+$gross)
	{REOCCURENCE} = $invoice->getVar('reoccurence')
	{REOCCURENCES} = $invoice->getVar('reoccurences')
	{OCCURENCEPAIDGRAND} = $invoice->getVar('occurence_grand')
	{OCCURENCEPAIDAMOUNT} = $invoice->getVar('occurence_amount')
	{OCCURENCEPAIDSHIPPING} = $invoice->getVar('occurence_shipping')
	{OCCURENCEPAIDHANDLING} = $invoice->getVar('occurence_handling')
	{OCCURENCEPAIDTAX} = $invoice->getVar('occurence_tax')
	{OCCURENCEPAIDWEIGHT} = $invoice->getVar('occurence_weight')
	{OCCURENCELEFTGRAND} = $invoice->getOccurencesLeftGrand()
	{OCCURENCELEFTAMOUNT} = $invoice->getOccurencesLeftAmount()
	{OCCURENCELEFTSHIPPING} = $invoice->getOccurencesLeftShipping()
	{OCCURENCELEFTHANDLING} = $invoice->getOccurencesLeftHandling()
	{OCCURENCELEFTTAX} = $invoice->getOccurencesLeftTax()
	{OCCURENCELEFTWEIGHT} = $invoice->getOccurencesLeftWeight()
	{OCCURENCETOTALGRAND} = $invoice->getOccurencesTotalGrand()
	{OCCURENCETOTALAMOUNT} = $invoice->getOccurencesTotalAmount()
	{OCCURENCETOTALSHIPPING} = $invoice->getOccurencesTotalShipping()
	{OCCURENCETOTALHANDLING} = $invoice->getOccurencesTotalHandling()
	{OCCURENCETOTALTAX} = $invoice->getOccurencesTotalTax()
	{OCCURENCETOTALWEIGHT} = $invoice->getOccurencesTotalWeight()
	{REBILLDAYS} = $invoice->getVar('reoccurence_period_days')
	{CREATED} = date(_DATESTRING, $invoice->getVar('created'))
	{UPDATED} = date(_DATESTRING, $invoice->getVar('updated'))
	{ACTIONED} = date(_DATESTRING, $invoice->getVar('actioned'))
	{OCCURENCE} = date(_DATESTRING, $invoice->getVar('occurance'))
	{PREVIOUS} = date(_DATESTRING, $invoice->getVar('previous'))
	{REMITTED} = date(_DATESTRING, $invoice->getVar('remitted'))
	{DUE} = date(_DATESTRING, $invoice->getVar('due'))
	{COLLECT} = date(_DATESTRING, $invoice->getVar('collect'))
	{WAIT} = date(_DATESTRING, $invoice->getVar('wait'))
	{OFFLINE} = date(_DATESTRING, $invoice->getVar('offline'))
	{USERIP} = $invoice->getVar('user_ip')
	{USERUID} = $invoice->getVar('user_uid')
	{MODE} = $invoice->getVar('mode')
	{REMITTION} = $invoice->getVar('remittion')

	1x - items count line number for item from 1 to last number of items on invoice.. 
	ie: {ITEM_1_IIID}, {ITEM_2_IIID}...

	{ITEM_x_IIID} = $item->getVar('iiid')
	{ITEM_x_IID} = $item->getVar('iid')
	{ITEM_x_CAT} = $item->getVar('cat')
	{ITEM_x_NAME} = $item->getVar('name')
	{ITEM_x_AMOUNT} = $item->getVar('amount')
	{ITEM_x_QUANTITY} = $item->getVar('quantity')
	{ITEM_x_SHIPPING} = $item->getVar('shipping')
	{ITEM_x_HANDLING} = $item->getVar('handling')
	{ITEM_x_WEIGHT} = $item->getVar('weight')
	{ITEM_x_TAX} = $item->getVar('tax')
	{ITEM_x_DESCRIPTION} = $item->getVar('description')
	{ITEM_x_MODE} = $item->getVar('mode')
	{ITEM_x_CREATED} = $item->getVar('created')
	{ITEM_x_UPDATED} = $item->getVar('updated')
	{ITEM_x_ACTIONED} = $item->getVar('actioned')
	{ITEM_x_CREATED_DATETIME} = date(_DATESTRING, $item->getVar('created'))
	{ITEM_x_UPDATED_DATETIME} = date(_DATESTRING, $item->getVar('updated'))
	{ITEM_x_ACTIONED_DATETIME} = date(_DATESTRING, $item->getVar('actioned'))
	{ITEM_COUNT} = max(x);

	1x - items count line number for item from 1 to last number of user in category of broker on invoice.. 
	ie: {BROKER_1_UID}, {BROKER_2_UID}...

	{BROKER_x_UID} = $xoopsuser->getVar('uid')
	{BROKER_x_NAME} = $xoopsuser->getVar('name')
	{BROKER_x_UNAME} = $xoopsuser->getVar('uname')
	{BROKER_x_EMAIL} = $xoopsuser->getVar('email')
	{BROKER_x_URL} = $xoopsuser->getVar('url')
	{BROKER_x_USER_AVATA} = $xoopsuser->getVar('user_avatar')
	{BROKER_x_USER_REGDATE} = $xoopsuser->getVar('user_regdate')
	{BROKER_x_USER_ICQ} = $xoopsuser->getVar('user_icq')
	{BROKER_x_USER_FROM} = $xoopsuser->getVar('user_from')
	{BROKER_x_USER_SIG} = $xoopsuser->getVar('user_sig')
	{BROKER_x_USER_VIEWEMAIL} = $xoopsuser->getVar('user_viewemail')
	{BROKER_x_ACTKEY} = $xoopsuser->getVar('actkey')
	{BROKER_x_USER_AIM} = $xoopsuser->getVar('user_aim')
	{BROKER_x_USER_YIM} = $xoopsuser->getVar('user_yim')
	{BROKER_x_USER_MSNM} = $xoopsuser->getVar('user_msnm')
	{BROKER_x_PASS} = $xoopsuser->getVar('pass')
	{BROKER_x_POSTS} = $xoopsuser->getVar('posts')
	{BROKER_x_ATTACHSIG} = $xoopsuser->getVar('attachsig')
	{BROKER_x_RANK} = $xoopsuser->getVar('rank')
	{BROKER_x_LEVEL} = $xoopsuser->getVar('level')
	{BROKER_x_THEME} = $xoopsuser->getVar('theme')
	{BROKER_x_TIMEZONE_OFFSET} = $xoopsuser->getVar('timezone_offset')
	{BROKER_x_LAST_LOGIN} = $xoopsuser->getVar('last_login')
	{BROKER_x_UMODE} = $xoopsuser->getVar('umode')
	{BROKER_x_UORDER} = $xoopsuser->getVar('uorder')
	{BROKER_x_NOTIFY_METHOD} = $xoopsuser->getVar('notify_method')
	{BROKER_x_NOTIFY_MODE} = $xoopsuser->getVar('notify_mode')
	{BROKER_x_USER_OCC} = $xoopsuser->getVar('user_occ')
	{BROKER_x_BIO} = $xoopsuser->getVar('bio')
	{BROKER_x_USER_INTREST} = $xoopsuser->getVar('user_intrest')
	{BROKER_x_USER_MAILOK} = $xoopsuser->getVar('user_mailok')
	{BROKER_COUNT} = max(x);
	* If Profile Module is installed then the fields will also be included here as well in the broker profile.

	1x - items count line number for item from 1 to last number of user in category of broker on invoice.. 
	ie: {ACCOUNTS_1_UID}, {ACCOUNTS_2_UID}...

	{ACCOUNTS_x_UID} = $xoopsuser->getVar('uid')
	{ACCOUNTS_x_NAME} = $xoopsuser->getVar('name')
	{ACCOUNTS_x_UNAME} = $xoopsuser->getVar('uname')
	{ACCOUNTS_x_EMAIL} = $xoopsuser->getVar('email')
	{ACCOUNTS_x_URL} = $xoopsuser->getVar('url')
	{ACCOUNTS_x_USER_AVATA} = $xoopsuser->getVar('user_avatar')
	{ACCOUNTS_x_USER_REGDATE} = $xoopsuser->getVar('user_regdate')
	{ACCOUNTS_x_USER_ICQ} = $xoopsuser->getVar('user_icq')
	{ACCOUNTS_x_USER_FROM} = $xoopsuser->getVar('user_from')
	{ACCOUNTS_x_USER_SIG} = $xoopsuser->getVar('user_sig')
	{ACCOUNTS_x_USER_VIEWEMAIL} = $xoopsuser->getVar('user_viewemail')
	{ACCOUNTS_x_ACTKEY} = $xoopsuser->getVar('actkey')
	{ACCOUNTS_x_USER_AIM} = $xoopsuser->getVar('user_aim')
	{ACCOUNTS_x_USER_YIM} = $xoopsuser->getVar('user_yim')
	{ACCOUNTS_x_USER_MSNM} = $xoopsuser->getVar('user_msnm')
	{ACCOUNTS_x_PASS} = $xoopsuser->getVar('pass')
	{ACCOUNTS_x_POSTS} = $xoopsuser->getVar('posts')
	{ACCOUNTS_x_ATTACHSIG} = $xoopsuser->getVar('attachsig')
	{ACCOUNTS_x_RANK} = $xoopsuser->getVar('rank')
	{ACCOUNTS_x_LEVEL} = $xoopsuser->getVar('level')
	{ACCOUNTS_x_THEME} = $xoopsuser->getVar('theme')
	{ACCOUNTS_x_TIMEZONE_OFFSET} = $xoopsuser->getVar('timezone_offset')
	{ACCOUNTS_x_LAST_LOGIN} = $xoopsuser->getVar('last_login')
	{ACCOUNTS_x_UMODE} = $xoopsuser->getVar('umode')
	{ACCOUNTS_x_UORDER} = $xoopsuser->getVar('uorder')
	{ACCOUNTS_x_NOTIFY_METHOD} = $xoopsuser->getVar('notify_method')
	{ACCOUNTS_x_NOTIFY_MODE} = $xoopsuser->getVar('notify_mode')
	{ACCOUNTS_x_USER_OCC} = $xoopsuser->getVar('user_occ')
	{ACCOUNTS_x_BIO} = $xoopsuser->getVar('bio')
	{ACCOUNTS_x_USER_INTREST} = $xoopsuser->getVar('user_intrest')
	{ACCOUNTS_x_USER_MAILOK} = $xoopsuser->getVar('user_mailok')
	{ACCOUNTS_COUNT} = max(x);
	* If Profile Module is installed then the fields will also be included here as well in the accounts profile.

	1x - items count line number for item from 1 to last number of user in category of broker on invoice.. 
	ie: {OFFICERS_1_UID}, {OFFICERS_2_UID}...

	{OFFICERS_x_UID} = $xoopsuser->getVar('uid')
	{OFFICERS_x_NAME} = $xoopsuser->getVar('name')
	{OFFICERS_x_UNAME} = $xoopsuser->getVar('uname')
	{OFFICERS_x_EMAIL} = $xoopsuser->getVar('email')
	{OFFICERS_x_URL} = $xoopsuser->getVar('url')
	{OFFICERS_x_USER_AVATA} = $xoopsuser->getVar('user_avatar')
	{OFFICERS_x_USER_REGDATE} = $xoopsuser->getVar('user_regdate')
	{OFFICERS_x_USER_ICQ} = $xoopsuser->getVar('user_icq')
	{OFFICERS_x_USER_FROM} = $xoopsuser->getVar('user_from')
	{OFFICERS_x_USER_SIG} = $xoopsuser->getVar('user_sig')
	{OFFICERS_x_USER_VIEWEMAIL} = $xoopsuser->getVar('user_viewemail')
	{OFFICERS_x_ACTKEY} = $xoopsuser->getVar('actkey')
	{OFFICERS_x_USER_AIM} = $xoopsuser->getVar('user_aim')
	{OFFICERS_x_USER_YIM} = $xoopsuser->getVar('user_yim')
	{OFFICERS_x_USER_MSNM} = $xoopsuser->getVar('user_msnm')
	{OFFICERS_x_PASS} = $xoopsuser->getVar('pass')
	{OFFICERS_x_POSTS} = $xoopsuser->getVar('posts')
	{OFFICERS_x_ATTACHSIG} = $xoopsuser->getVar('attachsig')
	{OFFICERS_x_RANK} = $xoopsuser->getVar('rank')
	{OFFICERS_x_LEVEL} = $xoopsuser->getVar('level')
	{OFFICERS_x_THEME} = $xoopsuser->getVar('theme')
	{OFFICERS_x_TIMEZONE_OFFSET} = $xoopsuser->getVar('timezone_offset')
	{OFFICERS_x_LAST_LOGIN} = $xoopsuser->getVar('last_login')
	{OFFICERS_x_UMODE} = $xoopsuser->getVar('umode')
	{OFFICERS_x_UORDER} = $xoopsuser->getVar('uorder')
	{OFFICERS_x_NOTIFY_METHOD} = $xoopsuser->getVar('notify_method')
	{OFFICERS_x_NOTIFY_MODE} = $xoopsuser->getVar('notify_mode')
	{OFFICERS_x_USER_OCC} = $xoopsuser->getVar('user_occ')
	{OFFICERS_x_BIO} = $xoopsuser->getVar('bio')
	{OFFICERS_x_USER_INTREST} = $xoopsuser->getVar('user_intrest')
	{OFFICERS_x_USER_MAILOK} = $xoopsuser->getVar('user_mailok')
	{OFFICERS_COUNT} = max(x);
	* If Profile Module is installed then the fields will also be included here as well in the officers profile.

Item Hook Plugin Email Tags

	{ITEM_IIID} = $item->getVar('iiid')
	{ITEM_IID} = $item->getVar('iid')
	{ITEM_CAT} = $item->getVar('cat')
	{ITEM_NAME} = $item->getVar('name')
	{ITEM_AMOUNT} = $item->getVar('amount')
	{ITEM_QUANTITY} = $item->getVar('quantity')
	{ITEM_SHIPPING} = $item->getVar('shipping')
	{ITEM_HANDLING} = $item->getVar('handling')
	{ITEM_WEIGHT} = $item->getVar('weight')
	{ITEM_TAX} = $item->getVar('tax')
	{ITEM_DESCRIPTION} = $item->getVar('description')
	{ITEM_MODE} = $item->getVar('mode')
	{ITEM_CREATED} = $item->getVar('created')
	{ITEM_UPDATED} = $item->getVar('updated')
	{ITEM_ACTIONED} = $item->getVar('actioned')
	{ITEM_CREATED_DATETIME} = date(_DATESTRING, $item->getVar('created'))
	{ITEM_UPDATED_DATETIME} = date(_DATESTRING, $item->getVar('updated'))
	{ITEM_ACTIONED_DATETIME} = date(_DATESTRING, $item->getVar('actioned'))

Transaction Hook Plugin Email Tags
	
	{TRANSACTION_TIID} = $transaction->getVar('tiid')
	{TRANSACTION_IID} = $transaction->getVar('iid')
	{TRANSACTION_TRANSACTIONID} = $transaction->getVar('transactionid')
	{TRANSACTION_EMAIL} = $transaction->getVar('email')
	{TRANSACTION_INVOICE} = $transaction->getVar('invoice')
	{TRANSACTION_CUSTOM} = $transaction->getVar('custom')
	{TRANSACTION_STATUS} = $transaction->getVar('status')
	{TRANSACTION_DATE} = $transaction->getVar('date')
	{TRANSACTION_GROSS} = $transaction->getVar('gross')
	{TRANSACTION_FEE} = $transaction->getVar('fee')
	{TRANSACTION_SETTLE} = $transaction->getVar('settle')
	{TRANSACTION_EXCHANGERATE} = $transaction->getVar('exchangerate')
	{TRANSACTION_FIRSTNAME} = $transaction->getVar('firstname')
	{TRANSACTION_LASTNAME} = $transaction->getVar('lastname')
	{TRANSACTION_STREET} = $transaction->getVar('street')
	{TRANSACTION_CITY} = $transaction->getVar('city')
	{TRANSACTION_STATE} = $transaction->getVar('state')
	{TRANSACTION_POSTCODE} = $transaction->getVar('postcode')
	{TRANSACTION_COUNTRY} = $transaction->getVar('country')
	{TRANSACTION_ADDRESS_STATUS} = $transaction->getVar('address_status')
	{TRANSACTION_PAYER_EMAIL} = $transaction->getVar('payer_email')
	{TRANSACTION_PAYER_STATUS} = $transaction->getVar('payer_status')
	{TRANSACTION_PLUGIN} = $transaction->getVar('plugin')
	{TRANSACTION_MODE} = $transaction->getVar('mode')
	{TRANSACTION_DATE_DATETIME} = date(_DATESTRING, $transaction->getVar('date'))
	{GROSS} = $gross
	
Gateway Plugins

	It comes with the gateway for paypal but it is designed to have customised gateway plugins written for it so you 
	can have things like worldpay, 2c0, westpac any bank with a gateway or most credit card processing services work with it.
	
	There is the following folder classes
	
		/modules/xpayment 
		  |- class 
		       |- gateway
	
	The gateway class plugin belongs in a folder below the gateway class folder for example currently it looks like:
	
		/modules/xpayment 
		  |- class 
		  |     |- gateway 
		  |          |- paypal *4 
		  |                |- paypal.php *2 
		  |                |- gateway_info.php *3 
		  |- language 
		           |-english 
		                  |- paypal.php *1
		
		*1 paypal.php - is a language file, it must adopt the same name as the folder that contains it in the gateway 
		class folder.
		
		*2 paypal.php - is the main plugin file for running the gateway the class in it adopts names from the folder 
		(*4) and the extension of the the class name in this example is a class called PaypalGatewaysPlugin.
		
			- It has 5 functions that need to be in all plugins called goInvoiceObj, goActionCancel, goActionReturn, goIPN, 
			getPaymentHTML the rest are stratum functions in this example.
		
		*3 gateway_info.php - is the main information file for the gateway this includes fields for the gateway and options.
		(required)
		
		*4 paypal - is the containing folder for the plugin.
	
	If you where going to make a plugin for 2c0 lets say the folder structure would looks like:
	
		/modules/xpayment 
		  |- class 
		  |     |- gateway 
		  |          |- 2c0 *4 
		  |                |- 2c0.php *2 
		  |                |- gateway_info.php *3 
		  |- language 
		           |-english 
		                  |- 2c0.php *1
		
		*1 2c0.php - is a language file, it must adopt the same name as the folder that contains it in the gateway class folder.
		
		*2 2c0.php - is the main plugin file for running the gateway the class in it adopts names from the folder (*4)
		and the extension of the the class name in this example is a class called 2c0GatewaysPlugin.
		
			- It needs to have 5 functions that need to be in all plugins called goInvoiceObj, goActionCancel, goActionReturn,
			goIPN, getPaymentHTML the rest is upto the developer.
		
		*3 gateway_info.php - is the main information file for the gateway this includes fields for the gateway and options.
		(required)
		
		*4 2c0 - is the containing folder for the plugin.
	
	If you where going to make a plugin for WorldPay lets say the folder structure would looks like:
	
		/modules/xpayment 
		  |- class 
		  |     |- gateway 
		  |          |- worldpay*4 
		  |                |- worldpay.php *2 
		  |                |- gateway_info.php *3 
		  |- language 
		           |-english 
		                  |- worldpay.php *1
		
		*1 worldpay.php - is a language file, it must adopt the same name as the folder that contains it in the gateway class
		folder.
		
		*2 worldpay.php - is the main plugin file for running the gateway the class in it adopts names from the folder (*4)
		and the extension of the the class name in this example is a class called WorldpayGatewaysPlugin.
		
			- It needs to have 5 functions that need to be in all plugins called goInvoiceObj, goActionCancel, goActionReturn,
			goIPN, getPaymentHTML the rest is upto the developer.
		
		*3 gateway_info.php - is the main information file for the gateway this includes fields for the gateway and options.
		(required)
		
		*4 worldpay - is the containing folder for the plugin
