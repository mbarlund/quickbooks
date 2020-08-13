<?php
namespace QuickBooks\TransactionTypes;

/**
 * Class Transaction
 * @package QuickBooks\TransactionTypes
 */
class Transaction
{
    /**
     * int
     */
    const TRANSACTION = 0;

    /**
     * int
     */
    const DISTRIBUTION = 1;

    /**
     * int
     */
    const END_TRANSACTION = -1;

    /**
     * @var array
     */
    static $trns_fields = ['TRNS', 'TRNSID', 'TRNSTYPE', 'DATE', 'ACCNT', 'NAME', 'CLASS', 'AMOUNT', 'DOCNUM', 'MEMO'];

    /**
     * @var array
     */
    static $dist_fields = ['SPL', 'SPLID', 'TRNSTYPE', 'DATE', 'ACCNT', 'NAME', 'CLASS', 'AMOUNT', 'DOCNUM', 'MEMO'];

    /**
     * @var string
     */
    static $end_trns = 'ENDTRNS';

    /**
     * Available transaction types
     * @var array
     */
    static $transaction_types = [
        'BEGINBALCHECK',
        'BILL',
        'BILL REFUND',
        'CASH REFUND',
        'CASH SALE',
        'CCARD REFUND',
        'CHECK',
        'CREDIT CARD',
        'CREDIT MEMO',
        'DEPOSIT',
        'ESTIMATES',
        'GENERAL JOURNAL',
        'INVOICE',
        'PAYMENT',
        'PURCHORD',
        'TRANSFER',
    ];

    /**
     * @var array
     */
    static $headers = [];

    /**
     * @var array
     */
    public $lines = [];

    /**
     * Get the complete transaction
     *
     * @return array
     */
    public function getTransaction()
    {
        return array_merge(self::getHeaders(count($this->getLines())), $this->getLines());
    }

    /**
     * Get the transaction's headers
     *
     * @param int $line_count
     * @return array
     */
    static function getHeaders($line_count = 3)
    {
        // Reset headers
        self::$headers = [];

        // Add initial header
        self::addTransactionHeader(self::TRANSACTION);

        // If we have more than a TRNS and ENDTRNS row add the DIST header
        if ($line_count > 2)
            self::addTransactionHeader(self::DISTRIBUTION);

        // Add the end header
        self::addTransactionHeader(self::END_TRANSACTION);

        return self::$headers;
    }

    /**
     * Get the transaction's lines
     * @return array
     */
    public function getLines()
    {
        return $this->lines;
    }

    /**
     * Get the row's keyword
     * TRNS or SPL
     *
     * @return string
     */
    public function getStartLine()
    {
        // If there are no lines yet, use TRNS, else use SPL
        return count($this->lines) == 0 ? self::$trns_fields[0] : self::$dist_fields[0];
    }

    /**
     * Add a line to the Transaction
     *
     * @param TransactionLine $transactionLine
     */
    public function setLine(TransactionLine $transactionLine)
    {
        // Remove the END line so more lines can be added
        if (count($this->lines) > 0 && in_array(self::$end_trns, end($this->lines)))
            array_pop($this->lines);

        // Add the Line
        $this->lines[] = $transactionLine->toArray();

        // Add the END line
        $this->setEndLine();
    }

    /**
     * Add the END line
     *
     * @return void
     */
    public function setEndLine()
    {
        $EndLine = new TransactionLine(self::$end_trns);
        $this->lines[] = $EndLine->toArray();
    }

    /**
     * Add a header row to the array
     * Either a !TRNS, !SPL, or !ENDTRNS
     *
     * @param $type
     * @return void
     */
    static function addTransactionHeader($type)
    {
        switch ($type)
        {
            case self::TRANSACTION:
                // All of the column heads for TRNS
                $headerRow = self::$trns_fields;
                break;

            case self::DISTRIBUTION:
                // All of the column headers for SPL
                $headerRow = self::$dist_fields;
                break;

            case self::END_TRANSACTION:
                // Only the ENDTRNS string, but make it an array ['ENDTRNS']
                $headerRow = (array) self::$end_trns;
                break;

            default:
                // We shouldn't be here
                $headerRow = null;
        }

        // If we have a valid header row we can continue
        if ($headerRow)
        {
            // Prepend an "!" to the string in the first column
            $headerRow[0] = '!' . $headerRow[0];

            // Add the row to the array
            self::$headers[] = $headerRow;
        }
    }
}