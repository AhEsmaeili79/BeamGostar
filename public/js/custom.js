document.addEventListener('DOMContentLoaded', function () {
    // Detect changes in 'grant' radio button
    const grantRadio = document.querySelector('[name="grant"]');
    const totalCostInput = document.querySelector('[name="total_cost"]');
    const applicantShareInput = document.querySelector('[name="applicant_share"]');
    const networkShareInput = document.querySelector('[name="network_share"]');

    grantRadio.addEventListener('change', function () {
        const grantValue = parseInt(grantRadio.value);

        if (grantValue === 0) {
            applicantShareInput.value = totalCostInput.value;
            networkShareInput.value = 0;
        } else {
            // Update the applicant_share and network_share dynamically based on total_cost
            const networkShareValue = parseFloat(networkShareInput.value) || 0;
            const applicantShareValue = parseFloat(totalCostInput.value) - networkShareValue;
            applicantShareInput.value = applicantShareValue;
        }
    });

    totalCostInput.addEventListener('input', function () {
        const grantValue = parseInt(grantRadio.value);
        const totalCostValue = parseFloat(totalCostInput.value);

        if (grantValue === 0) {
            applicantShareInput.value = totalCostValue;
            networkShareInput.value = 0;
        } else {
            const networkShareValue = parseFloat(networkShareInput.value) || 0;
            const applicantShareValue = totalCostValue - networkShareValue;
            applicantShareInput.value = applicantShareValue;
        }
    });

    networkShareInput.addEventListener('input', function () {
        const grantValue = parseInt(grantRadio.value);
        const networkShareValue = parseFloat(networkShareInput.value) || 0;
        const totalCostValue = parseFloat(totalCostInput.value);
        
        if (grantValue === 1) {
            const applicantShareValue = totalCostValue - networkShareValue;
            applicantShareInput.value = applicantShareValue;
        }
    });
});
