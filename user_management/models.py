from django.db import models

# Create your models here.
from django.db import models
from django.contrib.auth.models import AbstractUser
from django.utils.translation import gettext_lazy as _


# Define constants for gender choices, status, collaboration type, etc.
class Gender(models.TextChoices):
    MALE = 'M', _('مرد')
    FEMALE = 'F', _('زن')

class CollaborationType(models.TextChoices):
    PLATFORM_MANAGEMENT = 'platform_management', _('مدیریت پلتفرم')
    TECHNICAL_MANAGEMENT = 'technical_management', _('مدیریت فنی')
    FINANCIAL_MANAGEMENT = 'financial_management', _('مدیریت مالی')
    ADMISSION = 'admission', _('پذیرش')
    LABORATORY = 'laboratory', _('آزمایشگاه')
    RESPONSE = 'response', _('جوابدهی')
    CUSTOMERS = 'customers', _('مشتریان')

class UserStatus(models.TextChoices):
    ACTIVE = 'active', _('فعال')
    INACTIVE = 'inactive', _('غیرفعال')

class PhoneType(models.TextChoices):
    HOME = 'home', _('منزل')
    WORK = 'work', _('محل کار')
    MOBILE = 'mobile', _('موبایل')
    FAX = 'fax', _('فکس')

class UserType(models.TextChoices):
    CUSTOMER = 'customer', _('مشتری')
    ADMIN = 'admin', _('ادمین')

class WorkGroup(models.TextChoices):
    USER_MANAGEMENT = 'user_management', _('مدیریت کاربران')
    TECHNICAL_MANAGEMENT = 'technical_management', _('مدیریت فنی')
    ADMISSION = 'admission', _('پذیرش')
    FINANCE = 'finance', _('مالی')
    LABORATORY = 'laboratory', _('آزمایشگاه')
    RESPONSE = 'response', _('جوابدهی')
    CUSTOMERS = 'customers', _('مشتریان')


class User(AbstractUser):
    first_name = models.CharField(_('نام'), max_length=100)
    last_name = models.CharField(_('نام خانوادگی'), max_length=100)
    father_name = models.CharField(_('نام پدر'), max_length=100)
    gender = models.CharField(_('جنسیت'), max_length=1, choices=Gender.choices)
    national_code = models.CharField(_('کد ملی'), max_length=10, unique=True)
    birth_certificate_number = models.CharField(_('شماره شناسنامه'), max_length=20, unique=True)
    work_area = models.CharField(_('محدوده کاری'), max_length=255)
    
    # For provinces and cities, you'll need another model for their relationship
    province = models.CharField(_('استان'), max_length=100)
    city = models.CharField(_('شهرستان'), max_length=100)
    
    collaboration_type = models.CharField(_('نوع همکاری'), max_length=50, choices=CollaborationType.choices)
    status = models.CharField(_('وضعیت'), max_length=8, choices=UserStatus.choices)
    profile_picture = models.ImageField(_('عکس پرسنلی'), upload_to='profile_pictures/', null=True, blank=True)
    
    phone_type = models.CharField(_('نوع تلفن'), max_length=10, choices=PhoneType.choices)
    phone_number = models.CharField(_('شماره تلفن'), max_length=20)
    priority = models.IntegerField(_('اولویت'), default=0)
    
    description = models.TextField(_('توضیحات'), blank=True, null=True)
    username = models.CharField(_('نام کاربری'), max_length=150, unique=True)
    password = models.CharField(_('رمز ورود'), max_length=128)
    
    user_type = models.CharField(_('نوع کاربری'), max_length=10, choices=UserType.choices)
    work_group = models.CharField(_('گروه کاری'), max_length=50, choices=WorkGroup.choices)
    
    def __str__(self):
        return f'{self.first_name} {self.last_name} ({self.username})'

    class Meta:
        verbose_name = _('کاربر')
        verbose_name_plural = _('کاربران')
