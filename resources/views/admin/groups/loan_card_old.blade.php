@extends('admin.layout')


@section('header_scripts')
 
@endsection

@section('main')


<?php 
    // Loan details
    $principal = 10000;  // Principal loan amount in rupees
    $annual_interest_rate = 20;  // Annual interest rate in percentage
    $loan_tenure_months = 12;  // Loan tenure in months

    // Calculate monthly interest rate
    $monthly_interest_rate = ($annual_interest_rate / 12) / 100;

    // Calculate EMI formula components
    $emi_numerator = $principal * $monthly_interest_rate * pow((1 + $monthly_interest_rate), $loan_tenure_months);
    $emi_denominator = pow((1 + $monthly_interest_rate), $loan_tenure_months) - 1;

    // Calculate EMI
    $emi = $emi_numerator / $emi_denominator;

    // Initialize variables for the loop
    $outstanding_balance = $principal;
?>

<div class="main" ng-controller="groupsCtrl" ng-init="">
    <div class="row mb-4">
        <div class="col-md-6">
            <h1 class="h3 mb-2 text-gray-800">Loan Card</h1>
        </div>
        <div class="col-md-6 text-right">
            <a href="{{url('admin/groups')}}" class="btn btn-primary">Back</a>
        </div>
    </div>    
    <div class="card shadow mb-4"> 
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" width="100%" cellspacing="0">
                    <thead>
                       <tr>
                            <th>Month</th>
                            <th>EMI</th>
                            <th>Interest Payment</th>
                            <th>Principal Payment</th>   
                        </tr>
                    </thead>
                    <tbody>
                        <?php 

                            for ($month = 1; $month <= $loan_tenure_months; $month++) {
                                // Calculate monthly interest payment
                                $monthly_interest_payment = $outstanding_balance * $monthly_interest_rate;

                                // Calculate monthly principal payment
                                $monthly_principal_payment = $emi - $monthly_interest_payment;

                                ?>
                                <tr>
                                    <td><?php echo $month; ?></td>
                                    <td><?php echo number_format($emi, 2); ?></td>
                                    <td><?php echo number_format($monthly_interest_payment, 2); ?></td>
                                    <td><?php echo number_format($monthly_principal_payment, 2); ?></td>
                                </tr>
                                <?php

                                // Update outstanding balance for the next month
                                $outstanding_balance -= $monthly_principal_payment;
                            }?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>  
</div>
@endsection

@section('footer_scripts')
    <?php $version = "0.0.2"; ?>
        
    <script type="text/javascript" src="{{url('assets/scripts/core/groups_ctrl.js?v='.$version)}}" ></script>

    
@endsection