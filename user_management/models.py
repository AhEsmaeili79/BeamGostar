from django.contrib.auth.models import AbstractUser, Group, Permission
from django.db import models
from django.utils import timezone
from menu.models import Menu

class Gender(models.TextChoices):
    MALE = 'M', 'مرد'  # Male in Persian
    FEMALE = 'F', 'زن'  # Female in Persian

class UserStatus(models.TextChoices):
    ACTIVE = 'active', 'فعال'  # Active in Persian
    INACTIVE = 'inactive', 'غیرفعال'  # Inactive in Persian

class PhoneType(models.TextChoices):
    HOME = 'home', 'منزل'  # Home in Persian
    WORK = 'work', 'محل کار'  # Work in Persian
    MOBILE = 'mobile', 'موبایل'  # Mobile in Persian
    FAX = 'fax', 'فکس'  # Fax in Persian


class UserRole(models.Model):
    name = models.CharField(max_length=100, unique=True, verbose_name='نام نقش')
    description = models.TextField(blank=True, verbose_name='توضیحات')
    status = models.CharField(max_length=10, choices=UserStatus.choices, default=UserStatus.ACTIVE, verbose_name='وضعیت')

    # Access permissions for menus
    menus = models.ManyToManyField(Menu, blank=True, verbose_name='دسترسی به منوها')

    # CRUD permissions
    can_create = models.BooleanField(default=False, verbose_name='ایجاد')
    can_retrieve = models.BooleanField(default=False, verbose_name='مشاهده')
    can_update = models.BooleanField(default=False, verbose_name='ویرایش')
    can_delete = models.BooleanField(default=False, verbose_name='حذف')
    can_upload = models.BooleanField(default=False, verbose_name='بارگذاری فایل')
    can_export = models.BooleanField(default=False, verbose_name='خروجی فایل')

    def __str__(self):
        return self.name

class User(AbstractUser):
    first_name = models.CharField(max_length=100, verbose_name='نام')
    last_name = models.CharField(max_length=100, verbose_name='نام خانوادگی')
    father_name = models.CharField(max_length=100, verbose_name='نام پدر')
    gender = models.CharField(max_length=1, choices=Gender.choices, verbose_name='جنسیت', default=Gender.MALE)
    national_id = models.CharField(max_length=10, unique=True, verbose_name="کد ملی")
    id_number = models.CharField(max_length=10, verbose_name='شماره شناسنامه')
    date_of_birth = models.DateField(verbose_name="تاریخ تولد")
    
    # Location-related fields
    province = models.CharField(max_length=100, verbose_name='استان')
    city = models.CharField(max_length=100, verbose_name='شهرستان')

    # Foreign key to UserRole for roles and work groups
    role = models.ForeignKey(UserRole, related_name='role_users', on_delete=models.SET_NULL, null=True, verbose_name='نوع همکاری')
    work_group = models.ForeignKey(UserRole, related_name='work_group_users', on_delete=models.SET_NULL, null=True, verbose_name='گروه کاری')

    # Job role and status fields
    status = models.CharField(max_length=10, choices=UserStatus.choices, verbose_name='وضعیت', default=UserStatus.ACTIVE)

    # Personal information
    profile_picture = models.ImageField(upload_to='profile_pics/', verbose_name='عکس پرسنلی', blank=True, null=True)
    phone_type = models.CharField(max_length=10, choices=PhoneType.choices, verbose_name='نوع تلفن')
    phone_number = models.CharField(max_length=15, verbose_name='تلفن')

    # User preferences
    priority = models.PositiveIntegerField(default=0, verbose_name='اولویت')
    username = models.CharField(max_length=150, unique=True, verbose_name='نام کاربری')
    password = models.CharField(max_length=128, verbose_name='رمز ورود')

    # User type (customer or admin)
    user_type = models.CharField(max_length=20, choices=[('customer', 'مشتری'), ('admin', 'ادمین')], verbose_name='نوع کاربری')

    # Additional notes or description
    description = models.TextField(blank=True, null=True, verbose_name='توضیحات')
    
    groups = models.ManyToManyField(Group, related_name="custom_user_groups", blank=True)
    user_permissions = models.ManyToManyField(Permission, related_name="custom_user_permissions", blank=True)
    date_joined = models.DateTimeField(auto_now_add=True, verbose_name="تاریخ پیوستن")
    created_at = models.DateTimeField(auto_now_add=True, verbose_name="تاریخ ایجاد")  # Automatically set when created
    deleted_at = models.DateTimeField(null=True, blank=True, verbose_name="تاریخ حذف")  # Will be set when soft deleted

    def __str__(self):
        return self.username
    
    def delete(self, using=None, keep_parents=False):
        # Soft delete: set `deleted_at`
        self.deleted_at = timezone.now()
        self.save()

    def restore(self):
        # Restore a soft-deleted user
        self.deleted_at = None
        self.save()

    class Meta:
        verbose_name = 'User'
        verbose_name_plural = 'Users'