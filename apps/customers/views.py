# Django Imports
from django.shortcuts import render, redirect
from django.contrib.auth.decorators import login_required
from django.core.paginator import Paginator
from django.core.exceptions import ValidationError

# Models
from .models import PriceAnalysis, PriceAnalysisCredit, Customer, PaymentMethod

# Utility Functions
from utils.utils import get_persian_datetime
from persiantools.jdatetime import JalaliDate

# Get Persian date and time
persian_date, persian_time, now = get_persian_datetime()
datetime = [persian_date, persian_time]

# Global Variables
buttons = [
    {'text': 'راهنما', 'url': '#', 'icon': 'solar:question-circle-broken'},
    {'text': 'ثبت رکورد جدید', 'url': "customer_add", 'icon': 'solar:add-circle-broken'},
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

# Views

@login_required(login_url='/login')
def payment_method_list(request):
    payment_method = PaymentMethod.objects.all()
    paginator = Paginator(payment_method, 10)
    page_number = request.GET.get('page')
    page_obj = paginator.get_page(page_number)
    return render(request, 'dashboard/customers/payment/payment_method_list.html', page_obj)


@login_required(login_url='/login')
def price_analysis_list(request):
    price_analyses = PriceAnalysis.objects.all()
    paginator = Paginator(price_analyses, 10)
    page_number = request.GET.get('page')
    page_obj = paginator.get_page(page_number)
    return render(request, 'dashboard/customers/prices_analysis/price_analysis_list.html', {'page_obj': page_obj, 'buttons': buttons})


@login_required(login_url='/login')
def price_analysis_credit_list(request):
    price_analysis_credits = PriceAnalysisCredit.objects.all()
    paginator = Paginator(price_analysis_credits, 10)
    page_number = request.GET.get('page')
    page_obj = paginator.get_page(page_number)
    return render(request, 'dashboard/customers/prices_analysis/price_analysis_credit_list.html', {'page_obj': page_obj, 'buttons': buttons})


@login_required(login_url='/login')
def customer_list(request):
    customers = Customer.objects.filter(is_deleted=False).order_by('-id')
    paginator = Paginator(customers, 8)
    page_number = request.GET.get('page')
    page_obj = paginator.get_page(page_number)

    for customer in page_obj:
        customer.created_at = JalaliDate(customer.created_at).strftime('%Y/%m/%d')

    context = {
        'page_obj': page_obj,
        'buttons': buttons,
        'filters': filters,
    }

    return render(request, 'dashboard/customers/customers/customer_list.html', context)


def customer_add(request):
    if request.method == 'POST':
        try:
            data = request.POST
            customer_type = 1 if data.get('customer-type') == 'real' else 2
            nationality = 1 if data.get('nationality') == 'iranian' else 2
            clearing_type = 1 if data.get('payment-type') == 'cash' else 2
            birth_date = None if customer_type == 2 else data.get('birth-date')

            customer_data = {
                "customer_type": customer_type,
                "nationality": nationality,
                "clearing_type": clearing_type,
                "name_fa": data.get('first-name') if customer_type == 1 else data.get('first-name-en'),
                "family_fa": data.get('last-name') if customer_type == 1 else data.get('last-name-en'),
                "name_en": data.get('first-name-en'),
                "family_en": data.get('last-name-en'),
                "national_id": data.get('national-id') if nationality == 1 else None,
                "national_code": data.get('national-id') if customer_type == 2 else None,
                "passport": data.get('national-id') if nationality == 2 else None,
                "economy_code": data.get('economy_code') if customer_type == 2 else None,
                "company_fa": data.get('first-name') if customer_type == 2 else None,
                "company_en": data.get('last-name') if customer_type == 2 else None,
                "mobile": data.get('phone-number'),
                "email": data.get('email'),
                "postal_code": data.get('zipcode'),
                "address": data.get('address'),
                "birth_date": birth_date,
                "created_at": now,
                "updated_at": now,
            }

            Customer.objects.create(**customer_data)

        except (ValueError, ValidationError) as e:
            return render(request, 'dashboard/customers/customers/customer_add.html', {'error': str(e)})

    return render(request, 'dashboard/customers/customers/customer_add.html')


def customer_delete(request, customer_id):
    try:
        customer = Customer.objects.get(id=customer_id)
        customer.is_deleted = True
        customer.save()
        return redirect('customer_list')
    except Customer.DoesNotExist:
        return render(request, 'dashboard/customers/customers/customer_list.html', {'error': 'Customer not found'})


def customer_edit(request, customer_id):
    try:
        customer = Customer.objects.get(id=customer_id)
        if request.method == 'POST':
            data = request.POST
            customer.customer_type = 1 if data.get('customer-type') == 'real' else 2
            customer.nationality = 1 if data.get('nationality') == 'iranian' else 2
            customer.clearing_type = 1 if data.get('payment-type') == 'cash' else 2
            customer.birth_date = None if customer.customer_type == 2 else data.get('birth-date')

            customer.name_fa = data.get('first-name') if customer.customer_type == 1 else data.get('first-name-en')
            customer.family_fa = data.get('last-name') if customer.customer_type == 1 else data.get('last-name-en')
            customer.name_en = data.get('first-name-en')
            customer.family_en = data.get('last-name-en')
            customer.national_id = data.get('national-id') if customer.nationality == 1 else None
            customer.national_code = data.get('national-id') if customer.customer_type == 2 else None
            customer.passport = data.get('national-id') if customer.nationality == 2 else None
            customer.economy_code = data.get('economy_code') if customer.customer_type == 2 else None
            customer.company_fa = data.get('first-name') if customer.customer_type == 2 else None
            customer.company_en = data.get('last-name') if customer.customer_type == 2 else None
            customer.mobile = data.get('phone-number')
            customer.email = data.get('email')
            customer.postal_code = data.get('zipcode')
            customer.address = data.get('address')
            customer.updated_at = now

            customer.save()
            return redirect('customer_list')

        return render(request, 'dashboard/customers/customers/customer_edit.html', {'customer': customer})

    except Customer.DoesNotExist:
        return render(request, 'dashboard/customers/customers/customer_list.html', {'error': 'Customer not found'})


def customer_detail(request, customer_id):
    try:
        customer = Customer.objects.get(id=customer_id)
        return render(request, 'dashboard/customers/customers/customer_detail.html', {'customer': customer})
    except Customer.DoesNotExist:
        return render(request, 'dashboard/customers/customers/customer_list.html', {'error': 'Customer not found'})


def customer_test(request):
     # Initialize the default values
    customer_type = 'real'
    nationality = 'iranian'
    payment_type = 'cash'

    if request.method == 'POST':
        # Get selected values from POST data
        customer_type = request.POST.get('customer-type', 'real')
        nationality = request.POST.get('nationality', 'iranian')
        payment_type = request.POST.get('payment-type', 'cash')

    # Pass the selected values to the template
    return render(request, 'dashboard/customers/customers/customer_test.html', {
        'customer_type': customer_type,
        'nationality': nationality,
        'payment_type': payment_type,
    })
