

   # quickbooks
A small package used with Laravel to store transactions to be exported into QuickBooks. It was used with Laravel 4.2 but could easily but updated for a later version or modified to be used outside of Laravel. It can be expanded to include more QuickBooks activity but I just needed IIF exports for specific records in the database.

The qb_ledger_accruals table was used to reconcile exports from the application that made it into QuickBooks and allow the accrual batches to be exported again at a later time if needed.

## Set DOCNUM Format
This package incremented a specific number format used for the Entry No. You can set it here:
```php
LedgerAccrual::DOC_NUM_FORMAT
```

## Standarize Memo Format
This package uses a set format for the memo filled in by sprintf(). You can set it here:
```php
LedgerAccrual::MEMO_FORMAT
```

## Accrual Example

```php
    <?php 
    /*
    * Example - create a new accrual
    */
    $accrualEntity = QuickBooks::newAccrual();
    
    // Batch num is a reference for the entire batch.
    $accrualEntity->batch_num = QuickBooks::getLastLedgerBatchNum() + 1;
    
    // QuickBooks DOCNUM, aka Entry No. 
    // You can get the last one you used and increment it
    $accrualEntity->doc_num = QuickBooks::getLastLedgerDocNum() +1;
    
    // Reference your system user creating this accrual
    $accrualEntity->user_id = $user_id;
    
    // QuickBooks TRNSTYPE
    $accrualEntity->trns_type = 'GENERAL JOURNAL';
    
    // QuickBooks NAME
    $accrualEntity->name = 'Invoice with Sales Tax';
    
    // QuickBooks CLASS
    $accrualEntity->class = 'retail';

    // Amount - debit will be negative
    $accrualEntity->amount = -100.00;

    // Date - stored as a SQL date
    $accrualEntity->date = '1985-10-21;
	
    // QuickBooks debited Account
    $accrualEntity->account = '24680';
	
    // A memo
    // Format was 'Site-%s | Project-%s | Milestone-%s | Complete Date-%s | Price-%01.2f | PO-%s | POLI-%s'
    $accrualEntity->memo = sprintf(
            QuickBooks::getAccrualMemoFormat(),
            'SiteABC123',
            'Special Project,
            'Cabinet Install',
            '10/21/1985',
            100.00,
            '12345678',
            '12'
        );;

    // Record saved
    $accrualEntity->save();

    /*
    * Replicate the previous debit accrual and create a matching credit accrual
    * Update the accruing account and reverse the amount
    */
    $accrualOrg = $accrualEntity->replicate();

    // Same amount, reversed for credit
    $accrualOrg->amount = 100.00;

    // QuickBooks credited Account
    $accrualOrg->account = '13579';

    // Record saved
    $accrualOrg->save();
```

## Export Example
```php
    <?php
    /* 
    * Generate the IIF file to import
    * You'll need the batch number you want to export
    */
    $batch = QuickBooks::getLedgerBatch($batch_num);
    
    /*
    * Note you can create separate transactions with your data. 
    * Group them by date, or account, or some other criteria.
    * The export can contain multiple transactions.
    * This example puts the whole batch in one transaction.
    */
    
    // Start a new Transaction
    $transaction  = QuickBooks::newTransaction();  
  
    // Loop through the data in your batch and add each row as a TransactionLine
    foreach ($batch as $data)  {  
      // Convert to DateTime so it can be correctly formatted  
      $date = new DateTime(date(QuickBooks::getAccrualDateFormat(), strtotime($data['date'])));  
      
      // Create a line  
      $transactionLine = QuickBooks::newTransactionLine(  
        $transaction->getStartLine(),  
        $data['trans_id'],  
        $data['trns_type'],  
        $date,  
        $data['account'],  
        $data['name'],  
        $data['class'],  
        $data['amount'],  
        $data['doc_num'],  
        $data['memo']  
     );  
     
      // Add the line  to the Transaction
      $transaction->setLine($transactionLine);  
      
      // Store the Transaction  
      $transactions[] = $transaction->getLines();  
    }  
      
    // Convert Transaction data to an iif file  
    $response = QuickBooks::exportIif($transactions, 'Accrued_Revenue_Batch_' . $batch_num);

    return  Response::download($response['file']);

		
