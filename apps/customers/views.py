from django.shortcuts import render
from .models import PaymentMethod

def payment_method_list(request):
    # Define buttons
    buttons = [
        {'text': 'راهنما', 'url': '#', 'icon': 'solar:question-circle-broken'},
        {'text': 'ثبت رکورد جدید', 'url': '#', 'icon': 'solar:add-circle-broken'},
        {'text': 'پرینت', 'url': '#', 'icon': 'solar:printer-bold'},
        {'text': 'حذف چندتایی', 'url': '#', 'icon': 'solar:trash-bin-minimalistic-2-broken'},
        {'text': 'خروجی', 'url': '#', 'icon': 'solar:export-bold'},
        {'text': 'تنظیمات', 'url': '#', 'icon': 'solar:settings-outline'},
    ]
    
    # Base queryset
    queryset = PaymentMethod.objects.all()

    # Apply filters
    title_filter = request.GET.get('title')
    if title_filter:
        queryset = queryset.filter(title__icontains=title_filter)
    
    status_filter = request.GET.get('status')
    if status_filter:
        queryset = queryset.filter(status=status_filter)
    
    start_date = request.GET.get('start_date')
    end_date = request.GET.get('end_date')
    if start_date and end_date:
        queryset = queryset.filter(created_at__range=[start_date, end_date])

    # Pagination
    page = request.GET.get('page', 1)
    paginate_by = 10
    start = (int(page) - 1) * paginate_by
    end = start + paginate_by
    page_obj = queryset[start:end]

    # Context
    context = {
        'page_obj': page_obj,
        'buttons': buttons,
        'paginator': {
            'has_previous': start > 0,
            'has_next': end < len(queryset),
            'previous_page_number': int(page) - 1 if start > 0 else None,
            'next_page_number': int(page) + 1 if end < len(queryset) else None,
        },
    }
    
    return render(request, 'dashboard/customers/payment/payment_method_list.html', context)
