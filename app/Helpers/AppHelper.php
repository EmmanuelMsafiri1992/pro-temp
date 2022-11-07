<?php

namespace App\Helpers;

use App\Models\GeneralSetting;

class AppHelper
{
    public $allSettings;
    function __construct()
    {
        $this->allSettings = GeneralSetting::get();
    }
    // get app general setting
    public function getGeneralSettigns()
    {
        $settings = [
            'companyName' => $this->allSettings->where('key', 'company_name')->first()->value,
            'currencyName' => $this->allSettings->where('key', 'currency_name')->first()->value,
            'compnayTagline' => $this->allSettings->where('key', 'compnay_tagline')->first()->value,
            'logo' => asset('img') . '/' . $this->allSettings->where('key', 'logo')->first()->value,
            'currencySymbol' => $this->allSettings->where('key', 'currency_symbol')->first()->value,
            'currencyPosition' => $this->allSettings->where('key', 'currency_position')->first()->value,
            //'timezone' => $this->allSettings->where('key', 'timezone')->first()->value,
            'codePefix' => $this->allSettings->where('key', 'purchase_code_prefix')->first()->value,
            'processingCodePefix' => $this->allSettings->where('key', 'processing_code_prefix')->first()->value,
            'finishedCodePefix' => $this->allSettings->where('key', 'finished_code_prefix')->first()->value,
            'transferredCodePrefix' => $this->allSettings->where('key', 'transferred_code_prefix')->first()->value,
        ];
        $settings = (object) $settings;
        return $settings;
    }

    // return formatted currency
    public function formattedCurrency($amount)
    {
        if ($this->getGeneralSettigns()->currencyPosition == 'left') {
            return $amount > 0 ? $this->getGeneralSettigns()->currencySymbol . $amount : $this->getGeneralSettigns()->currencySymbol . "0";
        } else {
            return $amount > 0 ? $amount . $this->getGeneralSettigns()->currencySymbol : "0" . $this->getGeneralSettigns()->currencySymbol;
        }
    }

    // return discount amount
    public function discountAmount($discount, $subtotal)
    {
        return ($discount / 100) * $subtotal;
    }

    // return purchase code
    public function pruchaseCode($code)
    {
        return $this->getGeneralSettigns()->codePefix . $code;
    }

    // return processing code
    public function processingCode($code)
    {
        return $this->getGeneralSettigns()->processingCodePefix . $code;
    }

    // return finished code
    public function finishedCode($code)
    {
        return $this->getGeneralSettigns()->finishedCodePefix . $code;
    }

    // return transferred code
    public function transferredCode($code)
    {
        return $this->getGeneralSettigns()->transferredCodePrefix . $code;
    }

    public function startQueryLog()
    {
        \DB::enableQueryLog();
    }

    public function showQueries()
    {
        dd(\DB::getQueryLog());
    }

    public static function instance()
    {
        return new AppHelper();
    }
}
