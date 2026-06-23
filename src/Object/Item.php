<?php

declare(strict_types=1);

namespace SSitdikov\ATOL\Object;

/**
 * Class Item.
 *
 * @package SSitdikov\ATOL\Object
 */
class Item implements \JsonSerializable
{

    /**
     * О реализуемом товаре, за исключением подакцизного товара и товара, подлежащего маркировке средствами идентификации
     */
    public const PAYMENT_OBJECT_COMMODITY = 1;

    /**
     * О реализуемом подакцизном товаре, за исключением товара, подлежащего маркировке средствами идентификации
     */
    public const PAYMENT_OBJECT_EXCISE = 2;

    /**
     * О выполняемой работе
     */
    public const PAYMENT_OBJECT_JOB = 3;

    /**
     * Об оказываемой услуге
     */
    public const PAYMENT_OBJECT_SERVICE = 4;

    /**
     * О приеме ставок при осуществлении деятельности по проведению азартных игр
     */
    public const PAYMENT_OBJECT_GAMBLING_BET = 5;

    /**
     * О выплате денежных средств в виде выигрыша при осуществлении деятельности по проведению азартных игр
     */
    public const PAYMENT_OBJECT_GAMBLING_PRIZE = 6;

    /**
     * О приеме денежных средств при реализации лотерейных билетов, электронных лотерейных билетов, приеме лотерейных ставок
     */
    public const PAYMENT_OBJECT_LOTTERY = 7;

    /**
     * О выплате денежных средств в виде выигрыша при осуществлении деятельности по проведению лотерей
     */
    public const PAYMENT_OBJECT_LOTTERY_PRIZE = 8;

    /**
     * О предоставлении прав на использование результатов интеллектуальной деятельности или средств индивидуализации
     */
    public const PAYMENT_OBJECT_INTELLECTUAL_ACTIVITY = 9;

    /**
     * Об авансе, задатке, предоплате, кредите
     */
    public const PAYMENT_OBJECT_PAYMENT = 10;

    /**
     * О вознаграждении пользователя, являющегося платежным агентом (субагентом), банковским платежным агентом (субагентом), комиссионером, поверенным или иным агентом
     */
    public const PAYMENT_OBJECT_AGENT_COMMISSION = 11;

    /**
     * О взносе в счет оплаты, пени, штрафе, вознаграждении, бонусе и ином аналогичном предмете расчета
     */
    public const PAYMENT_OBJECT_COMPOSITE = 12;

    /**
     * О предмете расчета, не относящемуся к предметам расчета, которым может быть присвоено значение от «1» до «11» и от «14» до «26»
     */
    public const PAYMENT_OBJECT_ANOTHER = 13;

    /**
     * О передаче имущественных прав
     */
    public const PAYMENT_OBJECT_PROPERTY_RIGHT = 14;

    /**
     * О внереализационном доходе
     */
    public const PAYMENT_OBJECT_NON_OPERATING_GAIN = 15;

    /**
     * О суммах расходов, платежей и взносов, уменьшающих сумму налога
     */
    public const PAYMENT_OBJECT_TAX_REDUCTION = 16;

    /**
     * О суммах уплаченного торгового сбора
     */
    public const PAYMENT_OBJECT_SALES_TAX = 17;

    /**
     * Туристический налог
     */
    public const PAYMENT_OBJECT_RESORT_FEE = 18;

    /**
     * О залоге
     */
    public const PAYMENT_OBJECT_COLLATERAL = 19;

    /**
     * О суммах произведенных расходов, уменьшающих доход
     */
    public const PAYMENT_OBJECT_EXPENSES_REDUCTION = 20;

    /**
     * О страховых взносах на обязательное пенсионное страхование, уплачиваемых ИП, не производящими выплаты и иные вознаграждения физическим лицам
     */
    public const PAYMENT_OBJECT_INSURANCE_PREMIUM_IP_PENSION = 21;

    /**
     * О страховых взносах на обязательное пенсионное страхование, уплачиваемых организациями и ИП, производящими выплаты и иные вознаграждения физическим лицам
     */
    public const PAYMENT_OBJECT_INSURANCE_PREMIUM_ORG_PENSION = 22;

    /**
     * О страховых взносах на обязательное медицинское страхование, уплачиваемых ИП, не производящими выплаты и иные вознаграждения физическим лицам
     */
    public const PAYMENT_OBJECT_INSURANCE_PREMIUM_IP_MEDICAL = 23;

    /**
     * О страховых взносах на обязательное медицинское страхование, уплачиваемые организациями и ИП, производящими выплаты и иные вознаграждения физическим лицам
     */
    public const PAYMENT_OBJECT_INSURANCE_PREMIUM_ORG_MEDICAL = 24;

    /**
     * О страховых взносах на обязательное социальное страхование
     */
    public const PAYMENT_OBJECT_INSURANCE_PREMIUM_SOCIAL = 25;

    /**
     * О приеме и выплате денежных средств при осуществлении казино и залами игровых автоматов расчетов с использованием обменных знаков игорного заведения
     */
    public const PAYMENT_OBJECT_GAMBLING_EXCHANGE = 26;

    /**
     * О выдаче денежных средств банковским платежным агентом
     */
    public const PAYMENT_OBJECT_BANK_AGENT_PAYOUT = 27;

    /**
     * О реализуемом подакцизном товаре, подлежащем маркировке средством идентификации, не имеющем кода маркировки
     */
    public const PAYMENT_OBJECT_EXCISE_MARKED_WITHOUT_CODE = 30;

    /**
     * О реализуемом подакцизном товаре, подлежащем маркировке средством идентификации, имеющем код маркировки
     */
    public const PAYMENT_OBJECT_EXCISE_MARKED_WITH_CODE = 31;

    /**
     * О реализуемом товаре, подлежащем маркировке средством идентификации, имеющем код маркировки, за исключением подакцизного товара
     */
    public const PAYMENT_OBJECT_MARKED_WITH_CODE = 33;

    /**
     *  предоплата 100%. Полная предварительная оплата до момента передачи предмета расчета
     */
    public const PAYMENT_METHOD_FULL_PREPAYMENT = 'full_prepayment';

    /**
     * предоплата. Частичная предварительная оплата до момента передачи предмета расчета
     */
    public const PAYMENT_METHOD_PREPAYMENT = 'prepayment';

    /**
     * аванс
     */
    public const PAYMENT_METHOD_ADVANCE = 'advance';

    /**
     * полный расчет. Полная оплата, в том числе с учетом аванса (предварительной оплаты) в момент передачи предмета расчета.
     */
    public const PAYMENT_METHOD_FULL_PAYMENT = 'full_payment';

    /**
     *  частичный расчет и кредит. Частичная оплата предмета расчета в момент его передачи с последующей оплатой в кредит.
     */
    public const PAYMENT_METHOD_PARTIAL_PAYMENT = 'partial_payment';

    /**
     * передача в кредит. Передача предмета расчета без его оплаты в момент его передачи с последующей оплатой в кредит
     */
    public const PAYMENT_METHOD_CREDIT = 'credit';

    /**
     * оплата кредита. Оплата предмета расчета после его передачи с оплатой в кредит (оплата кредита)
     */
    public const PAYMENT_METHOD_CREDIT_PAYMENT = 'credit_payment';

    /**
     * Применяется для предметов расчета, которые могут быть реализованы поштучно или единицами
     */
    public const MEASURE_UNIT_PIECE = 0;

    /**
     * Грамм
     */
    public const MEASURE_UNIT_GRAM = 10;

    /**
     * Килограмм
     */
    public const MEASURE_UNIT_KILOGRAM = 11;

    /**
     * Тонна
     */
    public const MEASURE_UNIT_TON = 12;

    /**
     * Сантиметр
     */
    public const MEASURE_UNIT_CENTIMETER = 20;

    /**
     * Дециметр
     */
    public const MEASURE_UNIT_DECIMETER = 21;

    /**
     * Метр
     */
    public const MEASURE_UNIT_METER = 22;

    /**
     * Квадратный сантиметр
     */
    public const MEASURE_UNIT_SQUARE_CENTIMETER = 30;

    /**
     * Квадратный дециметр
     */
    public const MEASURE_UNIT_SQUARE_DECIMETER = 31;

    /**
     * Квадратный метр
     */
    public const MEASURE_UNIT_SQUARE_METER = 32;

    /**
     * Миллилитр
     */
    public const MEASURE_UNIT_MILLILITER = 40;

    /**
     * Литр
     */
    public const MEASURE_UNIT_LITER = 41;

    /**
     * Кубический метр
     */
    public const MEASURE_UNIT_CUBIC_METER = 42;

    /**
     * Киловатт час
     */
    public const MEASURE_UNIT_KILOWATT_HOUR = 50;

    /**
     * Гигакалория
     */
    public const MEASURE_UNIT_GIGACALORIE = 51;

    /**
     * Сутки (день)
     */
    public const MEASURE_UNIT_DAY = 70;

    /**
     * Час
     */
    public const MEASURE_UNIT_HOUR = 71;

    /**
     * Минута
     */
    public const MEASURE_UNIT_MINUTE = 72;

    /**
     * Секунда
     */
    public const MEASURE_UNIT_SECOND = 73;

    /**
     * Килобайт
     */
    public const MEASURE_UNIT_KILOBYTE = 80;

    /**
     * Мегабайт
     */
    public const MEASURE_UNIT_MEGABYTE = 81;

    /**
     * Гигабайт
     */
    public const MEASURE_UNIT_GIGABYTE = 82;

    /**
     * Терабайт
     */
    public const MEASURE_UNIT_TERABYTE = 83;

    /**
     * Применяется при использовании иных единиц измерения
     */
    public const MEASURE_UNIT_OTHER = 255;

    private $sum = 0.0;

    private $vat = null;

    private $name = '';

    private $price = 0.0;

    private $quantity = 1.0;

    /**
     * @var string $payment_object Признак предмета расчета
     */

    private $payment_object = 1;
    /**
     * @var string $payment_method Признак способа расчета
     */
    private $payment_method = 'full_payment';
    /**
     * @var int $measure Единица измерения предмета расчета
     */
    private $measure = 0;

    /**
     * Информация об агенте
     *
     * @var AgentInfo
     */
    private $agent_info;

    /**
     * Информация о поставщике
     *
     * @var SupplierInfo
     */
    private $supplier_info;


    /**
     * Продаваемый товар по чеку.
     *
     * @param  string  $name
     * @param  float   $price
     * @param  float   $quantity
     * @param  Vat     $vat
     * @param  string  $payment_object
     * @param  string  $payment_method
     */
    public function __construct($name, $price, $quantity, $vat, $payment_object = 'commodity', $payment_method = 'full_payment')
    {
        $this->setName($name);
        $this->setPrice($price);
        $this->setQuantity($quantity);
        $this->setVat($vat);
        $this->setSum(round($price * $quantity, 2));
        $this->setPaymentObject($payment_object);
        $this->setPaymentMethod($payment_method);
    }


    /**
     * @return array
     */
    public function jsonSerialize(): array
    {
        return array_filter([
            'name'              => $this->getName(),
            'price'             => $this->getPrice(),
            'quantity'          => $this->getQuantity(),
            'sum'               => $this->getSum(),
            'vat'               => $this->getVat(),
            'payment_object'    => $this->getPaymentObject(),
            'payment_method'    => $this->getPaymentMethod(),
            'measurement_unit'  => $this->getMeasurementUnit(),
            'agent_info'        => $this->getAgentInfo(),
            'supplier_info'     => $this->getSupplierInfo(),
        ], function ($property) {
            return !is_null($property);
        });
    }


    /**
     * @return AgentInfo
     */
    public function getAgentInfo()
    {
        return $this->agent_info;
    }


    /**
     * @param AgentInfo $agent_info
     *
     * @return Item
     */
    public function setAgentInfo(AgentInfo $agent_info): self
    {
        $this->agent_info = $agent_info;
        return $this;
    }


    /**
     * @return SupplierInfo
     */
    public function getSupplierInfo()
    {
        return $this->supplier_info;
    }


    /**
     * @param SupplierInfo $supplier_info
     *
     * @return Item
     */
    public function setSupplierInfo(SupplierInfo $supplier_info): self
    {
        $this->supplier_info = $supplier_info;
        return $this;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }


    /**
     * @param  string  $name
     *
     * @return Item
     */
    public function setName(string $name): self
    {
        $this->name = $name;
        return $this;
    }


    /**
     * @return float
     */
    public function getPrice(): float
    {
        return $this->price;
    }


    /**
     * @param  float  $price
     *
     * @return Item
     */
    public function setPrice(float $price): self
    {
        $this->price = $price;
        return $this;
    }


    /**
     * @return float
     */
    public function getQuantity(): float
    {
        return $this->quantity;
    }


    /**
     * @param  float  $quantity
     *
     * @return Item
     */
    public function setQuantity(float $quantity): self
    {
        $this->quantity = $quantity;
        return $this;
    }

    /**
     * @return Vat|null
     */
    public function getVat()
    {
        return $this->vat;
    }

    /**
     * @param Vat $vat
     */
    public function setVat(Vat $vat): self
    {
        $this->vat = $vat;
        return $this;
    }


    /**
     * @return float
     */
    public function getSum(): float
    {
        return $this->sum;
    }


    /**
     * @param  float  $sum
     *
     * @return Item
     */
    public function setSum(float $sum): self
    {
        $this->sum = $sum;
        return $this;
    }


    /**
     * @return string
     */
    public function getPaymentObject(): string
    {
        return $this->payment_object;
    }


    /**
     * @param  string  $payment_object
     *
     * @return Item
     */
    public function setPaymentObject(string $payment_object): self
    {
        $this->payment_object = $payment_object;
        return $this;
    }


    /**
     * @return string
     */
    public function getPaymentMethod(): string
    {
        return $this->payment_method;
    }


    /**
     * @param  string  $payment_method
     *
     * @return Item
     */
    public function setPaymentMethod(string $payment_method): self
    {
        $this->payment_method = $payment_method;
        return $this;
    }


    /**
     * @return string
     */
    public function getMeasurementUnit(): int
    {
        return $this->measure;
    }


    /**
     * @param  string  $measure
     *
     * @return Item
     */
    public function setMeasurementUnit(int $measure): self
    {
        $this->measure = $measure;
        return $this;
    }
}
