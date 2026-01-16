// PHP TAX COMPUTATION 

function ComputeTax($basic_salary)
{
	// 1. Calculate correct employee-share deductions
	$gsis_ded = $basic_salary * 0.09;
	$phn_ded = $basic_salary * 0.025; // Fixed: Use 2.5% employee share
	$pagibig_ded = 200; // Fixed: Added Pag-IBIG

	$total_deductions = $gsis_ded + $phn_ded + $pagibig_ded;

	// 2. Calculate Taxable Income
	$taxable_income = $basic_salary - $total_deductions;

	$tax = 0; // Initialize tax

	// 3. Apply the BIR Tax Table (Fixed bracket logic)
	if ($taxable_income <= 20833.33) {
		$tax = 0;
	} else if ($taxable_income > 20833.33 && $taxable_income <= 33332.99) {
		// 15% of the excess over 20,833.33
		$tax = ($taxable_income - 20833.33) * 0.15;
	} else if ($taxable_income > 33332.99 && $taxable_income <= 66666.99) {
		// ₱1,875 + 20% of the excess over 33,333
		$tax = (($taxable_income - 33333.33) * 0.20) + 1875; // Fixed math bug
	} else if ($taxable_income > 66666.99 && $taxable_income <= 166666.99) {
		// ₱8,541.67 + 25% of the excess over 66,667
		$tax = (($taxable_income - 66666.67) * 0.25) + 8541.67; // Fixed math bug
	} else if ($taxable_income > 166666.99 && $taxable_income <= 666666.99) {
		// ₱33,541.67 + 30% of the excess over 166,667
		$tax = (($taxable_income - 166666.67) * 0.30) + 33541.67; // Fixed math bug
	} else if ($taxable_income > 666666.99) {
		// ₱183,541.67 + 35% of the excess over 666,667
		$tax = (($taxable_income - 666666.67) * 0.35) + 183541.67; // Fixed math bug
	}

	return $tax;
}

// RATE FOR BANK COMPUTATIONS
  
define('FINANCIAL_MAX_ITERATIONS', 128);
define('FINANCIAL_PRECISION', 1.0e-08);
function RATE($month, $payment, $amount, $fv = 0.0, $type = 0, $guess = 0.1) {
    $rate = $guess;
    if (abs($rate) < FINANCIAL_PRECISION) {
        $y = $amount * (1 + $month * $rate) + $payment * (1 + $rate * $type) * $month + $fv;
    } else {
        $f = exp($month * log(1 + $rate));
        $y = $amount * $f + $payment * (1 / $rate + $type) * ($f - 1) + $fv;
    }
    $y0 = $amount + $payment * $month + $fv;
    $y1 = $amount * $f + $payment * (1 / $rate + $type) * ($f - 1) + $fv;
    $i = $x0 = 0.0;
    $x1 = $rate;
    while ((abs($y0 - $y1) > FINANCIAL_PRECISION) && ($i < FINANCIAL_MAX_ITERATIONS)) {
        $rate = ($y1 * $x0 - $y0 * $x1) / ($y1 - $y0);
        $x0 = $x1;
        $x1 = $rate;
        if (abs($rate) < FINANCIAL_PRECISION) {
            $y = $amount * (1 + $month * $rate) + $payment * (1 + $rate * $type) * $month + $fv;
        } else {
            $f = exp($month * log(1 + $rate));
            $y = $amount * $f + $payment * (1 / $rate + $type) * ($f - 1) + $fv;
        }
        $y0 = $y1;
        $y1 = $y;
        ++$i;
    }
    return $rate*12;
}
$fv = 0;
$type = 0;
$guess = 0.01;
$percentage = (RATE($month, $payment, $amount));
$percentage2 = $percentage*12;


$v_rate = round($percentage2*100,2);
