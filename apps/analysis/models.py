from django.db import models
from apps.personnel.models import Personnel
from utils.utils import get_persian_datetime

persian_date, persian_time = get_persian_datetime()
datetime = [persian_date, persian_time]

class Analyze(models.Model):
    id = models.AutoField(primary_key=True)  # auto-incrementing primary key
    title = models.CharField(max_length=250, verbose_name='عنوان آنالیز')
    status =  models.BooleanField(default=True, verbose_name='وضعیت ' )
    created_at = models.DateTimeField(default=datetime, null=True, blank=True)
    updated_at = models.DateTimeField(default=datetime, null=True, blank=True)
    deleted_at = models.DateTimeField(null=True, blank=True)

    class Meta:
        db_table = 'analyze'
    
    def __str__(self):
        return self.title

class LinkAnalysisPerson(models.Model):
    id = models.AutoField(primary_key=True)  # auto-incrementing primary key
    personnel = models.ForeignKey(Personnel, on_delete=models.CASCADE, related_name='link_analysis_persons', verbose_name='نام فرد')
    analyze = models.ForeignKey(Analyze, on_delete=models.CASCADE, related_name='link_analysis_persons', verbose_name='آنالیز')
    date = models.DateField(default=persian_date, verbose_name='تاریخ ثبت')  # Automatically sets to current date
    time = models.TimeField(default=persian_time, verbose_name='زمان ثبت')  # Automatically sets to current time
    created_at = models.DateTimeField(default=datetime, null=True, blank=True)
    updated_at = models.DateTimeField(default=datetime, null=True, blank=True)
    deleted_at = models.DateTimeField(null=True, blank=True)

    class Meta:
        db_table = 'link_analysis_persons'

    def __str__(self):
        return f"{self.personnel} - {self.analyze}"

class GetAnswer(models.Model):
    title = models.CharField(max_length=200, verbose_name='عنوان')
    status = models.BooleanField(default=True, verbose_name='وضعیت ' )
    created_at = models.DateTimeField(default=datetime, verbose_name='زمان ایجاد')
    updated_at = models.DateTimeField(default=datetime, verbose_name='زمان بروزرسانی')
    deleted_at = models.DateTimeField(null=True, blank=True, verbose_name='زمان حذف')

    class Meta:
        db_table = 'get_answers'  # This specifies the table name

    def __str__(self):
        return self.title
