# models.py
from django.db import models
from utils.utils import get_persian_datetime

persian_date, persian_time = get_persian_datetime()
datetime = [persian_date, persian_time]

class PaymentMethod(models.Model):
    
    title = models.CharField(max_length=200, verbose_name='عنوان')
    status =  models.BooleanField(default=True, verbose_name='وضعیت ' )
    created_at = models.DateTimeField(default=datetime, verbose_name='تاریخ ایجاد')
    updated_at = models.DateTimeField(default=datetime, verbose_name='تاریخ ویرایش')
    deleted_at = models.DateTimeField(null=True, blank=True, verbose_name='تاریخ حذف')

    class Meta:
        db_table = 'payment_method'

    def __str__(self):
        return self.title
