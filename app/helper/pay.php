<?php
 function sendMoneyToVoucher($Amount)
{
    $Payer_Account = config('perfect.Payer_Account ');
    $AccountID = config('perfect.AccountID');
    $PassPhrase = urlencode(config('perfect.PassPhrase'));
    $opts = array(
        'socket' => array(
            'bindto' => '0.0.0.0:0',
        )
    );
    $context = stream_context_create($opts);
    $voucher = http::get("https://perfectmoney.com/acct/ev_create.asp?AccountID=".$AccountID."&PassPhrase=".$PassPhrase."&Payer_Account=".$Payer_Account."&Amount=".$Amount)->body();
    $dom = new \DOMDocument();
    $dom->loadHTML($voucher);
    $output = [];
    $element = $dom->getElementsByTagName("input");
    array_push($output, $element[0]->getAttribute('value'));
    array_push($output, $element[1]->getAttribute('value'));
    array_push($output, $element[2]->getAttribute('value'));
    array_push($output, $element[3]->getAttribute('value'));
    array_push($output, $element[4]->getAttribute('value'));
    array_push($output, $element[5]->getAttribute('value'));
    return $output;
}
function ActiveVoucher($ev_number, $ev_code)
{
    $Payer_Account = config('perfect.Payer_Account ');
    $AccountID = config('perfect.AccountID');
    $PassPhrase = urlencode(config('perfect.PassPhrase'));

    $opts = array(
        'socket' => array(
            'bindto' => '0.0.0.0:0',
        )
    );

    $context = stream_context_create($opts);

    $voucher = http::get("https://perfectmoney.is/acct/ev_activate.asp?AccountID=" . $AccountID . "&PassPhrase=" . $PassPhrase . "&Payee_Account=" . $Payer_Account . "&ev_number=" . $ev_number . "&ev_code=" . $ev_code)->body();

    if (strpos($voucher, 'Error') !== false)
        return false;

    $dom = new DomDocument();
    $dom->loadHTML($voucher);

    $output = array();

    $element = $dom->getElementsByTagName("input");

    return $element[1]->getAttribute('value');
}
