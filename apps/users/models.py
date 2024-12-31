from django.db import models
from apps.personnel.models import Personnel
from apps.miscellaneous.models import Menu
from django.contrib.auth.models import AbstractUser

from utils.utils import get_persian_datetime

persian_date, persian_time = get_persian_datetime()
datetime = [persian_date, persian_time]

# Abstract User model for extending Django's default User model
class User(AbstractUser):
    personnel = models.ForeignKey(Personnel, on_delete=models.SET_NULL, null=True, blank=True)
    reg_date = models.DateTimeField(default=datetime)
    remember_token = models.CharField(max_length=100, null=True, blank=True)
    registrator_id = models.IntegerField(null=True, blank=True)
    accounttype_id = models.PositiveSmallIntegerField()
    state = models.BooleanField(default=True, help_text="Active: 1, Inactive: 0")
    lastactive = models.DateTimeField(null=True, blank=True)
    
    groups = models.ManyToManyField(
        'auth.Group', related_name='custom_user_set', blank=True)
    user_permissions = models.ManyToManyField(
        'auth.Permission', related_name='custom_user_permissions_set', blank=True)

    class Meta:
        db_table = 'user'


# User Activity Model
class UserActivity(models.Model):
    user = models.ForeignKey(User, on_delete=models.CASCADE)
    item_name = models.CharField(max_length=64)
    date = models.DateTimeField(default=datetime)
    ip = models.CharField(max_length=15)
    lat = models.FloatField(null=True, blank=True)
    lng = models.FloatField(null=True, blank=True)
    record_id = models.IntegerField(null=True, blank=True)
    table_name = models.CharField(max_length=50, null=True, blank=True)
    
    class Meta:
        db_table = 'useractivity'


# Auth Assignment Model (Roles/Permissions assignment)
class AuthAssignment(models.Model):
    item_name = models.CharField(max_length=64)
    user = models.ForeignKey(User, on_delete=models.CASCADE)
    created_at = models.IntegerField(default=datetime,null=True, blank=True)

    class Meta:
        db_table = 'auth_assignment'


# Auth Item Model (Roles/Permissions)
class AuthItem(models.Model):
    name = models.CharField(max_length=64)
    type = models.IntegerField()
    description = models.TextField(null=True, blank=True)
    rule_name = models.CharField(max_length=64, null=True, blank=True)
    data = models.TextField(null=True, blank=True)
    created_at = models.IntegerField(default=datetime,null=True, blank=True)
    updated_at = models.IntegerField(default=datetime,null=True, blank=True)

    class Meta:
        db_table = 'auth_item'


# Auth Item Child Model (Mapping Parent-Child roles/permissions)
class AuthItemChild(models.Model):
    parent = models.ForeignKey(AuthItem, related_name='parent_items', on_delete=models.CASCADE)
    child = models.ForeignKey(AuthItem, related_name='child_items', on_delete=models.CASCADE)
    updated_at = models.DateTimeField(default=datetime)

    class Meta:
        db_table = 'auth_item_child'


# Auth Menu Model (Menus for roles/permissions)
class AuthMenu(models.Model):
    item_name = models.CharField(max_length=64)
    menu = models.ForeignKey(Menu, on_delete=models.CASCADE)  # Corrected reference to Menu model
    updated_at = models.DateTimeField(default=datetime)

    class Meta:
        db_table = 'auth_menu'


# Auth Rule Model (Custom rules for roles/permissions)
class AuthRule(models.Model):
    name = models.CharField(max_length=64)
    data = models.TextField(null=True, blank=True)
    created_at = models.IntegerField(default=datetime,null=True, blank=True)
    updated_at = models.IntegerField(default=datetime,null=True, blank=True)

    class Meta:
        db_table = 'auth_rule'




