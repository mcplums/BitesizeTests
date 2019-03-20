<?php

$csv = array_map('str_getcsv', file('stfu.csv'));
/*print_r(count($csv);*/

echo count($csv);

$transactions = array();


foreach ($csv as $transaction)
{
	$trans['amount'] = $transaction[0];
	$trans['commentary'] = $transaction[2];
	$trans['transOtherParty'] = $transaction[1];
	$transactions[] = $trans;
	/*print_r($trans);*/
}

print_r($transactions);

// This array ensures that double marking as complete doesn't happen
		static $tradeRecord = array();

		$tradeID = 'BFUQASAS2';
		$amountCurrency = 1490.01;
		$matchTolerance = 1;

		foreach ($transactions as $transaction)
		{
			syslog(LOG_DEBUG, "TransMatch: Transaction {$transaction['commentary']} found");
			

			// See if matched trade found
			if (stristr($transaction['commentary'], $tradeID) &&
				  ($transaction['amount'] - $amountCurrency <= $matchTolerance) &&
				  ($transaction['amount'] - $amountCurrency >= 0))
			{
				echo 'Match found';

				// Do some anti-fraud
				if (antiFraudCheck($transaction))
				{
					// Passed
					syslog(LOG_INFO, "TransMatch: Matched trade {$tradeID}");
					echo 'passed KYC';
				}
				
			}

			else
				{echo 'No match';}
		}

function antiFraudCheck($transaction)
	{
		// Any tranasction other party information at all? Might not be for telephone banking.
		if (!isset($transaction['transOtherParty']) || $transaction['transOtherParty'] == '')
		{
			$reason = 'There is no payer information';
			return false;
		}
	
		$payeeName = strtoupper(trim($transaction['transOtherParty']));
		echo 'AntiFraud: checks on payment from: '.$payeeName."\n";

		// Is payeeName too short for checks to pass?
		if (strlen($payeeName) < 4)
		{
			$reason = 'Payer information is too short';
			return false;
		}

		// Is expected payment data available?
		echo "AntiFraud: Trying previous payment data\n";
		foreach ($trade->payData as $payData)
		{
			syslog(LOG_DEBUG, "AntiFraud: {$payData->detail} => {$payData->value}");

			if ($payData->detail == 'bankOtherParty')
			{
				// Make sure this is valid paydata
				if (strlen($payData->value) <= 2)
				{
					syslog(LOG_DEBUG, "AntiFraud: Ignored as too short");
					continue;
				}

				syslog(LOG_INFO, "AntiFraud: Testing {$payData->detail} => {$payData->value}");
				$payDataRecord=strtoupper(trim($payData->value));

				if (fuzzyMatch($payeeName, $payDataRecord))
					return true;

				if (fuzzyMatch($payDataRecord, $payeeName))
					return true;
			}
		}

		// Match on real name
		syslog(LOG_INFO, "AntiFraud: Unable to find previous matching payment data");

		// See if real name is a match to payee
		$payDataRecord = strtoupper(trim($trade->realname));
		syslog(LOG_INFO, "AntiFraud: Trying real name: " . $payDataRecord);

		if (fuzzyMatch($payeeName, $payDataRecord))
		   	return true;

		if (fuzzyMatch($payDataRecord, $payeeName))
		   	return true;

		$reason = 'No payee match found';
		return false;
	}

?>