<?php
namespace QuickBooks;

use QuickBooks\TransactionTypes\Transaction;
use QuickBooks\TransactionTypes\TransactionLine;

/**
 * Class QuickBooks
 * @package QuickBooks
 */
class QuickBooks
{
    /**
     * File name space
     * @string
     */
    const FILENAME_SPACE = '_';

    /**
     * IIF extension
     * @string
     */
    const IIF_EXT = '.iif';

    /**
     * @return string
     */
    static function getAccruedRevField()
    {
        return QuickBooksAccount::ACCRUED_REV;
    }

    /**
     * @return string
     */
    static function getAccrualMemoFormat()
    {
        return LedgerAccrual::MEMO_FORMAT;
    }

    /**
     * @return string
     */
    static function getAccrualDateFormat()
    {
        return LedgerAccrual::DATE_FORMAT;
    }

    /**
     * @return string
     */
    static function getAccrualDocNumFormat()
    {
        return LedgerAccrual::DOC_NUM_FORMAT;
    }

    /**
     * @return int
     */
    static function getLastLedgerBatchNum()
    {
        return LedgerAccrual::getLastBatchNum();
    }

    /**
     * @return string
     */
    static function getLastLedgerDocNum()
    {
        return LedgerAccrual::getLastDocNum();
    }

    /**
     * @return LedgerAccrual
     */
    static function newAccrual()
    {
        return new LedgerAccrual();
    }

    /**
     * @param $batch_num
     * @return mixed
     */
    static function getLedgerBatch($batch_num)
    {
        return LedgerAccrual::where('batch_num', $batch_num)->get();
    }

    /**
     * @return Transaction
     */
    static function newTransaction()
    {
        return new Transaction();
    }

    /**
     * @param $start
     * @param $trans_id
     * @param $trns_type
     * @param $date
     * @param $account
     * @param $name
     * @param $class
     * @param $amount
     * @param $doc_num
     * @param $memo
     * @return TransactionLine
     */
    static function newTransactionLine($start, $trans_id, $trns_type, $date, $account, $name, $class, $amount, $doc_num, $memo)
    {
        return new TransactionLine(
            $start,
            $trans_id,
            $trns_type,
            $date->format(self::getAccrualDateFormat()),
            $account,
            $name,
            $class,
            $amount,
            $doc_num,
            $memo
        );
    }

    /**
     * Add data to a tab delimited file
     *
     * @param array         $transactions is an array of Transaction
     * @param string        $prefix A prefix for the file to be returned
     * @return array        [success, message, file]
     */
    static function exportIif(array $transactions, $prefix = 'QuickBooks_Import')
    {
        try
        {
            // Make a file name
            $filename = $prefix . self::FILENAME_SPACE . date('Y-m-d-h-i-s') . self::IIF_EXT;

            // Create a temporary file
            $file = sys_get_temp_dir() . '/' . $filename;

            // Open file for writing
            $fp = fopen($file, 'w');

            // delimiter and enclosure for fputcsv()
            $delimiter = "\t";
            $enclosure = "\"";

            // Insert headers
            foreach (Transaction::getHeaders() as $header)
                fputcsv($fp, $header, "\t");

            // Loop through data, inserting each array into the
            foreach ($transactions as $transaction)
                foreach ($transaction as $data)
                    fputcsv($fp, $data, $delimiter, $enclosure);

            fclose($fp);

            /*
             * fputcsv() requires a field enclosure
             * QuickBooks does not accept one so we strip them out here.
             */
            $content = file_get_contents($file);
            $clean_content = str_replace($enclosure, '', $content);
            file_put_contents($file, $clean_content);

            // Return file
            return ['success' => true, 'message' => 'File created', 'file' => $file];
        }
        catch(\Exception $e)
        {
            \Log::error($e);
            return ['success' => false, 'message' => $e->getMessage(), 'file' => null];
        }
    }
}