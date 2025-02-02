@extends('filament::layouts.app')

@section('content')
    <div class="filament-page-content">
        {!! form($form) !!}  <!-- Filament Form -->
    </div>
@endsection

@push('scripts')
    <script type="text/javascript">
        function calculator() {
            var samples_number = $('#samples_number').val();
            var analyze_time = $('#analyze_time').val();
            var discount_num = $('#discount_num').val();
            var additional_cost = $('#additional_cost').val();
            var value_added = $('input[name="value_added"]:checked').val();
            var discount = $('input[name="discount"]:checked').val();
            var grant = $('input[name="grant"]:checked').val();
            var valueadded = 0;
            var total = 0;

            // Call AJAX to get price from server
            $.ajax({
                async: false,
                url: '{{ route("your_price_route") }}',  // Replace with your actual route for price
                data: { analyze_id: $('#analyze_id').val(), customers_id: $('#customers_id').val() },
                contentType: 'application/json',
                dataType: 'json',
                success: function(data) {
                    price = data;
                },
            });

            // Calculate value added
            if (value_added == 1) {
                valueadded = ((samples_number * price) * 10) / 100;
            } else if (value_added == 0) {
                valueadded = 0;
            }

            // Discount calculation
            if (discount == 0) {
                total = (samples_number * price + valueadded);
            } else if (discount == 1) {
                total = ((samples_number * price + valueadded) - discount_num);
            } else if (discount == 2) {
                total = (samples_number * price + valueadded) - ((samples_number * price + valueadded) * discount_num) / 100;
            }

            // Additional cost calculation
            if (additional_cost != '') {
                total = (total * 1) + (additional_cost * 1);
            }

            // Set total cost
            $('#total_cost').val(total);

            // Grant calculations
            if (grant == 0) {
                $('#applicant_share').val(total);
                $('#network_share').val('');
                $('#network_id').val('');
            } else if (grant == 1) {
                var applicant_share = total - $('#network_share').val();
                var network_share = total - applicant_share;
                $('#applicant_share').val(applicant_share);
                $('#network_share').val(network_share);
            }

            // Call AJAX to get analyze time
            $.ajax({
                async: false,
                url: '{{ route("your_time_route") }}',  // Replace with your actual route for analyze time
                data: { analyze_id: $('#analyze_id').val() },
                contentType: 'application/json',
                dataType: 'json',
                success: function(data) {
                    var splittime = data.split('/');
                    var labquery = splittime[0];
                    var daywork = splittime[1];
                    var default_day = splittime[2];
                    var celltime = Math.ceil(((labquery * 1) + (samples_number * 1)) / daywork) + (default_day * 1);
                    if (celltime == 0)
                        $('#analyze_time').val(1);
                    else
                        $('#analyze_time').val(celltime);
                },
            });
        }
    </script>
@endpush
