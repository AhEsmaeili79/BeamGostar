from django.shortcuts import render, redirect
from django.contrib.auth import authenticate, login, logout
from django.contrib.auth.decorators import login_required
from django.contrib import messages
from django.views import View
from django.core.paginator import Paginator
from django.contrib.auth.mixins import LoginRequiredMixin

# login
def user_login(request):
    if request.method == 'POST':
        user = authenticate(request, username=request.POST['username'], password=request.POST['password'])
        if user:
            login(request, user)
            return redirect('/')
        messages.error(request, "نام کاربری یا رمز عبور اشتباه است")
    return render(request, 'auth/login.html')

# logout
@login_required(login_url='/login')
def user_logout(request):
    logout(request)
    return redirect('login')

# Dashboard redirect 
class DashboardView(LoginRequiredMixin, View):
    login_url = '/login'
    
    def get(self, request):
        return render(request, 'dashboard/base.html')




@login_required(login_url='/login')
def users_form(request):
    return render(request, 'dashboard/users/user_form.html')


@login_required(login_url='/login')
def users_list(request):
    # Sample users data
    users_data = [
        {'province': 'تهران', 'district': 'شهر ری', 'first_name': 'مایکل آ.', 'last_name': 'ماینر', 'national_code': '1234567890', 'executive_body': 'دستگاه اجرایی 1', 'work_group': 'گروه کاری 1', 'status': 'فعال'},
        {'province': 'اصفهان', 'district': 'اصفهان', 'first_name': 'ترزا تی.', 'last_name': 'برُس', 'national_code': '0987654321', 'executive_body': 'دستگاه اجرایی 2', 'work_group': 'گروه کاری 2', 'status': 'غیرفعال'},
        {'province': 'شیراز', 'district': 'شیراز', 'first_name': 'جیمز ال.', 'last_name': 'اریکسون', 'national_code': '1231231234', 'executive_body': 'دستگاه اجرایی 3', 'work_group': 'گروه کاری 3', 'status': 'فعال'},
        {'province': 'کرج', 'district': 'کرج', 'first_name': 'لیلی و.', 'last_name': 'ویلسون', 'national_code': '4321432143', 'executive_body': 'دستگاه اجرایی 4', 'work_group': 'گروه کاری 4', 'status': 'غیرفعال'},
        {'province': 'تبریز', 'district': 'تبریز', 'first_name': 'سارا م.', 'last_name': 'بروکس', 'national_code': '8765876587', 'executive_body': 'دستگاه اجرایی 5', 'work_group': 'گروه کاری 5', 'status': 'غیرفعال'},
        {'province': 'مشهد', 'district': 'مشهد', 'first_name': 'جو ک.', 'last_name': 'هال', 'national_code': '6547654765', 'executive_body': 'دستگاه اجرایی 6', 'work_group': 'گروه کاری 6', 'status': 'فعال'},
        {'province': 'یزد', 'district': 'یزد', 'first_name': 'رالف', 'last_name': 'هویبر', 'national_code': '3214321432', 'executive_body': 'دستگاه اجرایی 7', 'work_group': 'گروه کاری 7', 'status': 'فعال'},
        {'province': 'کرمان', 'district': 'کرمان', 'first_name': 'سارا', 'last_name': 'درشر', 'national_code': '9081708170', 'executive_body': 'دستگاه اجرایی 8', 'work_group': 'گروه کاری 8', 'status': 'فعال'},
        {'province': 'رشت', 'district': 'رشت', 'first_name': 'لئونی', 'last_name': 'مایستر', 'national_code': '9876987698', 'executive_body': 'دستگاه اجرایی 9', 'work_group': 'گروه کاری 9', 'status': 'فعال'},
    ]

    # Buttons for the toolbar
    buttons = [
        {'text': 'راهنما', 'title': 'راهنما: جهت مشاهده ی راهنما (پنجره ی فعلی) از این دکمه استفاده نمایید.', 'url': '#', 'icon': 'solar:question-circle-broken'},
        {'text': 'ثبت رکورد جدید', 'title': 'ثبت رکورد جدید: جهت ثبت یک رکورد جدید و افزودن آن به این لیست از این دکمه استفاده کنید. با کلیک روی این دکمه به فرم مربوطه هدایت می شوید.', 'url': '#', 'icon': 'solar:add-circle-broken'},
        {'text': 'پرینت', 'title': 'پرینت: جهت چاپ تمام اطلاعات موجود در گرید از این دکمه استفاده کنید. بنابراین با فیلتر اطلاعات گرید، اطلاعات فیلتر شده را میتوان پرینت گرفت.', 'url': '#', 'icon': 'solar:printer-bold'},
        {'text': 'حذف چندتایی', 'title': 'حذف چندتایی: در صورت تمایل به حذف چندتایی رکورد ها، ابتدا رکوردهای مورد نظر را در گرید مارک دار نمایید و سپس از این دکمه برای حذف استفاده نمایید.', 'url': '#', 'icon': 'solar:trash-bin-minimalistic-2-broken'},
        {'text': 'خروجی', 'title': 'خروجی: جهت تهیه ی خروجی اکسل و یا پی دی اف از این دکمه و انتخاب نوع خروجی مورد نظر از لیست باز شده، استفاده نمایید.', 'url': '#', 'icon': 'solar:export-bold' },
        {'text': 'تنظیمات', 'title': 'تنظیمات: جهت تنظیمات دلخواه گرید مانند: نمایش یا عدم نمایش ستونها، رنگ بندی، تعداد رکوردهای قابل نمایش در صفحه و.. از این دکمه استفاده نمایید.', 'url': '#', 'icon': 'solar:settings-outline'},
    ]
    
    # Paginator setup: 10 users per page
    paginator = Paginator(users_data, 6)
    
    # Get current page number from the request (defaults to 1)
    page_number = request.GET.get('page')
    page_obj = paginator.get_page(page_number)
    
    # Define the filter dropdown options dynamically
    filters = [
        {'id': 'province', 'label': 'استان', 'options': ['استان 1', 'استان 2', 'استان 3','استان 1', 'استان 2', 'استان 3']},
        {'id': 'district', 'label': 'شهرستان', 'options': ['شهرستان 1', 'شهرستان 2', 'شهرستان 3','شهرستان 1', 'شهرستان 2', 'شهرستان 3']},
        {'id': 'executive_body', 'label': 'دستگاه اجرایی', 'options': ['اتحادیه ماکیان شرق', 'اداره راه شهرستان','اتحادیه ماکیان شرق', 'اداره راه شهرستان']},
        {'id': 'work_group', 'label': 'گروه کاری', 'options': ['ادمین', 'پذیرش']},
        {'id': 'status', 'label': 'وضعیت', 'options': ['فعال', 'غیرفعال']},
    ]

    # Render the template with dynamic data
    return render(request, 'dashboard/users/user_list.html', {
        'users_data': users_data,
        'buttons': buttons,
        'page_obj': page_obj,
        'filters': filters,  # Pass dynamic filter data to the template
    })
    


def accounts_list(request):
    # Sample data for demonstration
    accounts = [
        {
            'account_type': 'رسمی',
            'account_number': '1234567890',
            'card_number': '1234-5678-9012-3456',
            'iban': 'IR123456789012345678901234',
            'creation_date': '2024-01-01',
            'edit_date': '2024-01-15',
        },
        {
            'account_type': 'رسمی',
            'account_number': '1234567890',
            'card_number': '1234-5678-9012-3456',
            'iban': 'IR123456789012345678901234',
            'creation_date': '2024-01-01',
            'edit_date': '2024-01-15',
        },
        {
            'account_type': 'غیر رسمی',
            'account_number': '1234567890',
            'card_number': '1234-5678-9012-3456',
            'iban': 'IR123456789012345678901234',
            'creation_date': '2024-01-01',
            'edit_date': '2024-01-15',
        },
        {
            'account_type': 'رسمی',
            'account_number': '1234567890',
            'card_number': '1234-5678-9012-3456',
            'iban': 'IR123456789012345678901234',
            'creation_date': '2024-01-01',
            'edit_date': '2024-01-15',
        },
        {
            'account_type': 'رسمی',
            'account_number': '1234567890',
            'card_number': '1234-5678-9012-3456',
            'iban': 'IR123456789012345678901234',
            'creation_date': '2024-01-01',
            'edit_date': '2024-01-15',
            'status': 'غیر فعال',
        },
        {
            'account_type': 'رسمی',
            'account_number': '1234567890',
            'card_number': '1234-5678-9012-3456',
            'iban': 'IR123456789012345678901234',
            'creation_date': '2024-01-01',
            'edit_date': '2024-01-15',
        },
        # Add more accounts here
    ]
    buttons = [
        {'text': 'راهنما', 'title': 'راهنما: جهت مشاهده ی راهنما (پنجره ی فعلی) از این دکمه استفاده نمایید.', 'url': '#', 'icon': 'solar:question-circle-broken'},
        {'text': 'ثبت رکورد جدید', 'title': 'ثبت رکورد جدید: جهت ثبت یک رکورد جدید و افزودن آن به این لیست از این دکمه استفاده کنید. با کلیک روی این دکمه به فرم مربوطه هدایت می شوید.', 'url': '#', 'icon': 'solar:add-circle-broken'},
        {'text': 'پرینت', 'title': 'پرینت: جهت چاپ تمام اطلاعات موجود در گرید از این دکمه استفاده کنید. بنابراین با فیلتر اطلاعات گرید، اطلاعات فیلتر شده را میتوان پرینت گرفت.', 'url': '#', 'icon': 'solar:printer-bold'},
        {'text': 'حذف چندتایی', 'title': 'حذف چندتایی: در صورت تمایل به حذف چندتایی رکورد ها، ابتدا رکوردهای مورد نظر را در گرید مارک دار نمایید و سپس از این دکمه برای حذف استفاده نمایید.', 'url': '#', 'icon': 'solar:trash-bin-minimalistic-2-broken'},
        {'text': 'خروجی', 'title': 'خروجی: جهت تهیه ی خروجی اکسل و یا پی دی اف از این دکمه و انتخاب نوع خروجی مورد نظر از لیست باز شده، استفاده نمایید.', 'url': '#', 'icon': 'solar:export-bold' },
        {'text': 'تنظیمات', 'title': 'تنظیمات: جهت تنظیمات دلخواه گرید مانند: نمایش یا عدم نمایش ستونها، رنگ بندی، تعداد رکوردهای قابل نمایش در صفحه و.. از این دکمه استفاده نمایید.', 'url': '#', 'icon': 'solar:settings-outline'},
    ]
    # Pagination
    paginator = Paginator(accounts, 5)  # Show 10 accounts per page
    page_number = request.GET.get('page')
    page_obj = paginator.get_page(page_number)

    return render(request, 'dashboard/users/accounts_list.html', {'accounts': page_obj.object_list, 'page_obj': page_obj, 'buttons':buttons})