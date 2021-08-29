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
    $voucher = file_get_contents("https://perfectmoney.com/acct/ev_create.asp?AccountID=".$AccountID."&PassPhrase=".$PassPhrase."&Payer_Account=".$Payer_Account."&Amount=".$Amount, false);
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
