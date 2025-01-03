from django.shortcuts import render
from .models import PaymentMethod
from .models import PriceAnalysis, PriceAnalysisCredit,Customer
from django.core.paginator import Paginator
from utils.utils import get_persian_datetime

persian_date, persian_time, now  = get_persian_datetime()
datetime = [persian_date, persian_time]
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

from django.http import JsonResponse
from .models import Customer

def customer_add(request):
    if request.method == 'POST':
        # Collect data from POST
        customer_type_legal = request.POST.get('customer-type-legal')
        nationality_iranian = request.POST.get('nationality-iranian')
        clearing_type_cash = request.POST.get('payment-type-cash')
        name_fa = request.POST.get('first-name')
        family_fa = request.POST.get('last-name')
        name_en = request.POST.get('first-name-en')
        family_en = request.POST.get('last-name-en')
        national_code = request.POST.get('national-id')
        mobile = request.POST.get('phone-number')
        email = request.POST.get('email')
        postal_code = request.POST.get('zipcode')
        address = request.POST.get('address')
        birth_date = request.POST.get('birth-date')
        passport = request.POST.get('passport')  # Optional field
        economy_code = request.POST.get('economy_code')  # Optional field

        # Add extra fields based on type
        if customer_type_legal == 'on':  # If 'Legal'
            company_fa = name_fa
            company_en = family_fa
            name_fa = request.POST.get('legal-rep-name-fa', '')
            family_fa = request.POST.get('legal-rep-family-fa', '')
            customer_type= 2
        else:
            company_fa = None
            company_en = None
            customer_type= 1
            
        if clearing_type_cash == 'on':
            clearing_type = 1
        else:
            clearing_type = 2
            
        if nationality_iranian == 'on':
            nationality = 1
        else: 
            nationality = 2


        # Save the customer
        Customer.objects.create(
            customer_type=customer_type,
            nationality=nationality,
            clearing_type=clearing_type,
            name_fa=name_fa,
            family_fa=family_fa,
            name_en=name_en,
            family_en=family_en,
            national_code=national_code,
            passport=passport,
            economy_code=economy_code,
            company_fa=company_fa,
            company_en=company_en,
            mobile=mobile,
            email=email,
            postal_code=postal_code,
            address=address,
            birth_date=birth_date,
            created_at=now,  # Use the datetime object
            updated_at=now   # Use the datetime object
        )
        
    return render(request, 'dashboard/customers/customers/customer_add.html')