# models.py
from django.db import models
from apps.analysis.models import Analyze
from utils.utils import get_persian_datetime

persian_date, persian_time ,now = get_persian_datetime()
datetime = [persian_date, persian_time]

class PaymentMethod(models.Model):
    id = models.IntegerField(primary_key=True, verbose_name='کد')
    title = models.CharField(max_length=200, verbose_name='عنوان')
    state =  models.BooleanField(default=True, verbose_name='وضعیت ' )
    created_at = models.DateTimeField(default=datetime, verbose_name='تاریخ ایجاد')
    updated_at = models.DateTimeField(default=datetime, verbose_name='تاریخ ویرایش')
    deleted_at = models.DateTimeField(null=True, blank=True, verbose_name='تاریخ حذف')
    is_deleted = models.BooleanField(default=False, help_text="Indicates if the record is soft-deleted")
        
    class Meta:
        db_table = 'payment_method'

    def __str__(self):
        return self.title

    def save(self, *args, **kwargs):
        if self.is_deleted:
            self.state = False
        self.id = self.id or (PaymentMethod.objects.aggregate(models.Max('id'))['id__max'] or 0) + 1
        super().save(*args, **kwargs)


class Customer(models.Model):
    REAL = 1
    LEGAL = 2
    CUSTOMER_TYPE_CHOICES = [
        (REAL, 'حقیقی'),
        (LEGAL, 'حقوقی'),
    ]
    
    CASH = 1
    CREDIT = 2
    CLEARING_TYPE_CHOICES = [
        (CASH, 'نقدی'),
        (CREDIT, 'اعتباری'),
    ]
    
    IRANIAN = 1
    FOREIGN = 2
    NATIONALITY_CHOICES = [
        (IRANIAN, 'ایرانی'),
        (FOREIGN, 'خارجی'),
    ]
    
    id = models.IntegerField(primary_key=True, verbose_name='کد')
    customer_type = models.PositiveSmallIntegerField(choices=CUSTOMER_TYPE_CHOICES, verbose_name='نوع مشتری')
    clearing_type = models.PositiveSmallIntegerField(choices=CLEARING_TYPE_CHOICES, verbose_name='نوع تسویه')
    nationality = models.PositiveSmallIntegerField(choices=NATIONALITY_CHOICES, verbose_name='تابعیت')
    national_code = models.CharField(max_length=10, null=True, blank=True, verbose_name='کد ملی')
    national_id = models.CharField(max_length=15, null=True, blank=True, verbose_name='شناسه ملی')
    passport = models.CharField(max_length=10, null=True, blank=True)
    economy_code = models.CharField(max_length=15, null=True, blank=True, verbose_name='کد اقتصادی')
    name_fa = models.CharField(max_length=40, verbose_name='نام (فارسی)')
    family_fa = models.CharField(max_length=40, verbose_name='نام خانوادگی (فارسی)')
    name_en = models.CharField(max_length=40, null=True, blank=True, verbose_name='نام (انگلیسی)')
    family_en = models.CharField(max_length=40, null=True, blank=True, verbose_name='نام خانوادگی (انگلیسی)')
    birth_date = models.DateField(null=True, blank=True, verbose_name='تاریخ تولد')
    company_fa = models.CharField(max_length=255, null=True, blank=True, verbose_name='نام شرکت (فارسی)')
    company_en = models.CharField(max_length=255, null=True, blank=True, verbose_name='نام شرکت (انگلیسی)')
    mobile = models.CharField(max_length=11, verbose_name='شماره همراه')
    phone = models.CharField(max_length=11, null=True, blank=True, verbose_name='شماره تماس شرکت')
    password = models.CharField(max_length=255, null=True, blank=True, verbose_name='رمز عبور')
    re_password = models.CharField(max_length=255, null=True, blank=True, verbose_name='تکرار رمز عبور')
    email = models.EmailField(max_length=170, null=True, blank=True, verbose_name='پست الکترونیک')
    postal_code = models.CharField(max_length=10, null=True, blank=True, verbose_name='کد پستی')
    address = models.CharField(max_length=255, null=True, blank=True, verbose_name='آدرس')
    state =  models.BooleanField(default=True, verbose_name='وضعیت')
    created_at = models.DateTimeField(default=datetime)
    updated_at = models.DateTimeField(default=datetime)
    deleted_at = models.DateTimeField(null=True, blank=True)
    is_deleted = models.BooleanField(default=False, help_text="Indicates if the record is soft-deleted")
        

    class Meta:
        db_table = 'customers'
        ordering = ['created_at']
        
        
    def __str__(self):
        return self.name_fa + ' ' + self.family_fa
        
    def save(self, *args, **kwargs):
        if self.is_deleted:
            self.state = False
        self.id = self.id or (Customer.objects.aggregate(models.Max('id'))['id__max'] or 0) + 1
        super().save(*args, **kwargs)


class PriceAnalysis(models.Model):
    id = models.AutoField(primary_key=True, verbose_name='کد')
    analyze_id = models.ForeignKey(Analyze, on_delete=models.CASCADE, verbose_name='آنالیز')
    price = models.CharField(max_length=10, verbose_name='قیمت(ریال)')
    date = models.DateField(default=persian_date, null=True, blank=True, verbose_name='تاریخ ثبت')
    time = models.TimeField(default=persian_time ,null=True, blank=True, verbose_name='زمان ثبت')
    state =  models.BooleanField(default=True, verbose_name='وضعیت')
    created_at = models.DateTimeField(default=datetime)
    updated_at = models.DateTimeField(default=datetime)
    deleted_at = models.DateTimeField(null=True, blank=True)
    is_deleted = models.BooleanField(default=False, help_text="Indicates if the record is soft-deleted")
        

    class Meta:
        db_table = 'price_analysis'

    def __str__(self):
        return f"آنالیز {self.analyze_id} قیمت {self.price}"

    def save(self, *args, **kwargs):
        if self.is_deleted:
            self.state = False
        self.id = self.id or (PriceAnalysis.objects.aggregate(models.Max('id'))['id__max'] or 0) + 1
        super().save(*args, **kwargs)
    
class PriceAnalysisCredit(models.Model):
    id = models.AutoField(primary_key=True, verbose_name='کد')
    analyze_id = models.ForeignKey(Analyze, on_delete=models.CASCADE, verbose_name='آنالیز')
    customers_id = models.ForeignKey(Customer, on_delete=models.CASCADE, verbose_name='مشتریان')
    price = models.CharField(max_length=10, verbose_name='قیمت(ریال)')
    date = models.DateField(default=persian_date,null=True, blank=True, verbose_name='تاریخ ثبت')
    time = models.TimeField(default=persian_time,null=True, blank=True, verbose_name='زمان ثبت')
    state =  models.BooleanField(default=True, verbose_name='وضعیت')
    created_at = models.DateTimeField(default=datetime)
    updated_at = models.DateTimeField(default=datetime)
    deleted_at = models.DateTimeField(null=True, blank=True)
    is_deleted = models.BooleanField(default=False, help_text="Indicates if the record is soft-deleted")
        

    class Meta:
        db_table = 'price_analysis_credit'
    
    def __str__(self):
        return f"مشتری {self.customers_id.name_fa} قیمت {self.price}"

    def save(self, *args, **kwargs):
        if self.is_deleted:
            self.state = False
        self.id = self.id or (PriceAnalysisCredit.objects.aggregate(models.Max('id'))['id__max'] or 0) + 1
        super().save(*args, **kwargs)