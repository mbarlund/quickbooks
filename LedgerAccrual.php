<?php
namespace QuickBooks;

// Extend Laravel Eloquent Model
use Illuminate\Database\Eloquent\Model as Eloquent;

/**
 * Class LedgerAccrual
 * @package QuickBooks
 */
class LedgerAccrual extends Eloquent
{
    protected $fillable = [
        'batch_num',
        'doc_num',
        'user_id',
        'trns_type',
        'name',
        'class',
        'amount',
        'date',
        'account',
        'memo'
    ];

    /**
     * @var string
     */
    protected $table = 'qb_ledger_accruals';

    /**
     * sprintf() pattern
     */
    const MEMO_FORMAT = 'Site-%s | Project-%s | Milestone-%s | Complete Date-%s | Price-%01.2f | PO-%s | POLI-%s';

    /**
     * date string
     */
    const DATE_FORMAT = 'm/d/y';

    /**
     * Entry number string
     */
    const DOC_NUM_FORMAT = 'ACR-0000000';

    /**
     * @return int
     */
    static function getLastBatchNum()
    {
        return LedgerAccrual::max('batch_num');
    }

    /**
     * @return string
     */
    static function getLastDocNum()
    {
        return LedgerAccrual::max('doc_num');
    }
}