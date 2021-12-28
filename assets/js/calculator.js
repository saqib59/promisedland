$(document).ready(function () {

    var priceAndCost = 0.00;
    var capitalNeed = 0.00;
    var loanAmount = 0.00;
    var monthlyPayment = 0.00;
    var depriciationRate = 0.00;
    var depriciation = 0.00;
    var cashFlowWithoutTax = 0.00;
    var monthlyTax = 100.00;
    var samePaymentInterest = 0.00;

    function calcGrossRental() {
        let price = $('input[name=price_of_purchase]').val();
        let rental = $('input[name=rental]').val();

        if (price && rental) {
            var simpleGrossRentalYield = ((Number(rental) * 12) / Number(price) * 100).toFixed(2);
            $("input[name=gross_rental]").val(simpleGrossRentalYield);
        }
    }
    $("input[name=price_of_purchase], input[name=rental]").on("keyup", calcGrossRental);

    function calcAdditionalPurchaseCost() {
        let buyingTax = $('select[name=state]').val();
        let estateAgent = $('input[name=estate_agent]').val();
        let notary = $('input[name=notary]').val();
        let registerCost = $('input[name=register_cost]').val();
        let moreCost = $('input[name=more_cost]').val();
        let purchasePrice = $('input[name=price_of_purchase]').val();

        if (estateAgent && notary && registerCost && moreCost) {
            let purchaseCost = ((Number(buyingTax) + Number(estateAgent) + Number(notary) + Number(registerCost) + Number(moreCost)) * Number(purchasePrice) / 100);
            $("input[name=additional_cost]").val(purchaseCost.toFixed(2));
            priceAndCost = (Number(purchasePrice) + Number(purchaseCost)).toFixed(2);
            $("input[name=purchase_cost]").val(priceAndCost);
        }
    }

    $('select[name=state]').on("change", function () {
        var taxy = $(this).val();
        $("input[name=buying_tax]").val(taxy);
    });

    $('select[name=state]').on("change", calcAdditionalPurchaseCost);
    $('input[name=estate_agent], input[name=notary], input[name=register_cost], input[name=more_cost], input[name=price_of_purchase]').on("keyup", calcAdditionalPurchaseCost);

    function calcNeedOfCapital() {
        let renovation = $('input[name=renovation]').val();
        let contribution = $('input[name=contribution]').val();

        if (contribution && renovation) {
            capitalNeed = (Number(contribution) + Number(renovation) + Number(priceAndCost.replace(/,/g, ''))).toFixed(2);
            $("input[name=capital_need]").val(capitalNeed);
        }
    }
    $("input[name=renovation], input[name=contribution]").on("keyup", calcNeedOfCapital);


    function calcLoanAmount() {
        let loanPercent = $('input[name=loan_percent]').val();
        let price = $('input[name=price_of_purchase]').val();

        if (loanPercent) {
            loanAmount = (Number(price) * Number(loanPercent) / 100).toFixed(2);
            $("input[name=loan_amount]").val(loanAmount);
            var privateCapital = (Number(capitalNeed.replace(/,/g, '')) - Number(loanAmount.replace(/,/g, ''))).toFixed(2);
            $("input[name=private_capital]").val(privateCapital);
        }
    }
    $("input[name=loan_percent]").on("keyup", calcLoanAmount);

    function calcMonthlyPayment() {
        let interest = $('input[name=interest]').val();
        let repaymentIn = $('input[name=repayment_in]').val();

        if (interest && repaymentIn) {
            monthlyPayment = ((Number(interest) + Number(repaymentIn)) * Number(loanAmount.replace(/,/g, '')) / 12 / 100).toFixed(2);
            $("input[name=monthly_payment]").val(monthlyPayment);
        }
    }
    $("input[name=interest], input[name=repayment_in]").on("keyup", calcMonthlyPayment);

    function calcCashFlowWithoutTax() {
        let houseMoney = $('input[name=house_money]').val();
        let saving = $('input[name=saving]').val();
        let renovationSaving = $('input[name=renovation_saving]').val();
        let rental = $('input[name=rental]').val();
        let squareMeters = $('input[name=square_meters]').val();

        // Calculating Percentages
        saving = Number(rental) * Number(saving) / 100;
        renovationSaving = Number(renovationSaving) * Number(squareMeters);

        cashFlowWithoutTax = (Number(rental) - (Number(monthlyPayment.replace(/,/g, '')) + Number(houseMoney) + Number(saving) + Number(renovationSaving))).toFixed(2);
        $("#cashFlowWithoutTax").html(cashFlowWithoutTax);
    }
    $("input[name=house_money], input[name=saving], input[name=renovation_saving]").on("keyup", calcCashFlowWithoutTax);

    function calcDepreciationRate() {
        let year = $('input[name=year_build]').val();
        if (Number(year) > 1924) {
            depriciationRate = 2.0;
        } else {
            depriciationRate = 2.5;
        }
        let price = $('input[name=price_of_purchase]').val();
        $("input[name=depriciation]").val(depriciationRate);
        depriciation = ((Number(price) * depriciationRate) / 100).toFixed(2);
        $("input[name=depreciation_month]").val(depriciation);
    }
    $("input[name=year_build]").on("keyup", calcDepreciationRate);

    function calcTaxableCashFlow() {
        let taxRate = $('input[name=tax_rate]').val();
        let rental = $('input[name=rental]').val();
        let houseMoney = $('input[name=house_money]').val();

        console.log('rental', Number(rental))
        console.log('housemoney', Number(houseMoney))
        console.log('monthly', Number(monthlyPayment.replace(/,/g, '')))
        console.log('depreciation', Number(depriciation.replace(/,/g, '')))

        var taxableCashflow = (Number(rental) - Number(houseMoney) - Number(monthlyPayment.replace(/,/g, '')) - Number(depriciation.replace(/,/g, ''))).toFixed(2);
        $("input[name=tax_cashflow]").val(taxableCashflow);

        var cashflowAfterTax = (Number(cashFlowWithoutTax.replace(/,/g, '')) - monthlyTax).toFixed(2);
        $("#cashflowAfterTax").html(cashflowAfterTax);
    }
    $("input[name=tax_rate]").on("keyup", calcTaxableCashFlow);

    function calcSamePayment() {
        let interestYears = $('input[name=interest_for_years]').val();
        if (Number(interestYears) == 10) {
            samePaymentInterest = 3;
        }
    }
    $("input[name=interest_for_years]").on("keyup", calcSamePayment);

})