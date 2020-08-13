<?php
namespace QuickBooks\TransactionTypes;

/**
 * Class TransactionLine
 * @package QuickBooks
 */
class TransactionLine
{
    /**
     * @var string
     */
    public $trns;

    /**
     * @var string
     */
    public $trns_id;

    /**
     * @var string
     */
    public $trns_type;

    /**
     * @var string
     */
    public $date;

    /**
     * @var string
     */
    public $acct;

    /**
     * @var string
     */
    public $name;

    /**
     * @var string
     */
    public $class;

    /**
     * @var float
     */
    public $amount;

    /**
     * @var string
     */
    public $memo;

    /**
     * @var string
     */
    public $docnum;

    /**
     * Init the object
     *
     * @param $trns
     * @param null|string $trns_id      Optional ID
     * @param null|string $trns_type    Value in Transaction::$transaction_type
     * @param null|string $date         Transaction date
     * @param null|string $account      Account # from QuickBooks
     * @param null|string $name         Name from QuickBooks
     * @param null|string $class        Class from QuickBooks
     * @param null|float $amount        Dollar amount
     * @param null|string $memo         Note for QuickBooks
     * @param null|string $docnum       The number of the transaction. For checks, the number is the check number; for invoices, the number is the invoice number; etc.

     */
    public function __construct($trns, $trns_id = null, $trns_type = null, $date = null, $account = null, $name = null, $class = null, $amount = null, $memo = null, $docnum = null)
    {
        $this->setTrns($trns);
        $this->setTrnsId($trns_id);
        $this->setTrnsType($trns_type);
        $this->setName($name);
        $this->setDate($date);
        $this->setAcct($account);
        $this->setClass($class);
        $this->setAmount($amount);
        $this->setMemo($memo);
        $this->setDocNum($docnum);
    }

    /**
     * Return the object as an array
     *
     * @return array
     */
    public function toArray()
    {
        return (array) $this;
    }

    /**
     * @param $value
     */
    protected function setTrns($value)
    {
        $this->trns = $value;
    }

    /**
     * @return string
     */
    public function getTrns()
    {
        return $this->trns;
    }

    /**
     * @param $value
     */
    protected function setTrnsId($value)
    {
        $this->trns_id = $value;
    }

    /**
     * @return string
     */
    public function getTrnsId()
    {
        return $this->trns_id;
    }

    /**
     * @param $value
     */
    protected function setTrnsType($value)
    {
        if (in_array($value, Transaction::$transaction_types))
            $this->trns_type = $value;
        else
            $this->trns_type = null;
    }

    /**
     * @return string
     */
    public function getTrnsType()
    {
        return $this->trns_type;
    }

    /**
     * @param $value
     */
    protected function setDate($value)
    {
        $this->date = $value;
    }

    /**
     * @return string
     */
    public function getDate()
    {
        return $this->date;
    }

    /**
     * @param $value
     */
    protected function setAcct($value)
    {
        $this->acct = $value;
    }

    /**
     * @return string
     */
    public function getAcct()
    {
        return $this->acct;
    }

    /**
     * @param $value
     */
    protected function setName($value)
    {
        $this->name = $value;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param $value
     */
    protected function setClass($value)
    {
        $this->class = $value;
    }

    /**
     * @return string
     */
    public function getClass()
    {
        return $this->class;
    }

    /**
     * @param $value
     */
    protected function setAmount($value)
    {
        $this->amount = $value;
    }

    /**
     * @return float
     */
    public function getAmount()
    {
        return $this->amount;
    }

    /**
     * @param $value
     */
    protected function setMemo($value)
    {
        $this->memo = $value;
    }

    /**
     * @return string
     */
    public function getMemo()
    {
        return $this->memo;
    }

    /**
     * @param $value
     */
    protected function setDocNum($value)
    {
        $this->docnum = $value;
    }

    /**
     * @return string
     */
    public function getDocNum()
    {
        return $this->docnum;
    }
}