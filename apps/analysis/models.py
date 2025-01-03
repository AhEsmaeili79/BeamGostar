from django.db import models
from apps.personnel.models import Personnel
from utils.utils import get_persian_datetime
from apps.users.models import User
persian_date, persian_time ,now = get_persian_datetime()
datetime = [persian_date, persian_time]

class Analyze(models.Model):
    id = models.IntegerField(primary_key=True, verbose_name='کد')  # auto-incrementing primary key
    title = models.CharField(max_length=250, verbose_name='عنوان آنالیز')
    state =  models.BooleanField(default=True, verbose_name='وضعیت ' )
    created_at = models.DateTimeField(default=datetime, null=True, blank=True)
    updated_at = models.DateTimeField(default=datetime, null=True, blank=True)
    deleted_at = models.DateTimeField(null=True, blank=True)
    is_deleted = models.BooleanField(default=False)
    class Meta:
        db_table = 'analyze'
    
    def __str__(self):
        return self.title
    
    def save(self, *args, **kwargs):
        if self.is_deleted:
            self.state = False
        self.id = self.id or (Analyze.objects.aggregate(models.Max('id'))['id__max'] or 0) + 1
        super().save(*args, **kwargs)

class LinkAnalysisPerson(models.Model):
    id = models.IntegerField(primary_key=True, verbose_name='کد')  # auto-incrementing primary key
    personnel = models.ForeignKey(Personnel, on_delete=models.CASCADE, related_name='link_analysis_persons', verbose_name='نام فرد')
    analyze = models.ForeignKey(Analyze, on_delete=models.CASCADE, related_name='link_analysis_persons', verbose_name='آنالیز')
    date = models.DateField(default=persian_date, verbose_name='تاریخ ثبت')  # Automatically sets to current date
    time = models.TimeField(default=persian_time, verbose_name='زمان ثبت')  # Automatically sets to current time
    created_at = models.DateTimeField(default=datetime, null=True, blank=True)
    updated_at = models.DateTimeField(default=datetime, null=True, blank=True)
    deleted_at = models.DateTimeField(null=True, blank=True)
    is_deleted = models.BooleanField(default=False)
    
    class Meta:
        db_table = 'link_analysis_persons'

    def __str__(self):
        return f"{self.personnel} - {self.analyze}"
    
    def save(self, *args, **kwargs):
        if self.is_deleted:
            self.state = False
        self.id = self.id or (LinkAnalysisPerson.objects.aggregate(models.Max('id'))['id__max'] or 0) + 1
        super().save(*args, **kwargs)

class GetAnswer(models.Model):
    id = models.IntegerField(primary_key=True, verbose_name='کد')
    title = models.CharField(max_length=200, verbose_name='عنوان')
    state = models.BooleanField(default=True, verbose_name='وضعیت ' )
    created_at = models.DateTimeField(default=datetime, verbose_name='زمان ایجاد')
    updated_at = models.DateTimeField(default=datetime, verbose_name='زمان بروزرسانی')
    deleted_at = models.DateTimeField(null=True, blank=True, verbose_name='زمان حذف')
    is_deleted = models.BooleanField(default=False)
    
    class Meta:
        db_table = 'get_answers'  # This specifies the table name

    def __str__(self):
        return self.title

    def save(self, *args, **kwargs):
        if self.is_deleted:
            self.state = False
        self.id = self.id or (GetAnswer.objects.aggregate(models.Max('id'))['id__max'] or 0) + 1
        super().save(*args, **kwargs)
        
    def delete(self, using=None, keep_parents=False):
        self.is_deleted = True
        self.save()
        # Or you can call the signal manually here if needed
        super(User, self).delete(using=using, keep_parents=keep_parents)