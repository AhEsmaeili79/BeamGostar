document.addEventListener('DOMContentLoaded', function () {
    if (document.querySelector('.customer-add')) {
        const realRadioButton = document.getElementById('customer-real');
        const legalRadioButton = document.getElementById('customer-legal');
        const iranianRadioButton = document.getElementById('iranian');
        const foreignRadioButton = document.getElementById('foreign');
        const nationalCodeContainer = document.getElementById('national-code-container');

        // Fields for حقیقی customer type
        const realFields = [
            'first-name-container', 'last-name-container', 'first-name-en-container', 
            'last-name-en-container', 'national-code-container', 'birth-date-container', 'phone-number-container'
        ];

        // Legal fields to be added dynamically
        const legalFields = [
            { id: 'legal-field-1', label: 'نام شرکت(فارسی)', placeholder: 'نام شرکت را وارد کنید', name: 'company_name', size: 'col-lg-6' },
            { id: 'legal-field-2', label: 'نام شرکت(انگلیسی)', placeholder: 'نام شرکت را به انگلیسی وارد کنید', name: 'company_name_en', size: 'col-lg-6' },
            { id: 'legal-field-3', label: 'نام رابط(فارسی)', placeholder: 'نام رابط را وارد کنید', name: 'contact_name_fa', size: 'col-lg-6' },
            { id: 'legal-field-4', label: 'نام خانوادگی رابط(فارسی)', placeholder: 'نام خانوادگی رابط را وارد کنید', name: 'contact_lastname_fa', size: 'col-lg-6' },
            { id: 'legal-field-5', label: 'نام رابط(انگلیسی)', placeholder: 'نام رابط را به انگلیسی وارد کنید', name: 'contact_name_en', size: 'col-lg-4' },
            { id: 'legal-field-6', label: 'نام خانوادگی رابط(انگلیسی)', placeholder: 'نام خانوادگی رابط را به انگلیسی وارد کنید', name: 'contact_lastname_en', size: 'col-lg-4' },
            { id: 'legal-field-7', label: 'کد اقتصادی', placeholder: 'کد اقتصادی را وارد کنید', name: 'economy_code', size: 'col-lg-4' },
            { id: 'legal-field-8', label: 'شناسه ملی', placeholder: 'شناسه ملی را وارد کنید', name: 'national_id', icon: 'solar:user-bold', size: 'col-lg-4' },
            { id: 'legal-field-9', label: 'شماره همراه', placeholder: 'شماره همراه را وارد کنید', name: 'phone_number', icon: 'solar:outgoing-call-rounded-bold-duotone', size: 'col-lg-4' },
            { id: 'legal-field-10', label: 'شماره تماس شرکت', placeholder: 'شماره تماس شرکت را وارد کنید', name: 'company_phone', icon: 'solar:outgoing-call-rounded-bold-duotone', size: 'col-lg-4' }
        ];

        // Function to handle the change event when customer type or nationality is selected
        function handleCustomerTypeChange() {
            const isLegal = legalRadioButton.checked;
            const isIranian = iranianRadioButton.checked;
            const isForeign = foreignRadioButton.checked;

            // Show/Hide fields based on the selected customer type
            if (isLegal) {
                toggleFieldsVisibility(realFields, false); // Hide real customer fields
                addLegalFields(); // Add legal customer fields dynamically
                nationalCodeContainer.style.display = 'none'; // Hide national code for legal customers
            } else {
                toggleFieldsVisibility(realFields, true); // Show real customer fields
                removeLegalFields(); // Remove any dynamically added legal fields
                handleNationalityChange(isIranian, isForeign); // Adjust national code visibility
            }
        }

        // Toggle visibility of real customer fields
        function toggleFieldsVisibility(fields, show) {
            fields.forEach(fieldId => {
                const fieldElement = document.getElementById(fieldId);
                if (fieldElement) {
                    fieldElement.style.display = show ? 'block' : 'none';
                }
            });
        }

        // Add legal fields dynamically to the form
        function addLegalFields() {
            // Remove any existing legal fields before adding new ones
            document.querySelectorAll('.dynamic-field').forEach(field => field.remove());

            legalFields.forEach(field => {
                const newInputContainer = document.createElement('div');
                newInputContainer.classList.add(field.size, 'dynamic-field');
                newInputContainer.innerHTML = `
                    <div class="mb-2"></div>
                    <label for="${field.id}" class="form-label">${field.label}</label>
                    ${field.icon ? `<div class="input-group mb-1">` : ''}
                    ${field.icon ? `<span class="input-group-text fs-20"><iconify-icon icon="${field.icon}" class="fs-20"></iconify-icon></span>` : ''}
                    <input type="text" id="${field.id}" name="${field.name}" class="form-control" placeholder="${field.placeholder}" />
                    ${field.icon ? `</div>` : ''}
                `;
                const nationalIdContainer = document.getElementById('national-code-container');
                nationalIdContainer.parentNode.insertBefore(newInputContainer, nationalIdContainer);
            });
        }

        // Remove dynamically added legal fields
        function removeLegalFields() {
            document.querySelectorAll('.dynamic-field').forEach(field => field.remove());
        }

        // Adjust the national code field based on nationality selection
        function handleNationalityChange(isIranian, isForeign) {
            if (isIranian || isForeign) {
                nationalCodeContainer.style.display = 'block';
                const nationalCodeLabel = nationalCodeContainer.querySelector('label');
                const nationalCodeInput = nationalCodeContainer.querySelector('input');

                if (isForeign) {
                    nationalCodeLabel.textContent = 'شماره گذرنامه';
                    nationalCodeInput.name = 'passport';
                    nationalCodeInput.placeholder = 'شماره گذرنامه را وارد کنید';
                } else {
                    nationalCodeLabel.textContent = 'کد ملی';
                    nationalCodeInput.name = 'national-code';
                    nationalCodeInput.placeholder = 'کد ملی را وارد کنید';
                }
            } else {
                nationalCodeContainer.style.display = 'none';
            }
        }

        // Add event listeners for changes in customer type and nationality
        realRadioButton.addEventListener('change', handleCustomerTypeChange);
        legalRadioButton.addEventListener('change', handleCustomerTypeChange);
        iranianRadioButton.addEventListener('change', handleCustomerTypeChange);
        foreignRadioButton.addEventListener('change', handleCustomerTypeChange);

        // Initial check for the default selection
        handleCustomerTypeChange();
    }
});