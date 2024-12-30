from django.shortcuts import render
from django.core.paginator import Paginator
from datetime import datetime

def analyze_list_view(request):
    # Sample Data
    analyzes = [
        {
            "name": f"تحلیل {i}",
            "creation_date": datetime.now().strftime("%Y-%m-%d"),
            "edit_date": datetime.now().strftime("%Y-%m-%d"),
            "status": "فعال" if i % 2 == 0 else "غیرفعال",
        }
        for i in range(1, 101)
    ]
    

    # Pagination
    paginator = Paginator(analyzes, 10)  # 10 items per page
    page_number = request.GET.get("page")
    page_obj = paginator.get_page(page_number)

    context = {
        "page_obj": page_obj,
        "buttons" : [
            {'text': 'راهنما', 'url': '#', 'icon': 'solar:question-circle-broken'},
            {'text': 'ثبت رکورد جدید',  'url': '#', 'icon': 'solar:add-circle-broken'},
            {'text': 'پرینت', 'url': '#', 'icon': 'solar:printer-bold'},
            {'text': 'حذف چندتایی', 'url': '#', 'icon': 'solar:trash-bin-minimalistic-2-broken'},
            {'text': 'خروجی', 'url': '#', 'icon': 'solar:export-bold' },
            {'text': 'تنظیمات', 'url': '#', 'icon': 'solar:settings-outline'},
        ],
    }
    return render(request, 'analyze_management/partials/analyze-list.html', context)
    




def analysis_time_view(request):
    # Mock data - replace with database query
    ANALYSIS_DATA = [
        {
            "analysis_type": "XRD normal",
            "based_on": "روز",
            "count": 10,
            "minutes": 100,
            "creation_date": "2024-01-01",
            "edit_date": "2024-01-05",
        },
        {
            "analysis_type": "VSM",
            "based_on": "دقیقه",
            "count": 10,
            "minutes": 100,
            "creation_date": "2024-01-01",
            "edit_date": "2024-01-05",
        },
        {
            "analysis_type": "Zeta",
            "based_on": "دقیقه",
            "count": 10,
            "minutes": 100,
            "creation_date": "2024-01-01",
            "edit_date": "2024-01-05",
        },
        {
            "analysis_type": "آنالیز حرارتی",
            "based_on": "روز",
            "count": 10,
            "minutes": 100,
            "creation_date": "2024-01-01",
            "edit_date": "2024-01-05",
        },
        {
            "analysis_type": "XRD normal",
            "based_on": "روز",
            "count": 10,
            "minutes": 100,
            "creation_date": "2024-01-01",
            "edit_date": "2024-01-05",
        },
        # Add all other analysis types as per requirements
    ]
    buttons = [
        {'text': 'راهنما', 'title': 'راهنما: جهت مشاهده ی راهنما (پنجره ی فعلی) از این دکمه استفاده نمایید.', 'url': '#', 'icon': 'solar:question-circle-broken'},
        {'text': 'ثبت رکورد جدید', 'title': 'ثبت رکورد جدید: جهت ثبت یک رکورد جدید و افزودن آن به این لیست از این دکمه استفاده کنید. با کلیک روی این دکمه به فرم مربوطه هدایت می شوید.', 'url': '#', 'icon': 'solar:add-circle-broken'},
        {'text': 'پرینت', 'title': 'پرینت: جهت چاپ تمام اطلاعات موجود در گرید از این دکمه استفاده کنید. بنابراین با فیلتر اطلاعات گرید، اطلاعات فیلتر شده را میتوان پرینت گرفت.', 'url': '#', 'icon': 'solar:printer-bold'},
        {'text': 'حذف چندتایی', 'title': 'حذف چندتایی: در صورت تمایل به حذف چندتایی رکورد ها، ابتدا رکوردهای مورد نظر را در گرید مارک دار نمایید و سپس از این دکمه برای حذف استفاده نمایید.', 'url': '#', 'icon': 'solar:trash-bin-minimalistic-2-broken'},
        {'text': 'خروجی', 'title': 'خروجی: جهت تهیه ی خروجی اکسل و یا پی دی اف از این دکمه و انتخاب نوع خروجی مورد نظر از لیست باز شده، استفاده نمایید.', 'url': '#', 'icon': 'solar:export-bold' },
        {'text': 'تنظیمات', 'title': 'تنظیمات: جهت تنظیمات دلخواه گرید مانند: نمایش یا عدم نمایش ستونها، رنگ بندی، تعداد رکوردهای قابل نمایش در صفحه و.. از این دکمه استفاده نمایید.', 'url': '#', 'icon': 'solar:settings-outline'},
    ]
    filters = [
        {'id': 'executive_body', 'label': 'آنالیز', 'options': ['xrd','XRF','CHN','CHNS','ICP']},
        {'id': 'status', 'label': 'برحسب', 'options': ['روز', 'دقیقه']},
    ]
    page_number = request.GET.get('page', 1)
    paginator = Paginator(ANALYSIS_DATA, 10)  # Show 10 items per page
    page_obj = paginator.get_page(page_number)

    context = {
        'analysis_time': page_obj,
        'page_obj': page_obj,
        'buttons':buttons,
        'filters':filters,
    }
    return render(request, 'analyze_management/partials/analysis-time-list.html', context)

