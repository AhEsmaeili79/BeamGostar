from django.shortcuts import render
from .models import PaymentMethod
from .models import PriceAnalysis, PriceAnalysisCredit,Customer
from django.core.paginator import Paginator

buttons = [
        {'text': 'راهنما', 'url': '#', 'icon': 'solar:question-circle-broken'},
        {'text': 'ثبت رکورد جدید', 'url': '#', 'icon': 'solar:add-circle-broken'},
        {'text': 'پرینت', 'url': '#', 'icon': 'solar:printer-bold'},
        {'text': 'حذف چندتایی', 'url': '#', 'icon': 'solar:trash-bin-minimalistic-2-broken'},
        {'text': 'خروجی', 'url': '#', 'icon': 'solar:export-bold'},
        {'text': 'تنظیمات', 'url': '#', 'icon': 'solar:settings-outline'},
    ]

filters = [
        {'id': 'province', 'label': 'نوع مشتری', 'options': ['حقیقی', 'حقوقی']},
        {'id': 'district', 'label': 'نوع تسویه', 'options': ['نقدی','اعتباری']},
        {'id': 'executive_body', 'label': 'تابعیت', 'options': ['ایرانی','خارجی']},
    ]

def payment_method_list(request):
    # Define buttons
    
    
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


def price_analysis_list(request):
    price_analyses = PriceAnalysis.objects.all()
    paginator = Paginator(price_analyses, 10)
    page_number = request.GET.get('page')
    page_obj = paginator.get_page(page_number)
    
    return render(request, 'dashboard/customers/prices_analysis/price_analysis_list.html', {'page_obj': page_obj,'buttons':buttons})

def price_analysis_credit_list(request):
    price_analysis_credits = PriceAnalysisCredit.objects.all()
    paginator = Paginator(price_analysis_credits, 10)
    page_number = request.GET.get('page')
    page_obj = paginator.get_page(page_number)
    
    return render(request, 'dashboard/customers/prices_analysis/price_analysis_credit_list.html', {'page_obj': page_obj,'buttons':buttons})




def customer_list(request):
    customers = Customer.objects.all()

    # Set up pagination
    paginator = Paginator(customers, 10)  # Show 10 customers per page
    page_number = request.GET.get('page')
    page_obj = paginator.get_page(page_number)

    context = {
        'page_obj': page_obj,
        'buttons':buttons,
        'filters':filters,
    }
    return render(request, 'dashboard/customers/customers/customers_list.html', context)