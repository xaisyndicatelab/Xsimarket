<?php

namespace Xsimarket\Payments;

use Xsimarket\Database\Models\Settings;

abstract class Base
{
  public $currency;

  public function __construct()
  {
    $settings = Settings::first();
    $this->currency = $settings->options['currency'];
  }
}
