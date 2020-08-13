<?php
namespace QuickBooks;

// Extend Laravel Eloquent Model
use Illuminate\Database\Eloquent\Model as Eloquent;

/**
 * Class QuickBooksAccount
 * @package QuickBooks
 */
class QuickBooksAccount extends Eloquent
{
    protected $fillable = [
        'accountable_type',
        'accountable_id',
        'account',
        'name',
        'class'
    ];

    protected $table = 'qb_accounts';

    /**
     * date string
     */
    const DATE_FORMAT = 'm/d/y';

    /**
     * Accrued revenue string
     */
    const ACCRUED_REV = 'Accrued Revenue';

    /**
     * Accrued expenses string
     */
    const ACCRUED_EXP = 'Accrued Expenses';

    /**
     * Get the owning accountable model.
     */
    public function accountable()
    {
        return $this->morphTo();
    }
}