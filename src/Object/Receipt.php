<?php

declare(strict_types=1);

namespace SSitdikov\ATOL\Object;

/**
 * Class Receipt
 * @package SSitdikov\ATOL\Object
 */
class Receipt implements \JsonSerializable
{

    private $sno = ReceiptSno::RECEIPT_SNO_USN_INCOME;

    /**
     * @var string
     */
    private $email = '';

    /**
     * @var string
     */
    private $phone = '';

    /**
     * @var array
     */
    private $items = [];

    /**
     * @var array
     */
    private $payments = [];

    /**
     * @var float
     */
    private $total = 0.0;

    /**
     * @return array
     */
    public function jsonSerialize(): array
    {
        return [
            'attributes' => [
                'sno' => $this->getSno(),
                'email' => $this->getEmail(),
                'phone' => $this->getPhone()
            ],
            'items' => $this->getItems(),
            'total' => $this->getTotal(),
            'payments' => $this->getPayments()
        ];
    }

    /**
     * @return string
     */
    public function getSno(): string
    {
        return $this->sno;
    }

    /**
     * @param string $sno
     * @return Receipt
     */
    public function setSno(string $sno): Receipt
    {
        $this->sno = $sno;

        return $this;
    }

    /**
     * @return string
     */
    public function getEmail(): string
    {
        return $this->email;
    }

    /**
     * @param string $email
     * @return Receipt
     */
    public function setEmail(string $email): Receipt
    {
        $this->email = $email;

        return $this;
    }

    /**
     * @return string
     */
    public function getPhone(): string
    {
        return $this->phone;
    }

    /**
     * @param string $phone
     * @return Receipt
     */
    public function setPhone(string $phone): Receipt
    {
        $this->phone = $phone;

        return $this;
    }

    /**
     * @return array
     */
    public function getItems(): array
    {
        return $this->items;
    }

    /**
     * @param array $items
     * @return Receipt
     */
    public function setItems(array $items): Receipt
    {
        foreach ($items as $element) {
            $this->addItem($element);
        }

        return $this;
    }

    /**
     * @return array
     */
    public function getPayments(): array
    {
        return $this->payments;
    }

    /**
     * @param array $payments
     * @return Receipt
     */
    public function setPayments(array $payments): Receipt
    {
        $this->payments = $payments;

        return $this;
    }

    /**
     * @return float
     */
    public function getTotal(): float
    {
        return $this->total;
    }

    /**
     * @param Item $item
     * @return $this
     */
    public function addItem(Item $item): Receipt
    {
        $this->items[] = $item;
        $this->addTotal($item->getSum());

        return $this;
    }

    /**
     * @param float $sum
     */
    private function addTotal($sum): void
    {
        $this->total += $sum;
    }

    /**
     * @param Payment $payment
     * @return $this
     */
    public function addPayment(Payment $payment): Receipt
    {
        $this->payments[] = $payment;

        return $this;
    }
}
