from django.db import models
from apps.personnel.models import Personnel
from apps.miscellaneous.models import Menu
from django.contrib.auth.models import AbstractUser
from django.utils import timezone
from utils.utils import get_persian_datetime

persian_date, persian_time = get_persian_datetime()
datetime = [persian_date, persian_time]


# Abstract User model for extending Django's default User model
class User(AbstractUser):
    id = models.IntegerField(primary_key=True, verbose_name='کد')
    personnel_id = models.ForeignKey(Personnel, on_delete=models.SET_NULL, null=True, blank=True)
    register_date = models.DateField(default=persian_date)
    remember_token = models.CharField(max_length=100, null=True, blank=True)
    registrator_id = models.IntegerField(null=True, blank=True)
    accounttype_id = models.PositiveSmallIntegerField(null=True, blank=True)
    state = models.BooleanField(default=True, help_text="Active: 1, Inactive: 0")
    lastactive = models.DateTimeField(null=True, blank=True)
    is_deleted = models.BooleanField(default=False, help_text="Indicates if the record is soft-deleted")

    
    groups = models.ManyToManyField(
        'auth.Group', related_name='custom_user_set', blank=True)
    user_permissions = models.ManyToManyField(
        'auth.Permission', related_name='custom_user_permissions_set', blank=True)

    class Meta:
        db_table = 'user'
        
    def save(self, *args, **kwargs):
        if self.is_deleted:
            self.state = False
        self.id = self.id or (User.objects.aggregate(models.Max('id'))['id__max'] or 0) + 1
        super().save(*args, **kwargs)


# User Activity Model
class UserActivity(models.Model):
    ACTIONS = (
        ('added', 'Added'),
        ('updated', 'Updated'),
        ('deleted', 'Deleted'),
        ('logged_in', 'Logged In'),
        ('logged_out', 'Logged Out'),
    )

    id = models.AutoField(primary_key=True)
    user = models.ForeignKey(User, on_delete=models.PROTECT, null=True)
    item_name = models.CharField(max_length=255)
    action_type = models.CharField(max_length=50, choices=ACTIONS)
    date = models.DateTimeField(default=timezone.now)
    model_name = models.CharField(max_length=255)
    is_deleted = models.BooleanField(default=False, help_text="Indicates if the record is soft-deleted")

    class Meta:
        db_table = 'useractivity'
        ordering = ['-date']

    def save(self, *args, **kwargs):
        self.id = self.id or (UserActivity.objects.aggregate(models.Max('id'))['id__max'] or 0) + 1
        super().save(*args, **kwargs)

    def __str__(self):
        return f"{self.user.username} {self.action_type} on {self.model_name} at {self.date}"


# Auth Assignment Model (Roles/Permissions assignment)
class AuthAssignment(models.Model):
    id = models.IntegerField(primary_key=True, verbose_name='کد')
    item_name = models.CharField(max_length=64)
    user_id = models.ForeignKey(User, on_delete=models.CASCADE)
    created_at = models.IntegerField(default=datetime,null=True, blank=True)
    is_deleted = models.BooleanField(default=False, help_text="Indicates if the record is soft-deleted")


    class Meta:
        db_table = 'auth_assignment'
        
    def save(self, *args, **kwargs):
        self.id = self.id or (AuthAssignment.objects.aggregate(models.Max('id'))['id__max'] or 0) + 1
        super().save(*args, **kwargs)


# Auth Item Model (Roles/Permissions)
class AuthItem(models.Model):
    id = models.IntegerField(primary_key=True, verbose_name='کد')
    name = models.CharField(max_length=64)
    type = models.IntegerField()
    description = models.TextField(null=True, blank=True)
    rule_name = models.CharField(max_length=64, null=True, blank=True)
    data = models.TextField(null=True, blank=True)
    created_at = models.IntegerField(default=datetime,null=True, blank=True)
    updated_at = models.IntegerField(default=datetime,null=True, blank=True)
    is_deleted = models.BooleanField(default=False, help_text="Indicates if the record is soft-deleted")

        
    class Meta:
        db_table = 'auth_item'
        
    def save(self, *args, **kwargs):
        self.id = self.id or (AuthItem.objects.aggregate(models.Max('id'))['id__max'] or 0) + 1
        super().save(*args, **kwargs)


# Auth Item Child Model (Mapping Parent-Child roles/permissions)
class AuthItemChild(models.Model):
    id = models.IntegerField(primary_key=True, verbose_name='کد')
    parent = models.ForeignKey(AuthItem, related_name='parent_items', on_delete=models.CASCADE)
    child = models.ForeignKey(AuthItem, related_name='child_items', on_delete=models.CASCADE)
    updated_at = models.DateTimeField(default=datetime)
    is_deleted = models.BooleanField(default=False, help_text="Indicates if the record is soft-deleted")

        
    class Meta:
        db_table = 'auth_item_child'
        
    def save(self, *args, **kwargs):
        self.id = self.id or (AuthItemChild.objects.aggregate(models.Max('id'))['id__max'] or 0) + 1
        super().save(*args, **kwargs)


# Auth Menu Model (Menus for roles/permissions)
class AuthMenu(models.Model):
    id = models.IntegerField(primary_key=True, verbose_name='کد')
    item_name = models.CharField(max_length=64)
    menu = models.ForeignKey(Menu, on_delete=models.CASCADE)  # Corrected reference to Menu model
    updated_at = models.DateTimeField(default=datetime)
    is_deleted = models.BooleanField(default=False, help_text="Indicates if the record is soft-deleted")


    class Meta:
        db_table = 'auth_menu'
        
    def save(self, *args, **kwargs):
        self.id = self.id or (AuthMenu.objects.aggregate(models.Max('id'))['id__max'] or 0) + 1
        super().save(*args, **kwargs)


# Auth Rule Model (Custom rules for roles/permissions)
class AuthRule(models.Model):
    id = models.IntegerField(primary_key=True, verbose_name='کد')
    name = models.CharField(max_length=64)
    data = models.TextField(null=True, blank=True)
    created_at = models.IntegerField(default=datetime,null=True, blank=True)
    updated_at = models.IntegerField(default=datetime,null=True, blank=True)
    is_deleted = models.BooleanField(default=False, help_text="Indicates if the record is soft-deleted")

        
    class Meta:
        db_table = 'auth_rule'

    def save(self, *args, **kwargs):
        self.id = self.id or (AuthRule.objects.aggregate(models.Max('id'))['id__max'] or 0) + 1
        super().save(*args, **kwargs)



